<?php
/**
 * Plugin Name: AME Bazaar QR Shortlinks
 * Description: Redirects /p/{SKU} to the WooCommerce product permalink. Keeps 1D barcodes for billing.
 * Version: 1.0.0
 * Author: AME Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Register the Custom URL Route
add_action( 'init', 'ame_bazaar_qr_shortlink_rewrite' );
function ame_bazaar_qr_shortlink_rewrite() {
    add_rewrite_tag( '%sku_shortlink%', '([^/]+)' );
    // Map /p/SKU to the custom query variable
    add_rewrite_rule( '^p/([^/]+)/?$', 'index.php?sku_shortlink=$matches[1]', 'top' );
}

// 2. Intercept the Request and Redirect
add_action( 'template_redirect', 'ame_bazaar_qr_shortlink_redirect' );
function ame_bazaar_qr_shortlink_redirect() {
    global $wp_query;
    
    // Check if this is our custom route
    if ( $sku = $wp_query->get( 'sku_shortlink' ) ) {
        // Sanitize the input to prevent XSS or open redirect issues
        $sku = sanitize_text_field( wp_unslash( $sku ) );
        
        // Ensure WooCommerce function exists
        if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
            wp_die( 'WooCommerce is required for QR shortlinks to function.' );
        }

        // Lookup WooCommerce Product by SKU
        $product_id = wc_get_product_id_by_sku( $sku );
        
        if ( $product_id && get_post_status( $product_id ) === 'publish' ) {
            // Found and Published: Redirect Temporary (302) as requested for testing
            $permalink = get_permalink( $product_id );
            
            // Validate the permalink
            if ( wp_http_validate_url( $permalink ) ) {
                wp_safe_redirect( $permalink, 302 );
                exit;
            }
        } 
        
        // Not Found, Invalid, or Draft: Fallback to Shop Search Page safely
        $search_url = home_url( '/?s=' . urlencode( $sku ) . '&post_type=product' );
        wp_safe_redirect( $search_url, 302 );
        exit;
    }
}
