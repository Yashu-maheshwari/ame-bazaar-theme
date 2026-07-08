<?php
/**
 * Diagnostic script to check for physical files in document root.
 */
header('Content-Type: text/plain');

$theme_dir = __DIR__;
echo "THEME DIR: $theme_dir\n";

$inc_files = scandir($theme_dir . '/inc');
echo "\nINC FILES:\n";
foreach ($inc_files as $f) {
	echo "- $f\n";
}

$sitemap_path = $theme_dir . '/inc/sitemap.php';
echo "\nSITEMAP.PHP EXISTS: " . (file_exists($sitemap_path) ? 'YES' : 'NO') . "\n";

$func_content = file_get_contents($theme_dir . '/functions.php');
echo "\nFUNCTIONS.PHP CONTAINS 'sitemap.php': " . (strpos($func_content, 'sitemap.php') !== false ? 'YES' : 'NO') . "\n";

// Let's check rewrite rules
global $wp_rewrite;
echo "\nSITEMAP REWRITE RULE: " . (isset($wp_rewrite->rules['^sitemap\.xml$']) ? $wp_rewrite->rules['^sitemap\.xml$'] : 'NOT FOUND') . "\n";
