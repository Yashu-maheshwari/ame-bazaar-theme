<?php
/**
 * Cinematic Living Hero — Dynamic Hero Video Edition
 *
 * Architecture:
 *   - Video container supporting Desktop/Mobile WebM and MP4 formats.
 *   - Full fallback priority (Video -> Poster -> Fallback -> Placeholder).
 *   - Text overlay with high-contrast legibility support.
 *   - Atmospheric micro-motions (Depth breathe, light shimmer, bokeh, parallax, scroll cue).
 *
 * Media Manager is the ONLY source of truth.
 * Falls back gracefully if media is missing.
 * PHP 5.6+ compatible. UTF-8 without BOM.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch Admin configured values
$label         = get_option( 'ame_bazaar_hero_label', __( 'Summer Collection', 'ame-bazaar' ) );
$headline      = get_option( 'ame_bazaar_hero_headline', __( 'Dress The Moment.', 'ame-bazaar' ) );
$subheading    = get_option( 'ame_bazaar_hero_subheading', __( 'Breathable linen, light coordinates, and effortless styles for the Delhi summer.', 'ame-bazaar' ) );
$primary_btn   = get_option( 'ame_bazaar_hero_primary_btn_text', __( 'Shop Collection', 'ame-bazaar' ) );
$secondary_btn = get_option( 'ame_bazaar_hero_secondary_btn_text', __( 'Visit Store', 'ame-bazaar' ) );

// Fetch media files (IDs to URLs)
$desktop_webm = wp_get_attachment_url( get_option( 'ame_bazaar_media_hero_desktop_video_webm' ) );
$desktop_mp4  = wp_get_attachment_url( get_option( 'ame_bazaar_media_hero_desktop_video_mp4' ) );
$mobile_webm  = wp_get_attachment_url( get_option( 'ame_bazaar_media_hero_mobile_video_webm' ) );
$mobile_mp4   = wp_get_attachment_url( get_option( 'ame_bazaar_media_hero_mobile_video_mp4' ) );
$poster       = wp_get_attachment_image_url( get_option( 'ame_bazaar_media_hero_poster' ), 'full' );
$fallback     = wp_get_attachment_image_url( get_option( 'ame_bazaar_media_hero_fallback' ), 'full' );

$ultimate_fallback = ame_bazaar_asset_uri( 'assets/images/hero-placeholder.jpg' );

// Resolve fallback values
if ( ! $poster ) {
	$poster = $ultimate_fallback;
}
if ( ! $fallback ) {
	$fallback = $ultimate_fallback;
}

$has_video = $desktop_webm || $desktop_mp4 || $mobile_webm || $mobile_mp4;

// Split headline at the last word to preserve the dual-line styling
$words = explode( ' ', $headline );
if ( count( $words ) > 1 ) {
	$last_word = array_pop( $words );
	$headline_l1 = implode( ' ', $words );
	$headline_l2 = $last_word;
} else {
	$headline_l1 = $headline;
	$headline_l2 = '';
}

// Business URLs
$shop_url = home_url( '/shop/' );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section
	class="ame-hero"
	id="ame-hero"
	aria-label="<?php esc_attr_e( 'AME Bazaar Fashion Campaign', 'ame-bazaar' ); ?>"
	data-slide-count="1"
>

	<!-- ═══ IMAGE / VIDEO STACK ═══ -->
	<div class="ame-hero__stage" id="ame-hero-stage" aria-hidden="true">

		<div class="ame-hero__slides" id="ame-hero-slides">
			<div class="ame-hero__slide is-active" data-slide="0" aria-hidden="false">
				<?php if ( $has_video ) : ?>
					<video
						class="ame-hero__video"
						autoplay
						muted
						loop
						playsinline
						preload="auto"
						poster="<?php echo esc_url( $poster ); ?>"
						draggable="false"
					>
						<?php if ( $mobile_webm ) : ?>
							<source src="<?php echo esc_url( $mobile_webm ); ?>" type="video/webm" media="(max-width: 767px)">
						<?php endif; ?>
						<?php if ( $mobile_mp4 ) : ?>
							<source src="<?php echo esc_url( $mobile_mp4 ); ?>" type="video/mp4" media="(max-width: 767px)">
						<?php endif; ?>
						<?php if ( $desktop_webm ) : ?>
							<source src="<?php echo esc_url( $desktop_webm ); ?>" type="video/webm">
						<?php endif; ?>
						<?php if ( $desktop_mp4 ) : ?>
							<source src="<?php echo esc_url( $desktop_mp4 ); ?>" type="video/mp4">
						<?php endif; ?>
					</video>
				<?php else : ?>
					<picture class="ame-hero__picture">
						<img
							class="ame-hero__image"
							src="<?php echo esc_url( $fallback ); ?>"
							alt="<?php echo esc_attr( $headline ) . ' — ' . esc_attr__( 'AME Bazaar Premium Family Fashion', 'ame-bazaar' ); ?>"
							loading="eager"
							fetchpriority="high"
							decoding="async"
							draggable="false"
						>
					</picture>
				<?php endif; ?>
			</div>
		</div>

		<!-- Light shimmer — perpetual atmospheric drift -->
		<div class="ame-hero__shimmer" id="ame-hero-shimmer" aria-hidden="true"></div>

		<!-- Bokeh particles — floating dust motes in warm light -->
		<div class="ame-hero__bokeh" id="ame-hero-bokeh" aria-hidden="true"></div>

		<!-- Gradient scrim — left legibility + bottom vignette -->
		<div class="ame-hero__overlay" aria-hidden="true"></div>

	</div><!-- /.ame-hero__stage -->

	<!-- ═══ EDITORIAL PANEL ═══ -->
	<div class="ame-hero__editorial" id="ame-hero-editorial">

		<!-- Eyebrow collection label -->
		<div class="ame-hero__eyebrow" id="ame-hero-eyebrow" aria-live="polite">
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
			<span class="ame-hero__eyebrow-text" id="ame-hero-label">
				<?php echo esc_html( $label ); ?>
			</span>
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
		</div>

		<!-- Main headline -->
		<h1 class="ame-hero__headline" id="ame-hero-headline">
			<span class="ame-hero__hl1" id="ame-hero-hl1"><?php echo esc_html( $headline_l1 ); ?></span>
			<?php if ( $headline_l2 ) : ?>
				<em class="ame-hero__hl2" id="ame-hero-hl2"><?php echo esc_html( $headline_l2 ); ?></em>
			<?php endif; ?>
		</h1>

		<!-- Subline -->
		<p class="ame-hero__sub" id="ame-hero-sub">
			<?php echo esc_html( $subheading ); ?>
		</p>

		<!-- CTAs -->
		<div class="ame-hero__actions" id="ame-hero-actions">
			<?php if ( $primary_btn ) : ?>
				<a href="<?php echo esc_url( $shop_url ); ?>"
					class="ame-hero-btn ame-hero-btn--glass"
					id="ame-hero-btn-shop">
					<span class="ame-hero-btn__text"><?php echo esc_html( $primary_btn ); ?></span>
					<svg class="ame-hero-btn__arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
						<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
					</svg>
				</a>
			<?php endif; ?>
			<?php if ( $secondary_btn ) : ?>
				<a href="<?php echo esc_url( $maps_url ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="ame-hero-btn ame-hero-btn--navy"
					id="ame-hero-btn-store">
					<span class="ame-hero-btn__text"><?php echo esc_html( $secondary_btn ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<!-- Scroll cue -->
		<div class="ame-hero__scroll-cue" aria-hidden="true">
			<div class="ame-hero__scroll-line"></div>
		</div>

	</div><!-- /.ame-hero__editorial -->

</section><!-- /#ame-hero -->

<script id="ame-hero-engine">
/* =============================================================================
   AME Bazaar — Cinematic Living Hero Engine (Dynamic Hero Video Edition)
   Technology: GSAP 3 — opacity + transform ONLY (zero WebGL, zero canvas)
============================================================================= */
(function () {
	'use strict';

	/* ── Guard: GSAP must be loaded ── */
	if ( typeof gsap === 'undefined' ) {
		var ed = document.getElementById('ame-hero-editorial');
		if ( ed ) ed.style.opacity = '1';
		return;
	}

	/* ── DOM references ── */
	var hero      = document.getElementById('ame-hero');
	var stage     = document.getElementById('ame-hero-stage');
	var editorial = document.getElementById('ame-hero-editorial');
	var slide     = document.querySelector('.ame-hero__slide');
	var shimmer   = document.getElementById('ame-hero-shimmer');
	var bokehWrap = document.getElementById('ame-hero-bokeh');
	var eyebrow   = document.getElementById('ame-hero-eyebrow');
	var hl1       = document.getElementById('ame-hero-hl1');
	var hl2       = document.getElementById('ame-hero-hl2');
	var subEl     = document.getElementById('ame-hero-sub');
	var actions   = document.getElementById('ame-hero-actions');
	var scrollCue = document.querySelector('.ame-hero__scroll-cue');

	if ( !hero || !editorial ) return;

	var imgEl = slide ? slide.querySelector('.ame-hero__image, .ame-hero__video') : null;

	/* ══════════════════════════════════════════════
	   1. INITIAL STATES
	══════════════════════════════════════════════ */
	gsap.set( editorial, { opacity: 0 } );
	if ( slide ) gsap.set( slide, { opacity: 1 } );
	if ( shimmer ) gsap.set( shimmer, { opacity: 0 } );

	/* Ken-Burns initial state */
	if ( imgEl ) gsap.set( imgEl, { scale: 1.06, transformOrigin: '55% 50%' } );

	/* ══════════════════════════════════════════════
	   2. ENTRANCE ANIMATION
	══════════════════════════════════════════════ */
	var entranceTL = gsap.timeline({ delay: 0.15 });
	entranceTL
		.set( editorial, { opacity: 1 } )
		.fromTo( eyebrow,
			{ opacity: 0, y: 18 },
			{ opacity: 1, y: 0, duration: 0.85, ease: 'power2.out' }
		)
		.fromTo( hl1,
			{ opacity: 0, y: 40, skewX: -2 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.1, ease: 'power3.out' },
			'-=0.4'
		);

	if ( hl2 ) {
		entranceTL.fromTo( hl2,
			{ opacity: 0, y: 50, skewX: -2 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.1, ease: 'power3.out' },
			'-=0.75'
		);
	}

	entranceTL
		.fromTo( subEl,
			{ opacity: 0, y: 22 },
			{ opacity: 1, y: 0, duration: 0.85, ease: 'power2.out' },
			'-=0.5'
		)
		.fromTo( actions,
			{ opacity: 0, y: 16 },
			{ opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
			'-=0.45'
		);

	if ( scrollCue ) {
		gsap.fromTo( scrollCue,
			{ opacity: 0 },
			{ opacity: 1, duration: 1.2, delay: 2.8, ease: 'power1.out' }
		);
	}

	/* ══════════════════════════════════════════════
	   3. KEN-BURNS
	══════════════════════════════════════════════ */
	if ( imgEl ) {
		gsap.fromTo( imgEl,
			{ scale: 1.06, transformOrigin: '55% 50%' },
			{ scale: 1.0,  duration: 13, ease: 'power1.out' }
		);
	}

	/* ══════════════════════════════════════════════
	   4. DEPTH BREATHE
	══════════════════════════════════════════════ */
	if ( stage ) {
		gsap.to( stage, {
			scale: 1.005,
			duration: 6,
			ease: 'sine.inOut',
			yoyo: true,
			repeat: -1,
			transformOrigin: '50% 50%'
		} );
	}

	/* ══════════════════════════════════════════════
	   5. LIGHT SHIMMER
	══════════════════════════════════════════════ */
	if ( shimmer ) {
		gsap.to( shimmer, { opacity: 0.07, duration: 1.5, ease: 'power1.out', delay: 0.5 } );
		gsap.to( shimmer, {
			x: '4%',
			opacity: 0.11,
			duration: 9,
			ease: 'sine.inOut',
			yoyo: true,
			repeat: -1,
			delay: 2
		} );
	}

	/* ══════════════════════════════════════════════
	   6. BOKEH PARTICLES
	══════════════════════════════════════════════ */
	(function () {
		if ( !bokehWrap ) return;
		var N = 10;
		for ( var p = 0; p < N; p++ ) {
			var dot = document.createElement('span');
			dot.className = 'ame-hero__particle';
			var sz = Math.random() * 3 + 2;
			dot.style.cssText = [
				'position:absolute',
				'border-radius:50%',
				'background:rgba(255,245,200,0.22)',
				'width:' + sz + 'px',
				'height:' + sz + 'px',
				'left:' + ( 45 + Math.random() * 50 ) + '%',
				'top:' + ( 20 + Math.random() * 60 ) + '%',
				'will-change:transform,opacity',
				'pointer-events:none'
			].join(';');
			bokehWrap.appendChild( dot );

			(function ( el ) {
				function animate() {
					var dur = 3.5 + Math.random() * 4;
					var dy  = -( 80 + Math.random() * 80 );
					gsap.fromTo( el,
						{ opacity: 0, y: 0 },
						{
							opacity: 0.22,
							y: dy,
							duration: dur,
							ease: 'none',
							onComplete: function () {
								gsap.set( el, { y: 0, opacity: 0 } );
								setTimeout( animate, Math.random() * 2000 );
							}
						}
					);
				}
				setTimeout( animate, Math.random() * 5000 );
			})( dot );
		}
	})();

	/* ══════════════════════════════════════════════
	   7. MOUSE PARALLAX — editorial panel only
	══════════════════════════════════════════════ */
	if ( window.innerWidth > 900 && hero ) {
		var rafId = null;
		var mx = 0, my = 0;
		var pxD = 14;

		function applyParallax() {
			gsap.to( editorial, {
				x: mx * pxD,
				y: my * ( pxD * 0.55 ),
				duration: 2.0,
				ease: 'power1.out',
				overwrite: 'auto'
			} );
			rafId = null;
		}

		hero.addEventListener( 'mousemove', function ( e ) {
			var r = hero.getBoundingClientRect();
			mx = ( e.clientX - r.left )  / r.width  - 0.5;
			my = ( e.clientY - r.top )   / r.height - 0.5;
			if ( !rafId ) rafId = requestAnimationFrame( applyParallax );
		}, { passive: true } );

		hero.addEventListener( 'mouseleave', function () {
			mx = 0; my = 0;
			gsap.to( editorial, { x: 0, y: 0, duration: 1.6, ease: 'power2.out', overwrite: 'auto' } );
		} );
	}

	/* ══════════════════════════════════════════════
	   8. SCROLL PARALLAX
	══════════════════════════════════════════════ */
	window.addEventListener( 'scroll', function () {
		var sy = window.pageYOffset;
		if ( sy > window.innerHeight ) return;
		var activeImg = slide ? slide.querySelector('.ame-hero__image, .ame-hero__video') : null;
		if ( activeImg ) {
			gsap.to( activeImg, {
				y: sy * 0.25,
				duration: 0.5,
				ease: 'none',
				overwrite: 'auto'
			} );
		}
	}, { passive: true } );

	/* ══════════════════════════════════════════════
	   9. SCROLL CUE
	══════════════════════════════════════════════ */
	if ( scrollCue ) {
		gsap.to( scrollCue.querySelector('.ame-hero__scroll-line'), {
			scaleY: 0.4,
			transformOrigin: 'top center',
			duration: 0.8,
			ease: 'sine.inOut',
			yoyo: true,
			repeat: -1,
			delay: 3.2
		} );
	}

})();
</script>
