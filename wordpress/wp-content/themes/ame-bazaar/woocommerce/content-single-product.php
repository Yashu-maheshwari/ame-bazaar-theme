<?php
/**
 * Custom Single Product layout template override.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ame-single-product-container', $product ); ?>>
	<div class="ame-bazaar-container">
		
		<!-- Custom Breadcrumbs -->
		<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

		<div class="ame-single-product-layout-grid">
			
			<!-- Left Column: Gallery Slider/Zoom -->
			<div class="ame-product-gallery-column">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>

			<!-- Right Column: Product Info Summary -->
			<div class="summary entry-summary ame-product-summary-column">
				
				<?php
				// Badges row
				$is_on_sale = $product->is_on_sale();
				$is_featured = $product->is_featured();
				$stock_status = $product->get_stock_status();
				
				echo '<div class="ame-single-product-badges" style="display:flex; gap:0.5rem; margin-bottom:0.75rem;">';
				if ( $is_on_sale ) {
					echo '<span class="ame-badge-sale">' . esc_html__( 'Sale', 'ame-bazaar' ) . '</span>';
				}
				if ( $is_featured ) {
					echo '<span class="ame-badge-bestseller">' . esc_html__( 'Best Seller', 'ame-bazaar' ) . '</span>';
				}
				if ( 'outofstock' === $stock_status ) {
					echo '<span class="ame-badge-outofstock">' . esc_html__( 'Out of Stock', 'ame-bazaar' ) . '</span>';
				} else {
					echo '<span class="ame-badge-new">' . esc_html__( 'In Stock', 'ame-bazaar' ) . '</span>';
				}
				echo '</div>';
				
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 15
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>

				<!-- Reusable Specifications & Fabric Info Accordion -->
				<div class="ame-accordion ame-product-meta-accordion" style="margin-top: 2rem;">
					<div class="ame-accordion-item">
						<button class="ame-accordion-header" id="prod-acc-fabric" aria-expanded="false" aria-controls="prod-panel-fabric">
							<span>Fabric & Material Details</span>
							<svg class="ame-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
						</button>
						<div class="ame-accordion-panel" id="prod-panel-fabric" aria-labelledby="prod-acc-fabric" role="region" hidden>
							<div class="ame-accordion-content">
								<p>This premium garment is crafted from 100% pure mulmul cotton / handloomed raw silk threads. Extremely breathable, skin-friendly, and lightweight—perfectly tailored for summers in Delhi.</p>
							</div>
						</div>
					</div>

					<div class="ame-accordion-item">
						<button class="ame-accordion-header" id="prod-acc-size" aria-expanded="false" aria-controls="prod-panel-size">
							<span>Size & Fitting Guide</span>
							<svg class="ame-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
						</button>
						<div class="ame-accordion-panel" id="prod-panel-size" aria-labelledby="prod-acc-size" role="region" hidden>
							<div class="ame-accordion-content">
								<p>Our sizing follows standard Indian ethnic charts. On-site tailor measurements and adjustments are available free of charge at our Mubarakpur Road store location in Kirari, Delhi.</p>
							</div>
						</div>
					</div>

					<div class="ame-accordion-item">
						<button class="ame-accordion-header" id="prod-acc-shipping" aria-expanded="false" aria-controls="prod-panel-shipping">
							<span>Delivery & Local Pick-up</span>
							<svg class="ame-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
						</button>
						<div class="ame-accordion-panel" id="prod-panel-shipping" aria-labelledby="prod-acc-shipping" role="region" hidden>
							<div class="ame-accordion-content">
								<p>Free local delivery within 5km of Kirari, Delhi. Standard domestic shipping across India takes 3 to 5 business days. In-store pick-up orders are typically ready within 2 hours.</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Single Product Tabs, Related Products, and Recently Viewed -->
		<div class="ame-single-product-bottom-wrap" style="margin-top: 4rem; clear: both;">
			<?php
			/**
			 * Hook: woocommerce_after_single_product_summary.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 * @hooked ame_bazaar_render_recently_viewed_single - 25
			 */
			do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>

	</div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
