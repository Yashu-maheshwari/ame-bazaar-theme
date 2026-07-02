<?php
/**
 * Content Publishing Framework helpers and hooks for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculate the estimated reading time of a post.
 *
 * @param string $content The post content.
 * @return string Estimated reading time string.
 */
function ame_bazaar_get_reading_time( $content = '' ) {
	if ( empty( $content ) ) {
		$content = get_the_content();
	}

	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$wpm        = 200; // Average reading speed words per minute
	$minutes    = ceil( $word_count / $wpm );

	return sprintf(
		/* translators: %d: reading minutes */
		_n( '%d min read', '%d mins read', $minutes, 'ame-bazaar' ),
		$minutes
	);
}

/**
 * Renders the article meta data row (Date, Reading Time, Last Updated).
 *
 * @return string Meta HTML markup.
 */
function ame_bazaar_get_post_meta_data() {
	$pub_date      = get_the_date();
	$pub_date_c    = get_the_date( 'c' );
	$mod_date      = get_the_modified_date();
	$mod_date_c    = get_the_modified_date( 'c' );
	$reading_time  = ame_bazaar_get_reading_time();

	$output = sprintf(
		'<span class="ame-post-meta-pub-date"><time datetime="%s">%s</time></span>',
		esc_attr( $pub_date_c ),
		esc_html( $pub_date )
	);

	// Display last updated if it differs significantly from published date
	if ( get_the_modified_time( 'U' ) > ( get_the_time( 'U' ) + 86400 ) ) {
		$output .= sprintf(
			' <span class="ame-post-meta-sep">•</span> <span class="ame-post-meta-mod-date">%s <time datetime="%s">%s</time></span>',
			esc_html__( 'Last Updated:', 'ame-bazaar' ),
			esc_attr( $mod_date_c ),
			esc_html( $mod_date )
		);
	}

	$output .= sprintf(
		' <span class="ame-post-meta-sep">•</span> <span class="ame-post-meta-reading-time">%s</span>',
		esc_html( $reading_time )
	);

	return $output;
}

/**
 * Render a highly accessible FAQ Accordion Block (Zero JS).
 *
 * @param array $faqs Array of FAQs containing 'question' and 'answer'.
 * @return string FAQ block HTML.
 */
function ame_bazaar_render_faq_block( $faqs = array() ) {
	if ( empty( $faqs ) ) {
		return '';
	}

	$output = '<div class="ame-blog-faq-block" itemscope itemtype="https://schema.org/FAQPage">';
	$output .= '<h3 class="ame-blog-faq-block-title">' . esc_html__( 'Frequently Asked Questions', 'ame-bazaar' ) . '</h3>';

	foreach ( $faqs as $index => $faq ) {
		if ( empty( $faq['q'] ) || empty( $faq['a'] ) ) {
			continue;
		}

		$output .= sprintf(
			'<details class="ame-blog-faq-item" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
				<summary class="ame-blog-faq-question" itemprop="name"><strong>%s</strong></summary>
				<div class="ame-blog-faq-answer" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
					<div itemprop="text">%s</div>
				</div>
			</details>',
			esc_html( $faq['q'] ),
			wp_kses_post( $faq['a'] )
		);
	}

	$output .= '</div>';

	return $output;
}
