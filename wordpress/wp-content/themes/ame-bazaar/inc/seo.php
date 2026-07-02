<?php
/**
 * SEO, Meta Tags, Open Graph, and Twitter Cards module for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output SEO meta tags in the document head if no major SEO plugin is active.
 */
function ame_bazaar_output_seo_meta() {
	// Bypass if Rank Math, Yoast SEO, or SEOPress is active to avoid duplicate meta tags.
	if ( class_exists( 'RankMath' ) || defined( 'WPSEO_VERSION' ) || class_exists( 'WPSEO_Frontend' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}

	$brand_name = ame_bazaar_get_brand_name();
	$url        = get_permalink();
	$title      = get_the_title();
	$type       = 'website';
	$image      = ame_bazaar_get_custom_logo_url();

	// Fallback description
	$desc = get_bloginfo( 'description' );
	if ( is_front_page() || is_home() ) {
		$desc = get_theme_mod( 'ame_bazaar_footer_about', $desc );
		$url  = home_url( '/' );
	} elseif ( is_singular() ) {
		$type = 'article';
		if ( has_excerpt() ) {
			$desc = get_the_excerpt();
		} else {
			$desc = wp_strip_all_tags( get_the_content() );
		}
		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( null, 'large' );
		}
	} elseif ( is_archive() ) {
		$desc = get_the_archive_description();
		if ( ! $desc ) {
			$desc = sprintf( __( 'Explore premium fashion collections at %s.', 'ame-bazaar' ), $brand_name );
		}
		$desc = wp_strip_all_tags( $desc );
	}

	// Trim description length for search engines (max 160 chars)
	$desc = wp_html_excerpt( $desc, 155, '...' );

	// Output Meta description
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";

	// Output Canonical URL
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";

	// Open Graph Tags
	echo '<meta property="og:site_name" content="' . esc_attr( $brand_name ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	if ( is_singular( 'post' ) ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . "\n";
		echo '<meta property="article:author" content="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . "\n";
	}


	// Twitter Card Tags
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ame_bazaar_output_seo_meta', 5 );

/**
 * Filter image attachment attributes to ensure fallback ALT tag exists.
 *
 * @param array   $attr       Attributes for the image markup.
 * @param WP_Post $attachment Image attachment post.
 * @return array
 */
function ame_bazaar_image_alt_fallback( $attr, $attachment ) {
	if ( empty( $attr['alt'] ) ) {
		// Use attachment title or parent post title as fallback ALT description
		$fallback_alt = get_the_title( $attachment->ID );
		if ( ! $fallback_alt && $attachment->post_parent ) {
			$fallback_alt = get_the_title( $attachment->post_parent );
		}
		if ( ! $fallback_alt ) {
			$fallback_alt = ame_bazaar_get_brand_name();
		}
		$attr['alt'] = esc_attr( $fallback_alt );
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ame_bazaar_image_alt_fallback', 10, 2 );
