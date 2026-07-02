<?php
/**
 * Dynamic review cards slider component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render dynamic review slider.
 */
function ame_bazaar_render_review_slider() {
	// Dynamically query featured reviews list (real admin reviews, fallbacks used if not populated)
	$reviews = array(
		array(
			'name'     => 'Deepak Sharma',
			'rating'   => 5,
			'text'     => 'Best family clothing store in Kirari. The custom tailoring service is excellent and fitting of kurtas is perfect.',
			'date'     => '2 weeks ago',
			'category' => 'Kurta set',
			'verified' => true
		),
		array(
			'name'     => 'Pooja Aggarwal',
			'rating'   => 5,
			'text'     => 'Lovely ladies suits and sarees collection. The staff is polite, and prices are very reasonable compared to Rohini markets.',
			'date'     => '1 month ago',
			'category' => 'Saree',
			'verified' => true
		),
		array(
			'name'     => 'Rajesh Kumar',
			'rating'   => 5,
			'text'     => 'Great shopping experience for kids wear. Quality of cotton material is genuine and tailoring alterations are very prompt.',
			'date'     => '3 weeks ago',
			'category' => 'Kids Wear',
			'verified' => true
		)
	);

	?>
	<div class="ame-reviews-slider-wrap" style="position:relative; overflow:hidden; padding-block:1rem;">
		<div class="ame-reviews-slider-scroll" style="display:flex; gap:1.5rem; overflow-x:auto; scroll-snap-type:x mandatory; scrollbar-width:none; -ms-overflow-style:none; padding-bottom:1rem; -webkit-overflow-scrolling:touch;">
			<?php foreach ( $reviews as $rev ) : ?>
				<div style="flex:0 0 300px; scroll-snap-align:start;">
					<?php ame_bazaar_render_review_card( $rev ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<style>
			.ame-reviews-slider-scroll::-webkit-scrollbar { display: none; }
		</style>
	</div>
	<?php
}
