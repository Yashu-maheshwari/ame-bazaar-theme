<?php
/**
 * Blog Preview section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_blog_preview_title', 'Latest from our Journal' );
$section_subtitle = get_theme_mod( 'ame_bazaar_blog_preview_subtitle', 'Stay updated with clothing tips, traditional heritage, and family fashion guides.' );

$query_args = array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
);
$blog_query = new WP_Query( $query_args );
$posts_list = array();

if ( $blog_query->have_posts() ) {
	while ( $blog_query->have_posts() ) {
		$blog_query->the_post();
		$posts_list[] = array(
			'id'        => get_the_ID(),
			'title'     => get_the_title(),
			'excerpt'   => get_the_excerpt(),
			'date'      => get_the_date(),
			'img_url'   => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
			'link'      => get_permalink(),
		);
	}
	wp_reset_postdata();
}

// Return early and hide section if no blog posts exist
if ( empty( $posts_list ) ) {
	return;
}
?>

<section class="ame-blog-preview-section" aria-labelledby="ame-blog-preview-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-blog-preview-header">
			<div class="ame-blog-preview-header-left">
				<h2 id="ame-blog-preview-title" class="ame-blog-preview-section-title"><?php echo esc_html( $section_title ); ?></h2>
				<?php if ( $section_subtitle ) : ?>
					<p class="ame-blog-preview-section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="ame-blog-preview-header-right">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog' ) ); ?>" class="ame-bazaar-btn ame-bazaar-btn--primary">
					<span><?php esc_html_e( 'View All Articles', 'ame-bazaar' ); ?></span>
				</a>
			</div>
		</div>

		<!-- Articles Grid -->
		<div class="ame-blog-grid">
			<?php foreach ( $posts_list as $post_item ) : ?>
				<article class="ame-blog-card">
					<div class="ame-blog-card-visual-wrap">
						<a href="<?php echo esc_url( $post_item['link'] ); ?>" class="ame-blog-img-link" tabindex="-1" aria-hidden="true">
							<?php if ( $post_item['img_url'] ) : ?>
								<img src="<?php echo esc_url( $post_item['img_url'] ); ?>" 
									 alt="<?php echo esc_attr( $post_item['title'] ); ?>" 
									 class="ame-blog-img" 
									 loading="lazy">
							<?php else : ?>
								<div class="ame-blog-img-placeholder">
									<div class="ame-placeholder-logo-overlay">
										<span class="ame-placeholder-logo-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
									</div>
									<span class="ame-placeholder-tag"><?php esc_html_e( 'Journal', 'ame-bazaar' ); ?></span>
								</div>
							<?php endif; ?>
						</a>
					</div>

					<div class="ame-blog-card-content">
						<span class="ame-blog-card-date"><?php echo esc_html( $post_item['date'] ); ?></span>
						<h3 class="ame-blog-card-title">
							<a href="<?php echo esc_url( $post_item['link'] ); ?>">
								<?php echo esc_html( $post_item['title'] ); ?>
							</a>
						</h3>
						<p class="ame-blog-card-excerpt"><?php echo esc_html( wp_trim_words( $post_item['excerpt'], 18 ) ); ?></p>
						<a href="<?php echo esc_url( $post_item['link'] ); ?>" class="ame-blog-card-link" aria-label="<?php echo esc_attr( sprintf( __( 'Read more: %s', 'ame-bazaar' ), $post_item['title'] ) ); ?>">
							<span><?php esc_html_e( 'Read More', 'ame-bazaar' ); ?></span>
							<svg class="ame-arrow-mini" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
							</svg>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
