<?php
/**
 * Author template page for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$author      = get_queried_object();
$author_id   = $author->ID;
$author_name = get_the_author_meta( 'display_name', $author_id );
$author_desc = get_the_author_meta( 'description', $author_id ) ?: sprintf( __( 'Fashion Specialist at %s. Writing about local styling trends, fabric selection, alterations care, and family fashion coordinates in Delhi.', 'ame-bazaar' ), ame_bazaar_get_brand_name() );
?>

<main id="primary" class="site-main" role="main">
	<div class="ame-author-archive-wrapper">
		<div class="ame-bazaar-container">
			
			<!-- Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<!-- Author Header Panel -->
			<header class="ame-author-archive-header">
				<div class="ame-author-archive-profile">
					<div class="ame-author-archive-avatar-wrap">
						<?php echo get_avatar( $author_id, 120, '', sprintf( __( 'Avatar photo of %s', 'ame-bazaar' ), esc_attr( $author_name ) ), array( 'class' => 'ame-author-archive-avatar' ) ); ?>
					</div>
					<div class="ame-author-archive-details">
						<span class="ame-author-archive-label"><?php esc_html_e( 'Author Profile', 'ame-bazaar' ); ?></span>
						<h1 class="ame-author-archive-name"><?php echo esc_html( $author_name ); ?></h1>
						<p class="ame-author-archive-bio"><?php echo esc_html( $author_desc ); ?></p>
					</div>
				</div>
			</header>

			<!-- Post List Grid -->
			<h2 class="ame-author-posts-title"><?php printf( __( 'Articles Published by %s', 'ame-bazaar' ), esc_html( $author_name ) ); ?></h2>

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
				<p class="ame-author-no-posts"><?php esc_html_e( 'This author has not published any articles yet.', 'ame-bazaar' ); ?></p>
			<?php endif; ?>

		</div>
	</div>
</main>

<?php
get_footer();
