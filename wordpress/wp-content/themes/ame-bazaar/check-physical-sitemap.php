<?php
/**
 * Diagnostic script to check for physical files in document root.
 */
header('Content-Type: text/plain');

$doc_root = dirname(dirname(dirname(__DIR__)));
echo "DOCUMENT ROOT: $doc_root\n";

$sitemap_path = $doc_root . '/sitemap.xml';
echo "PHYSICAL SITEMAP EXISTS: " . (file_exists($sitemap_path) ? 'YES' : 'NO') . "\n";
if (file_exists($sitemap_path)) {
	echo "PHYSICAL SITEMAP CONTENT:\n";
	echo file_get_contents($sitemap_path);
}

// Let's verify the active query var and template redirect
global $wp_rewrite;
echo "\nSITEMAP REWRITE RULE: " . (isset($wp_rewrite->rules['^sitemap\.xml$']) ? $wp_rewrite->rules['^sitemap\.xml$'] : 'NOT FOUND') . "\n";
