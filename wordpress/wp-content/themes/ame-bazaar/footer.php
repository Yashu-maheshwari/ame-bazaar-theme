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
<footer id="colophon" class="site-footer ame-bazaar-site-footer" role="contentinfo">
	<div class="ame-bazaar-container">
		<?php do_action( 'ame_bazaar_footer' ); ?>
	</div>
</footer>
<?php do_action( 'ame_bazaar_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
