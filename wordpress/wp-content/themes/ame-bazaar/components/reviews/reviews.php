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
$rating_val = get_theme_mod( 'ame_bazaar_reviews_google_rating', '4.8' );
$review_count = get_theme_mod( 'ame_bazaar_reviews_count', '100+' );
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
							<?php echo esc_html( sprintf( __( 'Based on %s verified customer reviews on Google Maps.', 'ame-bazaar' ), $review_count ) ); ?>
						</p>
					</div>
				</div>

				<!-- Testimonial cards list -->
				<div class="ame-testimonials-list">
					<?php
					for ( $index = 1; $index <= 3; $index++ ) :
						$t_name  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_name' );
						$t_text  = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_text' );
						$t_stars = get_theme_mod( 'ame_bazaar_reviews_t' . $index . '_stars', '5' );

						if ( ! $t_name && ! $t_text ) {
							continue;
						}
						?>
						<blockquote class="ame-testimonial-block">
							<div class="ame-testimonial-header">
								<span class="ame-testimonial-author"><?php echo esc_html( $t_name ); ?></span>
								<div class="ame-testimonial-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'ame-bazaar' ), $t_stars ) ); ?>">
									<?php for ( $j = 0; $j < intval( $t_stars ); $j++ ) : ?>
										<svg class="ame-testimonial-star-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
											<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
										</svg>
									<?php endfor; ?>
								</div>
							</div>
							<p class="ame-testimonial-text">"<?php echo esc_html( $t_text ); ?>"</p>
						</blockquote>
					<?php endfor; ?>
				</div>

			</div>

			<!-- Right Column: Trust indicators list -->
			<div class="ame-reviews-right-col">
				<div class="ame-reviews-trust-cards-grid">
					<?php
					$card_icons = array(
						1 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
						2 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>',
						3 => '<svg class="ame-trust-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
					);

					for ( $index = 1; $index <= 3; $index++ ) :
						$c_title = get_theme_mod( 'ame_bazaar_reviews_c' . $index . '_title' );
						$c_desc  = get_theme_mod( 'ame_bazaar_reviews_c' . $index . '_desc' );

						if ( ! $c_title && ! $c_desc ) {
							continue;
						}
						?>
						<article class="ame-reviews-trust-card">
							<div class="ame-reviews-trust-card-icon-container">
								<?php echo $card_icons[ $index ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							<div class="ame-reviews-trust-card-content">
								<h3 class="ame-reviews-trust-card-title"><?php echo esc_html( $c_title ); ?></h3>
								<p class="ame-reviews-trust-card-desc"><?php echo esc_html( $c_desc ); ?></p>
							</div>
						</article>
					<?php endfor; ?>
				</div>
			</div>

		</div>

	</div>
</section>
