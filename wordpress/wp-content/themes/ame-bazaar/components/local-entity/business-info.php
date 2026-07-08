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
$phone        = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$email        = ame_bazaar_get_business_setting( 'email', 'contact@amebazaar.com' );
$maps_url     = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp' );
$whatsapp_url = $whatsapp ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $whatsapp ) : '';

$street = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
$city   = ame_bazaar_get_business_setting( 'city', 'Kirari' );
$state  = ame_bazaar_get_business_setting( 'state', 'Delhi' );
$zip    = ame_bazaar_get_business_setting( 'postal_code', '110086' );

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
?>
<div class="ame-local-card ame-local-business-info-card">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Store Location & Info', 'ame-bazaar' ); ?></h3>
	<ul class="ame-local-card-list">
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Address:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">
				<?php echo esc_html( $address ); ?>
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
