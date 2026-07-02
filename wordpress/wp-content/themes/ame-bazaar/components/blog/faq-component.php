<?php
/**
 * Component: Blog Post Local FAQs List
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$local_faqs = get_post_meta( get_the_ID(), 'ame_local_faqs', true );
if ( ! is_array( $local_faqs ) || empty( $local_faqs ) ) {
	return;
}
?>
<section class="ame-ai-section ame-post-faq-section" style="margin-top: 3.5rem; border-top: 1px solid var(--ame-color-border); padding-top: 2.5rem;">
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
