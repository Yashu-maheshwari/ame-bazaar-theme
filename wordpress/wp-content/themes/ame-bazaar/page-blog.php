<?php
/**
 * Dedicated Blog/Journal page template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$paged = max( 1, absint( get_query_var( 'paged' ) ) );

$blog_query = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 12,
	'paged'               => $paged,
	'ignore_sticky_posts' => false,
) );
?>

<main id="primary" class="site-main" role="main">
	<div class="ame-blog-archive-wrapper">
		<div class="ame-bazaar-container">
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<header class="ame-blog-archive-header" style="margin-top: 1.5rem; margin-bottom: 2rem;">
				<h1 class="ame-blog-archive-title" style="font-size: clamp(1.9rem, 4vw, 2.8rem); line-height: 1.2; color: var(--ame-color-navy); margin-bottom: 0.5rem;">
					<?php esc_html_e( 'AME Bazaar Journal', 'ame-bazaar' ); ?>
				</h1>
				<p class="ame-blog-archive-desc" style="font-size: 0.95rem; color: var(--ame-color-slate); margin: 0;">
					<?php esc_html_e( 'Fashion guides, clothing tips, fabric care, family styling advice, and helpful local fashion knowledge.', 'ame-bazaar' ); ?>
				</p>
			</header>

			<?php if ( $blog_query->have_posts() ) : ?>
				<div class="ame-blog-archive-grid">
					<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-blog-card' ); ?>>
							<div class="ame-blog-card-thumb-wrap">
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>" class="ame-blog-card-thumb-link" aria-hidden="true" tabindex="-1">
										<?php the_post_thumbnail( 'medium_large', array( 'class' => 'ame-blog-card-thumb', 'loading' => 'lazy' ) ); ?>
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

								<h2 class="ame-blog-card-title">
									<a href="<?php the_permalink(); ?>" class="ame-blog-card-title-link"><?php the_title(); ?></a>
								</h2>

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
					<?php endwhile; ?>
				</div>

				<?php
				$pagination = paginate_links( array(
					'total'     => $blog_query->max_num_pages,
					'current'   => $paged,
					'mid_size'  => 2,
					'prev_text' => '&larr; ' . __( 'Previous', 'ame-bazaar' ),
					'next_text' => __( 'Next', 'ame-bazaar' ) . ' &rarr;',
					'type'      => 'list',
				) );
				if ( $pagination ) :
					?>
					<nav class="ame-blog-pagination" aria-label="Blog pagination">
						<?php echo wp_kses_post( $pagination ); ?>
					</nav>
				<?php endif; ?>

			<?php else : ?>
				<div class="ame-blog-archive-empty" style="padding: 3rem 1rem; text-align: center;">
					<h2><?php esc_html_e( 'No published articles found.', 'ame-bazaar' ); ?></h2>
					<p><?php esc_html_e( 'The Journal will automatically show articles once they are published.', 'ame-bazaar' ); ?></p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
