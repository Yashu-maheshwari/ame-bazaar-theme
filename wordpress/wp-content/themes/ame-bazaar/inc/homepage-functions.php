<?php
/**
 * Homepage rendering functions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Hero Section
function ame_bazaar_render_hero() {
	get_template_part( 'components/hero/hero' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_hero', 10 );

// 2. Category Section
function ame_bazaar_render_categories() {
	get_template_part( 'components/categories/categories' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_categories', 20 );

// 3. Why AME Bazaar
function ame_bazaar_render_why_choose_us() {
	get_template_part( 'components/why-choose-us/why-choose-us' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_why_choose_us', 30 );



// 5. Customer Reviews
function ame_bazaar_render_reviews() {
	get_template_part( 'components/reviews/reviews' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_reviews', 50 );

// 5b. About AME Bazaar & Local Info
function ame_bazaar_render_about_business() {
	get_template_part( 'components/about-business/about-business' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_about_business', 55 );



