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
	$brand_name   = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$phone        = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$email        = ame_bazaar_get_business_setting( 'email', 'apparelmaheshwari@gmail.com' );
	$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
	$clean_wa     = preg_replace( '/[^0-9+]/', '', $whatsapp );
	$whatsapp_url = 'https://wa.me/' . ltrim( $clean_wa, '+' );
	$facebook     = ame_bazaar_get_business_setting( 'facebook', 'https://www.facebook.com/AmeBazaar/' );
	$instagram    = ame_bazaar_get_business_setting( 'instagram', 'https://www.instagram.com/ame_bazaar/' );
	$maps_url     = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );

	$schema = array(
		'@type'         => 'Organization',
		'@id'           => home_url( '/#organization' ),
		'name'          => 'AME Bazaar - Family Garment Store',
		'legalName'     => 'Apparel Maheshwari Enterprises',
		'alternateName' => 'AME Bazaar',
		'description'   => 'AME Bazaar (Apparel Maheshwari Enterprises) is a family fashion retail store and custom tailoring showroom located on Mubarakpur Road, Kirari, Delhi, offering men\'s, women\'s, and kids\' clothing.',
		'url'           => home_url( '/' ),
		'foundingDate'  => '2015',
		'knowsAbout'    => array(
			'Ethnic Wear', 'Custom Tailoring', 'Family Fashion', 
			'Men\'s Clothing', 'Women\'s Clothing', 'Kids\' Clothing', 
			'Sarees', 'Fabric Retail'
		),
		'brand'         => array(
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
	if ( ! in_array( 'https://www.facebook.com/AmeBazaar/', $same_as, true ) ) {
		$same_as[] = 'https://www.facebook.com/AmeBazaar/';
	}
	if ( $instagram && '#' !== $instagram ) {
		$same_as[] = $instagram;
	}
	if ( $maps_url ) {
		$same_as[] = $maps_url;
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
	$brand_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$maps_url   = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );

	// Coordinates
	$lat = ame_bazaar_get_business_setting( 'latitude', '28.7051' );
	$lng = ame_bazaar_get_business_setting( 'longitude', '77.0583' );

	// Address details
	$street  = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
	$city    = ame_bazaar_get_business_setting( 'city', 'Kirari' );
	$state   = ame_bazaar_get_business_setting( 'state', 'Delhi' );
	$zip     = ame_bazaar_get_business_setting( 'postal_code', '110086' );
	$country = ame_bazaar_get_business_setting( 'country', 'IN' );

	// Additional info
	$areas_served = get_theme_mod( 'ame_bazaar_areas_served', 'Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, Rohini' );
	$price_range  = get_theme_mod( 'ame_bazaar_price_range', '₹100–₹1000' );
	$facebook     = ame_bazaar_get_business_setting( 'facebook', 'https://www.facebook.com/AmeBazaar/' );
	$instagram    = ame_bazaar_get_business_setting( 'instagram', 'https://www.instagram.com/ame_bazaar/' );
	$primary_cat  = ame_bazaar_get_business_setting( 'primary_category', 'ClothingStore' );

	$schema = array(
		'@type'              => 'ClothingStore',
		'@id'                => home_url( '/#store' ),
		'name'               => $brand_name,
		'description'        => 'Physical clothing showroom and custom tailoring center on Mubarakpur Road, Kirari, Delhi, offering ready-made family garments and tailoring services.',
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
				'url'   => home_url( '/product-category/men/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Women\'s Wear Collection',
				'url'   => home_url( '/product-category/women/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Kids\' Wear Collection',
				'url'   => home_url( '/product-category/kids/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'Accessories Collection',
				'url'   => home_url( '/product-category/accessories/' ),
			),
			array(
				'@type' => 'OfferCatalog',
				'name'  => 'In-Store Tailoring & Custom Garment Alterations',
				'url'   => home_url( '/tailoring-near-me/' ),
			),
		),
	);

	// Removed unverified aggregateRating and Review schema to adhere to AEO/GEO guidelines


	// SameAs Profiles
	$same_as = array();
	if ( $facebook && '#' !== $facebook ) {
		$same_as[] = $facebook;
	}
	if ( ! in_array( 'https://www.facebook.com/AmeBazaar/', $same_as, true ) ) {
		$same_as[] = 'https://www.facebook.com/AmeBazaar/';
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
function ame_bazaar_get_current_url_and_title() {
	$brand_name = ame_bazaar_get_brand_name();
	if ( is_front_page() || is_home() ) {
		return array( home_url( '/' ), get_bloginfo( 'name' ) );
	} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		return array( get_permalink( $shop_page_id ), get_the_title( $shop_page_id ) );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		global $wp;
		return array( home_url( add_query_arg( array(), $wp->request ) ) . '/', single_term_title( '', false ) . ' - ' . $brand_name );
	} elseif ( is_post_type_archive() ) {
		return array( get_post_type_archive_link( get_query_var( 'post_type' ) ), post_type_archive_title( '', false ) . ' - ' . $brand_name );
	} else {
		return array( get_permalink(), get_the_title() );
	}
}

function ame_bazaar_get_webpage_schema() {
	list( $current_url, $current_title ) = ame_bazaar_get_current_url_and_title();
	$schema = array(
		'@type'         => 'WebPage',
		'@id'           => $current_url . '#webpage',
		'url'           => $current_url,
		'name'          => $current_title,
		'inLanguage'    => 'en-US',
		'isPartOf'      => array(
			'@id' => home_url( '/#website' ),
		),
		'breadcrumb'    => array(
			'@id' => $current_url . '#breadcrumbs',
		),
	);

	if ( is_singular() ) {
		$schema['datePublished'] = get_the_date( 'c' );
		$schema['dateModified']  = get_the_modified_date( 'c' );
	}

	if ( is_front_page() ) {
		$schema['about'] = array(
			'@id' => home_url( '/#store' ),
		);
	}

	// Check if this is a registered local entity page or tailoring page
	if ( is_page() ) {
		$entity_type = get_post_meta( get_the_ID(), 'ame_local_entity_type', true );
		$registry    = ame_bazaar_get_entity_registry();
		if ( ( $entity_type && isset( $registry[ $entity_type ] ) ) || is_page( 'tailoring-near-me' ) ) {
			$schema['about'] = array(
				'@id' => home_url( '/#store' ),
			);
			if ( 'tailoring' === $entity_type || is_page( 'tailoring-near-me' ) ) {
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
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$schema['mainEntity'] = array(
			'@id' => get_permalink() . '#product',
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
	} elseif ( is_product_category() ) {
		if ( function_exists( 'ame_bazaar_get_category_faqs' ) ) {
			$term     = get_queried_object();
			$cat_faqs = ame_bazaar_get_category_faqs( $term->slug );
			if ( ! empty( $cat_faqs ) ) {
				foreach ( $cat_faqs as $faq ) {
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
	} elseif ( is_page_template( 'templates/template-ai-advisor.php' ) || is_page_template( 'templates/template-ask-ame.php' ) ) {
		$all_faqs = function_exists( 'ame_bazaar_get_knowledge_base_faqs' ) ? ame_bazaar_get_knowledge_base_faqs() : array();
		foreach ( $all_faqs as $group ) {
			foreach ( $group['faqs'] as $faq ) {
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
	} elseif ( is_page_template( 'templates/template-authority.php' ) ) {
		$all_faqs = function_exists( 'ame_bazaar_get_knowledge_base_faqs' ) ? ame_bazaar_get_knowledge_base_faqs() : array();
		$current_slug = get_post_field( 'post_name', get_the_ID() );
		$authority_faq_map = array(
			'best-clothing-store-in-kirari' => array( 'store_basics', 'kirari_shopping' ),
			'best-mens-wear-shop'           => array( 'men', 'western' ),
			'best-womens-wear-shop'         => array( 'women', 'fabric' ),
			'best-kids-wear-shop'           => array( 'kids', 'accessories' ),
			'affordable-fashion-store'      => array( 'budget', 'payments' ),
			'wedding-shopping-in-kirari'    => array( 'wedding', 'ethnic' ),
			'tailoring-near-me'             => array( 'tailoring', 'size_guide' ),
			'family-clothing-store'         => array( 'store_visit', 'parking' ),
			'festival-shopping-guide'       => array( 'festival', 'care_guide' )
		);
		$keys = isset( $authority_faq_map[ $current_slug ] ) ? $authority_faq_map[ $current_slug ] : array( 'store_basics' );
		foreach ( $keys as $fkey ) {
			if ( isset( $all_faqs[ $fkey ] ) ) {
				foreach ( $all_faqs[ $fkey ]['faqs'] as $faq ) {
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
	} elseif ( is_product() ) {
		// Generate product metadata-driven FAQ items
		$post_id = get_the_ID();
		$fabric  = get_post_meta( $post_id, '_ame_fabric', true );
		$material = get_post_meta( $post_id, '_ame_material', true );
		$gsm     = get_post_meta( $post_id, '_ame_gsm', true );
		$alteration = get_post_meta( $post_id, '_ame_alteration_available', true );
		$care    = get_post_meta( $post_id, '_ame_care_instructions', true );
		$wash    = get_post_meta( $post_id, '_ame_wash_instructions', true );
		$mfr     = get_post_meta( $post_id, '_ame_manufacturer', true );
		$origin  = get_post_meta( $post_id, '_ame_country_of_origin', true );
		$occasion= get_post_meta( $post_id, '_ame_occasion', true );
		$season  = get_post_meta( $post_id, '_ame_season', true );

		$label_mappings = array(
			'_ame_fabric' => array(
				'pure-cotton'   => 'Pure Cotton',
				'mulmul-cotton' => 'Pure Mulmul Cotton',
				'silk'          => 'Silk (Banarasi/Raw)',
				'rayon'         => 'Soft Rayon',
				'georgette'     => 'Georgette',
				'cotton-blend'  => 'Cotton Blend',
				'wool'          => 'Pure Wool / Cashmere',
				'synthetic'     => 'Polyester / Synthetic',
				'denim'         => 'Denim',
			),
			'_ame_occasion' => array(
				'casual'   => 'Casual Daily',
				'formal'   => 'Office Formal',
				'wedding'  => 'Wedding / Ceremony',
				'festival' => 'Festive Shopping',
				'party'    => 'Party Wear',
				'school'   => 'School Wear',
			),
			'_ame_season' => array(
				'all-season' => 'All Seasons',
				'summer'     => 'Summer Wear (Mulmul Cotton)',
				'winter'     => 'Winter Layers',
				'monsoon'    => 'Monsoon Wear',
			),
		);

		$get_lbl = function( $key, $value ) use ( $label_mappings ) {
			if ( isset( $label_mappings[ $key ][ $value ] ) ) {
				return $label_mappings[ $key ][ $value ];
			}
			return $value;
		};

		// 1. Fabric
		if ( $fabric || $material ) {
			$fab_desc = $fabric ? $get_lbl( '_ame_fabric', $fabric ) : '';
			if ( $material ) {
				$fab_desc .= $fab_desc ? ' (' . $material . ')' : $material;
			}
			$wt_desc = $gsm ? ' (GSM: ' . $gsm . ')' : '';
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => sprintf( __( 'What fabric or material is this %s made of?', 'ame-bazaar' ), strtolower( get_the_title() ) ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sprintf( __( 'This garment is crafted from premium %s%s. It is designed to be highly breathable and comfortable for local Delhi weather.', 'ame-bazaar' ), $fab_desc, $wt_desc ),
				),
			);
		}

		// 2. Alteration
		if ( $alteration ) {
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => __( 'Is custom tailoring or alteration available for this garment?', 'ame-bazaar' ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => 'yes' === $alteration || '1' === $alteration 
						? __( 'Yes! We provide on-site custom fitting and hem alterations within 30 minutes at our Mubarakpur Road outlet in Kirari, Delhi.', 'ame-bazaar' )
						: __( 'Standard sizes are available. You can visit our Kirari outlet for fitting consultations with our master tailors.', 'ame-bazaar' ),
				),
			);
		}

		// 3. Care
		if ( $care || $wash ) {
			$care_text = $care ? $care : '';
			$wash_text = $wash ? $wash : '';
			$sep = ($care_text && $wash_text) ? ' | ' : '';
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => __( 'How should I wash and care for this product?', 'ame-bazaar' ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sprintf( __( 'Recommended care: %s%s%s. Proper care ensures the fabric maintains its color and texture for years.', 'ame-bazaar' ), $care_text, $sep, $wash_text ),
				),
			);
		}

		// 4. Origin & Manufacturer
		if ( $mfr || $origin ) {
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => __( 'Where is this garment manufactured?', 'ame-bazaar' ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sprintf( __( 'This premium apparel is manufactured by %s. Country of origin: %s.', 'ame-bazaar' ), $mfr ? $mfr : 'Apparel Maheshwari Enterprises', $origin ? $origin : 'India' ),
				),
			);
		}

		// 5. Occasion & Season
		if ( $occasion || $season ) {
			$occ_lbl = $occasion ? $get_lbl( '_ame_occasion', $occasion ) : '';
			$sea_lbl = $season ? $get_lbl( '_ame_season', $season ) : '';
			$parts = array();
			if ( $occ_lbl ) $parts[] = sprintf( __( 'designed for %s', 'ame-bazaar' ), strtolower( $occ_lbl ) );
			if ( $sea_lbl ) $parts[] = sprintf( __( 'perfect for %s', 'ame-bazaar' ), strtolower( $sea_lbl ) );
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => __( 'What season and occasion is this garment suitable for?', 'ame-bazaar' ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sprintf( __( 'This item is %s. It makes an excellent addition to your seasonal ethnic wardrobe.', 'ame-bazaar' ), implode( ' and ', $parts ) ),
				),
			);
		}
	} elseif ( is_page( 'faq' ) || is_page_template( 'templates/template-faq.php' ) ) {
		// Output global FAQ data for the main FAQ page
		$faq_categories = ame_bazaar_get_knowledge_base_faqs();
		if ( ! empty( $faq_categories ) ) {
			foreach ( $faq_categories as $cat ) {
				foreach ( $cat['faqs'] as $faq ) {
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
	list( $current_url, $current_title ) = ame_bazaar_get_current_url_and_title();

	$schema = array(
		'@type'           => 'BreadcrumbList',
		'@id'             => $current_url . '#breadcrumbs',
		'itemListElement' => array(
			array(
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => $brand_name,
				'item'     => home_url( '/' ),
			),
		),
	);

	if ( ! is_front_page() && ! is_home() ) {
		if ( is_product() && class_exists( 'WooCommerce' ) ) {
			$position = 2;
			$terms = wc_get_product_terms( get_the_ID(), 'product_cat', apply_filters( 'woocommerce_breadcrumb_product_terms_args', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) );
			
			if ( $terms && ! is_wp_error( $terms ) ) {
				$main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
				$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
				$ancestors = array_reverse( $ancestors );
				
				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'product_cat' );
					if ( $ancestor && ! is_wp_error( $ancestor ) ) {
						$schema['itemListElement'][] = array(
							'@type'    => 'ListItem',
							'position' => $position,
							'name'     => $ancestor->name,
							'item'     => get_term_link( $ancestor ),
						);
						$position++;
					}
				}
				
				$schema['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => $main_term->name,
					'item'     => get_term_link( $main_term ),
				);
				$position++;
			}
			
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			);
			
		} elseif ( is_singular() ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			);
		} elseif ( is_archive() || is_tax() || is_category() || is_tag() ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => strip_tags( get_the_archive_title() ),
				'item'     => $current_url,
			);
		} elseif ( is_search() ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => 'Search Results',
				'item'     => home_url( '/?s=' . get_search_query() ),
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
	$phone        = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$email        = ame_bazaar_get_business_setting( 'email', 'contact@amebazaar.com' );
	$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp' );
	$whatsapp_url = $whatsapp ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $whatsapp ) : '';
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
		if ( 'tailoring' === $entity_type || is_page( 'tailoring-near-me' ) ) {
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

	// 12. WooCommerce Product Schema Integration (Active only on single product views)
	if ( is_product() ) {
		$product_schema = ame_bazaar_get_single_product_schema();
		if ( $product_schema ) {
			$graph[] = $product_schema;
		}
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

/**
 * Remove duplicate WooCommerce default structured data to prevent conflicts
 * with our highly optimized custom AME schema.
 */
add_action( 'init', function() {
    if ( class_exists( 'WooCommerce' ) && isset( WC()->structured_data ) ) {
        remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );
    }
});

/**
 * Get WooCommerce Product entity schema with advanced specifications.
 *
 * @return array|bool
 */
function ame_bazaar_get_single_product_schema() {
	if ( ! is_product() || ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	$post_id = get_the_ID();
	$product = wc_get_product( $post_id );
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return false;
	}

	$custom_brand = get_post_meta( $post_id, '_ame_brand', true );
	$brand_name   = $custom_brand ? $custom_brand : ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );

	$brand_schema = array(
		'@type' => 'Brand',
		'name'  => $brand_name,
	);
	if ( empty( $custom_brand ) || 'AME Bazaar' === $custom_brand ) {
		$brand_schema['@id'] = home_url( '/#brand' );
		$brand_schema['url'] = home_url( '/' );
	}

	$image_id  = $product->get_image_id();
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : ame_bazaar_get_custom_logo_url();

	$short_desc = $product->get_short_description();
	$long_desc  = $product->get_description();
	$desc       = $short_desc ? $short_desc : $long_desc;
	$desc       = wp_strip_all_tags( $desc );

	// Offer availability mapping
	$stock_status = $product->get_stock_status();
	if ( 'onbackorder' === $stock_status ) {
		$availability = 'https://schema.org/BackOrder';
	} elseif ( 'outofstock' === $stock_status || ! $product->is_in_stock() ) {
		$availability = 'https://schema.org/OutOfStock';
	} else {
		$availability = 'https://schema.org/InStock';
	}

	$raw_price       = $product->get_price();
	$formatted_price = ( '' !== $raw_price && false !== $raw_price && null !== $raw_price ) ? wc_format_decimal( $raw_price, 2 ) : '0.00';
	$currency        = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'INR';

	$offer = array(
		'@type'         => 'Offer',
		'@id'           => get_permalink( $post_id ) . '#offer',
		'url'           => get_permalink( $post_id ),
		'priceCurrency' => $currency,
		'price'         => $formatted_price,
		'itemCondition' => 'https://schema.org/NewCondition',
		'availability'  => $availability,
		'seller'        => array(
			'@type' => 'ClothingStore',
			'@id'   => home_url( '/#store' ),
			'name'  => ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' ),
		),
	);

	$date_on_sale_to = $product->get_date_on_sale_to();
	if ( $date_on_sale_to && is_a( $date_on_sale_to, 'WC_DateTime' ) ) {
		$offer['priceValidUntil'] = $date_on_sale_to->date( 'Y-m-d' );
	}

	// Build basic Product schema
	$schema = array(
		'@type'       => 'Product',
		'@id'         => get_permalink( $post_id ) . '#product',
		'name'        => $product->get_name(),
		'image'       => $image_url,
		'description' => $desc,
		'brand'       => $brand_schema,
		'offers'      => $offer,
	);

	if ( $product->get_sku() ) {
		$schema['sku'] = $product->get_sku();
	}

	$terms = wc_get_product_terms( $post_id, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
	$category_path    = '';
	$deepest_cat_name = '';
	$top_cat_name     = '';

	if ( $terms && ! is_wp_error( $terms ) ) {
		// Pick the most specific term (deepest term with a parent if available)
		$deepest_term = $terms[0];
		foreach ( $terms as $t ) {
			if ( $t->parent > 0 ) {
				$deepest_term = $t;
				break;
			}
		}
		$deepest_cat_name = $deepest_term->name;

		$ancestors = get_ancestors( $deepest_term->term_id, 'product_cat' );
		$hierarchy = array();
		if ( ! empty( $ancestors ) ) {
			foreach ( array_reverse( $ancestors ) as $anc_id ) {
				$anc_term = get_term( $anc_id, 'product_cat' );
				if ( $anc_term && ! is_wp_error( $anc_term ) ) {
					$hierarchy[] = $anc_term->name;
					if ( empty( $top_cat_name ) ) {
						$top_cat_name = $anc_term->name;
					}
				}
			}
		}
		$hierarchy[] = $deepest_term->name;
		if ( empty( $top_cat_name ) ) {
			$top_cat_name = $deepest_term->name;
		}

		$category_path = implode( ' > ', $hierarchy );
		$schema['category'] = $category_path;
	}

	// Custom properties map
	$property_mappings = array(
		'GSM'                  => '_ame_gsm',
		'Fabric Weight'        => '_ame_fabric_weight',
		'Age Group'            => '_ame_age_group',
		'Fit'                  => '_ame_fit',
		'Sleeve Type'          => '_ame_sleeve_type',
		'Neck Type'            => '_ame_neck_type',
		'Closure'              => '_ame_closure',
		'Collection'           => '_ame_collection',
		'Style'                => '_ame_style',
		'MRP'                  => '_ame_mrp',
		'Price Segment'        => '_ame_price_segment',
		'Size'                 => '_ame_size_flat',
		'Size Chart'           => '_ame_size_chart',
		'Wash Instructions'    => '_ame_wash_instructions',
		'Care Instructions'    => '_ame_care_instructions',
		'Country of Origin'    => '_ame_country_of_origin',
		'Manufacturer'         => '_ame_manufacturer',
		'Kirari Stock'         => '_ame_kirari_stock',
		'AI Keywords'          => '_ame_ai_keywords',
		'GEO Targets'          => '_ame_geo_target',
		'Target Demographic'   => '_ame_target_customer',
		'Trending Status'      => '_ame_trending',
		'Featured Reason'      => '_ame_featured_reason',
		'WhatsApp Commerce'    => '_ame_whatsapp_ready',
		'Local Availability'   => '_ame_local_availability',
	);

	$additional_properties = array();

	if ( ! empty( $category_path ) ) {
		$additional_properties[] = array(
			'@type' => 'PropertyValue',
			'name'  => 'Category Hierarchy',
			'value' => $category_path,
		);
	}
	if ( ! empty( $deepest_cat_name ) ) {
		$additional_properties[] = array(
			'@type' => 'PropertyValue',
			'name'  => 'Product Type',
			'value' => $deepest_cat_name,
		);
	}
	if ( ! empty( $top_cat_name ) && $top_cat_name !== $deepest_cat_name ) {
		$additional_properties[] = array(
			'@type' => 'PropertyValue',
			'name'  => 'Department',
			'value' => $top_cat_name,
		);
	}

	foreach ( $property_mappings as $label => $meta_key ) {
		$val = get_post_meta( $post_id, $meta_key, true );
		if ( $val ) {
			$additional_properties[] = array(
				'@type' => 'PropertyValue',
				'name'  => $label,
				'value' => $val,
			);
		}
	}

	// Sizing parameters mapping to standard fields
	$flat_size = get_post_meta( $post_id, '_ame_size_flat', true );
	if ( $flat_size ) {
		$schema['size'] = $flat_size;
	}

	// Safe Raintech Category mapping to audienceType
	$audience_set = false;
	$raintech_raw = get_post_meta( $post_id, '_ame_raw_raintech_data', true );
	if ( is_array( $raintech_raw ) && ! empty( $raintech_raw['Category'] ) ) {
		$category     = trim( (string) $raintech_raw['Category'] );
		$category_key = strtolower( preg_replace( '/\s+/', ' ', $category ) );

		$gender_map = array(
			'mens wear'        => 'Men',
			'female wear'      => 'Women',
			'kids wear(boys)'  => 'Boys',
			'kids wear(girls)' => 'Girls',
			'infant wear'      => 'Infants',
		);

		if ( isset( $gender_map[ $category_key ] ) ) {
			$schema['audience'] = array(
				'@type'        => 'Audience',
				'audienceType' => $gender_map[ $category_key ],
			);
			$audience_set = true;
		}
	}

	// Dynamic fallback from existing verified WooCommerce taxonomy
	if ( ! $audience_set && ! empty( $top_cat_name ) ) {
		$top_lower = strtolower( $top_cat_name );
		if ( 'men' === $top_lower || "men's wear" === $top_lower ) {
			$schema['audience'] = array(
				'@type'        => 'Audience',
				'audienceType' => 'Men',
			);
		} elseif ( 'women' === $top_lower || "women's wear" === $top_lower ) {
			$schema['audience'] = array(
				'@type'        => 'Audience',
				'audienceType' => 'Women',
			);
		} elseif ( 'kids' === $top_lower || "kids' wear" === $top_lower ) {
			$schema['audience'] = array(
				'@type'        => 'Audience',
				'audienceType' => 'Kids',
			);
		}
	}

	if ( ! empty( $additional_properties ) ) {
		$schema['additionalProperty'] = $additional_properties;
	}

	return apply_filters( 'ame_bazaar_single_product_schema', $schema );
}
