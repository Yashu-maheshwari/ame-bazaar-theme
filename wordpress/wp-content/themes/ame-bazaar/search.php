<?php
/**
 * Search results page template for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;
$result_count = $wp_query->found_posts;
$brand_name   = ame_bazaar_get_brand_name();
$phone        = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$maps_url     = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$cat_men_url  = get_theme_mod( 'ame_bazaar_cat_men_url', '#' );
$cat_women_url= get_theme_mod( 'ame_bazaar_cat_women_url', '#' );
$cat_kids_url = get_theme_mod( 'ame_bazaar_cat_kids_url', '#' );
$cat_acc_url  = get_theme_mod( 'ame_bazaar_cat_accessories_url', '#' );
?>

<main id="primary" class="site-main" role="main">
	<div class="ame-search-results-wrapper">
		<div class="ame-bazaar-container">
			
			<!-- Breadcrumbs -->
			<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

			<!-- Header -->
			<header class="ame-search-results-header">
				<h1 class="ame-search-title">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search results for: "%s"', 'ame-bazaar' ),
						esc_html( get_search_query() )
					);
					?>
				</h1>
				<p class="ame-search-count-desc">
					<?php
					printf(
						/* translators: %d: results count */
						_n( 'We found %d matching result in our store catalog.', 'We found %d matching results in our store catalog.', $result_count, 'ame-bazaar' ),
						$result_count
					);
					?>
				</p>
			</header>

			<!-- Search Results Grid -->
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
										<span class="ame-blog-card-placeholder-text"><?php echo esc_html( $brand_name ); ?></span>
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
				
				<!-- Fallback Layout when no results found -->
				<div class="ame-search-no-results">
					<div class="ame-search-no-results-content">
						<h2 class="ame-no-results-title"><?php esc_html_e( 'No Matches Found', 'ame-bazaar' ); ?></h2>
						<p class="ame-no-results-text"><?php esc_html_e( 'We couldn\'t locate any articles or collections matching your search criteria. Please try different keywords or browse our main clothing departments.', 'ame-bazaar' ); ?></p>
						
						<div class="ame-404-search-box">
							<?php get_search_form(); ?>
						</div>

						<div class="ame-404-actions">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ame-btn ame-btn-primary">
								<?php esc_html_e( 'Return to Homepage', 'ame-bazaar' ); ?>
							</a>
						</div>
					</div>

					<div class="ame-search-no-results-departments">
						<h3 class="ame-no-results-dept-title"><?php esc_html_e( 'Browse Popular Departments', 'ame-bazaar' ); ?></h3>
						<ul class="ame-404-dept-list">
							<li>
								<a href="<?php echo esc_url( $cat_men_url ); ?>" class="ame-404-dept-link">
									<span><?php esc_html_e( 'Men\'s Wear Collection', 'ame-bazaar' ); ?></span>
									<span class="ame-dept-arrow">&rarr;</span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $cat_women_url ); ?>" class="ame-404-dept-link">
									<span><?php esc_html_e( 'Women\'s Wear Collection', 'ame-bazaar' ); ?></span>
									<span class="ame-dept-arrow">&rarr;</span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $cat_kids_url ); ?>" class="ame-404-dept-link">
									<span><?php esc_html_e( 'Kids\' Wear Collection', 'ame-bazaar' ); ?></span>
									<span class="ame-dept-arrow">&rarr;</span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $cat_women_url ); ?>" class="ame-404-dept-link">
									<span><?php esc_html_e( 'Sarees Collection', 'ame-bazaar' ); ?></span>
									<span class="ame-dept-arrow">&rarr;</span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( $cat_acc_url ); ?>" class="ame-404-dept-link">
									<span><?php esc_html_e( 'Fashion Accessories', 'ame-bazaar' ); ?></span>
									<span class="ame-dept-arrow">&rarr;</span>
								</a>
							</li>
						</ul>

						<div class="ame-search-help-box">
							<h4 class="ame-no-results-help-title"><?php esc_html_e( 'Direct Store Support', 'ame-bazaar' ); ?></h4>
							<p class="ame-no-results-help-desc"><?php printf( __( 'Looking for a specific garment? Speak to our local team on Mubarakpur Road directly.', 'ame-bazaar' ), esc_html( $brand_name ) ); ?></p>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="ame-404-call-link">
								<svg class="ame-contact-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
								</svg>
								<span><?php echo esc_html( $phone ); ?></span>
							</a>
						</div>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
</main>

<?php
get_footer();
