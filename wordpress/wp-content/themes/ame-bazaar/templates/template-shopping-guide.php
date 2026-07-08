<?php
/**
 * Template Name: Interactive Shopping Guide
 * Description: Reusable template for AI Commerce Shopping & Fitting Guides.
 *
 * @package Ame_Bazaar
 */

get_header();

require_once AME_BAZAAR_PATH . '/inc/knowledge-components.php';

$post_id = get_the_ID();
$linked_cat = get_post_meta( $post_id, '_ame_guide_wc_cat', true );
$checklist_str = get_post_meta( $post_id, '_ame_guide_checklist', true );
?>

<div id="primary" class="content-area ame-knowledge-single-container ame-shopping-guide-container">
	<main id="main" class="site-main" role="main">
		
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'shopping-guide-article' ); ?>>
				
				<header class="ame-knowledge-hero">
					<?php ame_bazaar_render_breadcrumb(); ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<div class="entry-subtitle"><?php the_excerpt(); ?></div>
					<?php endif; ?>
					<div class="ame-article-meta">
						<span class="meta-item last-updated">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
							<?php esc_html_e( 'Last Updated: ', 'ame-bazaar' ) . the_modified_date( 'F j, Y' ); ?>
						</span>
					</div>
				</header>

				<div class="ame-knowledge-layout">
					
					<!-- Main Buying Advice Column -->
					<div class="ame-knowledge-main-content">
						<div class="entry-content">
							<?php the_content(); ?>
						</div>

						<!-- Interactive Guide Checklist Component -->
						<?php if ( ! empty( $checklist_str ) ) : 
							$checklist_items = array_filter( array_map( 'trim', explode( "\n", $checklist_str ) ) );
							if ( ! empty( $checklist_items ) ) :
								?>
								<section class="ame-guide-checklist-box" style="background: var(--ame-color-cream); padding: 25px; border-radius: var(--ame-radius-md); margin-top: 35px; border: 1px solid var(--ame-color-border);">
									<h3 style="margin-top:0; color: var(--ame-color-navy);"><?php esc_html_e( 'Interactive Fit & Fitting Checklist', 'ame-bazaar' ); ?></h3>
									<p style="font-size: 0.95rem; color: var(--ame-color-slate);"><?php esc_html_e( 'Check items as you inspect sizes and fabric quality:', 'ame-bazaar' ); ?></p>
									<ul class="checklist-items" style="list-style: none; padding: 0; margin: 15px 0 0 0;">
										<?php foreach ( $checklist_items as $index => $item ) : ?>
											<li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
												<input type="checkbox" id="chk-<?php echo esc_attr( $index ); ?>" style="width: 18px; height: 18px; cursor: pointer;" />
												<label for="chk-<?php echo esc_attr( $index ); ?>" style="font-size: 1.05rem; cursor: pointer;"><?php echo esc_html( $item ); ?></label>
											</li>
										<?php endforeach; ?>
									</ul>
								</section>
								<?php
							endif;
						endif; ?>

						<!-- Dynamic WooCommerce Products Loop (Category Based) -->
						<?php if ( ! empty( $linked_cat ) ) :
							$cat_term = get_term_by( 'slug', $linked_cat, 'product_cat' );
							if ( $cat_term && ! is_wp_error( $cat_term ) ) :
								$cat_query = new WP_Query( array(
									'post_type'      => 'product',
									'post_status'    => 'publish',
									'posts_per_page' => 4,
									'tax_query'      => array(
										array(
											'taxonomy' => 'product_cat',
											'field'    => 'slug',
											'terms'    => $linked_cat,
										),
									),
								) );

								if ( $cat_query->have_posts() ) :
									?>
									<section class="ame-related-products" style="margin-top: 40px; border-top: 2px solid var(--ame-color-border); padding-top: 30px;">
										<h3 class="section-title"><?php printf( esc_html__( 'Recommended %s Products', 'ame-bazaar' ), esc_html( $cat_term->name ) ); ?></h3>
										<div class="products-grid">
											<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); 
												global $product;
												?>
												<div class="product-item-card">
													<?php if ( has_post_thumbnail() ) : ?>
														<div class="product-image">
															<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
														</div>
													<?php endif; ?>
													<h4 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
													<?php if ( function_exists( 'woocommerce_template_loop_price' ) ) : ?>
														<div class="product-price"><?php woocommerce_template_loop_price(); ?></div>
													<?php endif; ?>
													<a href="<?php the_permalink(); ?>" class="button view-product-btn"><?php esc_html_e( 'View Product', 'ame-bazaar' ); ?></a>
												</div>
											<?php endwhile; wp_reset_postdata(); ?>
										</div>
									</section>
									<?php
								endif;
							endif;
						endif; ?>
					</div>

					<!-- Local Merchant Details Sidebar -->
					<aside class="ame-knowledge-sidebar" role="complementary">
						
						<!-- Centralized Location & Contact Details -->
						<?php ame_bazaar_render_location_facts(); ?>

						<!-- Unified Business Hours and Support Facts -->
						<?php ame_bazaar_render_business_facts(); ?>

						<!-- Tailoring Alterations & Custom Stitching Service details -->
						<section class="ame-fact-card store-services-widget">
							<h3 class="card-title"><?php esc_html_e( 'Bespoke Custom Stitching', 'ame-bazaar' ); ?></h3>
							<p style="font-size: 0.95rem; line-height: 1.6; color: var(--ame-color-slate); margin-bottom: 15px;">
								<?php esc_html_e( 'Looking for the perfect fit? We offer custom alterations and tailored measurements directly in our Kirari store.', 'ame-bazaar' ); ?>
							</p>
							<ul class="fact-list">
								<li><?php esc_html_e( '• Expert measurement sizing', 'ame-bazaar' ); ?></li>
								<li><?php esc_html_e( '• Blouse, suit & trouser custom fits', 'ame-bazaar' ); ?></li>
								<li><?php esc_html_e( '• Finished inside 2-3 days', 'ame-bazaar' ); ?></li>
							</ul>
						</section>
					</aside>

				</div>

			</article>
			<?php
		endwhile;
		?>
		
	</main>
</div>

<?php
get_footer();
