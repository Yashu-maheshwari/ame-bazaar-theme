<?php
/**
 * AME Image Upload Helper
 * 
 * Provides a WooCommerce-authenticated REST endpoint to upload images
 * to the WordPress Media Library. Uses WC consumer key/secret auth.
 * 
 * Endpoint: POST /wp-json/ame/v1/upload-image
 * Body: { "filename": "P-0170.jpg", "data": "<base64 encoded image>" }
 * Returns: { "media_id": 12345, "source_url": "https://..." }
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', function() {
    register_rest_route( 'ame/v1', '/upload-image', [
        'methods'             => 'POST',
        'callback'            => 'ame_handle_image_upload',
        'permission_callback' => function( $request ) {
            // Use WooCommerce authentication (consumer key/secret)
            return current_user_can( 'upload_files' );
        },
    ]);
});

function ame_handle_image_upload( $request ) {
    $filename = sanitize_file_name( $request->get_param('filename') );
    $data     = $request->get_param('data');
    
    if ( empty( $filename ) || empty( $data ) ) {
        return new WP_Error( 'missing_params', 'filename and data are required', [ 'status' => 400 ] );
    }
    
    $decoded = base64_decode( $data, true );
    if ( $decoded === false || strlen( $decoded ) < 100 ) {
        return new WP_Error( 'invalid_data', 'Invalid or corrupt image data', [ 'status' => 400 ] );
    }
    
    // Validate MIME
    $finfo = new finfo( FILEINFO_MIME_TYPE );
    $mime  = $finfo->buffer( $decoded );
    $allowed = [ 'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp' ];
    if ( ! in_array( $mime, $allowed ) ) {
        return new WP_Error( 'invalid_mime', "Invalid image type: $mime", [ 'status' => 400 ] );
    }
    
    // Write to temp file
    $tmp = wp_tempnam( $filename );
    file_put_contents( $tmp, $decoded );
    
    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
        'size'     => strlen( $decoded ),
        'error'    => UPLOAD_ERR_OK,
    ];
    
    // Sideload into Media Library
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    
    $media_id = media_handle_sideload( $file_array, 0 );
    
    if ( is_wp_error( $media_id ) ) {
        @unlink( $tmp );
        return new WP_Error( 'upload_failed', $media_id->get_error_message(), [ 'status' => 500 ] );
    }
    
    return rest_ensure_response([
        'media_id'   => $media_id,
        'source_url' => wp_get_attachment_url( $media_id ),
    ]);
}
