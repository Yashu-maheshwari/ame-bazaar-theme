<?php
/**
 * Temp script to inspect sync results post oauth connection.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

echo json_encode( array(
	'client_id'      => get_option( 'ame_bazaar_gbp_client_id' ),
	'location_id'    => get_option( 'ame_bazaar_gbp_location_id' ),
	'last_sync'      => get_option( 'ame_bazaar_gbp_last_sync' ),
	'google_rating'  => get_option( 'ame_bazaar_google_reviews_rating' ),
	'review_count'   => get_option( 'ame_bazaar_google_reviews_count' ),
	'phone'          => get_option( 'ame_bazaar_phone' ),
	'address'        => get_option( 'ame_bazaar_address' ),
	'logs'           => Ame_Bazaar_GBP_Service::get_logs()
), JSON_PRETTY_PRINT );
