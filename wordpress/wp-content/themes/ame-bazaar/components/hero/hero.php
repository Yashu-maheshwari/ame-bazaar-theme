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

$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section class="ame-zara-hero">
	
	<!-- Full Bleed Image Canvas -->
	<div class="ame-zara-hero__bg">
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
					'class'         => 'ame-zara-hero__img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'alt'           => esc_html__( 'AME Bazaar Premium Collection', 'ame-bazaar' ),
				) ); 
				?>
			</picture>
		<?php } else { ?>
			<!-- Fallback intentionally blank to force Media Manager usage -->
			<div class="ame-zara-hero__fallback"></div>
		<?php } ?>
	</div>

	<!-- Absolute Minimalist Content Overlay -->
	<div class="ame-zara-hero__content">
		<h1 class="ame-zara-hero__title">
			THE COLLECTION
		</h1>
		
		<div class="ame-zara-hero__actions">
			<a href="#categories" class="ame-zara-btn">
				<?php esc_html_e( 'SHOP NOW', 'ame-bazaar' ); ?>
			</a>
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-zara-btn">
				<?php esc_html_e( 'VISIT STORE', 'ame-bazaar' ); ?>
			</a>
		</div>
	</div>

</section>
