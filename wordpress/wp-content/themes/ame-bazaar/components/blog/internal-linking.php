<?php
/**
 * Internal linking widget for blog posts.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brand_name  = ame_bazaar_get_brand_name();
$maps_url    = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$phone       = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$cat_men     = get_theme_mod( 'ame_bazaar_cat_men_url', '#' );
$cat_women   = get_theme_mod( 'ame_bazaar_cat_women_url', '#' );
$cat_kids    = get_theme_mod( 'ame_bazaar_cat_kids_url', '#' );
$cat_acc     = get_theme_mod( 'ame_bazaar_cat_accessories_url', '#' );
?>

<div class="ame-blog-internal-linking-box">
	<h4 class="ame-internal-linking-title"><?php printf( __( 'Visit %s in Kirari, Delhi', 'ame-bazaar' ), esc_html( $brand_name ) ); ?></h4>
	<p class="ame-internal-linking-text">
		<?php printf( __( 'Looking for high-quality, affordable family fashion coordinates? Explore our specialized collection coordinates including <a href="%1$s">Men\'s Wear</a>, <a href="%2$s">Women\'s Wear</a>, <a href="%3$s">Kids\' Wear</a>, and traditional <a href="%2$s">Sarees</a> directly in-store on Mubarakpur Road. We also provide custom <a href="%4$s">tailoring and alterations services</a> to guarantee a flawless, comfortable fit.', 'ame-bazaar' ),
			esc_url( $cat_men ),
			esc_url( $cat_women ),
			esc_url( $cat_kids ),
			esc_url( home_url( '/#ame-about-business-title' ) )
		); ?>
	</p>
	<div class="ame-internal-linking-actions">
		<a href="<?php echo esc_url( $maps_url ); ?>" class="ame-btn ame-btn-primary" target="_blank" rel="noopener noreferrer">
			<svg class="ame-icon-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
				<path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
			</svg>
			<span><?php esc_html_e( 'Get Directions to Store', 'ame-bazaar' ); ?></span>
		</a>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="ame-btn ame-btn-secondary" aria-label="Call AME Bazaar phone number">
			<svg class="ame-icon-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
			</svg>
			<span><?php esc_html_e( 'Call Store Assistant', 'ame-bazaar' ); ?></span>
		</a>
	</div>
</div>
