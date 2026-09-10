<?php
/**
 * AME Bazaar - IndexNow Integration
 *
 * Implements Microsoft IndexNow for real-time URL notification on updates, additions, and deletions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Serve the IndexNow API Key
 * Must be served at the root URL: /a38c4b9d5f7142d59e8b60a886f44d18.txt
 */
function ame_bazaar_serve_indexnow_key() {
	$api_key = 'a38c4b9d5f7142d59e8b60a886f44d18';
	$request = $_SERVER['REQUEST_URI'];
	
	if ( strpos( $request, '/' . $api_key . '.txt' ) !== false ) {
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo $api_key;
		exit;
	}
}
add_action( 'init', 'ame_bazaar_serve_indexnow_key', 1 );

/**
 * 2. Hook into post status transitions to schedule a notification
 */
function ame_bazaar_indexnow_notify_transition( $new_status, $old_status, $post ) {
	// Only care about public-facing post types
	if ( ! in_array( $post->post_type, array( 'post', 'page', 'product' ) ) ) {
		return;
	}

	$should_notify = false;

	// Case 1: Post becomes published (new or from draft/private)
	if ( 'publish' === $new_status && $old_status !== 'publish' ) {
		$should_notify = true;
	} 
	// Case 2: Post is updated while remaining published
	elseif ( 'publish' === $new_status && 'publish' === $old_status ) {
		$should_notify = true;
	}
	// Case 3: Post was published, but is now unpublished/trashed
	elseif ( 'publish' === $old_status && in_array( $new_status, array( 'trash', 'draft', 'private', 'pending' ) ) ) {
		$should_notify = true;
	}

	if ( $should_notify ) {
		$url = get_permalink( $post->ID );
		if ( ! empty( $url ) ) {
			// Schedule single event to prevent blocking the save action
			if ( ! wp_next_scheduled( 'ame_bazaar_indexnow_async_ping', array( $url ) ) ) {
				wp_schedule_single_event( time(), 'ame_bazaar_indexnow_async_ping', array( $url ) );
			}
		}
	}
}
add_action( 'transition_post_status', 'ame_bazaar_indexnow_notify_transition', 10, 3 );

/**
 * 3. Hook into hard deletions
 */
function ame_bazaar_indexnow_notify_deleted( $post_id, $post ) {
	if ( ! in_array( $post->post_type, array( 'post', 'page', 'product' ) ) ) {
		return;
	}

	// Only notify if it was previously published before being deleted
	if ( 'publish' === $post->post_status ) {
		$url = get_permalink( $post_id );
		if ( ! empty( $url ) ) {
			if ( ! wp_next_scheduled( 'ame_bazaar_indexnow_async_ping', array( $url ) ) ) {
				wp_schedule_single_event( time(), 'ame_bazaar_indexnow_async_ping', array( $url ) );
			}
		}
	}
}
add_action( 'deleted_post', 'ame_bazaar_indexnow_notify_deleted', 10, 2 );

/**
 * 4. Execute the Async Ping to IndexNow
 */
function ame_bazaar_indexnow_execute_ping( $url ) {
	$api_key = 'a38c4b9d5f7142d59e8b60a886f44d18';
	
	// IndexNow endpoint - using api.indexnow.org which routes to all participating engines
	$endpoint = 'https://api.indexnow.org/IndexNow';
	
	// Normalize the URL
	$parsed_url = parse_url( $url );
	if ( ! isset( $parsed_url['host'] ) ) {
		return; // Cannot notify without a valid host
	}
	$host = $parsed_url['host'];

	$payload = array(
		'host'        => $host,
		'key'         => $api_key,
		'keyLocation' => 'https://' . $host . '/' . $api_key . '.txt',
		'urlList'     => array( $url )
	);

	$args = array(
		'body'        => wp_json_encode( $payload ),
		'headers'     => array(
			'Content-Type' => 'application/json; charset=utf-8'
		),
		'timeout'     => 5,
		'blocking'    => false, // Fire and forget
	);

	wp_remote_post( $endpoint, $args );
}
add_action( 'ame_bazaar_indexnow_async_ping', 'ame_bazaar_indexnow_execute_ping' );
