<?php
/**
 * The template for displaying product content within loops
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$post_id = $product->get_id();
$brand   = get_post_meta( $post_id, '_ame_brand', true );
$trending = get_post_meta( $post_id, '_ame_trending', true );
$alteration = get_post_meta( $post_id, '_ame_alteration_available', true );
$local_avail = get_post_meta( $post_id, '_ame_local_availability', true );

// Determine if it is a New Arrival (published in last 30 days)
$created_date = $product->get_date_created();
$is_new = false;
if ( $created_date ) {
	$days_diff = ( time() - $created_date->getTimestamp() ) / DAY_IN_SECONDS;
	if ( $days_diff <= 30 ) {
		$is_new = true;
	}
}

// Hover image logic
$gallery_images = $product->get_gallery_image_ids();
$hover_img_url = '';
if ( ! empty( $gallery_images ) ) {
	$hover_img_url = wp_get_attachment_image_url( $gallery_images[0], 'woocommerce_thumbnail' );
}

// Prices and Discount calculation
$regular_price = floatval( $product->get_regular_price() );
$sale_price = floatval( $product->get_sale_price() );
$mrp = floatval( get_post_meta( $post_id, '_ame_mrp', true ) );

// Fallback regular price to MRP if empty
if ( ! $regular_price && $mrp ) {
	$regular_price = $mrp;
}

$discount_pct = 0;
if ( $product->is_on_sale() && $regular_price > 0 && $sale_price > 0 ) {
	$discount_pct = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
}

// WhatsApp CTA Link
$whatsapp = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
$whatsapp_tel = preg_replace( '/[^0-9]/', '', $whatsapp );
$wa_text = sprintf( 'Hi, I am interested in buying "%s" priced at %s. Is it in stock?', get_the_title(), strip_tags( $product->get_price_html() ) );
$wa_url = 'https://wa.me/' . $whatsapp_tel . '?text=' . rawurlencode( $wa_text );
?>
<li <?php wc_product_class( 'product ame-product-card ame-depth-1 ame-premium-card', $product ); ?>>
	<div class="ame-product-card-inner">
		<!-- Image & Badges container -->
		<div class="ame-product-card-image-wrap" style="position: relative; overflow: hidden; border-radius: 12px; background: #f8fafc;">
			<!-- Badges Overlay -->
			<div class="ame-product-card-badges" style="position: absolute; top: 0.5rem; left: 0.5rem; display: flex; flex-direction: column; gap: 0.25rem; z-index: 10;">
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="ame-badge-sale" style="background: #ef4444; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 4px; text-transform: uppercase;"><?php esc_html_e( 'Sale', 'ame-bazaar' ); ?></span>
				<?php endif; ?>
				<?php if ( $is_new ) : ?>
					<span class="ame-badge-new" style="background: #2563eb; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 4px; text-transform: uppercase;"><?php esc_html_e( 'New', 'ame-bazaar' ); ?></span>
				<?php endif; ?>
				<?php if ( 'yes' === $trending || '1' === $trending ) : ?>
					<span class="ame-badge-trending" style="background: #ca8a04; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 4px; text-transform: uppercase;"><?php esc_html_e( 'Trending', 'ame-bazaar' ); ?></span>
				<?php endif; ?>
			</div>

			<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ame-product-card-image-link" style="display: block; position: relative; aspect-ratio: 3/4;">
				<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'ame-product-primary-image', 'style' => 'width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s ease;' ) ); ?>
				<?php if ( $hover_img_url ) : ?>
					<img src="<?php echo esc_url( $hover_img_url ); ?>" class="ame-product-hover-image" alt="<?php echo esc_attr( $product->get_name() ); ?> - hover" loading="lazy" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.3s ease;" />
				<?php endif; ?>
			</a>

			<!-- Quick Actions Overlay -->
			<div class="ame-product-card-quick-actions" style="position: absolute; bottom: 0.5rem; right: 0.5rem; display: flex; gap: 0.35rem; z-index: 10;">
				<button class="ame-quickview-btn" data-product-id="<?php echo esc_attr( $post_id ); ?>" aria-label="Quick View" style="background: rgba(255,255,255,0.9); border: none; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--ame-color-navy); box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				</button>
				<button class="ame-wishlist-action-btn" aria-label="Add to Wishlist" style="background: rgba(255,255,255,0.9); border: none; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--ame-color-navy); box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
				</button>
			</div>
		</div>

		<!-- Details container -->
		<div class="ame-product-card-details" style="padding: 1rem 0.5rem;">
			<!-- Subtle Trust Badges Row -->
			<div class="ame-product-card-trust-badges" style="display: flex; flex-wrap: wrap; gap: 0.25rem; margin-bottom: 0.4rem;">
				<?php if ( 'yes' === $alteration || '1' === $alteration ) : ?>
					<span class="ame-trust-badge" title="Alteration/Tailoring available at Mubarakpur Road outlet" style="font-size: 0.65rem; background: #f0fdf4; color: #16a34a; padding: 0.1rem 0.35rem; border-radius: 3px; font-weight: 700;">✂ Alterations</span>
				<?php endif; ?>
				<?php if ( 'in-store-only' === $local_avail ) : ?>
					<span class="ame-trust-badge color-store" title="Trial rooms available at Mubarakpur Road outlet" style="font-size: 0.65rem; background: #fffbeb; color: #d97706; padding: 0.1rem 0.35rem; border-radius: 3px; font-weight: 700;">📍 Kirari Store</span>
				<?php elseif ( 'online-and-instore' === $local_avail ) : ?>
					<span class="ame-trust-badge color-store" title="Available for pickup and online ordering" style="font-size: 0.65rem; background: #f0fdf4; color: #16a34a; padding: 0.1rem 0.35rem; border-radius: 3px; font-weight: 700;">📍 Store & Online</span>
				<?php endif; ?>
				<?php if ( $product->is_featured() ) : ?>
					<span class="ame-trust-badge color-featured" style="font-size: 0.65rem; background: #fef2f2; color: #ef4444; padding: 0.1rem 0.35rem; border-radius: 3px; font-weight: 700;">⭐ Fav</span>
				<?php endif; ?>
			</div>

			<!-- Brand -->
			<?php if ( $brand ) : ?>
				<div class="ame-product-card-brand" style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;"><?php echo esc_html( $brand ); ?></div>
			<?php endif; ?>

			<!-- Title -->
			<h2 class="ame-product-card-title" style="font-size: 0.95rem; font-weight: 700; line-height: 1.3; margin: 0 0 0.4rem 0;">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="color: var(--ame-color-navy); text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo esc_html( $product->get_name() ); ?></a>
			</h2>

			<!-- Rating Placeholder -->
			<div class="ame-product-card-rating" style="margin-bottom: 0.5rem;">
				<?php if ( $product->get_average_rating() > 0 ) : ?>
					<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
				<?php else : ?>
					<div class="star-rating" role="img" aria-label="Rated 5.00 out of 5" style="font-size: 0.75rem;"><span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span></div>
				<?php endif; ?>
			</div>

			<!-- Price & Discount -->
			<div class="ame-product-card-price-row" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
				<?php if ( $discount_pct > 0 ) : ?>
					<span class="price" style="font-size: 0.95rem; font-weight: 800; color: var(--ame-color-navy);">
						<del aria-hidden="true" style="opacity: 0.5; margin-right: 0.25rem; font-size: 0.8rem; font-weight: 400;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₹</span><?php echo esc_html( $regular_price ); ?></bdi></span></del>
						<ins style="text-decoration: none; color: #ef4444;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₹</span><?php echo esc_html( $sale_price ); ?></bdi></span></ins>
					</span>
					<span class="ame-discount-pct-badge" style="font-size: 0.7rem; font-weight: 800; background: #fee2e2; color: #ef4444; padding: 0.1rem 0.35rem; border-radius: 4px;"><?php echo $discount_pct; ?>% OFF</span>
				<?php else : ?>
					<span class="price" style="font-size: 0.95rem; font-weight: 800; color: var(--ame-color-navy);"><?php echo $product->get_price_html(); ?></span>
				<?php endif; ?>
			</div>

			<!-- Action buttons -->
			<div class="ame-product-card-actions" style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 0.4rem;">
				<a href="<?php echo esc_url( $wa_url ); ?>" class="ame-card-wa-btn" target="_blank" rel="noopener" aria-label="Order on WhatsApp" style="background: #25d366; color: #fff; padding: 0.5rem 0.75rem; border-radius: 30px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem; text-decoration: none;">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
					<span>WhatsApp</span>
				</a>
				<a href="?add-to-cart=<?php echo esc_attr( $post_id ); ?>" class="ame-card-add-btn ajax_add_to_cart" data-product_id="<?php echo esc_attr( $post_id ); ?>" aria-label="Add to Cart" style="background: var(--ame-color-navy); color: #fff; padding: 0.5rem 0.75rem; border-radius: 30px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; text-transform: uppercase; letter-spacing: 0.05em;">
					<span>Add</span>
				</a>
			</div>
		</div>
	</div>
</li>
