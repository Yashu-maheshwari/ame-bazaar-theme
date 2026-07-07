<?php
/**
 * Temp script to dump GBP database options.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

echo json_encode( array(
	'client_id'      => get_option( 'ame_bazaar_gbp_client_id' ),
	'client_secret'  => get_option( 'ame_bazaar_gbp_client_secret' ) ? '***' : '',
	'access_token'   => get_option( 'ame_bazaar_gbp_access_token' ) ? '***' : '',
	'refresh_token'  => get_option( 'ame_bazaar_gbp_refresh_token' ) ? '***' : '',
	'token_expires'  => get_option( 'ame_bazaar_gbp_token_expires' ),
	'location_id'    => get_option( 'ame_bazaar_gbp_location_id' ),
), JSON_PRETTY_PRINT );
