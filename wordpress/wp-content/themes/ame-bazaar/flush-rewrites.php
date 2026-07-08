<?php
/**
 * Temp script to flush WordPress rewrite rules post-deployment.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header('Content-Type: text/plain');

echo "FLUSHING REWRITE RULES...\n";
flush_rewrite_rules(true);
echo "REWRITE RULES FLUSHED SUCCESSFULLY.\n";
