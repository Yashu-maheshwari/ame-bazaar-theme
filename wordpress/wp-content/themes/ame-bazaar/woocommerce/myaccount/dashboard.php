<?php
/**
 * Custom My Account Dashboard template override.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="ame-account-dashboard-wrapper">
	<header class="ame-account-dashboard-header">
		<h2 class="ame-account-dashboard-title">
			<?php
			/* translators: 1: user display name */
			printf(
				esc_html__( 'Hello, %1$s!', 'woocommerce' ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
			);
			?>
		</h2>
		<p class="ame-account-dashboard-intro">
			<?php
			/* translators: 1: logout url */
			printf(
				wp_kses( __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ), $allowed_html ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</header>

	<!-- Quick Links Grid Cards -->
	<div class="ame-grid ame-grid-3" style="margin-top: 2rem;">
		
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="ame-account-card-shortcut">
			<div class="ame-shortcut-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
			</div>
			<span class="ame-shortcut-title"><?php esc_html_e( 'Recent Orders', 'ame-bazaar' ); ?></span>
			<span class="ame-shortcut-desc"><?php esc_html_e( 'Track active shipments & view receipts', 'ame-bazaar' ); ?></span>
		</a>

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="ame-account-card-shortcut">
			<div class="ame-shortcut-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
			</div>
			<span class="ame-shortcut-title"><?php esc_html_e( 'Addresses', 'ame-bazaar' ); ?></span>
			<span class="ame-shortcut-desc"><?php esc_html_e( 'Billing & shipping coordinates', 'ame-bazaar' ); ?></span>
		</a>

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="ame-account-card-shortcut">
			<div class="ame-shortcut-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</div>
			<span class="ame-shortcut-title"><?php esc_html_e( 'Account details', 'ame-bazaar' ); ?></span>
			<span class="ame-shortcut-desc"><?php esc_html_e( 'Password management & profile', 'ame-bazaar' ); ?></span>
		</a>

	</div>
</div>
<?php
/**
 * Deprecated dashboard hook.
 */
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
