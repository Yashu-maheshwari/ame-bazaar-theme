<?php
/**
 * Diagnostic script to read .htaccess and other server rules.
 */
header('Content-Type: text/plain');

$htaccess_file = dirname(dirname(dirname(__DIR__))) . '/.htaccess';
echo "HTACCESS PATH: $htaccess_file\n";
echo "EXISTS: " . (file_exists($htaccess_file) ? 'YES' : 'NO') . "\n";

if (file_exists($htaccess_file)) {
	echo "\n--- .htaccess CONTENTS ---\n";
	echo file_get_contents($htaccess_file);
} else {
	// Let's check files in the root folder
	$root_dir = dirname(dirname(dirname(__DIR__)));
	echo "\nROOT DIR FILES:\n";
	$files = scandir($root_dir);
	foreach ($files as $f) {
		echo "- $f\n";
	}
}
