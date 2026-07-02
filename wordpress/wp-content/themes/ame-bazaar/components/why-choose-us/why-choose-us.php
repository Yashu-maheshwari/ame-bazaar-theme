<?php
/**
 * Why Choose AME Bazaar section template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title = get_theme_mod( 'ame_bazaar_why_section_title', 'Why Choose AME Bazaar' );
$section_subtitle = get_theme_mod( 'ame_bazaar_why_section_subtitle', 'Premium Family Fashion Store in Kirari, Delhi' );

// Core vector icons and default customizer configs
$why_cards = array(
	1 => array(
		'icon' => '<svg class="ame-why-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
	),
	2 => array(
		'icon' => '<svg class="ame-why-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
	),
	3 => array(
		'icon' => '<svg class="ame-why-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>',
	),
	4 => array(
		'icon' => '<svg class="ame-why-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
	),
);
?>

<section class="ame-why-choose-section" aria-labelledby="ame-why-choose-title">
	<div class="ame-bazaar-container">
		
		<!-- Section Header -->
		<div class="ame-why-choose-header">
			<h2 id="ame-why-choose-title" class="ame-why-choose-section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php if ( $section_subtitle ) : ?>
				<p class="ame-why-choose-section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Strengths Grid -->
		<div class="ame-why-choose-grid">
			<?php
			foreach ( $why_cards as $index => $card_meta ) :
				$title = get_theme_mod( 'ame_bazaar_why_card' . $index . '_title' );
				$desc  = get_theme_mod( 'ame_bazaar_why_card' . $index . '_desc' );
				
				if ( ! $title && ! $desc ) {
					continue;
				}
				?>
				<article class="ame-why-card">
					<div class="ame-why-card-icon-container">
						<?php echo $card_meta['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3 class="ame-why-card-title"><?php echo esc_html( $title ); ?></h3>
					<?php if ( $desc ) : ?>
						<p class="ame-why-card-desc"><?php echo esc_html( $desc ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
