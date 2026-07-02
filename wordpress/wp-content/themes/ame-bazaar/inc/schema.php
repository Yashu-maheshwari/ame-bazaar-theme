<?php
/**
 * Structured data helpers and connected JSON-LD Entity Graph generator.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get ImageObject schema for the store logo.
 *
 * @return array|bool
 */
function ame_bazaar_get_logo_image_schema() {
	$logo_url = ame_bazaar_get_custom_logo_url();
	if ( ! $logo_url ) {
		return false;
	}

	$brand_name = ame_bazaar_get_brand_name();

	$schema = array(
		'@type'      => 'ImageObject',
		'@id'        => home_url( '/#logo' ),
		'url'        => $logo_url,
		'caption'    => $brand_name,
		'inLanguage' => 'en-US',
	);

	return apply_filters( 'ame_bazaar_logo_image_schema', $schema );
}

/**
 * Get Brand entity schema.
 *
 * @return array
 */
function ame_bazaar_get_brand_schema() {
	$brand_name = ame_bazaar_get_brand_name();

	$schema = array(
		'@type' => 'Brand',
		'@id'   => home_url( '/#brand' ),
		'name'  => $brand_name,
		'url'   => home_url( '/' ),
	);

	if ( ame_bazaar_get_custom_logo_url() ) {
		$schema['logo'] = array(
			'@id' => home_url( '/#logo' ),
		);
	}

	return apply_filters( 'ame_bazaar_brand_schema', $schema );
}

/**
 * Get WebSite entity schema with sitelinks searchbox.
 *
 * @return array
 */
function ame_bazaar_get_website_schema() {
	$brand_name = ame_bazaar_get_brand_name();

	$schema = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => $brand_name,
		'publisher'       => array(
			'@id' => home_url( '/#organization' ),
		),
		'potentialAction' => array(
			array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		),
	);

	return apply_filters( 'ame_bazaar_website_schema', $schema );
}

/**
 * Get Organization entity schema.
 *
 * @return array
 */
function ame_bazaar_get_organization_schema() {
	$brand_name   = ame_bazaar_get_brand_name();
	$phone        = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
	$email        = get_theme_mod( 'ame_bazaar_email', 'contact@amebazaar.com' );
	$whatsapp_url = get_theme_mod( 'ame_bazaar_whatsapp_url', '' );
	$facebook     = get_theme_mod( 'ame_bazaar_facebook_url', 'https://www.facebook.com/amebazaar' );
	$instagram    = get_theme_mod( 'ame_bazaar_instagram_url', 'https://www.instagram.com/amebazaar' );

	$schema = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => $brand_name,
		'url'   => home_url( '/' ),
		'brand' => array(
			'@id' => home_url( '/#brand' ),
		),
	);

	if ( ame_bazaar_get_custom_logo_url() ) {
		$schema['logo'] = array(
			'@id' => home_url( '/#logo' ),
		);
		$schema['image'] = array(
			'@id' => home_url( '/#logo' ),
		);
	}

	// Contact Point for Customer Support
	$contact_point = array(
		'@type'       => 'ContactPoint',
		'telephone'   => $phone,
		'contactType' => 'customer support',
		'email'       => $email,
	);
	if ( $whatsapp_url ) {
		$contact_point['url'] = $whatsapp_url;
	}
	$schema['contactPoint'] = $contact_point;

	// SameAs Profiles
	$same_as = array();
	if ( $facebook && '#' !== $facebook ) {
		$same_as[] = $facebook;
	}
	if ( $instagram && '#' !== $instagram ) {
		$same_as[] = $instagram;
	}
	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = $same_as;
	}

	return apply_filters( 'ame_bazaar_organization_schema', $schema );
}

/**
 * Get ClothingStore / LocalBusiness entity schema.
 *
 * @return array
 */
function ame_bazaar_get_clothing_store_schema() {
	$brand_name = ame_bazaar_get_brand_name();
	$phone      = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
	$maps_url   = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );

	// Coordinates from theme config (Customizer)
	$lat = get_theme_mod( 'ame_bazaar_latitude', '28.7051' );
	$lng = get_theme_mod( 'ame_bazaar_longitude', '77.0583' );

	// Address details
	$street  = get_theme_mod( 'ame_bazaar_street_address', 'Mubarakpur Road' );
	$city    = get_theme_mod( 'ame_bazaar_locality', 'Kirari' );
	$state   = get_theme_mod( 'ame_bazaar_region', 'Delhi' );
	$zip     = get_theme_mod( 'ame_bazaar_postal_code', '110086' );
	$country = get_theme_mod( 'ame_bazaar_country', 'IN' );

	// Additional info
	$areas_served = get_theme_mod( 'ame_bazaar_areas_served', 'Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, Rohini' );
	$price_range  = get_theme_mod( 'ame_bazaar_price_range', '₹100–₹1000' );
	$facebook     = get_theme_mod( 'ame_bazaar_facebook_url', 'https://www.facebook.com/amebazaar' );
	$instagram    = get_theme_mod( 'ame_bazaar_instagram_url', 'https://www.instagram.com/amebazaar' );

	$schema = array(
		'@type'              => 'ClothingStore',
		'@id'                => home_url( '/#store' ),
		'name'               => $brand_name,
		'url'                => home_url( '/' ),
		'telephone'          => $phone,
		'priceRange'         => $price_range,
		'hasMap'             => $maps_url,
		'parentOrganization' => array(
			'@id' => home_url( '/#organization' ),
		),
		'brand'              => array(
			'@id' => home_url( '/#brand' ),
		),
		'address'            => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $street,
			'addressLocality' => $city,
			'addressRegion'   => $state,
			'postalCode'      => $zip,
			'addressCountry'  => $country,
		),
		'geo'                => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $lat,
			'longitude' => $lng,
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '09:00',
				'closes'    => '22:00',
			),
		),
		'paymentAccepted'    => 'Cash, UPI, Credit Card, Debit Card, Digital Wallets',
		'currenciesAccepted' => 'INR',
	);

	if ( ame_bazaar_get_custom_logo_url() ) {
		$schema['image'] = array(
			'@id' => home_url( '/#logo' ),
		);
	}

	if ( $areas_served ) {
		$areas = array_map( 'trim', explode( ',', $areas_served ) );
		$schema['areaServed'] = $areas;
	}

	// Service & Product Offers Catalog (WooCommerce and Merchant Center compatible)
	$schema['hasOfferCatalog'] = array(
		'@type' => 'OfferCatalog',
		'@id'   => home_url( '/#catalog' ),
		'name'  => $brand_name . ' Fashion Catalog',
		'itemListElement' => array(
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Men\'s Wear Collection',
				'url'   => home_url( '/category/mens-wear/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Women\'s Wear Collection',
				'url'   => home_url( '/category/womens-wear/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Kids\' Wear Collection',
				'url'   => home_url( '/category/kids-wear/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Accessories Collection',
				'url'   => home_url( '/category/accessories/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'In-Store Tailoring & Custom Garment Alterations',
			),
		),
	);

	// Aggregate Rating from Customizer values
	$rating_val   = get_theme_mod( 'ame_bazaar_reviews_google_rating', '4.8' );
	$review_count = get_theme_mod( 'ame_bazaar_reviews_count', '100' );
	// Clean review count to extract digits only
	$review_count_clean = preg_replace( '/[^0-9]/', '', $review_count );
	if ( ! $review_count_clean ) {
		$review_count_clean = '100';
	}

	$schema['aggregateRating'] = array(
		'@type'       => 'AggregateRating',
		'ratingValue' => $rating_val,
		'reviewCount' => $review_count_clean,
		'bestRating'  => '5',
		'worstRating' => '1',
	);

	// Testimonials review mapping
	$reviews = array();
	for ( $index = 1; $index <= 3; $index++ ) {
		$t_name  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_name' );
		$t_text  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_text' );
		$t_stars = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_stars', '5' );

		if ( $t_name && $t_text ) {
			$reviews[] = array(
				'@type'  => 'Review',
				'author' => array(
					'@type' => 'Person',
					'name'  => $t_name,
				),
				'reviewBody'   => $t_text,
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $t_stars,
					'bestRating'  => '5',
					'worstRating' => '1',
				),
			);
		}
	}
	if ( ! empty( $reviews ) ) {
		$schema['review'] = $reviews;
	}

	// SameAs Profiles
	$same_as = array();
	if ( $facebook && '#' !== $facebook ) {
		$same_as[] = $facebook;
	}
	if ( $instagram && '#' !== $instagram ) {
		$same_as[] = $instagram;
	}
	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = $same_as;
	}

	return apply_filters( 'ame_bazaar_clothing_store_schema', $schema );
}

/**
 * Get WebPage entity schema.
 *
 * @return array
 */
function ame_bazaar_get_webpage_schema() {
	$schema = array(
		'@type'      => 'WebPage',
		'@id'        => get_permalink() . '#webpage',
		'url'        => get_permalink(),
		'name'       => get_the_title(),
		'inLanguage' => 'en-US',
		'isPartOf'   => array(
			'@id' => home_url( '/#website' ),
		),
		'breadcrumb' => array(
			'@id' => get_permalink() . '#breadcrumbs',
		),
	);

	if ( is_front_page() ) {
		$schema['about'] = array(
			'@id' => home_url( '/#store' ),
		);
	}

	// Check if this is a registered local entity page
	if ( is_page() ) {
		$entity_type = get_post_meta( get_the_ID(), 'ame_local_entity_type', true );
		$registry    = ame_bazaar_get_entity_registry();
		if ( $entity_type && isset( $registry[ $entity_type ] ) ) {
			$schema['about'] = array(
				'@id' => home_url( '/#store' ),
			);
			if ( 'tailoring' === $entity_type ) {
				$schema['mainEntity'] = array(
					'@id' => get_permalink() . '#service',
				);
			}
		}
	}

	// Single blog posts webpage connectivity
	if ( is_singular( 'post' ) ) {
		$schema['mainEntity'] = array(
			'@id' => get_permalink() . '#article',
		);
	}

	return apply_filters( 'ame_bazaar_webpage_schema', $schema );
}

/**
 * Get FAQ Page entity schema if homepage or local page questions exist.
 *
 * @return array|bool
 */
function ame_bazaar_get_faq_schema() {
	$questions = array();

	if ( is_front_page() ) {
		for ( $index = 1; $index <= 3; $index++ ) {
			$q = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_q' );
			$a = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_a' );

			if ( $q && $a ) {
				$questions[] = array(
					'@type'          => 'Question',
					'name'           => $q,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $a,
					),
				);
			}
		}
	} elseif ( is_page() || is_singular( 'post' ) ) {
		// Read custom page/post FAQs meta (registered custom post meta array)
		$local_faqs = get_post_meta( get_the_ID(), 'ame_local_faqs', true );
		if ( is_array( $local_faqs ) ) {
			foreach ( $local_faqs as $faq ) {
				if ( ! empty( $faq['q'] ) && ! empty( $faq['a'] ) ) {
					$questions[] = array(
						'@type'          => 'Question',
						'name'           => $faq['q'],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $faq['a'],
						),
					);
				}
			}
		}
	}

	if ( empty( $questions ) ) {
		return false;
	}

	$schema = array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink() . '#faq',
		'mainEntity' => $questions,
	);

	return apply_filters( 'ame_bazaar_faq_schema', $schema );
}

/**
 * Get Breadcrumb entity schema.
 *
 * @return array
 */
function ame_bazaar_get_breadcrumb_schema() {
	$brand_name = ame_bazaar_get_brand_name();

	$schema = array(
		'@type'           => 'BreadcrumbList',
		'@id'             => get_permalink() . '#breadcrumbs',
		'itemListElement' => array(
			array(
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => $brand_name,
				'item'     => home_url( '/' ),
			),
		),
	);

	if ( ! is_front_page() ) {
		if ( is_singular() ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			);
		} elseif ( is_archive() ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => get_the_archive_title(),
				'item'     => get_permalink(),
			);
		}
	}

	return apply_filters( 'ame_bazaar_breadcrumb_schema', $schema );
}

/**
 * Get BlogPosting / Article entity schema for single blog posts.
 *
 * @return array|bool
 */
function ame_bazaar_get_article_schema() {
	if ( ! is_singular( 'post' ) ) {
		return false;
	}

	$summary   = get_post_meta( get_the_ID(), 'ame_factual_summary', true );
	$aut_title = get_post_meta( get_the_ID(), 'ame_author_title', true );

	$desc = ! empty( $summary ) ? $summary : wp_strip_all_tags( get_the_excerpt() );

	// Author Person node
	$author = array(
		'@type' => 'Person',
		'@id'   => get_author_posts_url( get_the_author_meta( 'ID' ) ) . '#author',
		'name'  => get_the_author(),
		'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
	);
	if ( ! empty( $aut_title ) ) {
		$author['jobTitle'] = $aut_title;
		$author['worksFor'] = array(
			'@id' => home_url( '/#organization' ),
		);
	}

	$schema = array(
		'@type'            => 'BlogPosting',
		'@id'              => get_permalink() . '#article',
		'isPartOf'         => array(
			'@id' => get_permalink() . '#webpage',
		),
		'mainEntityOfPage' => get_permalink(),
		'headline'         => get_the_title(),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => $author,
		'publisher'        => array(
			'@id' => home_url( '/#organization' ),
		),
		'description'      => $desc,
		'inLanguage'       => 'en-US',
	);

	if ( has_post_thumbnail() ) {
		$schema['image'] = array(
			'@type' => 'ImageObject',
			'url'   => get_the_post_thumbnail_url( null, 'full' ),
		);
	}

	// Add associated entity keywords
	$keywords = array();
	$fabric   = get_post_meta( get_the_ID(), 'ame_associated_fabric', true );
	$occasion = get_post_meta( get_the_ID(), 'ame_associated_occasion', true );
	$season   = get_post_meta( get_the_ID(), 'ame_associated_season', true );

	if ( $fabric ) {
		$keywords[] = $fabric;
	}
	if ( $occasion ) {
		$keywords[] = $occasion;
	}
	if ( $season ) {
		$keywords[] = $season;
	}
	if ( ! empty( $keywords ) ) {
		$schema['keywords'] = implode( ', ', $keywords );
	}

	return apply_filters( 'ame_bazaar_article_schema', $schema );
}

/**
 * Get ProfilePage / Person schema for author pages.
 *
 * @return array|bool
 */
function ame_bazaar_get_author_profile_schema() {
	if ( ! is_author() ) {
		return false;
	}

	$author_id   = get_query_var( 'author' );
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$author_desc = get_the_author_meta( 'description', $author_id );
	$author_url  = get_author_posts_url( $author_id );

	$schema = array(
		'@type'      => 'ProfilePage',
		'@id'        => $author_url . '#webpage',
		'url'        => $author_url,
		'name'       => sprintf( __( 'Author Profile: %s', 'ame-bazaar' ), $author_name ),
		'inLanguage' => 'en-US',
		'isPartOf'   => array(
			'@id' => home_url( '/#website' ),
		),
		'mainEntity' => array(
			'@type'       => 'Person',
			'@id'         => $author_url . '#author',
			'name'        => $author_name,
			'description' => $author_desc,
			'jobTitle'    => 'Fashion Advisor',
			'worksFor'    => array(
				'@id' => home_url( '/#organization' ),
			),
		),
	);

	return apply_filters( 'ame_bazaar_author_profile_schema', $schema );
}

/**
 * Get Tailoring Service Schema.
 *
 * @return array
 */
function ame_bazaar_get_tailoring_service_schema() {
	$phone        = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
	$email        = get_theme_mod( 'ame_bazaar_email', 'contact@amebazaar.com' );
	$whatsapp_url = get_theme_mod( 'ame_bazaar_whatsapp_url', '' );
	$areas_served = get_theme_mod( 'ame_bazaar_areas_served', 'Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, Rohini' );

	$schema = array(
		'@type'       => 'Service',
		'@id'         => get_permalink() . '#service',
		'name'        => 'Custom Tailoring & Alteration Services',
		'serviceType' => 'Tailoring & Alteration',
		'provider'    => array(
			'@id' => home_url( '/#store' ),
		),
		'hoursAvailable' => array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
			'opens'     => '09:00',
			'closes'    => '22:00',
		),
		'offers'      => array(
			'@type'         => 'Offer',
			'priceRange'    => '₹100–₹1000',
			'priceCurrency' => 'INR',
		),
	);

	if ( $areas_served ) {
		$areas = array_map( 'trim', explode( ',', $areas_served ) );
		$schema['areaServed'] = $areas;
	}

	// Service Available Channels
	$channel = array(
		'@type'        => 'ServiceChannel',
		'name'         => 'In-Store Support Helpline',
		'servicePhone' => $phone,
		'serviceUrl'   => get_permalink(),
	);
	if ( $whatsapp_url ) {
		$channel['servicePostalAddress'] = $whatsapp_url;
	}
	$schema['availableChannel'] = $channel;

	return apply_filters( 'ame_bazaar_tailoring_service_schema', $schema );
}

/**
 * Output combined connected JSON-LD Entity Graph in the head.
 */
function ame_bazaar_output_schema() {
	if ( is_admin() ) {
		return;
	}

	// Bypass theme schema entirely if Rank Math, Yoast SEO, or SEOPress is active.
	if ( class_exists( 'RankMath' ) || defined( 'WPSEO_VERSION' ) || class_exists( 'WPSEO_Frontend' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}

	$graph = array();

	// 1. ImageObject (Logo) Entity
	$logo = ame_bazaar_get_logo_image_schema();
	if ( $logo ) {
		$graph[] = $logo;
	}

	// 2. Brand Entity
	$brand = ame_bazaar_get_brand_schema();
	if ( $brand ) {
		$graph[] = $brand;
	}

	// 3. WebSite Entity
	$website = ame_bazaar_get_website_schema();
	if ( $website ) {
		$graph[] = $website;
	}

	// 4. Organization Entity
	$org = ame_bazaar_get_organization_schema();
	if ( $org ) {
		$graph[] = $org;
	}

	// 5. ClothingStore (LocalBusiness) Entity
	$store = ame_bazaar_get_clothing_store_schema();
	if ( $store ) {
		$graph[] = $store;
	}

	// 6. WebPage Entity
	$webpage = ame_bazaar_get_webpage_schema();
	if ( $webpage ) {
		$graph[] = $webpage;
	}

	// 7. BlogPosting / Article Entity (Single Posts Only)
	if ( is_singular( 'post' ) ) {
		$article = ame_bazaar_get_article_schema();
		if ( $article ) {
			$graph[] = $article;
		}
	}

	// 8. Author Profile Entity (Author Archive Only)
	if ( is_author() ) {
		$author_profile = ame_bazaar_get_author_profile_schema();
		if ( $author_profile ) {
			$graph[] = $author_profile;
		}
	}

	// 9. Tailoring Service Entity (Conditional on tailoring page)
	if ( is_page() ) {
		$entity_type = get_post_meta( get_the_ID(), 'ame_local_entity_type', true );
		if ( 'tailoring' === $entity_type ) {
			$service = ame_bazaar_get_tailoring_service_schema();
			if ( $service ) {
				$graph[] = $service;
			}
		}
	}

	// 10. Breadcrumbs Entity
	$breadcrumbs = ame_bazaar_get_breadcrumb_schema();
	if ( $breadcrumbs ) {
		$graph[] = $breadcrumbs;
	}

	// 11. FAQ Page Entity (Conditional)
	$faq = ame_bazaar_get_faq_schema();
	if ( $faq ) {
		$graph[] = $faq;
	}

	if ( empty( $graph ) ) {
		return;
	}

	$output = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $output, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ame_bazaar_output_schema', 20 );
