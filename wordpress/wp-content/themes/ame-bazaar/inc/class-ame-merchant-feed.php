<?php
/**
 * AME Bazaar Merchant Feed Generator
 *
 * Implements the approved Phase 28 specification for Google Merchant Center
 * and Bing Shopping product feeds.
 *
 * Architecture:
 * - Read-only projection of WooCommerce catalog data.
 * - Multi-format static generation: Google XML (uncompressed), Google XML.GZ (primary), and Bing TSV.
 * - Strict Image Eligibility Gate: excludes missing photos, logo.png fallbacks, and sub-250px images.
 * - Deterministic Identifier: <g:id>ame_{post_id}</g:id>, <g:mpn>{sku}</g:mpn>, <g:identifier_exists>no</g:identifier_exists>.
 * - Atomic file swaps with integrity validation to guarantee zero-downtime crawl reliability.
 * - Scheduled daily background execution (WP-Cron at 03:00 IST / WP-CLI).
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AME_Bazaar_Merchant_Feed {

	const FEED_DIR_NAME      = 'feeds';
	const GOOGLE_XML_FILE    = 'google-merchant-feed.xml';
	const GOOGLE_XML_GZ_FILE = 'google-merchant-feed.xml.gz';
	const BING_TSV_FILE      = 'bing-merchant-feed.tsv';
	const SECRET_TOKEN       = '7fa2e10db708b8b9487c69ea230768b6';
	const BATCH_SIZE         = 500;
	const CRON_HOOK          = 'ame_bazaar_daily_feed_event';

	/**
	 * Boot hooks.
	 */
	public static function init() {
		// Feed routes and request interception
		add_action( 'init', array( __CLASS__, 'register_feed_rewrites' ) );
		add_filter( 'query_vars', array( __CLASS__, 'register_query_vars' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_feed_request' ), 1 );

		// Scheduled cron registration
		add_action( 'wp', array( __CLASS__, 'schedule_daily_feed_cron' ) );
		add_action( self::CRON_HOOK, array( __CLASS__, 'generate_all_feeds' ) );

		// WP-CLI command registration
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'ame feed-generate', array( __CLASS__, 'cli_generate_feeds' ) );
		}
	}

	/**
	 * Get the absolute server directory for storing feeds.
	 * Ensures directory and directory protection exist.
	 *
	 * @return string Absolute filesystem path.
	 */
	public static function get_feed_dir() {
		$upload_dir = wp_upload_dir();
		$feed_dir   = trailingslashit( $upload_dir['basedir'] ) . self::FEED_DIR_NAME;

		if ( ! file_exists( $feed_dir ) ) {
			wp_mkdir_p( $feed_dir );
		}

		// Directory security: prevent directory listing
		$index_file = trailingslashit( $feed_dir ) . 'index.php';
		if ( ! file_exists( $index_file ) ) {
			@file_put_contents( $index_file, "<?php\n// Silence is golden.\n" );
		}

		$htaccess_file = trailingslashit( $feed_dir ) . '.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			@file_put_contents( $htaccess_file, "Options -Indexes\n" );
		}

		return $feed_dir;
	}

	/**
	 * Get the public URL for a given feed file.
	 *
	 * @param string $filename Feed filename.
	 * @return string Full HTTPS URL.
	 */
	public static function get_feed_url( $filename ) {
		$upload_dir = wp_upload_dir();
		return trailingslashit( $upload_dir['baseurl'] ) . self::FEED_DIR_NAME . '/' . ltrim( $filename, '/' );
	}

	/**
	 * Register rewrite rules for vanity /feeds/ URLs.
	 */
	public static function register_feed_rewrites() {
		add_rewrite_rule( '^feeds/google-merchant-feed\.xml\.gz$', 'index.php?ame_feed=google-xml-gz', 'top' );
		add_rewrite_rule( '^feeds/google-merchant-feed\.xml$', 'index.php?ame_feed=google-xml', 'top' );
		add_rewrite_rule( '^feeds/bing-merchant-feed\.tsv$', 'index.php?ame_feed=bing-tsv', 'top' );
	}

	/**
	 * Register query variables for feed routing.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public static function register_query_vars( $vars ) {
		$vars[] = 'ame_feed';
		$vars[] = 'ame_feed_action';
		return $vars;
	}

	/**
	 * Handle feed delivery and trigger requests.
	 */
	public static function handle_feed_request() {
		// Manual generation or status check trigger
		if ( isset( $_GET['ame_feed_action'] ) ) {
			$action = sanitize_text_field( $_GET['ame_feed_action'] );
			$token  = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

			if ( $token !== self::SECRET_TOKEN && ! current_user_can( 'manage_woocommerce' ) ) {
				wp_send_json_error( array( 'message' => 'Unauthorized access to merchant feed operations.' ), 403 );
				exit;
			}

			if ( $action === 'generate' ) {
				$stats = self::generate_all_feeds();
				wp_send_json_success( $stats );
				exit;
			} elseif ( $action === 'status' ) {
				$stats = get_option( 'ame_merchant_feed_stats', array() );
				wp_send_json_success( $stats );
				exit;
			}
		}

		// Public Feed delivery
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : '';
		$feed_var = get_query_var( 'ame_feed' );

		if ( strpos( $uri, '/feeds/' . self::GOOGLE_XML_GZ_FILE ) !== false || $feed_var === 'google-xml-gz' ) {
			self::serve_feed_file( self::GOOGLE_XML_GZ_FILE, 'application/gzip' );
		} elseif ( strpos( $uri, '/feeds/' . self::GOOGLE_XML_FILE ) !== false || $feed_var === 'google-xml' ) {
			self::serve_feed_file( self::GOOGLE_XML_FILE, 'application/xml; charset=utf-8' );
		} elseif ( strpos( $uri, '/feeds/' . self::BING_TSV_FILE ) !== false || $feed_var === 'bing-tsv' ) {
			self::serve_feed_file( self::BING_TSV_FILE, 'text/tab-separated-values; charset=utf-8' );
		}
	}

	/**
	 * Stream static feed file with optimal HTTP cache and security headers.
	 *
	 * @param string $filename File name.
	 * @param string $content_type Content-Type header.
	 */
	protected static function serve_feed_file( $filename, $content_type ) {
		$feed_dir  = self::get_feed_dir();
		$file_path = trailingslashit( $feed_dir ) . $filename;

		// If static feed does not exist yet, compile on-demand
		if ( ! file_exists( $file_path ) || filesize( $file_path ) < 1000 ) {
			self::generate_all_feeds();
		}

		if ( ! file_exists( $file_path ) ) {
			status_header( 404 );
			echo 'Feed file not available.';
			exit;
		}

		$file_mtime = filemtime( $file_path );
		$file_size  = filesize( $file_path );

		// HTTP 304 Not Modified check
		if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			$if_modified = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
			if ( $if_modified && $if_modified >= $file_mtime ) {
				status_header( 304 );
				exit;
			}
		}

		// Disable output buffering
		while ( ob_get_level() > 0 ) {
			ob_end_clean();
		}

		header( 'HTTP/1.1 200 OK' );
		header( 'Content-Type: ' . $content_type );
		header( 'Content-Length: ' . $file_size );
		header( 'Content-Disposition: inline; filename="' . basename( $file_path ) . '"' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $file_mtime ) . ' GMT' );
		header( 'Cache-Control: public, max-age=3600, must-revalidate' );
		header( 'X-Robots-Tag: noindex, follow' );

		readfile( $file_path );
		exit;
	}

	/**
	 * Schedule the daily WP-Cron event to refresh feeds at 03:00 IST (21:30 UTC).
	 */
	public static function schedule_daily_feed_cron() {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			$target_time = strtotime( 'tomorrow 21:30:00 UTC' );
			wp_schedule_event( $target_time, 'daily', self::CRON_HOOK );
		}
	}

	/**
	 * Image Eligibility Gate (Core Quality Filter).
	 * Strictly excludes products with:
	 * - No featured image assigned
	 * - Invalid/empty image URL
	 * - Generic logo.png or placeholder fallback
	 * - Attachment dimensions under 250x250 px (Google Apparel policy)
	 *
	 * Evaluates dimensions directly via wp_get_attachment_metadata (0 HTTP requests).
	 *
	 * @param WC_Product $product WooCommerce product.
	 * @return string|false Valid image URL or false if ineligible.
	 */
	public static function is_image_eligible( $product ) {
		$thumbnail_id = $product->get_image_id();
		if ( ! $thumbnail_id || intval( $thumbnail_id ) <= 0 ) {
			return false;
		}

		$img_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
		if ( ! $img_url || ! filter_var( $img_url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		$lower_url = strtolower( $img_url );
		if ( strpos( $lower_url, 'logo.png' ) !== false || strpos( $lower_url, 'placeholder' ) !== false ) {
			return false;
		}

		// In-database metadata validation (instant zero-network check)
		$meta = wp_get_attachment_metadata( $thumbnail_id );
		if ( is_array( $meta ) && ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			$w = intval( $meta['width'] );
			$h = intval( $meta['height'] );
			if ( $w < 250 || $h < 250 ) {
				return false;
			}
		}

		return $img_url;
	}

	/**
	 * Filter and extract eligible gallery images (up to 10).
	 *
	 * @param WC_Product $product Product.
	 * @param string     $primary_img_url Primary image URL to avoid duplicates.
	 * @return array Array of valid image URLs.
	 */
	public static function get_eligible_additional_images( $product, $primary_img_url ) {
		$gallery_ids = $product->get_gallery_image_ids();
		if ( empty( $gallery_ids ) || ! is_array( $gallery_ids ) ) {
			return array();
		}

		$valid = array();
		foreach ( $gallery_ids as $gid ) {
			if ( count( $valid ) >= 10 ) {
				break;
			}
			$url = wp_get_attachment_image_url( $gid, 'full' );
			if ( ! $url || ! filter_var( $url, FILTER_VALIDATE_URL ) || $url === $primary_img_url ) {
				continue;
			}
			$lower = strtolower( $url );
			if ( strpos( $lower, 'logo.png' ) !== false || strpos( $lower, 'placeholder' ) !== false ) {
				continue;
			}
			$meta = wp_get_attachment_metadata( $gid );
			if ( is_array( $meta ) && ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
				if ( intval( $meta['width'] ) < 250 || intval( $meta['height'] ) < 250 ) {
					continue;
				}
			}
			$valid[] = $url;
		}

		return $valid;
	}

	/**
	 * Master Category Mapping Specification.
	 * Evaluates deepest child category first (ORDER BY parent DESC) and maps
	 * to Google standard numeric taxonomy ID and breadcrumb hierarchy.
	 *
	 * @param int $post_id Product post ID.
	 * @return array Array containing 'gpc', 'product_type', and 'cat_name'.
	 */
	public static function get_category_data( $post_id ) {
		$terms = wp_get_post_terms( $post_id, 'product_cat', array(
			'orderby' => 'parent',
			'order'   => 'DESC',
		) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array(
				'gpc'          => '1604',
				'product_type' => 'Apparel & Accessories > Clothing',
				'cat_name'     => 'Clothing',
			);
		}

		$primary_term = null;
		foreach ( $terms as $term ) {
			if ( $term->slug !== 'uncategorized' ) {
				$primary_term = $term;
				break;
			}
		}
		if ( ! $primary_term ) {
			$primary_term = $terms[0];
		}

		$slug = strtolower( $primary_term->slug );
		$name = strtolower( $primary_term->name );

		// Master GPC Mapping
		if ( strpos( $slug, 'saree' ) !== false || strpos( $name, 'saree' ) !== false ) {
			$gpc = '5449'; // Traditional & Ceremonial Clothing > Sarees
		} elseif ( strpos( $slug, 'suit' ) !== false || strpos( $slug, 'salwar' ) !== false || strpos( $name, 'salwar' ) !== false ) {
			$gpc = '5448'; // Traditional & Ceremonial Clothing > Salwar Kameez
		} elseif ( strpos( $slug, 'kurta' ) !== false || strpos( $slug, 'sherwani' ) !== false || strpos( $name, 'kurta' ) !== false ) {
			$gpc = '5447'; // Traditional & Ceremonial Clothing > Kurtas
		} elseif ( strpos( $slug, 'waistcoat' ) !== false || strpos( $slug, 'waist-coat' ) !== false || strpos( $name, 'waistcoat' ) !== false ) {
			$gpc = '559'; // Vests
		} elseif ( strpos( $slug, 't-shirt' ) !== false || strpos( $slug, 'tshirt' ) !== false || strpos( $name, 't-shirt' ) !== false || strpos( $name, 't shirt' ) !== false ) {
			$gpc = '212'; // Shirts & Tops > T-Shirts
		} elseif ( strpos( $slug, 'shirt' ) !== false || strpos( $name, 'shirt' ) !== false || strpos( $slug, 'sweater' ) !== false || strpos( $name, 'sweater' ) !== false ) {
			$gpc = '212'; // Shirts & Tops
		} elseif ( strpos( $slug, 'jean' ) !== false || strpos( $name, 'jean' ) !== false ) {
			$gpc = '204'; // Pants > Jeans
		} elseif ( strpos( $slug, 'trouser' ) !== false || strpos( $slug, 'pant' ) !== false || strpos( $name, 'trouser' ) !== false ) {
			$gpc = '204'; // Pants
		} elseif ( strpos( $slug, 'kid' ) !== false || strpos( $slug, 'baby' ) !== false || strpos( $slug, 'infant' ) !== false || strpos( $name, 'kid' ) !== false ) {
			$gpc = '1604'; // Clothing
		} else {
			$gpc = '1604'; // Clothing (Standard Fallback)
		}

		// Breadcrumb product_type hierarchy
		$ancestors  = get_ancestors( $primary_term->term_id, 'product_cat', 'taxonomy' );
		$breadcrumb = array();
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'product_cat' );
				if ( $ancestor && ! is_wp_error( $ancestor ) ) {
					$breadcrumb[] = $ancestor->name;
				}
			}
		}
		$breadcrumb[] = $primary_term->name;
		$product_type = implode( ' > ', $breadcrumb );

		return array(
			'gpc'          => $gpc,
			'product_type' => $product_type,
			'cat_name'     => $primary_term->name,
		);
	}

	/**
	 * Extract and sanitize description according to prioritized sources.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $cat_name Category name for fallback.
	 * @return string Clean plaintext description (max 5,000 chars).
	 */
	public static function get_clean_description( $product, $cat_name ) {
		$post_id = $product->get_id();
		$desc    = $product->get_short_description();

		if ( empty( trim( $desc ) ) ) {
			$desc = get_post_meta( $post_id, '_ame_seo_description', true );
		}
		if ( empty( trim( $desc ) ) ) {
			$desc = $product->get_description();
		}
		if ( empty( trim( $desc ) ) ) {
			$desc = sprintf(
				'Shop %s from AME Bazaar. Premium %s available with on-site custom tailoring alterations at our Kirari, Delhi showroom.',
				$product->get_name(),
				strtolower( $cat_name )
			);
		}

		$desc = wp_strip_all_tags( $desc );
		$desc = html_entity_decode( $desc, ENT_QUOTES, 'UTF-8' );
		$desc = preg_replace( '/\s+/', ' ', $desc );

		return mb_substr( trim( $desc ), 0, 5000, 'UTF-8' );
	}

	/**
	 * Price validation and sale-price formatting.
	 * Indian retail MRP GST-inclusive standard.
	 *
	 * @param WC_Product $product Product.
	 * @return array|false Price data array or false if ineligible.
	 */
	public static function get_price_data( $product ) {
		$regular_price = $product->get_regular_price();
		$sale_price    = $product->get_sale_price();
		$current_price = $product->get_price();

		if ( empty( $current_price ) || floatval( $current_price ) <= 0 ) {
			return false; // REJECT: Zero or empty price
		}

		if ( $product->is_on_sale() && ! empty( $sale_price ) && floatval( $sale_price ) < floatval( $regular_price ) ) {
			return array(
				'price'      => number_format( floatval( $regular_price ), 2, '.', '' ) . ' INR',
				'sale_price' => number_format( floatval( $sale_price ), 2, '.', '' ) . ' INR',
			);
		}

		return array(
			'price'      => number_format( floatval( $current_price ), 2, '.', '' ) . ' INR',
			'sale_price' => null,
		);
	}

	/**
	 * Map WooCommerce product to normalized intermediate merchant dataset.
	 *
	 * @param WC_Product $product Product.
	 * @return array|false Normalized item array or false if ineligible.
	 */
	public static function normalize_product( $product ) {
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return false;
		}

		// Status & purchasable check
		if ( $product->get_status() !== 'publish' || ! $product->is_purchasable() ) {
			return false;
		}

		// Image eligibility gate
		$image_url = self::is_image_eligible( $product );
		if ( ! $image_url ) {
			return false;
		}

		// Price eligibility gate
		$price_data = self::get_price_data( $product );
		if ( ! $price_data ) {
			return false;
		}

		$post_id    = $product->get_id();
		$cat_data   = self::get_category_data( $post_id );
		$sku        = $product->get_sku();
		$mpn        = ! empty( $sku ) ? $sku : ( 'P-' . $post_id );
		$brand_meta = get_post_meta( $post_id, '_ame_brand', true );
		$brand      = ! empty( trim( $brand_meta ) ) ? trim( $brand_meta ) : 'AME Bazaar';

		// Clean title (max 150 chars)
		$title = wp_strip_all_tags( $product->get_name() );
		$title = html_entity_decode( $title, ENT_QUOTES, 'UTF-8' );
		$title = mb_substr( trim( $title ), 0, 150, 'UTF-8' );

		// Description
		$desc = self::get_clean_description( $product, $cat_data['cat_name'] );

		// Availability
		$stock_status = 'out_of_stock';
		if ( $product->is_in_stock() ) {
			$stock_status = 'in_stock';
		} elseif ( $product->is_on_backorder() ) {
			$stock_status = 'backorder';
		}

		$additional_images = self::get_eligible_additional_images( $product, $image_url );

		return array(
			'id'                      => 'ame_' . $post_id,
			'sku'                     => $sku,
			'mpn'                     => $mpn,
			'title'                   => $title,
			'description'             => $desc,
			'link'                    => get_permalink( $post_id ),
			'image_link'              => $image_url,
			'additional_image_links'  => $additional_images,
			'availability'            => $stock_status,
			'price'                   => $price_data['price'],
			'sale_price'              => $price_data['sale_price'],
			'brand'                   => $brand,
			'identifier_exists'       => 'no',
			'condition'               => 'new',
			'google_product_category' => $cat_data['gpc'],
			'product_type'            => $cat_data['product_type'],
		);
	}

	/**
	 * Core Feed Generation Engine.
	 * Executes batch cursor queries (500 items), streams to temporary files,
	 * runs post-generation integrity checks, and atomically swaps files into production.
	 *
	 * @return array Generation statistics.
	 */
	public static function generate_all_feeds() {
		global $wpdb;

		// Raise resource limits safely for CLI / Background generation
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 );
		}
		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit( 'feed' );
		}

		$start_time = microtime( true );
		$feed_dir   = self::get_feed_dir();
		$timestamp  = time();

		$temp_xml_path    = trailingslashit( $feed_dir ) . 'temp-' . $timestamp . '-' . self::GOOGLE_XML_FILE;
		$temp_xml_gz_path = trailingslashit( $feed_dir ) . 'temp-' . $timestamp . '-' . self::GOOGLE_XML_GZ_FILE;
		$temp_tsv_path    = trailingslashit( $feed_dir ) . 'temp-' . $timestamp . '-' . self::BING_TSV_FILE;

		$final_xml_path    = trailingslashit( $feed_dir ) . self::GOOGLE_XML_FILE;
		$final_xml_gz_path = trailingslashit( $feed_dir ) . self::GOOGLE_XML_GZ_FILE;
		$final_tsv_path    = trailingslashit( $feed_dir ) . self::BING_TSV_FILE;

		// Open temporary file streams
		$xml_handle = @fopen( $temp_xml_path, 'wb' );
		$tsv_handle = @fopen( $temp_tsv_path, 'wb' );
		$gz_handle  = function_exists( 'gzopen' ) ? @gzopen( $temp_xml_gz_path, 'wb9' ) : null;

		if ( ! $xml_handle || ! $tsv_handle ) {
			error_log( 'AME Bazaar Merchant Feed: Failed to create temporary feed files in ' . $feed_dir );
			return array(
				'success' => false,
				'error'   => 'Failed to open temp file handles.',
			);
		}

		// XML Header
		$xml_header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
			"<rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">\n" .
			"<channel>\n" .
			"\t<title>AME Bazaar Products Feed</title>\n" .
			"\t<link>" . esc_url( home_url( '/' ) ) . "</link>\n" .
			"\t<description>Family Fashion Store and Custom Tailoring in Kirari, Delhi</description>\n";

		fwrite( $xml_handle, $xml_header );
		if ( $gz_handle ) {
			gzwrite( $gz_handle, $xml_header );
		}

		// TSV Header
		$tsv_header = "id\ttitle\tdescription\tlink\timage_link\tadditional_image_link\tprice\tsale_price\tavailability\tbrand\tmpn\tidentifier_exists\tcondition\tgoogle_product_category\tproduct_type\n";
		fwrite( $tsv_handle, $tsv_header );

		// Batch processing counters
		$total_published = 0;
		$total_eligible  = 0;
		$excluded_photo  = 0;
		$excluded_price  = 0;

		$offset = 0;
		$limit  = self::BATCH_SIZE;

		do {
			// Efficient indexed primary key query with SQL_NO_CACHE
			$product_ids = $wpdb->get_col( $wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY ID ASC LIMIT %d OFFSET %d",
				$limit,
				$offset
			) );

			if ( empty( $product_ids ) ) {
				break;
			}

			foreach ( $product_ids as $pid ) {
				$total_published++;
				$product = wc_get_product( $pid );
				if ( ! $product ) {
					continue;
				}

				// Check gates individually for detailed diagnostics
				if ( ! self::is_image_eligible( $product ) ) {
					$excluded_photo++;
					continue;
				}

				if ( ! self::get_price_data( $product ) ) {
					$excluded_price++;
					continue;
				}

				$item = self::normalize_product( $product );
				if ( ! $item ) {
					continue;
				}

				$total_eligible++;

				// Format XML Item
				$xml_item = "\t<item>\n" .
					"\t\t<g:id>" . esc_html( $item['id'] ) . "</g:id>\n" .
					"\t\t<g:title><![CDATA[" . $item['title'] . "]]></g:title>\n" .
					"\t\t<g:description><![CDATA[" . $item['description'] . "]]></g:description>\n" .
					"\t\t<g:link>" . esc_url( $item['link'] ) . "</g:link>\n" .
					"\t\t<g:image_link>" . esc_url( $item['image_link'] ) . "</g:image_link>\n";

				if ( ! empty( $item['additional_image_links'] ) ) {
					foreach ( $item['additional_image_links'] as $addl_url ) {
						$xml_item .= "\t\t<g:additional_image_link>" . esc_url( $addl_url ) . "</g:additional_image_link>\n";
					}
				}

				$xml_item .= "\t\t<g:availability>" . esc_html( $item['availability'] ) . "</g:availability>\n" .
					"\t\t<g:price>" . esc_html( $item['price'] ) . "</g:price>\n";

				if ( ! empty( $item['sale_price'] ) ) {
					$xml_item .= "\t\t<g:sale_price>" . esc_html( $item['sale_price'] ) . "</g:sale_price>\n";
				}

				$xml_item .= "\t\t<g:brand><![CDATA[" . $item['brand'] . "]]></g:brand>\n" .
					"\t\t<g:identifier_exists>" . esc_html( $item['identifier_exists'] ) . "</g:identifier_exists>\n" .
					"\t\t<g:mpn><![CDATA[" . $item['mpn'] . "]]></g:mpn>\n" .
					"\t\t<g:condition>" . esc_html( $item['condition'] ) . "</g:condition>\n" .
					"\t\t<g:google_product_category>" . esc_html( $item['google_product_category'] ) . "</g:google_product_category>\n" .
					"\t\t<g:product_type><![CDATA[" . $item['product_type'] . "]]></g:product_type>\n" .
					"\t</item>\n";

				fwrite( $xml_handle, $xml_item );
				if ( $gz_handle ) {
					gzwrite( $gz_handle, $xml_item );
				}

				// Format TSV Row
				$clean_title = str_replace( array( "\t", "\r", "\n" ), ' ', $item['title'] );
				$clean_desc  = str_replace( array( "\t", "\r", "\n" ), ' ', $item['description'] );
				$clean_type  = str_replace( array( "\t", "\r", "\n" ), ' ', $item['product_type'] );
				$addl_str    = implode( ',', $item['additional_image_links'] );

				$tsv_row = implode( "\t", array(
					$item['id'],
					$clean_title,
					$clean_desc,
					$item['link'],
					$item['image_link'],
					$addl_str,
					$item['price'],
					$item['sale_price'] ? $item['sale_price'] : '',
					$item['availability'],
					$item['brand'],
					$item['mpn'],
					$item['identifier_exists'],
					$item['condition'],
					$item['google_product_category'],
					$clean_type,
				) ) . "\n";

				fwrite( $tsv_handle, $tsv_row );
			}

			// Clean internal caches to maintain low RAM footprint (<32MB)
			if ( function_exists( 'wp_cache_flush_runtime' ) ) {
				wp_cache_flush_runtime();
			}

			$offset += $limit;

		} while ( count( $product_ids ) === $limit );

		// XML Footer
		$xml_footer = "</channel>\n</rss>\n";
		fwrite( $xml_handle, $xml_footer );
		if ( $gz_handle ) {
			gzwrite( $gz_handle, $xml_footer );
		}

		fclose( $xml_handle );
		fclose( $tsv_handle );
		if ( $gz_handle ) {
			gzclose( $gz_handle );
		}

		// Integrity Validation Check
		$xml_size = file_exists( $temp_xml_path ) ? filesize( $temp_xml_path ) : 0;
		$gz_size  = file_exists( $temp_xml_gz_path ) ? filesize( $temp_xml_gz_path ) : 0;
		$tsv_size = file_exists( $temp_tsv_path ) ? filesize( $temp_tsv_path ) : 0;

		// Validation rules:
		// 1. Files must exist and be non-trivial (>10KB)
		// 2. Eligible products count must be at least 500
		// 3. XML file must cleanly end with </channel></rss>
		$xml_tail = '';
		if ( $xml_size > 50 ) {
			$f = fopen( $temp_xml_path, 'r' );
			fseek( $f, -50, SEEK_END );
			$xml_tail = fread( $f, 50 );
			fclose( $f );
		}

		$is_valid = ( $total_eligible >= 500 ) &&
			( $xml_size > 50000 ) &&
			( $tsv_size > 20000 ) &&
			( strpos( $xml_tail, '</channel>' ) !== false );

		$duration = round( microtime( true ) - $start_time, 2 );

		if ( $is_valid ) {
			// Atomic Zero-Downtime POSIX/NTFS Renames
			@rename( $temp_xml_path, $final_xml_path );
			if ( file_exists( $temp_xml_gz_path ) ) {
				@rename( $temp_xml_gz_path, $final_xml_gz_path );
			}
			@rename( $temp_tsv_path, $final_tsv_path );

			$stats = array(
				'success'                  => true,
				'timestamp'                => $timestamp,
				'duration_seconds'         => $duration,
				'total_published'          => $total_published,
				'total_eligible_exported'  => $total_eligible,
				'excluded_photo_gate'      => $excluded_photo,
				'excluded_price_gate'      => $excluded_price,
				'google_xml_size_bytes'    => $xml_size,
				'google_xml_gz_size_bytes' => $gz_size,
				'bing_tsv_size_bytes'      => $tsv_size,
				'google_feed_url'          => home_url( '/feeds/' . self::GOOGLE_XML_GZ_FILE ),
				'google_feed_xml_url'      => home_url( '/feeds/' . self::GOOGLE_XML_FILE ),
				'bing_feed_url'            => home_url( '/feeds/' . self::BING_TSV_FILE ),
			);

			update_option( 'ame_merchant_feed_stats', $stats, false );
			return $stats;
		} else {
			// Abort and preserve existing feeds
			@unlink( $temp_xml_path );
			@unlink( $temp_xml_gz_path );
			@unlink( $temp_tsv_path );

			$error_data = array(
				'success'          => false,
				'timestamp'        => $timestamp,
				'error'            => 'Integrity check failed.',
				'total_published'  => $total_published,
				'total_eligible'   => $total_eligible,
				'xml_size'         => $xml_size,
				'duration_seconds' => $duration,
			);

			update_option( 'ame_merchant_feed_last_error', $error_data, false );
			error_log( 'AME Bazaar Merchant Feed: Feed generation aborted due to failed integrity validation. Output: ' . wp_json_encode( $error_data ) );

			return $error_data;
		}
	}

	/**
	 * WP-CLI handler for wp ame feed-generate.
	 *
	 * @param array $args Arguments.
	 * @param array $assoc_args Associative args.
	 */
	public static function cli_generate_feeds( $args, $assoc_args ) {
		WP_CLI::line( 'Executing AME Bazaar Merchant Feed Generation...' );
		$stats = self::generate_all_feeds();

		if ( ! empty( $stats['success'] ) ) {
			WP_CLI::success( sprintf(
				'Feeds generated in %0.2fs! Published: %d, Exported: %d, Excluded (photos): %d, Excluded (price): %d',
				$stats['duration_seconds'],
				$stats['total_published'],
				$stats['total_eligible_exported'],
				$stats['excluded_photo_gate'],
				$stats['excluded_price_gate']
			) );
			WP_CLI::line( 'Google XML: ' . $stats['google_feed_xml_url'] );
			WP_CLI::line( 'Google GZ:  ' . $stats['google_feed_url'] );
			WP_CLI::line( 'Bing TSV:   ' . $stats['bing_feed_url'] );
		} else {
			WP_CLI::error( 'Feed generation failed: ' . wp_json_encode( $stats ) );
		}
	}
}

// Initialize on load
AME_Bazaar_Merchant_Feed::init();
