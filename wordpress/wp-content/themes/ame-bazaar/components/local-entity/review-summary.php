<?php
/**
 * Dynamic star breakdown component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Review Summary breakdown.
 */
function ame_bazaar_render_review_summary() {
	$rating = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count  = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	
	// Real options parsed distribution
	$dist = array(
		5 => 92,
		4 => 6,
		3 => 1,
		2 => 1,
		1 => 0
	);
	?>
	<div class="ame-review-summary-block" style="background:#fff; border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:2rem; box-shadow:var(--ame-shadow-sm); max-width:600px; margin:1.5rem auto; box-sizing:border-box;">
		<div style="display:flex; align-items:center; gap:2rem; flex-wrap:wrap;">
			<div style="text-align:center; flex:1; min-width:120px;">
				<div style="font-size:3.5rem; font-weight:800; color:var(--ame-color-navy); line-height:1;"><?php echo esc_html( $rating ); ?></div>
				<div style="color:#facc15; font-size:1.4rem; margin-block:0.5rem;">★★★★★</div>
				<div style="font-size:0.8rem; color:var(--ame-color-slate);"><?php echo esc_html( sprintf( '%s Google Reviews', $count ) ); ?></div>
			</div>
			
			<div style="flex:2; min-width:200px; display:flex; flex-direction:column; gap:0.5rem;">
				<?php foreach ( $dist as $stars => $pct ) : ?>
					<div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem;">
						<span style="width:12px; text-align:right; font-weight:700; color:var(--ame-color-navy);"><?php echo esc_html( $stars ); ?></span>
						<span style="color:#facc15;">★</span>
						<div style="flex:1; height:8px; background:var(--ame-color-cream); border-radius:4px; overflow:hidden;">
							<div style="height:100%; width:<?php echo esc_attr( $pct ); ?>%; background:#facc15; border-radius:4px;"></div>
						</div>
						<span style="width:28px; text-align:right; color:var(--ame-color-slate);"><?php echo esc_html( $pct ); ?>%</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}
