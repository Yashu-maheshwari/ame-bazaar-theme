<?php
/**
 * Plugin Name: AME Bazaar Local Inventory Feed
 * Description: Secure endpoints to receive, validate, and serve the Google Merchant Center local inventory feed.
 * Version: 1.0.0
 * Author: AME Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AME_Local_Inventory_Feed {
    
    private $store_code = '13106886836526018955';
    private $max_payload_size = 5242880; // 5MB

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        // PUSH Endpoint
        register_rest_route( 'ame/v1', '/update-inventory-csv', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'handle_update' ],
            'permission_callback' => function() {
                return current_user_can( 'manage_woocommerce' );
            },
        ]);

        // GET Endpoint
        // Registering regex to match exactly /local-inventory.csv
        register_rest_route( 'ame/v1', '/local-inventory\.csv', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'handle_get' ],
            'permission_callback' => '__return_true',
        ]);
    }

    private function get_storage_dir() {
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] . '/ame_protected_inventory';
        
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
            // Prevent direct access
            file_put_contents( $dir . '/.htaccess', 'Deny from all' );
            file_put_contents( $dir . '/index.php', '<?php // silence' );
        }
        return $dir;
    }

    public function handle_update( WP_REST_Request $request ) {
        $body = $request->get_body();

        // 1. Payload Size Check
        if ( strlen( $body ) > $this->max_payload_size ) {
            return new WP_Error( 'payload_too_large', 'Payload exceeds maximum size.', [ 'status' => 413 ] );
        }

        if ( empty( trim( $body ) ) ) {
            return new WP_Error( 'empty_payload', 'Payload is empty.', [ 'status' => 400 ] );
        }

        // 2. Parse CSV & Validate
        $lines = explode( "\n", str_replace( "\r", "", trim( $body ) ) );
        if ( count( $lines ) < 2 ) {
            return new WP_Error( 'invalid_csv', 'CSV must contain headers and at least one product row.', [ 'status' => 400 ] );
        }

        $headers = str_getcsv( array_shift( $lines ) );
        $expected_headers = [ 'store_code', 'id', 'price', 'availability' ];
        if ( $headers !== $expected_headers ) {
            return new WP_Error( 'invalid_headers', 'Exact headers required: store_code,id,price,availability', [ 'status' => 400 ] );
        }

        $seen_ids = [];
        $valid_rows = 0;

        foreach ( $lines as $line_num => $line ) {
            if ( empty( trim( $line ) ) ) continue;
            
            $row = str_getcsv( $line );
            if ( count( $row ) !== 4 ) {
                return new WP_Error( 'malformed_row', 'Row ' . ($line_num + 2) . ' is malformed.', [ 'status' => 400 ] );
            }

            list( $r_store, $r_id, $r_price, $r_avail ) = $row;

            if ( $r_store !== $this->store_code ) {
                return new WP_Error( 'invalid_store', 'Invalid store_code at row ' . ($line_num + 2), [ 'status' => 400 ] );
            }
            if ( empty( $r_id ) ) {
                return new WP_Error( 'invalid_id', 'Empty ID at row ' . ($line_num + 2), [ 'status' => 400 ] );
            }
            if ( in_array( $r_id, $seen_ids ) ) {
                return new WP_Error( 'duplicate_id', 'Duplicate ID detected: ' . $r_id, [ 'status' => 400 ] );
            }
            if ( ! preg_match( '/^\d+(\.\d{1,2})? INR$/', $r_price ) ) {
                return new WP_Error( 'invalid_price', 'Invalid price format at row ' . ($line_num + 2) . '. Must be e.g. "1625.00 INR"', [ 'status' => 400 ] );
            }
            if ( ! in_array( $r_avail, [ 'in_stock', 'out_of_stock' ] ) ) {
                return new WP_Error( 'invalid_availability', 'Availability must be in_stock or out_of_stock at row ' . ($line_num + 2), [ 'status' => 400 ] );
            }

            $seen_ids[] = $r_id;
            $valid_rows++;
        }

        // 3. Safety Check: 90% collapse prevention
        $dir = $this->get_storage_dir();
        $target_file = $dir . '/local_inventory.csv';
        
        if ( file_exists( $target_file ) ) {
            // Count existing data rows (excluding header)
            $existing_lines = file( $target_file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES );
            $existing_count = count( $existing_lines ) > 1 ? count( $existing_lines ) - 1 : 0;
            
            // Only trigger safety stop if we previously had significant inventory (e.g., > 10 items)
            if ( $existing_count > 10 ) {
                $threshold = $existing_count * 0.10;
                if ( $valid_rows < $threshold ) {
                    return new WP_Error( 'safety_stop', 'Safety stop: Feed dropped by >90% (from ' . $existing_count . ' to ' . $valid_rows . '). Update aborted.', [ 'status' => 400 ] );
                }
            }
        }

        // 4. Atomic Write
        $temp_file = $dir . '/local_inventory_temp_' . time() . '.csv';
        $write_result = file_put_contents( $temp_file, $body );
        
        if ( $write_result === false ) {
            return new WP_Error( 'write_error', 'Failed to write temporary file.', [ 'status' => 500 ] );
        }

        if ( ! rename( $temp_file, $target_file ) ) {
            unlink( $temp_file );
            return new WP_Error( 'rename_error', 'Failed to replace active feed atomically.', [ 'status' => 500 ] );
        }

        return rest_ensure_response( [
            'success' => true,
            'message' => 'Local inventory updated successfully.',
            'rows_accepted' => $valid_rows
        ]);
    }

    public function handle_get( WP_REST_Request $request ) {
        $dir = $this->get_storage_dir();
        $target_file = $dir . '/local_inventory.csv';

        if ( ! file_exists( $target_file ) ) {
            return new WP_Error( 'not_found', 'No valid local inventory feed found.', [ 'status' => 404 ] );
        }

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );
        
        readfile( $target_file );
        exit;
    }
}

new AME_Local_Inventory_Feed();
