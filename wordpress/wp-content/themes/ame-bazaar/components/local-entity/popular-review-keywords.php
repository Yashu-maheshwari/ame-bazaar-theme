<?php
/**
 * Dynamic popular review keywords block component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Popular Review Keywords.
 */
function ame_bazaar_render_popular_review_keywords() {
	$keywords = array(
		'Custom Alterations', 'Ladies Suits', 'Designer Sarees', 'Bespoke Fitting', 
		'Mubarakpur Road', 'Best Rates', 'Family Store', 'Friendly Tailors'
	);

	?>
	<div class="ame-popular-keywords" style="text-align:center; margin-block:1.5rem;">
		<span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; display:block; margin-bottom:0.75rem;">Frequently Mentioned In Reviews</span>
		<div style="display:flex; justify-content:center; gap:0.5rem; flex-wrap:wrap; max-width:600px; margin:0 auto;">
			<?php foreach ( $keywords as $kw ) : ?>
				<span style="background:var(--ame-color-cream); color:var(--ame-color-navy); border:1px solid var(--ame-color-border); padding:0.25rem 0.75rem; border-radius:15px; font-size:0.75rem; font-weight:700;"><?php echo esc_html( $kw ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}
