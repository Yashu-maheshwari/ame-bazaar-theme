<?php
/**
 * Google Business Profile Service and OAuth Manager for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ame_Bazaar_GBP_Service {

	/**
	 * Client ID Option Key.
	 */
	const CLIENT_ID_OPTION = 'ame_bazaar_gbp_client_id';

	/**
	 * Client Secret Option Key.
	 */
	const CLIENT_SECRET_OPTION = 'ame_bazaar_gbp_client_secret';

	/**
	 * Access Token Option Key.
	 */
	const ACCESS_TOKEN_OPTION = 'ame_bazaar_gbp_access_token';

	/**
	 * Refresh Token Option Key.
	 */
	const REFRESH_TOKEN_OPTION = 'ame_bazaar_gbp_refresh_token';

	/**
	 * Token Expires At Option Key.
	 */
	const TOKEN_EXPIRES_OPTION = 'ame_bazaar_gbp_token_expires';

	/**
	 * Log Option Key.
	 */
	const LOGS_OPTION = 'ame_bazaar_gbp_logs';

	/**
	 * Get Client ID.
	 */
	public static function get_client_id() {
		return get_option( self::CLIENT_ID_OPTION, '' );
	}

	/**
	 * Get Client Secret.
	 */
	public static function get_client_secret() {
		return get_option( self::CLIENT_SECRET_OPTION, '' );
	}

	/**
	 * Get Redirect URI.
	 */
	public static function get_redirect_uri() {
		return admin_url( 'admin.php?page=ame-google-reviews' );
	}

	/**
	 * Get Access Token.
	 */
	public static function get_access_token() {
		// Auto refresh if expired
		$expires = (int) get_option( self::TOKEN_EXPIRES_OPTION, 0 );
		if ( $expires > 0 && time() >= $expires ) {
			self::refresh_access_token();
		}
		return get_option( self::ACCESS_TOKEN_OPTION, '' );
	}

	/**
	 * Get Refresh Token.
	 */
	public static function get_refresh_token() {
		return get_option( self::REFRESH_TOKEN_OPTION, '' );
	}

	/**
	 * Generate Google OAuth Authorization URL.
	 */
	public static function get_auth_url() {
		$client_id = self::get_client_id();
		if ( ! $client_id ) {
			return '';
		}

		$params = array(
			'client_id'      => $client_id,
			'redirect_uri'   => self::get_redirect_uri(),
			'response_type'  => 'code',
			'scope'          => 'https://www.googleapis.com/auth/business.manage https://www.googleapis.com/auth/userinfo.profile',
			'access_type'    => 'offline',
			'prompt'         => 'consent',
		);

		return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query( $params );
	}

	/**
	 * Exchange Authorization Code for Access & Refresh Tokens.
	 */
	public static function exchange_code_for_token( $code ) {
		$client_id     = self::get_client_id();
		$client_secret = self::get_client_secret();
		if ( ! $client_id || ! $client_secret ) {
			self::log_error( 'Failed token exchange: Client ID or Secret is missing.' );
			return false;
		}

		$response = wp_remote_post( 'https://oauth2.googleapis.com/token', array(
			'body' => array(
				'code'          => $code,
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
				'redirect_uri'  => self::get_redirect_uri(),
				'grant_type'    => 'authorization_code',
			),
		) );

		if ( is_wp_error( $response ) ) {
			self::log_error( 'OAuth Token Exchange WP Error: ' . $response->get_error_message() );
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $body['error'] ) ) {
			self::log_error( 'OAuth Token Exchange API Error: ' . $body['error_description'] );
			return false;
		}

		if ( isset( $body['access_token'] ) ) {
			update_option( self::ACCESS_TOKEN_OPTION, $body['access_token'] );
			if ( isset( $body['refresh_token'] ) ) {
				update_option( self::REFRESH_TOKEN_OPTION, $body['refresh_token'] );
			}
			$expires_in = isset( $body['expires_in'] ) ? (int) $body['expires_in'] : 3600;
			update_option( self::TOKEN_EXPIRES_OPTION, time() + $expires_in );
			self::log_info( 'Successfully authenticated and stored tokens.' );
			return true;
		}

		return false;
	}

	/**
	 * Refresh access token using refresh token.
	 */
	public static function refresh_access_token() {
		$client_id     = self::get_client_id();
		$client_secret = self::get_client_secret();
		$refresh_token = self::get_refresh_token();

		if ( ! $client_id || ! $client_secret || ! $refresh_token ) {
			self::log_error( 'Cannot refresh token: Credentials or Refresh Token missing.' );
			return false;
		}

		$response = wp_remote_post( 'https://oauth2.googleapis.com/token', array(
			'body' => array(
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
				'refresh_token' => $refresh_token,
				'grant_type'    => 'refresh_token',
			),
		) );

		if ( is_wp_error( $response ) ) {
			self::log_error( 'Refresh token post error: ' . $response->get_error_message() );
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $body['error'] ) ) {
			self::log_error( 'Refresh token API error: ' . $body['error_description'] );
			return false;
		}

		if ( isset( $body['access_token'] ) ) {
			update_option( self::ACCESS_TOKEN_OPTION, $body['access_token'] );
			$expires_in = isset( $body['expires_in'] ) ? (int) $body['expires_in'] : 3600;
			update_option( self::TOKEN_EXPIRES_OPTION, time() + $expires_in );
			self::log_info( 'Successfully refreshed Google access token.' );
			return true;
		}

		return false;
	}

	/**
	 * Perform live synchronization from GBP API.
	 */
	public static function perform_sync() {
		$access_token = self::get_access_token();
		if ( ! $access_token ) {
			self::log_error( 'Synchronization failed: Unauthenticated.' );
			return false;
		}

		// 1. Fetch location details
		$location_id = get_option( 'ame_bazaar_gbp_location_id', '' );
		if ( ! $location_id ) {
			self::log_error( 'Sync failed: Google Business Profile Location ID not configured.' );
			return false;
		}

		// Request parameters to Google Business Profile API
		$url = 'https://mybusinessbusinessinformation.googleapis.com/v1/' . $location_id;
		$response = wp_remote_get( $url, array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $access_token,
				'Content-Type'  => 'application/json',
			),
		) );

		if ( is_wp_error( $response ) ) {
			self::log_error( 'Failed syncing location details: ' . $response->get_error_message() );
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $body['error'] ) ) {
			self::log_error( 'API error during sync: ' . $body['error']['message'] );
			return false;
		}

		// Process and sync metadata
		self::parse_and_update_gbp_data( $body );
		update_option( 'ame_bazaar_gbp_last_sync', time() );
		self::log_info( 'Google Business Profile details successfully synchronized.' );
		return true;
	}

	/**
	 * Parse payload and store into WordPress options.
	 */
	private static function parse_and_update_gbp_data( $data ) {
		if ( empty( $data ) ) {
			return;
		}

		// Store Name
		if ( ! empty( $data['title'] ) ) {
			update_option( 'ame_bazaar_store_name', sanitize_text_field( $data['title'] ) );
		}

		// Address
		if ( ! empty( $data['postalAddress'] ) ) {
			$address = $data['postalAddress'];
			$address_lines = isset( $address['addressLines'] ) ? implode( ', ', $address['addressLines'] ) : '';
			update_option( 'ame_bazaar_address', sanitize_text_field( $address_lines ) );
			if ( ! empty( $address['locality'] ) ) {
				update_option( 'ame_bazaar_city', sanitize_text_field( $address['locality'] ) );
			}
			if ( ! empty( $address['administrativeArea'] ) ) {
				update_option( 'ame_bazaar_state', sanitize_text_field( $address['administrativeArea'] ) );
			}
			if ( ! empty( $address['postalCode'] ) ) {
				update_option( 'ame_bazaar_postal_code', sanitize_text_field( $address['postalCode'] ) );
			}
		}

		// Phone
		if ( ! empty( $data['primaryPhone'] ) ) {
			update_option( 'ame_bazaar_phone', sanitize_text_field( $data['primaryPhone'] ) );
			update_option( 'ame_bazaar_whatsapp', sanitize_text_field( $data['primaryPhone'] ) );
		}

		// Lat/Lng
		if ( ! empty( $data['latlng'] ) ) {
			update_option( 'ame_bazaar_latitude', sanitize_text_field( $data['latlng']['latitude'] ) );
			update_option( 'ame_bazaar_longitude', sanitize_text_field( $data['latlng']['longitude'] ) );
		}

		// Categories
		if ( ! empty( $data['primaryCategory']['displayName'] ) ) {
			update_option( 'ame_bazaar_primary_category', sanitize_text_field( $data['primaryCategory']['displayName'] ) );
		}
	}

	/**
	 * Register automatic daily cron scheduler.
	 */
	public static function register_cron() {
		if ( ! wp_next_scheduled( 'ame_bazaar_gbp_daily_sync' ) ) {
			wp_schedule_event( time(), 'daily', 'ame_bazaar_gbp_daily_sync' );
		}
	}

	/**
	 * Log info message.
	 */
	public static function log_info( $message ) {
		self::add_log_entry( 'INFO', $message );
	}

	/**
	 * Log error message.
	 */
	public static function log_error( $message ) {
		self::add_log_entry( 'ERROR', $message );
	}

	/**
	 * Append entry to log stack.
	 */
	private static function add_log_entry( $level, $message ) {
		$logs = get_option( self::LOGS_OPTION, array() );
		if ( ! is_array( $logs ) ) {
			$logs = array();
		}
		$logs[] = array(
			'timestamp' => current_time( 'mysql' ),
			'level'     => $level,
			'message'   => $message,
		);
		// Keep last 100 entries
		if ( count( $logs ) > 100 ) {
			array_shift( $logs );
		}
		update_option( self::LOGS_OPTION, $logs );
	}

	/**
	 * Retrieve log history.
	 */
	public static function get_logs() {
		return get_option( self::LOGS_OPTION, array() );
	}

	/**
	 * Get Connection Health Status.
	 */
	public static function get_health_status() {
		if ( ! self::get_client_id() || ! self::get_client_secret() ) {
			return array(
				'status'      => 'Unconfigured',
				'class'       => 'notice-warning',
				'description' => __( 'OAuth App client details are missing.', 'ame-bazaar' ),
			);
		}
		$token = get_option( self::ACCESS_TOKEN_OPTION );
		if ( ! $token ) {
			return array(
				'status'      => 'Disconnected',
				'class'       => 'notice-error',
				'description' => __( 'Authorized credentials do not exist. Please connect your Google account.', 'ame-bazaar' ),
			);
		}

		$expires = (int) get_option( self::TOKEN_EXPIRES_OPTION, 0 );
		if ( $expires > 0 && time() >= $expires && ! self::get_refresh_token() ) {
			return array(
				'status'      => 'Expired',
				'class'       => 'notice-error',
				'description' => __( 'Access token has expired and no refresh token exists. Please authorize again.', 'ame-bazaar' ),
			);
		}

		return array(
			'status'      => 'Connected',
			'class'       => 'notice-success',
			'description' => sprintf( __( 'Connected successfully. Token expires in %s.', 'ame-bazaar' ), human_time_diff( time(), $expires ) ),
		);
	}
}

// Hook Cron Sync Callback
add_action( 'ame_bazaar_gbp_daily_sync', array( 'Ame_Bazaar_GBP_Service', 'perform_sync' ) );
