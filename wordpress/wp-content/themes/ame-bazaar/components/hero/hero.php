<?php
/**
 * Premium Hero section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve customizer settings with defaults
$hero_title = get_theme_mod( 'ame_bazaar_hero_title', 'Affordable Fashion for Every Family' );
$hero_subtitle = get_theme_mod( 'ame_bazaar_hero_subtitle', 'Premium quality • Kirari • Delhi' );

// Strict architectural lock to Homepage Media Manager
$hero_desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$hero_mobile_id = get_option( 'ame_bazaar_media_hero_mobile' );

$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section class="ame-hero-premium" aria-label="<?php esc_attr_e( 'Introduction', 'ame-bazaar' ); ?>">
	
	<!-- Edge-to-Edge Image Background / Right Sidebar -->
	<div class="ame-hero-premium__visual">
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
					'class'         => 'ame-hero-premium__img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'alt'           => esc_html__( 'Premium Fashion Collection - AME Bazaar Kirari, Delhi', 'ame-bazaar' ),
				) ); 
				?>
			</picture>
		<?php } else { ?>
			<img src="<?php echo esc_url( ame_bazaar_asset_uri( 'assets/images/hero-lifestyle.png' ) ); ?>" alt="<?php esc_attr_e( 'Premium Fashion Collection', 'ame-bazaar' ); ?>" class="ame-hero-premium__img" loading="eager" fetchpriority="high">
		<?php } ?>
		
		<!-- Subtle gradient overlay to ensure text legibility on mobile -->
		<div class="ame-hero-premium__overlay"></div>
	</div>

	<!-- Editorial Typography Content -->
	<div class="ame-hero-premium__content-wrapper">
		<div class="ame-hero-premium__content">
			
			<div class="ame-hero-premium__trust-badge">
				<?php esc_html_e( 'Trusted Family Fashion Store', 'ame-bazaar' ); ?>
			</div>

			<h1 class="ame-hero-premium__headline">
				<?php echo nl2br( esc_html( $hero_title ) ); ?>
			</h1>
			
			<p class="ame-hero-premium__subheadline">
				<?php echo esc_html( $hero_subtitle ); ?>
			</p>
			
			<div class="ame-hero-premium__ctas">
				<a href="#categories" class="ame-btn-premium ame-btn-premium--solid" aria-label="<?php esc_attr_e( 'Shop Collection', 'ame-bazaar' ); ?>">
					<span><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
				</a>
				
				<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-btn-premium ame-btn-premium--ghost" aria-label="<?php esc_attr_e( 'Visit Store (opens Google Maps)', 'ame-bazaar' ); ?>">
					<span><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
				</a>
			</div>
			
			<!-- Micro Trust Ticker -->
			<div class="ame-hero-premium__ticker">
				<span>EST. 2005</span> &bull; 
				<span>KIRARI, DELHI</span> &bull; 
				<span>4.9+ GOOGLE RATED</span>
			</div>
			
		</div>
	</div>

</section>
