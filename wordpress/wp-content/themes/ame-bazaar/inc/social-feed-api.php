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
        if ( is_array( $data ) && ! empty( $data['error']['message'] ) ) {
            $message = $data['error']['message'];
        }
        return new WP_Error( 'meta_graph_error', $message, array( 'status' => $status ) );
    }

    return $data;
}

function ame_bazaar_social_feed_proxy_callback() {
    if ( ! defined( 'AME_META_ACCESS_TOKEN' ) || ! AME_META_ACCESS_TOKEN ||
        ! defined( 'AME_META_FACEBOOK_PAGE_ID' ) || ! AME_META_FACEBOOK_PAGE_ID ||
        ! defined( 'AME_META_INSTAGRAM_USER_ID' ) || ! AME_META_INSTAGRAM_USER_ID ) {
        return new WP_Error( 'social_feed_not_configured', 'Meta social feed is not configured.', array( 'status' => 503 ) );
    }

    $cache_key = 'ame_bazaar_social_feed_v2';
    $cached    = get_transient( $cache_key );

    if ( false !== $cached && is_array( $cached ) ) {
        return new WP_REST_Response( $cached, 200 );
    }

    $user_token = AME_META_ACCESS_TOKEN;

    // Meta's Facebook Login flow uses a user token to obtain the Page access
    // token that acts on behalf of the linked Facebook Page/Instagram account.
    $page = ame_bazaar_meta_graph_request(
        AME_META_FACEBOOK_PAGE_ID,
        $user_token,
        array(
            'fields' => 'name,access_token,instagram_business_account',
        )
    );

    if ( is_wp_error( $page ) ) {
        return new WP_Error( 'social_feed_page_auth', 'Could not connect to the AME Bazaar Facebook Page.', array( 'status' => 502 ) );
    }

    $page_token = ! empty( $page['access_token'] ) ? $page['access_token'] : $user_token;
    $page_name  = ! empty( $page['name'] ) ? $page['name'] : 'AME Bazaar';

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
        $facebook_posts = array( 'data' => array() );
    }

    // Instagram profile metadata.
    $instagram_profile = ame_bazaar_meta_graph_request(
        AME_META_INSTAGRAM_USER_ID,
        $page_token,
        array(
            'fields' => 'id,username,name,biography,profile_picture_url,followers_count,follows_count,media_count',
        )
    );

    if ( is_wp_error( $instagram_profile ) ) {
        $instagram_profile = array(
            'id'       => AME_META_INSTAGRAM_USER_ID,
            'username' => 'ame_bazaar',
        );
    }

    // Instagram: latest media from the connected Professional account.
    $instagram_media = ame_bazaar_meta_graph_request(
        AME_META_INSTAGRAM_USER_ID . '/media',
        $page_token,
        array(
            'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
            'limit'  => 9,
        )
    );

    if ( is_wp_error( $instagram_media ) ) {
        $instagram_media = array( 'data' => array() );
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
        ),
        'instagram' => array(
            'profile' => $instagram_profile,
            'posts'   => ! empty( $instagram_media['data'] ) ? $instagram_media['data'] : array(),
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
