<?php
/**
 * Template Name: Pillar Page Template
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main" role="main">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>
	
	<div class="ame-bazaar-container ame-bazaar-section">
		<div class="ame-local-entity-layout">
			<!-- Left Column: Pillar Main Content & Spoke Articles Grid -->
			<div class="ame-local-entity-main">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<header class="entry-header" style="margin-bottom: 2rem;">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>

					<!-- AI Citation Summary Box -->
					<?php get_template_part( 'components/blog/ai-citation' ); ?>

					<!-- Table of Contents -->
					<?php get_template_part( 'components/blog/table-of-contents' ); ?>

					<div class="entry-content" style="margin-bottom: 3rem;">
						<?php the_content(); ?>
					</div>

					<!-- FAQ Section (Conditional) -->
					<?php get_template_part( 'components/blog/faq-component' ); ?>
				<?php endwhile; ?>

				<!-- Dynamic Spoke Articles Grid -->
				<?php
				$pillar_key = get_post_meta( get_the_ID(), 'ame_associated_pillar', true );
				if ( ! empty( $pillar_key ) ) :
					$spokes = new WP_Query( array(
						'post_type'      => 'post',
						'posts_per_page' => 12,
						'post_status'    => 'publish',
						'meta_query'     => array(
							array(
								'key'   => 'ame_associated_pillar',
								'value' => $pillar_key,
							),
						),
					) );

					if ( $spokes->have_posts() ) :
						?>
						<section class="ame-pillar-spokes-section" style="border-top: 1px solid var(--ame-color-border); padding-top: 2.5rem;">
							<h2 style="font-family: var(--ame-font-heading); font-size: 1.5rem; font-weight: 700; color: var(--ame-color-navy); margin-bottom: 1.75rem;">
								<?php esc_html_e( 'Expert Guides & Articles', 'ame-bazaar' ); ?>
							</h2>
							<div class="ame-blog-archive-grid">
								<?php
								while ( $spokes->have_posts() ) :
									$spokes->the_post();
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
												<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
											</div>
											<h3 class="ame-blog-card-title">
												<a href="<?php the_permalink(); ?>" class="ame-blog-card-title-link"><?php the_title(); ?></a>
											</h3>
											<div class="ame-blog-card-excerpt">
												<?php the_excerpt(); ?>
											</div>
											<a href="<?php the_permalink(); ?>" class="ame-blog-card-read-more">
												<span><?php esc_html_e( 'Read Guide', 'ame-bazaar' ); ?></span>
												<svg class="ame-arrow-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
													<path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
												</svg>
											</a>
										</div>
									</article>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
							</div>
						</section>
						<?php
					endif;
				endif;
				?>
			</div>

			<!-- Right Column: Sidebar Widgets -->
			<aside class="ame-local-entity-sidebar">
				<?php get_template_part( 'components/local-entity/business-info' ); ?>
				<?php get_template_part( 'components/local-entity/opening-hours' ); ?>
				<?php get_template_part( 'components/local-entity/payment-methods' ); ?>
				<?php get_template_part( 'components/local-entity/local-navigation' ); ?>
			</aside>
		</div>
	</div>
</main>
<?php
get_footer();
