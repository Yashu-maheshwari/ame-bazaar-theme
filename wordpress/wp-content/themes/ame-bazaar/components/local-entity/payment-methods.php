<?php
/**
 * Component: Local Entity Payment Methods
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ame-local-card ame-local-payment-methods-card">
	<h3 class="ame-local-card-title"><?php esc_html_e( 'Accepted Payments', 'ame-bazaar' ); ?></h3>
	<ul class="ame-local-card-list">
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Cash payment', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">✓</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'UPI (GPay / PhonePe / Paytm)', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">✓</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Credit / Debit Cards', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">✓</span>
		</li>
		<li>
			<span class="ame-local-card-list-lbl"><?php esc_html_e( 'Mobile Wallets', 'ame-bazaar' ); ?></span>
			<span class="ame-local-card-list-val">✓</span>
		</li>
	</ul>
</div>
