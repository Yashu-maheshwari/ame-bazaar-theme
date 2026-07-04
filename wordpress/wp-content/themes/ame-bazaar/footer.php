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
	</div>
</footer>
<?php do_action( 'ame_bazaar_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
