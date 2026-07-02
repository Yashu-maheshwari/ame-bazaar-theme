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
	return $val ? $val : $default;
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
			'store_pickup_available', 'tailoring_available', 'parking_available', 'home_delivery_available'
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
					<th><label for="short_description"><?php esc_html_e( 'Short Description', 'ame-bazaar' ); ?></label></th>
					<td><textarea id="short_description" name="short_description" class="large-text" rows="3"><?php echo esc_textarea( $short_desc ); ?></textarea></td>
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
					<th><label for="maps_url"><?php esc_html_e( 'Google Maps URL', 'ame-bazaar' ); ?></label></th>
					<td><input type="text" id="maps_url" name="maps_url" value="<?php echo esc_attr( $maps_url ); ?>" class="large-text" /></td>
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
		echo "# AME Bazaar - LLMs Discovery File\n\n";
		echo "This file provides index paths for AI crawlers.\n\n";
		echo "## Main URLs\n";
		echo "- Home: " . esc_url( home_url( '/' ) ) . "\n";
		echo "- Shop: " . esc_url( wc_get_page_permalink( 'shop' ) ) . "\n";
		echo "- Location: Mubarakpur Road, Kirari, Delhi\n";
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


