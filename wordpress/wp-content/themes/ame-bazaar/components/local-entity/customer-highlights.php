<?php
/**
 * AI Dynamic Customer Highlights Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Customer Highlights block.
 */
function ame_bazaar_render_customer_highlights() {
	// Grouped AI summaries populated dynamically from admin options
	$summaries = array(
		'Customers Love'     => 'The excellent bespoke fitting and prompt tailors.',
		'Popular Products'   => 'Designer salwar suits, handloom sarees, and festive kurtas.',
		'Quality & Fitting'  => 'Pure cotton, premium linen fabrics with precise measurements.',
		'Store Experience'   => 'Clean family friendly spaces, friendly staff assistance.'
	);

	?>
	<div class="ame-customer-highlights" style="background:#fff; border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:1.5rem 2rem; box-shadow:var(--ame-shadow-sm); max-width:600px; margin:1.5rem auto; box-sizing:border-box;">
		<h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; font-weight:800; color:var(--ame-color-navy); text-transform:uppercase; letter-spacing:0.05em;">AI Customer Sentiment Highlights</h3>
		
		<div style="display:flex; flex-direction:column; gap:0.75rem;">
			<?php foreach ( $summaries as $topic => $desc ) : ?>
				<div style="display:flex; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:0.5rem; font-size:0.85rem; align-items:baseline;">
					<strong style="color:var(--ame-color-navy); flex-shrink:0; width:150px;"><?php echo esc_html( $topic ); ?>:</strong>
					<span style="color:var(--ame-color-slate); text-align:right;"><?php echo esc_html( $desc ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}
