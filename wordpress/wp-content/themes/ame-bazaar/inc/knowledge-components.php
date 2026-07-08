<?php
/**
 * Reusable layout components for AME Bazaar Knowledge Hub.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Breadcrumb Component
 */
function ame_bazaar_render_breadcrumb() {
	$home_title = __( 'Home', 'ame-bazaar' );
	$hub_title  = __( 'Knowledge Hub', 'ame-bazaar' );
	
	echo '<nav class="ame-breadcrumb" aria-label="breadcrumb">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( $home_title ) . '</a>';
	echo '<span class="sep">/</span>';
	
	if ( is_singular( 'knowledge' ) ) {
		echo '<a href="' . esc_url( get_post_type_archive_link( 'knowledge' ) ) . '">' . esc_html( $hub_title ) . '</a>';
		echo '<span class="sep">/</span>';
		echo '<span class="current" aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} else {
		echo '<span class="current" aria-current="page">' . esc_html( $hub_title ) . '</span>';
	}
	echo '</nav>';
}

/**
 * 2. Reading Time & Last Updated Component
 */
function ame_bazaar_get_reading_time( $content ) {
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 );
	return sprintf( _n( '%d min read', '%d min read', $reading_time, 'ame-bazaar' ), $reading_time );
}

function ame_bazaar_render_meta_bar() {
	$reading_time = ame_bazaar_get_reading_time( get_the_content() );
	$modified_date = get_the_modified_date( 'F j, Y' );
	
	echo '<div class="ame-article-meta">';
	echo '<span class="meta-item reading-time">';
	echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
	echo esc_html( $reading_time );
	echo '</span>';
	echo '<span class="meta-item last-updated">';
	echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
	echo esc_html__( 'Last Updated: ', 'ame-bazaar' ) . esc_html( $modified_date );
	echo '</span>';
	echo '</div>';
}

/**
 * 3. Knowledge Hero Component
 */
function ame_bazaar_render_knowledge_hero() {
	?>
	<header class="ame-knowledge-hero">
		<?php ame_bazaar_render_breadcrumb(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?>
			<div class="entry-subtitle"><?php the_excerpt(); ?></div>
		<?php endif; ?>
		<?php ame_bazaar_render_meta_bar(); ?>
	</header>
	<?php
}

/**
 * 4. Centralized Business Facts Component
 */
function ame_bazaar_render_business_facts() {
	$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
	$email      = ame_bazaar_get_business_setting( 'email', 'info@amebazaar.in' );
	$hours      = ame_bazaar_get_business_setting( 'hours', 'Monday to Sunday: 09:00 AM - 10:00 PM' );
	$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road, Kirari Suleman Nagar, Delhi' );
	$zip        = ame_bazaar_get_business_setting( 'postal_code', '110086' );
	$rating     = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$reviews    = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	?>
	<section class="ame-fact-card business-facts">
		<h3 class="card-title"><?php esc_html_e( 'Verified Business Profile', 'ame-bazaar' ); ?></h3>
		<ul class="fact-list">
			<li>
				<strong><?php esc_html_e( 'Retail Store:', 'ame-bazaar' ); ?></strong>
				<span><?php echo esc_html( $store_name ); ?></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Address:', 'ame-bazaar' ); ?></strong>
				<span><?php echo esc_html( $address ) . ' - ' . esc_html( $zip ); ?></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Contact Hotline:', 'ame-bazaar' ); ?></strong>
				<span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Support Email:', 'ame-bazaar' ); ?></strong>
				<span><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Opening Hours:', 'ame-bazaar' ); ?></strong>
				<span><?php echo esc_html( $hours ); ?></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Rating & Reviews:', 'ame-bazaar' ); ?></strong>
				<span class="rating-badge">★ <?php echo esc_html( $rating ); ?> (<?php echo esc_html( $reviews ); ?> <?php esc_html_e( 'reviews', 'ame-bazaar' ); ?>)</span>
			</li>
		</ul>
	</section>
	<?php
}

/**
 * 5. Location Authority Facts Component
 */
function ame_bazaar_render_location_facts() {
	$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?cid=123456' );
	$embed_url = ame_bazaar_get_business_setting( 'maps_embed_url' );
	$lat = ame_bazaar_get_business_setting( 'latitude', '28.7051' );
	$lng = ame_bazaar_get_business_setting( 'longitude', '77.0583' );
	?>
	<section class="ame-fact-card location-facts">
		<h3 class="card-title"><?php esc_html_e( 'Store Location & Coordinates', 'ame-bazaar' ); ?></h3>
		<ul class="fact-list">
			<li>
				<strong><?php esc_html_e( 'Region Authority:', 'ame-bazaar' ); ?></strong>
				<span><?php esc_html_e( 'Kirari, Suleman Nagar, North West Delhi, India', 'ame-bazaar' ); ?></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Coordinates:', 'ame-bazaar' ); ?></strong>
				<span><?php echo esc_html( $lat ) . ', ' . esc_html( $lng ); ?></span>
			</li>
			<li>
				<strong><?php esc_html_e( 'Maps Link:', 'ame-bazaar' ); ?></strong>
				<span><a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View Google Maps', 'ame-bazaar' ); ?></a></span>
			</li>
		</ul>
		<?php if ( $embed_url ) : ?>
			<div class="maps-embed-wrapper" style="margin-top: 15px;">
				<iframe src="<?php echo esc_url( $embed_url ); ?>" width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		<?php endif; ?>
	</section>
	<?php
}

/**
 * 6. FAQ Accordion Block Component
 */
function ame_bazaar_render_faq_block( $post_id ) {
	$faqs = get_post_meta( $post_id, '_ame_knowledge_faqs', true );
	if ( empty( $faqs ) ) {
		return;
	}
	?>
	<section class="ame-faq-section">
		<h2 class="section-title"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h2>
		<div class="ame-accordion">
			<?php foreach ( $faqs as $index => $faq ) : ?>
				<div class="accordion-item">
					<button class="accordion-header" aria-expanded="false" aria-controls="faq-content-<?php echo esc_attr( $index ); ?>">
						<span class="header-text"><?php echo esc_html( $faq['question'] ); ?></span>
						<span class="chevron"></span>
					</button>
					<div id="faq-content-<?php echo esc_attr( $index ); ?>" class="accordion-content" aria-hidden="true">
						<div class="content-inner">
							<p><?php echo nl2br( esc_html( $faq['answer'] ) ); ?></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const headers = document.querySelectorAll('.accordion-header');
			headers.forEach(header => {
				header.addEventListener('click', function() {
					const expanded = this.getAttribute('aria-expanded') === 'true';
					this.setAttribute('aria-expanded', !expanded);
					
					const content = document.getElementById(this.getAttribute('aria-controls'));
					content.setAttribute('aria-hidden', expanded);
					
					if (!expanded) {
						content.style.maxHeight = content.scrollHeight + 'px';
					} else {
						content.style.maxHeight = '0px';
					}
				});
			});
		});
	</script>
	<?php
}

/**
 * 7. Related Categories & WooCommerce Integration Component
 */
function ame_bazaar_render_related_categories( $post_id ) {
	$selected_cats = get_post_meta( $post_id, '_ame_knowledge_wc_cats', true );
	if ( empty( $selected_cats ) ) {
		return;
	}
	?>
	<section class="ame-related-block related-categories">
		<h4 class="block-title"><?php esc_html_e( 'Explore Related Collections', 'ame-bazaar' ); ?></h4>
		<div class="category-links">
			<?php foreach ( $selected_cats as $slug ) : 
				$term = get_term_by( 'slug', $slug, 'product_cat' );
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}
				?>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="cat-pill">
					<?php echo esc_html( $term->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * 8. WooCommerce Product Integration Block (Survives future modifications)
 */
function ame_bazaar_render_related_products( $post_id ) {
	$product_ids_str = get_post_meta( $post_id, '_ame_knowledge_wc_products', true );
	if ( empty( $product_ids_str ) ) {
		return;
	}
	
	$product_ids = array_filter( array_map( 'intval', explode( ',', $product_ids_str ) ) );
	if ( empty( $product_ids ) ) {
		return;
	}

	$products_query = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'post__in'       => $product_ids,
		'posts_per_page' => count( $product_ids ),
	) );

	if ( ! $products_query->have_posts() ) {
		return;
	}
	?>
	<section class="ame-related-products">
		<h3 class="section-title"><?php esc_html_e( 'Featured Collection Products', 'ame-bazaar' ); ?></h3>
		<div class="products-grid">
			<?php while ( $products_query->have_posts() ) : $products_query->the_post(); 
				global $product;
				?>
				<div class="product-item-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="product-image">
							<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
						</div>
					<?php endif; ?>
					<h4 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<?php if ( function_exists( 'woocommerce_template_loop_price' ) ) : ?>
						<div class="product-price"><?php woocommerce_template_loop_price(); ?></div>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="button view-product-btn"><?php esc_html_e( 'View Details', 'ame-bazaar' ); ?></a>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php
}

/**
 * 9. Author Box Component
 */
function ame_bazaar_render_author_box() {
	$brand_name = ame_bazaar_get_brand_name();
	$logo_url   = ame_bazaar_get_custom_logo_url();
	$desc       = get_bloginfo( 'description' ) ?: __( 'AI-First Premium Fashion Retailer and Custom Tailoring Hub in Kirari, North West Delhi.', 'ame-bazaar' );
	?>
	<div class="ame-author-box">
		<?php if ( $logo_url ) : ?>
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $brand_name ); ?> Logo" class="author-avatar" />
		<?php endif; ?>
		<div class="author-details">
			<h4 class="author-name"><?php echo esc_html( $brand_name ) . ' ' . esc_html__( 'Editorial Team', 'ame-bazaar' ); ?></h4>
			<p class="author-bio"><?php echo esc_html( $desc ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * 10. Related Articles (Topic Cluster) Component
 */
function ame_bazaar_render_related_articles( $post_id ) {
	$related = new WP_Query( array(
		'post_type'      => 'knowledge',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'post__not_in'   => array( $post_id ),
	) );

	if ( ! $related->have_posts() ) {
		return;
	}
	?>
	<section class="ame-related-articles">
		<h3 class="section-title"><?php esc_html_e( 'Recommended Guides', 'ame-bazaar' ); ?></h3>
		<div class="articles-grid">
			<?php while ( $related->have_posts() ) : $related->the_post(); ?>
				<article class="article-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="article-thumb">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
						</div>
					<?php endif; ?>
					<h4 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<p class="article-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
					<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read Guide →', 'ame-bazaar' ); ?></a>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php
}
