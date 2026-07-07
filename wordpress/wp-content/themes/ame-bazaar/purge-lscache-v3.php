<?php
/**
 * Temp script to purge LiteSpeed Cache via hook (V3).
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

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

// Also run the auto-assign mapping to ensure the new women-wear high-res asset ID maps to option key
if ( function_exists( 'ame_bazaar_auto_assign_media_mappings' ) ) {
	ame_bazaar_auto_assign_media_mappings();
	$auto_assign = 'Executed auto-assignment';
} else {
	$auto_assign = 'Auto-assignment helper missing';
}

echo json_encode( array(
	'purged' => $purged,
	'auto_assign' => $auto_assign,
	'ame_bazaar_media_women' => get_option( 'ame_bazaar_media_women' )
), JSON_PRETTY_PRINT );
