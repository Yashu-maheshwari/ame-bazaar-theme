<?php
/**
 * Temp script to list all options starting with ame_ or containing gbp/google/oauth.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

global $wpdb;
$options = $wpdb->get_results( "
	SELECT option_name, option_value 
	FROM $wpdb->options 
	WHERE option_name LIKE 'ame%' 
	   OR option_name LIKE '%gbp%' 
	   OR option_name LIKE '%google%' 
	   OR option_name LIKE '%oauth%'
" );

$clean_options = array();
foreach ( $options as $opt ) {
	$val = $opt->option_value;
	// Mask potential sensitive data
	if ( stripos( $opt->option_name, 'secret' ) !== false || stripos( $opt->option_name, 'token' ) !== false || strlen( $val ) > 100 ) {
		$val = $val ? '*** (length: ' . strlen( $val ) . ')' : '';
	}
	$clean_options[$opt->option_name] = $val;
}

echo json_encode( $clean_options, JSON_PRETTY_PRINT );
