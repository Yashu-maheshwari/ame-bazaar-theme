<?php
/**
 * Visit Our Store section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99999 99999' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$street = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
$city = ame_bazaar_get_business_setting( 'city', 'Kirari' );
$state = ame_bazaar_get_business_setting( 'state', 'Delhi' );
$zip = ame_bazaar_get_business_setting( 'postal_code', '110086' );

// Build address cleanly without duplicates
$address = $street;
if ( stripos( $street, $city ) === false ) {
	$address .= ', ' . $city;
}
if ( stripos( $street, $state ) === false ) {
	$address .= ', ' . $state;
}
if ( stripos( $street, $zip ) === false ) {
	$address .= ' - ' . $zip;
}

$hours = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );
?>

<section class="ame-visit-store-section" aria-labelledby="ame-visit-store-title">
	<div class="ame-bazaar-container">
		<div class="ame-visit-store-grid">
			
			<!-- Left: Location Details and CTAs -->
			<div class="ame-visit-store-details">
				
				<?php 
				$logo_id = get_option( 'ame_bazaar_media_primary_logo' );
				if ( $logo_id ) : 
				?>
					<div class="ame-visit-logo-wrap" style="margin-bottom: 1.5rem; max-width: 140px;">
						<?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'ame-visit-store-logo', 'style' => 'width: 100%; height: auto; display: block;' ) ); ?>
					</div>
				<?php endif; ?>

				<h2 id="ame-visit-store-title" class="ame-visit-store-heading"><?php esc_html_e( 'Visit Our Store', 'ame-bazaar' ); ?></h2>
				
				<?php 
				$rating_val = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
				$review_count = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
				if ( $rating_val ) : 
				?>
					<div class="ame-visit-rating-badge" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
						<div class="ame-stars" style="display: flex; color: #fbbf24; gap: 2px;">
							<?php for ( $i = 0; $i < 5; $i++ ) : ?>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
							<?php endfor; ?>
						</div>
						<span style="font-size: 0.9rem; font-weight: 600; color: #475569;"><?php echo esc_html( $rating_val ); ?> / 5 (<?php echo esc_html( $review_count ); ?>+ Reviews)</span>
					</div>
				<?php endif; ?>

				<p class="ame-visit-store-intro"><?php esc_html_e( 'Come shop our premium clothing collections in person. Experience quality fabrics and get custom tailoring assistance.', 'ame-bazaar' ); ?></p>
				
				<?php 
				$customizer_url = get_theme_mod( 'ame_bazaar_img_visit' );
				$img_html = '';

				if ( ! empty( $customizer_url ) ) {
					$custom_visit_id = attachment_url_to_postid( $customizer_url );
					if ( $custom_visit_id ) {
						$img_html = wp_get_attachment_image( $custom_visit_id, 'medium_large', false, array(
							'class' => 'ame-visit-store-banner-img',
							'style' => 'width: 100%; height: auto; display: block;',
						) );
					} else {
						$img_html = '<img src="' . esc_url( $customizer_url ) . '" class="ame-visit-store-banner-img" style="width: 100%; height: auto; display: block;" loading="lazy" alt="' . esc_attr__( 'Visit AME Bazaar Mubarakpur Road Store - Kirari, Delhi', 'ame-bazaar' ) . '" />';
					}
				} else {
					$visit_img_id = get_option( 'ame_bazaar_media_visit_store' ) ?: 547;
					if ( $visit_img_id ) {
						$img_html = wp_get_attachment_image( $visit_img_id, 'medium_large', false, array(
							'class'   => 'ame-visit-store-banner-img',
							'style'   => 'width: 100%; height: auto; display: block;',
							'loading' => 'lazy',
							'alt'     => esc_attr__( 'Visit AME Bazaar Mubarakpur Road Store - Kirari, Delhi', 'ame-bazaar' ),
						) );
					}
				}

				if ( $img_html ) {
					echo '<div class="ame-visit-store-banner-wrapper" style="margin-bottom: 2rem; border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-sm);">';
					echo $img_html;
					echo '</div>';
				}
				?>
				
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

					<nav class="ame-bazaar-social-links" aria-label="Social media" style="display:flex;align-items:center;gap:0.75rem;margin:0.25rem 0 0.75rem 0;">
						<a class="ame-bazaar-social-link ame-bazaar-social-link--facebook" href="https://www.facebook.com/AMETTBAZAAR" target="_blank" rel="noopener noreferrer" aria-label="Follow AME Bazaar on Facebook" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;">
							<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="width:20px;height:20px;max-width:20px;max-height:20px;display:block;"><path d="M14 8h3V4h-3c-3.314 0-5 1.686-5 5v3H6v4h3v4h4v-4h3l1-4h-4V9c0-.552.448-1 1-1Z" fill="currentColor"/></svg>
						</a>
						<a class="ame-bazaar-social-link ame-bazaar-social-link--instagram" href="https://www.instagram.com/ame_bazaar/" target="_blank" rel="noopener noreferrer" aria-label="Follow AME Bazaar on Instagram" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;">
							<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="width:20px;height:20px;max-width:20px;max-height:20px;display:block;"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>
						</a>
					</nav>

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

				<div class="ame-visit-ctas" style="margin-top: 2rem;">
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
					<?php 
					$whatsapp = ame_bazaar_get_business_setting( 'whatsapp' );
					if ( $whatsapp ) : 
						$whatsapp_tel = preg_replace( '/[^0-9]/', '', $whatsapp );
					?>
						<a href="https://wa.me/<?php echo esc_attr( $whatsapp_tel ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn" style="background-color: #25D366; color: white; border: 1px solid #25D366; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: var(--ame-radius-sm); font-weight: 600; font-size: 0.95rem; transition: all 0.2s ease;">
							<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="stroke: currentColor;">
								<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
							</svg>
							<span><?php esc_html_e( 'WhatsApp', 'ame-bazaar' ); ?></span>
						</a>
					<?php endif; ?>
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