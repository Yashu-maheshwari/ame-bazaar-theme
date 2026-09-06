<?php
/**
 * Server-side live Facebook + Instagram feed for AME Bazaar.
 *
 * Meta credentials are stored outside the theme in wp-content/ame-social-secrets.php.
 * The browser only receives the normalized public feed payload through the
 * same-origin WordPress REST endpoint.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( file_exists( WP_CONTENT_DIR . '/ame-social-secrets.php' ) ) {
    require_once WP_CONTENT_DIR . '/ame-social-secrets.php';
}

function ame_bazaar_meta_graph_request( $path, $access_token, $query = array() ) {
    $version = defined( 'AME_META_GRAPH_VERSION' ) ? AME_META_GRAPH_VERSION : 'v25.0';
    $url     = 'https://graph.facebook.com/' . $version . '/' . ltrim( $path, '/' );
    $query['access_token'] = $access_token;

    $response = wp_remote_get(
        add_query_arg( $query, $url ),
        array(
            'timeout' => 15,
            'headers' => array( 'Accept' => 'application/json' ),
        )
    );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $status = wp_remote_retrieve_response_code( $response );
    $body   = wp_remote_retrieve_body( $response );
    $data   = json_decode( $body, true );

    if ( $status < 200 || $status >= 300 || ! is_array( $data ) ) {
        $message = 'Meta Graph API request failed.';
        $error   = array();
        if ( is_array( $data ) && ! empty( $data['error'] ) && is_array( $data['error'] ) ) {
            $error = $data['error'];
            if ( ! empty( $error['message'] ) ) {
                $message = $error['message'];
            }
        }
        return new WP_Error(
            'meta_graph_error',
            $message,
            array(
                'status'    => $status,
                'meta_code' => ! empty( $error['code'] ) ? (int) $error['code'] : 0,
                'meta_type' => ! empty( $error['type'] ) ? sanitize_key( $error['type'] ) : '',
            )
        );
    }

    return $data;
}

function ame_bazaar_social_error_summary( $error ) {
    if ( ! is_wp_error( $error ) ) {
        return '';
    }

    $data = $error->get_error_data();
    $code = is_array( $data ) && ! empty( $data['meta_code'] ) ? (int) $data['meta_code'] : 0;

    if ( 190 === $code ) {
        return 'meta_token_expired';
    }
    if ( in_array( $code, array( 10, 200 ), true ) ) {
        return 'meta_permission_denied';
    }
    if ( 100 === $code ) {
        return 'meta_invalid_parameter';
    }
    if ( is_array( $data ) && ! empty( $data['status'] ) && (int) $data['status'] >= 500 ) {
        return 'meta_service_unavailable';
    }

    return 'meta_connection_error';
}

function ame_bazaar_social_feed_proxy_callback() {
    if ( ! defined( 'AME_META_ACCESS_TOKEN' ) || ! AME_META_ACCESS_TOKEN ||
        ! defined( 'AME_META_FACEBOOK_PAGE_ID' ) || ! AME_META_FACEBOOK_PAGE_ID ) {
        return new WP_Error( 'social_feed_not_configured', 'Meta social feed is not configured.', array( 'status' => 503 ) );
    }

    $cache_key = 'ame_bazaar_social_feed_v3';
    $cached    = get_transient( $cache_key );

    if ( false !== $cached && is_array( $cached ) ) {
        return new WP_REST_Response( $cached, 200 );
    }

    $user_token = AME_META_ACCESS_TOKEN;

    // Resolve the Page and its linked Professional Instagram account from the
    // same Meta connection. This avoids relying on a stale/mismatched IG ID.
    $page = ame_bazaar_meta_graph_request(
        AME_META_FACEBOOK_PAGE_ID,
        $user_token,
        array(
            'fields' => 'id,name,access_token,instagram_business_account',
        )
    );

    if ( is_wp_error( $page ) ) {
        return new WP_Error(
            'social_feed_page_auth',
            'Could not connect to the AME Bazaar Facebook Page.',
            array(
                'status'  => 502,
                'reason'  => ame_bazaar_social_error_summary( $page ),
                'meta_code' => (int) ( is_array( $page->get_error_data() ) && ! empty( $page->get_error_data()['meta_code'] ) ? $page->get_error_data()['meta_code'] : 0 ),
            )
        );
    }

    $page_token  = ! empty( $page['access_token'] ) ? $page['access_token'] : $user_token;
    $page_name   = ! empty( $page['name'] ) ? $page['name'] : 'AME Bazaar';
    $linked_ig   = ! empty( $page['instagram_business_account']['id'] ) ? (string) $page['instagram_business_account']['id'] : '';
    $configured_ig = defined( 'AME_META_INSTAGRAM_USER_ID' ) ? trim( (string) AME_META_INSTAGRAM_USER_ID ) : '';
    $ig_user_id  = $linked_ig ? $linked_ig : $configured_ig;

    $facebook_error  = '';
    $instagram_error = '';

    // Facebook: own Page posts.
    $facebook_posts = ame_bazaar_meta_graph_request(
        AME_META_FACEBOOK_PAGE_ID . '/posts',
        $page_token,
        array(
            'fields' => 'id,message,created_time,full_picture,permalink_url',
            'limit'  => 3,
        )
    );

    if ( is_wp_error( $facebook_posts ) ) {
        $facebook_error = ame_bazaar_social_error_summary( $facebook_posts );
        $facebook_posts = array( 'data' => array() );
    }

    $instagram_profile = array(
        'id'       => $ig_user_id,
        'username' => 'ame_bazaar',
    );
    $instagram_media = array( 'data' => array() );

    if ( $ig_user_id ) {
        // Instagram profile metadata.
        $profile_response = ame_bazaar_meta_graph_request(
            $ig_user_id,
            $page_token,
            array(
                'fields' => 'id,username,name,biography,profile_picture_url,followers_count,follows_count,media_count',
            )
        );

        if ( is_wp_error( $profile_response ) ) {
            $instagram_error = ame_bazaar_social_error_summary( $profile_response );
        } else {
            $instagram_profile = $profile_response;
        }

        // Instagram: latest media from the connected Professional account.
        $instagram_media_response = ame_bazaar_meta_graph_request(
            $ig_user_id . '/media',
            $page_token,
            array(
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
                'limit'  => 9,
            )
        );

        if ( is_wp_error( $instagram_media_response ) ) {
            $instagram_error = ame_bazaar_social_error_summary( $instagram_media_response );
        } else {
            $instagram_media = $instagram_media_response;
        }
    } else {
        $instagram_error = 'instagram_not_linked';
    }

    $payload = array(
        'success'    => true,
        'source'     => 'meta_graph_api',
        'updated_at' => gmdate( 'c' ),
        'facebook'   => array(
            'profile' => array(
                'name' => $page_name,
            ),
            'posts'   => ! empty( $facebook_posts['data'] ) ? $facebook_posts['data'] : array(),
            'error'   => $facebook_error,
        ),
        'instagram' => array(
            'profile' => $instagram_profile,
            'posts'   => ! empty( $instagram_media['data'] ) ? $instagram_media['data'] : array(),
            'error'   => $instagram_error,
        ),
    );

    set_transient( $cache_key, $payload, 5 * MINUTE_IN_SECONDS );

    return new WP_REST_Response( $payload, 200 );
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
