<?php
/**
 * Theme setup.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_setup_theme() {
	load_child_theme_textdomain( 'ame-bazaar', AME_BAZAAR_PATH . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'search-form',
	) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'woocommerce' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ame-bazaar' ),
		'footer'  => __( 'Footer Menu', 'ame-bazaar' ),
	) );

	add_image_size( 'ame-bazaar-hero', 1800, 1200, true );
	add_image_size( 'ame-bazaar-card', 900, 1200, true );
	add_image_size( 'ame-bazaar-square', 900, 900, true );
}
add_action( 'after_setup_theme', 'ame_bazaar_setup_theme' );

function ame_bazaar_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'ame_bazaar_content_width', 0 );

/**
 * Render the header template part.
 */
function ame_bazaar_render_header() {
	get_template_part( 'components/header/header' );
}
add_action( 'ame_bazaar_header', 'ame_bazaar_render_header' );

/**
 * Render the footer template part.
 */
function ame_bazaar_render_footer() {
	get_template_part( 'components/footer/footer' );
}
add_action( 'ame_bazaar_footer', 'ame_bazaar_render_footer' );

/**
 * Render dynamic Favicon inside the head tag.
 */
function ame_bazaar_render_favicon_in_head() {
	$favicon_id = get_option( 'ame_bazaar_media_favicon' );
	if ( $favicon_id ) {
		$favicon_url = wp_get_attachment_image_url( $favicon_id, 'full' );
		if ( $favicon_url ) {
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon_url ) . '" type="image/x-icon" />';
		}
	}
}
add_action( 'wp_head', 'ame_bazaar_render_favicon_in_head' );

function ame_bazaar_render_opengraph_meta_in_head() {
	$logo_id = get_option( 'ame_bazaar_media_primary_logo' ) ?: get_theme_mod( 'custom_logo' );
	$logo_url = '';
	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
	}
	?>
	<meta property="og:title" content="<?php echo esc_attr( wp_get_document_title() ); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo esc_url( home_url( $_SERVER['REQUEST_URI'] ) ); ?>" />
	<?php if ( $logo_url ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $logo_url ); ?>" />
		<meta name="twitter:image" content="<?php echo esc_url( $logo_url ); ?>" />
	<?php endif; ?>
	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( wp_get_document_title() ); ?>" />
	<?php
}
add_action( 'wp_head', 'ame_bazaar_render_opengraph_meta_in_head' );


/**
 * Programmatically create the AI and authority pages on theme init.
 */
function ame_bazaar_create_authority_and_ai_pages() {
	// 1. Delete old/outdated pages
	$old_slugs = array( 'ai-fashion-assistant' );
	$deleted = false;
	foreach ( $old_slugs as $old_slug ) {
		$old_page = get_page_by_path( $old_slug );
		if ( $old_page ) {
			wp_delete_post( $old_page->ID, true );
			$deleted = true;
		}
	}

	// 1.5 Disable WooCommerce coming-soon mode options to ensure shop pages are visible to crawlers and users
	if ( function_exists( 'update_option' ) ) {
		update_option( 'woocommerce_coming_soon', 'no' );
		update_option( 'woocommerce_store_editing', 'no' );
	}

	// 2. Map pages to templates
	$pages_map = array(
		'about-ame-bazaar' => array(
			'title'    => 'About AME Bazaar',
			'template' => 'templates/template-about.php',
		),
		'contact' => array(
			'title'    => 'Contact Us',
			'template' => 'templates/template-contact.php',
		),
		'faq' => array(
			'title'    => 'FAQ',
			'template' => 'templates/template-faq.php',
		),
		'fashion-advisor' => array(
			'title'    => 'AI Fashion Advisor',
			'template' => 'templates/template-ai-advisor.php',
		),
		'ask-ame' => array(
			'title'    => 'Ask AME - Internal AI Search',
			'template' => 'templates/template-ask-ame.php',
		),
		'best-clothing-store-in-kirari' => array(
			'title'    => 'Best Clothing Store in Kirari',
			'template' => 'templates/template-authority.php',
		),
		'best-mens-wear-shop' => array(
			'title'    => 'Best Men\'s Wear Shop',
			'template' => 'templates/template-authority.php',
		),
		'best-womens-wear-shop' => array(
			'title'    => 'Best Women\'s Wear Shop',
			'template' => 'templates/template-authority.php',
		),
		'best-kids-wear-shop' => array(
			'title'    => 'Best Kids Wear Shop',
			'template' => 'templates/template-authority.php',
		),
		'affordable-fashion-store' => array(
			'title'    => 'Affordable Fashion Store',
			'template' => 'templates/template-authority.php',
		),
		'wedding-shopping-in-kirari' => array(
			'title'    => 'Wedding Shopping in Kirari',
			'template' => 'templates/template-authority.php',
		),
		'tailoring-near-me' => array(
			'title'    => 'Tailoring Near Me',
			'template' => 'templates/template-authority.php',
		),
		'family-clothing-store' => array(
			'title'    => 'Family Clothing Store',
			'template' => 'templates/template-authority.php',
		),
		'festival-shopping-guide' => array(
			'title'    => 'Festival Shopping Guide',
			'template' => 'templates/template-authority.php',
		),
		'shirt-fitting-guide' => array(
			'title'    => 'Shirt Fitting Guide',
			'template' => 'templates/template-authority.php',
		),
		'jeans-fitting-guide' => array(
			'title'    => 'Jeans Fitting Guide',
			'template' => 'templates/template-authority.php',
		),
		'fabric-guide' => array(
			'title'    => 'Fabric and Materials Guide',
			'template' => 'templates/template-authority.php',
		),
		'winter-wear-guide' => array(
			'title'    => 'Winter Wear Guide',
			'template' => 'templates/template-authority.php',
		),
		'clothing-care-guide' => array(
			'title'    => 'Clothing Wash and Care Guide',
			'template' => 'templates/template-authority.php',
		),
	);

	$inserted = false;
	foreach ( $pages_map as $slug => $data ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$page_id = wp_insert_post( array(
				'post_title'   => $data['title'],
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_name'    => $slug,
			) );
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $data['template'] );
				$inserted = true;
			}
		} else {
			// Ensure template matches
			$curr_template = get_post_meta( $page->ID, '_wp_page_template', true );
			if ( $curr_template !== $data['template'] ) {
				update_post_meta( $page->ID, '_wp_page_template', $data['template'] );
				$inserted = true;
			}
		}
	}

	if ( $inserted || $deleted ) {
		flush_rewrite_rules( false );
	}
}
add_action( 'init', 'ame_bazaar_create_authority_and_ai_pages' );

/**
 * Handle template redirect to serve /llms.txt dynamically at the root.
 */
function ame_bazaar_serve_llms_txt_route() {
	$request_uri = $_SERVER['REQUEST_URI'];
	if ( untrailingslashit( $request_uri ) === '/llms.txt' ) {
		header( 'Content-Type: text/plain; charset=utf-8' );
		$file = AME_BAZAAR_PATH . '/llms.txt';
		if ( file_exists( $file ) ) {
			readfile( $file );
		} else {
			echo "AME Bazaar AI Profile\nLegal Entity: Apparel Maheshwari Enterprises\nLocation: Kirari, Delhi";
		}
		exit;
	}
}
add_action( 'template_redirect', 'ame_bazaar_serve_llms_txt_route' );
