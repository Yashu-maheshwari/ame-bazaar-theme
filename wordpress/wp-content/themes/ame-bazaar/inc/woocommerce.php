<?php
/**
 * WooCommerce foundation and templates integration.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. WooCommerce theme support & configuration.
 */
function ame_bazaar_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'ame_bazaar_woocommerce_setup', 15 );

/**
 * 2. Remove default WooCommerce structures to replace with Design System markup.
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

/**
 * 3. Add custom product card structure based on Design System.
 */
function ame_bazaar_loop_product_card_start() {
	echo '<article class="ame-product-card">';
}
add_action( 'woocommerce_before_shop_loop_item', 'ame_bazaar_loop_product_card_start', 5 );

function ame_bazaar_loop_product_card_end() {
	echo '</article>';
}
add_action( 'woocommerce_after_shop_loop_item', 'ame_bazaar_loop_product_card_end', 30 );

function ame_bazaar_loop_product_thumbnail_with_actions() {
	global $product;
	$img_url   = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
	$is_on_sale = $product->is_on_sale();
	$is_featured = $product->is_featured();
	$stock_status = $product->get_stock_status();

	// Grab second image from gallery for hover image swap
	$gallery_image_ids = $product->get_gallery_image_ids();
	$hover_img_url = '';
	if ( ! empty( $gallery_image_ids ) ) {
		$hover_img_url = wp_get_attachment_image_url( $gallery_image_ids[0], 'medium' );
	}

	echo '<div class="ame-product-visual-wrap">';
	echo '<a href="' . esc_url( get_permalink() ) . '" class="ame-product-img-link" tabindex="-1" aria-hidden="true">';
	
	if ( $img_url ) {
		echo '<img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_the_title() ) . '" class="ame-product-img" loading="lazy">';
		if ( $hover_img_url ) {
			echo '<img src="' . esc_url( $hover_img_url ) . '" alt="" class="ame-product-img-hover" loading="lazy">';
		}
	} else {
		// Elegant Design System Placeholder
		?>
		<div class="ame-product-img-placeholder">
			<div class="ame-placeholder-logo-overlay">
				<span class="ame-placeholder-logo-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
			</div>
			<span class="ame-placeholder-tag"><?php esc_html_e( 'Coming Soon', 'ame-bazaar' ); ?></span>
		</div>
		<?php
	}
	
	echo '</a>';

	// Badges
	echo '<div class="ame-product-badges-overlay">';
	if ( $is_on_sale ) {
		// Calculate percentage discount
		$reg_price = (float) $product->get_regular_price();
		$sale_price = (float) $product->get_sale_price();
		if ( $reg_price > 0 ) {
			$discount = round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 );
			echo '<span class="ame-badge-sale">' . sprintf( __( 'Sale -%d%%', 'ame-bazaar' ), $discount ) . '</span>';
		} else {
			echo '<span class="ame-badge-sale">' . esc_html__( 'Sale', 'ame-bazaar' ) . '</span>';
		}
	}
	if ( $is_featured ) {
		echo '<span class="ame-badge-bestseller">' . esc_html__( 'Bestseller', 'ame-bazaar' ) . '</span>';
	}
	if ( 'outofstock' === $stock_status ) {
		echo '<span class="ame-badge-outofstock">' . esc_html__( 'Out of Stock', 'ame-bazaar' ) . '</span>';
	} else {
		// Kirari local and customization badges
		echo '<span class="ame-badge-new">' . esc_html__( 'Kirari Store Pick-up', 'ame-bazaar' ) . '</span>';
		echo '<span class="ame-badge-limited" style="background:#e0f2fe; color:#0369a1;">' . esc_html__( 'Tailoring Available', 'ame-bazaar' ) . '</span>';
	}
	echo '</div>';

	// Hover Actions
	?>
	<div class="ame-product-hover-actions">
		<a href="?add-to-cart=<?php echo esc_attr( get_the_ID() ); ?>" class="ame-product-action-btn button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( get_the_ID() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php esc_attr_e( 'Add to Cart', 'ame-bazaar' ); ?>" rel="nofollow">
			<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
		</a>
		<a href="#" class="ame-product-action-btn ame-wishlist-action-btn" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>" aria-label="<?php esc_attr_e( 'Add to Wishlist (Future Integration)', 'ame-bazaar' ); ?>">
			<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
			</svg>
		</a>
		<a href="#" class="ame-product-action-btn ame-compare-action-btn" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>" aria-label="<?php esc_attr_e( 'Add to Compare (Future Integration)', 'ame-bazaar' ); ?>">
			<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
			</svg>
		</a>
	</div>
	<?php
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'ame_bazaar_loop_product_thumbnail_with_actions', 10 );

function ame_bazaar_loop_product_title_custom() {
	echo '<h3 class="ame-product-title">';
	echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
	echo '</h3>';
}
add_action( 'woocommerce_shop_loop_item_title', 'ame_bazaar_loop_product_title_custom', 10 );

function ame_bazaar_loop_product_price_and_rating() {
	global $product;
	echo '<div class="ame-product-meta-row">';
	
	// Price
	echo '<div class="ame-product-price">' . $product->get_price_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// Rating (if any stars exist)
	$rating = $product->get_average_rating();
	if ( $rating > 0 ) {
		echo '<div class="ame-product-rating" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'ame-bazaar' ), $rating ) ) . '">';
		echo '<span class="ame-rating-number">' . esc_html( $rating ) . '</span>';
		echo '<svg class="ame-star-icon" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
		echo '</div>';
	}
	
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'ame_bazaar_loop_product_price_and_rating', 10 );

/**
 * 4. Cookie-based Recently Viewed products tracker & drawer engine.
 */
function ame_bazaar_track_recently_viewed_product() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['ame_recently_viewed'] ) ) {
		$viewed = array();
	} else {
		$viewed = wp_parse_id_list( explode( '|', wp_unslash( $_COOKIE['ame_recently_viewed'] ) ) );
	}

	// Remove current product if already in array
	$keys = array_keys( $viewed, $post->ID );
	if ( ! empty( $keys ) ) {
		foreach ( $keys as $key ) {
			unset( $viewed[ $key ] );
		}
	}

	// Add current product to the beginning
	array_unshift( $viewed, $post->ID );

	// Keep only latest 5 products
	$viewed = array_slice( $viewed, 0, 5 );

	// Set cookie for 30 days
	setcookie( 'ame_recently_viewed', implode( '|', $viewed ), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
}
add_action( 'template_redirect', 'ame_bazaar_track_recently_viewed_product' );

function ame_bazaar_get_recently_viewed_products_html() {
	if ( empty( $_COOKIE['ame_recently_viewed'] ) ) {
		return '';
	}

	$viewed_ids = wp_parse_id_list( explode( '|', wp_unslash( $_COOKIE['ame_recently_viewed'] ) ) );

	if ( empty( $viewed_ids ) ) {
		return '';
	}

	// Exclude current product if on single product page
	if ( is_singular( 'product' ) ) {
		$viewed_ids = array_diff( $viewed_ids, array( get_the_ID() ) );
	}

	if ( empty( $viewed_ids ) ) {
		return '';
	}

	$args = array(
		'post_type'      => 'product',
		'post__in'       => $viewed_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => 4,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	ob_start();
	?>
	<section class="ame-recently-viewed-products">
		<div class="ame-bazaar-container">
			<h2 class="ame-recently-viewed-title"><?php esc_html_e( 'Recently Viewed Products', 'ame-bazaar' ); ?></h2>
			<div class="ame-products-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					wc_get_template_part( 'content', 'product' );
				}
				?>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}

function ame_bazaar_render_recently_viewed_single() {
	echo ame_bazaar_get_recently_viewed_products_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'woocommerce_after_single_product_summary', 'ame_bazaar_render_recently_viewed_single', 25 );

/**
 * 5. Slide Drawer Mini Cart AJAX and fragments updates.
 */
function ame_bazaar_render_mini_cart_drawer() {
	?>
	<div class="ame-drawer ame-drawer-right" id="ame-mini-cart-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="ame-mini-cart-title">
		<div class="ame-drawer-inner">
			<header class="ame-drawer-header">
				<h3 id="ame-mini-cart-title" class="ame-drawer-title"><?php esc_html_e( 'Shopping Cart', 'ame-bazaar' ); ?></h3>
				<button class="ame-drawer-close-btn" id="ame-cart-close-btn" aria-label="Close Cart">&times;</button>
			</header>
			<div class="ame-drawer-body">
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
				</div>
			</div>
		</div>
		<div class="ame-drawer-overlay" id="ame-cart-overlay-bg"></div>
	</div>
	<?php
}
add_action( 'wp_footer', 'ame_bazaar_render_mini_cart_drawer' );

function ame_bazaar_cart_fragments( $fragments ) {
	ob_start();
	?>
	<span class="ame-cart-count">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
	$fragments['.ame-cart-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ame_bazaar_cart_fragments' );

/**
 * 6. AJAX Product & Category Suggestions Search.
 */
function ame_bazaar_ajax_search_products() {
	$search_query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';

	$suggestions = array();

	if ( ! empty( $search_query ) ) {
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 5,
			's'              => $search_query,
			'status'         => 'publish',
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				global $product;
				$suggestions[] = array(
					'title' => get_the_title(),
					'price' => strip_tags( $product->get_price_html() ),
					'link'  => get_permalink(),
					'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
				);
			}
			wp_reset_postdata();
		}
	}

	wp_send_json_success( $suggestions );
}
add_action( 'wp_ajax_ame_bazaar_search', 'ame_bazaar_ajax_search_products' );
add_action( 'wp_ajax_nopriv_ame_bazaar_search', 'ame_bazaar_ajax_search_products' );

/**
 * 7. Enhance Product Structured Schema data for AI and Local search engines.
 */
function ame_bazaar_enhance_product_schema( $markup, $product ) {
	// Add custom Delhi store attributes
	$markup['offers']['seller'] = array(
		'@type' => 'ClothingStore',
		'name'  => 'AME Bazaar',
		'address' => array(
			'@type' => 'PostalAddress',
			'streetAddress' => 'Mubarakpur Road, Kirari',
			'addressLocality' => 'Delhi',
			'postalCode' => '110086',
			'addressCountry' => 'IN',
		),
		'telephone' => '+91 99999 99999',
	);
	
	// Advanced AI attributes
	$markup['brand'] = array(
		'@type' => 'Brand',
		'name'  => 'AME Bazaar',
	);
	$markup['material'] = 'Pure Mulmul Cotton / Silk';
	$markup['pattern'] = 'Embroidered Handloom / Ethnic Solid';
	$markup['audience'] = array(
		'@type' => 'PeopleAudience',
		'suggestedGender' => 'Unisex',
		'suggestedMinAge' => 18,
	);
	
	// Return Policy & Shipping Details Schema
	$markup['hasMerchantReturnPolicy'] = array(
		'@type' => 'MerchantReturnPolicy',
		'applicableCountry' => 'IN',
		'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnPeriod',
		'returnFees' => 'https://schema.org/FreeReturn',
		'merchantReturnDays' => 7,
		'returnMethod' => 'https://schema.org/ReturnInStore',
	);

	$markup['shippingDetails'][] = array(
		'@type' => 'ProductShippingDetails',
		'shippingDestination' => array(
			'@type' => 'DefinedRegion',
			'addressCountry' => 'IN',
		),
		'shippingRate' => array(
			'@type' => 'MonetaryAmount',
			'value' => 0,
			'currency' => 'INR',
		),
	);
	
	$markup['additionalProperty'][] = array(
		'@type' => 'PropertyValue',
		'name'  => 'Tailoring Option',
		'value' => 'On-site tailoring and fitting alteration services available within 30 minutes',
	);

	$markup['additionalProperty'][] = array(
		'@type' => 'PropertyValue',
		'name'  => 'Local Store trial',
		'value' => 'Trial available at Mubarakpur Road Kirari store in Delhi',
	);
	
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'ame_bazaar_enhance_product_schema', 10, 2 );

/**
 * 8. Local Retail Features summary module.
 */
function ame_bazaar_render_local_retail_features() {
	global $product;
	?>
	<div class="ame-local-retail-card" style="margin-top: 1.5rem; padding: 1.5rem; background: var(--ame-color-cream); border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md);">
		<h4 class="ame-local-retail-title" style="display:flex; align-items:center; gap:0.5rem; margin:0 0 1rem 0; font-size:1rem; font-weight:800; color:var(--ame-color-navy);">
			<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:18px; height:18px; color:var(--ame-color-gold-dark);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
			<span><?php esc_html_e( 'In-Store Delhi Services', 'ame-bazaar' ); ?></span>
		</h4>
		<ul class="ame-local-services-list" style="list-style:none; padding:0; margin:0 0 1.5rem 0; display:flex; flex-direction:column; gap:0.6rem; font-size:0.85rem; color:var(--ame-color-slate);">
			<li style="display:flex; gap:0.4rem;"><strong>Kirari Store Status:</strong> In-stock & available for immediate trial.</li>
			<li style="display:flex; gap:0.4rem;"><strong>Alteration Timeline:</strong> Fittings and hem adjustments completed in 30 minutes.</li>
			<li style="display:flex; gap:0.4rem;"><strong>Trial room:</strong> Try before purchase at Mubarakpur Road outlet.</li>
		</ul>
		<div class="ame-local-retail-actions" style="display:flex; gap:0.8rem; flex-wrap:wrap;">
			<a href="tel:+919999999999" class="ame-btn-outline" style="padding:0.6rem 1rem; font-size:0.75rem; text-decoration:none;">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2v3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
				<span>Call Store</span>
			</a>
			<a href="https://wa.me/919999999999?text=Hi%20I%20am%20interested%20in%20<?php echo rawurlencode( get_the_title() ); ?>" class="ame-btn-secondary" style="padding:0.6rem 1rem; font-size:0.75rem; text-decoration:none;" target="_blank" rel="noopener">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
				<span>WhatsApp Enquiry</span>
			</a>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'ame_bazaar_render_local_retail_features', 35 );

/**
 * 9. Smart Bought Together Recommendations Package.
 */
function ame_bazaar_frequently_bought_together() {
	global $product;
	if ( ! is_product() ) return;
	
	// Query 2 related products as suggestions
	$related_ids = wc_get_related_products( get_the_ID(), 2 );
	if ( empty( $related_ids ) ) return;
	
	$query = new WP_Query( array(
		'post_type' => 'product',
		'post__in' => $related_ids,
	) );
	
	if ( ! $query->have_posts() ) return;
	
	?>
	<section class="ame-frequently-bought-together">
		<h3 class="ame-recommendations-title"><?php esc_html_e( 'Frequently Bought Together / Complete the Look', 'ame-bazaar' ); ?></h3>
		<div class="ame-fbt-flex-container">
			
			<div class="ame-fbt-products-line">
				<div class="ame-fbt-item">
					<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'ame-fbt-img' ) ); ?>
					<div class="ame-fbt-info">
						<span class="ame-fbt-name"><?php the_title(); ?></span>
						<span class="ame-fbt-price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
				</div>
				
				<?php while ( $query->have_posts() ) : $query->the_post(); global $product; ?>
					<div class="ame-fbt-plus-icon">+</div>
					<div class="ame-fbt-item">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'ame-fbt-img' ) ); ?>
						<div class="ame-fbt-info">
							<span class="ame-fbt-name"><?php the_title(); ?></span>
							<span class="ame-fbt-price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			
			<div class="ame-fbt-summary-box">
				<p class="ame-fbt-total-label">Custom coordinator set suggestions</p>
				<a href="?add-to-cart=<?php echo esc_attr( get_the_ID() ); ?>" class="ame-btn-primary ame-fbt-add-btn"><?php esc_html_e( 'Add Package to Cart', 'ame-bazaar' ); ?></a>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'ame_bazaar_frequently_bought_together', 12 );

/**
 * 10. Mobile Sticky Add to Cart layout injector.
 */
function ame_bazaar_sticky_add_to_cart_mobile() {
	if ( ! is_product() ) {
		return;
	}
	
	global $product;
	?>
	<div class="ame-sticky-add-to-cart" id="ame-mobile-sticky-cart" aria-hidden="true" style="display: none;">
		<div class="ame-sticky-add-to-cart-info">
			<span class="ame-sticky-add-to-cart-title"><?php the_title(); ?></span>
			<span class="ame-sticky-add-to-cart-price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</div>
		<a href="?add-to-cart=<?php echo esc_attr( get_the_ID() ); ?>" class="ame-btn-primary ame-btn-sticky-add ajax_add_to_cart" data-product_id="<?php echo esc_attr( get_the_ID() ); ?>" aria-label="<?php esc_attr_e( 'Add to Cart', 'ame-bazaar' ); ?>">
			<?php esc_html_e( 'Add', 'ame-bazaar' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'ame_bazaar_sticky_add_to_cart_mobile' );

/**
 * Update Cart Item Count Badge dynamically via AJAX.
 */
function ame_bazaar_woocommerce_add_to_cart_fragments( $fragments ) {
	$cart_count = WC()->cart->get_cart_contents_count();
	$display_style = $cart_count > 0 ? 'flex' : 'none';
	ob_start();
	?>
	<span class="ame-cart-count" style="display: <?php echo $display_style; ?>;"><?php echo esc_html( $cart_count ); ?></span>
	<?php
	$fragments['span.ame-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ame_bazaar_woocommerce_add_to_cart_fragments' );

// End of WooCommerce Integration. Rerun trigger 4.



