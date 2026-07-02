<?php
/**
 * 404 Page template for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$brand_name   = ame_bazaar_get_brand_name();
$phone        = get_theme_mod( 'ame_bazaar_phone', '+91 99999 99999' );
$maps_url     = get_theme_mod( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$cat_men_url  = get_theme_mod( 'ame_bazaar_cat_men_url', '#' );
$cat_women_url= get_theme_mod( 'ame_bazaar_cat_women_url', '#' );
$cat_kids_url = get_theme_mod( 'ame_bazaar_cat_kids_url', '#' );
$cat_acc_url  = get_theme_mod( 'ame_bazaar_cat_accessories_url', '#' );
?>

<main id="primary" class="site-main" role="main">
	<div class="ame-404-wrapper">
		<div class="ame-bazaar-container">
			
			<div class="ame-404-layout">
				<div class="ame-404-content">
					<span class="ame-404-badge"><?php esc_html_e( 'Error 404', 'ame-bazaar' ); ?></span>
					<h1 class="ame-404-title"><?php esc_html_e( 'Looking for Something in Our Collections?', 'ame-bazaar' ); ?></h1>
					<p class="ame-404-text"><?php esc_html_e( 'The page you requested is unavailable or has been relocated. Use the search bar below, or check out our popular family fashion departments.', 'ame-bazaar' ); ?></p>
					
					<!-- Search form wrapper -->
					<div class="ame-404-search-box">
						<?php get_search_form(); ?>
					</div>
					
					<!-- Action Buttons -->
					<div class="ame-404-actions">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ame-btn ame-btn-primary">
							<?php esc_html_e( 'Return to Homepage', 'ame-bazaar' ); ?>
						</a>
						<a href="<?php echo esc_url( $maps_url ); ?>" class="ame-btn ame-btn-secondary" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Get Store Directions', 'ame-bazaar' ); ?>
						</a>
					</div>
				</div>

				<div class="ame-404-departments">
					<h2 class="ame-404-departments-title"><?php esc_html_e( 'Shop Apparel Departments', 'ame-bazaar' ); ?></h2>
					<ul class="ame-404-dept-list">
						<li>
							<a href="<?php echo esc_url( $cat_men_url ); ?>" class="ame-404-dept-link">
								<span><?php esc_html_e( 'Men\'s Wear Collection', 'ame-bazaar' ); ?></span>
								<span class="ame-dept-arrow">&rarr;</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( $cat_women_url ); ?>" class="ame-404-dept-link">
								<span><?php esc_html_e( 'Women\'s Wear Collection', 'ame-bazaar' ); ?></span>
								<span class="ame-dept-arrow">&rarr;</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( $cat_kids_url ); ?>" class="ame-404-dept-link">
								<span><?php esc_html_e( 'Kids\' Wear Collection', 'ame-bazaar' ); ?></span>
								<span class="ame-dept-arrow">&rarr;</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( $cat_women_url ); ?>" class="ame-404-dept-link">
								<span><?php esc_html_e( 'Sarees Collection', 'ame-bazaar' ); ?></span>
								<span class="ame-dept-arrow">&rarr;</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( $cat_acc_url ); ?>" class="ame-404-dept-link">
								<span><?php esc_html_e( 'Fashion Accessories', 'ame-bazaar' ); ?></span>
								<span class="ame-dept-arrow">&rarr;</span>
							</a>
						</li>
					</ul>
					
					<div class="ame-404-help-box">
						<h3 class="ame-404-help-title"><?php esc_html_e( 'Need Personal Assistance?', 'ame-bazaar' ); ?></h3>
						<p class="ame-404-help-desc"><?php printf( __( 'Speak directly with our store representative to locate items, check availability, or consult alterations sizes.', 'ame-bazaar' ), esc_html( $brand_name ) ); ?></p>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="ame-404-call-link">
							<svg class="ame-contact-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
							</svg>
							<span><?php echo esc_html( $phone ); ?></span>
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
