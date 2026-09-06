<?php
/**
 * Featured Collections section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_featured_title', 'Featured Collections' );
$section_subtitle = get_theme_mod( 'ame_bazaar_featured_subtitle', 'Handpicked fashion arrivals selected for Delhi families' );

$products = get_transient( 'ame_bazaar_featured_products' );
if ( false === $products ) {
	$products = array();
	if ( class_exists( 'WooCommerce' ) ) {
		// Query recent products
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 4,
			'status'         => 'publish',
		);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				global $product;
				$products[] = array(
					'id'         => get_the_ID(),
					'title'      => get_the_title(),
					'price_html' => $product->get_price_html(),
					'img_url'    => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
					'link'       => get_permalink(),
					'add_to_cart_url' => esc_url( $product->add_to_cart_url() ),
				);
			}
			wp_reset_postdata();
		}
	}
	set_transient( 'ame_bazaar_featured_products', $products, HOUR_IN_SECONDS );
}

// Return early and hide section if no products exist
if ( empty( $products ) ) {
	return;
}
?>

<section class="ame-featured-section" aria-labelledby="ame-featured-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-featured-header">
			<div class="ame-featured-header-left">
				<h2 id="ame-featured-title" class="ame-featured-section-title"><?php echo esc_html( $section_title ); ?></h2>
				<?php if ( $section_subtitle ) : ?>
					<p class="ame-featured-section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="ame-featured-header-right">
				<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="ame-bazaar-btn ame-bazaar-btn--primary">
					<span><?php esc_html_e( 'View All Products', 'ame-bazaar' ); ?></span>
				</a>
			</div>
		</div>

		<!-- Products Grid -->
		<div class="ame-products-grid">
			<?php foreach ( $products as $prod ) : ?>
				<article class="ame-product-card">
					<div class="ame-product-visual-wrap">
						<a href="<?php echo esc_url( $prod['link'] ); ?>" class="ame-product-img-link" tabindex="-1" aria-hidden="true">
							<?php if ( $prod['img_url'] ) : ?>
								<img src="<?php echo esc_url( $prod['img_url'] ); ?>" 
									 alt="<?php echo esc_attr( $prod['title'] ); ?>" 
									 class="ame-product-img" 
									 loading="lazy">
							<?php else : ?>
								<div class="ame-product-img-placeholder">
									<div class="ame-placeholder-logo-overlay">
										<span class="ame-placeholder-logo-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
									</div>
									<span class="ame-placeholder-tag"><?php esc_html_e( 'Coming Soon', 'ame-bazaar' ); ?></span>
								</div>
							<?php endif; ?>
						</a>
						
						<!-- Hover Quick Actions -->
						<div class="ame-product-hover-actions">
							<a href="<?php echo esc_url( $prod['add_to_cart_url'] ); ?>" class="ame-product-action-btn" aria-label="<?php esc_attr_e( 'Add to Cart', 'ame-bazaar' ); ?>">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
								</svg>
							</a>
							<a href="#" class="ame-product-action-btn ame-wishlist-action-btn" aria-label="<?php esc_attr_e( 'Add to Wishlist', 'ame-bazaar' ); ?>">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
								</svg>
							</a>
						</div>
					</div>

					<div class="ame-product-info">
						<h3 class="ame-product-title">
							<a href="<?php echo esc_url( $prod['link'] ); ?>">
								<?php echo esc_html( $prod['title'] ); ?>
							</a>
						</h3>
						<div class="ame-product-price">
							<?php echo $prod['price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
