<?php
/**
 * Dynamic reviews rating badge component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Review Badge.
 */
function ame_bazaar_render_review_badge() {
	$rating = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count  = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	$g_url  = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	
	?>
	<div class="ame-reviews-badge-wrapper" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--ame-color-cream); border:1px solid var(--ame-color-border); padding:0.5rem 1rem; border-radius:30px;">
		<span style="color:#facc15; font-size:1.1rem; line-height:1;">★</span>
		<span style="font-weight:800; font-size:0.85rem; color:var(--ame-color-navy);"><?php echo esc_html( $rating ); ?>/5</span>
		<span style="font-size:0.75rem; color:var(--ame-color-slate);">based on <a href="<?php echo esc_url( $g_url ); ?>" target="_blank" rel="noopener noreferrer" style="color:inherit; font-weight:700; text-decoration:underline;"><?php echo esc_html( $count ); ?>+ Google reviews</a></span>
	</div>
	<?php
}
