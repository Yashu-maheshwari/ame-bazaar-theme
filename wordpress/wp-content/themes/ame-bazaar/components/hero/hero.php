<?php
/**
 * Hero section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve customizer settings with defaults
$hero_title = get_theme_mod( 'ame_bazaar_hero_title', 'Affordable Fashion for Every Family in Kirari, Delhi' );
$hero_subtitle = get_theme_mod( 'ame_bazaar_hero_subtitle', "Men's Wear • Women's Wear • Kids Wear • Accessories" );
$hero_image = get_theme_mod( 'ame_bazaar_hero_image' );

if ( ! $hero_image ) {
	// Fallback to local copied image
	$hero_image = ame_bazaar_asset_uri( 'assets/images/hero-placeholder.jpg' );
}

$phone_number = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section class="ame-hero" aria-label="<?php esc_attr_e( 'Introduction', 'ame-bazaar' ); ?>">
	<div class="ame-bazaar-container ame-hero-inner">
		
		<!-- Hero Content Left -->
		<div class="ame-hero-content">
			
			<!-- Main Headline (SEO optimized h1, only one per page) -->
			<h1 class="ame-hero-headline"><?php echo esc_html( $hero_title ); ?></h1>
			
			<!-- Supporting Subheadline -->
			<p class="ame-hero-subheadline"><?php echo esc_html( $hero_subtitle ); ?></p>
			
			<!-- Calls to Action -->
			<div class="ame-hero-ctas">
				<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary ame-hero-btn-visit" aria-label="<?php esc_attr_e( 'Visit Store (opens Google Maps)', 'ame-bazaar' ); ?>">
					<svg class="ame-hero-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
				</a>
				
				<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-hero-btn-call" aria-label="<?php echo esc_attr( sprintf( __( 'Call Now: %s', 'ame-bazaar' ), $phone_number ) ); ?>">
					<svg class="ame-hero-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
					</svg>
					<span><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
				</a>
			</div>

			<!-- Trust Row Section -->
			<div class="ame-hero-trust-row">
				<div class="ame-trust-item">
					<svg class="ame-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
					<span class="ame-trust-text"><?php esc_html_e( 'Family-owned business', 'ame-bazaar' ); ?></span>
				</div>
				<div class="ame-trust-item">
					<svg class="ame-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
					<span class="ame-trust-text"><?php esc_html_e( 'Affordable Fashion', 'ame-bazaar' ); ?></span>
				</div>
				<div class="ame-trust-item">
					<svg class="ame-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
					<span class="ame-trust-text"><?php esc_html_e( 'Tailoring Available', 'ame-bazaar' ); ?></span>
				</div>
				<div class="ame-trust-item">
					<svg class="ame-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
					<span class="ame-trust-text"><?php esc_html_e( '4.8+ Google Rating', 'ame-bazaar' ); ?></span>
				</div>
			</div>

		</div>

		<!-- Hero Visual Right -->
		<div class="ame-hero-visual">
			<!-- LCP Optimized: loading eager, fetchpriority high -->
			<img src="<?php echo esc_url( $hero_image ); ?>" 
				 alt="<?php esc_attr_e( 'Premium Fashion Store Front - AME Bazaar Kirari, Delhi', 'ame-bazaar' ); ?>" 
				 class="ame-hero-img" 
				 loading="eager" 
				 fetchpriority="high">
		</div>

	</div>
</section>
