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

			<!-- Right Column: Interactive Local Accordion FAQs -->
			<div class="ame-about-faq-col">
				<h3 class="ame-about-faq-headline" style="color: var(--ame-color-navy); margin-bottom: 1.5rem;"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h3>
				<div class="ame-about-faq-accordion" style="display: flex; flex-direction: column; gap: 10px; max-height: 550px; overflow-y: auto; padding-right: 5px;">
					<?php
					$verified_faqs = ame_bazaar_get_verified_faqs();
					foreach ( $verified_faqs as $index => $faq ) :
						?>
						<div class="ame-faq-accordion-item" id="<?php echo esc_attr( $faq['id'] ); ?>" style="border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); background: var(--ame-color-white); overflow: hidden;">
							<button class="ame-faq-accordion-trigger" style="width: 100%; text-align: left; padding: 0.9rem 1.1rem; background: none; border: none; font-weight: 700; font-size: 0.95rem; color: var(--ame-color-navy); cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 10px;" aria-expanded="false" onclick="
								const content = this.nextElementSibling;
								const isExpanded = this.getAttribute('aria-expanded') === 'true';
								this.setAttribute('aria-expanded', !isExpanded);
								content.style.maxHeight = !isExpanded ? content.scrollHeight + 'px' : null;
							">
								<span><?php echo esc_html( $faq['q'] ); ?></span>
								<span class="accordion-arrow" style="transition: transform var(--ame-transition); font-weight: 400; font-size: 0.8rem;">▼</span>
							</button>
							<div class="ame-faq-accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.25s ease-out; padding-inline: 1.1rem;">
								<p style="padding-bottom: 1.1rem; margin: 0; color: var(--ame-color-slate); line-height: 1.6; font-size: 0.9rem;">
									<?php echo esc_html( $faq['a'] ); ?>
								</p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			
			<style>
			.ame-faq-accordion-trigger[aria-expanded="true"] .accordion-arrow {
				transform: rotate(180deg);
			}
			.ame-about-faq-accordion::-webkit-scrollbar {
				width: 5px;
			}
			.ame-about-faq-accordion::-webkit-scrollbar-track {
				background: var(--ame-color-cream);
				border-radius: 10px;
			}
			.ame-about-faq-accordion::-webkit-scrollbar-thumb {
				background: var(--ame-color-border);
				border-radius: 10px;
			}
			</style>

		</div>

	</div>
</section>
