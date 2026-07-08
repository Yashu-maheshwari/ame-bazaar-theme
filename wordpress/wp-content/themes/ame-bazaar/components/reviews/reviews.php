<?php
/**
 * Customer Trust and Google Reviews section template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_reviews_section_title', 'Trusted by Families Across Kirari' );
$section_subtitle = get_theme_mod( 'ame_bazaar_reviews_section_subtitle', 'Read genuine feedback from customers who choose AME Bazaar for their family fashion needs.' );

// Every business fact must come from the centralized business settings
$rating_val   = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
$review_count = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
$gbp_url      = ame_bazaar_get_business_setting( 'gbp_url', 'https://maps.google.com/?cid=7148817758252271950' );

// Testimonials: Do NOT create fake testimonials. Only display if real customer content is stored in the database.
$active_testimonials = array();
for ( $index = 1; $index <= 3; $index++ ) {
	$t_name  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_name' );
	$t_text  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_text' );
	$t_stars = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_stars', '5' );

	if ( $t_name && $t_text ) {
		$active_testimonials[] = array(
			'name'  => $t_name,
			'text'  => $t_text,
			'stars' => $t_stars,
		);
	}
}

// Trust Cards default configurations (Brand value propositions - not testimonials)
$trust_card_defaults = array(
	1 => array(
		'title' => 'Family-Owned Store',
		'desc'  => 'Providing personalized styling support and curated garments for all generations of Kirari families.',
	),
	2 => array(
		'title' => 'Tailoring & Alterations',
		'desc'  => 'Get custom fits and on-time stitching services directly inside the store.',
	),
	3 => array(
		'title' => 'Local Trust',
		'desc'  => 'Built on long-term relationships, reliable apparel quality, and honest recommendations.',
	),
);

// Collect active trust cards
$active_trust_cards = array();
for ( $index = 1; $index <= 3; $index++ ) {
	$c_title = get_theme_mod( 'ame_bazaar_reviews_c' . $index . '_title', $trust_card_defaults[ $index ]['title'] );
	$c_desc  = get_theme_mod( 'ame_bazaar_reviews_c' . $index . '_desc', $trust_card_defaults[ $index ]['desc'] );

	if ( $c_title || $c_desc ) {
		$active_trust_cards[] = array(
			'index' => $index,
			'title' => $c_title,
			'desc'  => $c_desc,
		);
	}
}

// Hide section entirely if no testimonials, rating, or trust cards exist
if ( ! $rating_val && empty( $active_testimonials ) && empty( $active_trust_cards ) ) {
	return;
}
?>

<section class="ame-reviews-section" aria-labelledby="ame-reviews-section-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-reviews-header">
			<h2 id="ame-reviews-section-title" class="ame-reviews-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php if ( $section_subtitle ) : ?>
				<p class="ame-reviews-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Layout Split Columns -->
		<div class="ame-reviews-layout-grid">
			
			<!-- Left Column: Google rating summary + Testimonials list -->
			<div class="ame-reviews-left-col">
				
				<!-- Google rating summary card -->
				<?php if ( $rating_val ) : ?>
					<div class="ame-reviews-google-card" aria-label="<?php esc_attr_e( 'Google Business Profile reviews summary', 'ame-bazaar' ); ?>">
						<div class="ame-reviews-google-rating-wrap">
							<span class="ame-google-rating-number" aria-hidden="true"><?php echo esc_html( $rating_val ); ?></span>
							<div class="ame-reviews-stars-container" aria-hidden="true">
								<?php for ( $i = 0; $i < 5; $i++ ) : ?>
									<svg class="ame-star-icon" viewBox="0 0 24 24" fill="currentColor">
										<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
									</svg>
								<?php endfor; ?>
							</div>
						</div>
						<div class="ame-reviews-google-info">
							<h3 class="ame-google-rating-title"><?php esc_html_e( 'Google Rating', 'ame-bazaar' ); ?></h3>
							<p class="ame-google-rating-subtitle">
								<?php echo esc_html( sprintf( __( 'Based on %s+ verified customer reviews on Google Maps.', 'ame-bazaar' ), $review_count ) ); ?>
							</p>
						</div>
						<?php 
						$reviews_banner_id = get_option( 'ame_bazaar_media_reviews' );
						if ( $reviews_banner_id ) {
							echo '<div class="ame-reviews-banner-wrap" style="margin-top: 1.5rem; border-radius: var(--ame-radius-sm); overflow: hidden; box-shadow: var(--ame-shadow-sm);">';
							echo wp_get_attachment_image( $reviews_banner_id, 'medium', false, array(
								'class'   => 'ame-reviews-banner-img',
								'style'   => 'width: 100%; height: auto; display: block;',
								'loading' => 'lazy',
								'alt'     => esc_attr__( 'AME Bazaar Google Maps Verified Rating - Kirari, Delhi', 'ame-bazaar' ),
							) );
							echo '</div>';
						}
						?>
					</div>
				<?php endif; ?>

				<!-- Testimonial cards list (Only renders if real testimonials exist) -->
				<?php if ( ! empty( $active_testimonials ) ) : ?>
					<div class="ame-testimonials-list">
						<?php foreach ( $active_testimonials as $t ) : ?>
							<blockquote class="ame-testimonial-block">
								<div class="ame-testimonial-header">
									<span class="ame-testimonial-author"><?php echo esc_html( $t['name'] ); ?></span>
									<div class="ame-testimonial-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'ame-bazaar' ), $t['stars'] ) ); ?>">
										<?php for ( $j = 0; $j < intval( $t['stars'] ); $j++ ) : ?>
											<svg class="ame-testimonial-star-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
												<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
											</svg>
										<?php endfor; ?>
									</div>
								</div>
								<p class="ame-testimonial-text">"<?php echo esc_html( $t['text'] ); ?>"</p>
							</blockquote>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>

			<!-- Right Column: Trust indicators list -->
			<?php if ( ! empty( $active_trust_cards ) ) : ?>
				<div class="ame-reviews-right-col">
					<div class="ame-reviews-trust-cards-grid">
						<?php
						$card_icons = array(
							1 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
							2 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>',
							3 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
						);

						foreach ( $active_trust_cards as $tc ) :
							?>
							<article class="ame-reviews-trust-card">
								<div class="ame-reviews-trust-card-icon-container">
									<?php echo $card_icons[ $tc['index'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<div class="ame-reviews-trust-card-content">
									<h3 class="ame-reviews-trust-card-title"><?php echo esc_html( $tc['title'] ); ?></h3>
									<p class="ame-reviews-trust-card-desc"><?php echo esc_html( $tc['desc'] ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>

	</div>
</section>
