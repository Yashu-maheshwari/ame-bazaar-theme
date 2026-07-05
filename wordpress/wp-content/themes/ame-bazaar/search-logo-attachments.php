<?php
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}
header( 'Content-Type: application/json' );

global $wpdb;
$query = "SELECT ID, post_title, post_name, guid FROM {$wpdb->posts} WHERE post_type = 'attachment' AND (post_title LIKE '%logo%' OR post_name LIKE '%logo%' OR guid LIKE '%logo%' OR post_title LIKE '%icon%' OR post_name LIKE '%icon%' OR guid LIKE '%icon%')";
$results = $wpdb->get_results( $query );

echo json_encode( $results, JSON_PRETTY_PRINT );
