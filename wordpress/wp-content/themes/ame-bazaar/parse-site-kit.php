<?php
/**
 * Temp script to inspect Google Site Kit credentials and settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

$credentials = get_option( 'googlesitekit_credentials' );
if ( is_string( $credentials ) ) {
	$credentials = maybe_unserialize( $credentials );
}

// Check other googlesitekit options
global $wpdb;
$sitekit_options = $wpdb->get_results( "
	SELECT option_name, option_value 
	FROM $wpdb->options 
	WHERE option_name LIKE 'googlesitekit_%'
" );

$details = array(
	'credentials_type' => gettype( $credentials ),
	'credentials_keys' => is_array( $credentials ) ? array_keys( $credentials ) : array(),
	'raw_credentials' => $credentials, // Let's check keys and values safely
	'all_sitekit_options' => array()
);

foreach ( $sitekit_options as $opt ) {
	$val = $opt->option_value;
	if ( stripos( $opt->option_name, 'token' ) !== false || stripos( $opt->option_name, 'secret' ) !== false || stripos( $opt->option_name, 'credentials' ) !== false ) {
		$val = '***';
	}
	$details['all_sitekit_options'][$opt->option_name] = $val;
}

echo json_encode( $details, JSON_PRETTY_PRINT );
