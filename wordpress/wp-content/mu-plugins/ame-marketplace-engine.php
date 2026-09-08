<?php
/**
 * Plugin Name: AME Bazaar Marketplace Engine
 * Description: Reusable Marketplace Engine to connect AME Bazaar with multiple marketplaces (Meesho, Amazon, etc.).
 * Version: 1.0.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Main Marketplace Engine Class
 */
class AME_Marketplace_Engine {

    private static $instance = null;
    private $adapters = [];

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register activation hook for creating tables (mu-plugins don't have standard activation hooks, so we check on admin_init)
        add_action( 'admin_init', [ $this, 'maybe_create_tables' ] );
        
        // Add Admin Menu
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        
        // Register default adapters
        $this->register_adapters();
    }

    public function maybe_create_tables() {
        $db_version = get_option( 'ame_marketplace_db_version', '0' );
        if ( version_compare( $db_version, '1.0.0', '<' ) ) {
            $this->create_schema();
            update_option( 'ame_marketplace_db_version', '1.0.0' );
        }
    }

    private function create_schema() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ame_marketplace_mappings';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            ame_product_id bigint(20) unsigned NOT NULL,
            marketplace varchar(50) NOT NULL,
            account_id varchar(100) NOT NULL,
            external_product_id varchar(100) DEFAULT NULL,
            external_catalog_id varchar(100) DEFAULT NULL,
            marketplace_status varchar(50) DEFAULT NULL,
            sync_status varchar(50) DEFAULT NULL,
            sync_error text DEFAULT NULL,
            marketplace_metadata longtext DEFAULT NULL,
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY ame_product_id (ame_product_id),
            KEY marketplace_account (marketplace, account_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function register_adapters() {
        $this->adapters['meesho'] = new AME_Meesho_Adapter();
        // Future: $this->adapters['amazon'] = new AME_Amazon_Adapter();
    }

    public function register_admin_menu() {
        add_menu_page(
            'Marketplaces',
            'Marketplaces',
            'manage_woocommerce',
            'ame-marketplaces',
            [ $this, 'render_admin_page' ],
            'dashicons-store',
            56
        );
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>AME Bazaar Marketplace Engine</h1>
            <p>Connect and manage multiple marketplaces from a single source of truth.</p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Marketplace</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Meesho</strong></td>
                        <td>Not Connected</td>
                        <td><a href="#" class="button button-primary">Configure</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Generic sync product method
     */
    public function sync_product( $ame_product_id, $marketplace, $account_id ) {
        if ( isset( $this->adapters[ $marketplace ] ) ) {
            return $this->adapters[ $marketplace ]->publish_product( $ame_product_id, $account_id );
        }
        return false;
    }
}

/**
 * Abstract Marketplace Adapter
 */
abstract class AME_Marketplace_Adapter {
    abstract public function get_name();
    abstract public function get_configuration_fields();
    
    // Core capabilities
    abstract public function publish_product( $ame_product_id, $account_id );
    abstract public function update_product( $ame_product_id, $account_id );
    abstract public function update_inventory( $ame_product_id, $account_id, $stock_qty );
    abstract public function update_price( $ame_product_id, $account_id, $price );
}

/**
 * Meesho Adapter Implementation
 */
class AME_Meesho_Adapter extends AME_Marketplace_Adapter {
    
    public function get_name() {
        return 'Meesho';
    }

    public function get_configuration_fields() {
        return [
            'merchant_id' => 'Merchant ID',
            'supplier_identifier' => 'Supplier Identifier',
            'api_secret' => 'API Secret',
        ];
    }

    public function publish_product( $ame_product_id, $account_id ) {
        // TODO: Implement actual API V2 payload construction and push
        // 1. Fetch WooCommerce product data
        // 2. Fetch Meesho-specific metadata from wp_ame_marketplace_mappings
        // 3. Transform to Meesho format
        // 4. Send API request
        // 5. Update sync_status and last_synced_at in mapping table
        return [ 'status' => 'pending', 'message' => 'Not implemented yet' ];
    }

    public function update_product( $ame_product_id, $account_id ) {
        // TODO: Implement product update
    }

    public function update_inventory( $ame_product_id, $account_id, $stock_qty ) {
        // TODO: Implement inventory sync
    }

    public function update_price( $ame_product_id, $account_id, $price ) {
        // TODO: Implement price sync
    }
}

// Initialize the engine
AME_Marketplace_Engine::get_instance();
