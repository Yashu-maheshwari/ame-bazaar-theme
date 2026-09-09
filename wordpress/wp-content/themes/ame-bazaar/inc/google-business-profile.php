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
	\ = get_transient( 'ame_bazaar_gbp_data' );
	if ( false !== \ && is_array( \ ) && isset( \['rating'] ) ) {
		return \;
	}
	
	// If no cache, trigger an immediate synchronous fetch for the first run.
	return ame_bazaar_refresh_gbp_data();
}

/**
 * Fetches fresh data from Google Business Profile API and updates cache.
 * Can be triggered via WP-Cron.
 */
function ame_bazaar_refresh_gbp_data() {
	\ = '/home/u473080180/domains/amebazaar.in/ame-gbp-secrets.php';
	
	if ( ! file_exists( \ ) ) {
		return false;
	}
	
	\ = include \;
	if ( empty( \['client_id'] ) || empty( \['refresh_token'] ) ) {
		return false;
	}
	
	// 1. Fetch Access Token
	\ = 'https://oauth2.googleapis.com/token';
	\ = wp_remote_post( \, [
		'body' => [
			'client_id'     => \['client_id'],
			'client_secret' => \['client_secret'],
			'refresh_token' => \['refresh_token'],
			'grant_type'    => 'refresh_token',
		],
		'timeout' => 15,
	] );
	
	if ( is_wp_error( \ ) ) {
		return false;
	}
	
	\ = json_decode( wp_remote_retrieve_body( \ ), true );
	if ( empty( \['access_token'] ) ) {
		return false;
	}
	
	\ = \['access_token'];
	
	\ = wp_remote_get( 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts', [
		'headers' => [ 'Authorization' => 'Bearer ' . \ ],
		'timeout' => 15,
	] );
	
	if ( is_wp_error( \ ) ) {
		return false;
	}
	
	\ = json_decode( wp_remote_retrieve_body( \ ), true );
	if ( empty( \['accounts'] ) ) {
		return false;
	}
	
	// Find the matching account and location
	foreach ( \['accounts'] as \ ) {
		\ = wp_remote_get( 'https://mybusinessbusinessinformation.googleapis.com/v1/' . \['name'] . '/locations?readMask=name,title', [
			'headers' => [ 'Authorization' => 'Bearer ' . \ ],
			'timeout' => 15,
		] );
		
		if ( ! is_wp_error( \ ) ) {
			\ = json_decode( wp_remote_retrieve_body( \ ), true );
			if ( ! empty( \['locations'] ) ) {
				foreach ( \['locations'] as \ ) {
					if ( stripos( \['title'], 'AME Bazaar - Family Garment Store' ) !== false || stripos( \['title'], 'AME Bazaar' ) !== false ) {
						
						// Verify it has reviews before committing
						\  = str_replace( 'locations/', '', \['name'] );
						\  = str_replace( 'accounts/', '', \['name'] );
						\ = "https://mybusiness.googleapis.com/v4/accounts/{\}/locations/{\}/reviews";
						
						\ = wp_remote_get( \, [
							'headers' => [ 'Authorization' => 'Bearer ' . \ ],
							'timeout' => 15,
						] );
						
						if ( ! is_wp_error( \ ) ) {
							\ = json_decode( wp_remote_retrieve_body( \ ), true );
							if ( isset( \['averageRating'] ) && isset( \['totalReviewCount'] ) ) {
								
								\ = [
									'rating'       => (float) \['averageRating'],
									'review_count' => (int) \['totalReviewCount'],
									'updated_at'   => current_time( 'timestamp' ),
									'source'       => 'google_business_profile'
								];
								
								// Cache for 1 hour
								set_transient( 'ame_bazaar_gbp_data', \, HOUR_IN_SECONDS );
								return \;
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
