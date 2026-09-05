<?php
/**
 * The footer for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php do_action( 'ame_bazaar_before_footer' ); ?>
<?php
$footer_bg_id = get_option( 'ame_bazaar_media_footer_bg' );
$footer_bg_style = '';
if ( $footer_bg_id ) {
	$footer_bg_url = wp_get_attachment_image_url( $footer_bg_id, 'full' );
	if ( $footer_bg_url ) {
		$footer_bg_style = ' style="background-image: linear-gradient(rgba(0,35,71,0.95), rgba(0,35,71,0.95)), url(' . esc_url( $footer_bg_url ) . '); background-size: cover; background-position: center;"';
	}
}
?>
<footer id="colophon" class="site-footer ame-bazaar-site-footer" role="contentinfo"<?php echo $footer_bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="ame-bazaar-container">
		<?php do_action( 'ame_bazaar_footer' ); ?>
		<nav class="ame-bazaar-social-links" aria-label="Social media">
			<a class="ame-bazaar-social-link ame-bazaar-social-link--facebook" href="https://www.facebook.com/AMETTBAZAAR" target="_blank" rel="noopener noreferrer" aria-label="Follow AME Bazaar on Facebook">
				<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="width:20px;height:20px;max-width:20px;max-height:20px;flex:0 0 20px;display:block;"><path d="M14 8h3V4h-3c-3.314 0-5 1.686-5 5v3H6v4h3v4h4v-4h3l1-4h-4V9c0-.552.448-1 1-1Z" fill="currentColor"/></svg>
				<span>Facebook</span>
			</a>
			<a class="ame-bazaar-social-link ame-bazaar-social-link--instagram" href="https://www.instagram.com/ame_bazaar/" target="_blank" rel="noopener noreferrer" aria-label="Follow AME Bazaar on Instagram">
				<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="width:20px;height:20px;max-width:20px;max-height:20px;flex:0 0 20px;display:block;"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>
				<span>Instagram</span>
			</a>
		</nav>
	</div>
</footer>
<?php do_action( 'ame_bazaar_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
