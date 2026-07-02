<?php
/**
 * Component: Local Entity Tailoring Status
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$entity_type = get_post_meta( get_the_ID(), 'ame_local_entity_type', true );
$registry    = ame_bazaar_get_entity_registry();

if ( ! $entity_type || ! isset( $registry[ $entity_type ] ) || ! $registry[ $entity_type ]['has_tailor'] ) {
	return;
}
?>
<div class="ame-local-card ame-local-tailoring-card" style="border-left: 4px solid var(--ame-color-gold);">
	<h3 class="ame-local-card-title" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0.5rem;">
		<?php esc_html_e( 'Alterations Available', 'ame-bazaar' ); ?>
	</h3>
	<p style="font-size: 0.85rem; margin: 0; color: var(--ame-color-slate);">
		<?php esc_html_e( 'Professional custom fitting, tailoring adjustments, and apparel alterations are available directly in-store for all collections.', 'ame-bazaar' ); ?>
	</p>
</div>
