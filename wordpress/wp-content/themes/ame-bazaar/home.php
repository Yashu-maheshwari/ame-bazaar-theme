<?php
/**
 * The blog posts index template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main" role="main">
	<div class="ame-blog-archive-wrapper">
		<div class="ame-bazaar-container">
			
			<!-- Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<div class="ame-local-entity-layout" style="margin-top: 1.5rem;">
				<!-- Left Column: Blog Posts Grid -->
				<div class="ame-local-entity-main">
					<header class="ame-blog-archive-header" style="margin-bottom: 2rem;">
						<h1 class="ame-blog-archive-title" style="font-size: clamp(1.75rem, 4vw, 2.5rem); line-height: 1.2; color: var(--ame-color-navy); margin-bottom: 0.5rem;">
							<?php esc_html_e( 'AME Bazaar Knowledge Engine', 'ame-bazaar' ); ?>
						</h1>
						<div class="ame-blog-archive-desc" style="font-size: 0.95rem; color: var(--ame-color-slate);">
							<?php esc_html_e( 'Factual styling advice, garment guidelines, fabric care instructions, and family outfit recommendations.', 'ame-bazaar' ); ?>
						</div>
					</header>

					<?php if ( have_posts() ) : ?>
						<div class="ame-blog-archive-grid">
							<?php
							while ( have_posts() ) :
								the_post();
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-blog-card' ); ?>>
									<div class="ame-blog-card-thumb-wrap">
										<?php if ( has_post_thumbnail() ) : ?>
											<a href="<?php the_permalink(); ?>" class="ame-blog-card-thumb-link" aria-hidden="true" tabindex="-1">
												<?php the_post_thumbnail( 'medium_large', array( 'class' => 'ame-blog-card-thumb' ) ); ?>
											</a>
										<?php else : ?>
											<div class="ame-blog-card-placeholder">
												<span class="ame-blog-card-placeholder-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
											</div>
										<?php endif; ?>
									</div>
									<div class="ame-blog-card-body">
										<div class="ame-blog-card-meta">
											<?php
											$categories = get_the_category();
											if ( ! empty( $categories ) ) :
												?>
												<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>" class="ame-blog-card-cat-link"><?php echo esc_html( $categories[0]->name ); ?></a>
												<span class="ame-blog-card-meta-sep">•</span>
											<?php endif; ?>
											<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
										</div>
										<h3 class="ame-blog-card-title">
											<a href="<?php the_permalink(); ?>" class="ame-blog-card-title-link"><?php the_title(); ?></a>
										</h3>
										<div class="ame-blog-card-excerpt">
											<?php the_excerpt(); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="ame-blog-card-read-more" aria-label="<?php echo esc_attr( sprintf( __( 'Read full article: %s', 'ame-bazaar' ), get_the_title() ) ); ?>">
											<span><?php esc_html_e( 'Read Article', 'ame-bazaar' ); ?></span>
											<svg class="ame-arrow-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
												<path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
											</svg>
										</a>
									</div>
								</article>
								<?php
							endwhile;
							?>
						</div>

						<!-- Pagination -->
						<div class="ame-blog-pagination">
							<?php the_posts_pagination( array(
								'prev_text' => '&larr; <span class="screen-reader-text">' . __( 'Previous Page', 'ame-bazaar' ) . '</span>',
								'next_text' => '<span class="screen-reader-text">' . __( 'Next Page', 'ame-bazaar' ) . '</span> &rarr;',
							) ); ?>
						</div>

					<?php else : ?>
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					<?php endif; ?>
				</div>

				<!-- Right Column: Sidebar -->
				<aside class="ame-local-entity-sidebar">
					<?php get_template_part( 'components/local-entity/business-info' ); ?>
					<?php get_template_part( 'components/local-entity/opening-hours' ); ?>
					<?php get_template_part( 'components/local-entity/payment-methods' ); ?>
					<?php get_template_part( 'components/local-entity/local-navigation' ); ?>
				</aside>
			</div>

		</div>
	</div>
</main>
<?php
get_footer();
