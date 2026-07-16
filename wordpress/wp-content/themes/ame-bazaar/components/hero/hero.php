<?php
/**
 * Art-Directed Luxury Campaign Hero
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Media Manager acts as the sole source of truth for the campaign identity
$hero_desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$hero_mobile_id = get_option( 'ame_bazaar_media_hero_mobile' );

$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section class="ame-luxury-hero">
	
	<!-- Cinematic Campaign Canvas -->
	<figure class="ame-luxury-hero__canvas">
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
					'class'         => 'ame-luxury-hero__image',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'alt'           => esc_html__( 'AME Bazaar Premium Campaign', 'ame-bazaar' ),
				) ); 
				?>
			</picture>
		<?php } else { ?>
			<!-- Intentional void to enforce campaign imagery -->
			<div class="ame-luxury-hero__void"></div>
		<?php } ?>
	</figure>

	<!-- Editorial Typography Matrix -->
	<div class="ame-luxury-hero__editorial">
		<div class="ame-luxury-hero__season">
			<?php esc_html_e( 'FW / 2026', 'ame-bazaar' ); ?>
		</div>
		
		<h1 class="ame-luxury-hero__title">
			New<br>Elegance
		</h1>
		
		<div class="ame-luxury-hero__actions">
			<a href="#categories" class="ame-luxury-link">
				<?php esc_html_e( 'Explore Collection', 'ame-bazaar' ); ?>
			</a>
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-luxury-link">
				<?php esc_html_e( 'Visit Flagship', 'ame-bazaar' ); ?>
			</a>
		</div>
	</div>

</section>
