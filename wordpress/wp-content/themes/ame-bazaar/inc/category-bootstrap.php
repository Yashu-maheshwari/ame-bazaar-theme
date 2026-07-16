<?php
/**
 * Programmatic category architecture bootstrap for AME Bazaar WooCommerce.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Syncs taxonomy terms recursively to allow unlimited depth.
 */
function ame_bazaar_sync_terms_recursive( $structure, $parent_id = 0, &$actions_log = array() ) {
	foreach ( $structure as $slug => $data ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		$desc = isset( $data['description'] ) ? $data['description'] : '';

		if ( ! $term ) {
			$inserted = wp_insert_term(
				$data['name'],
				'product_cat',
				array(
					'slug'        => $slug,
					'description' => $desc,
					'parent'      => $parent_id,
				)
			);
			if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
				$current_id = $inserted['term_id'];
				$actions_log[] = array(
					'action'    => 'insert',
					'slug'      => $slug,
					'name'      => $data['name'],
					'parent'    => $parent_id,
					'status'    => 'success',
					'timestamp' => current_time( 'mysql' ),
				);
			} else {
				$actions_log[] = array(
					'action'    => 'insert_failed',
					'slug'      => $slug,
					'name'      => $data['name'],
					'parent'    => $parent_id,
					'status'    => 'error',
					'message'   => is_wp_error( $inserted ) ? $inserted->get_error_message() : 'Unknown error',
					'timestamp' => current_time( 'mysql' ),
				);
				continue;
			}
		} else {
			$current_id = $term->term_id;
			$update_args = array();
			$old_parent = $term->parent;
			$old_name = $term->name;

			if ( (int) $term->parent !== (int) $parent_id ) {
				$update_args['parent'] = $parent_id;
			}
			if ( $term->name !== $data['name'] ) {
				$update_args['name'] = $data['name'];
			}
			if ( $term->description !== $desc ) {
				$update_args['description'] = $desc;
			}
			if ( ! empty( $update_args ) ) {
				$updated = wp_update_term( $current_id, 'product_cat', $update_args );
				if ( ! is_wp_error( $updated ) ) {
					$actions_log[] = array(
						'action'     => 'update',
						'slug'       => $slug,
						'name'       => $data['name'],
						'old_name'   => $old_name,
						'old_parent' => $old_parent,
						'new_parent' => $parent_id,
						'status'     => 'success',
						'timestamp'  => current_time( 'mysql' ),
					);
				} else {
					$actions_log[] = array(
						'action'    => 'update_failed',
						'slug'      => $slug,
						'status'    => 'error',
						'message'   => $updated->get_error_message(),
						'timestamp' => current_time( 'mysql' ),
					);
				}
			}
		}

		if ( isset( $data['children'] ) && is_array( $data['children'] ) ) {
			ame_bazaar_sync_terms_recursive( $data['children'], $current_id, $actions_log );
		}
	}
}

/**
 * Master catalog category bootstrapping.
 */
function ame_bazaar_bootstrap_categories() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// 1. PRE-EXECUTION BACKUP & IDEMPOTENCY check
	if ( get_option( 'ame_taxonomy_migration_completed' ) === 'yes' ) {
		return;
	}

	$all_terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );

	$backup = array();
	foreach ( $all_terms as $t ) {
		$backup[] = array(
			'term_id' => $t->term_id,
			'name'    => $t->name,
			'slug'    => $t->slug,
			'parent'  => $t->parent,
		);
	}
	update_option( 'ame_taxonomy_backup', $backup );

	$actions_log = array();

	// 2. MERGE DUPLICATES
	if ( term_exists( 30, 'product_cat' ) ) {
		wp_delete_term( 30, 'product_cat' );
		$actions_log[] = array(
			'action'    => 'delete_duplicate_boy',
			'term_id'   => 30,
			'status'    => 'success',
			'timestamp' => current_time( 'mysql' ),
		);
	}
	if ( term_exists( 31, 'product_cat' ) ) {
		wp_delete_term( 31, 'product_cat' );
		$actions_log[] = array(
			'action'    => 'delete_duplicate_girl',
			'term_id'   => 31,
			'status'    => 'success',
			'timestamp' => current_time( 'mysql' ),
		);
	}

	// 3. MASTER RETAIL CATALOG STRUCTURE
	$categories_structure = array(
		'mens-wear' => array(
			'name'        => "Men's Wear",
			'description' => 'Premium men\'s shirts, casual t-shirts, stretchable denim jeans, trousers, and traditional kurta pajamas.',
			'children'    => array(
				'men-shirts' => array(
					'name'        => 'Shirts',
					'description' => 'Casual, formal, linen, and party wear shirts.',
					'children'    => array(
						'men-half-sleeve-shirts' => array( 'name' => 'Half Sleeve Shirts' ),
						'men-full-sleeve-shirts' => array( 'name' => 'Full Sleeve Shirts' ),
						'men-casual-shirts'      => array( 'name' => 'Casual Shirts' ),
						'men-formal-shirts'      => array( 'name' => 'Formal Shirts' ),
						'men-printed-shirts'     => array( 'name' => 'Printed Shirts' ),
						'men-checked-shirts'     => array( 'name' => 'Checked Shirts' ),
						'men-linen-shirts'       => array( 'name' => 'Linen Shirts' ),
						'men-denim-shirts'       => array( 'name' => 'Denim Shirts' ),
						'men-party-wear-shirts'  => array( 'name' => 'Party Wear Shirts' ),
					),
				),
				'men-tshirts' => array(
					'name'        => 'T-Shirts',
					'description' => 'Polo, round neck, and graphic t-shirts.',
					'children'    => array(
						'men-polo-tshirts'       => array( 'name' => 'Polo' ),
						'men-round-neck-tshirts' => array( 'name' => 'Round Neck' ),
						'men-collar-tshirts'     => array( 'name' => 'Collar' ),
						'men-graphic-tshirts'    => array( 'name' => 'Graphic' ),
						'men-printed-tshirts'    => array( 'name' => 'Printed' ),
						'men-fancy-tshirts'      => array( 'name' => 'Fancy' ),
						'men-regular-tshirts'    => array( 'name' => 'Regular' ),
					),
				),
				'men-jeans' => array(
					'name'        => 'Jeans',
					'description' => 'Denim jeans and cargo jeans styles.',
					'children'    => array(
						'men-cargo-jeans'        => array( 'name' => 'Cargo' ),
						'men-jogger-jeans'       => array( 'name' => 'Jogger' ),
						'men-slim-fit-jeans'     => array( 'name' => 'Slim Fit' ),
						'men-straight-fit-jeans' => array( 'name' => 'Straight Fit' ),
						'men-regular-fit-jeans'   => array( 'name' => 'Regular Fit' ),
						'men-casual-jeans'       => array( 'name' => 'Casual Jeans' ),
					),
				),
				'men-trousers' => array(
					'name'        => 'Trouser',
					'description' => 'Formal and daily wear trousers.',
					'children'    => array(
						'men-formal-trousers'     => array( 'name' => 'Formal' ),
						'men-regular-trousers'    => array( 'name' => 'Regular' ),
						'men-stylish-trousers'    => array( 'name' => 'Stylish' ),
						'men-stretchable-trousers' => array( 'name' => 'Stretchable' ),
					),
				),
				'men-cargo-pants' => array( 'name' => 'Cargo Pants' ),
				'men-track-pants' => array( 'name' => 'Track Pants' ),
				'men-lowers'      => array( 'name' => 'Lowers' ),
				'men-shorts'      => array( 'name' => 'Shorts' ),
				'men-kurta-pajama' => array(
					'name'        => 'Kurta Pajama',
					'description' => 'Designer and festival traditional kurtas.',
					'children'    => array(
						'men-designer-kurta-pajama' => array( 'name' => 'Designer' ),
						'men-koati-kurta-pajama'    => array( 'name' => 'Koati' ),
						'men-festival-kurta-pajama' => array( 'name' => 'Festival' ),
						'men-regular-kurta-pajama'  => array( 'name' => 'Regular' ),
					),
				),
				'men-sherwani'  => array( 'name' => 'Sherwani' ),
				'men-blazer'    => array( 'name' => 'Blazer' ),
				'men-waistcoat' => array( 'name' => 'Waistcoat' ),
				'men-winter-wear' => array(
					'name'        => 'Winter Wear',
					'description' => 'Hoodies, jackets, and thermal wear.',
					'children'    => array(
						'men-winter-hoodies'     => array( 'name' => 'Hoodies' ),
						'men-winter-sweaters'    => array( 'name' => 'Sweaters' ),
						'men-winter-jackets'     => array( 'name' => 'Jackets' ),
						'men-winter-sweatshirts' => array( 'name' => 'Sweatshirts' ),
						'men-winter-track-suits' => array( 'name' => 'Track Suits' ),
						'men-winter-thermals'    => array( 'name' => 'Thermals' ),
					),
				),
				'men-night-wear' => array( 'name' => 'Night Wear' ),
				'men-innerwear'  => array( 'name' => 'Innerwear' ),
				'men-thermals'   => array( 'name' => 'Thermals' ),
			),
		),
		'womens-wear' => array(
			'name'        => "Women's Wear",
			'description' => 'Designer ladies suits, daily-wear kurtis, rayon co-ords, soft sarees, cardigans, and nightwear.',
			'children'    => array(
				'sarees' => array(
					'name'        => 'Sarees',
					'description' => 'Silk, georgette, and printed daily wear sarees.',
					'children'    => array(
						'silk-sarees'             => array( 'name' => 'Silk Sarees' ),
						'georgette-rayon-sarees'  => array( 'name' => 'Georgette & Rayon' ),
						'printed-daily-sarees'    => array( 'name' => 'Daily Wear Sarees' ),
						'designer-festive-sarees' => array( 'name' => 'Designer & Festive' ),
					),
				),
				'women-kurtis-suits' => array(
					'name'        => 'Kurtis & Suits',
					'description' => 'Printed daily kurtis and salwar suits.',
				),
				'women-daily-wear' => array(
					'name'        => 'Daily Wear & Bottoms',
					'description' => 'Leggings, plazos, and jeans.',
				),
				'women-nightwear' => array(
					'name'        => 'Nightwear & Lounge',
					'description' => 'Pure cotton nighties and lounge sets.',
				),
				'women-winter-wear' => array(
					'name'        => 'Winter Wear',
					'description' => 'Ladies wool cardigans, sweaters, and shawls.',
				),
			),
		),
		'boys-wear' => array(
			'name'        => "Boy's Wear",
			'description' => 'Hypoallergenic soft cotton shirts, tees, jeans, and ethnic wear for boys aged 0 to 14 years.',
			'children'    => array(
				'boys-tops'    => array( 'name' => 'Shirts & Tees' ),
				'boys-bottoms' => array( 'name' => 'Jeans & Shorts' ),
				'boys-ethnic'  => array( 'name' => 'Ethnic Wear' ),
				'boys-winter'  => array( 'name' => 'Winter Wear' ),
			),
		),
		'girls-wear' => array(
			'name'        => "Girl's Wear",
			'description' => 'Hypoallergenic and soft-lined dresses, frocks, lehenga cholis, and bottoms for girls.',
			'children'    => array(
				'girls-dresses-frocks' => array( 'name' => 'Dresses & Frocks' ),
				'girls-tops'           => array( 'name' => 'Tops & Tees' ),
				'girls-bottoms'        => array( 'name' => 'Leggings & Jeans' ),
				'girls-ethnic'         => array( 'name' => 'Ethnic Wear' ),
				'girls-winter'         => array( 'name' => 'Winter Wear' ),
			),
		),
		'baby-wear' => array(
			'name'        => 'Infant Items',
			'description' => 'Soft rompers, bodysuits, and baby gift sets.',
		),
		'accessories' => array(
			'name'        => 'Accessories',
			'description' => 'Gents belts, wallets, cotton and woolen socks, handkerchiefs, and school uniform items.',
			'children'    => array(
				'gents-belts-wallets'        => array( 'name' => 'Belts & Wallets' ),
				'socks-handkerchiefs'        => array( 'name' => 'Socks & Handkerchiefs' ),
				'winter-accessories'         => array( 'name' => 'Winter Essentials' ),
				'school-uniform-accessories' => array( 'name' => 'School Uniforms' ),
			),
		),
		'footwear' => array(
			'name'        => 'Footwear',
			'description' => 'Daily wear sandals, formal shoes, and sneakers.',
			'children'    => array(
				'daily-sandals'   => array( 'name' => 'Daily Sandals & Slippers' ),
				'formal-shoes'    => array( 'name' => 'Formal Shoes' ),
				'casual-sneakers' => array( 'name' => 'Casual Sneakers' ),
				'kids-footwear'   => array( 'name' => 'Kids Footwear' ),
			),
		),
		'rainwear' => array(
			'name'        => 'Rainwear',
			'description' => 'Raincoats, umbrellas, and waterproof sheets.',
		),
		'tailoring' => array(
			'name'        => 'Tailoring Services',
			'description' => 'In-store custom tailoring and adjustments.',
			'children'    => array(
				'custom-gents-stitching'     => array( 'name' => 'Gents Stitching' ),
				'custom-ladies-stitching'    => array( 'name' => 'Ladies Stitching' ),
				'garment-sizing-alterations' => array( 'name' => 'Alteration Service' ),
			),
		),
		'online-exclusive' => array(
			'name'        => 'Online Exclusive',
			'description' => 'Web exclusive collections.',
		),
	);

	// 4. SYNC STRUCTURE
	try {
		ame_bazaar_sync_terms_recursive( $categories_structure, 0, $actions_log );
		update_option( 'ame_taxonomy_migration_completed', 'yes' );
		update_option( 'ame_taxonomy_migration_log', $actions_log );
	} catch ( Exception $e ) {
		// Auto-rollback on error
		$backup_terms = get_option( 'ame_taxonomy_backup' );
		if ( ! empty( $backup_terms ) ) {
			foreach ( $backup_terms as $bt ) {
				wp_update_term( $bt['term_id'], 'product_cat', array(
					'parent' => $bt['parent'],
				) );
			}
		}
		$actions_log[] = array(
			'action'    => 'rollback',
			'status'    => 'restored_on_failure',
			'error'     => $e->getMessage(),
			'timestamp' => current_time( 'mysql' ),
		);
		update_option( 'ame_taxonomy_migration_log', $actions_log );
	}
}
add_action( 'init', 'ame_bazaar_bootstrap_categories', 25 );
