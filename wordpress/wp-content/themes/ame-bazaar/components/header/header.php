<?php
/**
 * Header template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
// Strip spaces and special chars for tel link
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$logo_id = get_option( 'ame_bazaar_media_primary_logo' ) ?: get_theme_mod( 'custom_logo' );
?>

<!-- Main Sticky Navigation Header -->
<div class="ame-header-main-wrapper">
	<div class="ame-bazaar-container ame-header-inner">
		
		<!-- Left: Hamburger and Logo -->
		<div class="ame-header-left">
			<button class="ame-mobile-menu-toggle" id="ame-menu-toggle-btn" aria-expanded="false" aria-controls="ame-mobile-menu-drawer" aria-label="<?php esc_attr_e( 'Open Menu', 'ame-bazaar' ); ?>">
				<span class="ame-hamburger-line"></span>
				<span class="ame-hamburger-line"></span>
				<span class="ame-hamburger-line"></span>
			</button>

			<div class="ame-brand-logo-container">
				<?php
				if ( $logo_id ) {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="ame-logo-link" rel="home">';
					echo wp_get_attachment_image( $logo_id, 'full', false, array(
						'class'   => 'ame-logo-img',
						'loading' => 'lazy',
						'alt'     => esc_attr( ame_bazaar_get_brand_name() ),
					) );
					echo '</a>';
				} else {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="ame-logo-text" rel="home">';
					echo esc_html( ame_bazaar_get_brand_name() );
					echo '</a>';
				}
				?>
			</div>
		</div>

		<!-- Center: Primary Desktop Navigation -->
		<nav class="ame-desktop-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'ame-bazaar' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ame-desktop-menu-list',
					'depth'          => 2,
					'fallback_cb'    => false,
				) );
			} else {
				// Elegant fallback
				?>
				<ul class="ame-desktop-menu-list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/categories' ) ); ?>"><?php esc_html_e( 'Categories', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'ame-bazaar' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<!-- Right: Action Icons & Call Buttons -->
		<div class="ame-header-actions">
			<!-- Search Icon Button -->
			<button class="ame-action-btn ame-search-toggle" id="ame-search-open-btn" aria-label="<?php esc_attr_e( 'Search site', 'ame-bazaar' ); ?>">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</button>

			<?php
			$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
			$cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
			$my_account_id = function_exists( 'get_option' ) ? get_option('woocommerce_myaccount_page_id') : 0;
			$account_url = $my_account_id ? get_permalink( $my_account_id ) : '#';
			?>

			<!-- My Account Button -->
			<a href="<?php echo esc_url( $account_url ); ?>" class="ame-action-btn ame-account-link" aria-label="<?php esc_attr_e( 'My Account', 'ame-bazaar' ); ?>">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
				</svg>
			</a>

			<!-- Future Wishlist Placeholder -->
			<a href="#" class="ame-action-btn ame-wishlist-link" aria-label="<?php esc_attr_e( 'Wishlist (Future Integration)', 'ame-bazaar' ); ?>">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
				</svg>
			</a>

			<!-- Shopping Cart Button with Dynamic Badge -->
			<a href="<?php echo esc_url( $cart_url ); ?>" class="ame-action-btn ame-cart-link" aria-label="<?php esc_attr_e( 'Shopping Cart', 'ame-bazaar' ); ?>">
				<div class="ame-cart-icon-wrapper" style="position: relative; display: inline-flex;">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
					</svg>
					<span class="ame-cart-count" style="display: <?php echo $cart_count > 0 ? 'flex' : 'none'; ?>;"><?php echo esc_html( $cart_count ); ?></span>
				</div>
			</a>

			<!-- Call Now Button -->
			<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-btn-call" aria-label="<?php echo esc_attr( sprintf( __( 'Call Now: %s', 'ame-bazaar' ), $phone_number ) ); ?>">
				<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
				</svg>
				<span class="ame-btn-text"><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
			</a>

			<!-- Visit Store Button -->
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary ame-btn-visit" aria-label="<?php esc_attr_e( 'Visit Store (opens Google Maps)', 'ame-bazaar' ); ?>">
				<svg class="ame-icon-btn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
				</svg>
				<span class="ame-btn-text"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>
		</div>

	</div>
</div>

<!-- Mobile Off-Canvas Navigation Drawer -->
<div class="ame-mobile-drawer" id="ame-mobile-menu-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation Drawer', 'ame-bazaar' ); ?>">
	<div class="ame-mobile-drawer-inner">
		
		<!-- Drawer Header -->
		<div class="ame-mobile-drawer-header">
			<div class="ame-brand-logo-container">
				<?php if ( $logo_id ) : ?>
					<?php echo wp_get_attachment_image( $logo_id, 'full', false, array(
						'class'   => 'ame-logo-img',
						'loading' => 'lazy',
						'alt'     => esc_attr( ame_bazaar_get_brand_name() ),
					) ); ?>
				<?php else : ?>
					<span class="ame-logo-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
				<?php endif; ?>
			</div>
			
			<button class="ame-mobile-drawer-close-btn" id="ame-menu-close-btn" aria-label="<?php esc_attr_e( 'Close Menu', 'ame-bazaar' ); ?>">
				<svg class="ame-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<!-- Drawer Search -->
		<div class="ame-mobile-drawer-search">
			<form role="search" method="get" class="ame-mobile-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="ame-mobile-search-input"><?php esc_html_e( 'Search for:', 'ame-bazaar' ); ?></label>
				<input type="search" id="ame-mobile-search-input" class="ame-mobile-search-input" placeholder="<?php esc_attr_e( 'Search store...', 'ame-bazaar' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required />
				<button type="submit" class="ame-mobile-search-submit" aria-label="<?php esc_attr_e( 'Search', 'ame-bazaar' ); ?>">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</form>
		</div>

		<!-- Drawer Navigation List -->
		<nav class="ame-mobile-drawer-navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'ame-bazaar' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ame-mobile-menu-list',
					'depth'          => 2,
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<ul class="ame-mobile-menu-list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/categories' ) ); ?>"><?php esc_html_e( 'Categories', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'ame-bazaar' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<!-- Drawer Footer with CTAs -->
		<div class="ame-mobile-drawer-footer">
			<div class="ame-mobile-ctas">
				<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-btn-mobile-call">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
					</svg>
					<span><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
				</a>
				
				<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary ame-btn-mobile-visit">
					<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
				</a>
			</div>
		</div>

	</div>
	
	<!-- Overlay background for closing -->
	<div class="ame-mobile-drawer-overlay" id="ame-menu-overlay-bg" aria-hidden="true"></div>
</div>

<!-- Desktop Search Overlay -->
<div class="ame-search-overlay" id="ame-desktop-search-overlay" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Search site', 'ame-bazaar' ); ?>">
	<div class="ame-search-overlay-inner">
		<form role="search" method="get" class="ame-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="ame-search-input"><?php esc_html_e( 'Search for:', 'ame-bazaar' ); ?></label>
			<input type="search" id="ame-search-input" class="ame-search-input" placeholder="<?php esc_attr_e( 'Search store...', 'ame-bazaar' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required />
			<button type="submit" class="ame-search-submit" aria-label="<?php esc_attr_e( 'Search', 'ame-bazaar' ); ?>">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</button>
		</form>
		<button class="ame-search-close-btn" id="ame-search-close-btn" aria-label="<?php esc_attr_e( 'Close Search', 'ame-bazaar' ); ?>">
			<svg class="ame-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
	</div>
</div>
