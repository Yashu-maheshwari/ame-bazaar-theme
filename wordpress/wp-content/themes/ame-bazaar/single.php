<?php
/**
 * Single post template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main" role="main">
	<div class="ame-blog-single-wrapper">
		<div class="ame-bazaar-container">
			
			<!-- Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<div class="ame-local-entity-layout" style="margin-top: 1.5rem;">
				<!-- Left Column: Article content -->
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
