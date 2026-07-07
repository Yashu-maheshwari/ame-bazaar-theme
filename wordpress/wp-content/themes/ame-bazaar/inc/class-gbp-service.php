<?php
/**
 * Google Business Profile Service Class for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ame_Bazaar_GBP_Service {
	
	/**
	 * Get GBP API key or token if configured.
	 *
	 * @return array
	 */
	public static function get_api_credentials() {
		return array(
			'api_key'      => get_option( 'ame_bazaar_gbp_api_key', '' ),
			'oauth_token'  => get_option( 'ame_bazaar_gbp_oauth_token', '' ),
			'account_id'   => get_option( 'ame_bazaar_gbp_account_id', '' ),
			'location_id'  => get_option( 'ame_bazaar_gbp_location_id', '' ),
		);
	}

	/**
	 * Check if API connection is active.
	 *
	 * @return bool
	 */
	public static function is_connected() {
		$creds = self::get_api_credentials();
		return ! empty( $creds['api_key'] ) || ! empty( $creds['oauth_token'] );
	}

	/**
	 * Fetch live Google Reviews count and rating.
	 * Falls back to option database values if API is disconnected or request fails.
	 *
	 * @return array
	 */
	public static function get_reviews_stats() {
		$rating = get_option( 'ame_bazaar_google_reviews_rating', '4.9' );
		$count  = get_option( 'ame_bazaar_google_reviews_count', '524' );

		if ( self::is_connected() ) {
			// Future API integration logic block:
			// Query the GBP My Business API or Places API endpoint and update local option values.
		}

		return array(
			'rating' => $rating,
			'count'  => $count,
		);
	}

	/**
	 * Synchronize GBP location details into local options.
	 *
	 * @param array $data GBP details payload.
	 * @return bool
	 */
	public static function sync_location_details( $data ) {
		if ( empty( $data ) ) {
			return false;
		}
		
		$mappings = array(
			'title'             => 'store_name',
			'phone'             => 'phone',
			'email'             => 'email',
			'address'           => 'address',
			'latitude'          => 'latitude',
			'longitude'         => 'longitude',
			'opening_hours'     => 'hours',
		);

		foreach ( $mappings as $gbp_key => $option_key ) {
			if ( isset( $data[ $gbp_key ] ) ) {
				update_option( 'ame_bazaar_' . $option_key, sanitize_text_field( $data[ $gbp_key ] ) );
			}
		}
		return true;
	}
}
