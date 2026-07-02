<?php
/**
 * Custom WooCommerce Checkout Page Template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is required and not logged in, stop.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="ame-checkout-page-layout">
	<!-- Progress bar step indicator -->
	<div class="ame-checkout-steps-bar">
		<div class="ame-step ame-step-done"><span>1</span> <?php esc_html_e( 'Shopping Cart', 'ame-bazaar' ); ?></div>
		<div class="ame-step-line ame-step-line-active"></div>
		<div class="ame-step ame-step-active"><span>2</span> <?php esc_html_e( 'Checkout Details', 'ame-bazaar' ); ?></div>
		<div class="ame-step-line"></div>
		<div class="ame-step"><span>3</span> <?php esc_html_e( 'Order Complete', 'ame-bazaar' ); ?></div>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		<div class="ame-checkout-grid">
			
			<!-- Left Column: Billing, Shipping & Details -->
			<div class="ame-checkout-details-column">
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2" style="margin-top: 2rem;">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div>

			<!-- Right Column: Sticky Order Summary & Payment Gateway -->
			<div class="ame-checkout-summary-column">
				<div class="ame-checkout-summary-card">
					<h3 id="order_review_heading" class="ame-checkout-section-title"><?php esc_html_e( 'Your Order Summary', 'ame-bazaar' ); ?></h3>
					
					<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
					
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
			</div>

		</div>
	</form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
