<?php
/**
 * Temp script to inspect option retrieval and clear WordPress cache.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

// Check current options directly
$opt_phone = get_option( 'ame_bazaar_phone' );
$helper_phone = function_exists( 'ame_bazaar_get_business_setting' ) ? ame_bazaar_get_business_setting( 'phone' ) : 'function missing';

// Clear LSCache if active
if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
	LiteSpeed_Cache_API::purge_all();
	$lscache = 'Purged all';
} elseif ( function_exists( 'litespeed_purge_all' ) ) {
	litespeed_purge_all();
	$lscache = 'Purged all via function';
} else {
	$lscache = 'LiteSpeed API not found';
}

// Clear standard WP cache
if ( function_exists( 'wp_cache_flush' ) ) {
	wp_cache_flush();
	$wp_cache = 'Flushed';
} else {
	$wp_cache = 'Not supported';
}

echo json_encode( array(
	'opt_phone'    => $opt_phone,
	'helper_phone' => $helper_phone,
	'lscache'      => $lscache,
	'wp_cache'     => $wp_cache,
	'all_options'  => array(
		'phone'    => get_option( 'ame_bazaar_phone' ),
		'whatsapp' => get_option( 'ame_bazaar_whatsapp' )
	)
), JSON_PRETTY_PRINT );
