<?php
/**
 * Temp script to trigger GBP sync live.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

$synced = Ame_Bazaar_GBP_Service::perform_sync();

// Clear LSCache
if ( has_action( 'litespeed_purge_all' ) ) {
	do_action( 'litespeed_purge_all' );
	$purged = 'Purged LSCache via hook';
} else {
	$purged = 'LSCache hook not found';
}

echo json_encode( array(
	'success'     => $synced,
	'purged'      => $purged,
	'last_sync'   => get_option( 'ame_bazaar_gbp_last_sync' ),
	'store_name'  => get_option( 'ame_bazaar_store_name' ),
	'phone'       => get_option( 'ame_bazaar_phone' ),
	'email'       => get_option( 'ame_bazaar_email' ),
	'hours'       => get_option( 'ame_bazaar_hours' ),
	'rating'      => get_option( 'ame_bazaar_google_reviews_rating' ),
	'review_count'=> get_option( 'ame_bazaar_google_reviews_count' ),
	'logs'        => Ame_Bazaar_GBP_Service::get_logs()
), JSON_PRETTY_PRINT );
