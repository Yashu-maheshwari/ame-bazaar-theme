<?php
/**
 * Dynamic XML sitemap generator for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Disable default WordPress sitemaps
add_filter( 'wp_sitemaps_enabled', '__return_false' );

/**
 * Register custom sitemap rewrite rule.
 */
function ame_bazaar_sitemap_rewrite() {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?ame_bazaar_sitemap=1', 'top' );
}
add_action( 'init', 'ame_bazaar_sitemap_rewrite' );

/**
 * Register query variable for sitemap.
 */
function ame_bazaar_sitemap_query_vars( $vars ) {
	$vars[] = 'ame_bazaar_sitemap';
	return $vars;
}
add_filter( 'query_vars', 'ame_bazaar_sitemap_query_vars' );

/**
 * Flush rewrite rules on theme activation.
 */
add_action( 'after_switch_theme', function() {
	ame_bazaar_sitemap_rewrite();
	flush_rewrite_rules();
} );

/**
 * Intercept request and output XML sitemap.
 */
function ame_bazaar_generate_sitemap() {
	$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	if ( '/sitemap.xml' !== $path && '/sitemap.xml/' !== $path ) {
		return;
	}

	header( 'Content-Type: application/xml; charset=utf-8' );
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	$entries = array();

	// 1. Homepage Entry
	$entries[] = array(
		'loc'        => home_url( '/' ),
		'lastmod'    => date( 'c', current_time( 'timestamp' ) ),
		'changefreq' => 'daily',
		'priority'   => '1.0',
	);

	// 2. Query Published Pages
	$pages = get_posts( array(
		'post_type'        => 'page',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'suppress_filters' => true,
	) );

	// Exclude list (e.g. utility pages, drafts, redirected URLs)
	$exclude_slugs = array( 'privacy-policy', 'terms-of-service', 'shipping-returns', 'review-request', 'rate-experience' );

	foreach ( $pages as $page ) {
		if ( in_array( $page->post_name, $exclude_slugs, true ) ) {
			continue;
		}
		
		// If page template is for local-entity or pillar, prioritize it
		$template = get_post_meta( $page->ID, '_wp_page_template', true );
		$priority = '0.8';
		if ( strpos( $template, 'template-pillar' ) !== false || strpos( $template, 'template-local-entity' ) !== false ) {
			$priority = '0.9';
		}

		$entries[] = array(
			'loc'        => get_permalink( $page->ID ),
			'lastmod'    => get_the_modified_date( 'c', $page->ID ),
			'changefreq' => 'weekly',
			'priority'   => $priority,
		);
	}

	// 3. Query Published Posts
	$posts = get_posts( array(
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'suppress_filters' => true,
	) );
	foreach ( $posts as $post ) {
		$entries[] = array(
			'loc'        => get_permalink( $post->ID ),
			'lastmod'    => get_the_modified_date( 'c', $post->ID ),
			'changefreq' => 'weekly',
			'priority'   => '0.7',
		);
	}

	// 4. Query Published Products (WooCommerce)
	if ( post_type_exists( 'product' ) ) {
		$products = get_posts( array(
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
		) );
		foreach ( $products as $product ) {
			$entries[] = array(
				'loc'        => get_permalink( $product->ID ),
				'lastmod'    => get_the_modified_date( 'c', $product->ID ),
				'changefreq' => 'weekly',
				'priority'   => '0.8',
			);
		}
	}

	// 5. Query WooCommerce Product Categories
	if ( taxonomy_exists( 'product_cat' ) ) {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
		) );
		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				$entries[] = array(
					'loc'        => get_term_link( $cat ),
					'lastmod'    => date( 'c', current_time( 'timestamp' ) ),
					'changefreq' => 'weekly',
					'priority'   => '0.8',
				);
			}
		}
	}

	// Output URL entries
	foreach ( $entries as $entry ) {
		echo "   <url>\n";
		echo "      <loc>" . esc_url( $entry['loc'] ) . "</loc>\n";
		echo "      <lastmod>" . esc_html( $entry['lastmod'] ) . "</lastmod>\n";
		echo "      <changefreq>" . esc_html( $entry['changefreq'] ) . "</changefreq>\n";
		echo "      <priority>" . esc_html( $entry['priority'] ) . "</priority>\n";
		echo "   </url>\n";
	}

	echo '</urlset>' . "\n";
	exit;
}
add_action( 'template_redirect', 'ame_bazaar_generate_sitemap', 1 );
