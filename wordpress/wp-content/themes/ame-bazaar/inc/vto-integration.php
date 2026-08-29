<?php
/**
 * Virtual Try-On (VTO) Integration for AME Bazaar.
 * Powers photorealistic neural fitting on WooCommerce single product pages
 * via WordPress REST API and asynchronous IDM-VTON job architecture.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Register REST API Endpoints for VTO.
 */
function ame_bazaar_vto_permission_check( WP_REST_Request $request ) {
	$nonce = $request->get_header( 'x_wp_nonce' );
	if ( empty( $nonce ) ) {
		$nonce = $request->get_param( '_wpnonce' );
	}

	if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return new WP_Error(
			'rest_forbidden',
			__( 'Invalid or missing security nonce.', 'ame-bazaar' ),
			array( 'status' => 403 )
		);
	}

	return true;
}

/**
 * Safely resolves client IP respecting Cloudflare / Hostinger edge headers.
 *
 * @return string
 */
function ame_bazaar_vto_get_client_ip() {
	if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) && filter_var( $_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP ) ) {
		return $_SERVER['HTTP_CF_CONNECTING_IP'];
	}
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forwarded = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
		$first_ip  = trim( $forwarded[0] );
		if ( filter_var( $first_ip, FILTER_VALIDATE_IP ) ) {
			return $first_ip;
		}
	}
	if ( ! empty( $_SERVER['REMOTE_ADDR'] ) && filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ) ) {
		return $_SERVER['REMOTE_ADDR'];
	}
	return '127.0.0.1';
}

function ame_bazaar_register_vto_routes() {
	register_rest_route( 'ame/v1', '/try-on', array(
		'methods'             => 'POST',
		'callback'            => 'ame_bazaar_vto_submit_job_callback',
		'permission_callback' => 'ame_bazaar_vto_permission_check',
		'args'                => array(
			'person_image'  => array(
				'required'          => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'garment_image' => array(
				'required'          => true,
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw',
			),
			'category'      => array(
				'required'          => false,
				'type'              => 'string',
				'default'           => 'upper_body',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'product_id'    => array(
				'required'          => false,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
			),
		),
	) );

	register_rest_route( 'ame/v1', '/try-on/(?P<job_id>[a-zA-Z0-9_\-]+)', array(
		'methods'             => 'GET',
		'callback'            => 'ame_bazaar_vto_poll_job_callback',
		'permission_callback' => 'ame_bazaar_vto_permission_check',
	) );
}
add_action( 'rest_api_init', 'ame_bazaar_register_vto_routes' );

/**
 * Validates and sanitizes a base64 image data string.
 *
 * @param string $data_uri
 * @return array|WP_Error
 */
function ame_bazaar_vto_validate_image_data( $data_uri ) {
	if ( empty( $data_uri ) ) {
		return new WP_Error( 'invalid_image', __( 'No image data provided.', 'ame-bazaar' ), array( 'status' => 400 ) );
	}

	// Check if base64 data URL
	if ( strpos( $data_uri, 'data:image/' ) === 0 ) {
		$parts = explode( ',', $data_uri, 2 );
		if ( count( $parts ) !== 2 ) {
			return new WP_Error( 'invalid_format', __( 'Invalid image encoding format.', 'ame-bazaar' ), array( 'status' => 400 ) );
		}

		$header = $parts[0];
		$raw_data = base64_decode( $parts[1] );

		if ( false === $raw_data || empty( $raw_data ) ) {
			return new WP_Error( 'decode_failed', __( 'Could not decode image binary.', 'ame-bazaar' ), array( 'status' => 400 ) );
		}

		// Size check: Max 8MB raw
		if ( strlen( $raw_data ) > 8 * 1024 * 1024 ) {
			return new WP_Error( 'image_too_large', __( 'Customer image exceeds maximum allowed size (8MB).', 'ame-bazaar' ), array( 'status' => 400 ) );
		}

		return array(
			'data_uri' => $data_uri,
			'binary'   => $raw_data,
			'mime'     => ( strpos( $header, 'image/png' ) !== false ) ? 'image/png' : 'image/jpeg',
		);
	}

	// If remote URL
	if ( filter_var( $data_uri, FILTER_VALIDATE_URL ) ) {
		return array(
			'data_uri' => $data_uri,
			'binary'   => null,
			'mime'     => 'image/jpeg',
		);
	}

	return new WP_Error( 'invalid_image', __( 'Invalid image payload format.', 'ame-bazaar' ), array( 'status' => 400 ) );
}

/**
 * POST /wp-json/ame/v1/try-on
 * Creates an asynchronous VTO job and dispatches the task.
 */
function ame_bazaar_vto_submit_job_callback( WP_REST_Request $request ) {
	// Rate Limiting: Max 5 VTO submissions per 3 minutes (180s) per IP
	$client_ip = ame_bazaar_vto_get_client_ip();
	$rate_key  = 'ame_vto_rate_' . md5( $client_ip . '_ame_vto_salt' );
	$requests  = (int) get_transient( $rate_key );

	if ( $requests >= 5 ) {
		return new WP_REST_Response( array(
			'status'  => 'error',
			'code'    => 'rate_limited',
			'message' => __( 'Too many try-on requests from this device. Please wait 3 minutes before trying again.', 'ame-bazaar' ),
		), 429 );
	}

	set_transient( $rate_key, $requests + 1, 180 );

	$person_image_raw  = $request->get_param( 'person_image' );
	$garment_image_url = $request->get_param( 'garment_image' );
	$category          = $request->get_param( 'category' );
	$product_id        = $request->get_param( 'product_id' );

	// Normalize category
	$valid_categories = array( 'upper_body', 'lower_body', 'dresses' );
	if ( ! in_array( $category, $valid_categories, true ) ) {
		$category = 'upper_body';
	}

	// Validate person image
	$validated_person = ame_bazaar_vto_validate_image_data( $person_image_raw );
	if ( is_wp_error( $validated_person ) ) {
		return new WP_REST_Response( array(
			'status'  => 'error',
			'message' => $validated_person->get_error_message(),
		), 400 );
	}

	// If product_id is provided, resolve trusted garment image directly from WooCommerce
	if ( ! empty( $product_id ) ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$image_id = $product->get_image_id();
			if ( $image_id ) {
				$trusted_url = wp_get_attachment_image_url( $image_id, 'large' );
				if ( $trusted_url ) {
					$garment_image_url = $trusted_url;
				}
			}
		}
	}

	// Validate garment URL with strict SSRF protection (reject private IPs and invalid URLs)
	if ( empty( $garment_image_url ) || ! wp_http_validate_url( $garment_image_url ) ) {
		return new WP_REST_Response( array(
			'status'  => 'error',
			'message' => __( 'Invalid or untrusted garment product image URL.', 'ame-bazaar' ),
		), 400 );
	}

	// Generate clean unique Job ID
	$job_id = 'vto_' . bin2hex( random_bytes( 8 ) ) . '_' . time();

	// Store initial transient job state (15 minute TTL)
	$job_payload = array(
		'job_id'        => $job_id,
		'status'        => 'queued',
		'category'      => $category,
		'product_id'    => $product_id,
		'person_image'  => $validated_person['data_uri'],
		'garment_image' => $garment_image_url,
		'result_url'    => null,
		'stage'         => __( 'Job queued for neural fitting...', 'ame-bazaar' ),
		'created_at'    => time(),
		'updated_at'    => time(),
	);

	set_transient( 'ame_vto_job_' . $job_id, $job_payload, 900 );

	return new WP_REST_Response( array(
		'status'  => 'queued',
		'job_id'  => $job_id,
		'stage'   => __( 'Job queued. Starting IDM-VTON neural fitting...', 'ame-bazaar' ),
	), 200 );
}

/**
 * GET /wp-json/ame/v1/try-on/:job_id
 * Polls asynchronous job status, triggers background processing step, and returns result.
 */
function ame_bazaar_vto_poll_job_callback( WP_REST_Request $request ) {
	$job_id = sanitize_text_field( $request->get_param( 'job_id' ) );
	$transient_key = 'ame_vto_job_' . $job_id;
	$job = get_transient( $transient_key );

	if ( false === $job || empty( $job ) ) {
		return new WP_REST_Response( array(
			'status'  => 'error',
			'message' => __( 'Job not found or expired.', 'ame-bazaar' ),
		), 404 );
	}

	// Check if already completed or errored
	if ( in_array( $job['status'], array( 'completed', 'error' ), true ) ) {
		return new WP_REST_Response( $job, 200 );
	}

	// Check timeout (45 seconds limit)
	if ( ( time() - $job['created_at'] ) > 45 ) {
		$job['status']  = 'error';
		$job['message'] = __( 'Server is busy, try again.', 'ame-bazaar' );
		set_transient( $transient_key, $job, 300 );
		return new WP_REST_Response( $job, 200 );
	}

	// Execute direct inference dispatch if still queued
	if ( 'queued' === $job['status'] || 'processing' === $job['status'] ) {
		$job['status'] = 'processing';
		$job['stage']  = __( 'Processing IDM-VTON neural diffusion...', 'ame-bazaar' );
		set_transient( $transient_key, $job, 900 );

		// Attempt Hugging Face Space IDM-VTON API
		$inference_result = ame_bazaar_vto_execute_hf_tryon( $job['person_image'], $job['garment_image'], $job['category'] );

		if ( is_wp_error( $inference_result ) ) {
			$job['status']  = 'error';
			$job['message'] = $inference_result->get_error_message();
			$job['stage']   = __( 'Processing error encountered.', 'ame-bazaar' );
			set_transient( $transient_key, $job, 300 );
			return new WP_REST_Response( $job, 200 );
		}

		if ( ! empty( $inference_result['result_url'] ) ) {
			$job['status']     = 'completed';
			$job['result_url'] = $inference_result['result_url'];
			$job['stage']      = __( 'Neural fit complete!', 'ame-bazaar' );
			$job['updated_at'] = time();

			// Store completed result and purge raw customer photo data from memory
			unset( $job['person_image'] );
			set_transient( $transient_key, $job, 900 );

			return new WP_REST_Response( $job, 200 );
		}
	}

	return new WP_REST_Response( $job, 200 );
}

/**
 * Dispatches image tensors to Hugging Face IDM-VTON / OOTDiffusion space.
 *
 * @param string $person_image
 * @param string $garment_image
 * @param string $category
 * @return array|WP_Error
 */
function ame_bazaar_vto_execute_hf_tryon( $person_image, $garment_image, $category ) {
	$endpoint = 'https://yisol-idm-vton.hf.space/run/tryon';

	// Format payload matching Gradio IDM-VTON endpoint
	$prompt_desc = sprintf(
		'Fit and warp this %s garment cleanly onto the human model torso and pose. Retain natural fabric folds and contours, strictly eliminating garment background.',
		esc_attr( str_replace( '_', ' ', $category ) )
	);

	$body = array(
		'data' => array(
			array(
				'background' => $person_image,
				'layers'     => array(),
				'composite'  => null,
			),
			$garment_image,
			$prompt_desc,
			true,  // is_checked (auto-masking)
			false, // is_checked_crop (avoid server-side hang)
			20,    // denoise_steps (optimized speed)
			42,    // seed
		),
	);

	$response = wp_remote_post( $endpoint, array(
		'headers'     => array(
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json',
		),
		'body'        => wp_json_encode( $body ),
		'timeout'     => 35,
		'redirection' => 3,
		'sslverify'   => true,
	) );

	if ( is_wp_error( $response ) ) {
		// Try secondary fallback endpoint or return clean busy notice
		return new WP_Error( 'server_busy', __( 'Server is busy, try again.', 'ame-bazaar' ) );
	}

	$status_code = wp_remote_retrieve_response_code( $response );
	$response_body = wp_remote_retrieve_body( $response );

	if ( 200 !== $status_code || empty( $response_body ) ) {
		return new WP_Error( 'server_busy', __( 'Server is busy, try again.', 'ame-bazaar' ) );
	}

	$data = json_decode( $response_body, true );

	// Extract result image URL
	$result_url = '';
	if ( isset( $data['data'][0] ) ) {
		if ( is_string( $data['data'][0] ) ) {
			$result_url = $data['data'][0];
		} elseif ( is_array( $data['data'][0] ) && isset( $data['data'][0]['url'] ) ) {
			$result_url = $data['data'][0]['url'];
		} elseif ( is_array( $data['data'][0] ) && isset( $data['data'][0]['image']['url'] ) ) {
			$result_url = $data['data'][0]['image']['url'];
		}
	}

	if ( ! empty( $result_url ) ) {
		return array( 'result_url' => $result_url );
	}

	return new WP_Error( 'server_busy', __( 'Server is busy, try again.', 'ame-bazaar' ) );
}

/**
 * 2. Enqueue VTO Assets on Single Product Pages.
 */
function ame_bazaar_enqueue_vto_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	wp_enqueue_style(
		'ame-bazaar-vto-modal',
		AME_BAZAAR_URI . '/assets/css/vto-modal.css',
		array( 'ame-bazaar-main' ),
		AME_BAZAAR_VERSION
	);

	wp_enqueue_script(
		'ame-bazaar-vto-modal',
		AME_BAZAAR_URI . '/assets/js/vto-modal.js',
		array( 'jquery' ),
		AME_BAZAAR_VERSION,
		true
	);

	global $product;
	$post_id = get_the_ID();
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : null;
	}

	$product_id = ( $product && is_a( $product, 'WC_Product' ) ) ? $product->get_id() : $post_id;
	$garment_url = '';
	$product_name = '';

	if ( $product && is_a( $product, 'WC_Product' ) ) {
		$product_name = $product->get_name();
		$image_id = $product->get_image_id();
		if ( $image_id ) {
			$garment_url = wp_get_attachment_image_url( $image_id, 'large' );
		}
	}

	if ( empty( $garment_url ) && $post_id ) {
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		if ( $thumbnail_id ) {
			$garment_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );
		}
	}

	// Auto-detect garment category based on product categories
	$detected_category = 'upper_body';
	if ( $product_id ) {
		$terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'names' ) );
		$terms_str = strtolower( implode( ' ', (array) $terms ) . ' ' . $product_name );

		if ( strpos( $terms_str, 'saree' ) !== false || strpos( $terms_str, 'dress' ) !== false || strpos( $terms_str, 'suit' ) !== false || strpos( $terms_str, 'kurti' ) !== false || strpos( $terms_str, 'gown' ) !== false ) {
			$detected_category = 'dresses';
		} elseif ( strpos( $terms_str, 'pant' ) !== false || strpos( $terms_str, 'jean' ) !== false || strpos( $terms_str, 'trouser' ) !== false || strpos( $terms_str, 'skirt' ) !== false || strpos( $terms_str, 'shorts' ) !== false ) {
			$detected_category = 'lower_body';
		}
	}

	wp_localize_script(
		'ame-bazaar-vto-modal',
		'ameBazaarVTO',
		array(
			'restUrl'          => esc_url_raw( rest_url( 'ame/v1/' ) ),
			'nonce'            => wp_create_nonce( 'wp_rest' ),
			'productId'        => $product_id,
			'productTitle'     => $product_name ? $product_name : get_the_title( $post_id ),
			'defaultGarment'   => $garment_url,
			'detectedCategory' => $detected_category,
			'i18n'             => array(
				'title'          => __( 'Virtual Try-On Fitting Room', 'ame-bazaar' ),
				'tryItOn'        => __( 'Try It On (AI Mirror)', 'ame-bazaar' ),
				'uploadPrompt'   => __( 'Upload or capture your portrait', 'ame-bazaar' ),
				'serverBusy'     => __( 'Server is busy, try again.', 'ame-bazaar' ),
				'selectPhoto'    => __( 'Please select or capture a person photo first.', 'ame-bazaar' ),
				'generating'     => __( 'AI is perfectly fitting this garment to your body...', 'ame-bazaar' ),
				'fittingSuccess' => __( 'Fit complete! Check your look below.', 'ame-bazaar' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ame_bazaar_enqueue_vto_assets', 20 );

/**
 * 3. Render "Try It On" Action Button on Single Product Page.
 */
function ame_bazaar_render_try_on_button() {
	static $rendered = false;
	if ( $rendered ) {
		return;
	}

	global $product;
	$post_id = get_the_ID();
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : null;
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$rendered = true;
	$image_id = $product->get_image_id();
	$garment_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

	?>
	<div class="ame-vto-product-trigger-wrap" style="margin: 0.85rem 0 1.25rem 0;">
		<button
			type="button"
			id="ame-open-vto-btn"
			class="ame-vto-trigger-button"
			data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
			data-garment-url="<?php echo esc_url( $garment_url ); ?>"
		>
			<span class="ame-vto-btn-sparkle-icon">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M12 2l2.4 7.2L21 12l-6.6 2.8L12 22l-2.4-7.2L3 12l6.6-2.8L12 2z"/>
				</svg>
			</span>
			<span class="ame-vto-btn-text-content">
				<strong class="ame-vto-btn-main-title"><?php esc_html_e( 'Try It On (Virtual AI Mirror)', 'ame-bazaar' ); ?></strong>
				<small class="ame-vto-btn-sub-label"><?php esc_html_e( 'See how this outfit fits your body instantly', 'ame-bazaar' ); ?></small>
			</span>
			<span class="ame-vto-btn-arrow">
				<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
					<polyline points="9 18 15 12 9 6"/>
				</svg>
			</span>
		</button>
	</div>
	<?php
}
add_action( 'woocommerce_after_add_to_cart_button', 'ame_bazaar_render_try_on_button', 15 );
add_action( 'woocommerce_single_product_summary', 'ame_bazaar_render_try_on_button', 35 );

/**
 * 4. Render Virtual Try-On Modal Container in Footer.
 */
function ame_bazaar_render_vto_modal_markup() {
	if ( ! is_product() ) {
		return;
	}
	?>
	<!-- AME Bazaar Virtual Try-On Modal Root -->
	<div id="ame-vto-modal-root" class="ame-vto-modal-backdrop" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="ame-vto-modal-dialog">

			<!-- Modal Header -->
			<div class="ame-vto-modal-header">
				<div class="ame-vto-header-brand">
					<div class="ame-vto-brand-badge">
						<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
							<path d="M12 2l2.4 7.2L21 12l-6.6 2.8L12 22l-2.4-7.2L3 12l6.6-2.8L12 2z"/>
						</svg>
						<span>AME BAZAAR AI FIT</span>
					</div>
					<h3 class="ame-vto-modal-title"><?php esc_html_e( 'Virtual Fitting Room', 'ame-bazaar' ); ?></h3>
					<p class="ame-vto-modal-subtitle"><?php esc_html_e( 'AI garment drape with strict face & identity preservation', 'ame-bazaar' ); ?></p>
				</div>
				<button type="button" class="ame-vto-close-btn" id="ame-vto-close-btn" aria-label="<?php esc_attr_e( 'Close fitting room', 'ame-bazaar' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
			</div>

			<!-- Modal Body Content -->
			<div class="ame-vto-modal-body">

				<!-- Screen 1: Studio Setup & Upload -->
				<div id="ame-vto-setup-screen" class="ame-vto-screen active">
					<div class="ame-vto-grid-layout">

						<!-- Person Photo Upload Card -->
						<div class="ame-vto-card ame-vto-person-card">
							<div class="ame-vto-card-header">
								<span class="ame-vto-step-badge">1</span>
								<div>
									<h4 class="ame-vto-card-title"><?php esc_html_e( 'Your Photo', 'ame-bazaar' ); ?></h4>
									<p class="ame-vto-card-desc"><?php esc_html_e( 'Upload or take a frontal portrait photo', 'ame-bazaar' ); ?></p>
								</div>
							</div>

							<div class="ame-vto-dropzone" id="ame-vto-person-dropzone">
								<input type="file" id="ame-vto-file-input" accept="image/*" class="ame-vto-hidden-input" />

								<!-- Empty Upload State -->
								<div class="ame-vto-empty-upload" id="ame-vto-empty-upload-view">
									<div class="ame-vto-upload-icon-wrap">
										<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8">
											<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
											<polyline points="17 8 12 3 7 8"></polyline>
											<line x1="12" y1="3" x2="12" y2="15"></line>
										</svg>
									</div>
									<p class="ame-vto-dropzone-prompt"><?php esc_html_e( 'Click to browse or drag & drop portrait', 'ame-bazaar' ); ?></p>
									<span class="ame-vto-dropzone-hint"><?php esc_html_e( 'Clear frontal photo with visible upper body', 'ame-bazaar' ); ?></span>

									<div class="ame-vto-quick-actions">
										<button type="button" class="ame-vto-btn-sub" id="ame-vto-camera-btn">
											<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
												<circle cx="12" cy="13" r="4"></circle>
											</svg>
											<span><?php esc_html_e( 'Use Camera', 'ame-bazaar' ); ?></span>
										</button>
									</div>
								</div>

								<!-- Live Image Preview State -->
								<div class="ame-vto-preview-container" id="ame-vto-person-preview-wrap" style="display:none;">
									<img id="ame-vto-person-preview-img" src="" alt="<?php esc_attr_e( 'Customer portrait', 'ame-bazaar' ); ?>" />
									<button type="button" id="ame-vto-remove-person-btn" class="ame-vto-remove-photo-btn" title="<?php esc_attr_e( 'Remove photo', 'ame-bazaar' ); ?>">
										<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
											<line x1="18" y1="6" x2="6" y2="18"></line>
											<line x1="6" y1="6" x2="18" y2="18"></line>
										</svg>
									</button>
								</div>
							</div>
						</div>

						<!-- Selected Garment Card (Auto Loaded from Current Product) -->
						<div class="ame-vto-card ame-vto-garment-card">
							<div class="ame-vto-card-header">
								<span class="ame-vto-step-badge">2</span>
								<div>
									<h4 class="ame-vto-card-title"><?php esc_html_e( 'Garment Preview', 'ame-bazaar' ); ?></h4>
									<p class="ame-vto-card-desc"><?php esc_html_e( 'Current AME Bazaar catalog garment', 'ame-bazaar' ); ?></p>
								</div>
							</div>

							<div class="ame-vto-garment-preview-wrap">
								<img id="ame-vto-garment-img" src="" alt="<?php esc_attr_e( 'Product garment', 'ame-bazaar' ); ?>" />
								<div class="ame-vto-garment-info-tag">
									<strong id="ame-vto-garment-title"><?php esc_html_e( 'Selected Product', 'ame-bazaar' ); ?></strong>
									<span class="ame-vto-verified-tag"><?php esc_html_e( '✓ Ready for Fitting', 'ame-bazaar' ); ?></span>
								</div>
							</div>

							<!-- Category / Fit Mode Selection -->
							<div class="ame-vto-category-select-wrap">
								<label class="ame-vto-control-label"><?php esc_html_e( 'Garment Fit Type:', 'ame-bazaar' ); ?></label>
								<div class="ame-vto-cat-buttons" id="ame-vto-cat-buttons">
									<button type="button" class="ame-vto-cat-btn" data-category="upper_body">
										<span><?php esc_html_e( 'Top / Shirt', 'ame-bazaar' ); ?></span>
									</button>
									<button type="button" class="ame-vto-cat-btn" data-category="dresses">
										<span><?php esc_html_e( 'Dress / Saree / Suit', 'ame-bazaar' ); ?></span>
									</button>
									<button type="button" class="ame-vto-cat-btn" data-category="lower_body">
										<span><?php esc_html_e( 'Pants / Jeans', 'ame-bazaar' ); ?></span>
									</button>
								</div>
							</div>
						</div>

					</div>

					<!-- Error Notice Banner -->
					<div id="ame-vto-error-banner" class="ame-vto-alert-banner" style="display:none;">
						<span id="ame-vto-error-text"></span>
					</div>

					<!-- Action Footer -->
					<div class="ame-vto-modal-footer">
						<div class="ame-vto-guarantees-note">
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
							</svg>
							<span><?php esc_html_e( 'Identity & Face Preserved • Photos are not stored permanently', 'ame-bazaar' ); ?></span>
						</div>

						<button type="button" id="ame-vto-generate-btn" class="ame-vto-submit-button">
							<span class="ame-vto-sparkle-spin">✨</span>
							<span><?php esc_html_e( 'Try It On Now', 'ame-bazaar' ); ?></span>
						</button>
					</div>
				</div>

				<!-- Screen 2: Processing & Loading State -->
				<div id="ame-vto-loading-screen" class="ame-vto-screen" style="display:none;">
					<div class="ame-vto-loading-container">
						<div class="ame-vto-spinner-ring"></div>
						<h4 class="ame-vto-loading-title"><?php esc_html_e( 'Fitting Garment to Your Silhouette', 'ame-bazaar' ); ?></h4>
						<p id="ame-vto-loading-stage" class="ame-vto-loading-stage"><?php esc_html_e( 'Encoding tensors & submitting to IDM-VTON neural engine...', 'ame-bazaar' ); ?></p>

						<div class="ame-vto-loading-progress-bar">
							<div class="ame-vto-progress-fill" id="ame-vto-progress-fill"></div>
						</div>

						<p class="ame-vto-loading-note"><?php esc_html_e( 'Please wait a few moments while AI warps the fabric with photorealistic lighting.', 'ame-bazaar' ); ?></p>
					</div>
				</div>

				<!-- Screen 3: Result Runway View with Split Slider -->
				<div id="ame-vto-result-screen" class="ame-vto-screen" style="display:none;">
					<div class="ame-vto-result-header">
						<div class="ame-vto-result-badge">
							<span>✨ <?php esc_html_e( 'Neural Drape Fitted', 'ame-bazaar' ); ?></span>
						</div>
						<div class="ame-vto-result-view-toggles">
							<button type="button" class="ame-vto-toggle-btn active" data-view="split"><?php esc_html_e( 'Split Slider', 'ame-bazaar' ); ?></button>
							<button type="button" class="ame-vto-toggle-btn" data-view="side"><?php esc_html_e( 'Side-by-Side', 'ame-bazaar' ); ?></button>
							<button type="button" class="ame-vto-toggle-btn" data-view="single"><?php esc_html_e( 'Full Result', 'ame-bazaar' ); ?></button>
						</div>
					</div>

					<!-- Split Comparison Viewport -->
					<div class="ame-vto-comparison-viewport" id="ame-vto-comparison-viewport">
						<!-- Result Image (Underneath) -->
						<img id="ame-vto-final-result-img" class="ame-vto-final-img" src="" alt="<?php esc_attr_e( 'AI Fitted Look', 'ame-bazaar' ); ?>" />

						<!-- Original Photo (Clipped on top) -->
						<div class="ame-vto-clip-layer" id="ame-vto-clip-layer" style="width: 50%;">
							<img id="ame-vto-final-original-img" class="ame-vto-original-img" src="" alt="<?php esc_attr_e( 'Original Portrait', 'ame-bazaar' ); ?>" />
						</div>

						<!-- Slider Handle -->
						<div class="ame-vto-slider-handle" id="ame-vto-slider-handle" style="left: 50%;">
							<div class="ame-vto-handle-knob">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
									<polyline points="15 18 9 12 15 6"></polyline>
								</svg>
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</div>
						</div>

						<span class="ame-vto-view-tag-left"><?php esc_html_e( 'Original Photo', 'ame-bazaar' ); ?></span>
						<span class="ame-vto-view-tag-right"><?php esc_html_e( 'AME Bazaar Fit', 'ame-bazaar' ); ?></span>
					</div>

					<!-- Side by Side Viewport -->
					<div class="ame-vto-side-by-side-grid" id="ame-vto-side-by-side-grid" style="display:none;">
						<div class="ame-vto-side-col">
							<span class="ame-vto-side-label"><?php esc_html_e( 'Original Portrait', 'ame-bazaar' ); ?></span>
							<img id="ame-vto-side-original-img" src="" alt="<?php esc_attr_e( 'Original Portrait', 'ame-bazaar' ); ?>" />
						</div>
						<div class="ame-vto-side-col">
							<span class="ame-vto-side-label"><?php esc_html_e( 'Selected Garment', 'ame-bazaar' ); ?></span>
							<img id="ame-vto-side-garment-img" src="" alt="<?php esc_attr_e( 'Garment', 'ame-bazaar' ); ?>" />
						</div>
						<div class="ame-vto-side-col highlight">
							<span class="ame-vto-side-label"><?php esc_html_e( 'AI Fitted Result', 'ame-bazaar' ); ?></span>
							<img id="ame-vto-side-result-img" src="" alt="<?php esc_attr_e( 'Fitted Result', 'ame-bazaar' ); ?>" />
						</div>
					</div>

					<!-- Single Viewport -->
					<div class="ame-vto-single-viewport" id="ame-vto-single-viewport" style="display:none;">
						<img id="ame-vto-single-result-img" src="" alt="<?php esc_attr_e( 'Fitted Result Full', 'ame-bazaar' ); ?>" />
					</div>

					<!-- Result Action Buttons -->
					<div class="ame-vto-result-actions-footer">
						<button type="button" id="ame-vto-try-another-btn" class="ame-vto-btn-secondary">
							<span><?php esc_html_e( 'Try Another Photo', 'ame-bazaar' ); ?></span>
						</button>
						<button type="button" id="ame-vto-download-btn" class="ame-vto-btn-secondary">
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
								<polyline points="7 10 12 15 17 10"></polyline>
								<line x1="12" y1="15" x2="12" y2="3"></line>
							</svg>
							<span><?php esc_html_e( 'Download Look (HD)', 'ame-bazaar' ); ?></span>
						</button>
						<button type="button" id="ame-vto-continue-shopping-btn" class="ame-vto-submit-button">
							<span><?php esc_html_e( 'Continue Shopping', 'ame-bazaar' ); ?></span>
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'ame_bazaar_render_vto_modal_markup' );
