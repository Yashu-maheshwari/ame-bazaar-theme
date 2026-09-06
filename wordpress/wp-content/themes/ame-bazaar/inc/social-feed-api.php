<?php
/**
 * Public read-only proxy for the AME Bazaar social feed Google Apps Script.
 *
 * Meta credentials remain inside Apps Script. WordPress only proxies the
 * public feed payload to the same-origin frontend to avoid browser CORS.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ame_bazaar_social_feed_proxy_callback() {
    $gas_url   = 'https://script.google.com/macros/s/AKfycbxoBZ3tVKbFto_3DqrM0qVUd0Nda09cacHmOCi2p_y0bFcUuljQiGzx3sUBpO4RNmNf/exec';
    $cache_key = 'ame_bazaar_social_feed_v1';
    $cached    = get_transient( $cache_key );

    if ( false !== $cached && is_array( $cached ) ) {
        return new WP_REST_Response( $cached, 200 );
    }

    $response = wp_remote_get(
        add_query_arg(
            array(
                'action' => 'social_feed',
                'limit'  => 9,
                '_'      => time(),
            ),
            $gas_url
        ),
        array(
            'timeout' => 15,
            'headers' => array( 'Accept' => 'application/json' ),
        )
    );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'social_feed_unavailable', 'Social feed source is unavailable.', array( 'status' => 502 ) );
    }

    $status = wp_remote_retrieve_response_code( $response );
    $body   = wp_remote_retrieve_body( $response );
    $data   = json_decode( $body, true );

    if ( $status < 200 || $status >= 300 || ! is_array( $data ) ) {
        return new WP_Error( 'social_feed_invalid', 'Social feed source did not return valid JSON.', array( 'status' => 502 ) );
    }

    set_transient( $cache_key, $data, 5 * MINUTE_IN_SECONDS );

    return new WP_REST_Response( $data, 200 );
}

add_action( 'rest_api_init', function () {
    register_rest_route(
        'ame/v1',
        '/social-feed',
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'ame_bazaar_social_feed_proxy_callback',
            'permission_callback' => '__return_true',
        )
    );
} );
