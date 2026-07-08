<?php
/**
 * Phase 29A - Homepage High-ROI Conversion and Trust Improvements.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render lightweight top announcement bar.
 */
function ame_bazaar_render_announcement_bar() {
	$hours = ame_bazaar_get_business_setting( 'hours', 'Monday to Sunday: 09:00 AM - 10:00 PM' );
	$phone = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$whatsapp = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
	
	$clean_phone = preg_replace( '/[^0-9+]/', '', $phone );
	$clean_wa = preg_replace( '/[^0-9+]/', '', $whatsapp );
	
	// Open Daily text extraction
	$open_text = esc_html__( 'Open Daily', 'ame-bazaar' );
	if ( $hours ) {
		if ( preg_match( '/\d+:\d+\s*(?:AM|PM)\s*-\s*\d+:\d+\s*(?:AM|PM)/i', $hours, $matches ) ) {
			$open_text = sprintf( esc_html__( 'Open Daily (%s)', 'ame-bazaar' ), $matches[0] );
		}
	}
	?>
	<div class="ame-announcement-bar" style="background: var(--ame-color-navy); color: var(--ame-color-white); font-size: 0.8rem; padding: 0.6rem 1rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); z-index: 1000; position: relative; font-family: inherit;">
		<div class="ame-bazaar-container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
			<div class="announcement-left" style="font-weight: 600; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
				<span style="display: inline-flex; align-items: center; gap: 4px;">🛍️ <?php esc_html_e( 'Free 2-Hour Store Pickup', 'ame-bazaar' ); ?></span>
				<span class="bar-separator" style="opacity: 0.3;">|</span>
				<span style="display: inline-flex; align-items: center; gap: 4px;">🕒 <?php echo esc_html( $open_text ); ?></span>
			</div>
			<div class="announcement-right" style="display: flex; gap: 15px; align-items: center; font-weight: 700;">
				<a href="tel:<?php echo esc_attr( $clean_phone ); ?>" style="color: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
					📞 <?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?>
				</a>
				<a href="https://wa.me/<?php echo esc_attr( ltrim( $clean_wa, '+' ) ); ?>" target="_blank" rel="noopener noreferrer" style="color: #25d366; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
					💬 <?php esc_html_e( 'WhatsApp', 'ame-bazaar' ); ?>
				</a>
			</div>
		</div>
	</div>
	
	<style>
		@media (max-width: 600px) {
			.ame-announcement-bar .ame-bazaar-container {
				justify-content: center !important;
				gap: 5px !important;
			}
			.ame-announcement-bar .bar-separator {
				display: none !important;
			}
			.ame-announcement-bar .announcement-left {
				justify-content: center !important;
				width: 100% !important;
			}
		}
	</style>
	<?php
}
add_action( 'ame_bazaar_before_header', 'ame_bazaar_render_announcement_bar', 5 );
