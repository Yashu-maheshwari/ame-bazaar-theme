<?php
/**
 * Interactive Google Rating Widget styling.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Google Rating Widget.
 */
function ame_bazaar_render_google_rating_widget() {
	$rating = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count  = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	$g_url  = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	
	?>
	<div class="ame-google-rating-widget" style="background:#fff; border:1px solid #dadce0; border-radius:8px; padding:1.5rem; max-width:320px; box-sizing:border-box; display:flex; flex-direction:column; gap:0.75rem; font-family:Roboto,Arial,sans-serif; box-shadow:0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);">
		<div style="display:flex; justify-content:space-between; align-items:center;">
			<div style="display:flex; align-items:center; gap:0.5rem;">
				<img src="https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_92x30dp.png" alt="Google" style="height:20px; width:auto;" />
				<span style="font-size:0.75rem; color:#5f6368; font-weight:500;">Review</span>
			</div>
			<div style="background:#e8f0fe; color:#1a73e8; font-size:0.7rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:4px;">Verified</div>
		</div>

		<div style="display:flex; align-items:baseline; gap:0.5rem;">
			<span style="font-size:2.25rem; font-weight:400; color:#202124; line-height:1;"><?php echo esc_html( $rating ); ?></span>
			<div>
				<div style="color:#f29900; font-size:0.95rem; line-height:1;">★★★★★</div>
				<span style="font-size:0.75rem; color:#5f6368;"><?php echo esc_html( sprintf( '%s Google reviews', $count ) ); ?></span>
			</div>
		</div>

		<a href="<?php echo esc_url( $g_url ); ?>" target="_blank" rel="noopener noreferrer" style="text-decoration:none; background:#1a73e8; color:#fff; font-size:0.85rem; font-weight:700; text-align:center; padding:0.5rem 1rem; border-radius:4px; display:block; transition:background 0.2s;">
			Write a review
		</a>
	</div>
	<?php
}
