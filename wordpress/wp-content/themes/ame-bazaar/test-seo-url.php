<?php
/**
 * Test redirects debug.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

$request_uri = $_SERVER['REQUEST_URI'];
echo "REQUEST_URI: $request_uri\n";

$path = parse_url( $request_uri, PHP_URL_PATH );
echo "PARSED PATH: $path\n";

$path = '/' . trim( $path, '/' ) . '/';
echo "NORMALIZED PATH: $path\n";

$redirects = array(
	'/category/mens-wear/'    => '/product-category/mens-wear/',
	'/category/womens-wear/'  => '/product-category/womens-wear/',
	'/category/kids-wear/'    => '/product-category/boy-wear/', 
	'/category/accessories/'  => '/product-category/accessories/',
	'/return-policy/'         => '/return-refund-policy/',
	'/privacy-policy/'        => '/privacy-policy-2/',
);

echo "EXISTS IN REDIRECTS: " . (isset($redirects[$path]) ? 'YES' : 'NO') . "\n";
if (isset($redirects[$path])) {
	echo "REDIRECT TARGET: " . $redirects[$path] . "\n";
}

echo "CHECKING IF template_redirect HAS HOOK: \n";
global $wp_filter;
if (isset($wp_filter['template_redirect'])) {
	$hooks = $wp_filter['template_redirect'];
	foreach ($hooks as $priority => $callbacks) {
		foreach ($callbacks as $idx => $cb) {
			echo "Priority $priority: " . (is_string($cb['function']) ? $cb['function'] : 'Closure/Array') . " ($idx)\n";
		}
	}
}
