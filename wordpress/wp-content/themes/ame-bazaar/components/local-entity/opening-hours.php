<?php
/**
 * Component: Local Entity Opening Hours
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hours = get_theme_mod( 'ame_bazaar_store_hours', 'Daily 09:00 AM – 10:00 PM' );
?>
<div class="ame-local-card ame-local-opening-hours-card">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Opening Hours', 'ame-bazaar' ); ?></h3>
	<ul class="ame-local-card-list">
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Monday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Tuesday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Wednesday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Thursday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Friday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Saturday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Sunday:', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">09:00 AM – 10:00 PM</span>
		</li>
	</ul>
	<div style="margin-top: 1rem; border-top: 1px solid var(--ame-color-border); padding-top: 0.75rem; text-align: center; font-size: 0.85rem;">
		<span class="ame-local-hours-highlight"><?php esc_html_e( 'Open Today - Visit Store', 'ame-bazaar' ); ?></span>
	</div>
</div>
