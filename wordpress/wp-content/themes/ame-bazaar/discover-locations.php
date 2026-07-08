<?php
/**
 * Temp script to automatically discover GBP accounts and locations.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

$access_token = Ame_Bazaar_GBP_Service::get_access_token();
if ( ! $access_token ) {
	echo json_encode( array(
		'success' => false,
		'error'   => 'No access token available. Please connect Google Account first.'
	), JSON_PRETTY_PRINT );
	exit;
}

// 1. Fetch Accounts
$accounts_url = 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts';
$accounts_res = wp_remote_get( $accounts_url, array(
	'headers' => array(
		'Authorization' => 'Bearer ' . $access_token,
		'Content-Type'  => 'application/json'
	)
) );

if ( is_wp_error( $accounts_res ) ) {
	echo json_encode( array(
		'success' => false,
		'error'   => 'Failed to fetch accounts: ' . $accounts_res->get_error_message()
	), JSON_PRETTY_PRINT );
	exit;
}

$accounts_body = json_decode( wp_remote_retrieve_body( $accounts_res ), true );
if ( isset( $accounts_body['error'] ) ) {
	echo json_encode( array(
		'success' => false,
		'error'   => 'Accounts API error: ' . $accounts_body['error']['message'],
		'raw'     => $accounts_body
	), JSON_PRETTY_PRINT );
	exit;
}

$accounts = isset( $accounts_body['accounts'] ) ? $accounts_body['accounts'] : array();
$discovered_locations = array();

// 2. Loop accounts and fetch locations
foreach ( $accounts as $account ) {
	$account_name = $account['name']; // e.g. "accounts/12345"
	
	// Query locations for this account
	$locations_url = 'https://mybusinessbusinessinformation.googleapis.com/v1/' . $account_name . '/locations?readMask=name,title,storefrontAddress';
	$locations_res = wp_remote_get( $locations_url, array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $access_token,
			'Content-Type'  => 'application/json'
		)
	) );
	
	if ( is_wp_error( $locations_res ) ) {
		continue;
	}
	
	$locations_body = json_decode( wp_remote_retrieve_body( $locations_res ), true );
	if ( isset( $locations_body['locations'] ) ) {
		foreach ( $locations_body['locations'] as $loc ) {
			$address_str = '';
			if ( isset( $loc['storefrontAddress'] ) ) {
				$addr = $loc['storefrontAddress'];
				$address_lines = isset( $addr['addressLines'] ) ? implode( ', ', $addr['addressLines'] ) : '';
				$locality = isset( $addr['locality'] ) ? $addr['locality'] : '';
				$postal_code = isset( $addr['postalCode'] ) ? $addr['postalCode'] : '';
				$address_str = trim( "$address_lines, $locality, $postal_code", ', ' );
			}
			
			$discovered_locations[] = array(
				'name'         => $loc['name'], // e.g. "locations/67890"
				'title'        => isset( $loc['title'] ) ? $loc['title'] : 'Unknown Name',
				'address'      => $address_str,
				'account_name' => $account_name
			);
		}
	}
}

// 3. Process Discovery
$auto_configured = false;
$sync_result = null;

if ( count( $discovered_locations ) === 1 ) {
	// Exactly one location - auto configure!
	$location_id = $discovered_locations[0]['name']; // "locations/xxxxx"
	update_option( 'ame_bazaar_gbp_location_id', $location_id );
	$auto_configured = true;
	
	// Trigger Sync
	$synced = Ame_Bazaar_GBP_Service::perform_sync();
	$sync_result = array(
		'success'      => ! is_wp_error( $synced ) && $synced,
		'error'        => is_wp_error( $synced ) ? $synced->get_error_message() : '',
		'rating'       => get_option( 'ame_bazaar_google_reviews_rating' ),
		'review_count' => get_option( 'ame_bazaar_google_reviews_count' ),
		'last_sync'    => get_option( 'ame_bazaar_gbp_last_sync' )
	);
}

echo json_encode( array(
	'success'              => true,
	'accounts'             => $accounts,
	'discovered_locations' => $discovered_locations,
	'auto_configured'      => $auto_configured,
	'sync_result'          => $sync_result
), JSON_PRETTY_PRINT );
