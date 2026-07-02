<?php
/**
 * Component: Topic Cluster Navigation Sidebar Widget
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$associated_pillar = get_post_meta( get_the_ID(), 'ame_associated_pillar', true );
if ( ! $associated_pillar ) {
	return;
}

$current_id = get_the_ID();
$registry   = ame_bazaar_get_entity_registry();
$pillar_lbl = isset( $registry[ $associated_pillar ] ) ? $registry[ $associated_pillar ]['label'] : __( 'Topic Cluster', 'ame-bazaar' );

// Query sister articles in the cluster
$query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'post_status'    => 'publish',
	'post__not_in'   => array( $current_id ),
	'meta_query'     => array(
		array(
			'key'   => 'ame_associated_pillar',
			'value' => $associated_pillar,
		),
	),
) );

if ( ! $query->have_posts() ) {
	return;
}
?>
<div class="ame-local-card ame-topic-cluster-nav-card" style="margin-top: 1.5rem;">
	<h3 class="ame-local-card-title"><?php echo esc_html( sprintf( __( 'More in %s', 'ame-bazaar' ), $pillar_lbl ) ); ?></h3>
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
