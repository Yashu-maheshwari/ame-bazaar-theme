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

	// Footer social brand styling is site-wide because the footer appears on every page.
	wp_enqueue_style(
		'ame-bazaar-footer-social',
		ame_bazaar_asset_uri( 'assets/css/footer-social.css' ),
		array( 'ame-bazaar-premium-global' ),
		ame_bazaar_asset_version( 'assets/css/footer-social.css' )
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

		// Premium social-feed brand icons, scoped to the homepage social feed component.
		wp_enqueue_style(
			'ame-bazaar-social-feed-icons',
			ame_bazaar_asset_uri( 'assets/css/social-feed-icons.css' ),
			array( 'ame-bazaar-homepage-category-order' ),
			ame_bazaar_asset_version( 'assets/css/social-feed-icons.css' )
		);
	}

	if ( function_exists('is_product') && is_product() ) {
		wp_enqueue_style(
			'ame-bazaar-sticky-cart',
			ame_bazaar_asset_uri( 'assets/css/sticky-add-to-cart.css' ),
			array( 'ame-bazaar-main' ),
			ame_bazaar_asset_version( 'assets/css/sticky-add-to-cart.css' )
		);
	}

	wp_enqueue_style(
		'ame-bazaar-mobile-header',
		ame_bazaar_asset_uri( 'assets/css/mobile-header.css' ),
		array( 'ame-bazaar-main' ),
		ame_bazaar_asset_version( 'assets/css/mobile-header.css' )
	);


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

	// Working footer social links: official Facebook/Instagram plus Threads and YouTube.
	wp_enqueue_script(
		'ame-bazaar-footer-social-links',
		ame_bazaar_asset_uri( 'assets/js/footer-social-links.js' ),
		array(),
		ame_bazaar_asset_version( 'assets/js/footer-social-links.js' ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_enqueue_assets' );

/**
 * Phase 21-13: Isolate WooCommerce CSS dependency
 * Remove woocommerce-smallscreen CSS from the homepage since no standard WooCommerce grids are used.
 */
function ame_bazaar_dequeue_woocommerce_styles_on_homepage() {
	if ( is_front_page() || is_home() ) {
		wp_dequeue_style( 'woocommerce-smallscreen' );
	}
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_dequeue_woocommerce_styles_on_homepage', 100 );

/**
 * Phase 21-14: Isolate WooCommerce Layout CSS dependency
 * Remove woocommerce-layout CSS from the homepage since it uses custom grid markup.
 */
function ame_bazaar_dequeue_woocommerce_layout_on_homepage() {
    if ( is_front_page() || is_home() ) {
        wp_dequeue_style( 'woocommerce-layout' );
    }
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_dequeue_woocommerce_layout_on_homepage', 100 );
/**
 * Phase 21-16: Isolate Homepage JavaScript dependency
 * Remove the completely unused Astra Sites preview JS on the homepage.
 */
function ame_bazaar_dequeue_unused_js_on_homepage() {
    if ( is_front_page() || is_home() ) {
        wp_dequeue_script( 'starter-templates-zip-preview' );
    }
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_dequeue_unused_js_on_homepage', 100 );

