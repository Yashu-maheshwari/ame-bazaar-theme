<?php
/**
 * Temp script to purge LiteSpeed Cache and reset OPCache.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "PURGING LITESPEED CACHE...\n";

if ( has_action( 'litespeed_purge_all' ) ) {
	do_action( 'litespeed_purge_all' );
	echo "LiteSpeed Purge Action triggered.\n";
} elseif ( class_exists( 'LiteSpeed\Purge' ) ) {
	LiteSpeed\Purge::purge_all();
	echo "LiteSpeed Purge Class triggered.\n";
} else {
	// Fallback/standard WordPress cache plugins
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
		echo "WP Cache Clear triggered.\n";
	}
	echo "No explicit LiteSpeed Cache purge action found. Triggering generic actions.\n";
	wp_cache_flush();
}

if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo "OPCache Reset successful.\n";
}

echo "CACHE PURGE PROCESS COMPLETE.\n";
