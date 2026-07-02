<?php
/**
 * Instagram Gallery Section Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="ame-instagram-section" aria-labelledby="ame-instagram-title">
	<div class="ame-bazaar-container">
		<div class="ame-section-header" style="text-align: center; margin-bottom: 3rem;">
			<h2 id="ame-instagram-title" class="ame-h2" style="margin-bottom: 0.5rem;"><?php esc_html_e( '#AMEBazaar Style Showcase', 'ame-bazaar' ); ?></h2>
			<p class="ame-body" style="color: var(--ame-color-slate); max-width: 600px; margin: 0 auto;">
				<?php esc_html_e( 'Follow us on Instagram for daily fabric showcases, custom suit tutorials, on-site tailoring logs, and fashion updates.', 'ame-bazaar' ); ?>
			</p>
			<a href="https://instagram.com/amebazaar" class="ame-link" target="_blank" rel="noopener noreferrer" style="font-weight: 700; margin-top: 0.5rem; display: inline-block;">@amebazaar on Instagram &rarr;</a>
		</div>

		<!-- Grid of Posts (6 items) -->
		<div class="ame-grid ame-grid-3">
			
			<div class="ame-insta-card">
				<div class="ame-insta-visual-wrap">
					<div class="ame-insta-img-placeholder" style="background: var(--ame-color-cream); aspect-ratio: 1/1; border-radius: var(--ame-radius-md); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
						<span class="ame-placeholder-tag"><?php esc_html_e( 'Mulmul Cotton Suit fitting log', 'ame-bazaar' ); ?></span>
						<div class="ame-insta-hover-overlay">
							<span class="ame-insta-likes">❤️ 128</span>
						</div>
					</div>
				</div>
			</div>

			<div class="ame-insta-card">
				<div class="ame-insta-visual-wrap">
					<div class="ame-insta-img-placeholder" style="background: var(--ame-color-cream); aspect-ratio: 1/1; border-radius: var(--ame-radius-md); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
						<span class="ame-placeholder-tag"><?php esc_html_e( 'Banarasi silk saree drape guide', 'ame-bazaar' ); ?></span>
						<div class="ame-insta-hover-overlay">
							<span class="ame-insta-likes">❤️ 244</span>
						</div>
					</div>
				</div>
			</div>

			<div class="ame-insta-card">
				<div class="ame-insta-visual-wrap">
					<div class="ame-insta-img-placeholder" style="background: var(--ame-color-cream); aspect-ratio: 1/1; border-radius: var(--ame-radius-md); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
						<!-- Video Placeholder -->
						<div class="ame-video-play-indicator" style="position:absolute; top:1rem; right:1rem; background:rgba(0,0,0,0.6); color:#fff; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.7rem; font-weight:700;">Reel</div>
						<span class="ame-placeholder-tag"><?php esc_html_e( 'Tailor alteration tutorial (Video)', 'ame-bazaar' ); ?></span>
						<div class="ame-insta-hover-overlay">
							<span class="ame-insta-likes">❤️ 312</span>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
