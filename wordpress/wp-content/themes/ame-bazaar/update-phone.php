<?php
/**
 * Temp script to test dynamic business phone update.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

// Save new business settings
$settings = get_option( 'ame_bazaar_business_settings', array() );
$settings['phone'] = '+91 98100 98100';
$settings['whatsapp'] = '+91 98100 98100';
update_option( 'ame_bazaar_business_settings', $settings );

echo json_encode( array(
	'success' => true,
	'updated_settings' => get_option( 'ame_bazaar_business_settings' )
) );
