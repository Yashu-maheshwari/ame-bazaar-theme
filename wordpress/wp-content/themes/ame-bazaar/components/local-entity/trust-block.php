<?php
/**
 * Dynamic value props and trust blocks component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Trust Blocks.
 */
function ame_bazaar_render_trust_blocks() {
	$highlights = array(
		array(
			'title' => 'Tailoring Expertise',
			'desc'  => 'Perfect fits customized by master local tailors on-site.',
			'icon'  => '✂️'
		),
		array(
			'title' => '500+ Google Reviews',
			'desc'  => 'Trusted by generations across Mubarakpur and Kirari.',
			'icon'  => '⭐'
		),
		array(
			'title' => 'Genuine Pricing',
			'desc'  => 'Real premium fashion without Rohini or Connaught Place markups.',
			'icon'  => '🏷️'
		),
		array(
			'title' => 'Family Store Story',
			'desc'  => 'Proudly serving the Delhi ethnic fashion retail market since years.',
			'icon'  => '🏠'
		)
	);

	?>
	<div class="ame-trust-blocks-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.5rem; margin-block:2rem;">
		<?php foreach ( $highlights as $hl ) : ?>
			<div class="ame-trust-card" style="background:#fff; border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:1.5rem; box-shadow:var(--ame-shadow-sm); display:flex; gap:1rem; align-items:flex-start; box-sizing:border-box;">
				<span style="font-size:2rem; line-height:1;"><?php echo esc_html( $hl['icon'] ); ?></span>
				<div>
					<h3 style="margin:0 0 0.25rem 0; font-size:1rem; font-weight:700; color:var(--ame-color-navy);"><?php echo esc_html( $hl['title'] ); ?></h3>
					<p style="margin:0; font-size:0.8rem; color:var(--ame-color-slate); line-height:1.5;"><?php echo esc_html( $hl['desc'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
