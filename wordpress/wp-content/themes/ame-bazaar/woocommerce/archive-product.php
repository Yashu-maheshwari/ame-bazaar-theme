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

		<!-- Category Hero -->
		<header class="ame-category-hero" style="margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #fdfdfa 0%, #f8f7f4 100%); border-left: 5px solid var(--ame-color-gold, #ca8a04); border-radius: 8px; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));" class="ame-premium-card ame-depth-2">
			<!-- Custom Dynamic Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>
			
			<div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
				<div>
					<?php if ( is_product_category() ) : ?>
						<h1 class="ame-shop-category-title" style="margin: 0; font-size: 2rem; font-weight: 800; color: var(--ame-color-navy); text-transform: uppercase; letter-spacing: 0.02em;"><?php single_term_title(); ?></h1>
						<div class="ame-shop-category-desc" style="margin-top: 0.5rem; font-size: 0.95rem; color: var(--ame-color-slate); max-width: 700px; line-height: 1.5;">
							<?php the_archive_description(); ?>
						</div>
					<?php else : ?>
						<h1 class="ame-shop-category-title" style="margin: 0; font-size: 2rem; font-weight: 800; color: var(--ame-color-navy); text-transform: uppercase; letter-spacing: 0.02em;"><?php woocommerce_page_title(); ?></h1>
						<p class="ame-shop-category-desc" style="margin-top: 0.5rem; font-size: 0.95rem; color: var(--ame-color-slate); max-width: 700px; line-height: 1.5;">
							<?php esc_html_e( 'Explore our premium handloomed silk sarees, customized cotton coordinates, and local Delhi tailoring collections.', 'ame-bazaar' ); ?>
						</p>
					<?php endif; ?>
				</div>
				
				<div class="ame-category-meta-stats" style="background: var(--ame-color-white, #fff); padding: 0.5rem 1rem; border-radius: 40px; border: 1px solid var(--ame-color-border, #dbe2ea); font-size: 0.85rem; font-weight: 700; color: var(--ame-color-navy);">
					<?php 
					global $wp_query;
					$count = $wp_query->found_posts;
					printf( _n( '%d Product', '%d Products', $count, 'ame-bazaar' ), $count );
					?>
				</div>
			</div>
			
			<!-- Category SEO Intro -->
			<div class="ame-category-seo-intro" style="margin-top: 1.25rem; font-size: 0.82rem; color: #64748b; line-height: 1.5; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
				<strong>Factual Retailer Directives:</strong> Authentically sourced apparel directly from regional weavers and master handloom artisans. Certified for colorfastness, anti-shrink treatment, and 100% skin-safe cotton fibers. Hand-selected for Delhi's extreme climate patterns.
			</div>
		</header>

		<!-- Quick Filters chips -->
		<?php
		$current_url = remove_query_arg( array( 'paged' ) );
		$get_quick_filter_link = function( $params ) use ( $current_url ) {
			return add_query_arg( $params, $current_url );
		};

		$is_active_chip = function( $param_key, $param_val = null ) {
			if ( ! isset( $_GET[ $param_key ] ) ) {
				return false;
			}
			if ( null === $param_val ) {
				return true;
			}
			return $_GET[ $param_key ] === $param_val;
		};
		?>
		<div class="ame-quick-filters-bar" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
			<span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--ame-color-navy); margin-right: 0.5rem;">Quick Filters:</span>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'orderby' => 'date' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('orderby', 'date') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">New Arrivals</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'orderby' => 'popularity' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('orderby', 'popularity') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Best Sellers</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'filter_trending' => '1' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('filter_trending', '1') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Trending</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'max_price' => '499' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('max_price', '499') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Under ₹499</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'max_price' => '999' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('max_price', '999') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Under ₹999</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'filter_fabric' => 'pure-cotton' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('filter_fabric', 'pure-cotton') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Cotton</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'filter_season' => 'winter' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('filter_season', 'winter') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Winter</a>
			
			<a href="<?php echo esc_url( $get_quick_filter_link( array( 'filter_season' => 'summer' ) ) ); ?>" class="ame-ai-topic-chip <?php echo $is_active_chip('filter_season', 'summer') ? 'active' : ''; ?>" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 40px; background: #e2e8f0; color: #334155; text-decoration: none; font-weight: 600;">Summer</a>

			<?php if ( ! empty( $_GET ) ) : ?>
				<a href="<?php echo esc_url( strtok( $current_url, '?' ) ); ?>" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: underline; margin-left: auto;">Clear Filters</a>
			<?php endif; ?>
		</div>

		<!-- Main Layout Grid -->
		<div class="ame-category-grid" style="display: grid; grid-template-columns: 240px 1fr; gap: 2rem; align-items: start;">
			
			<!-- Left side: Custom Sidebar filters -->
			<aside class="ame-shop-sidebar" style="background: #fdfdfa; border: 1px solid var(--ame-color-border, #dbe2ea); border-radius: 8px; padding: 1.25rem;">
				<form method="get" action="" class="ame-filter-form">
					<!-- Retain critical parameters -->
					<?php if ( isset( $_GET['s'] ) ) : ?><input type="hidden" name="s" value="<?php echo esc_attr( $_GET['s'] ); ?>" /><?php endif; ?>
					<?php if ( isset( $_GET['orderby'] ) ) : ?><input type="hidden" name="orderby" value="<?php echo esc_attr( $_GET['orderby'] ); ?>" /><?php endif; ?>
					
					<h3 style="font-size: 0.9rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1rem; text-transform: uppercase; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 0.35rem; letter-spacing: 0.05em;">Refine Products</h3>
					
					<!-- Price Filter -->
					<div class="ame-filter-group" style="margin-bottom: 1.25rem;">
						<h4 style="font-size: 0.8rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.4rem 0; text-transform: uppercase; letter-spacing: 0.02em;">Max Price (INR)</h4>
						<input type="number" name="max_price" placeholder="e.g. 999" value="<?php echo isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''; ?>" style="width: 100%; padding: 0.4rem 0.6rem; border-radius: 6px; border: 1px solid var(--ame-color-border, #dbe2ea); font-size: 0.85rem;" />
					</div>
					
					<!-- Custom Attributes Filters -->
					<?php
					$filter_fields = array(
						'filter_gender'   => array( 'label' => 'Target Gender', 'options' => array( 'men' => 'Men\'s Wear', 'women' => 'Women\'s Wear', 'boys' => 'Boys Wear', 'girls' => 'Girls Wear', 'kids' => 'Kids Essentials', 'unisex' => 'Unisex' ) ),
						'filter_brand'    => array( 'label' => 'Brand', 'options' => array( 'AME Bazaar' => 'AME Bazaar', 'Maheshwari' => 'Maheshwari Handloom' ) ),
						'filter_fabric'   => array( 'label' => 'Fabric Type', 'options' => array( 'pure-cotton' => 'Pure Cotton', 'mulmul-cotton' => 'Mulmul Cotton', 'silk' => 'Silk', 'rayon' => 'Soft Rayon', 'georgette' => 'Georgette', 'cotton-blend' => 'Cotton Blend' ) ),
						'filter_pattern'  => array( 'label' => 'Pattern Style', 'options' => array( 'solid' => 'Solid / Plain', 'printed' => 'Printed', 'embroidered' => 'Embroidered', 'woven' => 'Woven / Zari Border' ) ),
						'filter_fit'      => array( 'label' => 'Fit Style', 'options' => array( 'regular' => 'Regular Fit', 'slim' => 'Slim Fit', 'loose' => 'Comfort Fit', 'tailored' => 'Custom Tailored' ) ),
						'filter_sleeve'   => array( 'label' => 'Sleeve Type', 'options' => array( 'full' => 'Full Sleeve', 'half' => 'Half Sleeve', 'sleeveless' => 'Sleeveless', 'three-quarter' => '3/4 Sleeve' ) ),
						'filter_neck'     => array( 'label' => 'Neck Type', 'options' => array( 'collar' => 'Shirt Collar', 'mandarin' => 'Mandarin Collar', 'round' => 'Round Neck', 'v-neck' => 'V-Neck' ) ),
						'filter_occasion' => array( 'label' => 'Occasion', 'options' => array( 'casual' => 'Casual Daily', 'formal' => 'Office Formal', 'wedding' => 'Wedding Wear', 'festival' => 'Festive Wear' ) ),
						'filter_season'   => array( 'label' => 'Seasonality', 'options' => array( 'all-season' => 'All Seasons', 'summer' => 'Summer Wear', 'winter' => 'Winter Wear' ) ),
					);
					
					foreach ( $filter_fields as $query_key => $config ) :
					?>
						<div class="ame-filter-group" style="margin-bottom: 1rem;">
							<h4 style="font-size: 0.8rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.4rem 0; text-transform: uppercase; letter-spacing: 0.02em;"><?php echo esc_html( $config['label'] ); ?></h4>
							<select name="<?php echo esc_attr( $query_key ); ?>" style="width: 100%; padding: 0.4rem 0.5rem; border-radius: 6px; border: 1px solid var(--ame-color-border, #dbe2ea); font-size: 0.85rem; background: #fff; color: var(--ame-color-slate);" onchange="this.form.submit()">
								<option value=""><?php printf( 'All %s', $config['label'] ); ?></option>
								<?php foreach ( $config['options'] as $val => $lbl ) : ?>
									<option value="<?php echo esc_attr( $val ); ?>" <?php selected( isset( $_GET[ $query_key ] ) && $_GET[ $query_key ] === $val ); ?>><?php echo esc_html( $lbl ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>
					
					<div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
						<button type="submit" class="ame-card-add-btn" style="width: 100%; border: none; padding: 0.5rem; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-transform: uppercase; background: var(--ame-color-navy); color: #fff; border-radius: 4px;">Apply</button>
						<a href="<?php echo esc_url( strtok( $current_url, '?' ) ); ?>" class="ame-card-wa-btn" style="width: 100%; text-align: center; text-decoration: none; padding: 0.5rem; font-size: 0.8rem; font-weight: 700; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
					</div>
				</form>
			</aside>

			<!-- Right side: Catalog Loops -->
			<div class="ame-shop-catalog-content">
				<?php
				if ( woocommerce_product_loop() ) {

					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked woocommerce_output_all_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					echo '<div class="ame-shop-loop-meta-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">';
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
					<div class="ame-empty-state ame-premium-card ame-depth-2" style="padding: 3rem; text-align: center; background: #fdfdfa; border-radius: 12px; border: 1px solid var(--ame-color-border, #dbe2ea);">
						<div class="ame-empty-icon-wrap" style="margin: 0 auto 1.5rem auto; width: 60px; height: 60px; background: var(--ame-color-cream, #f8f7f4); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
							<svg class="ame-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 30px; height: 30px; color: var(--ame-color-gold-dark, #ca8a04);">
								<circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line>
							</svg>
						</div>
						<h2 class="ame-empty-title" style="font-size: 1.5rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.75rem;">Products Coming Soon</h2>
						<p class="ame-empty-desc" style="font-size: 0.95rem; color: var(--ame-color-slate); max-width: 500px; margin: 0 auto 2rem auto; line-height: 1.5;">
							We are currently handpicking and stitching new inventory for this collection at our Mubarakpur Road workshop. Visit our store location or contact our design team directly.
						</p>
						
						<!-- Empty CTAs grid -->
						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; max-width: 600px; margin: 0 auto 2rem auto;">
							<a href="https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi" target="_blank" rel="noopener" class="ame-card ame-hover-lift" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem; background: #fff; border: 1px solid var(--ame-color-border, #dbe2ea); border-radius: 8px;">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: var(--ame-color-gold-dark, #916c02);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
								<span style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy);">Visit Our Store</span>
							</a>
							<a href="<?php 
								$whatsapp = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
								$whatsapp_tel = preg_replace( '/[^0-9]/', '', $whatsapp );
								echo esc_url( 'https://wa.me/' . $whatsapp_tel . '?text=Hi,%20I%20am%20inquiring%20about%20custom%20garments%20availability.' );
							?>" target="_blank" rel="noopener" class="ame-card ame-hover-lift" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem; background: #fff; border: 1px solid var(--ame-color-border, #dbe2ea); border-radius: 8px;">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: #25d366;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
								<span style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy);">Chat on WhatsApp</span>
							</a>
							<a href="<?php echo esc_url( home_url( '/tailoring-services/' ) ); ?>" class="ame-card ame-hover-lift" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem; background: #fff; border: 1px solid var(--ame-color-border, #dbe2ea); border-radius: 8px;">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: var(--ame-color-navy);"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
								<span style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy);">Tailoring & Fittings</span>
							</a>
						</div>

						<!-- Explore Related Categories -->
						<div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
							<h4 style="font-size: 0.85rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Explore Related Categories</h4>
							<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem;">
								<a href="<?php echo esc_url( home_url( '/category/mens-wear/' ) ); ?>" class="ame-ai-topic-chip" style="font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 40px; background: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600;">Men's Wear</a>
								<a href="<?php echo esc_url( home_url( '/category/womens-wear/' ) ); ?>" class="ame-ai-topic-chip" style="font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 40px; background: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600;">Women's Wear</a>
								<a href="<?php echo esc_url( home_url( '/category/boys-wear/' ) ); ?>" class="ame-ai-topic-chip" style="font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 40px; background: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600;">Boys Wear</a>
								<a href="<?php echo esc_url( home_url( '/category/girls-wear/' ) ); ?>" class="ame-ai-topic-chip" style="font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 40px; background: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600;">Girls Wear</a>
								<a href="<?php echo esc_url( home_url( '/category/sarees/' ) ); ?>" class="ame-ai-topic-chip" style="font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 40px; background: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600;">Sarees</a>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>


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
