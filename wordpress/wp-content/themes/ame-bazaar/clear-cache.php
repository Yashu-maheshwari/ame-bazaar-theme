<?php
/**
 * OPCache and Page Cache Reset helper script for AME Bazaar.
 */
if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo "OPCache Reset Successful!\n";
}

// Bootstrap WordPress to purge page cache (LiteSpeed, etc.)
$wp_load = __DIR__ . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
	define( 'WP_USE_THEMES', false );
	require_once $wp_load;
	
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
		echo "LiteSpeed Cache Purged via Action!\n";
	} elseif ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
		echo "LiteSpeed Cache Purged via Class!\n";
	} else {
		wp_cache_flush();
		echo "WordPress Cache Flushed!\n";
	}
} else {
	echo "WordPress load file not found at: $wp_load\n";
}
