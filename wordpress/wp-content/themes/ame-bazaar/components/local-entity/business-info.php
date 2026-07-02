<?php
/**
 * Component: Local Entity Business Info
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brand_name   = ame_bazaar_get_brand_name();
$phone        = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$email        = get_theme_mod( 'ame_bazaar_email', 'contact@amebazaar.com' );
$maps_url     = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$whatsapp_url = get_theme_mod( 'ame_bazaar_whatsapp_url', '' );

$street = get_theme_mod( 'ame_bazaar_street_address', 'Mubarakpur Road' );
$city   = get_theme_mod( 'ame_bazaar_locality', 'Kirari' );
$state  = get_theme_mod( 'ame_bazaar_region', 'Delhi' );
$zip    = get_theme_mod( 'ame_bazaar_postal_code', '110086' );
?>
<div class="ame-local-card ame-local-business-info-card">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Store Location & Info', 'ame-bazaar' ); ?></h3>
	<ul class="ame-local-card-list">
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Address:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">
				<?php echo esc_html( sprintf( '%s, %s, %s - %s', $street, $city, $state, $zip ) ); ?>
			</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Helpline:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Email:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</span>
		</li>
	</ul>
	
	<div class="ame-local-card-actions" style="margin-top: 1.25rem; display: flex; gap: 0.75rem;">
		<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary" style="padding: 0.6rem 1.1rem; font-size: 0.85rem; border-radius: var(--ame-radius-sm); text-align: center; flex: 1;">
			<?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?>
		</a>
		<?php if ( $whatsapp_url ) : ?>
			<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--secondary" style="padding: 0.6rem 1.1rem; font-size: 0.85rem; border-radius: var(--ame-radius-sm); text-align: center; flex: 1;">
				<?php esc_html_e( 'WhatsApp Us', 'ame-bazaar' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
