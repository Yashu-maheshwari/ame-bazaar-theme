<?php
/**
 * Instagram Gallery Section Component.
 * Hides completely if no real Instagram media has been uploaded.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$insta_img_id = get_option( 'ame_bazaar_media_instagram' );
if ( ! $insta_img_id ) {
	return; // Hide the Instagram section completely if no real image exists.
}
?>

<section class="ame-instagram-section" aria-labelledby="ame-instagram-title" style="padding-block: var(--ame-section-padding); background: var(--ame-color-white);">
	<div class="ame-bazaar-container">
		<div class="ame-section-header" style="text-align: center; margin-bottom: 3rem;">
			<h2 id="ame-instagram-title" class="ame-h2" style="margin-bottom: 0.5rem; color: var(--ame-color-navy);"><?php esc_html_e( '#AMEBazaar Style Showcase', 'ame-bazaar' ); ?></h2>
			<p class="ame-body" style="color: var(--ame-color-slate); max-width: 600px; margin: 0 auto;">
				<?php esc_html_e( 'Follow us on Instagram for daily fabric showcases, custom suit tutorials, and fashion updates.', 'ame-bazaar' ); ?>
			</p>
			<a href="https://instagram.com/amebazaar" class="ame-link" target="_blank" rel="noopener noreferrer" style="color: var(--ame-color-navy); font-weight: 700; margin-top: 0.5rem; display: inline-block;">@amebazaar on Instagram &rarr;</a>
		</div>

		<!-- Centered Single Showcase Card (No placeholders displayed) -->
		<div class="ame-insta-showcase-container" style="display: flex; justify-content: center;">
			<div class="ame-insta-card" style="max-width: 450px; width: 100%; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-md); transition: transform var(--ame-transition);">
				<div class="ame-insta-visual-wrap">
					<div class="ame-insta-img-wrapper" style="aspect-ratio: 1/1; position:relative; display:block;">
						<?php echo wp_get_attachment_image( $insta_img_id, 'medium_large', false, array(
							'class'   => 'ame-insta-img',
							'style'   => 'width:100%; height:100%; object-fit:cover; display:block;',
							'loading' => 'lazy',
							'alt'     => esc_attr__( 'Instagram fashion showcase photo - AME Bazaar Kirari', 'ame-bazaar' ),
						) ); ?>
						<div class="ame-insta-hover-overlay" style="position: absolute; inset: 0; background: rgba(0,35,71,0.7); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity var(--ame-transition);">
							<span class="ame-insta-likes" style="color: #fff; font-weight: 700; font-size: 1.2rem;">❤️ 128</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<style>
.ame-insta-card:hover {
	transform: translateY(-4px);
}
.ame-insta-card:hover .ame-insta-hover-overlay {
	opacity: 1 !important;
}
</style>
