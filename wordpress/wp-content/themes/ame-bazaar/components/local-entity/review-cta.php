<?php
/**
 * Dynamic Local CTAs Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Review CTAs.
 */
function ame_bazaar_render_review_ctas() {
	$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99999 99999' );
	$whatsapp   = ame_bazaar_get_business_setting( 'whatsapp', '+91 99999 99999' );
	$review_url = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	$maps_url   = ame_bazaar_get_business_setting( 'maps_url', '#' );
	
	$clean_phone = preg_replace( '/[^0-9+]/', '', $phone );
	$clean_wa    = preg_replace( '/[^0-9+]/', '', $whatsapp );
	
	?>
	<div class="ame-reviews-ctas" style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-block:1rem;">
		<a href="<?php echo esc_url( $review_url ); ?>" class="ame-btn-primary" target="_blank" rel="noopener noreferrer" style="text-decoration:none; font-size:0.85rem; padding:0.6rem 1.2rem;">
			Write a Review
		</a>
		<a href="<?php echo esc_url( $maps_url ); ?>" class="ame-btn-secondary" target="_blank" rel="noopener noreferrer" style="text-decoration:none; font-size:0.85rem; padding:0.6rem 1.2rem;">
			Get Directions
		</a>
		<a href="tel:<?php echo esc_attr( $clean_phone ); ?>" class="ame-btn-outline" style="text-decoration:none; font-size:0.85rem; padding:0.6rem 1.2rem;">
			Call Store
		</a>
		<a href="https://wa.me/<?php echo esc_attr( ltrim( $clean_wa, '+' ) ); ?>" class="ame-btn-outline" target="_blank" rel="noopener noreferrer" style="text-decoration:none; font-size:0.85rem; padding:0.6rem 1.2rem; display:inline-flex; align-items:center; gap:0.25rem;">
			WhatsApp
		</a>
	</div>
	<?php
}
