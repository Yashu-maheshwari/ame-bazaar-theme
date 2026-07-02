<?php
/**
 * The header for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'ame-bazaar' ); ?></a>
<?php do_action( 'ame_bazaar_before_header' ); ?>
<header id="masthead" class="site-header ame-bazaar-site-header" role="banner">
	<?php do_action( 'ame_bazaar_header' ); ?>
</header>
<?php do_action( 'ame_bazaar_after_header' ); ?>
