<?php
/**
 * Template Name: Contact Page Template
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Fetch dynamic GBP business settings
$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$email = ame_bazaar_get_business_setting( 'email', 'info@amebazaar.in' );
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
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$maps_embed_url = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3498.4239855581177!2d77.06202477524956!3d28.736798075608388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390daf29e0000001%3A0x6335a79bf6bb094e!2sAME%20Bazaar!5e0!3m2!1sen!2sin!4v1719920000000!5m2!1sen!2sin';
?>

<main id="primary" class="site-main ame-contact-page-main" role="main" style="background: #fafaf9; padding-bottom: 5rem;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<!-- Hero Header -->
	<header class="ame-contact-hero-header" style="background: var(--ame-color-navy); color: #ffffff; padding: 5rem 0; border-bottom: 3px solid var(--ame-color-gold); text-align: center;">
		<div class="ame-bazaar-container">
			<span style="background: rgba(255,255,255,0.1); color: var(--ame-color-gold); padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1.25rem;">
				<?php esc_html_e( 'Connect With Us', 'ame-bazaar' ); ?>
			</span>
			<h1 class="entry-title" style="font-size: clamp(2.25rem, 5vw, 3.5rem); font-weight: 800; margin: 0 0 1rem; letter-spacing: -0.02em;"><?php esc_html_e( 'Contact Our Store', 'ame-bazaar' ); ?></h1>
			<p style="max-width: 650px; margin-inline: auto; font-size: 1.15rem; opacity: 0.9; line-height: 1.6;"><?php esc_html_e( 'Get directions, check timings, call our coordinators, or request custom tailoring quotes.', 'ame-bazaar' ); ?></p>
		</div>
	</header>

	<div class="ame-bazaar-container" style="margin-top: 4rem;">
		<div class="ame-contact-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; max-width: 1050px; margin-inline: auto;">
			
			<!-- Left Column: Details -->
			<div class="ame-contact-info-panel" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 2.5rem 2rem; box-shadow: var(--ame-shadow-sm); display: flex; flex-direction: column; gap: 2rem;">
				<h2 style="font-size: 1.5rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.5rem; border-bottom: 2px solid var(--ame-color-gold); padding-bottom: 0.5rem;"><?php esc_html_e( 'Store Location & Timings', 'ame-bazaar' ); ?></h2>
				
				<!-- Items -->
				<div style="display: flex; flex-direction: column; gap: 1.5rem;">
					
					<!-- Location -->
					<div style="display: flex; gap: 1rem; align-items: flex-start;">
						<div style="background: var(--ame-color-cream); color: var(--ame-color-navy); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
						</div>
						<div>
							<span style="display: block; font-weight: 700; color: var(--ame-color-navy); font-size: 0.95rem; margin-bottom: 0.25rem;"><?php esc_html_e( 'Store Address', 'ame-bazaar' ); ?></span>
							<address style="font-style: normal; color: #475569; font-size: 0.95rem; line-height: 1.5;"><?php echo esc_html( $address ); ?></address>
							<span style="font-size: 0.85rem; color: #64748b; display: block; margin-top: 0.25rem; font-weight: 600;">(Landmark: near Chappan Bhog sweet shop)</span>
						</div>
					</div>

					<!-- Timings -->
					<div style="display: flex; gap: 1rem; align-items: flex-start;">
						<div style="background: var(--ame-color-cream); color: var(--ame-color-navy); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
						</div>
						<div>
							<span style="display: block; font-weight: 700; color: var(--ame-color-navy); font-size: 0.95rem; margin-bottom: 0.25rem;"><?php esc_html_e( 'Store Timings', 'ame-bazaar' ); ?></span>
							<span style="color: #475569; font-size: 0.95rem; line-height: 1.5; display: block;"><?php echo esc_html( $hours ); ?></span>
							<span style="font-size: 0.85rem; color: #22c55e; display: block; margin-top: 0.25rem; font-weight: 700;">Open Daily (Mo - Su)</span>
						</div>
					</div>

					<!-- Phone -->
					<div style="display: flex; gap: 1rem; align-items: flex-start;">
						<div style="background: var(--ame-color-cream); color: var(--ame-color-navy); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
						</div>
						<div>
							<span style="display: block; font-weight: 700; color: var(--ame-color-navy); font-size: 0.95rem; margin-bottom: 0.25rem;"><?php esc_html_e( 'Call Store Head', 'ame-bazaar' ); ?></span>
							<span style="color: #475569; font-size: 0.95rem; line-height: 1.5; display: block; font-weight: bold;"><?php echo esc_html( $phone_number ); ?></span>
						</div>
					</div>

					<!-- Email -->
					<div style="display: flex; gap: 1rem; align-items: flex-start;">
						<div style="background: var(--ame-color-cream); color: var(--ame-color-navy); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
						</div>
						<div>
							<span style="display: block; font-weight: 700; color: var(--ame-color-navy); font-size: 0.95rem; margin-bottom: 0.25rem;"><?php esc_html_e( 'Email Support', 'ame-bazaar' ); ?></span>
							<span style="color: #475569; font-size: 0.95rem; line-height: 1.5; display: block;"><?php echo esc_html( $email ); ?></span>
						</div>
					</div>

				</div>

				<!-- Quick CTAs -->
				<div style="display: flex; flex-direction: column; gap: 0.75rem; border-top: 1px solid var(--ame-color-border); padding-top: 1.5rem; margin-top: 1rem;">
					<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary" style="display: flex; justify-content: center;">
						<?php esc_html_e( 'Navigate in Google Maps', 'ame-bazaar' ); ?>
					</a>
					<?php 
					$whatsapp = ame_bazaar_get_business_setting( 'whatsapp' );
					if ( $whatsapp ) : 
						$whatsapp_tel = preg_replace( '/[^0-9]/', '', $whatsapp );
					?>
						<a href="https://wa.me/<?php echo esc_attr( $whatsapp_tel ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn" style="background-color: #25D366; color: white; border: 1px solid #25D366; display: flex; justify-content: center; align-items: center; gap: 0.5rem; text-decoration: none; padding: 0.9rem 1.4rem; border-radius: 999px; font-weight: 600; font-size: 0.95rem; transition: all 0.2s ease;">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentColor;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
							<span><?php esc_html_e( 'Chat on WhatsApp', 'ame-bazaar' ); ?></span>
						</a>
					<?php endif; ?>
				</div>

			</div>

			<!-- Right Column: Interactive Map Embed -->
			<div class="ame-contact-map-panel" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 1.5rem; box-shadow: var(--ame-shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
				<div class="ame-contact-map-wrap" style="border-radius: var(--ame-radius-sm); overflow: hidden; border: 1px solid var(--ame-color-border);">
					<iframe src="<?php echo esc_url( $maps_embed_url ); ?>" 
							width="100%" 
							height="400" 
							style="border:0; display: block;" 
							allowfullscreen="" 
							loading="lazy" 
							referrerpolicy="no-referrer-when-downgrade"
							title="<?php esc_attr_e( 'AME Bazaar Location Map', 'ame-bazaar' ); ?>">
					</iframe>
				</div>
				<div style="margin-top: 1.5rem; color: #475569; font-size: 0.9rem; line-height: 1.6;">
					<h3 style="font-size: 1.15rem; font-weight: 700; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.5rem;"><?php esc_html_e( 'How to Find Us:', 'ame-bazaar' ); ?></h3>
					<p style="margin: 0;">
						<?php esc_html_e( 'We are situated directly on the main Mubarakpur Road, a short walk from the Chappan Bhog sweet house. Roadside car and scooter parking is available free of charge directly in front of our gate.', 'ame-bazaar' ); ?>
					</p>
				</div>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
