<?php
/**
 * Asset loading.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_asset_version( $relative_path ) {
	$path = trailingslashit( AME_BAZAAR_PATH ) . ltrim( $relative_path, '/' );

	return file_exists( $path ) ? (string) filemtime( $path ) : AME_BAZAAR_VERSION;
}

function ame_bazaar_enqueue_assets() {
	$parent_style_version = wp_get_theme( get_template() )->get( 'Version' );

	wp_enqueue_style(
		'ame-bazaar-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$parent_style_version
	);

	wp_enqueue_style(
		'ame-bazaar-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'ame-bazaar-style',
		get_stylesheet_uri(),
		array( 'ame-bazaar-parent-style' ),
		AME_BAZAAR_VERSION
	);

	wp_enqueue_style(
		'ame-bazaar-main',
		ame_bazaar_asset_uri( 'assets/css/main.css' ),
		array( 'ame-bazaar-style' ),
		ame_bazaar_asset_version( 'assets/css/main.css' )
	);

	// Global premium UI layer: fixed navigation, inner-page contrast, visual rhythm.
	// Loaded site-wide so the header and readability fixes apply consistently on every page.
	wp_enqueue_style(
		'ame-bazaar-premium-global',
		ame_bazaar_asset_uri( 'assets/css/premium-homepage-final.css' ),
		array( 'ame-bazaar-main' ),
		ame_bazaar_asset_version( 'assets/css/premium-homepage-final.css' )
	);

	// Premium homepage UI layer. CSS-only refinement; no backend/template replacement.
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'ame-bazaar-premium-homepage',
			ame_bazaar_asset_uri( 'assets/css/premium-homepage.css' ),
			array( 'ame-bazaar-premium-global' ),
			ame_bazaar_asset_version( 'assets/css/premium-homepage.css' )
		);

		// Source-driven category ordering. The rule targets the category slug emitted by categories.php,
		// so it is independent of database return order or DOM child position.
		wp_enqueue_style(
			'ame-bazaar-homepage-category-order',
			ame_bazaar_asset_uri( 'assets/css/homepage-category-order.css' ),
			array( 'ame-bazaar-premium-homepage' ),
			ame_bazaar_asset_version( 'assets/css/homepage-category-order.css' )
		);
	}

	wp_enqueue_style(
		'ame-bazaar-mobile-header',
		ame_bazaar_asset_uri( 'assets/css/mobile-header.css' ),
		array( 'ame-bazaar-main' ),
		ame_bazaar_asset_version( 'assets/css/mobile-header.css' )
	);

	// GSAP for cinematic hero animations — loaded only where needed (homepage)
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script(
			'gsap',
			'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
			array(),
			'3.12.5',
			true
		);
	}

	wp_enqueue_script(
		'ame-bazaar-global',
		ame_bazaar_asset_uri( 'assets/js/global.js' ),
		array(),
		ame_bazaar_asset_version( 'assets/js/global.js' ),
		true
	);

	wp_localize_script(
		'ame-bazaar-global',
		'ameBazaarAjax',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'ame_bazaar_search_nonce' ),
		)
	);

	// Independent mobile interaction bridge. It intentionally has no dependency
	// on global.js so the header controls still work if another script fails.
	wp_enqueue_script(
		'ame-bazaar-mobile-header-interactions',
		ame_bazaar_asset_uri( 'assets/js/mobile-header-interactions.js' ),
		array(),
		ame_bazaar_asset_version( 'assets/js/mobile-header-interactions.js' ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_enqueue_assets' );