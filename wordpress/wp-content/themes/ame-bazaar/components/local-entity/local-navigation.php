<?php
/**
 * Component: Local Entity Cross Navigation Sidebar Menu
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$links_html = ame_bazaar_get_local_entity_links_html();
if ( ! $links_html ) {
	return;
}
?>
<div class="ame-local-card ame-local-navigation-card">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Our Local Directory', 'ame-bazaar' ); ?></h3>
	<?php echo $links_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
