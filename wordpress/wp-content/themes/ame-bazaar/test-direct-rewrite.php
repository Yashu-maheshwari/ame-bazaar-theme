<?php
/**
 * Test rewrite rules.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "ACTIVE THEME: " . get_stylesheet() . "\n";

// Force add rewrite rule directly and dump
add_rewrite_rule( '^sitemap\.xml$', 'index.php?ame_bazaar_sitemap=1', 'top' );

global $wp_rewrite;
$rules = $wp_rewrite->wp_rewrite_rules();

echo "RULE SEARCH FOR sitemap.xml: " . (isset($rules['^sitemap\.xml$']) ? $rules['^sitemap\.xml$'] : 'NOT FOUND') . "\n";

echo "\nALL REWRITE RULES DUMP (First 10):\n";
$i = 0;
foreach ($rules as $regex => $query) {
	echo "- $regex => $query\n";
	$i++;
	if ($i >= 10) break;
}

// Clean flush
echo "\nFLUSHING REWRITES DIRECTLY...\n";
flush_rewrite_rules(true);
$db_rules = get_option('rewrite_rules');
echo "DATABASE RULE SEARCH: " . (isset($db_rules['^sitemap\.xml$']) ? $db_rules['^sitemap\.xml$'] : 'NOT FOUND') . "\n";
