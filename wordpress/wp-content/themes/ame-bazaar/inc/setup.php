<?php
/**
 * Theme setup.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_setup_theme() {
	load_child_theme_textdomain( 'ame-bazaar', AME_BAZAAR_PATH . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'search-form',
	) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'woocommerce' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ame-bazaar' ),
		'footer'  => __( 'Footer Menu', 'ame-bazaar' ),
	) );

	add_image_size( 'ame-bazaar-hero', 1800, 1200, true );
	add_image_size( 'ame-bazaar-card', 900, 1200, true );
	add_image_size( 'ame-bazaar-square', 900, 900, true );
}
add_action( 'after_setup_theme', 'ame_bazaar_setup_theme' );

function ame_bazaar_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'ame_bazaar_content_width', 0 );

/**
 * Render the header template part.
 */
function ame_bazaar_render_header() {
	get_template_part( 'components/header/header' );
}
add_action( 'ame_bazaar_header', 'ame_bazaar_render_header' );

/**
 * Render the footer template part.
 */
function ame_bazaar_render_footer() {
	get_template_part( 'components/footer/footer' );
}
add_action( 'ame_bazaar_footer', 'ame_bazaar_render_footer' );

