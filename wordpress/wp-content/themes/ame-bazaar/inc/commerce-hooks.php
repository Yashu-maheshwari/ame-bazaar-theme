<?php
/**
 * Register WooCommerce display hooks for the Product Knowledge Layer.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom product tabs in WooCommerce.
 */
function ame_bazaar_custom_product_tabs( $tabs ) {
	$post_id = get_the_ID();

	// Add dynamic tabs only if data exists
	$facts = get_post_meta( $post_id, '_ame_product_facts', true );
	$material = get_post_meta( $post_id, '_ame_product_material', true );
	$size_fit = get_post_meta( $post_id, '_ame_product_size_fit', true );

	if ( ! empty( $facts ) || ! empty( $material ) || ! empty( $size_fit ) ) {
		$tabs['ame_product_knowledge'] = array(
			'title'    => __( 'Product Knowledge & Care', 'ame-bazaar' ),
			'priority' => 15,
			'callback' => 'ame_bazaar_render_product_knowledge_tab',
		);
	}

	$tabs['ame_merchant_trust'] = array(
		'title'    => __( 'Local Pickup & Returns', 'ame-bazaar' ),
		'priority' => 25,
		'callback' => 'ame_bazaar_render_merchant_trust_tab',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ame_bazaar_custom_product_tabs' );

/**
 * Render the Product Knowledge Tab.
 */
function ame_bazaar_render_product_knowledge_tab() {
	$post_id = get_the_ID();

	$facts_str = get_post_meta( $post_id, '_ame_product_facts', true );
	$material = get_post_meta( $post_id, '_ame_product_material', true );
	$care = get_post_meta( $post_id, '_ame_product_care', true );
	$size_fit = get_post_meta( $post_id, '_ame_product_size_fit', true );
	$occasion = get_post_meta( $post_id, '_ame_product_occasion', true );
	$season = get_post_meta( $post_id, '_ame_product_season', true );
	$related_guides = get_post_meta( $post_id, '_ame_product_related_knowledge', true ) ?: array();

	echo '<div class="ame-product-knowledge-tab">';

	if ( ! empty( $facts_str ) ) {
		$facts = array_filter( array_map( 'trim', explode( "\n", $facts_str ) ) );
		if ( ! empty( $facts ) ) {
			echo '<h4>' . esc_html__( 'Product Quick Facts', 'ame-bazaar' ) . '</h4>';
			echo '<ul class="ame-product-facts-list">';
			foreach ( $facts as $fact ) {
				echo '<li>• ' . esc_html( $fact ) . '</li>';
			}
			echo '</ul>';
		}
	}

	echo '<div class="ame-specs-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">';
	if ( ! empty( $material ) ) {
		echo '<div><strong>' . esc_html__( 'Material:', 'ame-bazaar' ) . '</strong><p>' . esc_html( $material ) . '</p></div>';
	}
	if ( ! empty( $care ) ) {
		echo '<div><strong>' . esc_html__( 'Care Instructions:', 'ame-bazaar' ) . '</strong><p>' . esc_html( $care ) . '</p></div>';
	}
	if ( ! empty( $size_fit ) ) {
		echo '<div><strong>' . esc_html__( 'Size & Fit:', 'ame-bazaar' ) . '</strong><p>' . esc_html( $size_fit ) . '</p></div>';
	}
	if ( ! empty( $occasion ) || ! empty( $season ) ) {
		echo '<div><strong>' . esc_html__( 'Style Occasion:', 'ame-bazaar' ) . '</strong><p>';
		if ( $occasion ) {
			echo esc_html( $occasion );
		}
		if ( $occasion && $season ) {
			echo ' / ';
		}
		if ( $season ) {
			echo esc_html( $season );
		}
		echo '</p></div>';
	}
	echo '</div>';

	// Render related knowledge guides
	if ( ! empty( $related_guides ) ) {
		$guides = new WP_Query( array(
			'post_type'      => 'knowledge',
			'post_status'    => 'publish',
			'post__in'       => $related_guides,
			'posts_per_page' => -1,
		) );
		if ( $guides->have_posts() ) {
			echo '<div class="ame-related-guides" style="margin-top: 30px; border-top: 1px solid var(--ame-color-border); padding-top: 20px;">';
			echo '<h5>' . esc_html__( 'Related Buying & Styling Guides', 'ame-bazaar' ) . '</h5>';
			echo '<ul class="guides-list" style="list-style: none; padding: 0;">';
			while ( $guides->have_posts() ) {
				$guides->the_post();
				echo '<li><a href="' . esc_url( get_permalink() ) . '" style="color: var(--ame-color-navy); font-weight: 600;">' . esc_html( get_the_title() ) . ' →</a></li>';
			}
			echo '</ul>';
			echo '</div>';
			wp_reset_postdata();
		}
	}

	echo '</div>';
}

/**
 * Render the Merchant Trust & Fulfillment Tab.
 */
function ame_bazaar_render_merchant_trust_tab() {
	$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road, near Chappan Bhog, Kirari, Delhi' );
	$hours      = ame_bazaar_get_business_setting( 'hours', 'Monday to Sunday: 09:00 AM - 10:00 PM' );
	
	$pickup = ame_bazaar_get_business_setting( 'store_pickup_available', 'yes' ) === 'yes';
	$delivery = ame_bazaar_get_business_setting( 'home_delivery_available', 'yes' ) === 'yes';

	echo '<div class="ame-merchant-trust-tab" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">';
	
	echo '<div>';
	echo '<h4>' . esc_html__( 'Local Merchant Details', 'ame-bazaar' ) . '</h4>';
	echo '<p><strong>' . esc_html( $store_name ) . '</strong><br />' . esc_html( $address ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Support Hotline:', 'ame-bazaar' ) . '</strong> <a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></p>';
	echo '<p><strong>' . esc_html__( 'Business Hours:', 'ame-bazaar' ) . '</strong><br />' . esc_html( $hours ) . '</p>';
	echo '</div>';

	echo '<div>';
	echo '<h4>' . esc_html__( 'Fulfillment & Returns', 'ame-bazaar' ) . '</h4>';
	echo '<ul style="padding: 0; list-style: none;">';
	if ( $pickup ) {
		echo '<li>✓ ' . esc_html__( 'Free In-Store Pickup Available (Ready in 2 Hours)', 'ame-bazaar' ) . '</li>';
	}
	if ( $delivery ) {
		echo '<li>✓ ' . esc_html__( 'Local Home Delivery inside North-West Delhi region', 'ame-bazaar' ) . '</li>';
	}
	echo '<li>✓ ' . esc_html__( '7-Day Easy Exchange Policy at Kirari store location', 'ame-bazaar' ) . '</li>';
	echo '<li>✓ ' . esc_html__( 'UPI, Cash, Credit Card accepted at counter', 'ame-bazaar' ) . '</li>';
	echo '</ul>';
	echo '</div>';

	echo '</div>';
}
