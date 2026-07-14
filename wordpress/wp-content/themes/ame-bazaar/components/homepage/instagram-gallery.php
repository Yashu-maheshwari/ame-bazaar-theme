<?php
/**
 * Showroom Gallery Section Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="ame-instagram-section" aria-labelledby="ame-instagram-title" style="padding-block: 5rem; background: #ffffff;">
	<div class="ame-bazaar-container">
		<div class="ame-section-header" style="text-align: center; margin-bottom: 3rem;">
			<h2 id="ame-instagram-title" class="ame-h2" style="margin-bottom: 0.5rem; font-weight: 800; color: var(--ame-color-navy);"><?php esc_html_e( 'Experience AME Bazaar Showroom', 'ame-bazaar' ); ?></h2>
			<p class="ame-body" style="color: var(--ame-color-slate); max-width: 600px; margin: 0 auto;">
				<?php esc_html_e( 'Take a virtual walk through our Mubarakpur Road showroom in Kirari. See our premium fabric racks, customer trials lounge, and custom alterations desk.', 'ame-bazaar' ); ?>
			</p>
			<a href="https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi" class="ame-link" target="_blank" rel="noopener noreferrer" style="font-weight: 700; margin-top: 0.5rem; display: inline-block; color: var(--ame-color-gold-dark); text-decoration: underline;">Get Directions on Google Maps &rarr;</a>
		</div>

		<!-- Grid of Posts (6 items) -->
		<div class="ame-grid ame-grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
			<?php
			$showroom_photos = array(
				array(
					'setting_key' => 'img_gallery_1',
					'id'          => 547,
					'label'       => 'Store Front Facade & Parking Area'
				),
				array(
					'setting_key' => 'img_gallery_2',
					'id'          => 540,
					'label'       => 'Main Showroom Interior Collections'
				),
				array(
					'setting_key' => 'img_gallery_3',
					'id'          => 546,
					'label'       => 'Men & Kids Clothing Racks'
				),
				array(
					'setting_key' => 'img_gallery_4',
					'id'          => 541,
					'label'       => 'Trials Room & Customer Lounge'
				),
				array(
					'setting_key' => 'img_gallery_5',
					'id'          => 545,
					'label'       => 'In-Store Alterations Desk'
				),
				array(
					'setting_key' => 'img_gallery_6',
					'id'          => 544,
					'label'       => 'Women\'s Ethnic wear showcase'
				),
			);

			foreach ( $showroom_photos as $photo ) :
				$img_html = ame_bazaar_get_showroom_image_html( $photo['setting_key'], $photo['id'], 'medium_large', array(
					'class'   => 'ame-gallery-img',
					'style'   => 'width:100%; height:100%; object-fit:cover; display:block; transition: transform 0.4s ease;',
					'loading' => 'lazy',
					'alt'     => esc_attr( $photo['label'] . ' - AME Bazaar Kirari Delhi' ),
				) );

				if ( $img_html ) :
				?>
					<div class="ame-insta-card" style="position: relative; overflow: hidden; border-radius: var(--ame-radius-md); box-shadow: var(--ame-shadow-sm); aspect-ratio: 1/1;">
						<div class="ame-insta-visual-wrap" style="width: 100%; height: 100%;">
							<div class="ame-insta-img-wrapper" style="width: 100%; height: 100%; position: relative;">
								<?php echo $img_html; ?>
								<div class="ame-insta-hover-overlay" style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0, 35, 71, 0.9) 10%, rgba(0, 35, 71, 0.4) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 1.5rem; opacity: 0; transition: opacity 0.3s ease; pointer-events: none;">
									<h4 style="color: #ffffff; font-size: 1rem; font-weight: 700; margin: 0 0 0.25rem 0;"><?php echo esc_html( $photo['label'] ); ?></h4>
									<p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0;">AME Bazaar Delhi 110086</p>
								</div>
							</div>
						</div>
					</div>
				<?php
				endif;
			endforeach;
			?>
		</div>
	</div>
</section>

<style>
.ame-insta-card:hover .ame-gallery-img {
	transform: scale(1.05);
}
.ame-insta-card:hover .ame-insta-hover-overlay {
	opacity: 1 !important;
}
</style>
