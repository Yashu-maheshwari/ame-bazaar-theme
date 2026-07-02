<?php
/**
 * Front page template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main ame-bazaar-home" role="main">
	<?php do_action( 'ame_bazaar_homepage' ); ?>
</main>
<?php
get_footer();
