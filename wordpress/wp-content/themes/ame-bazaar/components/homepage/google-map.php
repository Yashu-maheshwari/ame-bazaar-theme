<?php
/**
 * Exact Google Business Profile map for AME Bazaar.
 *
 * Uses the verified Google Maps place ID returned for the exact AME Bazaar
 * business listing; no guessed coordinates or generic Kirari search.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$place_id      = 'ChIJVa8bwP4HDTkRPo_4QDK2ixc';
$maps_place_url = 'https://www.google.com/maps/place/?q=place_id:' . $place_id;
$maps_embed_url = 'https://www.google.com/maps?q=place_id:' . $place_id . '&output=embed';
?>

<section class="ame-visit-store-section" aria-labelledby="ame-google-map-title">
	<div class="ame-bazaar-container">
		<div style="text-align:center; margin-bottom:1.5rem;">
			<h2 id="ame-google-map-title" style="margin:0 0 .5rem; color:var(--ame-color-navy);">
				<?php esc_html_e( 'Find AME Bazaar on Google Maps', 'ame-bazaar' ); ?>
			</h2>
			<p style="margin:0; color:var(--ame-color-slate);">
				<?php esc_html_e( 'Exact verified AME Bazaar store location on Google Maps.', 'ame-bazaar' ); ?>
			</p>
		</div>

		<div class="ame-map-container" style="width:100%; overflow:hidden; border-radius:var(--ame-radius-md); box-shadow:var(--ame-shadow-md);">
			<iframe
				src="<?php echo esc_url( $maps_embed_url ); ?>"
				style="display:block; width:100%; height:clamp(320px,45vw,520px); border:0;"
				loading="lazy"
				allowfullscreen
				referrerpolicy="no-referrer-when-downgrade"
				title="<?php esc_attr_e( 'AME Bazaar - Family Garment Store on Google Maps', 'ame-bazaar' ); ?>"
			></iframe>
		</div>

		<div style="display:flex; justify-content:center; margin-top:1.5rem;">
			<a href="<?php echo esc_url( $maps_place_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary">
				<?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?>
			</a>
		</div>
	</div>
</section>
