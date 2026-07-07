<?php
/**
 * Temp script to purge LiteSpeed Cache via hook (V3).
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

// Force update options to correct mapping IDs
update_option( 'ame_bazaar_media_women', 498 ); // Force map women wear to ID 498 (high-res women-wear.jpg)
update_option( 'ame_bazaar_email', 'info@amebazaar.in' ); // Ensure database email matches info@amebazaar.in

$purged = false;
if ( has_action( 'litespeed_purge_all' ) ) {
	do_action( 'litespeed_purge_all' );
	$purged = 'Purged via hook';
} else {
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
		$purged = 'Purged via LiteSpeed\Purge::purge_all()';
	} else {
		$purged = 'Hook and class not found';
	}
}

echo json_encode( array(
	'purged' => $purged,
	'ame_bazaar_media_women' => get_option( 'ame_bazaar_media_women' ),
	'ame_bazaar_email' => get_option( 'ame_bazaar_email' )
), JSON_PRETTY_PRINT );
