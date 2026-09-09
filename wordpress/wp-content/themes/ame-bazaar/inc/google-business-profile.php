<?php
/**
 * Google Business Profile Service for AME Bazaar.
 * 
 * Securely fetches and caches live Google reviews and ratings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main function to retrieve cached GBP summary.
 * 
 * @return array|false Returns ['rating' => float, 'review_count' => int] or false if unavailable.
 */
function ame_bazaar_get_gbp_summary() {
	$cached = get_transient( 'ame_bazaar_gbp_data' );
	if ( false !== $cached && is_array( $cached ) && isset( $cached['rating'] ) ) {
		return $cached;
	}
	
	// If no cache, trigger an immediate synchronous fetch for the first run.
	return ame_bazaar_refresh_gbp_data();
}

/**
 * Fetches fresh data from Google Business Profile API and updates cache.
 * Can be triggered via WP-Cron.
 */
function ame_bazaar_refresh_gbp_data() {
	$secrets_path = '/home/u473080180/domains/amebazaar.in/ame-gbp-secrets.php';
	
	if ( ! file_exists( $secrets_path ) ) {
		return false;
	}
	
	$secrets = include $secrets_path;
	if ( empty( $secrets['client_id'] ) || empty( $secrets['refresh_token'] ) ) {
		return false;
	}
	
	// 1. Fetch Access Token
	$token_url = 'https://oauth2.googleapis.com/token';
	$response = wp_remote_post( $token_url, [
		'body' => [
			'client_id'     => $secrets['client_id'],
			'client_secret' => $secrets['client_secret'],
			'refresh_token' => $secrets['refresh_token'],
			'grant_type'    => 'refresh_token',
		],
		'timeout' => 15,
	] );
	
	if ( is_wp_error( $response ) ) {
		return false;
	}
	
	$token_data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $token_data['access_token'] ) ) {
		return false;
	}
	
	$access_token = $token_data['access_token'];
	
	$accounts_resp = wp_remote_get( 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts', [
		'headers' => [ 'Authorization' => 'Bearer ' . $access_token ],
		'timeout' => 15,
	] );
	
	if ( is_wp_error( $accounts_resp ) ) {
		return false;
	}
	
	$accounts_data = json_decode( wp_remote_retrieve_body( $accounts_resp ), true );
	if ( empty( $accounts_data['accounts'] ) ) {
		return false;
	}
	
	// Find the matching account and location
	foreach ( $accounts_data['accounts'] as $acc ) {
		$locations_resp = wp_remote_get( 'https://mybusinessbusinessinformation.googleapis.com/v1/' . $acc['name'] . '/locations?readMask=name,title', [
			'headers' => [ 'Authorization' => 'Bearer ' . $access_token ],
			'timeout' => 15,
		] );
		
		if ( ! is_wp_error( $locations_resp ) ) {
			$locations_data = json_decode( wp_remote_retrieve_body( $locations_resp ), true );
			if ( ! empty( $locations_data['locations'] ) ) {
				foreach ( $locations_data['locations'] as $loc ) {
					if ( stripos( $loc['title'], 'AME Bazaar - Family Garment Store' ) !== false || stripos( $loc['title'], 'AME Bazaar' ) !== false ) {
						
						// Verify it has reviews before committing
						$v4_loc  = str_replace( 'locations/', '', $loc['name'] );
						$v4_acc  = str_replace( 'accounts/', '', $acc['name'] );
						$rev_url = "https://mybusiness.googleapis.com/v4/accounts/{$v4_acc}/locations/{$v4_loc}/reviews";
						
						$rev_resp = wp_remote_get( $rev_url, [
							'headers' => [ 'Authorization' => 'Bearer ' . $access_token ],
							'timeout' => 15,
						] );
						
						if ( ! is_wp_error( $rev_resp ) ) {
							$rev_data = json_decode( wp_remote_retrieve_body( $rev_resp ), true );
							if ( isset( $rev_data['averageRating'] ) && isset( $rev_data['totalReviewCount'] ) ) {
								
								$gbp_data = [
									'rating'       => (float) $rev_data['averageRating'],
									'review_count' => (int) $rev_data['totalReviewCount'],
									'updated_at'   => current_time( 'timestamp' ),
									'source'       => 'google_business_profile'
								];
								
								// Cache for 1 hour
								set_transient( 'ame_bazaar_gbp_data', $gbp_data, HOUR_IN_SECONDS );
								return $gbp_data;
							}
						}
					}
				}
			}
		}
	}
	
	return false;
}

/**
 * Schedule WP-Cron event to refresh GBP data hourly.
 */
function ame_bazaar_schedule_gbp_refresh() {
	if ( ! wp_next_scheduled( 'ame_bazaar_hourly_gbp_refresh' ) ) {
		wp_schedule_event( time(), 'hourly', 'ame_bazaar_hourly_gbp_refresh' );
	}
}
add_action( 'wp', 'ame_bazaar_schedule_gbp_refresh' );

/**
 * Hook into WP-Cron to refresh data.
 */
add_action( 'ame_bazaar_hourly_gbp_refresh', 'ame_bazaar_refresh_gbp_data' );

/**
 * Helper to clear GBP cache.
 */
function ame_bazaar_clear_gbp_cache() {
	delete_transient( 'ame_bazaar_gbp_data' );
}
