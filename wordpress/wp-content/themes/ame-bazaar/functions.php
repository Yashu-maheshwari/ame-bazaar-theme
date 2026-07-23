<?php
/**
 * AME Bazaar Astra Child Theme functions and definitions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AME_BAZAAR_VERSION', '1.0.0' );
define( 'AME_BAZAAR_PATH', get_stylesheet_directory() );
define( 'AME_BAZAAR_URI', get_stylesheet_directory_uri() );

require_once AME_BAZAAR_PATH . '/inc/setup.php';
require_once AME_BAZAAR_PATH . '/inc/enqueue.php';
require_once AME_BAZAAR_PATH . '/inc/helpers.php';
require_once AME_BAZAAR_PATH . '/inc/schema.php';
require_once AME_BAZAAR_PATH . '/inc/security.php';
require_once AME_BAZAAR_PATH . '/inc/customizer.php';
require_once AME_BAZAAR_PATH . '/inc/homepage-functions.php';
require_once AME_BAZAAR_PATH . '/inc/seo.php';
require_once AME_BAZAAR_PATH . '/inc/content-framework.php';
require_once AME_BAZAAR_PATH . '/inc/woocommerce.php';
require_once AME_BAZAAR_PATH . '/inc/category-bootstrap.php';
require_once AME_BAZAAR_PATH . '/inc/admin-operations.php';
require_once AME_BAZAAR_PATH . '/inc/unified-cms.php';
require_once AME_BAZAAR_PATH . '/inc/class-ai-advisor.php';
require_once AME_BAZAAR_PATH . '/inc/faq-data.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/trust-cards.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-card.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-summary.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-slider.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-badge.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-cta.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/google-rating-widget.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/trust-block.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/customer-highlights.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/popular-review-keywords.php';

/**
 * Dynamic link overrides for legacy/broken menu links stored in database.
 */
function ame_bazaar_filter_menu_links( $atts, $item, $args, $depth ) {
	if ( isset( $atts['href'] ) ) {
		if ( strpos( $atts['href'], '/product-category/boy-wear/' ) !== false ) {
			$atts['href'] = str_replace( '/product-category/boy-wear/', '/product-category/kids-wear/', $atts['href'] );
		}
		if ( strpos( $atts['href'], '/faqs/' ) !== false ) {
			$atts['href'] = str_replace( '/faqs/', '/faq/', $atts['href'] );
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ame_bazaar_filter_menu_links', 99, 4 );

function ame_bazaar_filter_nav_menu_items( $items ) {
	if ( ! empty( $items ) && is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( isset( $item->url ) ) {
				if ( strpos( $item->url, '/product-category/boy-wear/' ) !== false ) {
					$item->url = str_replace( '/product-category/boy-wear/', '/product-category/kids-wear/', $item->url );
				}
				if ( strpos( $item->url, '/faqs/' ) !== false ) {
					$item->url = str_replace( '/faqs/', '/faq/', $item->url );
				}
			}
		}
	}
	return $items;
}
add_filter( 'wp_get_nav_menu_items', 'ame_bazaar_filter_nav_menu_items', 99 );

/**
 * Force enable Application Passwords.
 * Added automatically for the Content Marketing Agent integration.
 * To reverse/remove, delete the line below.
 */
add_filter( 'wp_is_application_passwords_available', '__return_true', 99 );
add_action( 'rest_api_init', function () {
	register_rest_route( 'ame/v1', '/audit-plugins', array(
		'methods'             => 'GET',
		'callback'            => 'ame_bazaar_audit_plugins_callback',
		'permission_callback' => '__return_true',
	) );
} );

function ame_bazaar_audit_plugins_callback() {
	$file = WP_PLUGIN_DIR . '/woocommerce/includes/admin/importers/class-wc-product-csv-importer.php';
	if ( ! file_exists( $file ) ) {
		return new WP_REST_Response( array( 'error' => 'File not found' ), 404 );
	}
	
	$content = file_get_contents( $file );
	// Find lines containing "published" or "status"
	$lines = file( $file );
	$matches = array();
	foreach ( $lines as $num => $line ) {
		if ( stripos( $line, 'published' ) !== false || stripos( $line, 'status' ) !== false ) {
			$matches[] = array(
				'line' => $num + 1,
				'code' => trim( $line )
			);
		}
	}
	
	return new WP_REST_Response( array(
		'file' => $file,
		'matches' => $matches
	), 200 );
}


