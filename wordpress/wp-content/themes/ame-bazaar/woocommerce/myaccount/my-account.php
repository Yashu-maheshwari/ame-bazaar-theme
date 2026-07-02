<?php
/**
 * Custom WooCommerce My Account Wrapper Template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="ame-my-account-layout">
	<div class="ame-bazaar-container">
		<div class="ame-my-account-grid">
			
			<!-- Left Navigation Menu -->
			<nav class="ame-my-account-nav-column" aria-label="Customer Account Navigation">
				<?php
				/**
				 * My Account navigation hook.
				 *
				 * @hooked woocommerce_account_navigation - 10
				 */
				do_action( 'woocommerce_account_navigation' );
				?>
			</nav>

			<!-- Right Content Block -->
			<main class="ame-my-account-content-column" id="ame-my-account-main-content">
				<div class="ame-my-account-card">
					<?php
					/**
					 * My Account content hook.
					 *
					 * @hooked woocommerce_account_content - 10
					 */
					do_action( 'woocommerce_account_content' );
					?>
				</div>
			</main>

		</div>
	</div>
</div>
