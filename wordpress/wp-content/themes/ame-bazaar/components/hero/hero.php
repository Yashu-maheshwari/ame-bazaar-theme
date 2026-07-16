<?php
/**
 * Cinematic Premium Hero - AME Bazaar Flagship Experience
 * Media Manager is the ONLY source of truth.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Media Manager as the sole source of truth
$hero_desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$hero_mobile_id  = get_option( 'ame_bazaar_media_hero_mobile' );
$maps_url        = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$shop_url        = home_url( '/shop/' );

// Build srcset/URLs only if set — never hardcode
$hero_desktop_url = $hero_desktop_id ? wp_get_attachment_image_url( $hero_desktop_id, 'full' ) : '';
$hero_mobile_url  = $hero_mobile_id  ? wp_get_attachment_image_url( $hero_mobile_id,  'full' ) : $hero_desktop_url;

// Fallback poster color — no hardcoded image
$hero_bg_color = '#0a0f1e';
?>

<section class="ame-hero" id="ame-hero" aria-label="<?php esc_attr_e( 'AME Bazaar Featured Campaign', 'ame-bazaar' ); ?>">

	<!-- === CINEMATIC CANVAS === -->
	<div class="ame-hero__canvas" id="ame-hero-canvas">

		<?php if ( $hero_desktop_url ) : ?>
		<!-- GSAP Parallax Image Layer -->
		<div class="ame-hero__parallax-layer" id="ame-hero-parallax">
			<picture>
				<?php if ( $hero_mobile_url && $hero_mobile_url !== $hero_desktop_url ) : ?>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( $hero_mobile_url ); ?>">
				<?php endif; ?>
				<?php
				echo wp_get_attachment_image( $hero_desktop_id, 'full', false, array(
					'class'         => 'ame-hero__image',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'alt'           => esc_html__( 'AME Bazaar Family Fashion Campaign', 'ame-bazaar' ),
					'draggable'     => 'false',
				) );
				?>
			</picture>
		</div>
		<?php else : ?>
		<!-- No image set: show premium brand void -->
		<div class="ame-hero__void"></div>
		<?php endif; ?>

		<!-- Cinematic gradient overlay — creates layered depth -->
		<div class="ame-hero__gradient-overlay" aria-hidden="true"></div>

		<!-- Floating ambient particles -->
		<div class="ame-hero__particles" aria-hidden="true">
			<span class="ame-particle ame-particle--1"></span>
			<span class="ame-particle ame-particle--2"></span>
			<span class="ame-particle ame-particle--3"></span>
			<span class="ame-particle ame-particle--4"></span>
		</div>
	</div>

	<!-- === EDITORIAL COMPOSITION === -->
	<div class="ame-hero__editorial" id="ame-hero-editorial">

		<!-- Eyebrow Season Label -->
		<div class="ame-hero__eyebrow" id="ame-hero-eyebrow" data-gsap="fadeUp">
			<span class="ame-hero__eyebrow-line"></span>
			<span class="ame-hero__eyebrow-text"><?php esc_html_e( 'Festive Collection &mdash; 2026', 'ame-bazaar' ); ?></span>
			<span class="ame-hero__eyebrow-line"></span>
		</div>

		<!-- Hero Title — Playfair Display, perceived 3D gradient -->
		<h1 class="ame-hero__title" id="ame-hero-title" data-gsap="fadeUp">
			<span class="ame-hero__title-line"><?php esc_html_e( 'Dress The', 'ame-bazaar' ); ?></span>
			<span class="ame-hero__title-line ame-hero__title-line--italic"><?php esc_html_e( 'Moment.', 'ame-bazaar' ); ?></span>
		</h1>

		<!-- Subtitle -->
		<p class="ame-hero__subtitle" id="ame-hero-subtitle" data-gsap="fadeUp">
			<?php esc_html_e( 'Premium fashion for every occasion &mdash; Men, Women &amp; Kids', 'ame-bazaar' ); ?>
		</p>

		<!-- CTAs — all original business buttons, redesigned -->
		<div class="ame-hero__actions" id="ame-hero-actions" data-gsap="fadeUp">
			<!-- Shop Collection (primary) -->
			<a href="<?php echo esc_url( $shop_url ); ?>" class="ame-hero-btn ame-hero-btn--primary" id="ame-hero-cta-shop">
				<span class="ame-hero-btn__inner">
					<span class="ame-hero-btn__text"><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
					<svg class="ame-hero-btn__arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
						<path d="M1 7h12M8 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="square"/>
					</svg>
				</span>
			</a>

			<!-- Visit Store (secondary) -->
			<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-hero-btn ame-hero-btn--secondary" id="ame-hero-cta-store">
				<span class="ame-hero-btn__text"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>
		</div>

		<!-- Scroll hint -->
		<div class="ame-hero__scroll-indicator" aria-label="<?php esc_attr_e( 'Scroll to explore', 'ame-bazaar' ); ?>">
			<div class="ame-hero__scroll-dot"></div>
		</div>
	</div>

</section>

<!-- GSAP Cinematic Hero Animations — loaded after content -->
<script>
(function() {
	'use strict';

	function initHeroGSAP() {
		if (typeof gsap === 'undefined') return;

		const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

		// Cinematic image entrance — slow breathing parallax
		gsap.fromTo('#ame-hero-parallax',
			{ scale: 1.06, opacity: 0.7 },
			{ scale: 1, opacity: 1, duration: 2.5, ease: 'power2.out' }
		);

		// Editorial stagger entrance
		tl.fromTo('#ame-hero-eyebrow',
			{ opacity: 0, y: 20 },
			{ opacity: 1, y: 0, duration: 1.0, delay: 0.3 }
		)
		.fromTo('#ame-hero-title .ame-hero__title-line',
			{ opacity: 0, y: 40, skewX: -3 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.2, stagger: 0.15 },
			'-=0.5'
		)
		.fromTo('#ame-hero-subtitle',
			{ opacity: 0, y: 25 },
			{ opacity: 1, y: 0, duration: 0.9 },
			'-=0.6'
		)
		.fromTo('#ame-hero-actions',
			{ opacity: 0, y: 20 },
			{ opacity: 1, y: 0, duration: 0.8 },
			'-=0.5'
		)
		.fromTo('.ame-hero__scroll-indicator',
			{ opacity: 0 },
			{ opacity: 1, duration: 0.8 },
			'-=0.3'
		);

		// Parallax on mouse move — subtle luxury depth
		if (window.innerWidth > 768) {
			const canvas = document.getElementById('ame-hero-canvas');
			const parallax = document.getElementById('ame-hero-parallax');
			if (canvas && parallax) {
				canvas.addEventListener('mousemove', function(e) {
					const rect = canvas.getBoundingClientRect();
					const cx = (e.clientX - rect.left) / rect.width - 0.5;
					const cy = (e.clientY - rect.top) / rect.height - 0.5;
					gsap.to(parallax, {
						x: cx * 18,
						y: cy * 12,
						duration: 1.8,
						ease: 'power1.out'
					});
				});
				canvas.addEventListener('mouseleave', function() {
					gsap.to(parallax, { x: 0, y: 0, duration: 2, ease: 'power2.out' });
				});
			}
		}

		// Ambient floating particles
		gsap.to('.ame-particle--1', { y: '-20px', duration: 4, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0 });
		gsap.to('.ame-particle--2', { y: '15px', duration: 5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 1 });
		gsap.to('.ame-particle--3', { y: '-25px', duration: 6, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.5 });
		gsap.to('.ame-particle--4', { y: '10px', duration: 3.5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 2 });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHeroGSAP);
	} else {
		initHeroGSAP();
	}
})();
</script>
