<?php
/**
 * Component: Related Local Pages Widget
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_ids = get_post_meta( get_the_ID(), 'ame_related_local_pages', true );
$pillar   = get_post_meta( get_the_ID(), 'ame_associated_pillar', true );

$query_args = array(
	'post_type'      => 'page',
	'posts_per_page' => 5,
	'post_status'    => 'publish',
);

if ( is_array( $page_ids ) && ! empty( $page_ids ) ) {
	$query_args['post__in'] = $page_ids;
} elseif ( ! empty( $pillar ) ) {
	$query_args['meta_query'] = array(
		array(
			'key'   => 'ame_local_entity_type',
			'value' => $pillar,
		),
	);
} else {
	return;
}

$query = new WP_Query( $query_args );
if ( ! $query->have_posts() ) {
	return;
}
?>
<div class="ame-local-card ame-related-local-pages-card" style="margin-top: 1.5rem;">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Our Delhi Outlets & Services', 'ame-bazaar' ); ?></h3>
	<ul class="ame-local-entity-nav-list">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</ul>
</div>
