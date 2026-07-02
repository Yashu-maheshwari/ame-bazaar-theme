<?php
/**
 * Related products block template (WooCommerce compatible).
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brand_name = ame_bazaar_get_brand_name();

// Retrieve Category urls
$cat_urls = array(
	'men'   => get_theme_mod( 'ame_bazaar_cat_men_url', '#' ),
	'women' => get_theme_mod( 'ame_bazaar_cat_women_url', '#' ),
	'kids'  => get_theme_mod( 'ame_bazaar_cat_kids_url', '#' ),
	'acc'   => get_theme_mod( 'ame_bazaar_cat_accessories_url', '#' ),
);

// If WooCommerce is active, check for matching products
$has_wc_products = false;
$wc_products     = array();

if ( class_exists( 'WooCommerce' ) ) {
	// Query WooCommerce products
	$args = array(
		'limit'   => 3,
		'status'  => 'publish',
		'orderby' => 'rand',
	);
	
	// Optional filter products by post category matching product category
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		$args['category'] = array( $categories[0]->slug );
	}
	
	$products = wc_get_products( $args );
	if ( ! empty( $products ) ) {
		$has_wc_products = true;
		$wc_products     = $products;
	}
}
?>

<div class="ame-blog-related-products">
	<h3 class="ame-related-products-title"><?php esc_html_e( 'Featured Catalog Coordinates', 'ame-bazaar' ); ?></h3>

	<?php if ( $has_wc_products ) : ?>
		<div class="ame-related-products-grid">
			<?php foreach ( $wc_products as $product ) : 
				?>
				<div class="ame-product-card">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ame-product-image-link" aria-hidden="true" tabindex="-1">
						<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'ame-product-image' ) ); ?>
					</a>
					<div class="ame-product-details">
						<h4 class="ame-product-title">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="ame-product-title-link"><?php echo esc_html( $product->get_name() ); ?></a>
						</h4>
						<span class="ame-product-price"><?php echo $product->get_price_html(); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<!-- Premium Fallback Placeholder Showcase -->
		<div class="ame-products-placeholder-grid">
			<div class="ame-product-placeholder-card">
				<div class="ame-product-placeholder-visual">
					<span class="ame-product-placeholder-badge"><?php esc_html_e( 'Men\'s Wear', 'ame-bazaar' ); ?></span>
				</div>
				<h4 class="ame-product-placeholder-title"><?php esc_html_e( 'Men\'s Kurta & Casual Sets', 'ame-bazaar' ); ?></h4>
				<p class="ame-product-placeholder-desc"><?php esc_html_e( 'Premium linen and cotton coordinates styled for Delhi weather.', 'ame-bazaar' ); ?></p>
				<a href="<?php echo esc_url( $cat_urls['men'] ); ?>" class="ame-product-placeholder-link"><?php esc_html_e( 'View Department', 'ame-bazaar' ); ?></a>
			</div>
			
			<div class="ame-product-placeholder-card">
				<div class="ame-product-placeholder-visual">
					<span class="ame-product-placeholder-badge"><?php esc_html_e( 'Women\'s Wear', 'ame-bazaar' ); ?></span>
				</div>
				<h4 class="ame-product-placeholder-title"><?php esc_html_e( 'Designer Sarees & Suits', 'ame-bazaar' ); ?></h4>
				<p class="ame-product-placeholder-desc"><?php esc_html_e( 'Exquisite silk and georgette collections curated for every celebration.', 'ame-bazaar' ); ?></p>
				<a href="<?php echo esc_url( $cat_urls['women'] ); ?>" class="ame-product-placeholder-link"><?php esc_html_e( 'View Department', 'ame-bazaar' ); ?></a>
			</div>

			<div class="ame-product-placeholder-card">
				<div class="ame-product-placeholder-visual">
					<span class="ame-product-placeholder-badge"><?php esc_html_e( 'Accessories', 'ame-bazaar' ); ?></span>
				</div>
				<h4 class="ame-product-placeholder-title"><?php esc_html_e( 'Belts, Wallets & Bags', 'ame-bazaar' ); ?></h4>
				<p class="ame-product-placeholder-desc"><?php esc_html_e( 'Handcrafted leather coordinates to elevate your everyday apparel.', 'ame-bazaar' ); ?></p>
				<a href="<?php echo esc_url( $cat_urls['acc'] ); ?>" class="ame-product-placeholder-link"><?php esc_html_e( 'View Department', 'ame-bazaar' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
</div>
