<?php
/**
 * OPCache and Page Cache Reset helper script for AME Bazaar.
 */
if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo "OPCache Reset Successful!\n";
} else {
	echo "OPCache not enabled.\n";
}

// Load WordPress to flush object/page cache
if ( file_exists( __DIR__ . '/wp-load.php' ) ) {
	require_once( __DIR__ . '/wp-load.php' );
	
	// Purge LiteSpeed page cache
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
		echo "LiteSpeed Cache Purged via Purge class!\n";
	} elseif ( function_exists( 'litespeed_purge_all' ) ) {
		litespeed_purge_all();
		echo "LiteSpeed Cache Purged via function!\n";
	} else {
		echo "LiteSpeed Cache plugin not active or function missing.\n";
	}
	
	// Clean object cache
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
		echo "WordPress Object Cache Flushed!\n";
	}
}
