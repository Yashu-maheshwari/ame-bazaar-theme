<?php
/**
 * Temp script to purge LiteSpeed Cache.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "PURGING CACHE...\n";
if ( has_action( 'litespeed_purge_all' ) ) {
	do_action( 'litespeed_purge_all' );
	echo "LiteSpeed Purge Action triggered.\n";
} elseif ( class_exists( 'LiteSpeed\Purge' ) ) {
	LiteSpeed\Purge::purge_all();
	echo "LiteSpeed Purge Class triggered.\n";
} else {
	wp_cache_flush();
}

if ( function_exists( 'opcache_reset' ) ) {
	opcache_reset();
	echo "OPCache Reset.\n";
}

echo "DONE.\n";
