<?php
/**
 * Diagnostic script to list active plugins.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "ACTIVE PLUGINS:\n";
$active_plugins = get_option('active_plugins');
foreach ($active_plugins as $plugin) {
	echo "- $plugin\n";
}

echo "\nALL PLUGINS:\n";
if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$all_plugins = get_plugins();
foreach ($all_plugins as $file => $data) {
	echo "- $file (Name: {$data['Name']}, Version: {$data['Version']}, Active: " . (in_array($file, $active_plugins) ? 'YES' : 'NO') . ")\n";
}
