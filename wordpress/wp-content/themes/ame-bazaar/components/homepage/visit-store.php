<?php
/**
 * Visit Our Store section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );

$whatsapp_number = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
$whatsapp_tel_link = preg_replace( '/[^0-9+]/', '', $whatsapp_number );

$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?cid=7148817758252271950' );
$gbp_url = ame_bazaar_get_business_setting( 'gbp_url', 'https://maps.google.com/?cid=7148817758252271950' );

$address = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road, near Chappan Bhog, Kirari Suleman Nagar, Delhi' ) . ' - ' . ame_bazaar_get_business_setting( 'postal_code', '110086' );
$hours = ame_bazaar_get_business_setting( 'hours', 'Monday to Sunday: 09:00 AM - 10:00 PM' );
$maps_embed_url = ame_bazaar_get_business_setting( 'maps_embed_url', '' );
?>

<section class="ame-visit-store-section" aria-labelledby="ame-visit-store-title" style="padding-block: var(--ame-section-padding); background: var(--ame-color-cream);">
	<div class="ame-bazaar-container">
		<div class="ame-visit-store-grid" style="display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center;">
			<?php if ( is_admin() || ! wp_is_mobile() ) : ?>
				<style>
					@media (min-width: 768px) {
						.ame-visit-store-grid {
							grid-template-columns: 1fr 1fr !important;
						}
					}
				</style>
			<?php endif; ?>
			
			<!-- Left: Location Details and CTAs -->
			<div class="ame-visit-store-details">
				<h2 id="ame-visit-store-title" class="ame-visit-store-heading" style="color: var(--ame-color-navy); margin-bottom: 1rem;"><?php esc_html_e( 'Visit Our Store', 'ame-bazaar' ); ?></h2>
				<p class="ame-visit-store-intro" style="color: var(--ame-color-slate); margin-bottom: 2rem;"><?php esc_html_e( 'Come shop our premium clothing collections in person. Experience quality fabrics and get custom tailoring assistance.', 'ame-bazaar' ); ?></p>
				
				<?php 
				$visit_img_id = get_option( 'ame_bazaar_media_visit_store' );
				if ( $visit_img_id ) {
					echo '<div class="ame-visit-store-banner-wrapper" style="margin-bottom: 2rem; border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-sm);">';
					echo wp_get_attachment_image( $visit_img_id, 'medium_large', false, array(
						'class'   => 'ame-visit-store-banner-img',
						'style'   => 'width: 100%; height: auto; display: block;',
						'loading' => 'lazy',
						'alt'     => esc_attr__( 'Visit AME Bazaar Mubarakpur Road Store - Kirari, Delhi', 'ame-bazaar' ),
					) );
					echo '</div>';
				}
				?>
				
				<div class="ame-visit-info-list" style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2rem;">
					<div class="ame-visit-info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
						<div class="ame-visit-info-icon-wrap" style="color: var(--ame-color-navy); padding-top: 2px;">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px;" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label" style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ame-color-slate);"><?php esc_html_e( 'Store Location', 'ame-bazaar' ); ?></span>
							<address class="ame-visit-info-val" style="font-style: normal; font-weight: 600; color: var(--ame-color-navy);"><?php echo esc_html( $address ); ?></address>
						</div>
					</div>

					<div class="ame-visit-info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
						<div class="ame-visit-info-icon-wrap" style="color: var(--ame-color-navy); padding-top: 2px;">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px;" aria-hidden="true">
								<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label" style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ame-color-slate);"><?php esc_html_e( 'Business Hours', 'ame-bazaar' ); ?></span>
							<span class="ame-visit-info-val" style="font-weight: 600; color: var(--ame-color-navy);"><?php echo esc_html( $hours ); ?></span>
						</div>
					</div>

					<div class="ame-visit-info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
						<div class="ame-visit-info-icon-wrap" style="color: var(--ame-color-navy); padding-top: 2px;">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px;" aria-hidden="true">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
						</div>
						<div class="ame-visit-info-text">
							<span class="ame-visit-info-label" style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ame-color-slate);"><?php esc_html_e( 'Contact Hotline', 'ame-bazaar' ); ?></span>
							<span class="ame-visit-info-val" style="font-weight: 600; color: var(--ame-color-navy);"><a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>"><?php echo esc_html( $phone_number ); ?></a></span>
						</div>
					</div>
				</div>

				<div class="ame-visit-ctas" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
					<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary" style="padding: 0.8rem 1rem; font-size: 0.9rem;">
						📍 <span><?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?></span>
					</a>
					<a href="<?php echo esc_url( $gbp_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary" style="padding: 0.8rem 1rem; font-size: 0.9rem; background: var(--ame-color-gold-dark);">
						🏪 <span><?php esc_html_e( 'Google Maps', 'ame-bazaar' ); ?></span>
					</a>
					<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary" style="padding: 0.8rem 1rem; font-size: 0.9rem; border-color: var(--ame-color-navy);">
						📞 <span><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
					</a>
					<a href="https://wa.me/<?php echo esc_attr( ltrim( $whatsapp_tel_link, '+' ) ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--secondary" style="padding: 0.8rem 1rem; font-size: 0.9rem; border-color: #25d366; color: #128c7e;">
						💬 <span><?php esc_html_e( 'WhatsApp', 'ame-bazaar' ); ?></span>
					</a>
				</div>
			</div>

			<!-- Right: Interactive Google Maps Iframe -->
			<div class="ame-visit-store-map">
				<div class="ame-map-container" style="border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-md);">
					<?php
					$embed_src = $maps_embed_url ?: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3498.4239855581177!2d77.06202477524956!3d28.736798075608388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390daf29e0000001%3A0x6335a79bf6bb094e!2sAME%20Bazaar!5e0!3m2!1sen!2sin!4v1719920000000!5m2!1sen!2sin';
					?>
					<iframe src="<?php echo esc_url( $embed_src ); ?>" 
							width="100%" 
							height="400" 
							style="border:0; display: block;" 
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
