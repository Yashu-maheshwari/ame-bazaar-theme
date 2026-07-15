<?php
/**
 * OPCache and Page Cache Reset helper script for AME Bazaar.
 */

// Token protection
$token = isset( $_GET['token'] ) ? sanitize_key( $_GET['token'] ) : '';
if ( $token !== '7fa2e10db708b8b9487c69ea230768b6' ) {
	header( 'HTTP/1.1 403 Forbidden' );
	exit( 'Forbidden: Invalid Token' );
}

if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo "OPCache Reset Successful!\n";
} else {
	echo "OPCache not enabled.\n";
}

// Find wp-load.php by traversing upwards dynamically
$wp_load_path = '';
$current_dir = __DIR__;
for ( $i = 0; $i < 5; $i++ ) {
	if ( file_exists( $current_dir . '/wp-load.php' ) ) {
		$wp_load_path = $current_dir . '/wp-load.php';
		break;
	}
	$current_dir = dirname( $current_dir );
}

if ( $wp_load_path ) {
	require_once( $wp_load_path );
	echo "WordPress Loaded successfully from: " . esc_html( $wp_load_path ) . "\n";
	
	// Purge LiteSpeed page cache
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
		echo "LiteSpeed Cache Purged via Purge class!\n";
	} elseif ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
		echo "LiteSpeed Cache Purged via function!\n";
	} else {
		echo "LiteSpeed Cache Purge class/function not found. Trying hook...\n";
	}
	
	// Always trigger the action hook for safety
	do_action( 'litespeed_purge_all' );
	echo "LiteSpeed action hook litespeed_purge_all triggered!\n";
	
	// Clean object cache
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
		echo "WordPress Object Cache Flushed!\n";
	}
} else {
	echo "wp-load.php not found. Skipping WordPress cache clear.\n";
}

