<?php
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}
header( 'Content-Type: application/json' );

$attachments = get_posts( array(
	'post_type'      => 'attachment',
	'post_status'    => 'inherit',
	'posts_per_page' => 100,
) );

$output = array();
foreach ( $attachments as $attachment ) {
	$output[] = array(
		'id'   => $attachment->ID,
		'name' => $attachment->post_name,
		'title'=> $attachment->post_title,
		'url'  => wp_get_attachment_url( $attachment->ID ),
		'mime' => $attachment->post_mime_type,
	);
}

echo json_encode( $output, JSON_PRETTY_PRINT );
