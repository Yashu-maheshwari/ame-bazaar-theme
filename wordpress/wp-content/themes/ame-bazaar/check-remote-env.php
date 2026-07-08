<?php
/**
 * Temp script to inspect remote environment for keys.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

$results = array(
	'env_vars' => array(),
	'constants' => array(),
	'files' => array()
);

// Scan env
foreach ( $_SERVER as $key => $val ) {
	if ( stripos( $key, 'gbp' ) !== false || stripos( $key, 'google' ) !== false || stripos( $key, 'oauth' ) !== false ) {
		$results['env_vars'][$key] = '***';
	}
}

// Check common constants
$common_constants = array(
	'GBP_ACCESS_TOKEN', 'GBP_ACCOUNT_ID', 'GBP_LOCATION_ID',
	'GOOGLE_API_KEY', 'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET'
);
foreach ( $common_constants as $const ) {
	if ( defined( $const ) ) {
		$results['constants'][$const] = constant( $const );
	}
}

// Scan public_html folder files for config
$dir = ABSPATH;
$files = scandir($dir);
foreach ( $files as $file ) {
	if ( strpos( $file, '.env' ) !== false || strpos( $file, 'config' ) !== false || strpos( $file, 'credentials' ) !== false ) {
		$results['files'][] = $file;
	}
}

echo json_encode( $results, JSON_PRETTY_PRINT );
