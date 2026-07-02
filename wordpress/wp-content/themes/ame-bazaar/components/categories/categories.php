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
		'default_img' => ame_bazaar_asset_uri( 'assets/images/men-wear.png' ),
	),
	'women' => array(
		'label'       => 'Women\'s Wear',
		'default_img' => '', // Responsive placeholder
	),
	'kids' => array(
		'label'       => 'Kids Wear',
		'default_img' => ame_bazaar_asset_uri( 'assets/images/kids-wear.png' ),
	),
	'accessories' => array(
		'label'       => 'Accessories',
		'default_img' => '', // Responsive placeholder
	),
);
?>

<section class="ame-categories-section" aria-labelledby="ame-categories-title">
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
				$url  = get_theme_mod( 'ame_bazaar_cat_' . $key . '_url', '#' );
				$img  = get_theme_mod( 'ame_bazaar_cat_' . $key . '_image' );

				if ( ! $img ) {
					$img = $cat['default_img'];
				}
				?>
				<article class="ame-category-card">
					<a href="<?php echo esc_url( $url ); ?>" class="ame-category-card-visual-link" tabindex="-1" aria-hidden="true">
						<div class="ame-category-card-visual">
							<?php if ( $img ) : ?>
								<img src="<?php echo esc_url( $img ); ?>" 
									 alt="<?php echo esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $cat['label'] ) ); ?>" 
									 class="ame-category-img" 
									 loading="lazy">
							<?php else : ?>
								<!-- Accessible responsive placeholder visual -->
								<div class="ame-category-img-placeholder">
									<div class="ame-placeholder-logo-overlay">
										<span class="ame-placeholder-logo-text"><?php echo esc_html( ame_bazaar_get_brand_name() ); ?></span>
									</div>
									<span class="ame-placeholder-tag"><?php esc_html_e( 'Photography Coming Soon', 'ame-bazaar' ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</a>

					<div class="ame-category-card-content">
						<h3 class="ame-category-card-title">
							<a href="<?php echo esc_url( $url ); ?>" class="ame-category-title-link">
								<?php echo esc_html( $cat['label'] ); ?>
							</a>
						</h3>
						<?php if ( $desc ) : ?>
							<p class="ame-category-card-desc"><?php echo esc_html( $desc ); ?></p>
						<?php endif; ?>
						
						<a href="<?php echo esc_url( $url ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-category-card-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Explore %s Collection', 'ame-bazaar' ), $cat['label'] ) ); ?>">
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
