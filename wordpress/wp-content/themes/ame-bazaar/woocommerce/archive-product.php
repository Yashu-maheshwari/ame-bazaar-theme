<?php
/**
 * WooCommerce product archive template overrides.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10
 * @hooked woocommerce_breadcrumb - 20 (removed if using custom breadcrumbs, but styled in main.css)
 */
do_action( 'woocommerce_before_main_content' );
?>

<div class="ame-shop-archive-wrapper">
	<div class="ame-bazaar-container">

		<!-- Custom Dynamic Breadcrumbs -->
		<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

		<!-- Category/Shop Header Banner -->
		<header class="ame-shop-category-banner">
			<?php if ( is_product_category() ) : ?>
				<h1 class="ame-shop-category-title"><?php single_term_title(); ?></h1>
				<div class="ame-shop-category-desc">
					<?php the_archive_description(); ?>
				</div>
			<?php else : ?>
				<h1 class="ame-shop-category-title"><?php woocommerce_page_title(); ?></h1>
				<p class="ame-shop-category-desc">
					<?php esc_html_e( 'Explore our premium handloomed silk sarees, customized cotton coordinates, and local Delhi tailoring collections.', 'ame-bazaar' ); ?>
				</p>
			<?php endif; ?>
		</header>

		<?php
		if ( woocommerce_product_loop() ) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			echo '<div class="ame-shop-loop-meta-header">';
			do_action( 'woocommerce_before_shop_loop' );
			echo '</div>';

			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );

		} else {

			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			?>
			<div class="ame-empty-state" style="margin-top: 3rem;">
				<div class="ame-empty-icon-wrap">
					<svg class="ame-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
						<circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line>
					</svg>
				</div>
				<h2 class="ame-empty-title"><?php esc_html_e( 'No Products Found', 'ame-bazaar' ); ?></h2>
				<p class="ame-empty-desc"><?php esc_html_e( 'We couldn\'t find any active coordinates or fabrics matching this listing collection. Please check back later.', 'ame-bazaar' ); ?></p>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="ame-btn-primary"><?php esc_html_e( 'Reset Catalog', 'ame-bazaar' ); ?></a>
			</div>
			<?php
		}
		?>

	</div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
