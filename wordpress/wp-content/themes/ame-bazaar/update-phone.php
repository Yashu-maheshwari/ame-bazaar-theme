<?php
/**
 * Temp script to test dynamic business phone update.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

// Save new business settings individually
update_option( 'ame_bazaar_phone', '+91 98100 98100' );
update_option( 'ame_bazaar_whatsapp', '+91 98100 98100' );

echo json_encode( array(
	'success' => true,
	'phone'   => get_option( 'ame_bazaar_phone' ),
	'whatsapp'=> get_option( 'ame_bazaar_whatsapp' )
) );
// Trigger comment for re-run.
