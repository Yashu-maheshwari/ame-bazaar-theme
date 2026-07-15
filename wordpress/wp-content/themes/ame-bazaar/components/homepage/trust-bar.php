<?php
/**
 * Trust Bar / Local Trust & AI Authority section template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve dynamic parameters from central Business Settings
$store_name      = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
$reviews_rating  = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
$reviews_count   = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
$city            = ame_bazaar_get_business_setting( 'city', 'Kirari' );
$state           = ame_bazaar_get_business_setting( 'state', 'Delhi' );
$postal_code     = ame_bazaar_get_business_setting( 'postal_code', '110086' );
$hours           = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );
$whatsapp        = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
$clean_wa        = preg_replace( '/[^0-9]/', '', $whatsapp );
$whatsapp_url    = 'https://wa.me/' . $clean_wa . '?text=' . rawurlencode( 'Hello ' . $store_name . ', I would like to place an order.' );

$maps_url        = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$gbp_url         = ame_bazaar_get_business_setting( 'gbp_url', '#' );
$review_url      = ame_bazaar_get_business_setting( 'google_review_url', '#' );
$review_link     = '#' !== $review_url ? $review_url : ( '#' !== $gbp_url ? $gbp_url : $maps_url );

$tailoring_avail = ame_bazaar_get_business_setting( 'tailoring_available', 'yes' );
$parking_avail   = ame_bazaar_get_business_setting( 'parking_available', 'yes' );
$parking_info    = ame_bazaar_get_business_setting( 'parking_info', 'Free street parking available' );

// Format Hours nicely for display
$display_hours = 'Daily: 09:00 AM – 10:00 PM';
if ( strpos( $hours, '09:00' ) !== false && strpos( $hours, '22:00' ) !== false ) {
	$display_hours = 'Daily: 09:00 AM – 10:00 PM';
} else {
	$display_hours = $hours;
}
?>

<section class="ame-trust-section" aria-label="<?php esc_attr_e( 'Local Trust & Business Authority', 'ame-bazaar' ); ?>">
	<div class="ame-bazaar-container">
		
		<!-- Trust Grid -->
		<div class="ame-trust-grid">
			
			<!-- Item 1: Google Reviews -->
			<a href="<?php echo esc_url( $review_link ); ?>" target="_blank" rel="noopener noreferrer" class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon star-icon" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title"><?php echo esc_html( $reviews_count ); ?>+ Google Reviews</span>
					<span class="ame-trust-card-desc"><?php echo esc_html( sprintf( __( 'Rated %s Stars by local shoppers', 'ame-bazaar' ), $reviews_rating ) ); ?></span>
				</div>
			</a>

			<!-- Item 2: Location -->
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title"><?php echo esc_html( $city ) . ', ' . esc_html( $state ); ?></span>
					<span class="ame-trust-card-desc"><?php echo esc_html( sprintf( __( 'Mubarakpur Road - %s', 'ame-bazaar' ), $postal_code ) ); ?></span>
				</div>
			</a>

			<!-- Item 3: Family Clothing Store -->
			<div class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title"><?php esc_html_e( 'Family Clothing Store', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-card-desc"><?php esc_html_e( 'Outfits for all generations', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<!-- Item 4: Tailoring & Alteration -->
			<a href="<?php echo esc_url( home_url( '/tailoring-near-me/' ) ); ?>" class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title">
						<?php echo 'yes' === $tailoring_avail ? esc_html__( 'Tailoring & Alterations', 'ame-bazaar' ) : esc_html__( 'Custom Fitting Support', 'ame-bazaar' ); ?>
					</span>
					<span class="ame-trust-card-desc"><?php esc_html_e( 'On-site adjustments & styling', 'ame-bazaar' ); ?></span>
				</div>
			</a>

			<!-- Item 5: Parking Available -->
			<div class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<rect x="1" y="3" width="22" height="13" rx="2" ry="2"></rect><path d="M12 8H9v5h3a2.5 2.5 0 0 0 0-5z"></path>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title">
						<?php echo 'yes' === $parking_avail ? esc_html__( 'Parking Available', 'ame-bazaar' ) : esc_html__( 'Convenient Access', 'ame-bazaar' ); ?>
					</span>
					<span class="ame-trust-card-desc"><?php echo esc_html( $parking_info ); ?></span>
				</div>
			</div>

			<!-- Item 6: WhatsApp Ordering -->
			<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title" style="display: inline-flex; align-items: center; gap: 4px;">
						<span><?php esc_html_e( 'WhatsApp Ordering', 'ame-bazaar' ); ?></span>
						<svg class="ame-inline-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px; height:14px; transition:transform 0.2s;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</span>
					<span class="ame-trust-card-desc"><?php esc_html_e( 'Quick order placement & chat', 'ame-bazaar' ); ?></span>
				</div>
			</a>

			<!-- Item 7: Quality Affordable Fashion -->
			<div class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title"><?php esc_html_e( 'Affordable Quality', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-card-desc"><?php esc_html_e( 'Premium apparel at weaver rates', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<!-- Item 8: Store Timings -->
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="ame-trust-card">
				<div class="ame-trust-card-icon-wrapper">
					<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
					</svg>
				</div>
				<div class="ame-trust-card-text">
					<span class="ame-trust-card-title"><?php esc_html_e( 'Store Business Hours', 'ame-bazaar' ); ?></span>
					<span class="ame-trust-card-desc"><?php echo esc_html( $display_hours ); ?></span>
				</div>
			</a>

		</div>

		<!-- Compact AI Authority Badge Row -->
		<div class="ame-trust-authority-row">
			<span class="ame-authority-badge">
				<svg class="ame-badge-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<span><?php esc_html_e( 'Serving Families since inception', 'ame-bazaar' ); ?></span>
			</span>
			<span class="ame-authority-badge">
				<svg class="ame-badge-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<span><?php esc_html_e( 'Affordable Quality Clothing', 'ame-bazaar' ); ?></span>
			</span>
			<span class="ame-authority-badge">
				<svg class="ame-badge-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<span><?php esc_html_e( 'Men • Women • Boys • Girls', 'ame-bazaar' ); ?></span>
			</span>
			<span class="ame-authority-badge">
				<svg class="ame-badge-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<span><?php echo esc_html( sprintf( __( 'Local %s Showroom', 'ame-bazaar' ), $city ) ); ?></span>
			</span>
			<span class="ame-authority-badge">
				<svg class="ame-badge-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<span><?php echo esc_html( sprintf( __( 'Trusted by %s+ Local Customers', 'ame-bazaar' ), $reviews_count ) ); ?></span>
			</span>
		</div>

	</div>
</section>
