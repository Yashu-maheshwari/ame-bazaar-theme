<?php
/**
 * AME Store Operations and Admin Custom Fields Management.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Register Custom Product Fields inside WooCommerce Product Edit Screen.
 */
function ame_bazaar_add_custom_product_fields() {
	echo '<div class="options_group">';
	
	// Custom specifications
	woocommerce_wp_text_input( array(
		'id'          => '_ame_fabric',
		'label'       => __( 'Fabric / Material', 'ame-bazaar' ),
		'placeholder' => 'e.g. Pure Mulmul Cotton, Silk',
		'desc_tip'    => 'true',
		'description' => __( 'Specify the fabric composition.', 'ame-bazaar' ),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_gsm',
		'label'       => __( 'GSM Value', 'ame-bazaar' ),
		'placeholder' => 'e.g. 120, 180',
		'type'        => 'number',
		'desc_tip'    => 'true',
		'description' => __( 'GSM weight of the fabric.', 'ame-bazaar' ),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_pattern',
		'label'       => __( 'Pattern style', 'ame-bazaar' ),
		'placeholder' => 'e.g. Embroidered, Solid, Printed',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_gender',
		'label'   => __( 'Target Gender', 'ame-bazaar' ),
		'options' => array(
			'unisex' => __( 'Unisex', 'ame-bazaar' ),
			'men'    => __( 'Men', 'ame-bazaar' ),
			'women'  => __( 'Women', 'ame-bazaar' ),
			'kids'   => __( 'Kids', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_textarea_input( array(
		'id'          => '_ame_care_instructions',
		'label'       => __( 'Care Instructions', 'ame-bazaar' ),
		'placeholder' => 'e.g. Hand wash separately, Dry clean recommended',
	) );

	// Local retail statuses
	woocommerce_wp_checkbox( array(
		'id'            => '_ame_alteration_available',
		'wrapper_class' => 'show_if_simple show_if_variable',
		'label'         => __( 'On-site Alteration Available?', 'ame-bazaar' ),
		'description'   => __( 'Check if 30-minute Kirari fitting alteration is supported.', 'ame-bazaar' ),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_kirari_stock',
		'label'       => __( 'Kirari Store Stock', 'ame-bazaar' ),
		'type'        => 'number',
		'description' => __( 'Stock count physically present at Mubarakpur Road outlet.', 'ame-bazaar' ),
	) );

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'ame_bazaar_add_custom_product_fields' );

/**
 * Save Custom Fields data.
 */
function ame_bazaar_save_custom_product_fields( $post_id ) {
	$fields = array(
		'_ame_fabric',
		'_ame_gsm',
		'_ame_pattern',
		'_ame_gender',
		'_ame_care_instructions',
		'_ame_alteration_available',
		'_ame_kirari_stock',
	);

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		} else {
			delete_post_meta( $post_id, $field );
		}
	}
}
add_action( 'woocommerce_process_product_meta', 'ame_bazaar_save_custom_product_fields' );

/**
 * 2. Custom AME Store operations dashboard registered to WordPress backend admin.
 */
function ame_bazaar_register_store_dashboard() {
	add_menu_page(
		__( 'AME Store Dashboard', 'ame-bazaar' ),
		__( 'AME Store', 'ame-bazaar' ),
		'manage_options',
		'ame-store-dashboard',
		'ame_bazaar_render_admin_store_dashboard',
		'dashicons-chart-area',
		56
	);
}
add_action( 'admin_menu', 'ame_bazaar_register_store_dashboard' );

/**
 * Render Operations stats. Optimised using transient caching.
 */
function ame_bazaar_render_admin_store_dashboard() {
	// Try fetching cached dashboard transient values
	$stats = get_transient( 'ame_bazaar_store_stats' );
	
	if ( false === $stats ) {
		// Calculate from orders
		$query = new WC_Order_Query( array(
			'limit'  => 15,
			'status' => array( 'processing', 'completed' ),
		) );
		$orders = $query->get_orders();
		
		$revenue = 0;
		foreach ( $orders as $order ) {
			$revenue += (float) $order->get_total();
		}

		// Low stock counts
		$low_stock_args = array(
			'post_type'      => 'product',
			'posts_per_page' => 5,
			'meta_query'     => array(
				array(
					'key'     => '_stock',
					'value'   => 5,
					'compare' => '<=',
					'type'    => 'NUMERIC',
				),
			),
		);
		$low_stock_query = new WP_Query( $low_stock_args );
		$low_stock_items = array();
		if ( $low_stock_query->have_posts() ) {
			while ( $low_stock_query->have_posts() ) {
				$low_stock_query->the_post();
				global $product;
				$low_stock_items[] = array(
					'title' => get_the_title(),
					'stock' => get_post_meta( get_the_ID(), '_stock', true ),
				);
			}
			wp_reset_postdata();
		}

		$stats = array(
			'order_count'     => count( $orders ),
			'revenue'         => $revenue,
			'low_stock_items' => $low_stock_items,
		);

		// Cache stats transient for 1 hour to prevent DB load
		set_transient( 'ame_bazaar_store_stats', $stats, HOUR_IN_SECONDS );
	}
	
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AME Bazaar Store & Operations Desk', 'ame-bazaar' ); ?></h1>
		
		<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; max-width: 1000px;">
			<!-- Revenue card -->
			<div style="background:#fff; border:1px solid #ccd0d4; padding:1.5rem; border-radius:5px;">
				<h2 style="margin:0 0 0.5rem 0; font-size:1.1rem; color:#1d2327;">Recent Revenue</h2>
				<span style="font-size:2rem; font-weight:800; color:#ca8a04;">₹<?php echo esc_html( number_format( $stats['revenue'] ) ); ?></span>
			</div>

			<!-- Orders count -->
			<div style="background:#fff; border:1px solid #ccd0d4; padding:1.5rem; border-radius:5px;">
				<h2 style="margin:0 0 0.5rem 0; font-size:1.1rem; color:#1d2327;">Active Orders</h2>
				<span style="font-size:2rem; font-weight:800; color:#134e4a;"><?php echo esc_html( $stats['order_count'] ); ?></span>
			</div>
		</div>

		<!-- Low stock warning lists -->
		<div style="background:#fff; border:1px solid #ccd0d4; padding:2rem; border-radius:5px; margin-top: 2rem; max-width: 1000px;">
			<h2 style="margin:0 0 1rem 0; font-size:1.2rem; color:#b91c1c;">Low Stock Alert (Kirari Stock & Delivery limits)</h2>
			<?php if ( ! empty( $stats['low_stock_items'] ) ) : ?>
				<table class="wp-list-table widefat fixed striped" style="border:none;">
					<thead>
						<tr>
							<th><strong>Product Sizing</strong></th>
							<th><strong>Remaining Stock</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $stats['low_stock_items'] as $item ) : ?>
							<tr>
								<td><?php echo esc_html( $item['title'] ); ?></td>
								<td style="color:#b91c1c; font-weight:700;"><?php echo esc_html( $item['stock'] ); ?> units remaining</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p>All catalog products are fully stocked in Kirari Store.</p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * 3. Auto-generate SEO Title tags dynamically for catalog search engines.
 */
function ame_bazaar_auto_product_seo_title( $title ) {
	if ( is_product() ) {
		global $post;
		$fabric = get_post_meta( $post->ID, '_ame_fabric', true );
		if ( $fabric ) {
			return get_the_title() . ' - ' . $fabric . ' Ethnic Wear | AME Bazaar Kirari Delhi';
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'ame_bazaar_auto_product_seo_title', 20 );

/**
 * 4. Auto-generate Meta Description tags dynamically.
 */
function ame_bazaar_auto_product_seo_desc() {
	if ( is_product() ) {
		global $post;
		$fabric = get_post_meta( $post->ID, '_ame_fabric', true );
		$pattern = get_post_meta( $post->ID, '_ame_pattern', true );
		$desc = sprintf( 'Buy premium %s ethnic clothing at AME Bazaar Kirari. Fabric: %s. Pattern: %s. On-site 30-minute custom tailoring alterations and fitting available in our Mubarakpur Road, Kirari, Delhi store.', get_the_title(), $fabric ? $fabric : 'Cotton', $pattern ? $pattern : 'Handcrafted' );
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ame_bazaar_auto_product_seo_desc', 5 );

/**
 * 5. Improve WooCommerce product search queries to index fabric, pattern, and GSM.
 */
function ame_bazaar_product_search_meta_join( $join ) {
	global $wpdb;
	if ( is_search() && ! is_admin() && isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) {
		$join .= " LEFT JOIN {$wpdb->postmeta} AS ame_pm ON ({$wpdb->posts}.ID = ame_pm.post_id) ";
	}
	return $join;
}
add_filter( 'posts_join', 'ame_bazaar_product_search_meta_join' );

function ame_bazaar_product_search_meta_where( $where ) {
	global $wpdb;
	if ( is_search() && ! is_admin() && isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) {
		$search_term = esc_sql( get_query_var( 's' ) );
		$where .= " OR (ame_pm.meta_key IN ('_ame_fabric', '_ame_pattern', '_ame_gsm') AND ame_pm.meta_value LIKE '%{$search_term}%') ";
	}
	return $where;
}
add_filter( 'posts_where', 'ame_bazaar_product_search_meta_where' );

