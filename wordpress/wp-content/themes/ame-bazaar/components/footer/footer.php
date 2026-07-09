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
$brand_name     = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
$about_text     = ame_bazaar_get_business_setting( 'short_description', 'Apparel Maheshwari Enterprises offers premium fashion apparel for the entire family. Visit our store on Mubarakpur Road, Kirari, Delhi.' );
$phone          = ame_bazaar_get_business_setting( 'phone', '+91 99999 99999' );
$whatsapp       = ame_bazaar_get_business_setting( 'whatsapp', '+91 99999 99999' );
$clean_wa       = preg_replace( '/[^0-9+]/', '', $whatsapp );
$whatsapp_url   = 'https://wa.me/' . ltrim( $clean_wa, '+' ) . '?text=Hello%20AME%20Bazaar%2C%20I%20have%20an%20inquiry';
$email          = ame_bazaar_get_business_setting( 'email', 'contact@amebazaar.com' );
$maps_url       = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$facebook       = ame_bazaar_get_business_setting( 'facebook', 'https://www.facebook.com/amebazaar' );
$instagram      = ame_bazaar_get_business_setting( 'instagram', 'https://www.instagram.com/amebazaar' );
$hours          = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );

// Formatting opening hours text nicely for humans
$display_hours = 'Mon - Sun: 09:00 AM – 10:00 PM';
if ( strpos( $hours, '09:00' ) !== false && strpos( $hours, '22:00' ) !== false ) {
	$display_hours = 'Daily: 09:00 AM – 10:00 PM';
}

// Categories links dynamic resolution
$cat_men_url   = get_theme_mod( 'ame_bazaar_cat_men_url' );
$cat_women_url = get_theme_mod( 'ame_bazaar_cat_women_url' );
$cat_kids_url  = get_theme_mod( 'ame_bazaar_cat_kids_url' );
$cat_sarees_url = get_theme_mod( 'ame_bazaar_cat_sarees_url' );
$cat_acc_url   = get_theme_mod( 'ame_bazaar_cat_accessories_url' );

// Resolve defaults if # or empty
if ( ! $cat_men_url || '#' === $cat_men_url ) {
	$term = get_term_by( 'slug', 'mens-wear', 'product_cat' ) ?: get_term_by( 'slug', 'men', 'product_cat' );
	$cat_men_url = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/product-category/mens-wear/' );
	if ( is_wp_error( $cat_men_url ) ) { $cat_men_url = home_url( '/product-category/mens-wear/' ); }
}
if ( ! $cat_women_url || '#' === $cat_women_url ) {
	$term = get_term_by( 'slug', 'womens-wear', 'product_cat' ) ?: get_term_by( 'slug', 'women', 'product_cat' );
	$cat_women_url = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/product-category/womens-wear/' );
	if ( is_wp_error( $cat_women_url ) ) { $cat_women_url = home_url( '/product-category/womens-wear/' ); }
}
if ( ! $cat_kids_url || '#' === $cat_kids_url ) {
	$term = get_term_by( 'slug', 'kids-wear', 'product_cat' ) ?: get_term_by( 'slug', 'kids', 'product_cat' );
	$cat_kids_url = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/product-category/kids/' );
	if ( is_wp_error( $cat_kids_url ) ) { $cat_kids_url = home_url( '/product-category/kids/' ); }
}
if ( ! $cat_sarees_url || '#' === $cat_sarees_url ) {
	$term = get_term_by( 'slug', 'sarees', 'product_cat' );
	$cat_sarees_url = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/product-category/sarees/' );
	if ( is_wp_error( $cat_sarees_url ) ) { $cat_sarees_url = home_url( '/product-category/sarees/' ); }
}
if ( ! $cat_acc_url || '#' === $cat_acc_url ) {
	$term = get_term_by( 'slug', 'accessories', 'product_cat' );
	$cat_acc_url = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/product-category/accessories/' );
	if ( is_wp_error( $cat_acc_url ) ) { $cat_acc_url = home_url( '/product-category/accessories/' ); }
}
?>

<div class="ame-footer-top-grid">
	
	<!-- Column 1: Brand & Bio -->
	<div class="ame-footer-col ame-footer-brand-col">
		<?php 
		$logo_id = get_option( 'ame_bazaar_media_primary_logo' );
		if ( $logo_id ) : 
		?>
			<div class="ame-footer-logo-wrap" style="margin-bottom: 1.25rem; max-width: 150px;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'ame-footer-logo', 'style' => 'width: 100%; height: auto; display: block;' ) ); ?>
				</a>
			</div>
		<?php else : ?>
			<h3 class="ame-footer-logo-title"><?php echo esc_html( $brand_name ); ?></h3>
		<?php endif; ?>
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
			<li><a href="<?php echo esc_url( $cat_sarees_url ); ?>"><?php esc_html_e( 'Sarees Collection', 'ame-bazaar' ); ?></a></li>
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
<div class="ame-footer-bottom" style="display:flex; flex-direction:column; gap:1.5rem;">
	<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1.5rem; border-bottom:1px solid var(--ame-color-border); padding-bottom:1.5rem; width:100%;">
		<div class="ame-footer-payment-icons" style="display:flex; gap:0.8rem; flex-wrap:wrap; align-items:center;">
			<span style="font-size:0.7rem; font-weight:700; color:var(--ame-color-slate); text-transform:uppercase; margin-right:0.4rem;"><?php esc_html_e( 'We Accept:', 'ame-bazaar' ); ?></span>
			<span class="ame-badge-new" style="font-size:0.65rem; background:#fff; color:#475569; border:1px solid #cbd5e1;">UPI</span>
			<span class="ame-badge-new" style="font-size:0.65rem; background:#fff; color:#475569; border:1px solid #cbd5e1;">RuPay</span>
			<span class="ame-badge-new" style="font-size:0.65rem; background:#fff; color:#475569; border:1px solid #cbd5e1;">Visa</span>
			<span class="ame-badge-new" style="font-size:0.65rem; background:#fff; color:#475569; border:1px solid #cbd5e1;">Mastercard</span>
			<span class="ame-badge-new" style="font-size:0.65rem; background:#fff; color:#475569; border:1px solid #cbd5e1;">COD</span>
		</div>
		<div class="ame-footer-security-badges" style="display:flex; gap:1rem; flex-wrap:wrap; font-size:0.75rem; font-weight:700; color:#64748b;">
			<span style="display:flex; align-items:center; gap:0.4rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px; height:12px; color:#16a34a;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
				Secure SSL Checkout
			</span>
			<span style="display:flex; align-items:center; gap:0.4rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px; height:12px; color:#ca8a04;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
				100% Original coordinates
			</span>
		</div>
	</div>
	
	<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; width:100%;">
		<div class="ame-footer-copyright">
			<p style="margin:0;">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( $brand_name ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'ame-bazaar' ); ?>
			</p>
		</div>
		<div class="ame-footer-legal-links">
			<ul class="ame-footer-legal-list" style="list-style:none; padding:0; margin:0; display:flex; gap:1.25rem; font-size:0.8rem;">
				<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>"><?php esc_html_e( 'Shipping & Returns', 'ame-bazaar' ); ?></a></li>
			</ul>
		</div>
	</div>
</div>
