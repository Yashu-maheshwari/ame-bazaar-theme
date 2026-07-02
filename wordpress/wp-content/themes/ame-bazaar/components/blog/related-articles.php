<?php
/**
 * Related articles component template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
if ( empty( $categories ) ) {
	return;
}

$related_args = array(
	'category__in'        => array( $categories[0]->term_id ),
	'post__not_in'        => array( get_the_ID() ),
	'posts_per_page'      => 3,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
);

$related_query = new WP_Query( $related_args );

if ( ! $related_query->have_posts() ) {
	return;
}
?>

<div class="ame-related-articles-section">
	<h3 class="ame-related-articles-title"><?php esc_html_e( 'Recommended Reading', 'ame-bazaar' ); ?></h3>
	
	<div class="ame-related-articles-grid">
		<?php
		while ( $related_query->have_posts() ) :
			$related_query->the_post();
			?>
			<article class="ame-related-article-card">
				<div class="ame-related-article-thumb-wrap">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="ame-related-article-thumb-link" aria-hidden="true" tabindex="-1">
							<?php the_post_thumbnail( 'medium', array( 'class' => 'ame-related-article-thumb' ) ); ?>
						</a>
					<?php else : ?>
						<div class="ame-related-article-placeholder">
							<span class="ame-placeholder-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
						</div>
					<?php endif; ?>
				</div>
				<div class="ame-related-article-body">
					<div class="ame-related-article-meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</div>
					<h4 class="ame-related-article-card-title">
						<a href="<?php the_permalink(); ?>" class="ame-related-article-title-link"><?php the_title(); ?></a>
					</h4>
				</div>
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</div>
