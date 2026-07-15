<?php
/**
 * Programmatic category architecture bootstrap for AME Bazaar WooCommerce.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_bootstrap_categories() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$categories_structure = array(
		'mens-wear' => array(
			'name'        => 'Men Wear',
			'description' => 'Premium men\'s shirts, casual t-shirts, stretchable denim jeans, trousers, and traditional kurta pajamas.',
			'children'    => array(
				'men-formal-shirts'  => array(
					'name'        => 'Formal Shirts',
					'description' => 'Premium cotton and blended formal office shirts.',
				),
				'men-casual-tshirts' => array(
					'name'        => 'Casual T-Shirts',
					'description' => 'Combed cotton round-neck and polo t-shirts.',
				),
				'men-jeans-trousers' => array(
					'name'        => 'Jeans & Trousers',
					'description' => 'Slim stretch denim jeans and flat-front trousers.',
				),
				'men-ethnic-wear'    => array(
					'name'        => 'Ethnic Wear',
					'description' => 'Kurta pajamas, Nehru jackets, and wedding sherwanis.',
				),
				'men-winter-wear'    => array(
					'name'        => 'Winter Wear',
					'description' => 'Fleece-lined hoodies, sweatshirts, and pull-over woolens.',
				),
			),
		),
		'womens-wear' => array(
			'name'        => 'Women Wear',
			'description' => 'Designer ladies suits, daily-wear kurtis, rayon co-ords, soft sarees, cardigans, and nightwear.',
			'children'    => array(
				'women-kurtis-suits' => array(
					'name'        => 'Kurtis & Suits',
					'description' => 'Printed daily kurtis, salwar suits, and unstitched materials.',
				),
				'women-daily-wear'   => array(
					'name'        => 'Daily Wear & Bottoms',
					'description' => 'Four-way stretch leggings, palazzos, and ladies denim.',
				),
				'women-nightwear'    => array(
					'name'        => 'Nightwear & Lounge',
					'description' => 'Pure cotton nighties, hosiery sets, and matching pajamas.',
				),
				'women-winter-wear'  => array(
					'name'        => 'Winter Wear',
					'description' => 'Ladies wool cardigans, sweaters, and warm woolen suits.',
				),
			),
		),
		'boys-wear' => array(
			'name'        => 'Boys Wear',
			'description' => 'Hypoallergenic soft cotton shirts, tees, jeans, and ethnic wear for boys aged 0 to 14 years.',
			'children'    => array(
				'boys-tops'    => array(
					'name'        => 'Shirts & Tees',
					'description' => 'Casual graphic tees and shirts for young boys.',
				),
				'boys-bottoms' => array(
					'name'        => 'Jeans & Shorts',
					'description' => 'Stretch denim and active shorts with adjustable waists.',
				),
				'boys-ethnic'  => array(
					'name'        => 'Ethnic Wear',
					'description' => 'Boys dhoti-kurta sets and Nehru jackets.',
				),
				'boys-winter'  => array(
					'name'        => 'Winter Wear',
					'description' => 'Boys woolen sweaters, caps, and thick winter innerwear.',
				),
			),
		),
		'girls-wear' => array(
			'name'        => 'Girls Wear',
			'description' => 'Hypoallergenic and soft-lined dresses, frocks, lehenga cholis, and bottoms for girls.',
			'children'    => array(
				'girls-dresses-frocks' => array(
					'name'        => 'Dresses & Frocks',
					'description' => 'Premium party wear frocks, net dresses, and casual frocks.',
				),
				'girls-tops'           => array(
					'name'        => 'Tops & Tees',
					'description' => 'Comfortable daily wear tops and t-shirts.',
				),
				'girls-bottoms'        => array(
					'name'        => 'Leggings & Jeans',
					'description' => 'Soft stretch leggings and denim jeans.',
				),
				'girls-ethnic'         => array(
					'name'        => 'Ethnic Wear',
					'description' => 'Girls lehenga cholis, salwar suits, and festive coordinates.',
				),
				'girls-winter'         => array(
					'name'        => 'Winter Wear',
					'description' => 'Girls winter cardigans, thick inner thermals, and caps.',
				),
			),
		),
		'sarees' => array(
			'name'        => 'Sarees',
			'description' => 'Lightweight georgettes, rayon, zari-bordered silk, and printed daily wear sarees.',
			'children'    => array(
				'silk-sarees'             => array(
					'name'        => 'Silk Sarees',
					'description' => 'Traditional wedding banarasi and raw silk sarees.',
				),
				'georgette-rayon-sarees'  => array(
					'name'        => 'Georgette & Rayon',
					'description' => 'Lightweight, flowy georgette and soft rayon printed sarees.',
				),
				'printed-daily-sarees'    => array(
					'name'        => 'Daily Wear Sarees',
					'description' => 'Low-maintenance, printed cotton and synthetic drapes.',
				),
				'designer-festive-sarees' => array(
					'name'        => 'Designer & Festive',
					'description' => 'Heavy embroidered and border-embellished festival sarees.',
				),
			),
		),
		'accessories' => array(
			'name'        => 'Accessories',
			'description' => 'Gents belts, wallets, cotton and woolen socks, handkerchiefs, and school uniform items.',
			'children'    => array(
				'gents-belts-wallets'       => array(
					'name'        => 'Belts & Wallets',
					'description' => 'Premium genuine leather belts and classic wallets.',
				),
				'socks-handkerchiefs'       => array(
					'name'        => 'Socks & Handkerchiefs',
					'description' => 'Cotton dress socks, school socks, and pure cotton handkerchiefs.',
				),
				'winter-accessories'        => array(
					'name'        => 'Winter Essentials',
					'description' => 'Woolen beanies, caps, gloves, and thick winter thermals.',
				),
				'school-uniform-accessories' => array(
					'name'        => 'School Uniforms',
					'description' => 'Standard school uniform belts, ties, and white/black socks.',
				),
			),
		),
		'footwear' => array(
			'name'        => 'Footwear',
			'description' => 'Daily wear sandals, school shoes, Mojaris, Juttis, and running sneakers (expansion plans).',
			'children'    => array(
				'daily-sandals' => array(
					'name'        => 'Daily Sandals & Slippers',
					'description' => 'High-comfort rubber and leatherette daily wear footwear.',
				),
				'formal-shoes'  => array(
					'name'        => 'Formal Shoes',
					'description' => 'Classic brown and black gents office shoes.',
				),
				'casual-sneakers' => array(
					'name'        => 'Casual Sneakers',
					'description' => 'Lightweight running and walking sneakers.',
				),
				'kids-footwear'  => array(
					'name'        => 'Kids Footwear',
					'description' => 'Soft baby booties and straps sandals for children.',
				),
			),
		),
		'tailoring' => array(
			'name'        => 'Tailoring Services',
			'description' => 'In-store custom tailoring for gents ethnic wear, ladies suits, and alterations.',
			'children'    => array(
				'custom-gents-stitching'     => array(
					'name'        => 'Gents Stitching',
					'description' => 'Custom stitched Kurta Pajamas, Jodhpuri suits, and waistcoats.',
				),
				'custom-ladies-stitching'    => array(
					'name'        => 'Ladies Stitching',
					'description' => 'Designer ladies suits, padded blouses, and custom fit adjustments.',
				),
				'garment-sizing-alterations' => array(
					'name'        => 'Alteration Service',
					'description' => 'On-the-spot hemming, side-fitting, and shoulder adjustments.',
				),
			),
		),
	);

	foreach ( $categories_structure as $parent_slug => $parent_data ) {
		// Check or insert parent
		$parent_term = get_term_by( 'slug', $parent_slug, 'product_cat' );
		if ( ! $parent_term ) {
			$inserted = wp_insert_term(
				$parent_data['name'],
				'product_cat',
				array(
					'slug'        => $parent_slug,
					'description' => $parent_data['description'],
				)
			);
			if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
				$parent_id = $inserted['term_id'];
			} else {
				continue;
			}
		} else {
			$parent_id = $parent_term->term_id;
			// Ensure description is synced/updated if different
			if ( $parent_term->description !== $parent_data['description'] ) {
				wp_update_term( $parent_id, 'product_cat', array(
					'description' => $parent_data['description'],
				) );
			}
		}

		// Check or insert children
		foreach ( $parent_data['children'] as $child_slug => $child_data ) {
			$child_term = get_term_by( 'slug', $child_slug, 'product_cat' );
			if ( ! $child_term ) {
				wp_insert_term(
					$child_data['name'],
					'product_cat',
					array(
						'slug'        => $child_slug,
						'description' => $child_data['description'],
						'parent'      => $parent_id,
					)
				);
			} else {
				// Ensure correct parent relationship and description
				$update_args = array();
				if ( (int) $child_term->parent !== (int) $parent_id ) {
					$update_args['parent'] = $parent_id;
				}
				if ( $child_term->description !== $child_data['description'] ) {
					$update_args['description'] = $child_data['description'];
				}
				if ( ! empty( $update_args ) ) {
					wp_update_term( $child_term->term_id, 'product_cat', $update_args );
				}
			}
		}
	}
}
add_action( 'init', 'ame_bazaar_bootstrap_categories', 25 );
