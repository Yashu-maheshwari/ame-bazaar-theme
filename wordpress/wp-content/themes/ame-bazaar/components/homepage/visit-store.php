<?php
/**
 * Visit Our Store section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$address = get_theme_mod( 'ame_bazaar_address', 'Mubarakpur Road, Kirari, Delhi - 110086' );
$hours = get_theme_mod( 'ame_bazaar_hours', 'Monday - Sunday: 10:00 AM - 9:00 PM' );
?>

<section class="ame-visit-store-section" aria-labelledby="ame-visit-store-title">
	<div class="ame-bazaar-container">
		<div class="ame-visit-store-grid">
			
			<!-- Left: Location Details and CTAs -->
			<div class="ame-visit-store-details">
				<h2 id="ame-visit-store-title" class="ame-visit-store-heading"><?php esc_html_e( 'Visit Our Store', 'ame-bazaar' ); ?></h2>
				<p class="ame-visit-store-intro"><?php esc_html_e( 'Come shop our premium clothing collections in person. Experience quality fabrics and get custom tailoring assistance.', 'ame-bazaar' ); ?></p>
				
				<div class="ame-visit-info-list">
					<div class="ame-visit-info-item">
						<div class="ame-visit-info-icon-wrap">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label"><?php esc_html_e( 'Store Location', 'ame-bazaar' ); ?></span>
							<address class="ame-visit-info-val"><?php echo esc_html( $address ); ?></address>
						</div>
					</div>

					<div class="ame-visit-info-item">
						<div class="ame-visit-info-icon-wrap">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label"><?php esc_html_e( 'Business Hours', 'ame-bazaar' ); ?></span>
							<span class="ame-visit-info-val"><?php echo esc_html( $hours ); ?></span>
						</div>
					</div>

					<div class="ame-visit-info-item">
						<div class="ame-visit-info-icon-wrap">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label"><?php esc_html_e( 'Contact Number', 'ame-bazaar' ); ?></span>
							<span class="ame-visit-info-val"><?php echo esc_html( $phone_number ); ?></span>
						</div>
					</div>
				</div>

				<div class="ame-visit-ctas">
					<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary">
						<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
						</svg>
						<span><?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?></span>
					</a>
					<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary">
						<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
						</svg>
						<span><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
					</a>
				</div>
			</div>

			<!-- Right: Interactive Google Maps Iframe / Placeholder -->
			<div class="ame-visit-store-map">
				<div class="ame-map-container">
					<!-- AME Bazaar Google Maps Location iframe -->
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3498.4239855581177!2d77.06202477524956!3d28.736798075608388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390daf29e0000001%3A0x6335a79bf6bb094e!2sAME%20Bazaar!5e0!3m2!1sen!2sin!4v1719920000000!5m2!1sen!2sin" 
							width="100%" 
							height="450" 
							style="border:0;" 
							allowfullscreen="" 
							loading="lazy" 
							referrerpolicy="no-referrer-when-downgrade"
							title="<?php esc_attr_e( 'AME Bazaar Location Map', 'ame-bazaar' ); ?>">
					</iframe>
				</div>
			</div>

		</div>
	</div>
</section>
