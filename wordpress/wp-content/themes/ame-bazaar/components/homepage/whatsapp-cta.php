<?php
/**
 * WhatsApp & Newsletter CTA section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = ame_bazaar_get_business_setting( 'whatsapp' );
if ( ! $phone_number ) {
	$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
}
// Strip non-numbers except + for WhatsApp URL
$whatsapp_phone = preg_replace( '/[^0-9]/', '', $phone_number );
$whatsapp_url = 'https://wa.me/' . $whatsapp_phone . '?text=' . rawurlencode( 'Hello AME Bazaar! I would like to inquire about your custom tailoring services and fashion collections.' );
?>

<section class="ame-cta-section" aria-label="<?php esc_attr_e( 'Connect with us', 'ame-bazaar' ); ?>">
	<div class="ame-bazaar-container">
		<div class="ame-cta-card-wrapper">
			
			<div class="ame-cta-grid">
				
				<!-- Left Column: WhatsApp Connect -->
				<div class="ame-cta-col ame-cta-whatsapp-col">
					<div class="ame-cta-icon-badge">
						<svg class="ame-cta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
						</svg>
					</div>
					<h2 class="ame-cta-heading"><?php esc_html_e( 'Inquire on WhatsApp', 'ame-bazaar' ); ?></h2>
					<p class="ame-cta-desc"><?php esc_html_e( 'Have questions about sizing, pricing, or custom tailoring? Connect directly with us on WhatsApp for fast local assistance.', 'ame-bazaar' ); ?></p>
					
					<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary ame-btn-whatsapp">
						<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
						</svg>
						<span><?php esc_html_e( 'Chat on WhatsApp', 'ame-bazaar' ); ?></span>
					</a>
				</div>

				<!-- Right Column: Newsletter Signup -->
				<div class="ame-cta-col ame-cta-newsletter-col">
					<div class="ame-cta-icon-badge">
						<svg class="ame-cta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>
						</svg>
					</div>
					<h2 class="ame-cta-heading"><?php esc_html_e( 'Subscribe to Newsletter', 'ame-bazaar' ); ?></h2>
					<p class="ame-cta-desc"><?php esc_html_e( 'Receive seasonal fashion trends, exclusive discounts, and alerts when new fabric arrivals land at AME Bazaar.', 'ame-bazaar' ); ?></p>
					
					<form class="ame-cta-newsletter-form" action="#" method="post" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
						<label class="screen-reader-text" for="ame-cta-email"><?php esc_html_e( 'Email address', 'ame-bazaar' ); ?></label>
						<input type="email" id="ame-cta-email" class="ame-cta-email-input" placeholder="<?php esc_attr_e( 'Enter your email address...', 'ame-bazaar' ); ?>" required />
						<button type="submit" class="ame-bazaar-btn ame-bazaar-btn--primary ame-newsletter-submit">
							<span><?php esc_html_e( 'Subscribe', 'ame-bazaar' ); ?></span>
						</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</section>
