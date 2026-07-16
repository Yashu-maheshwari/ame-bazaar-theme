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
<!-- CATEGORIES COMPONENT LOADED -->
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
			$departments = get_terms( array(
				'taxonomy'   => 'product_cat',
				'parent'     => 0,
				'hide_empty' => false,
			) );

			if ( ! is_wp_error( $departments ) && ! empty( $departments ) ) :
				foreach ( $departments as $dept ) :
					$key = $dept->slug;
					$label = $dept->name;
					$desc = $dept->description ? $dept->description : sprintf( __( 'Explore premium custom %s collections.', 'ame-bazaar' ), $label );
					$url = get_term_link( $dept );

					// Resolve Image: check term meta first
					$img_html = '';
					$homepage_card_id = get_term_meta( $dept->term_id, '_ame_homepage_card', true );
					if ( $homepage_card_id ) {
						$img_html = wp_get_attachment_image( $homepage_card_id, 'medium_large', false, array(
							'class'   => 'ame-category-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $label ) ),
						) );
					}

					// Fallback to default thumbnail if empty
					if ( empty( $img_html ) ) {
						$img_html = '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( sprintf( __( '%s - AME Bazaar Premium Collection', 'ame-bazaar' ), $label ) ) . '" class="ame-category-img" loading="lazy">';
					}
					?>
					<!-- TERM <?php echo intval( $dept->term_id ); ?> -->
					<!-- ATTACHMENT <?php echo intval( $homepage_card_id ); ?> -->
					<!-- URL <?php echo esc_url( wp_get_attachment_image_url( $homepage_card_id, 'medium_large' ) ); ?> -->
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
									<?php echo esc_html( $label ); ?>
								</a>
							</h3>
							<?php if ( $desc ) : ?>
								<p class="ame-category-card-desc"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
							
							<a href="<?php echo esc_url( $url ); ?>" class="ame-bazaar-btn ame-bazaar-btn--secondary ame-category-card-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Explore %s Collection', 'ame-bazaar' ), $label ) ); ?>" data-category-slug="<?php echo esc_attr( $key ); ?>">
								<span><?php esc_html_e( 'Explore Collection', 'ame-bazaar' ); ?></span>
								<svg class="ame-arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
								</svg>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>
</section>
