<?php
/**
 * AME Bazaar footer component template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve customizer settings
$brand_name     = ame_bazaar_get_brand_name();
$about_text     = get_theme_mod( 'ame_bazaar_footer_about', 'Apparel Maheshwari Enterprises (AME Bazaar) offers premium fashion apparel for the entire family. Visit our store on Mubarakpur Road, Kirari, Delhi.' );
$phone          = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$whatsapp_url   = get_theme_mod( 'ame_bazaar_whatsapp_url', 'https://wa.me/919999999999?text=Hello%20AME%20Bazaar%2C%20I%20have%20an%20inquiry' );
$email          = get_theme_mod( 'ame_bazaar_email', 'contact@amebazaar.com' );
$maps_url       = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$facebook       = get_theme_mod( 'ame_bazaar_facebook_url', 'https://www.facebook.com/amebazaar' );
$instagram      = get_theme_mod( 'ame_bazaar_instagram_url', 'https://www.instagram.com/amebazaar' );
$hours          = get_theme_mod( 'ame_bazaar_store_hours', 'Mo-Su 09:00–22:00' );

// Formatting opening hours text nicely for humans
$display_hours = 'Mon - Sun: 09:00 AM – 10:00 PM';
if ( strpos( $hours, '09:00' ) !== false && strpos( $hours, '22:00' ) !== false ) {
	$display_hours = 'Daily: 09:00 AM – 10:00 PM';
}

// Categories links
$cat_men_url   = get_theme_mod( 'ame_bazaar_cat_men_url', '#' );
$cat_women_url = get_theme_mod( 'ame_bazaar_cat_women_url', '#' );
$cat_kids_url  = get_theme_mod( 'ame_bazaar_cat_kids_url', '#' );
$cat_acc_url   = get_theme_mod( 'ame_bazaar_cat_accessories_url', '#' );
?>

<div class="ame-footer-top-grid">
	
	<!-- Column 1: Brand & Bio -->
	<div class="ame-footer-col ame-footer-brand-col">
		<h3 class="ame-footer-logo-title"><?php echo esc_html( $brand_name ); ?></h3>
		<p class="ame-footer-bio"><?php echo esc_html( $about_text ); ?></p>
		
		<div class="ame-footer-hours-wrap">
			<span class="ame-footer-hours-label"><?php esc_html_e( 'Opening Hours', 'ame-bazaar' ); ?></span>
			<time class="ame-footer-hours-time"><?php echo esc_html( $display_hours ); ?></time>
		</div>

		<!-- Social links -->
		<div class="ame-footer-socials">
			<?php if ( $facebook && '#' !== $facebook ) : ?>
				<a href="<?php echo esc_url( $facebook ); ?>" class="ame-footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Visit AME Bazaar on Facebook">
					<svg class="ame-social-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
					</svg>
				</a>
			<?php endif; ?>
			<?php if ( $instagram && '#' !== $instagram ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" class="ame-footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Visit AME Bazaar on Instagram">
					<svg class="ame-social-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
						<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
						<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
					</svg>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<!-- Column 2: Shop Collections -->
	<div class="ame-footer-col">
		<h4 class="ame-footer-title"><?php esc_html_e( 'Shop Collections', 'ame-bazaar' ); ?></h4>
		<ul class="ame-footer-links">
			<li><a href="<?php echo esc_url( $cat_men_url ); ?>"><?php esc_html_e( 'Men\'s Wear', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( $cat_women_url ); ?>"><?php esc_html_e( 'Women\'s Wear', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( $cat_kids_url ); ?>"><?php esc_html_e( 'Kids\' Wear', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( $cat_women_url ); ?>"><?php esc_html_e( 'Sarees Collection', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( $cat_acc_url ); ?>"><?php esc_html_e( 'Accessories', 'ame-bazaar' ); ?></a></li>
		</ul>
	</div>

	<!-- Column 3: Quick Links -->
	<div class="ame-footer-col">
		<h4 class="ame-footer-title"><?php esc_html_e( 'Quick Links', 'ame-bazaar' ); ?></h4>
		<ul class="ame-footer-links">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a></li>
			<li><a href="#ame-about-business-title"><?php esc_html_e( 'About Store', 'ame-bazaar' ); ?></a></li>
			<li><a href="#ame-why-choose-title"><?php esc_html_e( 'Why Choose Us', 'ame-bazaar' ); ?></a></li>
			<li><a href="#ame-reviews-section-title"><?php esc_html_e( 'Customer Reviews', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Store Location Map', 'ame-bazaar' ); ?></a></li>
		</ul>
	</div>

	<!-- Column 4: Contact Info -->
	<div class="ame-footer-col ame-footer-contact-col">
		<h4 class="ame-footer-title"><?php esc_html_e( 'Contact Us', 'ame-bazaar' ); ?></h4>
		<p class="ame-footer-address">
			<strong><?php esc_html_e( 'Address:', 'ame-bazaar' ); ?></strong><br>
			<?php esc_html_e( 'Mubarakpur Road, Kirari, Delhi – 110086', 'ame-bazaar' ); ?>
		</p>
		
		<ul class="ame-footer-contact-links">
			<!-- Phone -->
			<li>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="ame-footer-contact-link-item" aria-label="Call store phone number">
					<svg class="ame-contact-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
					</svg>
					<span><?php echo esc_html( $phone ); ?></span>
				</a>
			</li>
			<!-- WhatsApp -->
			<?php if ( $whatsapp_url ) : ?>
				<li>
					<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="ame-footer-contact-link-item ame-whatsapp-link" target="_blank" rel="noopener noreferrer" aria-label="Chat with AME Bazaar on WhatsApp">
						<svg class="ame-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
						</svg>
						<span><?php esc_html_e( 'Chat on WhatsApp', 'ame-bazaar' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			<!-- Email -->
			<?php if ( $email ) : ?>
				<li>
					<a href="mailto:<?php echo esc_attr( $email ); ?>" class="ame-footer-contact-link-item" aria-label="Send email to AME Bazaar">
						<svg class="ame-contact-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
						</svg>
						<span><?php echo esc_html( $email ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>

</div>

<!-- Bottom Section: Legal & Copyright -->
<div class="ame-footer-bottom">
	<div class="ame-footer-copyright">
		<p>
			&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( $brand_name ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'ame-bazaar' ); ?>
		</p>
	</div>
	<div class="ame-footer-legal-links">
		<ul class="ame-footer-legal-list">
			<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'ame-bazaar' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>"><?php esc_html_e( 'Shipping & Returns', 'ame-bazaar' ); ?></a></li>
		</ul>
	</div>
</div>
