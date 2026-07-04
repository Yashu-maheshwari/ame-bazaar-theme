<?php
/**
 * Temp Media Library Scanner for AME Bazaar.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

$query = new WP_Query( array(
	'post_type'      => 'attachment',
	'post_status'    => 'inherit',
	'posts_per_page' => -1,
) );

$attachments = array();
if ( $query->have_posts() ) {
	foreach ( $query->posts as $post ) {
		$attachments[] = array(
			'id'        => $post->ID,
			'title'     => $post->post_title,
			'name'      => $post->post_name,
			'mime_type' => $post->post_mime_type,
			'url'       => wp_get_attachment_url( $post->ID ),
		);
	}
}

echo json_encode( $attachments, JSON_PRETTY_PRINT );
