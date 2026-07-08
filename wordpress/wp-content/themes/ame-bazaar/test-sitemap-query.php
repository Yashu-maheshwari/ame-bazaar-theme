<?php
/**
 * Test queries.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "TESTING PAGES QUERY...\n";
$pages = get_posts( array(
	'post_type'      => 'page',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
) );
echo "PAGES FOUND: " . count($pages) . "\n";
foreach ($pages as $p) {
	echo "- ID: {$p->ID}, Title: {$p->post_title}, Slug: {$p->post_name}\n";
}

echo "\nTESTING POSTS QUERY...\n";
$posts = get_posts( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
) );
echo "POSTS FOUND: " . count($posts) . "\n";

echo "\nTESTING PRODUCTS QUERY...\n";
$products = get_posts( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
) );
echo "PRODUCTS FOUND: " . count($products) . "\n";

echo "\nTESTING TERMS QUERY...\n";
$terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
) );
if (is_wp_error($terms)) {
	echo "TERMS ERROR: " . $terms->get_error_message() . "\n";
} else {
	echo "TERMS FOUND: " . count($terms) . "\n";
	foreach ($terms as $t) {
		echo "- Term ID: {$t->term_id}, Name: {$t->name}, Slug: {$t->slug}\n";
	}
}
