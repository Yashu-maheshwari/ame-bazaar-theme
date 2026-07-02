<?php
/**
 * AME Bazaar Astra Child Theme functions and definitions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_GET['flush_opcache'] ) && $_GET['flush_opcache'] === 'ame_bazaar_secret_key_123' ) {
	if ( function_exists( 'opcache_reset' ) ) {
		opcache_reset();
		echo 'OPCache Reset Successful!';
	} else {
		echo 'OPCache not enabled or reset function missing.';
	}
	exit;
}

define( 'AME_BAZAAR_VERSION', '1.0.0' );
define( 'AME_BAZAAR_PATH', get_stylesheet_directory() );
define( 'AME_BAZAAR_URI', get_stylesheet_directory_uri() );

require_once AME_BAZAAR_PATH . '/inc/setup.php';
require_once AME_BAZAAR_PATH . '/inc/enqueue.php';
require_once AME_BAZAAR_PATH . '/inc/helpers.php';
require_once AME_BAZAAR_PATH . '/inc/schema.php';
require_once AME_BAZAAR_PATH . '/inc/security.php';
require_once AME_BAZAAR_PATH . '/inc/customizer.php';
require_once AME_BAZAAR_PATH . '/inc/homepage-functions.php';
require_once AME_BAZAAR_PATH . '/inc/seo.php';
require_once AME_BAZAAR_PATH . '/inc/content-framework.php';
require_once AME_BAZAAR_PATH . '/inc/woocommerce.php';
require_once AME_BAZAAR_PATH . '/inc/admin-operations.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/trust-cards.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-card.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-summary.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-slider.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-badge.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/review-cta.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/google-rating-widget.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/trust-block.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/customer-highlights.php';
require_once AME_BAZAAR_PATH . '/components/local-entity/popular-review-keywords.php';

$upload_dir = wp_upload_dir();
if ( isset( $upload_dir['basedir'] ) ) {
	file_put_contents( $upload_dir['basedir'] . '/debug.txt', 'Loaded: ' . date('Y-m-d H:i:s') );
}

function ame_bazaar_diagnostic_footer_comment() {
	echo '<!-- AME_BAZAAR_CHILD_THEME_ACTIVE_AND_RUNNING -->';
}
add_action( 'wp_footer', 'ame_bazaar_diagnostic_footer_comment' );








