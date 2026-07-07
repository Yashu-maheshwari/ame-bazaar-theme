<?php
/**
 * Temp script to purge LiteSpeed Cache via hook.
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
	// Fallback to LiteSpeed Cache purge functions if hook is not registered yet
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
		$purged = 'Purged via LiteSpeed\Purge::purge_all()';
	} else {
		$purged = 'Hook and class not found';
	}
}

echo json_encode( array(
	'purged' => $purged,
	'active_plugins' => get_option( 'active_plugins' )
), JSON_PRETTY_PRINT );
