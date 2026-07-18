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

// 2. Trust Bar Section
function ame_bazaar_render_trust_bar() {
	get_template_part( 'components/homepage/trust-bar' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_trust_bar', 15 );

// 3. Category Section
function ame_bazaar_render_categories() {
	get_template_part( 'components/categories/categories' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_categories', 20 );

// 4. Featured Collections Section
function ame_bazaar_render_featured_collections() {
	get_template_part( 'components/homepage/featured-collections' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_featured_collections', 25 );

// 4.5 Custom Tailoring Service Section
function ame_bazaar_render_tailoring_service() {
	get_template_part( 'components/homepage/tailoring-service' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_tailoring_service', 28 );

// 4.8 Fashion Advisor
function ame_bazaar_render_fashion_advisor() {
	get_template_part( 'components/homepage/fashion-advisor' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_fashion_advisor', 33 );

// 5. Why AME Bazaar
function ame_bazaar_render_why_choose_us() {
	get_template_part( 'components/why-choose-us/why-choose-us' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_why_choose_us', 30 );

// 6. Customer Reviews
function ame_bazaar_render_reviews() {
	get_template_part( 'components/reviews/reviews' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_reviews', 35 );

// 6.5 FAQ Section
function ame_bazaar_render_faq() {
	get_template_part( 'components/homepage/faq' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_faq', 38 );

// 7. Visit Our Store
function ame_bazaar_render_visit_store() {
	get_template_part( 'components/homepage/visit-store' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_visit_store', 40 );

// 8. About AME Bazaar & Local Info
function ame_bazaar_render_about_business() {
	get_template_part( 'components/about-business/about-business' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_about_business', 45 );

// 9. Blog Preview Section
function ame_bazaar_render_blog_preview() {
	get_template_part( 'components/homepage/blog-preview' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_blog_preview', 50 );

// 9.5 Instagram Showcase Gallery Section
function ame_bazaar_render_instagram_gallery() {
	get_template_part( 'components/homepage/instagram-gallery' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_instagram_gallery', 52 );

// 10. WhatsApp & Newsletter CTA Section
function ame_bazaar_render_whatsapp_cta() {
	get_template_part( 'components/homepage/whatsapp-cta' );
}
add_action( 'ame_bazaar_homepage', 'ame_bazaar_render_whatsapp_cta', 55 );
