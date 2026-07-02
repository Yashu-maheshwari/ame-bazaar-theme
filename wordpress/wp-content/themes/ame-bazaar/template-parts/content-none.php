<?php
/**
 * Content not found template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="no-results not-found ame-bazaar-card">
	<div class="ame-bazaar-card__body">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'ame-bazaar' ); ?></h1>
		</header>

		<div class="page-content">
			<p><?php esc_html_e( 'Try searching for something else or return to the homepage.', 'ame-bazaar' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>
</section>
