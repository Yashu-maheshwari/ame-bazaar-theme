<?php
/**
 * Diagnostic script to verify uploaded file contents on Hostinger.
 */
header('Content-Type: text/plain');

$schema_file = __DIR__ . '/inc/schema.php';
$seo_file = __DIR__ . '/inc/seo.php';

echo "SCHEMA FILE PATH: $schema_file\n";
echo "EXISTS: " . (file_exists($schema_file) ? 'YES' : 'NO') . "\n";
if (file_exists($schema_file)) {
	$content = file_get_contents($schema_file);
	echo "CONTAINS 'product-category': " . (strpos($content, 'product-category') !== false ? 'YES' : 'NO') . "\n";
	echo "CONTAINS 'return-refund-policy': " . (strpos($content, 'return-refund-policy') !== false ? 'YES' : 'NO') . "\n";
}

echo "\nSEO FILE PATH: $seo_file\n";
echo "EXISTS: " . (file_exists($seo_file) ? 'YES' : 'NO') . "\n";
if (file_exists($seo_file)) {
	$content = file_get_contents($seo_file);
	echo "CONTAINS 'ame_bazaar_handle_redirects': " . (strpos($content, 'ame_bazaar_handle_redirects') !== false ? 'YES' : 'NO') . "\n";
}

// Check LiteSpeed Cache status
if (function_exists('opcache_reset')) {
	echo "\nOPCACHE RESETTABLE: YES\n";
} else {
	echo "\nOPCACHE RESETTABLE: NO\n";
}
