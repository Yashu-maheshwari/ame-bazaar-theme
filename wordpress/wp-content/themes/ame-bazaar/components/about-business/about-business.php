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
			<div class="ame-about-faq-col">
				<h3 class="ame-about-faq-headline"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h3>
				<dl class="ame-about-faq-list">
					<?php
					for ( $index = 1; $index <= 3; $index++ ) :
						$faq_q = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_q' );
						$faq_a = get_theme_mod( 'ame_bazaar_about_faq' . $index . '_a' );

						if ( ! $faq_q && ! $faq_a ) {
							continue;
						}
						?>
						<div class="ame-faq-item">
							<dt class="ame-faq-question"><?php echo esc_html( $faq_q ); ?></dt>
							<dd class="ame-faq-answer"><?php echo esc_html( $faq_a ); ?></dd>
						</div>
					<?php endfor; ?>
				</dl>
			</div>

		</div>

	</div>
</section>
