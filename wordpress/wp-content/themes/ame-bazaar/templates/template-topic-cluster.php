<?php
/**
 * Template Name: Topic Cluster Template
 * Template Post Type: post
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<!-- Reading Progress Bar -->
<div id="ame-reading-progress-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 4px; background: rgba(0,0,0,0.05); z-index: 9999;">
	<div id="ame-reading-progress-bar" style="width: 0%; height: 100%; background: var(--ame-color-gold); transition: width 80ms ease-out;"></div>
</div>

<main id="primary" class="site-main" role="main">
	<div class="ame-blog-single-wrapper">
		<div class="ame-bazaar-container">
			
			<!-- Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<div class="ame-local-entity-layout" style="margin-top: 1.5rem;">
				<!-- Left Column: Spoke Article main content -->
				<div class="ame-local-entity-main">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-blog-post-article' ); ?>>
							
							<!-- Header -->
							<header class="ame-blog-post-header" style="margin-bottom: 1.5rem;">
								<div class="ame-blog-post-meta-top" style="display: flex; gap: 0.75rem; align-items: center; font-size: 0.85rem; margin-bottom: 0.75rem;">
									<?php
									$categories = get_the_category();
									if ( ! empty( $categories ) ) :
										foreach ( $categories as $cat ) :
											?>
											<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="ame-blog-post-cat-badge" style="background: var(--ame-color-navy); color: var(--ame-color-white); padding: 0.2rem 0.6rem; border-radius: var(--ame-radius-sm); font-size: 0.75rem; font-weight: 700; text-decoration: none; text-transform: uppercase;"><?php echo esc_html( $cat->name ); ?></a>
											<?php
										endforeach;
									endif;
									?>
									<span class="ame-blog-post-date" style="color: #64748b; font-weight: 500;"><?php echo esc_html( get_the_date() ); ?></span>
								</div>
								<h1 class="ame-blog-post-title" style="margin-block: 0.5rem; font-size: clamp(1.75rem, 4vw, 2.5rem); line-height: 1.2; color: var(--ame-color-navy);"><?php the_title(); ?></h1>
							</header>

							<!-- Parent Pillar Connection Block -->
							<?php
							$associated_pillar = get_post_meta( get_the_ID(), 'ame_associated_pillar', true );
							if ( ! empty( $associated_pillar ) ) :
								$pillar_page = new WP_Query( array(
									'post_type'      => 'page',
									'posts_per_page' => 1,
									'meta_query'     => array(
										array(
											'key'   => 'ame_associated_pillar',
											'value' => $associated_pillar,
										),
									),
								) );

								if ( $pillar_page->have_posts() ) :
									$pillar_page->the_post();
									?>
									<div class="ame-local-card ame-pillar-connection-banner" style="border-left: 4px solid var(--ame-color-navy); margin-bottom: 2rem; background: var(--ame-color-cream); display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem;">
										<span style="font-size: 0.85rem; font-weight: 600; color: var(--ame-color-slate);">
											<?php esc_html_e( 'This guide is part of our comprehensive collection:', 'ame-bazaar' ); ?>
											<a href="<?php echo esc_url( get_permalink() ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; margin-left: 0.25rem; font-weight: 700;"><?php the_title(); ?></a>
										</span>
									</div>
									<?php
									wp_reset_postdata();
								endif;
							endif;
							?>

							<!-- Featured Image -->
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="ame-blog-post-featured-image-wrap" style="margin-bottom: 2rem; border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-sm);">
									<?php the_post_thumbnail( 'full', array( 'class' => 'ame-blog-post-featured-image', 'loading' => 'eager' ) ); ?>
								</div>
							<?php endif; ?>

							<!-- AI Citation Summary Box -->
							<?php get_template_part( 'components/blog/ai-citation' ); ?>

							<!-- Table of Contents -->
							<?php get_template_part( 'components/blog/table-of-contents' ); ?>

							<!-- Content -->
							<div class="entry-content ame-blog-post-entry-content">
								<?php the_content(); ?>
							</div>

							<!-- FAQ Section (Conditional) -->
							<?php get_template_part( 'components/blog/faq-component' ); ?>

							<!-- In-Cluster Next / Prev Post Navigation -->
							<?php
							if ( ! empty( $associated_pillar ) ) :
								$cluster_posts = get_posts( array(
									'post_type'      => 'post',
									'posts_per_page' => -1,
									'meta_query'     => array(
										array(
											'key'   => 'ame_associated_pillar',
											'value' => $associated_pillar,
										),
									),
									'orderby'        => 'date',
									'order'          => 'ASC',
								) );

								$current_index = -1;
								foreach ( $cluster_posts as $index => $cp ) {
									if ( $cp->ID === get_the_ID() ) {
										$current_index = $index;
										break;
									}
								}

								$prev_link = '';
								$next_link = '';
								if ( $current_index > 0 ) {
									$prev_p    = $cluster_posts[ $current_index - 1 ];
									$prev_link = sprintf(
										'<a href="%s" style="color: var(--ame-color-navy); text-decoration: none; font-weight: 700; font-size: 0.9rem;">&larr; %s</a>',
										esc_url( get_permalink( $prev_p->ID ) ),
										esc_html( get_the_title( $prev_p->ID ) )
									);
								}
								if ( $current_index >= 0 && $current_index < count( $cluster_posts ) - 1 ) {
									$next_p    = $cluster_posts[ $current_index + 1 ];
									$next_link = sprintf(
										'<a href="%s" style="color: var(--ame-color-navy); text-decoration: none; font-weight: 700; font-size: 0.9rem;">%s &rarr;</a>',
										esc_url( get_permalink( $next_p->ID ) ),
										esc_html( get_the_title( $next_p->ID ) )
									);
								}

								if ( ! empty( $prev_link ) || ! empty( $next_link ) ) :
									?>
									<nav class="ame-cluster-post-navigation" aria-label="<?php esc_attr_e( 'Cluster post navigation', 'ame-bazaar' ); ?>" style="border-top: 1px solid var(--ame-color-border); border-bottom: 1px solid var(--ame-color-border); padding-block: 1.25rem; margin-top: 2.5rem; display: flex; justify-content: space-between; align-items: center; gap: 2rem;">
										<div class="ame-cluster-nav-prev-wrap" style="flex: 1; text-align: left;">
											<?php if ( ! empty( $prev_link ) ) : ?>
												<span style="display: block; font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;"><?php esc_html_e( 'Previous Article', 'ame-bazaar' ); ?></span>
												<?php echo $prev_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<?php endif; ?>
										</div>
										<div class="ame-cluster-nav-next-wrap" style="flex: 1; text-align: right;">
											<?php if ( ! empty( $next_link ) ) : ?>
												<span style="display: block; font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;"><?php esc_html_e( 'Next Article', 'ame-bazaar' ); ?></span>
												<?php echo $next_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<?php endif; ?>
										</div>
									</nav>
									<?php
								endif;
							endif;
							?>

							<!-- Reusable widgets at the bottom of the article -->
							<footer class="ame-blog-post-footer" style="margin-top: 3rem; border-top: 1px solid var(--ame-color-border); padding-top: 2rem; display: flex; flex-direction: column; gap: 2rem;">
								<!-- Author Info Box -->
								<?php get_template_part( 'components/blog/author-info' ); ?>
								
								<!-- Related Products Block -->
								<?php get_template_part( 'components/blog/related-products' ); ?>

								<!-- Store Internal Link Widget -->
								<?php get_template_part( 'components/blog/internal-linking' ); ?>
							</footer>

						</article>

						<!-- Related Articles -->
						<?php get_template_part( 'components/blog/related-articles' ); ?>

						<?php
					endwhile;
					?>
				</div>

				<!-- Right Column: Sidebar -->
				<aside class="ame-local-entity-sidebar">
					<?php get_template_part( 'components/blog/topic-cluster-nav' ); ?>
					<?php get_template_part( 'components/blog/related-local-pages' ); ?>
					<?php get_template_part( 'components/local-entity/business-info' ); ?>
					<?php get_template_part( 'components/local-entity/opening-hours' ); ?>
				</aside>
			</div>

		</div>
	</div>
</main>
<?php
get_footer();
