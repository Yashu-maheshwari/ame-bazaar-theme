<?php
/**
 * Temp script to list all published pages and categories to audit URL paths.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

// 1. Get Pages
$pages = get_posts( array(
	'post_type'      => 'page',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
) );
$page_list = array();
foreach ( $pages as $p ) {
	$page_list[] = array(
		'id'    => $p->ID,
		'title' => $p->post_title,
		'slug'  => $p->post_name,
		'url'   => get_permalink( $p->ID ),
		'meta'  => get_post_meta( $p->ID )
	);
}

// 2. Get Product Categories (WooCommerce)
$prod_cats = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
) );
$prod_cat_list = array();
foreach ( $prod_cats as $cat ) {
	$prod_cat_list[] = array(
		'term_id' => $cat->term_id,
		'name'    => $cat->name,
		'slug'    => $cat->slug,
		'url'     => get_term_link( $cat ),
	);
}

// 3. Get Standard Post Categories
$post_cats = get_terms( array(
	'taxonomy'   => 'category',
	'hide_empty' => false,
) );
$post_cat_list = array();
foreach ( $post_cats as $cat ) {
	$post_cat_list[] = array(
		'term_id' => $cat->term_id,
		'name'    => $cat->name,
		'slug'    => $cat->slug,
		'url'     => get_term_link( $cat ),
	);
}

echo json_encode( array(
	'pages'              => $page_list,
	'product_categories' => $prod_cat_list,
	'post_categories'    => $post_cat_list,
), JSON_PRETTY_PRINT );
