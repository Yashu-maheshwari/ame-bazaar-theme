<?php
/**
 * Luxury Header template component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone_number = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$phone_tel_link = preg_replace( '/[^0-9+]/', '', $phone_number );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$logo_id = get_option( 'ame_bazaar_media_primary_logo' ) ?: get_theme_mod( 'custom_logo' );
?>

<!-- Minimalist Luxury Navigation Header (Desktop) -->
<div class="ame-header-luxury-wrapper hide-on-mobile">
	<div class="ame-bazaar-container ame-header-luxury-inner">
		
		<!-- Left: Hamburger & Desktop Navigation -->
		<div class="ame-header-luxury-left">
			<!-- Mobile Hamburger -->
			<button class="ame-luxury-menu-toggle" id="ame-menu-toggle-btn" aria-expanded="false" aria-controls="ame-mobile-menu-drawer" aria-label="<?php esc_attr_e( 'Open Menu', 'ame-bazaar' ); ?>">
				<span class="ame-hamburger-line"></span>
				<span class="ame-hamburger-line"></span>
				<span class="ame-hamburger-line"></span>
			</button>

			<!-- Desktop Nav (Left Aligned for Zara/COS feel) -->
			<nav class="ame-desktop-nav-luxury" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'ame-bazaar' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'ame-desktop-menu-luxury',
						'depth'          => 2,
						'fallback_cb'    => false,
					) );
				} else {
					?>
					<ul class="ame-desktop-menu-luxury">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/about-ame-bazaar/' ) ); ?>"><?php esc_html_e( 'About', 'ame-bazaar' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'ame-bazaar' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/fashion-advisor/' ) ); ?>"><?php esc_html_e( 'Fashion Advisor', 'ame-bazaar' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'ame-bazaar' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</nav>
		</div>

		<!-- Center: The One True Logo -->
		<div class="ame-header-luxury-center">
			<div class="ame-brand-logo-container">
				<?php
				if ( $logo_id ) {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="ame-logo-link" rel="home">';
					echo wp_get_attachment_image( $logo_id, 'full', false, array(
						'class'   => 'ame-logo-img',
						'loading' => 'eager', // Eager for LCP on header
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

		<!-- Right: Minimal Actions -->
		<div class="ame-header-luxury-right">
			<!-- Call Now Button -->
			<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-luxury-pill-btn ame-btn-call" aria-label="<?php echo esc_attr( sprintf( __( 'Call Now: %s', 'ame-bazaar' ), $phone_number ) ); ?>">
				<span class="ame-pill-text"><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></span>
			</a>

			<!-- Visit Store Button -->
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-luxury-pill-btn ame-btn-visit" aria-label="<?php esc_attr_e( 'Visit Store', 'ame-bazaar' ); ?>">
				<span class="ame-pill-text"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>

			<!-- Search -->
			<button class="ame-luxury-action-btn ame-search-toggle" id="ame-search-open-btn" aria-label="<?php esc_attr_e( 'Search', 'ame-bazaar' ); ?>">
				<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" aria-hidden="true">
					<circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16" y2="16"></line>
				</svg>
			</button>

			<!-- Wishlist -->
			<?php $wishlist_url = function_exists( 'YITH_WCWL' ) ? YITH_WCWL()->get_wishlist_url() : '#'; ?>
			<a href="<?php echo esc_url( $wishlist_url ); ?>" class="ame-luxury-action-btn" aria-label="<?php esc_attr_e( 'Wishlist', 'ame-bazaar' ); ?>">
				<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" aria-hidden="true">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
				</svg>
			</a>

			<?php
			$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
			$cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
			$my_account_id = function_exists( 'get_option' ) ? get_option('woocommerce_myaccount_page_id') : 0;
			$account_url = $my_account_id ? get_permalink( $my_account_id ) : '#';
			?>

			<!-- Account -->
			<a href="<?php echo esc_url( $account_url ); ?>" class="ame-luxury-action-btn" aria-label="<?php esc_attr_e( 'Account', 'ame-bazaar' ); ?>">
				<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" aria-hidden="true">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
				</svg>
			</a>

			<!-- Shopping Bag -->
			<a href="<?php echo esc_url( $cart_url ); ?>" class="ame-luxury-action-btn ame-cart-link" aria-label="<?php esc_attr_e( 'Shopping Bag', 'ame-bazaar' ); ?>">
				<div class="ame-cart-icon-wrapper">
					<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" aria-hidden="true">
						<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
					</svg>
					<?php if ( $cart_count > 0 ) : ?>
						<span class="ame-luxury-cart-dot"></span>
					<?php endif; ?>
				</div>
			</a>
		</div>

	</div>
</div>

<!-- Mobile Premium Navigation Header -->
<header class="ame-mobile-header-premium hide-on-desktop">
	<!-- Row 1: Actions, Logo, Icons -->
	<div class="ame-mobile-header-row-1">
		<!-- Left: Call & Visit -->
		<div class="ame-mobile-header-left">
			<a href="tel:<?php echo esc_attr( $phone_tel_link ); ?>" class="ame-mobile-cta" aria-label="<?php echo esc_attr( sprintf( __( 'Call Now: %s', 'ame-bazaar' ), $phone_number ) ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
				<span class="label"><?php esc_html_e( 'Call', 'ame-bazaar' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-mobile-cta" aria-label="<?php esc_attr_e( 'Visit Store', 'ame-bazaar' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
				<span class="label"><?php esc_html_e( 'Visit', 'ame-bazaar' ); ?></span>
			</a>
		</div>

		<!-- Center: Premium Logo Plate -->
		<div class="ame-mobile-header-center">
			<div class="ame-mobile-logo-plate">
				<?php
				if ( $logo_id ) {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
					echo wp_get_attachment_image( $logo_id, 'full', false, array(
						'loading' => 'eager',
						'alt'     => esc_attr( ame_bazaar_get_brand_name() ),
					) );
					echo '</a>';
				} else {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" style="text-decoration:none; color:var(--ame-color-primary); font-weight:bold;" rel="home">';
					echo esc_html( ame_bazaar_get_brand_name() );
					echo '</a>';
				}
				?>
			</div>
		</div>

		<!-- Right: Icons -->
		<div class="ame-mobile-header-right">
			<a href="#" class="ame-mobile-icon-btn ame-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'ame-bazaar' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16" y2="16"></line></svg>
			</a>
			<a href="<?php echo esc_url( $wishlist_url ); ?>" class="ame-mobile-icon-btn" aria-label="<?php esc_attr_e( 'Wishlist', 'ame-bazaar' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
			</a>
			<a href="<?php echo esc_url( $account_url ); ?>" class="ame-mobile-icon-btn" aria-label="<?php esc_attr_e( 'Account', 'ame-bazaar' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</a>
			<a href="<?php echo esc_url( $cart_url ); ?>" class="ame-mobile-icon-btn ame-cart-link" aria-label="<?php esc_attr_e( 'Shopping Bag', 'ame-bazaar' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
				<?php if ( $cart_count > 0 ) : ?>
					<span class="ame-mobile-cart-badge"></span>
				<?php endif; ?>
			</a>
		</div>
	</div>

	<!-- Row 2: Navigation Rail -->
	<div class="ame-mobile-header-row-2">
		<div class="ame-mobile-nav-rail-container">
			<ul class="ame-mobile-nav-rail">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-ame-bazaar/' ) ); ?>"><?php esc_html_e( 'About', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/fashion-advisor/' ) ); ?>"><?php esc_html_e( 'Fashion Advisor', 'ame-bazaar' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'ame-bazaar' ); ?></a></li>
			</ul>
		</div>
	</div>
</header>

<!-- Mobile Off-Canvas Navigation Drawer (Cleaned) -->
<div class="ame-mobile-drawer" id="ame-mobile-menu-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation', 'ame-bazaar' ); ?>">
	<div class="ame-mobile-drawer-inner">
		
		<div class="ame-mobile-drawer-header">
			<button class="ame-mobile-drawer-close-btn" id="ame-menu-close-btn" aria-label="<?php esc_attr_e( 'Close', 'ame-bazaar' ); ?>">
				<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<div class="ame-mobile-drawer-search">
			<form role="search" method="get" class="ame-luxury-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="ame-luxury-search-input" placeholder="<?php esc_attr_e( 'Search', 'ame-bazaar' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required />
			</form>
		</div>

		<nav class="ame-mobile-drawer-navigation">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ame-mobile-menu-luxury',
					'depth'          => 2,
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<ul class="ame-mobile-menu-luxury">
					<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about-ame-bazaar/' ) ); ?>"><?php esc_html_e( 'Brand', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/fashion-advisor/' ) ); ?>"><?php esc_html_e( 'AI Stylist', 'ame-bazaar' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'ame-bazaar' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>
	</div>
	<div class="ame-mobile-drawer-overlay" id="ame-menu-overlay-bg" aria-hidden="true"></div>
</div>

<!-- Desktop Search Overlay (Minimalist) -->
<div class="ame-search-overlay" id="ame-desktop-search-overlay" role="dialog" aria-modal="true" aria-hidden="true">
	<div class="ame-search-overlay-inner">
		<form role="search" method="get" class="ame-luxury-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" id="ame-search-input" class="ame-luxury-search-input-massive" placeholder="<?php esc_attr_e( 'Search...', 'ame-bazaar' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required />
		</form>
		<button class="ame-search-close-btn" id="ame-search-close-btn" aria-label="<?php esc_attr_e( 'Close', 'ame-bazaar' ); ?>">
			<svg class="ame-luxury-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
				<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
	</div>
</div>
