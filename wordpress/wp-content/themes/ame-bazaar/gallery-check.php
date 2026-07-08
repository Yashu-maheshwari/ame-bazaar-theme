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
	$meta = wp_get_attachment_metadata( $attachment->ID );
	$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
	$output[] = array(
		'id'   => $attachment->ID,
		'name' => $attachment->post_name,
		'title'=> $attachment->post_title,
		'alt'  => $alt,
		'width'=> isset( $meta['width'] ) ? $meta['width'] : null,
		'height'=> isset( $meta['height'] ) ? $meta['height'] : null,
		'url'  => wp_get_attachment_url( $attachment->ID ),
		'mime' => $attachment->post_mime_type,
	);
}

echo json_encode( $output, JSON_PRETTY_PRINT );
