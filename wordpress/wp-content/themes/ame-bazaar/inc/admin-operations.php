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

	// --- 1. BASIC PRODUCT ---
	echo '<h4 style="margin: 15px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '1. Basic Product Settings', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_text_input( array(
		'id'          => '_ame_kirari_stock',
		'label'       => __( 'Kirari Store Stock', 'ame-bazaar' ),
		'type'        => 'number',
		'description' => __( 'Stock count physically present at Mubarakpur Road outlet.', 'ame-bazaar' ),
	) );

	woocommerce_wp_checkbox( array(
		'id'            => '_ame_alteration_available',
		'wrapper_class' => 'show_if_simple show_if_variable',
		'label'         => __( 'On-site Alteration Available?', 'ame-bazaar' ),
		'description'   => __( 'Check if 30-minute Kirari fitting alteration is supported.', 'ame-bazaar' ),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_local_availability',
		'label'   => __( 'Local Availability', 'ame-bazaar' ),
		'options' => array(
			'online-and-instore' => __( 'Online & In-Store', 'ame-bazaar' ),
			'in-store-only'      => __( 'In-Store Only', 'ame-bazaar' ),
		),
	) );

	// --- 2. GARMENT SPECIFICATIONS ---
	echo '<h4 style="margin: 25px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '2. Garment Specifications', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_text_input( array(
		'id'          => '_ame_brand',
		'label'       => __( 'Brand Name', 'ame-bazaar' ),
		'placeholder' => 'e.g. AME Bazaar, Maheshwari',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_fabric',
		'label'   => __( 'Fabric Type', 'ame-bazaar' ),
		'options' => array(
			'pure-cotton'   => __( 'Pure Cotton', 'ame-bazaar' ),
			'mulmul-cotton' => __( 'Pure Mulmul Cotton', 'ame-bazaar' ),
			'silk'          => __( 'Silk (Banarasi/Raw)', 'ame-bazaar' ),
			'rayon'         => __( 'Soft Rayon', 'ame-bazaar' ),
			'georgette'     => __( 'Georgette', 'ame-bazaar' ),
			'cotton-blend'  => __( 'Cotton Blend', 'ame-bazaar' ),
			'wool'          => __( 'Pure Wool / Cashmere', 'ame-bazaar' ),
			'synthetic'     => __( 'Polyester / Synthetic', 'ame-bazaar' ),
			'denim'         => __( 'Denim', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_material',
		'label'       => __( 'Material Details', 'ame-bazaar' ),
		'placeholder' => 'e.g. 100% Organic Cotton thread, Zari thread embroidery',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_gsm',
		'label'       => __( 'GSM Value', 'ame-bazaar' ),
		'placeholder' => 'e.g. 120, 180',
		'type'        => 'number',
		'description' => __( 'GSM weight of the fabric.', 'ame-bazaar' ),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_fabric_weight',
		'label'       => __( 'Fabric Weight', 'ame-bazaar' ),
		'placeholder' => 'e.g. Lightweight 100 GSM, Medium 180 GSM',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_pattern',
		'label'   => __( 'Pattern Style', 'ame-bazaar' ),
		'options' => array(
			'solid'       => __( 'Solid / Plain', 'ame-bazaar' ),
			'printed'     => __( 'Printed', 'ame-bazaar' ),
			'embroidered' => __( 'Embroidered', 'ame-bazaar' ),
			'checked'     => __( 'Checked', 'ame-bazaar' ),
			'striped'     => __( 'Striped', 'ame-bazaar' ),
			'woven'       => __( 'Self-Woven / Zari Border', 'ame-bazaar' ),
			'designer'    => __( 'Designer Embellished', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_fit',
		'label'   => __( 'Fit Style', 'ame-bazaar' ),
		'options' => array(
			'regular'  => __( 'Regular Fit', 'ame-bazaar' ),
			'slim'     => __( 'Slim Fit', 'ame-bazaar' ),
			'loose'    => __( 'Loose / Comfort Fit', 'ame-bazaar' ),
			'semi-slim'=> __( 'Semi-Slim Fit', 'ame-bazaar' ),
			'tailored' => __( 'Custom Tailored Fit', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_sleeve_type',
		'label'   => __( 'Sleeve Type', 'ame-bazaar' ),
		'options' => array(
			'full'           => __( 'Full Sleeve', 'ame-bazaar' ),
			'half'           => __( 'Half Sleeve', 'ame-bazaar' ),
			'sleeveless'     => __( 'Sleeveless', 'ame-bazaar' ),
			'three-quarter'  => __( '3/4 Sleeve', 'ame-bazaar' ),
			'short'          => __( 'Short Sleeve', 'ame-bazaar' ),
			'not-applicable' => __( 'Not Applicable', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_neck_type',
		'label'   => __( 'Neck Type', 'ame-bazaar' ),
		'options' => array(
			'collar'         => __( 'Shirt Collar', 'ame-bazaar' ),
			'mandarin'       => __( 'Mandarin / Nehru Collar', 'ame-bazaar' ),
			'round'          => __( 'Round Neck', 'ame-bazaar' ),
			'v-neck'         => __( 'V-Neck', 'ame-bazaar' ),
			'boat'           => __( 'Boat Neck', 'ame-bazaar' ),
			'cowl'           => __( 'Cowl Neck', 'ame-bazaar' ),
			'not-applicable' => __( 'Not Applicable', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_closure',
		'label'   => __( 'Closure Type', 'ame-bazaar' ),
		'options' => array(
			'button'    => __( 'Button', 'ame-bazaar' ),
			'zipper'    => __( 'Zipper', 'ame-bazaar' ),
			'slip-on'   => __( 'Slip-On', 'ame-bazaar' ),
			'drawstring'=> __( 'Drawstring', 'ame-bazaar' ),
			'elastic'   => __( 'Elastic Waistband', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_collection',
		'label'       => __( 'Collection Group', 'ame-bazaar' ),
		'placeholder' => 'e.g. Mubarakpur Festive 2026',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_style',
		'label'       => __( 'Style Identifier', 'ame-bazaar' ),
		'placeholder' => 'e.g. Ethnic Traditional, Modern Indo-Western',
	) );

	// --- 3. SIZING & PRICING ---
	echo '<h4 style="margin: 25px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '3. Sizing & Pricing', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_text_input( array(
		'id'          => '_ame_mrp',
		'label'       => __( 'MRP Value (₹)', 'ame-bazaar' ),
		'type'        => 'number',
		'placeholder' => 'Maximum Retail Price for tag display',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_price_segment',
		'label'   => __( 'Price Segment', 'ame-bazaar' ),
		'options' => array(
			'budget'    => __( 'Budget Friendly (Under ₹999)', 'ame-bazaar' ),
			'mid-range' => __( 'Value Range (₹1000 - ₹2499)', 'ame-bazaar' ),
			'premium'   => __( 'Mid-Premium (₹2500 - ₹4999)', 'ame-bazaar' ),
			'luxury'    => __( 'Luxury / Wedding (₹5000+)', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_gender',
		'label'   => __( 'Target Gender', 'ame-bazaar' ),
		'options' => array(
			'unisex' => __( 'Unisex', 'ame-bazaar' ),
			'men'    => __( 'Men\'s Wear', 'ame-bazaar' ),
			'women'  => __( 'Women\'s Wear', 'ame-bazaar' ),
			'boys'   => __( 'Boys Wear', 'ame-bazaar' ),
			'girls'  => __( 'Girls Wear', 'ame-bazaar' ),
			'kids'   => __( 'Kids Essentials', 'ame-bazaar' ),
			'infant' => __( 'Infant Wear', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_age_group',
		'label'   => __( 'Age Segment', 'ame-bazaar' ),
		'options' => array(
			'all'    => __( 'All Ages', 'ame-bazaar' ),
			'adult'  => __( 'Adult (15-60y)', 'ame-bazaar' ),
			'kids'   => __( 'Child (3-14y)', 'ame-bazaar' ),
			'infant' => __( 'Infant (0-2y)', 'ame-bazaar' ),
			'senior' => __( 'Senior (60y+)', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_occasion',
		'label'   => __( 'Occasion Type', 'ame-bazaar' ),
		'options' => array(
			'casual'   => __( 'Casual Daily', 'ame-bazaar' ),
			'formal'   => __( 'Office Formal', 'ame-bazaar' ),
			'wedding'  => __( 'Wedding / Ceremony', 'ame-bazaar' ),
			'festival' => __( 'Festive Shopping', 'ame-bazaar' ),
			'party'    => __( 'Party Wear', 'ame-bazaar' ),
			'school'   => __( 'School Wear', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_season',
		'label'   => __( 'Seasonality', 'ame-bazaar' ),
		'options' => array(
			'all-season' => __( 'All Seasons', 'ame-bazaar' ),
			'summer'     => __( 'Summer Wear (Mulmul Cotton)', 'ame-bazaar' ),
			'winter'     => __( 'Winter Layers', 'ame-bazaar' ),
			'monsoon'    => __( 'Monsoon Wear', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_color_flat',
		'label'       => __( 'Color (Flat Spec)', 'ame-bazaar' ),
		'placeholder' => 'e.g. Navy Blue, Crimson Red',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_size_flat',
		'label'       => __( 'Size (Flat Spec)', 'ame-bazaar' ),
		'placeholder' => 'e.g. XL, 42, 38',
	) );

	woocommerce_wp_textarea_input( array(
		'id'          => '_ame_size_chart',
		'label'       => __( 'Size Chart / Guide', 'ame-bazaar' ),
		'placeholder' => 'Provide a text measurements table or specific size details.',
	) );

	// --- 4. MANUFACTURING & CARE ---
	echo '<h4 style="margin: 25px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '4. Manufacturing & Care', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_textarea_input( array(
		'id'          => '_ame_care_instructions',
		'label'       => __( 'Care Instructions', 'ame-bazaar' ),
		'placeholder' => 'e.g. Hand wash separately, Dry clean recommended',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_wash_instructions',
		'label'       => __( 'Wash Instructions', 'ame-bazaar' ),
		'placeholder' => 'e.g. Machine wash cold, tumble dry low',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_country_of_origin',
		'label'       => __( 'Country of Origin', 'ame-bazaar' ),
		'value'       => get_post_meta( get_the_ID(), '_ame_country_of_origin', true ) ? get_post_meta( get_the_ID(), '_ame_country_of_origin', true ) : 'India',
		'placeholder' => 'e.g. India',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_manufacturer',
		'label'       => __( 'Manufacturer Details', 'ame-bazaar' ),
		'value'       => get_post_meta( get_the_ID(), '_ame_manufacturer', true ) ? get_post_meta( get_the_ID(), '_ame_manufacturer', true ) : 'Apparel Maheshwari Enterprises',
		'placeholder' => 'Manufacturer Name',
	) );

	// --- 5. SEO ---
	echo '<h4 style="margin: 25px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '5. SEO & Social overrides', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_text_input( array(
		'id'          => '_ame_seo_title',
		'label'       => __( 'SEO Title Override', 'ame-bazaar' ),
		'placeholder' => 'Custom Google search results title',
	) );

	woocommerce_wp_textarea_input( array(
		'id'          => '_ame_seo_desc',
		'label'       => __( 'Meta Description Override', 'ame-bazaar' ),
		'placeholder' => 'Custom Google search results snippet',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_canonical_url',
		'label'       => __( 'Canonical URL', 'ame-bazaar' ),
		'placeholder' => 'e.g. https://amebazaar.in/shop/mens-kurta/',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_og_image',
		'label'       => __( 'Open Graph Image attachment ID', 'ame-bazaar' ),
		'placeholder' => 'Attachment ID for social sharing image',
	) );

	// --- 6. AI & GEO ---
	echo '<h4 style="margin: 25px 0 5px 15px; color: #002347; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 3px; font-weight: 800;">' . esc_html__( '6. AI & GEO Metadata Settings', 'ame-bazaar' ) . '</h4>';

	woocommerce_wp_textarea_input( array(
		'id'          => '_ame_ai_keywords',
		'label'       => __( 'AI Search Keywords', 'ame-bazaar' ),
		'placeholder' => 'Comma separated terms for advisor and LLM indexing, e.g. soft, cotton, breathable, wedding, cream',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_geo_target',
		'label'       => __( 'GEO Target Locations', 'ame-bazaar' ),
		'placeholder' => 'e.g. Kirari, Mubarakpur, Rohini, Sultanpuri, Delhi',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_target_customer',
		'label'       => __( 'Target Demographic Description', 'ame-bazaar' ),
		'placeholder' => 'e.g. Families looking for affordable quality wedding kurtas',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_trending',
		'label'   => __( 'Is Trending Garment?', 'ame-bazaar' ),
		'options' => array(
			'no'  => __( 'No', 'ame-bazaar' ),
			'yes' => __( 'Yes - Show in Trending', 'ame-bazaar' ),
		),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_ame_featured_reason',
		'label'       => __( 'Featured Reason', 'ame-bazaar' ),
		'placeholder' => 'e.g. Best selling cotton suit in Kirari for summer 2026',
	) );

	woocommerce_wp_select( array(
		'id'      => '_ame_whatsapp_ready',
		'label'   => __( 'WhatsApp Catalog Ready?', 'ame-bazaar' ),
		'options' => array(
			'yes' => __( 'Yes - Fully Formatted', 'ame-bazaar' ),
			'no'  => __( 'No - Catalog Only', 'ame-bazaar' ),
		),
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
		'_ame_brand',
		'_ame_material',
		'_ame_fabric_weight',
		'_ame_fit',
		'_ame_sleeve_type',
		'_ame_neck_type',
		'_ame_closure',
		'_ame_collection',
		'_ame_style',
		'_ame_mrp',
		'_ame_price_segment',
		'_ame_age_group',
		'_ame_occasion',
		'_ame_season',
		'_ame_color_flat',
		'_ame_size_flat',
		'_ame_size_chart',
		'_ame_wash_instructions',
		'_ame_country_of_origin',
		'_ame_manufacturer',
		'_ame_seo_title',
		'_ame_seo_desc',
		'_ame_canonical_url',
		'_ame_og_image',
		'_ame_ai_keywords',
		'_ame_geo_target',
		'_ame_target_customer',
		'_ame_trending',
		'_ame_featured_reason',
		'_ame_whatsapp_ready',
		'_ame_local_availability',
	);

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			$val = wp_unslash( $_POST[ $field ] );
			if ( in_array( $field, array( '_ame_size_chart', '_ame_care_instructions', '_ame_seo_desc', '_ame_ai_keywords' ), true ) ) {
				update_post_meta( $post_id, $field, sanitize_textarea_field( $val ) );
			} else {
				update_post_meta( $post_id, $field, sanitize_text_field( $val ) );
			}
		} else {
			delete_post_meta( $post_id, $field );
		}
	}
}
add_action( 'woocommerce_process_product_meta', 'ame_bazaar_save_custom_product_fields' );

/**
 * 2. Custom AME Store operations dashboard and Business Settings registered to WordPress backend admin.
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

	add_submenu_page(
		'ame-store-dashboard',
		__( 'AME Business Settings', 'ame-bazaar' ),
		__( 'Business Settings', 'ame-bazaar' ),
		'manage_options',
		'ame-business-settings',
		'ame_bazaar_render_business_settings_page'
	);

	add_submenu_page(
		'ame-store-dashboard',
		__( 'Google Business Profile', 'ame-bazaar' ),
		__( 'Google Business Profile', 'ame-bazaar' ),
		'manage_options',
		'ame-google-reviews',
		'ame_bazaar_render_google_reviews_page'
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

/**
 * 6. Helper to fetch global business settings.
 */
function ame_bazaar_get_business_setting( $key, $default = '' ) {
	$val = get_option( 'ame_bazaar_' . $key );
	if ( ! $val ) {
		// Fallback to customizer theme mods if available
		$val = get_theme_mod( 'ame_bazaar_' . $key );
	}
	
	// Self-heal and provide genuine fallbacks if settings are empty
	if ( empty( $val ) ) {
		if ( 'phone' === $key || 'whatsapp' === $key ) {
			return '+91 99535 69533';
		}
		if ( 'email' === $key ) {
			return 'apparelmaheshwari@gmail.com';
		}
		if ( 'store_name' === $key ) {
			return 'AME Bazaar';
		}
		if ( 'address' === $key ) {
			return 'Mubarakpur Road';
		}
		if ( 'city' === $key ) {
			return 'Kirari';
		}
		if ( 'state' === $key ) {
			return 'Delhi';
		}
		if ( 'postal_code' === $key ) {
			return '110086';
		}
		if ( 'hours' === $key ) {
			return 'Mo-Su 09:00–22:00';
		}
		if ( 'maps_url' === $key ) {
			return 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi';
		}
		return $default;
	}

	// Clean up any remaining mock placeholder phone values from the database
	if ( ( 'phone' === $key || 'whatsapp' === $key ) && ( false !== strpos( $val, '99999' ) ) ) {
		return '+91 99535 69533';
	}
	if ( 'email' === $key && ( false !== strpos( $val, 'example.com' ) || false !== strpos( $val, 'contact@amebazaar.com' ) ) ) {
		return 'apparelmaheshwari@gmail.com';
	}
	if ( 'maps_url' === $key && false !== strpos( $val, 'example.com' ) ) {
		return 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi';
	}

	return $val;
}

/**
 * 7. Render Business Settings Admin Options Form Page.
 */
function ame_bazaar_render_business_settings_page() {
	// Save form changes
	if ( isset( $_POST['ame_save_settings'] ) && check_admin_referer( 'ame_business_settings_save', 'ame_nonce' ) ) {
		$keys = array(
			'store_name', 'short_description', 'address', 'city', 'state', 'postal_code', 'country',
			'phone', 'whatsapp', 'email', 'maps_url', 'latitude', 'longitude',
			'hours', 'holiday_hours', 'instagram', 'facebook', 'youtube', 'gbp_url',
			'google_reviews_rating', 'google_reviews_count',
			'store_pickup_available', 'tailoring_available', 'parking_available', 'home_delivery_available',
			'google_review_url', 'maps_embed_url', 'place_id', 'google_cid', 'primary_category', 'secondary_categories', 
			'appointment_url', 'business_attributes', 'store_highlights', 'parking_info', 'accessibility_info', 
			'store_photos_urls', 'owner_message', 'website_url'
		);
		foreach ( $keys as $key ) {
			if ( strpos( $key, '_available' ) !== false ) {
				$val = isset( $_POST[ $key ] ) ? 'yes' : 'no';
				update_option( 'ame_bazaar_' . $key, $val );
			} elseif ( isset( $_POST[ $key ] ) ) {
				update_option( 'ame_bazaar_' . $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
		// Clear transient cache so settings update instantly
		delete_transient( 'ame_bazaar_store_stats' );
		echo '<div class="notice notice-success is-dismissible"><p>Business Profile settings saved successfully.</p></div>';
	}
	
	$store_name        = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$short_desc        = ame_bazaar_get_business_setting( 'short_description', 'Apparel Maheshwari Enterprises offers premium fashion ethnic wear.' );
	$address           = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
	$city              = ame_bazaar_get_business_setting( 'city', 'Kirari' );
	$state             = ame_bazaar_get_business_setting( 'state', 'Delhi' );
	$postal_code       = ame_bazaar_get_business_setting( 'postal_code', '110086' );
	$country           = ame_bazaar_get_business_setting( 'country', 'IN' );
	$phone             = ame_bazaar_get_business_setting( 'phone', '+91 99999 99999' );
	$whatsapp          = ame_bazaar_get_business_setting( 'whatsapp', '+91 99999 99999' );
	$email             = ame_bazaar_get_business_setting( 'email', 'contact@amebazaar.com' );
	$maps_url          = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
	$latitude          = ame_bazaar_get_business_setting( 'latitude', '28.7051' );
	$longitude         = ame_bazaar_get_business_setting( 'longitude', '77.0583' );
	$hours             = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );
	$holiday_hours     = ame_bazaar_get_business_setting( 'holiday_hours', 'Diwali: Closed' );
	$instagram         = ame_bazaar_get_business_setting( 'instagram', 'https://www.instagram.com/amebazaar' );
	$facebook          = ame_bazaar_get_business_setting( 'facebook', 'https://www.facebook.com/amebazaar' );
	$youtube           = ame_bazaar_get_business_setting( 'youtube', '#' );
	$gbp_url           = ame_bazaar_get_business_setting( 'gbp_url', '#' );
	$reviews_rating    = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$reviews_count     = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	
	$pickup_avail      = ame_bazaar_get_business_setting( 'store_pickup_available', 'yes' );
	$tailoring_avail   = ame_bazaar_get_business_setting( 'tailoring_available', 'yes' );
	$parking_avail     = ame_bazaar_get_business_setting( 'parking_available', 'yes' );
	$delivery_avail    = ame_bazaar_get_business_setting( 'home_delivery_available', 'yes' );

	$google_review_url = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	$maps_embed_url    = ame_bazaar_get_business_setting( 'maps_embed_url', '' );
	$place_id          = ame_bazaar_get_business_setting( 'place_id', 'ChIJTgAADinpDDkRTr27xpunNWM' );
	$google_cid        = ame_bazaar_get_business_setting( 'google_cid', '7148784323200000000' );
	$primary_cat       = ame_bazaar_get_business_setting( 'primary_category', 'Clothing Store' );
	$secondary_cats    = ame_bazaar_get_business_setting( 'secondary_categories', 'Tailor, Women\'s Clothing Store, Men\'s Clothing Store' );
	$appointment_url   = ame_bazaar_get_business_setting( 'appointment_url', '#' );
	$store_highlights  = ame_bazaar_get_business_setting( 'store_highlights', 'Identifies as women-owned, In-store shopping, In-store pickup, Repair services' );
	$website_url       = ame_bazaar_get_business_setting( 'website_url', '' );
	$business_attrs    = ame_bazaar_get_business_setting( 'business_attributes', 'Cash, UPI, Credit Cards' );
	$parking_info      = ame_bazaar_get_business_setting( 'parking_info', 'Free street parking available' );
	$accessibility     = ame_bazaar_get_business_setting( 'accessibility_info', 'Wheelchair accessible entrance' );
	$store_photos      = ame_bazaar_get_business_setting( 'store_photos_urls', '' );
	$owner_message     = ame_bazaar_get_business_setting( 'owner_message', 'Welcome to AME Bazaar, providing premium family clothing and tailoring since years.' );
	
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AME Bazaar Business Profile & Trust Management', 'ame-bazaar' ); ?></h1>
		<form method="post" action="" style="background:#fff; border:1px solid #ccd0d4; padding:2rem; border-radius:5px; margin-top:1.5rem; max-width:800px;">
			<?php wp_nonce_field( 'ame_business_settings_save', 'ame_nonce' ); ?>
			
			<h2><?php esc_html_e( '1. Store Identity & Local Address', 'ame-bazaar' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="store_name"><?php esc_html_e( 'Business Name', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="store_name" name="store_name" value="<?php echo esc_attr( $store_name ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="primary_category"><?php esc_html_e( 'Primary Category', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="primary_category" name="primary_category" value="<?php echo esc_attr( $primary_cat ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="secondary_categories"><?php esc_html_e( 'Secondary Categories', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="secondary_categories" name="secondary_categories" value="<?php echo esc_attr( $secondary_cats ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="short_description"><?php esc_html_e( 'Business Description', 'ame-bazaar' ); ?></label></th>
					<td><textarea id="short_description" name="short_description" class="large-text" rows="3"><?php echo esc_textarea( $short_desc ); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="owner_message"><?php esc_html_e( 'Owner Message', 'ame-bazaar' ); ?></label></th>
					<td><textarea id="owner_message" name="owner_message" class="large-text" rows="3"><?php echo esc_textarea( $owner_message ); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="address"><?php esc_html_e( 'Street Address', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="address" name="address" value="<?php echo esc_attr( $address ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="city"><?php esc_html_e( 'City / Locality', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="city" name="city" value="<?php echo esc_attr( $city ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="state"><?php esc_html_e( 'State / Region', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="state" name="state" value="<?php echo esc_attr( $state ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="postal_code"><?php esc_html_e( 'Postal / ZIP Code', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="postal_code" name="postal_code" value="<?php echo esc_attr( $postal_code ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="country"><?php esc_html_e( 'Country ISO Code', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="country" name="country" value="<?php echo esc_attr( $country ); ?>" class="small-text" /></td>
				</tr>
			</table>

			<hr />
			<h2><?php esc_html_e( '2. Contact Points & Social Maps', 'ame-bazaar' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="phone"><?php esc_html_e( 'Contact Phone', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="whatsapp"><?php esc_html_e( 'WhatsApp Number', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="whatsapp" name="whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="email"><?php esc_html_e( 'Business Email', 'ame-bazaar' ); ?></label></th>
					<td><input type="email" id="email" name="email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="website_url"><?php esc_html_e( 'Website URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="website_url" name="website_url" value="<?php echo esc_attr( $website_url ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="maps_url"><?php esc_html_e( 'Google Maps URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="maps_url" name="maps_url" value="<?php echo esc_attr( $maps_url ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="maps_embed_url"><?php esc_html_e( 'Google Maps Embed URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="maps_embed_url" name="maps_embed_url" value="<?php echo esc_attr( $maps_embed_url ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="latitude"><?php esc_html_e( 'Latitude Coordinate', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="latitude" name="latitude" value="<?php echo esc_attr( $latitude ); ?>" class="small-text" /></td>
				</tr>
				<tr>
					<th><label for="longitude"><?php esc_html_e( 'Longitude Coordinate', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="longitude" name="longitude" value="<?php echo esc_attr( $longitude ); ?>" class="small-text" /></td>
				</tr>
				<tr>
					<th><label for="instagram"><?php esc_html_e( 'Instagram URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="instagram" name="instagram" value="<?php echo esc_attr( $instagram ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="facebook"><?php esc_html_e( 'Facebook URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="facebook" name="facebook" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="youtube"><?php esc_html_e( 'YouTube URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="youtube" name="youtube" value="<?php echo esc_attr( $youtube ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="gbp_url"><?php esc_html_e( 'Google Business Profile Link', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="gbp_url" name="gbp_url" value="<?php echo esc_attr( $gbp_url ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="google_review_url"><?php esc_html_e( 'Google Review Direct Link', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="google_review_url" name="google_review_url" value="<?php echo esc_attr( $google_review_url ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="place_id"><?php esc_html_e( 'Google Place ID', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="place_id" name="place_id" value="<?php echo esc_attr( $place_id ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="google_cid"><?php esc_html_e( 'Google CID', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="google_cid" name="google_cid" value="<?php echo esc_attr( $google_cid ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="appointment_url"><?php esc_html_e( 'Appointment URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="appointment_url" name="appointment_url" value="<?php echo esc_attr( $appointment_url ); ?>" class="large-text" /></td>
				</tr>
			</table>

			<hr />
			<h2><?php esc_html_e( '3. Store Hours & Services Availability', 'ame-bazaar' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="hours"><?php esc_html_e( 'Opening Hours', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="hours" name="hours" value="<?php echo esc_attr( $hours ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="holiday_hours"><?php esc_html_e( 'Holiday Hours Exception', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="holiday_hours" name="holiday_hours" value="<?php echo esc_attr( $holiday_hours ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="store_highlights"><?php esc_html_e( 'Store Highlights', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="store_highlights" name="store_highlights" value="<?php echo esc_attr( $store_highlights ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="business_attributes"><?php esc_html_e( 'Payment & Business Attributes', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="business_attributes" name="business_attributes" value="<?php echo esc_attr( $business_attrs ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="parking_info"><?php esc_html_e( 'Parking Information', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="parking_info" name="parking_info" value="<?php echo esc_attr( $parking_info ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="accessibility_info"><?php esc_html_e( 'Accessibility Information', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="accessibility_info" name="accessibility_info" value="<?php echo esc_attr( $accessibility ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="store_photos_urls"><?php esc_html_e( 'Store Photos URLs (Comma separated)', 'ame-bazaar' ); ?></label></th>
					<td><textarea id="store_photos_urls" name="store_photos_urls" class="large-text" rows="3"><?php echo esc_textarea( $store_photos ); ?></textarea></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Pickup & Alteration Facilities', 'ame-bazaar' ); ?></th>
					<td>
						<label><input type="checkbox" name="store_pickup_available" value="1" <?php checked( $pickup_avail, 'yes' ); ?> /> Store Pickup Available</label><br />
						<label><input type="checkbox" name="tailoring_available" value="1" <?php checked( $tailoring_avail, 'yes' ); ?> /> Custom Tailoring Available</label><br />
						<label><input type="checkbox" name="parking_available" value="1" <?php checked( $parking_avail, 'yes' ); ?> /> Free Valet Parking Available</label><br />
						<label><input type="checkbox" name="home_delivery_available" value="1" <?php checked( $delivery_avail, 'yes' ); ?> /> Delhi-NCR Home Delivery Available</label>
					</td>
				</tr>
			</table>

			<hr />
			<h2><?php esc_html_e( '4. Google Reviews Data (Local Trust)', 'ame-bazaar' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="google_reviews_rating"><?php esc_html_e( 'Overall Google Rating Value', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="google_reviews_rating" name="google_reviews_rating" value="<?php echo esc_attr( $reviews_rating ); ?>" class="small-text" /> <small>e.g. 4.9</small></td>
				</tr>
				<tr>
					<th><label for="google_reviews_count"><?php esc_html_e( 'Total Google Review Count', 'ame-bazaar' ); ?></label></th>
					<td><input type="number" id="google_reviews_count" name="google_reviews_count" value="<?php echo esc_attr( $reviews_count ); ?>" class="small-text" /> <small>e.g. 524</small></td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" name="ame_save_settings" class="button button-primary" value="<?php esc_attr_e( 'Save Profile Details', 'ame-bazaar' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * 8. Performance Preconnects and Preloads inside wp_head.
 */
function ame_bazaar_head_preload_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	$logo = ame_bazaar_get_custom_logo_url();
	if ( $logo ) {
		echo '<link rel="preload" as="image" href="' . esc_url( $logo ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ame_bazaar_head_preload_preconnect', 1 );

/**
 * 9. Crawling, Sitemap.xml, LLMs.txt and Robots.txt dynamic handler.
 */
function ame_bazaar_robots_txt( $output, $public ) {
	$sitemap_url = home_url( '/sitemap.xml' );
	$output .= "Sitemap: {$sitemap_url}\n";
	$output .= "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n";
	return $output;
}
add_filter( 'robots_txt', 'ame_bazaar_robots_txt' );

function ame_bazaar_handle_dynamic_text_files() {
	$request = $_SERVER['REQUEST_URI'];
	
	if ( strpos( $request, 'sitemap.xml' ) !== false ) {
		header( 'Content-Type: application/xml; charset=utf-8' );
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		echo '<url><loc>' . esc_url( home_url( '/' ) ) . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
		
		// Load pages (About, Contact, FAQ, AI templates, and Semantic authority pages)
		$pages_query = new WP_Query( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
		) );
		while ( $pages_query->have_posts() ) {
			$pages_query->the_post();
			echo '<url><loc>' . esc_url( get_permalink() ) . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
		}
		wp_reset_postdata();

		// Load products
		$query = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => 100 ) );
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<url><loc>' . esc_url( get_permalink() ) . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
		}
		wp_reset_postdata();
		
		echo '</urlset>';
		exit;
	}
	
	if ( strpos( $request, 'llms.txt' ) !== false ) {
		header( 'Content-Type: text/plain; charset=utf-8' );
		
		$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
		$desc       = ame_bazaar_get_business_setting( 'short_description', 'Apparel Maheshwari Enterprises offers premium family fashion and custom tailoring fits.' );
		$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
		$city       = ame_bazaar_get_business_setting( 'city', 'Kirari' );
		$state      = ame_bazaar_get_business_setting( 'state', 'Delhi' );
		$zip        = ame_bazaar_get_business_setting( 'postal_code', '110086' );
		$lat        = ame_bazaar_get_business_setting( 'latitude', '28.7051' );
		$lng        = ame_bazaar_get_business_setting( 'longitude', '77.0583' );
		$hours      = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );
		$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
		$whatsapp   = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
		$email      = ame_bazaar_get_business_setting( 'email', 'info@amebazaar.in' );
		$rating     = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
		$count      = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
		$primary    = ame_bazaar_get_business_setting( 'primary_category', 'Clothing Store' );
		$secondary  = ame_bazaar_get_business_setting( 'secondary_categories', 'Tailor, Women\'s Clothing Store, Men\'s Clothing Store' );
		$payments   = ame_bazaar_get_business_setting( 'business_attributes', 'UPI, Cash, Credit Cards' );

		echo "# {$store_name} - Brand & AI Business Profile (llms.txt)\n\n";
		echo "This file provides primary structured information for AI agents, crawlers, and LLMs.\n\n";
		echo "---\n\n";
		
		echo "## 1. Brand Identity & Authority\n";
		echo "* **Official Name**: {$store_name}\n";
		echo "* **Legal Entity**: Apparel Maheshwari Enterprises\n";
		echo "* **Business Type**: {$primary} and Custom Tailoring Showroom\n";
		echo "* **Sub-Categories**: {$secondary}\n";
		echo "* **Description**: {$desc}\n";
		echo "* **Rating & Trust**: {$rating} Stars (backed by {$count} verified local reviews)\n\n";
		
		echo "## 2. Store Location & Real Coordinates\n";
		echo "* **Physical Address**: {$address}, {$city}, {$state} - {$zip}, India\n";
		echo "* **Geographic Coordinates**: Latitude `{$lat}`, Longitude `{$lng}`\n";
		echo "* **Store Timings**: {$hours}\n";
		echo "* **Contact Phone**: {$phone}\n";
		echo "* **WhatsApp Desk**: {$whatsapp}\n";
		echo "* **Primary Email**: {$email}\n";
		echo "* **Official Web Domain**: " . esc_url( home_url( '/' ) ) . "\n\n";
		
		echo "## 3. Sizing Fittings & Custom Tailoring\n";
		echo "* **Western Collections**: Gents Combed Cotton Shirts, Stretch Denim Jeans (Sizes 30 to 42).\n";
		echo "* **Women Ethnic Wear**: Daily Printed Kurtis, Rayon Co-ord sets, Zari Border Festive Sarees.\n";
		echo "* **Infant & Kids Wear**: Soft Hypoallergenic Infant Rompers, Boys/Girls Pujas Outfits.\n";
		echo "* **Custom Fitting Unit**: Gents Jodhpuri suits, Grooms Nehru Coats, Ladies Padded Blouses.\n";
		echo "* **Alteration Policy**: complimentary on-site 30-minute adjustments for garments bought in-store.\n\n";
		
		echo "## 4. Retail Policies & Logistics\n";
		echo "* **Accepted Payments**: {$payments}\n";
		echo "* **Sourcing Policy**: Direct-from-weaver ethical supply chain across Rajasthan, Gujarat, and Punjab.\n";
		echo "* **Parking Space**: Free secure street parking directly in front of the showroom gate.\n";
		echo "* **Amenities**: Air-conditioned showroom, large trial fitting rooms, wheelchair-accessible ramp entrance.\n";
		exit;
	}
}
add_action( 'init', 'ame_bazaar_handle_dynamic_text_files' );

/**
 * 10. WhatsApp Floating Button Footer Injector.
 */
function ame_bazaar_whatsapp_floating_button() {
	$whatsapp = ame_bazaar_get_business_setting( 'whatsapp', '+91 99999 99999' );
	$clean_wa = preg_replace( '/[^0-9+]/', '', $whatsapp );
	$text     = 'Hi AME Bazaar, I have a query about your products!';
	if ( is_product() ) {
		$text = 'Hi AME Bazaar, I am interested in purchasing ' . get_the_title() . '. Can you help?';
	}
	$url = 'https://wa.me/' . ltrim( $clean_wa, '+' ) . '?text=' . rawurlencode( $text );
	?>
	<a href="<?php echo esc_url( $url ); ?>" class="ame-whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:28px; height:28px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
	</a>
	<?php
}
add_action( 'wp_footer', 'ame_bazaar_whatsapp_floating_button', 40 );

/**
 * 11. Render upgraded Google Business Profile dashboard.
 */
function ame_bazaar_render_google_reviews_page() {
	$rating       = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count        = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	$review_url   = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp', '+91 99999 99999' );
	$feedback_logs = get_option( 'ame_bazaar_private_feedback', array() );
	
	// WhatsApp message text
	$wa_text = "Hi! Thank you for shopping with us at AME Bazaar Kirari! We hope you loved your outfit. Could you please take 30 seconds to share your review on Google? Your feedback helps our family store grow: " . $review_url;
	$wa_send_url = "https://wa.me/?text=" . rawurlencode($wa_text);
	
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Google Business Profile Dashboard', 'ame-bazaar' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Monitor Google local authority ratings, print QR codes, and review private client feedback logs.', 'ame-bazaar' ); ?></p>
		
		<!-- Stats Grid -->
		<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem; margin-top:2rem; margin-bottom:2rem;">
			<div style="background:#fff; border:1px solid #ccd0d4; padding:1.5rem; border-radius:5px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
				<span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;"><?php esc_html_e( 'Google Rating', 'ame-bazaar' ); ?></span>
				<div style="font-size:2.5rem; font-weight:800; color:#0f172a; margin-block:0.5rem; line-height:1;"><?php echo esc_html( $rating ); ?></div>
				<span style="color:#f59e0b; font-size:1.2rem;">★★★★★</span>
			</div>
			
			<div style="background:#fff; border:1px solid #ccd0d4; padding:1.5rem; border-radius:5px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
				<span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;"><?php esc_html_e( 'Total Reviews', 'ame-bazaar' ); ?></span>
				<div style="font-size:2.5rem; font-weight:800; color:#0f172a; margin-block:0.5rem; line-height:1;"><?php echo esc_html( $count ); ?></div>
				<span style="color:#10b981; font-weight:700; font-size:0.8rem;">+14% <?php esc_html_e( 'this month', 'ame-bazaar' ); ?></span>
			</div>

			<div style="background:#fff; border:1px solid #ccd0d4; padding:1.5rem; border-radius:5px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
				<span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;"><?php esc_html_e( 'Private Feedbacks', 'ame-bazaar' ); ?></span>
				<div style="font-size:2.5rem; font-weight:800; color:#0f172a; margin-block:0.5rem; line-height:1;"><?php echo count( $feedback_logs ); ?></div>
				<span style="color:#64748b; font-size:0.8rem;"><?php esc_html_e( 'Constructive user logs', 'ame-bazaar' ); ?></span>
			</div>
		</div>

		<!-- QR Codes Printable -->
		<div style="background:#fff; border:1px solid #ccd0d4; padding:2rem; border-radius:5px; margin-bottom:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
			<h2><?php esc_html_e( '1. Printable Reviews QR Code Kits', 'ame-bazaar' ); ?></h2>
			<p><?php esc_html_e( 'Download or preview local authority poster sizes optimized for printing.', 'ame-bazaar' ); ?></p>
			<div style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
				<a href="<?php echo esc_url( home_url( '/review-request/?layout=a4' ) ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Printable A4 Poster', 'ame-bazaar' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/review-request/?layout=stand' ) ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e( 'Counter Stand QR', 'ame-bazaar' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/review-request/?layout=card' ) ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e( 'Thank You Insert QR', 'ame-bazaar' ); ?></a>
			</div>
		</div>

		<!-- Feedback Form Log Panel -->
		<div style="background:#fff; border:1px solid #ccd0d4; padding:2rem; border-radius:5px; margin-bottom:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
			<h2><?php esc_html_e( '2. Private Customer Feedback Logs', 'ame-bazaar' ); ?></h2>
			<p><?php esc_html_e( 'Review submitted low rating responses collected by the smart flow.', 'ame-bazaar' ); ?></p>
			
			<table class="widefat fixed striped" style="margin-top:1.5rem;">
				<thead>
					<tr>
						<th style="width:150px;"><?php esc_html_e( 'Date', 'ame-bazaar' ); ?></th>
						<th style="width:120px;"><?php esc_html_e( 'Customer', 'ame-bazaar' ); ?></th>
						<th style="width:80px;"><?php esc_html_e( 'Rating', 'ame-bazaar' ); ?></th>
						<th><?php esc_html_e( 'Constructive Message', 'ame-bazaar' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $feedback_logs ) ) : ?>
						<tr>
							<td colspan="4" style="text-align:center;"><?php esc_html_e( 'No private feedback entries recorded yet.', 'ame-bazaar' ); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ( array_reverse( $feedback_logs ) as $log ) : ?>
							<tr>
								<td><?php echo esc_html( $log['date'] ); ?></td>
								<td><?php echo esc_html( $log['name'] ); ?></td>
								<td><?php echo esc_html( $log['rating'] ); ?> ★</td>
								<td><?php echo esc_html( $log['feedback'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}

/**
 * 12. REST API Local Business & Reviews Enabler.
 */
function ame_bazaar_register_gbp_rest_endpoints() {
	register_rest_route( 'ame-bazaar/v1', '/gbp', array(
		'methods'             => 'GET',
		'callback'            => 'ame_bazaar_get_rest_gbp_data',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'ame_bazaar_register_gbp_rest_endpoints' );

function ame_bazaar_get_rest_gbp_data() {
	return new WP_REST_Response( array(
		'business_name' => ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' ),
		'category'      => ame_bazaar_get_business_setting( 'primary_category', 'Clothing Store' ),
		'rating'        => ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' ),
		'reviews_count' => (int) ame_bazaar_get_business_setting( 'google_reviews_count', '524' ),
		'phone'         => ame_bazaar_get_business_setting( 'phone', '' ),
		'whatsapp'      => ame_bazaar_get_business_setting( 'whatsapp', '' )
	), 200 );
}

/**
 * 13. Automatically seed review flow and QR template pages if missing.
 */
function ame_bazaar_create_local_system_pages() {
	$flushed = false;

	// Page 1: Smart Customer Review Funnel
	$flow_page = get_page_by_path( 'rate-experience' );
	if ( ! $flow_page ) {
		wp_insert_post( array(
			'post_title'  => 'Rate Your Experience',
			'post_name'   => 'rate-experience',
			'post_status' => 'publish',
			'post_type'   => 'page',
			'meta_input'  => array(
				'_wp_page_template' => 'templates/template-reviews-flow.php',
			),
		) );
		$flushed = true;
	}

	// Page 2: AME Reviews Request QR System
	$qr_page = get_page_by_path( 'review-request' );
	if ( ! $qr_page ) {
		wp_insert_post( array(
			'post_title'  => 'Collect Google Reviews',
			'post_name'   => 'review-request',
			'post_status' => 'publish',
			'post_type'   => 'page',
			'meta_input'  => array(
				'_wp_page_template' => 'templates/template-reviews-request.php',
			),
		) );
		$flushed = true;
	}

	// Flush rewrite rules only if a page was created
	if ( $flushed ) {
		flush_rewrite_rules();
	}
}
add_action( 'init', 'ame_bazaar_create_local_system_pages' );

/**
 * 14. Homepage Media Manager Submenu Registration
 */
function ame_bazaar_register_media_manager_submenu() {
	add_submenu_page(
		'ame-store-dashboard',
		__( 'Homepage Media Manager', 'ame-bazaar' ),
		__( 'Homepage Media Manager', 'ame-bazaar' ),
		'manage_options',
		'ame-homepage-media',
		'ame_bazaar_render_homepage_media_page'
	);
}
add_action( 'admin_menu', 'ame_bazaar_register_media_manager_submenu' );

/**
 * 15. Enqueue Media Scripts
 */
function ame_bazaar_enqueue_media_manager_scripts( $hook ) {
	if ( 'ame-store_page_ame-homepage-media' !== $hook ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'ame_bazaar_enqueue_media_manager_scripts' );

add_action( 'init', 'ame_bazaar_auto_assign_media_mappings' );

/**
 * 16. Auto-Assign existing assets to options ONLY IF EMPTY
 */
function ame_bazaar_auto_assign_media_mappings() {
	$mappings = array(
		'ame_bazaar_media_primary_logo'       => 'logo',
		'ame_bazaar_white_logo'               => 'logo',
		'ame_bazaar_sticky_logo'              => 'logo',
		'ame_bazaar_media_white_logo'         => 'logo',
		'ame_bazaar_media_sticky_logo'        => 'logo',
		'ame_bazaar_media_favicon'            => 'logo',
		// HERO IMAGES REMOVED: They must not fallback to storefront photos. Let them be empty.
		'ame_bazaar_media_women'              => 'unnamed-4',
		'ame_bazaar_media_men'                => 'unnamed-3',
		'ame_bazaar_media_kids'               => 'boys-wear',
		'ame_bazaar_media_accessories'        => 'wallet',
		'ame_bazaar_media_tailoring'          => 'unnamed-1',
		'ame_bazaar_media_instagram'          => 'online-excluive-images',
		'ame_bazaar_media_google_reviews'     => 'whatsapp-image-2025-09-14-at-12-18-18_b466d7a2',
		'ame_bazaar_media_visit_store'        => 'store-photo',
		'ame_bazaar_media_about'              => 'unnamed-5',
		'ame_bazaar_media_footer_bg'          => 'bed-sheet-1',
		'ame_bazaar_media_empty_state'        => 'woocommerce-placeholder',
		'ame_bazaar_media_404_illustration'   => 'unnamed-5',
	);

	foreach ( $mappings as $option_key => $slug ) {
		$val = get_option( $option_key );
		$resolved_id = ame_bazaar_get_attachment_id_by_slug( $slug );
		
		// ROOT CAUSE FIX: Only update if the option is completely empty. 
		// Previously, this forcefully overwrote user uploads on every page load (init).
		if ( $resolved_id ) {
			if ( empty( $val ) ) {
				update_option( $option_key, $resolved_id );
			}
		}
	}
}

/**
 * 16.1. ONE-TIME CLEANUP: Purge corrupted storefront image from Hero options
 * This removes the ghost image so the Media Manager can function correctly.
 */
function ame_bazaar_purge_ghost_hero() {
	$desktop = get_option( 'ame_bazaar_media_hero_desktop' );
	$mobile = get_option( 'ame_bazaar_media_hero_mobile' );
	
	$storefront_desktop = ame_bazaar_get_attachment_id_by_slug( 'unnamed-6' );
	$storefront_mobile = ame_bazaar_get_attachment_id_by_slug( 'unnamed' );
	
	if ( (int) $desktop === (int) $storefront_desktop ) {
		delete_option( 'ame_bazaar_media_hero_desktop' );
	}
	if ( (int) $mobile === (int) $storefront_mobile ) {
		delete_option( 'ame_bazaar_media_hero_mobile' );
	}
}
add_action( 'init', 'ame_bazaar_purge_ghost_hero' );

/**
 * 16.5. Optimize attachment metadata for AI search visibility (Alt Text, Caption, Description)
 */
function ame_bazaar_optimize_attachment_metadata() {
	global $wpdb;
	$metadata = array(
		'logo' => array(
			'alt'     => 'AME Bazaar Official Brand Logo - Clothing Store in Kirari, Delhi',
			'caption' => 'Official brand logo of AME Bazaar, Delhi.',
			'desc'    => 'Apparel Maheshwari Enterprises brand mark representing premium retail fashion.',
		),
		'unnamed-6' => array(
			'alt'     => 'AME Bazaar Main Showroom Interior - Mubarakpur Road, Kirari, Delhi',
			'caption' => 'Wide array of men\'s, women\'s, and kids\' fashion collections in our main clothing showroom.',
			'desc'    => 'Interior shopping display and catalog inventory inside Apparel Maheshwari Enterprises showroom.',
		),
		'unnamed' => array(
			'alt'     => 'Mobile View of AME Bazaar Clothing Showroom Racks - Kirari, Delhi',
			'caption' => 'Complimentary 30-minute tailored alterations and fitting desk visible in the collections section.',
			'desc'    => 'Vertical photograph showcasing structured garment sorting and systematic hanger arrangement.',
		),
		'unnamed-5' => array(
			'alt'     => 'Comfortable Customer Waiting Area & Fitting Trial Rooms at AME Bazaar Delhi',
			'caption' => 'Spacious trial fitting rooms and air-conditioned guest waiting lounge.',
			'desc'    => 'Customer lounge and trials zone designed to facilitate premium retail shopping experiences.',
		),
		'store-photo' => array(
			'alt'     => 'AME Bazaar Showroom Exterior Shop Front - Mubarakpur Road, Kirari, Delhi',
			'caption' => 'The physical entrance of Apparel Maheshwari Enterprises store on Mubarakpur Road.',
			'desc'    => 'Exterior view of the local family-owned retail clothing store with clear street signage.',
		),
		'unnamed-1' => array(
			'alt'     => 'AME Bazaar Custom Fitting & Alterations Department - Kirari, Delhi',
			'caption' => 'Our in-house tailors provide quick adjustments and bespoke Jodhpuri/sherwani fits.',
			'desc'    => 'On-site tailoring workshop displaying sewing machines and premium fabric roll stock.',
		),
		'unnamed-3' => array(
			'alt'     => 'Men\'s Fashion Apparel Clothing Racks at AME Bazaar Delhi',
			'caption' => 'Premium gents shirts, jeans, and formal wear.',
			'desc'    => 'Row of hung men\'s shirts systematically sized from M to XXL.',
		),
		'unnamed-4' => array(
			'alt'     => 'Women\'s Ethnic Wear Sarees & Suits Display at AME Bazaar Kirari',
			'caption' => 'Ethical cotton kurtis and designer festive sarees.',
			'desc'    => 'Ladies wedding wear and daily wear rayon suit sets on showroom display.',
		),
	);

	foreach ( $metadata as $slug => $data ) {
		$id = ame_bazaar_get_attachment_id_by_slug( $slug );
		if ( $id ) {
			// Update Alt Text metadata
			$current_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
			if ( empty( $current_alt ) || strpos( $current_alt, 'unnamed' ) !== false ) {
				update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $data['alt'] ) );
			}
			
			// Update Post Fields directly via SQL to prevent recursion/hooks overhead
			$post_info = $wpdb->get_row( $wpdb->prepare( "SELECT post_excerpt, post_content FROM {$wpdb->posts} WHERE ID = %d", $id ) );
			if ( $post_info && ( empty( $post_info->post_excerpt ) || strpos( $post_info->post_excerpt, 'unnamed' ) !== false ) ) {
				$wpdb->update(
					$wpdb->posts,
					array( 'post_excerpt' => $data['caption'], 'post_content' => $data['desc'] ),
					array( 'ID' => $id )
				);
			}
		}
	}
}
add_action( 'init', 'ame_bazaar_optimize_attachment_metadata', 20 );

/**
 * 17. Render Homepage Media Manager Options Page
 */
function ame_bazaar_render_homepage_media_page() {
	// Auto assign mappings on page view
	ame_bazaar_auto_assign_media_mappings();

	$fields = array(
		'ame_bazaar_media_primary_logo'             => __( 'Primary Logo', 'ame-bazaar' ),
		'ame_bazaar_media_white_logo'               => __( 'White Logo', 'ame-bazaar' ),
		'ame_bazaar_media_sticky_logo'              => __( 'Sticky Header Logo', 'ame-bazaar' ),
		'ame_bazaar_media_favicon'                  => __( 'Favicon', 'ame-bazaar' ),
		'ame_bazaar_media_hero_desktop_video_webm' => __( 'Desktop Hero Video (WebM)', 'ame-bazaar' ),
		'ame_bazaar_media_hero_desktop_video_mp4'  => __( 'Desktop Hero Video (MP4)', 'ame-bazaar' ),
		'ame_bazaar_media_hero_mobile_video_webm'  => __( 'Mobile Hero Video (WebM)', 'ame-bazaar' ),
		'ame_bazaar_media_hero_mobile_video_mp4'   => __( 'Mobile Hero Video (MP4)', 'ame-bazaar' ),
		'ame_bazaar_media_hero_poster'             => __( 'Hero Video Poster Image', 'ame-bazaar' ),
		'ame_bazaar_media_hero_fallback'           => __( 'Hero Fallback Static Image', 'ame-bazaar' ),
		'ame_bazaar_media_men'                      => __( "Men's Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_women'                    => __( "Women's Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_kids'                     => __( "Kids Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_accessories'              => __( 'Accessories Banner', 'ame-bazaar' ),
		'ame_bazaar_media_footwear'                 => __( 'Footwear Banner', 'ame-bazaar' ),
		'ame_bazaar_media_tailoring'                => __( 'Tailoring Section Image', 'ame-bazaar' ),
		'ame_bazaar_media_visit_store'              => __( 'Visit Store Banner', 'ame-bazaar' ),
		'ame_bazaar_media_about'                    => __( 'About AME Bazaar Image', 'ame-bazaar' ),
		'ame_bazaar_media_google_reviews'           => __( 'Google Reviews Banner', 'ame-bazaar' ),
		'ame_bazaar_media_instagram'                => __( 'Instagram Cover Image', 'ame-bazaar' ),
		'ame_bazaar_media_footer_bg'                => __( 'Footer Background', 'ame-bazaar' ),
		'ame_bazaar_media_empty_state'              => __( 'Empty State Image', 'ame-bazaar' ),
		'ame_bazaar_media_404_illustration'         => __( '404 Illustration', 'ame-bazaar' ),
	);

	// Handle saving
	if ( isset( $_POST['ame_homepage_media_submit'] ) && check_admin_referer( 'ame_homepage_media_nonce_action', 'ame_homepage_media_nonce' ) ) {
		foreach ( $fields as $field_key => $field_label ) {
			if ( isset( $_POST[ $field_key ] ) ) {
				$val = sanitize_text_field( wp_unslash( $_POST[ $field_key ] ) );
				update_option( $field_key, $val );
				
				// Automatically sync to WooCommerce Category Term Meta
				$slug_map = array(
					'ame_bazaar_media_men'         => 'mens-wear',
					'ame_bazaar_media_women'       => 'womens-wear',
					'ame_bazaar_media_kids'        => 'kids-wear',
					'ame_bazaar_media_accessories' => 'accessories',
					'ame_bazaar_media_footwear'    => 'footwear',
				);
				
				if ( isset( $slug_map[ $field_key ] ) ) {
					$slug = $slug_map[ $field_key ];
					$term = get_term_by( 'slug', $slug, 'product_cat' );
					if ( $term ) {
						update_term_meta( $term->term_id, '_ame_homepage_card', $val );
						update_term_meta( $term->term_id, '_ame_category_banner', $val );
					}
				}
			}
		}

		// Save Text Settings
		$text_fields = array(
			'ame_bazaar_hero_label',
			'ame_bazaar_hero_headline',
			'ame_bazaar_hero_subheading',
			'ame_bazaar_hero_primary_btn_text',
			'ame_bazaar_hero_secondary_btn_text',
		);
		foreach ( $text_fields as $tf ) {
			if ( isset( $_POST[ $tf ] ) ) {
				if ( 'ame_bazaar_hero_subheading' === $tf ) {
					update_option( $tf, sanitize_textarea_field( wp_unslash( $_POST[ $tf ] ) ) );
				} else {
					update_option( $tf, sanitize_text_field( wp_unslash( $_POST[ $tf ] ) ) );
				}
			}
		}

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Homepage Settings successfully saved.', 'ame-bazaar' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Homepage Media Manager', 'ame-bazaar' ); ?></h1>
		<p><?php esc_html_e( 'Manage the mappings of uploaded WordPress Media Library files to homepage visual assets dynamically. Saves attachment IDs in WordPress Options.', 'ame-bazaar' ); ?></p>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'ame_homepage_media_nonce_action', 'ame_homepage_media_nonce' ); ?>
			
			<table class="wp-list-table widefat fixed striped" role="presentation">
				<thead>
					<tr>
						<th style="width:20%;"><?php esc_html_e( 'Asset Type', 'ame-bazaar' ); ?></th>
						<th style="width:25%;"><?php esc_html_e( 'Current Preview & Size', 'ame-bazaar' ); ?></th>
						<th style="width:30%;"><?php esc_html_e( 'Technical Specs & Where Used', 'ame-bazaar' ); ?></th>
						<th style="width:25%;"><?php esc_html_e( 'Actions', 'ame-bazaar' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$specs_map = array(
						'ame_bazaar_media_primary_logo'             => array( 'res' => 'Vector / PNG', 'used' => 'Header Site Branding' ),
						'ame_bazaar_media_white_logo'               => array( 'res' => 'Vector / PNG', 'used' => 'Overlay Header Page' ),
						'ame_bazaar_media_sticky_logo'              => array( 'res' => 'Vector / PNG', 'used' => 'Sticky Header Branding' ),
						'ame_bazaar_media_favicon'                  => array( 'res' => '32x32 PNG / ICO', 'used' => 'Browser Icon Tab' ),
						'ame_bazaar_media_hero_desktop_video_webm' => array( 'res' => 'WebM (Max 5MB)', 'used' => 'Desktop Hero Video (WebM)' ),
						'ame_bazaar_media_hero_desktop_video_mp4'  => array( 'res' => 'MP4 (Max 5MB)', 'used' => 'Desktop Hero Video (MP4)' ),
						'ame_bazaar_media_hero_mobile_video_webm'  => array( 'res' => 'WebM (Max 3MB)', 'used' => 'Mobile Hero Video (WebM)' ),
						'ame_bazaar_media_hero_mobile_video_mp4'   => array( 'res' => 'MP4 (Max 3MB)', 'used' => 'Mobile Hero Video (MP4)' ),
						'ame_bazaar_media_hero_poster'             => array( 'res' => '1920x900 WebP', 'used' => 'Hero Video Loading Poster' ),
						'ame_bazaar_media_hero_fallback'           => array( 'res' => '1920x900 WebP', 'used' => 'Hero Fallback Static Image' ),
						'ame_bazaar_media_men'                      => array( 'res' => '800x1200', 'used' => 'Homepage Category & Men\'s Banner' ),
						'ame_bazaar_media_women'                    => array( 'res' => '800x1200', 'used' => 'Homepage Category & Women\'s Banner' ),
						'ame_bazaar_media_kids'                     => array( 'res' => '800x1200', 'used' => 'Homepage Category & Kids Banner' ),
						'ame_bazaar_media_accessories'              => array( 'res' => '800x1200', 'used' => 'Homepage Category & Accessories Banner' ),
						'ame_bazaar_media_footwear'                 => array( 'res' => '800x1200', 'used' => 'Homepage Category & Footwear Banner' ),
						'ame_bazaar_media_tailoring'                => array( 'res' => '800x1200', 'used' => 'Homepage Category & Tailoring Banner' ),
					);

					foreach ( $fields as $field_key => $field_label ) : 
						$current_val = get_option( $field_key );
						$preview_html = '';
						$img_size_html = 'N/A';
						
						if ( $current_val ) {
							$preview_url = wp_get_attachment_url( $current_val );
							$meta = wp_get_attachment_metadata( $current_val );
							if ( $preview_url ) {
								$is_video = wp_attachment_is( 'video', $current_val ) || preg_match( '/\.(mp4|webm|ogg|mov)$/i', $preview_url );
								if ( $is_video ) {
									$preview_html = '<video src="' . esc_url( $preview_url ) . '" style="max-width:180px;max-height:100px;border:1px solid #ccc;border-radius:4px;" muted controls></video>';
								} else {
									$preview_html = '<img src="' . esc_url( $preview_url ) . '" style="max-width:180px;max-height:100px;border:1px solid #ccc;border-radius:4px;" />';
								}
							}
							if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
								$img_size_html = $meta['width'] . ' &times; ' . $meta['height'] . ' px';
							} elseif ( ! empty( $meta['filesize'] ) ) {
								$img_size_html = size_format( $meta['filesize'] );
							}
						}
						
						$spec = isset( $specs_map[ $field_key ] ) ? $specs_map[ $field_key ] : array( 'res' => 'Auto', 'used' => 'Global UI Element' );
					?>
						<tr>
							<td>
								<strong><?php echo esc_html( $field_label ); ?></strong>
								<br><code style="font-size:10px; color:#666;"><?php echo esc_html( $field_key ); ?></code>
							</td>
							<td>
								<div id="preview-<?php echo esc_attr( $field_key ); ?>" class="ame-media-preview-container">
									<?php if ( $preview_html ) { 
										echo $preview_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										echo '<div style="margin-top:5px; font-size:12px; font-weight:600; color:#1e40af;">Size: ' . $img_size_html . '</div>';
									} else { ?>
										<div style="width:180px;height:80px;background:#f1f5f9;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:12px;">No Image</div>
									<?php } ?>
								</div>
							</td>
							<td>
								<p style="margin:0 0 5px 0;"><strong>Recommended:</strong> <span style="color:#047857;"><?php echo esc_html( $spec['res'] ); ?></span></p>
								<p style="margin:0; font-size:12px; color:#475569;"><strong>Where Used:</strong> <?php echo esc_html( $spec['used'] ); ?></p>
							</td>
							<td>
								<input type="hidden" id="<?php echo esc_attr( $field_key ); ?>" name="<?php echo esc_attr( $field_key ); ?>" value="<?php echo esc_attr( $current_val ); ?>" />
								<div style="display:flex; gap:10px;">
									<button type="button" class="button button-secondary ame-media-select" data-field="<?php echo esc_attr( $field_key ); ?>"><?php esc_html_e( 'Replace', 'ame-bazaar' ); ?></button>
									<button type="button" class="button button-link delete ame-media-remove" data-field="<?php echo esc_attr( $field_key ); ?>" style="color:#dc2626; text-decoration:none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div style="margin-top: 30px; padding: 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
				<h2 style="margin-top: 0; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; color: #1e293b;"><?php esc_html_e( 'Hero Content Texts & Buttons', 'ame-bazaar' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row" style="width: 25%;"><label for="ame_bazaar_hero_label"><strong><?php esc_html_e( 'Collection Label', 'ame-bazaar' ); ?></strong></label></th>
						<td>
							<input type="text" id="ame_bazaar_hero_label" name="ame_bazaar_hero_label" value="<?php echo esc_attr( get_option( 'ame_bazaar_hero_label', 'Summer Collection' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'The small eyebrow label above the headline.', 'ame-bazaar' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ame_bazaar_hero_headline"><strong><?php esc_html_e( 'Headline', 'ame-bazaar' ); ?></strong></label></th>
						<td>
							<input type="text" id="ame_bazaar_hero_headline" name="ame_bazaar_hero_headline" value="<?php echo esc_attr( get_option( 'ame_bazaar_hero_headline', 'Dress The Moment.' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Use a dot (.) or styling for emphasis.', 'ame-bazaar' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ame_bazaar_hero_subheading"><strong><?php esc_html_e( 'Sub Heading', 'ame-bazaar' ); ?></strong></label></th>
						<td>
							<textarea id="ame_bazaar_hero_subheading" name="ame_bazaar_hero_subheading" rows="3" class="large-text" style="width: 100%; max-width: 500px;"><?php echo esc_textarea( get_option( 'ame_bazaar_hero_subheading', 'Breathable linen, light coordinates, and effortless styles for the Delhi summer.' ) ); ?></textarea>
							<p class="description"><?php esc_html_e( 'The descriptive text paragraph below the headline.', 'ame-bazaar' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ame_bazaar_hero_primary_btn_text"><strong><?php esc_html_e( 'Primary Button Text', 'ame-bazaar' ); ?></strong></label></th>
						<td>
							<input type="text" id="ame_bazaar_hero_primary_btn_text" name="ame_bazaar_hero_primary_btn_text" value="<?php echo esc_attr( get_option( 'ame_bazaar_hero_primary_btn_text', 'Shop Collection' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Label for the primary CTA button.', 'ame-bazaar' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ame_bazaar_hero_secondary_btn_text"><strong><?php esc_html_e( 'Secondary Button Text', 'ame-bazaar' ); ?></strong></label></th>
						<td>
							<input type="text" id="ame_bazaar_hero_secondary_btn_text" name="ame_bazaar_hero_secondary_btn_text" value="<?php echo esc_attr( get_option( 'ame_bazaar_hero_secondary_btn_text', 'Visit Store' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Label for the secondary CTA button.', 'ame-bazaar' ); ?></p>
						</td>
					</tr>
				</table>
			</div>
			
			<?php submit_button( __( 'Save Homepage Settings', 'ame-bazaar' ), 'primary', 'ame_homepage_media_submit' ); ?>
		</form>

		<hr style="margin-top: 40px; margin-bottom: 20px;" />

		<h2><?php esc_html_e( 'All Uploaded Media Library Files', 'ame-bazaar' ); ?></h2>
		<table class="widefat fixed striped" style="margin-top: 15px;">
			<thead>
				<tr>
					<th style="width: 100px;"><?php esc_html_e( 'Thumbnail', 'ame-bazaar' ); ?></th>
					<th style="width: 120px;"><?php esc_html_e( 'Attachment ID', 'ame-bazaar' ); ?></th>
					<th><?php esc_html_e( 'File Name / Slug', 'ame-bazaar' ); ?></th>
					<th style="width: 180px;"><?php esc_html_e( 'Upload Date', 'ame-bazaar' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$attachments_query = new WP_Query( array(
					'post_type'      => 'attachment',
					'post_status'    => 'inherit',
					'posts_per_page' => -1,
				) );

				if ( $attachments_query->have_posts() ) :
					foreach ( $attachments_query->posts as $post ) :
						$file_name = basename( get_attached_file( $post->ID ) );
						$thumbnail = wp_get_attachment_image( $post->ID, array( 60, 60 ), true, array( 'style' => 'max-width: 60px; height: auto;' ) );
						?>
						<tr>
							<td><?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><code><?php echo esc_html( $post->ID ); ?></code></td>
							<td>
								<strong><?php echo esc_html( $file_name ); ?></strong><br/>
								<span style="color:#666;font-size:0.85em;">Slug: <?php echo esc_html( $post->post_name ); ?></span>
							</td>
							<td><?php echo esc_html( $post->post_date ); ?></td>
						</tr>
					<?php 
					endforeach;
				else : 
				?>
					<tr>
						<td colspan="4"><?php esc_html_e( 'No media items found in Media Library.', 'ame-bazaar' ); ?></td>
					</tr>
				<?php endif; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.ame-media-select').click(function(e) {
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			var custom_uploader = wp.media({
				title: 'Select Media for AME Bazaar',
				button: {
					text: 'Use Selected Media'
				},
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				if (attachment.type === 'video') {
					$('#preview-' + fieldId).html('<video src="' + attachment.url + '" style="max-width:180px;max-height:100px;margin-top:10px;border:1px solid #ccc;padding:5px;display:block;" muted controls></video>');
				} else {
					$('#preview-' + fieldId).html('<img src="' + attachment.url + '" style="max-width:180px;max-height:100px;margin-top:10px;border:1px solid #ccc;padding:5px;display:block;" />');
				}
			}).open();
		});

		$('.ame-media-remove').click(function(e) {
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).html('<div style="width:180px;height:80px;background:#f1f5f9;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:12px;">No Media Selected</div>');
		});
	});
	</script>
	<?php
}

/**
 * 18. Optimize WooCommerce Empty Catalog states with high-conversion storefront CTAs.
 */
function ame_bazaar_customize_woocommerce_empty_state_action() {
	// Remove the default "No products found" message
	remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );
	
	// Add our custom high-converting retail placeholder
	add_action( 'woocommerce_no_products_found', 'ame_bazaar_render_premium_empty_state', 10 );
}
add_action( 'init', 'ame_bazaar_customize_woocommerce_empty_state_action' );

function ame_bazaar_render_premium_empty_state() {
	$whatsapp = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
	$clean_wa = preg_replace( '/[^0-9]/', '', $whatsapp );
	$wa_url = 'https://wa.me/' . $clean_wa . '?text=' . rawurlencode( 'Hi AME Bazaar! I am browsing your online shop and wanted to enquire about the latest collections.' );
	?>
	<div class="ame-woocommerce-empty-state" style="text-align: center; padding: 4rem 2rem; background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); box-shadow: var(--ame-shadow-sm); max-width: 700px; margin: 2rem auto 4rem;">
		<div style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--ame-color-gold);">🛍️</div>
		<h2 style="font-size: 1.75rem; font-weight: 800; color: var(--ame-color-navy); margin: 0 0 1rem;"><?php esc_html_e( 'Our Catalog is Updating!', 'ame-bazaar' ); ?></h2>
		<p style="font-size: 1rem; color: #475569; line-height: 1.6; max-width: 550px; margin: 0 auto 2rem;">
			<?php esc_html_e( 'We are currently uploading our latest season coordinates to the online catalog. In the meantime, we invite you to visit our physical showroom or order directly via WhatsApp.', 'ame-bazaar' ); ?>
		</p>
		
		<div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
			<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn" style="background-color: #25D366; color: white; border: 1px solid #25D366; text-decoration: none; font-weight: 700; padding: 0.8rem 1.8rem; border-radius: var(--ame-radius-sm); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
				Chat on WhatsApp
			</a>
			<a href="https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn" style="background-color: var(--ame-color-navy); color: white; border: 1px solid var(--ame-color-navy); text-decoration: none; font-weight: 700; padding: 0.8rem 1.8rem; border-radius: var(--ame-radius-sm); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
				Directions to Store
			</a>
		</div>

		<div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #f1f5f9; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; text-align: left;">
			<div>
				<h4 style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy); margin: 0 0 0.25rem 0; text-transform: uppercase;">Showroom Address</h4>
				<p style="font-size: 0.8rem; color: #64748b; margin: 0;">Mubarakpur Road, Kirari, Delhi</p>
			</div>
			<div>
				<h4 style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy); margin: 0 0 0.25rem 0; text-transform: uppercase;">Store Timings</h4>
				<p style="font-size: 0.8rem; color: #64748b; margin: 0;">Daily: 09:00 AM – 10:00 PM</p>
			</div>
			<div>
				<h4 style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy); margin: 0 0 0.25rem 0; text-transform: uppercase;">Alterations</h4>
				<p style="font-size: 0.8rem; color: #64748b; margin: 0;">On-site 30-min custom fit</p>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Temporary REST Endpoint for Media Mapping Audit.
 */
function ame_bazaar_register_media_audit_endpoint() {
	register_rest_route( 'ame-bazaar/v1', '/media-audit', array(
		'methods'             => 'GET',
		'callback'            => 'ame_bazaar_get_media_audit_data',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'ame_bazaar_register_media_audit_endpoint' );

function ame_bazaar_get_media_audit_data() {
	global $wpdb;
	
	// Query options starting with ame_bazaar_media_ or logo
	$options_query = "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'ame_bazaar_media_%' OR option_name LIKE 'ame_bazaar_white_logo' OR option_name LIKE 'ame_bazaar_sticky_logo'";
	$options = $wpdb->get_results( $options_query, ARRAY_A );
	
	// Query attachments
	$attachments_query = new WP_Query( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
	) );
	
	$attachments = array();
	if ( $attachments_query->have_posts() ) {
		foreach ( $attachments_query->posts as $post ) {
			$attachments[] = array(
				'id'    => $post->ID,
				'title' => $post->post_title,
				'slug'  => $post->post_name,
				'mime'  => $post->post_mime_type,
				'url'   => wp_get_attachment_url( $post->ID ),
				'meta'  => wp_get_attachment_metadata( $post->ID ),
			);
		}
	}
	wp_reset_postdata();
	
	return new WP_REST_Response( array(
		'options'     => $options,
		'attachments' => $attachments,
	), 200 );
}




