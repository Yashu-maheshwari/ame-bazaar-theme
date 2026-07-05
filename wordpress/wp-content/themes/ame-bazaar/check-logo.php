<?php
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}
header( 'Content-Type: application/json' );

echo json_encode( array(
	'custom_logo_mod' => get_theme_mod( 'custom_logo' ),
	'site_icon_option' => get_option( 'site_icon' ),
	'primary_logo_option' => get_option( 'ame_bazaar_media_primary_logo' ),
	'white_logo_option' => get_option( 'ame_bazaar_media_white_logo' ),
	'sticky_logo_option' => get_option( 'ame_bazaar_media_sticky_logo' ),
	'favicon_option' => get_option( 'ame_bazaar_media_favicon' ),
), JSON_PRETTY_PRINT );
