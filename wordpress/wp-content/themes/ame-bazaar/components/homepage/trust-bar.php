<?php
/**
 * Trust Bar section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reviews_rating = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
$reviews_count  = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
?>
<section class="ame-trust-bar-section" aria-label="<?php esc_attr_e( 'Trust indicators', 'ame-bazaar' ); ?>">
	<div class="ame-bazaar-container">
		<div class="ame-trust-bar-grid">
			
			<div class="ame-trust-bar-item">
				<div class="ame-trust-bar-icon-wrap">
					<svg class="ame-trust-bar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
					</svg>
				</div>
				<div class="ame-trust-bar-text">
					<span class="ame-trust-bar-title"><?php echo esc_html( $reviews_count ); ?>+<?php esc_html_e( ' Google Reviews', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-bar-desc"><?php echo esc_html( sprintf( __( 'Rated %s Stars by locals', 'ame-bazaar' ), $reviews_rating ) ); ?></span>
				</div>
			</div>

			<div class="ame-trust-bar-item">
				<div class="ame-trust-bar-icon-wrap">
					<svg class="ame-trust-bar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
					</svg>
				</div>
				<div class="ame-trust-bar-text">
					<span class="ame-trust-bar-title"><?php esc_html_e( 'Quality Products', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-bar-desc"><?php esc_html_e( 'Handpicked premium textiles', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<div class="ame-trust-bar-item">
				<div class="ame-trust-bar-icon-wrap">
					<svg class="ame-trust-bar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line>
					</svg>
				</div>
				<div class="ame-trust-bar-text">
					<span class="ame-trust-bar-title"><?php esc_html_e( 'Tailoring Available', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-bar-desc"><?php esc_html_e( 'Custom fitting & alterations', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<div class="ame-trust-bar-item">
				<div class="ame-trust-bar-icon-wrap">
					<svg class="ame-trust-bar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
				<div class="ame-trust-bar-text">
					<span class="ame-trust-bar-title"><?php esc_html_e( 'Family Fashion Store', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-bar-desc"><?php esc_html_e( 'Outfits for all generations', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<div class="ame-trust-bar-item">
				<div class="ame-trust-bar-icon-wrap">
					<svg class="ame-trust-bar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
					</svg>
				</div>
				<div class="ame-trust-bar-text">
					<span class="ame-trust-bar-title"><?php esc_html_e( 'Local Delhi Store', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-bar-desc"><?php esc_html_e( 'Conveniently in Kirari, Delhi', 'ame-bazaar' ); ?></span>
				</div>
			</div>

		</div>
	</div>
</section>
