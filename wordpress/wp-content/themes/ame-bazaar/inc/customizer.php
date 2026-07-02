<?php
/**
 * Customizer settings for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function ame_bazaar_customize_register( $wp_customize ) {
	// Add Section for Header Info
	$wp_customize->add_section( 'ame_bazaar_header_section', array(
		'title'       => __( 'AME Bazaar Header Settings', 'ame-bazaar' ),
		'priority'    => 30,
		'description' => __( 'Configure header contact options.', 'ame-bazaar' ),
	) );

	// 1. Phone Number Setting
	$wp_customize->add_setting( 'ame_bazaar_phone', array(
		'default'           => '+91 99999 99999',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'ame_bazaar_phone_control', array(
		'label'    => __( 'Phone Number (Call Now)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_header_section',
		'settings' => 'ame_bazaar_phone',
		'type'     => 'text',
	) );

	// 2. Google Maps URL Setting
	$wp_customize->add_setting( 'ame_bazaar_maps_url', array(
		'default'           => 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'ame_bazaar_maps_url_control', array(
		'label'    => __( 'Google Maps URL (Visit Store)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_header_section',
		'settings' => 'ame_bazaar_maps_url',
		'type'     => 'url',
	) );

	// Add Section for Hero
	$wp_customize->add_section( 'ame_bazaar_hero_section', array(
		'title'       => __( 'AME Bazaar Hero Settings', 'ame-bazaar' ),
		'priority'    => 40,
		'description' => __( 'Configure the homepage Hero section.', 'ame-bazaar' ),
	) );

	// 1. Hero Title
	$wp_customize->add_setting( 'ame_bazaar_hero_title', array(
		'default'           => 'Affordable Fashion for Every Family in Kirari, Delhi',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'ame_bazaar_hero_title_control', array(
		'label'    => __( 'Hero Title', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_hero_section',
		'settings' => 'ame_bazaar_hero_title',
		'type'     => 'text',
	) );

	// 2. Hero Subtitle
	$wp_customize->add_setting( 'ame_bazaar_hero_subtitle', array(
		'default'           => "Men's Wear • Women's Wear • Kids Wear • Accessories",
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'ame_bazaar_hero_subtitle_control', array(
		'label'    => __( 'Hero Subtitle', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_hero_section',
		'settings' => 'ame_bazaar_hero_subtitle',
		'type'     => 'text',
	) );

	// 3. Hero Image
	$wp_customize->add_setting( 'ame_bazaar_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ame_bazaar_hero_image_control', array(
		'label'    => __( 'Hero Image', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_hero_section',
		'settings' => 'ame_bazaar_hero_image',
	) ) );

	// Add Section for Categories
	$wp_customize->add_section( 'ame_bazaar_categories_section', array(
		'title'       => __( 'AME Bazaar Categories Settings', 'ame-bazaar' ),
		'priority'    => 50,
		'description' => __( 'Configure the Shop by Category homepage section.', 'ame-bazaar' ),
	) );

	// Section Title & Subtitle
	$wp_customize->add_setting( 'ame_bazaar_cat_section_title', array(
		'default'           => 'Shop by Category',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_cat_section_title_control', array(
		'label'    => __( 'Section Title', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_categories_section',
		'settings' => 'ame_bazaar_cat_section_title',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_cat_section_subtitle', array(
		'default'           => 'Explore our premium fashion collections',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_cat_section_subtitle_control', array(
		'label'    => __( 'Section Subtitle', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_categories_section',
		'settings' => 'ame_bazaar_cat_section_subtitle',
		'type'     => 'text',
	) );

	// Define 4 categories config
	$categories = array(
		'men' => array(
			'label' => 'Men\'s Wear',
			'desc'  => 'Premium clothing carefully selected for style, comfort, and durability. From casual shirts to ethnic wear.',
		),
		'women' => array(
			'label' => 'Women\'s Wear',
			'desc'  => 'Elegant apparel combining traditional heritage and contemporary fashion. Traditional wear, sarees, and casual attire.',
		),
		'kids' => array(
			'label' => 'Kids Wear',
			'desc'  => 'Comfortable, soft, and durable wear for boys, girls, and babies. Crafted with skin-friendly fabrics for active kids.',
		),
		'accessories' => array(
			'label' => 'Accessories',
			'desc'  => 'Timeless fashion accessories, premium leather belts, wallets, and bags to complete your everyday aesthetic.',
		),
	);

	foreach ( $categories as $key => $cat ) {
		// Category description setting
		$wp_customize->add_setting( 'ame_bazaar_cat_' . $key . '_desc', array(
			'default'           => $cat['desc'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_cat_' . $key . '_desc_control', array(
			'label'    => sprintf( __( '%s Description', 'ame-bazaar' ), $cat['label'] ),
			'section'  => 'ame_bazaar_categories_section',
			'settings' => 'ame_bazaar_cat_' . $key . '_desc',
			'type'     => 'textarea',
		) );

		// Category URL setting
		$wp_customize->add_setting( 'ame_bazaar_cat_' . $key . '_url', array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_cat_' . $key . '_url_control', array(
			'label'    => sprintf( __( '%s Target URL', 'ame-bazaar' ), $cat['label'] ),
			'section'  => 'ame_bazaar_categories_section',
			'settings' => 'ame_bazaar_cat_' . $key . '_url',
			'type'     => 'url',
		) );

		// Category image setting
		$wp_customize->add_setting( 'ame_bazaar_cat_' . $key . '_image', array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ame_bazaar_cat_' . $key . '_image_control', array(
			'label'    => sprintf( __( '%s Image', 'ame-bazaar' ), $cat['label'] ),
			'section'  => 'ame_bazaar_categories_section',
			'settings' => 'ame_bazaar_cat_' . $key . '_image',
		) ) );
	}

	// Add Section for Why Choose Us
	$wp_customize->add_section( 'ame_bazaar_why_choose_section', array(
		'title'       => __( 'AME Bazaar Why Choose Us Settings', 'ame-bazaar' ),
		'priority'    => 60,
		'description' => __( 'Configure the Why Choose AME Bazaar homepage section.', 'ame-bazaar' ),
	) );

	// Section Title & Subtitle
	$wp_customize->add_setting( 'ame_bazaar_why_section_title', array(
		'default'           => 'Why Choose AME Bazaar',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_why_section_title_control', array(
		'label'    => __( 'Section Title', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_why_choose_section',
		'settings' => 'ame_bazaar_why_section_title',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_why_section_subtitle', array(
		'default'           => 'Premium Family Fashion Store in Kirari, Delhi',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_why_section_subtitle_control', array(
		'label'    => __( 'Section Subtitle', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_why_choose_section',
		'settings' => 'ame_bazaar_why_section_subtitle',
		'type'     => 'text',
	) );

	// Define 4 cards config
	$why_cards = array(
		1 => array(
			'title' => 'Family-Owned Clothing Store',
			'desc'  => 'Apparel Maheshwari Enterprises provides carefully selected garments for local families. Rooted in trust, quality materials, and personal care since inception.',
		),
		2 => array(
			'title' => 'Affordable Family Fashion',
			'desc'  => 'High-quality collection spanning Men\'s Wear, Women\'s Wear, and Kids Wear at fair prices. Complete shopping for Mubarakpur Road families under one roof.',
		),
		3 => array(
			'title' => 'Tailoring & Alterations',
			'desc'  => 'Dedicated custom tailoring and on-site alteration facility. Get a flawless fit on ethnic coordinates, trousers, and custom sizing options.',
		),
		4 => array(
			'title' => 'Highly Rated Local Business',
			'desc'  => 'Proudly rated 4.8+ Stars on Google Reviews. Recognized for exceptional customer support, local Delhi apparel retail expertise, and honest service.',
		),
	);

	foreach ( $why_cards as $index => $card ) {
		// Title setting
		$wp_customize->add_setting( 'ame_bazaar_why_card' . $index . '_title', array(
			'default'           => $card['title'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_why_card' . $index . '_title_control', array(
			'label'    => sprintf( __( 'Card %d Title', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_why_choose_section',
			'settings' => 'ame_bazaar_why_card' . $index . '_title',
			'type'     => 'text',
		) );

		// Desc setting
		$wp_customize->add_setting( 'ame_bazaar_why_card' . $index . '_desc', array(
			'default'           => $card['desc'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_why_card' . $index . '_desc_control', array(
			'label'    => sprintf( __( 'Card %d Description', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_why_choose_section',
			'settings' => 'ame_bazaar_why_card' . $index . '_desc',
			'type'     => 'textarea',
		) );
	}

	// Add Section for Trust & Reviews
	$wp_customize->add_section( 'ame_bazaar_reviews_section', array(
		'title'       => __( 'AME Bazaar Reviews Settings', 'ame-bazaar' ),
		'priority'    => 70,
		'description' => __( 'Configure the Customer Trust & Google Reviews homepage section.', 'ame-bazaar' ),
	) );

	// Section Title & Subtitle
	$wp_customize->add_setting( 'ame_bazaar_reviews_section_title', array(
		'default'           => 'Trusted by Families Across Kirari',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_reviews_section_title_control', array(
		'label'    => __( 'Section Title', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_reviews_section',
		'settings' => 'ame_bazaar_reviews_section_title',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_reviews_section_subtitle', array(
		'default'           => 'Read genuine feedback from customers who choose AME Bazaar for their family fashion needs.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_reviews_section_subtitle_control', array(
		'label'    => __( 'Section Subtitle', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_reviews_section',
		'settings' => 'ame_bazaar_reviews_section_subtitle',
		'type'     => 'text',
	) );

	// Google Rating & Reviews count settings
	$wp_customize->add_setting( 'ame_bazaar_reviews_google_rating', array(
		'default'           => '4.8',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_reviews_google_rating_control', array(
		'label'    => __( 'Google Star Rating', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_reviews_section',
		'settings' => 'ame_bazaar_reviews_google_rating',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_reviews_count', array(
		'default'           => '100+',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_reviews_count_control', array(
		'label'    => __( 'Total Review Count', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_reviews_section',
		'settings' => 'ame_bazaar_reviews_count',
		'type'     => 'text',
	) );

	// Define 3 testimonials
	$testimonials = array(
		1 => array(
			'name'  => 'Local Customer',
			'text'  => 'Great collection of Men\'s wear and kids clothing. Very fair prices and the staff was extremely helpful.',
			'stars' => '5',
		),
		2 => array(
			'name'  => 'Delhi Shopper',
			'text'  => 'We got custom tailoring done for our festival coordinates here. The fit was absolutely perfect and completed on time.',
			'stars' => '5',
		),
		3 => array(
			'name'  => 'Kirari Resident',
			'text'  => 'One-stop apparel destination for my entire family. Honest recommendations and wonderful local experience.',
			'stars' => '5',
		),
	);

	foreach ( $testimonials as $index => $t ) {
		// Name
		$wp_customize->add_setting( 'ame_bazaar_reviews_t' . $index . '_name', array(
			'default'           => $t['name'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_reviews_t' . $index . '_name_control', array(
			'label'    => sprintf( __( 'Testimonial %d Customer Name', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_reviews_section',
			'settings' => 'ame_bazaar_reviews_t' . $index . '_name',
			'type'     => 'text',
		) );

		// Text
		$wp_customize->add_setting( 'ame_bazaar_reviews_t' . $index . '_text', array(
			'default'           => $t['text'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_reviews_t' . $index . '_text_control', array(
			'label'    => sprintf( __( 'Testimonial %d Review Text', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_reviews_section',
			'settings' => 'ame_bazaar_reviews_t' . $index . '_text',
			'type'     => 'textarea',
		) );

		// Rating Stars
		$wp_customize->add_setting( 'ame_bazaar_reviews_t' . $index . '_stars', array(
			'default'           => $t['stars'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_reviews_t' . $index . '_stars_control', array(
			'label'    => sprintf( __( 'Testimonial %d Star Rating (1-5)', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_reviews_section',
			'settings' => 'ame_bazaar_reviews_t' . $index . '_stars',
			'type'     => 'select',
			'choices'  => array(
				'1' => '1 Star',
				'2' => '2 Stars',
				'3' => '3 Stars',
				'4' => '4 Stars',
				'5' => '5 Stars',
			),
		) );
	}

	// Define 3 trust cards (Family-Owned, Tailoring, Local Trust)
	$trust_cards = array(
		1 => array(
			'title' => 'Family-Owned Store',
			'desc'  => 'Providing personalized styling support and curated garments for all generations of Kirari families.',
		),
		2 => array(
			'title' => 'Tailoring & Alterations',
			'desc'  => 'Get custom fits and on-time stitching services directly inside the store.',
		),
		3 => array(
			'title' => 'Local Trust',
			'desc'  => 'Built on long-term relationships, reliable apparel quality, and honest recommendations.',
		),
	);

	foreach ( $trust_cards as $index => $tc ) {
		// Title
		$wp_customize->add_setting( 'ame_bazaar_reviews_c' . $index . '_title', array(
			'default'           => $tc['title'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_reviews_c' . $index . '_title_control', array(
			'label'    => sprintf( __( 'Trust Card %d Title', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_reviews_section',
			'settings' => 'ame_bazaar_reviews_c' . $index . '_title',
			'type'     => 'text',
		) );

		// Desc
		$wp_customize->add_setting( 'ame_bazaar_reviews_c' . $index . '_desc', array(
			'default'           => $tc['desc'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_reviews_c' . $index . '_desc_control', array(
			'label'    => sprintf( __( 'Trust Card %d Description', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_reviews_section',
			'settings' => 'ame_bazaar_reviews_c' . $index . '_desc',
			'type'     => 'textarea',
		) );
	}

	// Add Section for About & Local Info
	$wp_customize->add_section( 'ame_bazaar_about_section', array(
		'title'       => __( 'AME Bazaar About Section Settings', 'ame-bazaar' ),
		'priority'    => 80,
		'description' => __( 'Configure the About AME Bazaar & Local Info homepage section.', 'ame-bazaar' ),
	) );

	// Section Title & Subtitle
	$wp_customize->add_setting( 'ame_bazaar_about_section_title', array(
		'default'           => 'About AME Bazaar',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_about_section_title_control', array(
		'label'    => __( 'Section Title', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_about_section',
		'settings' => 'ame_bazaar_about_section_title',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_about_section_subtitle', array(
		'default'           => 'Discover our heritage, collections, and values as a trusted local family store.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_about_section_subtitle_control', array(
		'label'    => __( 'Section Subtitle', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_about_section',
		'settings' => 'ame_bazaar_about_section_subtitle',
		'type'     => 'text',
	) );

	// Story Headline & Content
	$wp_customize->add_setting( 'ame_bazaar_about_story_headline', array(
		'default'           => 'Apparel Maheshwari Enterprises - Rooted in Trust',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_about_story_headline_control', array(
		'label'    => __( 'Story Headline', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_about_section',
		'settings' => 'ame_bazaar_about_story_headline',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_about_story_content', array(
		'default'           => 'Located on Mubarakpur Road in Kirari, Delhi, AME Bazaar is dedicated to providing high-quality garments for your entire family. We offer premium Men\'s Wear, Women\'s Wear, Kids\' Wear, Sarees, and fashion Accessories. In addition, our in-store tailoring and alterations service ensures a custom fit for every customer. We encourage you to visit our store for a premium minimal shopping experience.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_about_story_content_control', array(
		'label'    => __( 'Story Description Content', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_about_section',
		'settings' => 'ame_bazaar_about_story_content',
		'type'     => 'textarea',
	) );

	// FAQ 1, 2, 3 questions & answers
	$faqs = array(
		1 => array(
			'q' => 'Where is AME Bazaar located in Delhi?',
			'a' => 'We are located on Mubarakpur Road in Kirari, Delhi, making us easily accessible for shoppers from Baljit Vihar, Prem Nagar, and nearby Delhi areas.',
		),
		2 => array(
			'q' => 'What clothing ranges do you specialize in?',
			'a' => 'We specialize in family apparel including Men\'s Wear, Women\'s Wear, Kids\' Wear, traditional Sarees, and everyday fashion Accessories.',
		),
		3 => array(
			'q' => 'Do you provide custom alterations and tailoring?',
			'a' => 'Yes, we have an in-store tailoring and alterations service to customize fittings for your purchases, ensuring comfortable wear.',
		),
	);

	foreach ( $faqs as $index => $faq ) {
		// Q setting
		$wp_customize->add_setting( 'ame_bazaar_about_faq' . $index . '_q', array(
			'default'           => $faq['q'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_about_faq' . $index . '_q_control', array(
			'label'    => sprintf( __( 'FAQ %d Question', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_about_section',
			'settings' => 'ame_bazaar_about_faq' . $index . '_q',
			'type'     => 'text',
		) );

		// A setting
		$wp_customize->add_setting( 'ame_bazaar_about_faq' . $index . '_a', array(
			'default'           => $faq['a'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'ame_bazaar_about_faq' . $index . '_a_control', array(
			'label'    => sprintf( __( 'FAQ %d Answer', 'ame-bazaar' ), $index ),
			'section'  => 'ame_bazaar_about_section',
			'settings' => 'ame_bazaar_about_faq' . $index . '_a',
			'type'     => 'textarea',
		) );
	}

	// Add Section for Schema & Socials
	$wp_customize->add_section( 'ame_bazaar_schema_section', array(
		'title'       => __( 'AME Bazaar Schema & Socials Settings', 'ame-bazaar' ),
		'priority'    => 90,
		'description' => __( 'Configure structured schema details and social profiles.', 'ame-bazaar' ),
	) );

	// Address line settings
	$wp_customize->add_setting( 'ame_bazaar_street_address', array(
		'default'           => 'Mubarakpur Road',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_street_address_control', array(
		'label'    => __( 'Street Address', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_street_address',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_locality', array(
		'default'           => 'Kirari',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_locality_control', array(
		'label'    => __( 'City / Locality', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_locality',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_region', array(
		'default'           => 'Delhi',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_region_control', array(
		'label'    => __( 'State / Region', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_region',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_postal_code', array(
		'default'           => '110086',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_postal_code_control', array(
		'label'    => __( 'Postal Code', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_postal_code',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'ame_bazaar_country', array(
		'default'           => 'IN',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_country_control', array(
		'label'    => __( 'Country Code (e.g. IN)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_country',
		'type'     => 'text',
	) );

	// Store hours (Schema format)
	$wp_customize->add_setting( 'ame_bazaar_store_hours', array(
		'default'           => 'Mo-Su 09:00–22:00',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_store_hours_control', array(
		'label'    => __( 'Schema Opening Hours (e.g. Mo-Su 09:00–22:00)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_store_hours',
		'type'     => 'text',
	) );

	// Price Range setting
	$wp_customize->add_setting( 'ame_bazaar_price_range', array(
		'default'           => '₹100–₹1000',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_price_range_control', array(
		'label'    => __( 'Price Range (e.g. ₹100–₹1000)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_price_range',
		'type'     => 'text',
	) );

	// Social profile URLs
	$wp_customize->add_setting( 'ame_bazaar_facebook_url', array(
		'default'           => 'https://www.facebook.com/amebazaar',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_facebook_url_control', array(
		'label'    => __( 'Facebook Profile URL', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_facebook_url',
		'type'     => 'url',
	) );

	$wp_customize->add_setting( 'ame_bazaar_instagram_url', array(
		'default'           => 'https://www.instagram.com/amebazaar',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_instagram_url_control', array(
		'label'    => __( 'Instagram Profile URL', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_instagram_url',
		'type'     => 'url',
	) );

	// Areas Served
	$wp_customize->add_setting( 'ame_bazaar_areas_served', array(
		'default'           => 'Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, Rohini',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_areas_served_control', array(
		'label'    => __( 'Areas Served (Comma-separated)', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_areas_served',
		'type'     => 'text',
	) );

	// Latitude
	$wp_customize->add_setting( 'ame_bazaar_latitude', array(
		'default'           => '28.7051',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_latitude_control', array(
		'label'    => __( 'Store Latitude', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_latitude',
		'type'     => 'text',
	) );

	// Longitude
	$wp_customize->add_setting( 'ame_bazaar_longitude', array(
		'default'           => '77.0583',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_longitude_control', array(
		'label'    => __( 'Store Longitude', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_schema_section',
		'settings' => 'ame_bazaar_longitude',
		'type'     => 'text',
	) );

	// Add Section for Footer Settings
	$wp_customize->add_section( 'ame_bazaar_footer_section', array(
		'title'       => __( 'AME Bazaar Footer Settings', 'ame-bazaar' ),
		'priority'    => 100,
		'description' => __( 'Configure footer text and contact links.', 'ame-bazaar' ),
	) );

	// Footer About Text
	$wp_customize->add_setting( 'ame_bazaar_footer_about', array(
		'default'           => 'Apparel Maheshwari Enterprises (AME Bazaar) offers premium fashion apparel for the entire family. Visit our store on Mubarakpur Road, Kirari, Delhi.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_footer_about_control', array(
		'label'    => __( 'Footer Brand Description', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_footer_section',
		'settings' => 'ame_bazaar_footer_about',
		'type'     => 'textarea',
	) );

	// WhatsApp URL
	$wp_customize->add_setting( 'ame_bazaar_whatsapp_url', array(
		'default'           => 'https://wa.me/919999999999?text=Hello%20AME%20Bazaar%2C%20I%20have%20an%20inquiry',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_whatsapp_url_control', array(
		'label'    => __( 'WhatsApp Click-to-Chat URL', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_footer_section',
		'settings' => 'ame_bazaar_whatsapp_url',
		'type'     => 'url',
	) );

	// Store Email
	$wp_customize->add_setting( 'ame_bazaar_email', array(
		'default'           => 'contact@amebazaar.com',
		'sanitize_callback' => 'sanitize_email',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ame_bazaar_email_control', array(
		'label'    => __( 'Store Email Address', 'ame-bazaar' ),
		'section'  => 'ame_bazaar_footer_section',
		'settings' => 'ame_bazaar_email',
		'type'     => 'text',
	) );
}
add_action( 'customize_register', 'ame_bazaar_customize_register' );
