<?php
/**
 * Component: Conditional AI & Local Content Sections
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$buying_guide = get_post_meta( get_the_ID(), 'ame_local_buying_guide', true );
$seasonal_rec = get_post_meta( get_the_ID(), 'ame_local_seasonal_recommendations', true );
$local_faqs   = get_post_meta( get_the_ID(), 'ame_local_faqs', true );
?>

<?php if ( ! empty( $buying_guide ) ) : ?>
	<section class="ame-ai-section ame-ai-buying-guide-section">
		<h2 class="ame-ai-section-title"><?php esc_html_e( 'Apparel Selection Guide', 'ame-bazaar' ); ?></h2>
		<div class="ame-ai-guide-box">
			<?php echo wp_kses_post( $buying_guide ); ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $seasonal_rec ) ) : ?>
	<section class="ame-ai-section ame-ai-seasonal-recs-section">
		<h2 class="ame-ai-section-title"><?php esc_html_e( 'Seasonal Wear Tips', 'ame-bazaar' ); ?></h2>
		<div class="ame-ai-recs-box">
			<?php echo wp_kses_post( $seasonal_rec ); ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( is_array( $local_faqs ) && ! empty( $local_faqs ) ) : ?>
	<section class="ame-ai-section ame-local-faq-section">
		<h2 class="ame-ai-section-title"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h2>
		<div class="ame-blog-faq-block" style="margin-top: 0; padding-top: 0; border-top: none;">
			<?php foreach ( $local_faqs as $faq ) : ?>
				<?php if ( ! empty( $faq['q'] ) && ! empty( $faq['a'] ) ) : ?>
					<details class="ame-blog-faq-item">
						<summary class="ame-blog-faq-question"><?php echo esc_html( $faq['q'] ); ?></summary>
						<div class="ame-blog-faq-answer">
							<p><?php echo esc_html( $faq['a'] ); ?></p>
						</div>
					</details>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>
