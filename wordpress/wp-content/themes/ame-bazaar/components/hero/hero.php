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

// Query hero banner IDs from Homepage Media Manager options
$hero_desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$hero_mobile_id = get_option( 'ame_bazaar_media_hero_mobile' );

// Fallback to customizer settings if options empty
if ( ! $hero_desktop_id ) {
	$hero_desktop_id = get_theme_mod( 'ame_bazaar_hero_image_id' ) ?: ame_bazaar_get_attachment_id_by_slug( 'hero-banner-image' );
}

// Override with manually selected Customizer image mapping if defined
$custom_hero_url = get_theme_mod( 'ame_bazaar_img_hero' );
if ( ! empty( $custom_hero_url ) ) {
	$custom_hero_id = attachment_url_to_postid( $custom_hero_url );
	if ( $custom_hero_id ) {
		$hero_desktop_id = $custom_hero_id;
	} else {
		// Set to 0 but store URL to fallback in the HTML section
		$hero_desktop_id = 0;
	}
}


$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
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
				<a href="#categories" class="ame-bazaar-btn ame-bazaar-btn--primary ame-hero-btn-shop" aria-label="<?php esc_attr_e( 'Shop Collection', 'ame-bazaar' ); ?>">
					<svg class="ame-hero-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
					</svg>
					<span><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
				</a>
				
				<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-hero-btn-visit" aria-label="<?php esc_attr_e( 'Visit Store (opens Google Maps)', 'ame-bazaar' ); ?>">
					<svg class="ame-hero-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
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
					<?php $reviews_rating = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' ); ?>
					<span class="ame-trust-text"><?php echo esc_html( $reviews_rating ); ?>+<?php esc_html_e( ' Google Rating', 'ame-bazaar' ); ?></span>
				</div>
			</div>

		</div>

		<!-- Hero Visual Right -->
		<div class="ame-hero-visual">
			<?php
			if ( $hero_desktop_id ) {
				$mobile_srcset = $hero_mobile_id ? wp_get_attachment_image_url( $hero_mobile_id, 'full' ) : wp_get_attachment_image_url( $hero_desktop_id, 'large' );
				$desktop_srcset = wp_get_attachment_image_url( $hero_desktop_id, 'full' );
				?>
				<picture>
					<?php if ( $mobile_srcset ) : ?>
						<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_srcset ); ?>">
					<?php endif; ?>
					<?php 
					echo wp_get_attachment_image( $hero_desktop_id, 'full', false, array(
						'class'         => 'ame-hero-img',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'alt'           => esc_html__( 'Premium Fashion Store Front - AME Bazaar Kirari, Delhi', 'ame-bazaar' ),
					) ); 
					?>
				</picture>
				<?php
			} else {
				$fallback_url = get_theme_mod( 'ame_bazaar_img_hero' ) ?: get_theme_mod( 'ame_bazaar_hero_image', ame_bazaar_asset_uri( 'assets/images/hero-lifestyle.png' ) );
				echo '<img src="' . esc_url( $fallback_url ) . '" alt="' . esc_attr__( 'Premium Fashion Store Front - AME Bazaar Kirari, Delhi', 'ame-bazaar' ) . '" class="ame-hero-img" loading="eager" fetchpriority="high">';
			}
			?>
		</div>

	</div>
</section>
