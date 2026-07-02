<?php
/**
 * Security helpers.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_sanitize_svg_url( $url ) {
	return esc_url_raw( $url );
}

function ame_bazaar_safe_bool( $value ) {
	return (bool) $value;
}
