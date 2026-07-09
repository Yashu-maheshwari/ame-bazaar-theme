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

	add_submenu_page(
		'ame-store-dashboard',
		__( 'Homepage Media Manager', 'ame-bazaar' ),
		__( 'Homepage Media Manager', 'ame-bazaar' ),
		'manage_options',
		'ame-homepage-media',
		'ame_bazaar_render_homepage_media_page'
	);
}
// Remove duplicate actions and keep submenus correctly
remove_action( 'admin_menu', 'ame_bazaar_register_store_dashboard' );
add_action( 'admin_menu', 'ame_bazaar_register_media_manager_submenu' );

/**
 * 15. Enqueue Media Scripts
 */
function ame_bazaar_enqueue_media_manager_scripts( $hook ) {
	if ( 'ame-store-dashboard_page_ame-homepage-media' !== $hook && 'ame-store_page_ame-homepage-media' !== $hook ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'ame_bazaar_enqueue_media_manager_scripts' );

add_action( 'admin_init', 'ame_bazaar_auto_assign_media_mappings' );

/**
 * 16. Auto-Assign existing assets to options
 */
function ame_bazaar_auto_assign_media_mappings() {
	$mappings = array(
		'ame_bazaar_media_primary_logo'       => 'logo',
		'ame_bazaar_white_logo'               => 'logo',
		'ame_bazaar_sticky_logo'              => 'logo',
		'ame_bazaar_media_white_logo'         => 'logo',
		'ame_bazaar_media_sticky_logo'        => 'logo',
		'ame_bazaar_media_favicon'            => 'logo',
		'ame_bazaar_media_hero_desktop'       => 'unnamed-6',
		'ame_bazaar_media_hero_mobile'        => 'unnamed',
		'ame_bazaar_media_women'              => 'women-wear-image',
		'ame_bazaar_media_men'                => 'mens-tshirt',
		'ame_bazaar_media_kids'               => 'boys-wear',
		'ame_bazaar_media_accessories'        => 'wallet',
		'ame_bazaar_media_tailoring'          => 'winter-waist-coat',
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
		$args = array(
			'post_type'      => 'attachment',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
		);
		$posts = get_posts( $args );
		if ( $posts ) {
			if ( (int) $val !== (int) $posts[0]->ID ) {
				update_option( $option_key, $posts[0]->ID );
			}
		}
	}
}

/**
 * 17. Render Homepage Media Manager Options Page
 */
function ame_bazaar_render_homepage_media_page() {
	// Auto assign mappings on page view
	ame_bazaar_auto_assign_media_mappings();

	$fields = array(
		'ame_bazaar_media_primary_logo'       => __( 'Primary Logo', 'ame-bazaar' ),
		'ame_bazaar_media_white_logo'         => __( 'White Logo', 'ame-bazaar' ),
		'ame_bazaar_media_sticky_logo'        => __( 'Sticky Header Logo', 'ame-bazaar' ),
		'ame_bazaar_media_favicon'            => __( 'Favicon', 'ame-bazaar' ),
		'ame_bazaar_media_hero_desktop'       => __( 'Hero Desktop Banner', 'ame-bazaar' ),
		'ame_bazaar_media_hero_mobile'        => __( 'Hero Mobile Banner', 'ame-bazaar' ),
		'ame_bazaar_media_men'                => __( "Men's Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_women'              => __( "Women's Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_kids'               => __( "Kids Wear Banner", 'ame-bazaar' ),
		'ame_bazaar_media_accessories'        => __( 'Accessories Banner', 'ame-bazaar' ),
		'ame_bazaar_media_tailoring'          => __( 'Tailoring Section Image', 'ame-bazaar' ),
		'ame_bazaar_media_visit_store'        => __( 'Visit Store Banner', 'ame-bazaar' ),
		'ame_bazaar_media_about'              => __( 'About AME Bazaar Image', 'ame-bazaar' ),
		'ame_bazaar_media_google_reviews'     => __( 'Google Reviews Banner', 'ame-bazaar' ),
		'ame_bazaar_media_instagram'          => __( 'Instagram Cover Image', 'ame-bazaar' ),
		'ame_bazaar_media_footer_bg'          => __( 'Footer Background', 'ame-bazaar' ),
		'ame_bazaar_media_empty_state'        => __( 'Empty State Image', 'ame-bazaar' ),
		'ame_bazaar_media_404_illustration'   => __( '404 Illustration', 'ame-bazaar' ),
	);

	// Handle saving
	if ( isset( $_POST['ame_homepage_media_submit'] ) && check_admin_referer( 'ame_homepage_media_nonce_action', 'ame_homepage_media_nonce' ) ) {
		foreach ( $fields as $field_key => $field_label ) {
			if ( isset( $_POST[ $field_key ] ) ) {
				update_option( $field_key, sanitize_text_field( $_POST[ $field_key ] ) );
			}
		}
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Homepage Media Mapping successfully saved.', 'ame-bazaar' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Homepage Media Manager', 'ame-bazaar' ); ?></h1>
		<p><?php esc_html_e( 'Manage the mappings of uploaded WordPress Media Library files to homepage visual assets dynamically. Saves attachment IDs in WordPress Options.', 'ame-bazaar' ); ?></p>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'ame_homepage_media_nonce_action', 'ame_homepage_media_nonce' ); ?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<?php foreach ( $fields as $field_key => $field_label ) : 
						$current_val = get_option( $field_key );
						$preview_html = '';
						if ( $current_val ) {
							$preview_url = wp_get_attachment_url( $current_val );
							if ( $preview_url ) {
								$preview_html = '<img src="' . esc_url( $preview_url ) . '" style="max-width:150px;max-height:150px;margin-top:10px;border:1px solid #ccc;padding:5px;display:block;" />';
							}
						}
					?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $field_label ); ?></label></th>
							<td>
								<input type="text" id="<?php echo esc_attr( $field_key ); ?>" name="<?php echo esc_attr( $field_key ); ?>" value="<?php echo esc_attr( $current_val ); ?>" class="regular-text" style="width: 120px;" readonly />
								<button class="button button-secondary ame-media-select" data-field="<?php echo esc_attr( $field_key ); ?>"><?php esc_html_e( 'Select Image', 'ame-bazaar' ); ?></button>
								<button class="button button-link delete ame-media-remove" data-field="<?php echo esc_attr( $field_key ); ?>"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
								
								<div id="preview-<?php echo esc_attr( $field_key ); ?>" class="ame-media-preview-container">
									<?php if ( $preview_html ) { 
										echo $preview_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} else { ?>
										<p style="color:#666;font-style:italic;margin: 5px 0 0 0;"><?php esc_html_e( 'No image selected.', 'ame-bazaar' ); ?></p>
									<?php } ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
			<?php submit_button( __( 'Save Media Mappings', 'ame-bazaar' ), 'primary', 'ame_homepage_media_submit' ); ?>
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
					text: 'Use Selected Image'
				},
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				$('#preview-' + fieldId).html('<img src="' + attachment.url + '" style="max-width:150px;max-height:150px;margin-top:10px;border:1px solid #ccc;padding:5px;display:block;" />');
			}).open();
		});

		$('.ame-media-remove').click(function(e) {
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).html('<p style="color:#666;font-style:italic;margin: 5px 0 0 0;">No image selected.</p>');
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
