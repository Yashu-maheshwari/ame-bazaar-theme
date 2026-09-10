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
        
        // Handle CSV Export
        add_action( 'admin_post_export_meesho_csv', [ $this, 'export_meesho_csv' ] );
        
        // Register default adapters
        $this->register_adapters();
    }

    public function export_meesho_csv() {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( 'Unauthorized access' );
        }

        $results = $this->scan_woocommerce_catalog();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=meesho_bulk_catalog_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // Compulsory Meesho Headers (Simplified for generation)
        fputcsv($output, [
            'Product Name', 
            'Description', 
            'Category', 
            'SKU', 
            'MRP', 
            'Selling Price', 
            'Weight (gms)', 
            'Stock', 
            'Image URL 1'
        ]);

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ];

        $products = new WP_Query( $args );

        if ( $products->have_posts() ) {
            while ( $products->have_posts() ) {
                $products->the_post();
                $product = wc_get_product( get_the_ID() );
                
                if ( ! $product ) continue;

                $pName = strtolower( $product->get_name() );
                $terms = wc_get_product_terms( $product->get_id(), 'product_cat' );
                $catNames = array_map( function( $cat ) { return strtolower( $cat->name ); }, is_array($terms) && !is_wp_error($terms) ? $terms : [] );
                $catString = implode( ' ', $catNames );
                
                $weight = 500;
                if ( strpos( $pName, 'gown' ) !== false || strpos( $pName, 'coat pant' ) !== false || strpos( $pName, 'coat-pant' ) !== false || strpos( $pName, 'suit' ) !== false || strpos( $pName, 'sherwani' ) !== false ||
                     strpos( $catString, 'gown' ) !== false || strpos( $catString, 'coat pant' ) !== false || strpos( $catString, 'coat-pant' ) !== false || strpos( $catString, 'suit' ) !== false || strpos( $catString, 'sherwani' ) !== false ) {
                    $weight = 1500;
                }

                $image_id = $product->get_image_id();
                if ( empty( $image_id ) && ! empty( $product->get_sku() ) ) {
                    global $wpdb;
                    $recovered_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title LIKE %s LIMIT 1", '%' . $wpdb->esc_like( $product->get_sku() ) . '%' ) );
                    if ( $recovered_id ) {
                        $image_id = $recovered_id;
                    }
                }

                $mappedCategory = $this->get_meesho_category_and_status( $product );
                $sku = $product->get_sku() ?: 'R-' . $product->get_id(); // Safe SKU fallback

                // Check readiness (skip if missing vital info)
                if ( empty( $sku ) || empty( $product->get_price() ) || empty( $image_id ) || $mappedCategory === 'needs_review' ) {
                    continue; 
                }

                $image_url = wp_get_attachment_url( $image_id );
                
                fputcsv($output, [
                    $product->get_name(),
                    wp_strip_all_tags( $product->get_description() ),
                    $mappedCategory,
                    $sku,
                    $product->get_regular_price() ?: $product->get_price(),
                    $product->get_price(),
                    $weight, // Use derived Meesho-specific weight
                    $product->get_manage_stock() ? $product->get_stock_quantity() : 100,
                    $image_url
                ]);
            }
        }
        wp_reset_postdata();

        fclose($output);
        exit;
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
        $scan_results = $this->scan_woocommerce_catalog();
        
        ?>
        <div class="wrap">
            <h1>AME Bazaar Marketplace Engine</h1>
            <p>Connect and manage multiple marketplaces from a single source of truth.</p>
            
            <h2 class="nav-tab-wrapper">
                <a href="#" class="nav-tab nav-tab-active">Dashboard</a>
                <a href="#" class="nav-tab">Meesho Bulk Export</a>
            </h2>

            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>WooCommerce Catalog Readiness Report</h2>
                <p>Analyzing products for marketplace integration readiness.</p>
                
                <table class="widefat striped">
                    <tbody>
                        <tr>
                            <th>Total WooCommerce Products Found:</th>
                            <td><strong><?php echo esc_html( $scan_results['total'] ); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Ready for Publishing:</th>
                            <td style="color: green;"><strong><?php echo esc_html( $scan_results['ready'] ); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Missing Required Fields:</th>
                            <td style="color: red;"><strong><?php echo esc_html( $scan_results['missing'] ); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                
                <?php if ( ! empty( $scan_results['missing_details'] ) ) : ?>
                    <h3 style="margin-top: 20px;">Products Missing Data</h3>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="widefat fixed striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Missing Fields</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $scan_results['missing_details'] as $detail ) : ?>
                                    <tr>
                                        <td>#<?php echo esc_html( $detail['id'] ); ?></td>
                                        <td><?php echo esc_html( $detail['name'] ); ?></td>
                                        <td style="color: red;"><?php echo esc_html( implode( ', ', $detail['missing'] ) ); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <h3>Next Action</h3>
                    <p>Since direct API V2 catalog creation requires a support ticket and approval, the current most automated legitimate workflow is the <strong>Official Bulk Catalog Upload</strong>.</p>
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="export_meesho_csv">
                        <?php submit_button( 'Generate Meesho Bulk Excel/CSV (Option B)', 'primary', 'submit', false ); ?>
                        <span class="description"> Generates a CSV file matching Meesho's bulk upload format using your WooCommerce products.</span>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Scans WooCommerce catalog to check readiness for Meesho
     */
    private function scan_woocommerce_catalog() {
        $results = [
            'total' => 0,
            'ready' => 0,
            'missing' => 0,
            'missing_details' => []
        ];

        if ( ! class_exists( 'WooCommerce' ) ) {
            return $results;
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ];

        $products = new WP_Query( $args );
        $results['total'] = $products->found_posts;

        if ( $products->have_posts() ) {
            while ( $products->have_posts() ) {
                $products->the_post();
                $product = wc_get_product( get_the_ID() );
                
                if ( ! $product ) continue;

                // Check readiness
                $pName = strtolower( $product->get_name() );
                $terms = wc_get_product_terms( $product->get_id(), 'product_cat' );
                $catNames = array_map( function( $cat ) { return strtolower( $cat->name ); }, is_array($terms) && !is_wp_error($terms) ? $terms : [] );
                $catString = implode( ' ', $catNames );
                
                $weight = 500;
                if ( strpos( $pName, 'gown' ) !== false || strpos( $pName, 'coat pant' ) !== false || strpos( $pName, 'coat-pant' ) !== false || strpos( $pName, 'suit' ) !== false || strpos( $pName, 'sherwani' ) !== false ||
                     strpos( $catString, 'gown' ) !== false || strpos( $catString, 'coat pant' ) !== false || strpos( $catString, 'coat-pant' ) !== false || strpos( $catString, 'suit' ) !== false || strpos( $catString, 'sherwani' ) !== false ) {
                    $weight = 1500;
                }

                $image_id = $product->get_image_id();
                if ( empty( $image_id ) && ! empty( $product->get_sku() ) ) {
                    global $wpdb;
                    $recovered_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title LIKE %s LIMIT 1", '%' . $wpdb->esc_like( $product->get_sku() ) . '%' ) );
                    if ( $recovered_id ) {
                        $image_id = $recovered_id;
                    }
                }

                $mappedCategory = $this->get_meesho_category_and_status( $product );
                $sku = $product->get_sku() ?: 'R-' . $product->get_id(); // Safe SKU fallback

                $missing_fields = [];
                if ( empty( $sku ) ) { $missing_fields[] = 'SKU'; }
                if ( empty( $product->get_price() ) ) { $missing_fields[] = 'Price'; }
                if ( empty( $image_id ) ) { $missing_fields[] = 'missing_image'; }
                if ( $mappedCategory === 'needs_review' ) { $missing_fields[] = 'needs_review'; }
                
                if ( empty( $missing_fields ) ) {
                    $results['ready']++;
                } else {
                    $results['missing']++;
                    $results['missing_details'][] = [
                        'id' => get_the_ID(),
                        'name' => get_the_title(),
                        'missing' => $missing_fields
                    ];
                }
            }
        }
        wp_reset_postdata();

        return $results;
    }

    private function get_meesho_category_and_status( $product ) {
        $pName = strtolower( $product->get_name() );
        $terms = wc_get_product_terms( $product->get_id(), 'product_cat' );
        $catNames = array_map( function( $cat ) { return strtolower( $cat->name ); }, is_array($terms) && !is_wp_error($terms) ? $terms : [] );
        $catString = implode( ' ', $catNames );
        $str = $pName . ' ' . $catString;

        $isBoys = strpos($str, 'boys') !== false || strpos($str, 'baba suit') !== false || strpos($str, 'boy') !== false;
        $isGirls = strpos($str, 'girls') !== false || strpos($str, 'frock') !== false || strpos($str, 'girl') !== false;
        $isKids = $isBoys || $isGirls || strpos($str, 'infant') !== false || strpos($str, 'baby') !== false;
        $isMen = strpos($str, 'men') !== false || (!$isKids && strpos($str, 'women') === false && strpos($str, 'lady') === false);
        $isWomen = strpos($str, 'women') !== false || strpos($str, 'lady') !== false || strpos($str, 'kurti') !== false || strpos($str, 'gown') !== false || strpos($str, 'lehenga') !== false;

        $cat = 'category_mapping_required';
        $conf = 'None';

        // High Confidence Exact Matches
        if (strpos($str, 'gown') !== false) { $cat = 'Women > Ethnic Wear > Gowns'; $conf = 'High'; }
        elseif (strpos($str, 'sherwani') !== false) { $cat = 'Men > Ethnic Wear > Sherwanis'; $conf = 'High'; }
        elseif (strpos($str, 'suit') !== false && $isMen && strpos($str, 'track') === false && strpos($str, 'baba') === false) { $cat = 'Men > Western Wear > Suits'; $conf = 'High'; }
        elseif (strpos($str, 'coat pant') !== false || strpos($str, 'coat-pant') !== false || strpos($str, 'blazer') !== false) { $cat = 'Men > Western Wear > Suits'; $conf = 'High'; }
        elseif (strpos($str, 'baba suit') !== false || strpos($str, 'h/s set') !== false || strpos($str, 'f/s set') !== false || strpos($str, 'cloth set') !== false) { $cat = 'Kids > Boys Clothing > Clothing Sets'; $conf = 'High'; }
        elseif (strpos($str, 'kurta pajama') !== false) { $cat = 'Men > Ethnic Wear > Kurta Sets'; $conf = 'High'; }
        elseif (strpos($str, 'frock') !== false) { $cat = 'Kids > Girls Clothing > Frocks & Dresses'; $conf = 'High'; }
        elseif (strpos($str, 'kurti') !== false || strpos($str, 'kurti set') !== false) { $cat = 'Women > Ethnic Wear > Kurtis'; $conf = 'High'; }
        elseif (strpos($str, 'dress') !== false && ($isGirls || $isWomen)) { $cat = $isGirls ? 'Kids > Girls Clothing > Frocks & Dresses' : 'Women > Western Wear > Dresses'; $conf = 'High'; }
        
        // Deeper Classification for large generic categories (Boys/Girls/Men/Uncategorized)
        elseif (strpos($str, 'shirt') !== false && strpos($str, 'tshirt') === false && strpos($str, 't-shirt') === false && strpos($str, 'sweat') === false) {
            $cat = $isKids ? 'Kids > Boys Clothing > Shirts' : ($isWomen ? 'Women > Western Wear > Shirts' : 'Men > Western Wear > Shirts');
            $conf = 'High';
        }
        elseif (strpos($str, 'tshirt') !== false || strpos($str, 't shirt') !== false || strpos($str, 't-shirt') !== false) {
            $cat = $isKids ? 'Kids > Boys Clothing > Tshirts' : ($isWomen ? 'Women > Western Wear > Tshirts' : 'Men > Western Wear > Tshirts');
            $conf = 'High';
        }
        elseif (strpos($str, 'jeans') !== false) {
            $cat = $isKids ? ($isGirls ? 'Kids > Girls Clothing > Jeans' : 'Kids > Boys Clothing > Jeans') : ($isWomen ? 'Women > Western Wear > Jeans' : 'Men > Western Wear > Jeans');
            $conf = 'High';
        }
        elseif (strpos($str, 'sweater') !== false || strpos($str, 'winter wear') !== false || strpos($str, 'sweatshirt') !== false || strpos($str, 'hood') !== false || strpos($str, 'cardigan') !== false || strpos($str, 'jacket') !== false) {
            $cat = $isKids ? 'Kids > Boys & Girls Winter Wear' : ($isWomen ? 'Women > Western Wear > Winter Wear' : 'Men > Western Wear > Winter Wear');
            $conf = 'High';
        }
        elseif (strpos($str, 'undergarment') !== false || strpos($str, 'panty') !== false || strpos($str, 'bra') !== false || strpos($str, 'innerwear') !== false || strpos($str, 'slip') !== false || strpos($str, 'supporter') !== false) {
            $cat = $isWomen ? 'Women > Innerwear' : 'Men > Innerwear';
            $conf = 'High';
        }
        elseif (strpos($str, 'nighty') !== false || strpos($str, 'p/j set') !== false || strpos($str, 'night suit') !== false || strpos($str, 'nightwear') !== false) {
            $cat = $isWomen ? 'Women > Western Wear > Nightwear' : 'Men > Western Wear > Nightwear';
            $conf = 'High';
        }
        elseif (strpos($str, 'towel') !== false) {
            $cat = 'Home & Kitchen > Bath > Towels';
            $conf = 'High';
        }
        elseif (strpos($str, 'socks') !== false) {
            $cat = $isKids ? 'Kids > Kids Accessories > Socks' : ($isWomen ? 'Women > Western Wear > Socks' : 'Men > Innerwear > Socks');
            $conf = 'Medium';
        }
        elseif (strpos($str, 'top ') !== false || strpos($str, 'tops') !== false || substr($str, -3) === 'top') {
            $cat = $isGirls ? 'Kids > Girls Clothing > Tops & Tunics' : 'Women > Western Wear > Tops';
            $conf = 'High';
        }
        elseif (strpos($str, 'capri') !== false || strpos($str, 'capry') !== false || strpos($str, 'plazo') !== false || strpos($str, 'lower') !== false || strpos($str, 'track') !== false || strpos($str, 'cargo') !== false || strpos($str, 'chinos') !== false || strpos($str, 'trouser') !== false || strpos($str, 'pant') !== false || strpos($str, 'jeggings') !== false || strpos($str, 'leggings') !== false) {
            $cat = $isMen ? 'Men > Western Wear > Trousers & Pants' : 'Women > Western Wear > Trousers & Pants';
            $conf = 'Medium';
        }

        if ($conf !== 'High') {
            return 'needs_review';
        }
        return $cat;
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
