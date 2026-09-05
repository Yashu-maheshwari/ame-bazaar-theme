<?php
/**
 * AME Bazaar Astra Child Theme functions and definitions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'ame/v1', '/publish-drafts', array(
		'methods'             => 'GET',
		'callback'            => 'ame_bazaar_publish_all_drafts_callback',
		'permission_callback' => '__return_true',
	) );
} );

function ame_bazaar_publish_all_drafts_callback() {
	$drafts = get_posts( array(
		'post_type'   => 'product',
		'post_status' => 'draft',
		'numberposts' => -1,
		'fields'      => 'ids'
	) );

	$total_found = count( $drafts );
	$published = 0;
	$failed_ids = array();

	foreach ( $drafts as $id ) {
		$result = wp_update_post( array(
			'ID'          => $id,
			'post_status' => 'publish'
		) );

		if ( $result && ! is_wp_error( $result ) ) {
			$published++;
		} else {
			$failed_ids[] = $id;
		}
	}

	$total_products = count( get_posts( array(
		'post_type'   => 'product',
		'post_status' => array( 'publish', 'draft' ),
		'numberposts' => -1,
		'fields'      => 'ids'
	) ) );

	$published_count = count( get_posts( array(
		'post_type'   => 'product',
		'post_status' => 'publish',
		'numberposts' => -1,
		'fields'      => 'ids'
	) ) );

	$draft_count = count( get_posts( array(
		'post_type'   => 'product',
		'post_status' => 'draft',
		'numberposts' => -1,
		'fields'      => 'ids'
	) ) );

	return new WP_REST_Response( array(
		'status'             => 'success',
		'total_drafts_found' => $total_found,
		'published_count'    => $published,
		'failed_ids'         => $failed_ids,
		'after'              => array(
			'total_products'  => $total_products,
			'published_count' => $published_count,
			'draft_count'     => $draft_count
		)
	), 200 );
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
require_once AME_BAZAAR_PATH . '/inc/vto-integration.php';
require_once AME_BAZAAR_PATH . '/inc/final-ui-fixes.php';
require_once AME_BAZAAR_PATH . '/inc/mobile-whatsapp-final-fix.php';
require_once AME_BAZAAR_PATH . '/inc/mobile-header-layout-final.php';
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
require_once AME_BAZAAR_PATH . '/inc/social-identity.php';

/**
 * Dynamic link overrides for legacy/broken menu links stored in database.
 */
function ame_bazaar_filter_menu_links( $atts, $item, $args, $depth ) {
	if ( isset( $atts['href'] ) ) {
		if ( strpos( $atts['href'], '/product-category/boy-wear/' ) !== false || strpos( $atts['href'], '/product-category/kids-wear/' ) !== false ) {
			$atts['href'] = str_replace( array( '/product-category/boy-wear/', '/product-category/kids-wear/' ), '/product-category/kids/', $atts['href'] );
		}
		if ( strpos( $atts['href'], '/product-category/womens-wear/' ) !== false ) {
			$atts['href'] = str_replace( '/product-category/womens-wear/', '/product-category/women/', $atts['href'] );
		}
		if ( strpos( $atts['href'], '/product-category/mens-wear/' ) !== false ) {
			$atts['href'] = str_replace( '/product-category/mens-wear/', '/product-category/men/', $atts['href'] );
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
				if ( strpos( $item->url, '/product-category/boy-wear/' ) !== false || strpos( $item->url, '/product-category/kids-wear/' ) !== false ) {
					$item->url = str_replace( array( '/product-category/boy-wear/', '/product-category/kids-wear/' ), '/product-category/kids/', $item->url );
				}
				if ( strpos( $item->url, '/product-category/womens-wear/' ) !== false ) {
					$item->url = str_replace( '/product-category/womens-wear/', '/product-category/women/', $item->url );
				}
				if ( strpos( $item->url, '/product-category/mens-wear/' ) !== false ) {
					$item->url = str_replace( '/product-category/mens-wear/', '/product-category/men/', $item->url );
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

// deploy trigger comment
