<?php
/**
 * Shop by Category section template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_cat_section_title', 'Shop by Category' );
$section_subtitle = get_theme_mod( 'ame_bazaar_cat_section_subtitle', 'Explore our premium fashion collections' );

$categories = array(
	'men' => array(
		'label'       => 'Men\'s Wear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/men-wear-new.jpg' ),
	),
	'women' => array(
		'label'       => 'Women\'s Wear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/women-wear-new.jpg' ),
	),
	'boys' => array(
		'label'       => 'Boy\'s Wear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/kids-wear-new.jpg' ),
	),
	'girls' => array(
		'label'       => 'Girl\'s Wear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/kids-wear-new.jpg' ),
	),
	'infant' => array(
		'label'       => 'Infant Items',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/kids-wear-new.jpg' ),
	),
	'accessories' => array(
		'label'       => 'Accessories',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/accessories-new.jpg' ),
	),
	'footwear' => array(
		'label'       => 'Footwear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/accessories-new.jpg' ),
	),
	'rainwear' => array(
		'label'       => 'Rainwear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/accessories-new.jpg' ),
	),
	'tailoring' => array(
		'label'       => 'Tailoring Services',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/tailoring-new.jpg' ),
	),
	'exclusive' => array(
		'label'       => 'Online Exclusive',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/accessories-new.jpg' ),
	),
);
?>

<section class="ame-categories-section" id="categories" aria-labelledby="ame-categories-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-categories-header">
			<h2 id="ame-categories-title" class="ame-categories-section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php if ( $section_subtitle ) : ?>
				<p class="ame-categories-section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Categories Grid -->
		<div class="ame-categories-grid">
			<?php
			foreach ( $categories as $key => $cat ) :
				$desc = get_theme_mod( 'ame_bazaar_cat_' . $key . '_desc' );
				$url  = get_theme_mod( 'ame_bazaar_cat_' . $key . '_url' );

				if ( ! $url || '#' === $url ) {
					// Dynamically resolve WooCommerce category URLs
					$slugs_to_check = array( $key );
					if ( 'men' === $key ) {
						$slugs_to_check[] = 'mens-wear';
						$slugs_to_check[] = 'men-wear';
					} elseif ( 'women' === $key ) {
						$slugs_to_check[] = 'womens-wear';
						$slugs_to_check[] = 'women-wear';
					} elseif ( 'boys' === $key ) {
						$slugs_to_check[] = 'boys-wear';
						$slugs_to_check[] = 'boy-wear';
					} elseif ( 'girls' === $key ) {
						$slugs_to_check[] = 'girls-wear';
						$slugs_to_check[] = 'girl-wear';
					} elseif ( 'infant' === $key ) {
						$slugs_to_check[] = 'infant-items';
					} elseif ( 'tailoring' === $key ) {
						$slugs_to_check[] = 'tailoring-services';
					} elseif ( 'exclusive' === $key ) {
						$slugs_to_check[] = 'online-exclusive';
					}

					foreach ( $slugs_to_check as $slug ) {
						$term = get_term_by( 'slug', $slug, 'product_cat' );
						if ( $term && ! is_wp_error( $term ) ) {
							$resolved_url = get_term_link( $term );
							if ( ! is_wp_error( $resolved_url ) ) {
								$url = $resolved_url;
								break;
							}
						}
					}

					// Fallback to WooCommerce standard permalink route
					if ( ! $url || '#' === $url ) {
						if ( 'tailoring' === $key ) {
							$url = home_url( '/tailoring-near-me/' );
						} elseif ( 'men' === $key ) {
							$url = home_url( '/product-category/mens-wear/' );
						} elseif ( 'women' === $key ) {
							$url = home_url( '/product-category/womens-wear/' );
						} elseif ( 'boys' === $key ) {
							$url = home_url( '/product-category/boys-wear/' );
						} elseif ( 'girls' === $key ) {
							$url = home_url( '/product-category/girls-wear/' );
						} elseif ( 'infant' === $key ) {
							$url = home_url( '/product-category/infant-items/' );
						} elseif ( 'footwear' === $key ) {
							$url = home_url( '/product-category/footwear/' );
						} elseif ( 'rainwear' === $key ) {
							$url = home_url( '/product-category/rainwear/' );
						} elseif ( 'exclusive' === $key ) {
							$url = home_url( '/product-category/online-exclusive/' );
						} else {
							$url = home_url( '/product-category/' . $key . '/' );
						}
					}
				}

				// Self-healing: if the URL contains '/product-category/' or points to an invalid slug, correct it.
				if ( $url ) {
					if ( strpos( $url, '/category/' ) !== false ) {
						$url = str_replace( '/category/', '/product-category/', $url );
					}
					if ( strpos( $url, '/product-category/kids/' ) !== false || strpos( $url, '/product-category/kids-wear/' ) !== false ) {
						$url = home_url( '/product-category/boys-wear/' );
					}
					if ( strpos( $url, '/product-category/tailoring/' ) !== false ) {
						$url = home_url( '/tailoring-near-me/' );
					}
				}

				// Resolve Image: check term meta first (Single Source of Truth)
				$img_html = '';
				$term_id = 0;
				if ( isset( $term ) && ! is_wp_error( $term ) ) {
					$term_id = $term->term_id;
				}

				if ( $term_id ) {
					$homepage_card_id = get_term_meta( $term_id, '_ame_homepage_card', true );
					if ( $homepage_card_id ) {
						$img_html = wp_get_attachment_image( $homepage_card_id, 'medium_large', false, array(
							'class'   => 'ame-category-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ),
						) );
					}
				}

				// Fallbacks if no term meta image is set
				if ( empty( $img_html ) ) {
					$customizer_url = get_theme_mod( 'ame_bazaar_img_' . $key );
					if ( empty( $customizer_url ) ) {
						$customizer_url = get_theme_mod( 'ame_bazaar_cat_' . $key . '_image' );
					}
					if ( empty( $customizer_url ) && ( 'boys' === $key || 'girls' === $key ) ) {
						$customizer_url = get_theme_mod( 'ame_bazaar_cat_kids_image' );
					}

					if ( ! empty( $customizer_url ) ) {
						$attachment_id = attachment_url_to_postid( $customizer_url );
						if ( $attachment_id ) {
							$img_html = wp_get_attachment_image( $attachment_id, 'medium_large', false, array(
								'class'   => 'ame-category-img',
								'loading' => 'lazy',
								'alt'     => esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ),
							) );
						} else {
							$img_html = '<img src="' . esc_url( $customizer_url ) . '" alt="' . esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ) . '" class="ame-category-img" loading="lazy">';
						}
					} else {
						$img_id = get_option( 'ame_bazaar_media_' . $key );
						if ( ! $img_id && ( 'boys' === $key || 'girls' === $key ) ) {
							$img_id = get_option( 'ame_bazaar_media_kids' );
						}
						if ( ! $img_id ) {
							$img_id = get_theme_mod( 'ame_bazaar_cat_' . $key . '_image_id' );
							if ( ! $img_id && ( 'boys' === $key || 'girls' === $key ) ) {
								$img_id = get_theme_mod( 'ame_bazaar_cat_kids_image_id' );
							}
						}
						if ( ! $img_id ) {
							$slugs_to_check = array(
								$key . '-wear-image',
								$key . '-wear',
								$key . '-image',
								$key . '-banner',
								$key
							);
							foreach ( $slugs_to_check as $slug ) {
								$resolved_id = ame_bazaar_get_attachment_id_by_slug( $slug );
								if ( $resolved_id ) {
									$img_id = $resolved_id;
									break;
								}
							}
						}
						if ( $img_id ) {
							$img_html = wp_get_attachment_image( $img_id, 'medium_large', false, array(
								'class'   => 'ame-category-img',
								'loading' => 'lazy',
								'alt'     => esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ),
							) );
						} elseif ( ! empty( $cat['default_img'] ) ) {
							$img_html = '<img src="' . esc_url( $cat['default_img'] ) . '" alt="' . esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ) . '" class="ame-category-img" loading="lazy">';
						}
					}
				}
				?>
				<article class="ame-category-card">
					<?php if ( ! empty( $img_html ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="ame-category-card-visual-link" tabindex="-1" aria-hidden="true" data-category-slug="<?php echo esc_attr( $key ); ?>">
							<div class="ame-category-card-visual">
								<?php echo $img_html; ?>
							</div>
						</a>
					<?php endif; ?>

					<div class="ame-category-card-content">
						<h3 class="ame-category-card-title">
							<a href="<?php echo esc_url( $url ); ?>" class="ame-category-title-link" data-category-slug="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $cat['label'] ); ?>
							</a>
						</h3>
						<?php if ( $desc ) : ?>
							<p class="ame-category-card-desc"><?php echo esc_html( $desc ); ?></p>
						<?php endif; ?>
						
						<a href="<?php echo esc_url( $url ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-category-card-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Explore %s Collection', 'ame-bazaar' ), $cat['label'] ) ); ?>" data-category-slug="<?php echo esc_attr( $key ); ?>">
							<span><?php esc_html_e( 'Explore Collection', 'ame-bazaar' ); ?></span>
							<svg class="ame-arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
							</svg>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
