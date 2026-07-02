<?php
/**
 * Tailoring Service Section Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="ame-tailoring-service-section" aria-labelledby="ame-tailoring-title" style="padding-block: 5rem; background: var(--ame-color-cream);">
	<div class="ame-bazaar-container">
		<div class="ame-grid ame-grid-2" style="align-items: center; gap: 4rem;">
			
			<!-- Left: Description and Timelines -->
			<div class="ame-tailoring-content-col">
				<span class="ame-sg-label" style="color: var(--ame-color-gold-dark);"><?php esc_html_e( 'Master Alterations & Stitching', 'ame-bazaar' ); ?></span>
				<h2 id="ame-tailoring-title" class="ame-h2" style="margin-top: 0.5rem; margin-bottom: 1.5rem;"><?php esc_html_e( 'On-Site Custom Tailoring & Fittings', 'ame-bazaar' ); ?></h2>
				<p class="ame-body" style="color: var(--ame-color-slate); margin-bottom: 2rem;">
					<?php esc_html_e( 'Apparel Maheshwari Enterprises provides professional, precise on-site custom tailoring, measurement sizing adjustments, hem repair services, and styling options for all coordinates, sarees, and suits purchased at our Kirari store.', 'ame-bazaar' ); ?>
				</p>
				
				<div class="ame-tailoring-timelines-grid" style="display:flex; flex-direction:column; gap:1.25rem; margin-bottom:2.5rem;">
					<div style="display:flex; gap:1rem;">
						<div style="width:24px; height:24px; border-radius:50%; background:var(--ame-color-navy); color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">1</div>
						<div>
							<h4 style="margin:0 0 0.25rem 0; font-size:0.95rem; font-weight:700; color:var(--ame-color-navy);">30-Minute Alteration</h4>
							<p style="margin:0; font-size:0.85rem; color:var(--ame-color-slate);">Basic trouser hemming, shirt sleeve sizing, and waist alterations finished while you wait in-store.</p>
						</div>
					</div>
					<div style="display:flex; gap:1rem;">
						<div style="width:24px; height:24px; border-radius:50%; background:var(--ame-color-navy); color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">2</div>
						<div>
							<h4 style="margin:0 0 0.25rem 0; font-size:0.95rem; font-weight:700; color:var(--ame-color-navy);">Full Custom Stitching (2-3 Days)</h4>
							<p style="margin:0; font-size:0.85rem; color:var(--ame-color-slate);">Salwar suits, designer coordinates, blouse stitching, and custom saree fall & picot designs completed in record time.</p>
						</div>
					</div>
				</div>

				<div style="display:flex; gap:1rem; flex-wrap:wrap;">
					<a href="tel:+919999999999" class="ame-btn-primary"><?php esc_html_e( 'Book Fitting Now', 'ame-bazaar' ); ?></a>
					<a href="https://wa.me/919999999999?text=Hi%20I%20want%20to%20inquire%20about%20your%20tailoring%20services" class="ame-btn-secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'WhatsApp Stitching Enquiry', 'ame-bazaar' ); ?></a>
				</div>
			</div>

			<!-- Right: Mock Visual representation of tailoring tools -->
			<div class="ame-tailoring-visual-col" style="display:flex; justify-content:center;">
				<div style="background:var(--ame-color-white); border:1px solid var(--ame-color-border); padding:3rem; border-radius:var(--ame-radius-md); box-shadow:var(--ame-shadow-md); width:100%; max-width:400px; text-align:center;">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:64px; height:64px; color:var(--ame-color-gold-dark); margin-bottom:1.5rem;"><path d="M6 3h12l4 6-10 12L2 9z"></path></svg>
					<h3 style="margin:0 0 0.5rem 0; font-size:1.15rem; font-weight:800; color:var(--ame-color-navy);">Apparel Maheshwari Enterprises</h3>
					<p style="margin:0 0 1.5rem 0; font-size:0.85rem; color:var(--ame-color-slate);">Kirari Mubarakpur Road Stitching Desk</p>
					<div style="border-top:1px solid var(--ame-color-border); padding-top:1.5rem; display:flex; justify-content:space-around; font-size:0.8rem; font-weight:700; color:var(--ame-color-navy);">
						<span>Alterations: Free</span>
						<span>Full stitch: From ₹299</span>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
