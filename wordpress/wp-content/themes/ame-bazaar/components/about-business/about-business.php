<?php
/**
 * About AME Bazaar and Local Business Info section template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_about_section_title', 'About AME Bazaar' );
$section_subtitle = get_theme_mod( 'ame_bazaar_about_section_subtitle', 'Discover our heritage, collections, and values as a trusted local family store.' );
$story_headline = get_theme_mod( 'ame_bazaar_about_story_headline', 'Apparel Maheshwari Enterprises - Rooted in Trust' );
$story_content = get_theme_mod( 'ame_bazaar_about_story_content', 'Located on Mubarakpur Road in Kirari, Delhi, AME Bazaar is dedicated to providing high-quality garments for your entire family. We offer premium Men\'s Wear, Women\'s Wear, Kids\' Wear, Sarees, and fashion Accessories. In addition, our in-store tailoring and alterations service ensures a custom fit for every customer. We encourage you to visit our store for a premium minimal shopping experience.' );

// FAQ default configurations
$faq_defaults = array(
	1 => array(
		'q' => 'Where is AME Bazaar located in Delhi?',
		'a' => 'We are located on Mubarakpur Road in Kirari, Delhi, making us easily accessible for shoppers from Baljit Vihar, Prem Nagar, and nearby Delhi areas.',
	),
	2 => array(
		'q' => 'What clothing ranges do you specialize in?',
		'a' => 'We specialize in family apparel including Men\'s Wear, Women\'s Wear, Kids\' Wear, traditional Sarees, and everyday fashion Accessories.',
	),
	3 => array(
		'q' => 'Do you provide custom alterations and tailoring?',
		'a' => 'Yes, we have an in-store tailoring and alterations service to customize fittings for your purchases, ensuring comfortable wear.',
	),
);

// Collect active FAQs
$active_faqs = array();
for ( $index = 1; $index <= 3; $index++ ) {
	$q = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_q', $faq_defaults[ $index ]['q'] );
	$a = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_a', $faq_defaults[ $index ]['a'] );

	if ( $q && $a ) {
		$active_faqs[] = array(
			'q' => $q,
			'a' => $a,
		);
	}
}
?>

<section class="ame-about-business-section" aria-labelledby="ame-about-business-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-about-business-header">
			<h2 id="ame-about-business-title" class="ame-about-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php if ( $section_subtitle ) : ?>
				<p class="ame-about-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Split Layout: Story vs local FAQs -->
		<div class="ame-about-business-layout">
			
			<!-- Left Column: Story details -->
			<div class="ame-about-story-col">
				<h3 class="ame-about-story-headline"><?php echo esc_html( $story_headline ); ?></h3>
				<?php 
				$about_img_id = get_option( 'ame_bazaar_media_about' );
				if ( $about_img_id ) {
					echo '<div class="ame-about-banner-wrapper" style="margin-top: 1rem; margin-bottom: 1.5rem; border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-sm);">';
					echo wp_get_attachment_image( $about_img_id, 'medium_large', false, array(
						'class'   => 'ame-about-banner-img',
						'style'   => 'width: 100%; height: auto; display: block;',
						'loading' => 'lazy',
						'alt'     => esc_attr__( 'About AME Bazaar Store Heritage - Kirari, Delhi', 'ame-bazaar' ),
					) );
					echo '</div>';
				}
				?>
				<div class="ame-about-story-content-wrap">
					<p class="ame-about-story-para"><?php echo esc_html( $story_content ); ?></p>
				</div>
				<div class="ame-about-badges">
					<span class="ame-about-badge"><?php esc_html_e( 'Family-Owned', 'ame-bazaar' ); ?></span>
					<span class="ame-about-badge"><?php esc_html_e( 'Kirari, Delhi', 'ame-bazaar' ); ?></span>
					<span class="ame-about-badge"><?php esc_html_e( 'Tailoring Available', 'ame-bazaar' ); ?></span>
				</div>
			</div>

			<!-- Right Column: Semantic Local FAQ list for search engine visibility -->
			<?php if ( ! empty( $active_faqs ) ) : ?>
				<div class="ame-about-faq-col">
					<h3 class="ame-about-faq-headline"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h3>
					<dl class="ame-about-faq-list">
						<?php foreach ( $active_faqs as $faq ) : ?>
							<div class="ame-faq-item">
								<dt class="ame-faq-question"><?php echo esc_html( $faq['q'] ); ?></dt>
								<dd class="ame-faq-answer"><?php echo esc_html( $faq['a'] ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				</div>
			<?php endif; ?>

		</div>

	</div>
</section>
