<?php
/**
 * FAQ Section — Homepage Component
 *
 * Shows 8 key questions in a two-column premium accordion.
 * Pulls from the faq-data knowledge base — first 8 questions only.
 * PHP 5.6+ compatible. UTF-8 without BOM.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Pull first 8 homepage-worthy FAQs from the knowledge base
$all_faqs = function_exists( 'ame_bazaar_get_knowledge_base_faqs' )
	? ame_bazaar_get_knowledge_base_faqs()
	: array();

// Curated 8 for homepage display — pull one from each major category
$homepage_faqs = array(
	array(
		'q' => __( 'What clothing collections are available at AME Bazaar?', 'ame-bazaar' ),
		'a' => __( 'We stock Men\'s ethnic and western wear, Women\'s sarees, kurtis, and salwar suits, Kids\' clothing, and accessories — all under one roof in Kirari, Delhi.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Do you offer custom tailoring and alterations?', 'ame-bazaar' ),
		'a' => __( 'Yes. Our in-store tailoring desk offers 30-minute basic alterations and full custom stitching (salwar suits, sherwanis, blouses) in 2–3 days. Alterations are free on purchases above ₹500.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'What are your store timings?', 'ame-bazaar' ),
		'a' => __( 'AME Bazaar is open daily from 9:00 AM to 10:00 PM — seven days a week, including all public holidays.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Do you have festive wear for Diwali and weddings?', 'ame-bazaar' ),
		'a' => __( 'Yes — we carry an extensive range of embroidered sherwanis, silk sarees, lehengas, and designer kurtis for every festive occasion. New festive collections arrive 6–8 weeks before every major festival.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Can I order online or get home delivery?', 'ame-bazaar' ),
		'a' => __( 'You can browse and order on our website. For local Delhi orders, WhatsApp us for same-day or next-day delivery. Pan-India orders are dispatched via courier within 2–4 business days.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'What is your return and exchange policy?', 'ame-bazaar' ),
		'a' => __( 'Unused items with tags intact are exchangeable within 7 days. Custom stitched garments are non-refundable but we offer one free alteration if the fit isn\'t perfect.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Do you carry plus-size clothing?', 'ame-bazaar' ),
		'a' => __( 'Yes. We stock up to 4XL in most women\'s and men\'s categories. Our tailoring service can also custom-stitch any garment to your exact measurements.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Where exactly is the AME Bazaar store located?', 'ame-bazaar' ),
		'a' => __( 'We are located on Mubarakpur Road, Kirari, Delhi — 110086. Free parking is available. Search "AME Bazaar" on Google Maps for exact directions.', 'ame-bazaar' ),
	),
);

$faqs_page_url = home_url( '/faqs/' );
?>

<section class="ame-faq-section" aria-labelledby="ame-faq-title">
	<div class="ame-bazaar-container">

		<div class="ame-faq-header">
			<span class="ame-section-eyebrow"><?php esc_html_e( 'Common Questions', 'ame-bazaar' ); ?></span>
			<h2 id="ame-faq-title" class="ame-section-title"><?php esc_html_e( 'Everything You Need to Know', 'ame-bazaar' ); ?></h2>
			<p class="ame-section-sub"><?php esc_html_e( 'Quick answers to the most common questions about AME Bazaar.', 'ame-bazaar' ); ?></p>
		</div>

		<div class="ame-faq-grid" id="ame-faq-grid">
			<?php foreach ( $homepage_faqs as $idx => $faq ) :
				$faq_id = 'ame-faq-' . $idx;
				$panel_id = 'ame-faq-panel-' . $idx;
			?>
			<div class="ame-faq-item" id="<?php echo esc_attr( $faq_id . '-wrap' ); ?>">
				<button
					class="ame-faq-question"
					id="<?php echo esc_attr( $faq_id ); ?>"
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				>
					<span class="ame-faq-question-text"><?php echo esc_html( $faq['q'] ); ?></span>
					<span class="ame-faq-icon" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M3 6l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</button>
				<div
					class="ame-faq-answer"
					id="<?php echo esc_attr( $panel_id ); ?>"
					role="region"
					aria-labelledby="<?php echo esc_attr( $faq_id ); ?>"
					hidden
				>
					<p><?php echo esc_html( $faq['a'] ); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="ame-faq-footer">
			<p class="ame-faq-footer-text"><?php esc_html_e( 'Have a more specific question?', 'ame-bazaar' ); ?></p>
			<a href="<?php echo esc_url( $faqs_page_url ); ?>" class="ame-section-btn ame-section-btn--outline">
				<?php esc_html_e( 'View All FAQs', 'ame-bazaar' ); ?>
				<svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
					<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
				</svg>
			</a>
		</div>

	</div>
</section>

<script id="ame-faq-js">
/* AME FAQ Accordion — keyboard accessible, ARIA-compliant */
(function () {
	'use strict';
	var grid = document.getElementById('ame-faq-grid');
	if ( !grid ) return;

	var btns = grid.querySelectorAll('.ame-faq-question');

	btns.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var expanded = this.getAttribute('aria-expanded') === 'true';
			var panel    = document.getElementById( this.getAttribute('aria-controls') );

			/* Close all others */
			btns.forEach( function ( b ) {
				var p = document.getElementById( b.getAttribute('aria-controls') );
				b.setAttribute( 'aria-expanded', 'false' );
				b.classList.remove('is-open');
				if ( p ) p.hidden = true;
			} );

			/* Toggle clicked */
			if ( !expanded ) {
				this.setAttribute( 'aria-expanded', 'true' );
				this.classList.add('is-open');
				if ( panel ) panel.hidden = false;
			}
		} );
	} );
})();
</script>
