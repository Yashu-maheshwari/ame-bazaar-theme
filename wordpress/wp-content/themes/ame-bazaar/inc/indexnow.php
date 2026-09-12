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
 * Define IndexNow configuration constants.
 */
if ( ! defined( 'AME_INDEXNOW_KEY' ) ) {
	define( 'AME_INDEXNOW_KEY', 'a38c4b9d5f7142d59e8b60a886f44d18' );
}

if ( ! defined( 'AME_INDEXNOW_HOST' ) ) {
	define( 'AME_INDEXNOW_HOST', 'amebazaar.in' );
}

if ( ! defined( 'AME_INDEXNOW_ENDPOINT' ) ) {
	define( 'AME_INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow' );
}

/**
 * 1. Serve the IndexNow API Key
 *
 * Serves the exact key at https://amebazaar.in/<KEY>.txt with HTTP 200,
 * plain text content type, no whitespace corruption, and noindex headers.
 */
function ame_bazaar_serve_indexnow_key() {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	$path        = parse_url( $request_uri, PHP_URL_PATH );

	if ( '/' . AME_INDEXNOW_KEY . '.txt' === $path ) {
		// Clean any previous output buffer to prevent whitespace/BOM corruption
		while ( ob_get_level() ) {
			ob_end_clean();
		}

		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Robots-Tag: noindex, nofollow' );
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
		echo AME_INDEXNOW_KEY;
		exit;
	}
}
add_action( 'init', 'ame_bazaar_serve_indexnow_key', 1 );

/**
 * 2. Validate URLs for IndexNow Eligibility
 *
 * Ensures only public canonical HTTPS URLs belonging to amebazaar.in are submitted.
 * Excludes cart, checkout, account, admin, query strings, and non-canonical paths.
 *
 * @param string $url URL to inspect.
 * @return bool True if eligible for submission, false otherwise.
 */
function ame_bazaar_is_valid_indexnow_url( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return false;
	}

	$parts = parse_url( $url );
	if ( ! isset( $parts['scheme'], $parts['host'], $parts['path'] ) ) {
		return false;
	}

	// Host must be exact canonical domain
	if ( strtolower( $parts['host'] ) !== AME_INDEXNOW_HOST ) {
		return false;
	}

	// Scheme must be HTTPS
	if ( 'https' !== strtolower( $parts['scheme'] ) ) {
		return false;
	}

	// Exclude parameterized/tracking URLs
	if ( ! empty( $parts['query'] ) ) {
		return false;
	}

	$path = strtolower( $parts['path'] );

	// Excluded paths (WooCommerce utility, admin, API, system)
	$excluded_patterns = array(
		'/cart',
		'/checkout',
		'/my-account',
		'/wp-admin',
		'/wp-login',
		'/wp-json',
		'/xmlrpc',
		'/feed',
		'/order-received',
		'/lost-password',
		'/preview',
	);

	foreach ( $excluded_patterns as $pattern ) {
		if ( strpos( $path, $pattern ) === 0 || strpos( $path, $pattern . '/' ) !== false ) {
			return false;
		}
	}

	return true;
}

/**
 * 3. Schedule URL Submission with Rate-Limiting & Transient Deduplication
 *
 * Uses a 1-hour transient lock to prevent redundant IndexNow requests
 * during rapid sequential product or page updates.
 *
 * @param string $url Canonical URL to submit.
 * @return bool True if scheduled, false if deduplicated or invalid.
 */
function ame_bazaar_indexnow_schedule_url( $url ) {
	if ( ! ame_bazaar_is_valid_indexnow_url( $url ) ) {
		return false;
	}

	$transient_key = 'ame_in_sent_' . md5( $url );
	if ( get_transient( $transient_key ) ) {
		return false; // Already submitted or queued within the deduplication window
	}

	// Lock submission for 1 hour
	set_transient( $transient_key, 1, HOUR_IN_SECONDS );

	// Schedule single event to prevent blocking caller
	if ( ! wp_next_scheduled( 'ame_bazaar_indexnow_async_ping', array( $url ) ) ) {
		wp_schedule_single_event( time(), 'ame_bazaar_indexnow_async_ping', array( $url ) );
	}

	return true;
}

/**
 * 4. Hook: Post / Page / Product Status Transitions
 */
function ame_bazaar_indexnow_notify_transition( $new_status, $old_status, $post ) {
	if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page', 'product' ), true ) ) {
		return;
	}

	$should_notify = false;

	// Case 1: Post newly published
	if ( 'publish' === $new_status && 'publish' !== $old_status ) {
		$should_notify = true;
	}
	// Case 2: Post updated while remaining published
	elseif ( 'publish' === $new_status && 'publish' === $old_status ) {
		$should_notify = true;
	}
	// Case 3: Post was published, now unpublished or trashed
	elseif ( 'publish' === $old_status && in_array( $new_status, array( 'trash', 'draft', 'private' ), true ) ) {
		$should_notify = true;
	}

	if ( $should_notify ) {
		$url = get_permalink( $post->ID );
		if ( ! empty( $url ) ) {
			ame_bazaar_indexnow_schedule_url( $url );
		}
	}
}
add_action( 'transition_post_status', 'ame_bazaar_indexnow_notify_transition', 10, 3 );

/**
 * 5. Hook: Permanent Post Deletions
 */
function ame_bazaar_indexnow_notify_deleted( $post_id, $post ) {
	if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page', 'product' ), true ) ) {
		return;
	}

	if ( 'publish' === $post->post_status ) {
		$url = get_permalink( $post_id );
		if ( ! empty( $url ) ) {
			ame_bazaar_indexnow_schedule_url( $url );
		}
	}
}
add_action( 'deleted_post', 'ame_bazaar_indexnow_notify_deleted', 10, 2 );

/**
 * 6. Hook: WooCommerce Product Updates
 *
 * Ensures WooCommerce CRUD updates (e.g. stock/price/metadata changes)
 * queue the product URL if published. Deduplication transient prevents flooding.
 */
function ame_bazaar_indexnow_notify_wc_product( $product_id ) {
	if ( function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $product_id );
		if ( $product && 'publish' === $product->get_status() ) {
			$url = $product->get_permalink();
			if ( ! empty( $url ) ) {
				ame_bazaar_indexnow_schedule_url( $url );
			}
		}
	}
}
add_action( 'woocommerce_update_product', 'ame_bazaar_indexnow_notify_wc_product', 10, 1 );

/**
 * 7. Hook: Product Category Updates
 *
 * Submits category URL when a product category term is modified.
 */
function ame_bazaar_indexnow_notify_category( $term_id ) {
	$term_link = get_term_link( (int) $term_id, 'product_cat' );
	if ( ! is_wp_error( $term_link ) && ! empty( $term_link ) ) {
		ame_bazaar_indexnow_schedule_url( $term_link );
	}
}
add_action( 'saved_product_cat', 'ame_bazaar_indexnow_notify_category', 10, 1 );

/**
 * 8. Execute Asynchronous Single-URL Ping
 */
function ame_bazaar_indexnow_execute_ping( $url ) {
	if ( ! ame_bazaar_is_valid_indexnow_url( $url ) ) {
		return;
	}

	ame_bazaar_indexnow_submit_urls( array( $url ), false );
}
add_action( 'ame_bazaar_indexnow_async_ping', 'ame_bazaar_indexnow_execute_ping' );

/**
 * 9. Core IndexNow Submission Function
 *
 * Supports single and batch URL submissions, with non-blocking or blocking dispatch.
 * Fully failure-tolerant: catches all errors so external API issues never impact site operations.
 *
 * @param array $urls Array of canonical URLs.
 * @param bool  $blocking True to wait for API response (e.g. in tests/CLI), false for fire-and-forget.
 * @return array Operation status result.
 */
function ame_bazaar_indexnow_submit_urls( array $urls, $blocking = false ) {
	$valid_urls = array();
	foreach ( $urls as $u ) {
		if ( ame_bazaar_is_valid_indexnow_url( $u ) ) {
			$valid_urls[] = esc_url_raw( $u );
		}
	}

	$valid_urls = array_values( array_unique( $valid_urls ) );
	if ( empty( $valid_urls ) ) {
		return array(
			'success' => false,
			'message' => 'No valid canonical URLs provided.',
		);
	}

	$payload = array(
		'host'        => AME_INDEXNOW_HOST,
		'key'         => AME_INDEXNOW_KEY,
		'keyLocation' => 'https://' . AME_INDEXNOW_HOST . '/' . AME_INDEXNOW_KEY . '.txt',
		'urlList'     => $valid_urls,
	);

	$args = array(
		'body'        => wp_json_encode( $payload ),
		'headers'     => array(
			'Content-Type' => 'application/json; charset=utf-8',
		),
		'timeout'     => 10,
		'blocking'    => (bool) $blocking,
	);

	try {
		$response = wp_remote_post( AME_INDEXNOW_ENDPOINT, $args );

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'error'   => $response->get_error_message(),
			);
		}

		if ( ! $blocking ) {
			return array(
				'success' => true,
				'message' => 'Dispatched non-blocking ping.',
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );

		return array(
			'success'     => ( 200 === $status_code || 202 === $status_code ),
			'status_code' => $status_code,
			'body'        => $body,
		);
	} catch ( Throwable $e ) {
		// Suppress any unexpected exceptions to guarantee absolute zero caller disruption
		return array(
			'success' => false,
			'error'   => $e->getMessage(),
		);
	}
}

/**
 * 10. WP-CLI Command for Safe Operations & Verification
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'ame-indexnow submit', function( $args, $assoc_args ) {
		$url = isset( $assoc_args['url'] ) ? (string) $assoc_args['url'] : '';
		if ( empty( $url ) ) {
			WP_CLI::error( 'Please provide a URL via --url=<URL>' );
		}

		$result = ame_bazaar_indexnow_submit_urls( array( $url ), true );
		if ( ! empty( $result['success'] ) ) {
			WP_CLI::success( 'IndexNow accepted URL: ' . $url . ' (HTTP ' . $result['status_code'] . ')' );
		} else {
			WP_CLI::error( 'IndexNow submission failed: ' . wp_json_encode( $result ) );
		}
	} );
}
