<?php
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}
header( 'Content-Type: application/json' );

global $wpdb;
$query = "SELECT ID, post_title, post_name, guid, post_mime_type FROM {$wpdb->posts} WHERE post_type = 'attachment' ORDER BY ID DESC";
$results = $wpdb->get_results( $query );

echo json_encode( $results, JSON_PRETTY_PRINT );
