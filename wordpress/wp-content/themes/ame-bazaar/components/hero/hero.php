<?php
/**
 * Ultra-Premium Cinematic Hero section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Lock architecture to Homepage Media Manager strictly
$hero_desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$hero_mobile_id = get_option( 'ame_bazaar_media_hero_mobile' );

// Retrieve customizer settings
$hero_title = get_theme_mod( 'ame_bazaar_hero_title', 'The Modern Family Collection' );
$hero_subtitle = get_theme_mod( 'ame_bazaar_hero_subtitle', 'Redefining elegance for everyday wear. Exclusively at Kirari, Delhi.' );

$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section class="ame-cinematic-hero" aria-label="<?php esc_attr_e( 'Premium Collection Introduction', 'ame-bazaar' ); ?>">
	
	<!-- Full Bleed Background Canvas -->
	<div class="ame-cinematic-hero__canvas">
		<?php
		if ( $hero_desktop_id ) {
			$mobile_srcset = $hero_mobile_id ? wp_get_attachment_image_url( $hero_mobile_id, 'full' ) : wp_get_attachment_image_url( $hero_desktop_id, 'large' );
			?>
			<picture>
				<?php if ( $mobile_srcset ) : ?>
					<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_srcset ); ?>">
				<?php endif; ?>
				<?php 
				echo wp_get_attachment_image( $hero_desktop_id, 'full', false, array(
					'class'         => 'ame-cinematic-hero__image',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'alt'           => esc_html__( 'AME Bazaar Premium Fashion Campaign', 'ame-bazaar' ),
				) ); 
				?>
			</picture>
		<?php } else { ?>
			<!-- Fallback intentionally unstyled to force Media Manager usage, but robust for errors -->
			<div style="width:100%; height:100vh; background:#e2e8f0;"></div>
		<?php } ?>
		
		<!-- Soft cinematic vignette shadow to anchor typography -->
		<div class="ame-cinematic-hero__vignette"></div>
	</div>

	<!-- Floating Glassmorphism Content Panel -->
	<div class="ame-cinematic-hero__glass-panel">
		
		<div class="ame-cinematic-hero__badge">
			<?php esc_html_e( 'Premium Fashion Destination', 'ame-bazaar' ); ?>
		</div>

		<h1 class="ame-cinematic-hero__title">
			<?php echo nl2br( esc_html( $hero_title ) ); ?>
		</h1>
		
		<p class="ame-cinematic-hero__subtitle">
			<?php echo esc_html( $hero_subtitle ); ?>
		</p>
		
		<div class="ame-cinematic-hero__actions">
			<a href="#categories" class="ame-btn-luxury ame-btn-luxury--primary" aria-label="<?php esc_attr_e( 'Discover the Collection', 'ame-bazaar' ); ?>">
				<span><?php esc_html_e( 'Discover', 'ame-bazaar' ); ?></span>
			</a>
			
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-btn-luxury ame-btn-luxury--secondary" aria-label="<?php esc_attr_e( 'Visit Store (opens Google Maps)', 'ame-bazaar' ); ?>">
				<span><?php esc_html_e( 'Visit Boutique', 'ame-bazaar' ); ?></span>
			</a>
		</div>
		
	</div>

</section>
