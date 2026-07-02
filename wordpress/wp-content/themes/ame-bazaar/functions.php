<?php
/**
 * AME Bazaar Astra Child Theme functions and definitions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
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





