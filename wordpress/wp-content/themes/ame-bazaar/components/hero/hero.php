<?php
/**
 * Cinematic Hero — Collection Crossfade + Parallax
 * Media Manager is the ONLY source of truth.
 * Supports 3 collection slides: Summer, Festive, Winter.
 * Falls back gracefully if only 1 image is set.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Media Manager IDs ────────────────────────────────────────────────────────
$slide_data = array(
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_desktop' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_mobile' ),
		'label'      => __( 'Summer Collection', 'ame-bazaar' ),
		'season'     => 'summer',
	),
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_festive' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_festive_mobile' ),
		'label'      => __( 'Festive Collection — Diwali', 'ame-bazaar' ),
		'season'     => 'festive',
	),
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_winter' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_winter_mobile' ),
		'label'      => __( 'Winter Collection', 'ame-bazaar' ),
		'season'     => 'winter',
	),
);

// Remove any slides where no desktop image has been set
$slides = array_values( array_filter( $slide_data, function( $s ) {
	return $s['desktop_id'] > 0;
} ) );

// If nothing is set, render a minimal branded void
$has_images = ! empty( $slides );

// Business URLs (never hardcoded)
$shop_url  = home_url( '/shop/' );
$maps_url  = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section
	class="ame-hero<?php echo $has_images ? '' : ' ame-hero--void'; ?>"
	id="ame-hero"
	aria-label="<?php esc_attr_e( 'AME Bazaar Fashion Campaign', 'ame-bazaar' ); ?>"
	data-slide-count="<?php echo count( $slides ); ?>"
>

	<!-- ═══ CINEMATIC CANVAS ═══ -->
	<div class="ame-hero__canvas" id="ame-hero-canvas">

		<?php if ( $has_images ) : ?>

		<!-- Image stack: each slide is absolutely positioned, opacity-controlled by GSAP -->
		<div class="ame-hero__slides" id="ame-hero-slides" aria-hidden="true">
			<?php foreach ( $slides as $i => $slide ) :
				$desktop_url = wp_get_attachment_image_url( $slide['desktop_id'], 'full' );
				$mobile_url  = $slide['mobile_id'] > 0
					? wp_get_attachment_image_url( $slide['mobile_id'], 'full' )
					: $desktop_url;
				$is_first    = ( $i === 0 );
				$loading     = $is_first ? 'eager' : 'lazy';
				$priority    = $is_first ? ' fetchpriority="high"' : '';
			?>
			<div
				class="ame-hero__slide<?php echo $is_first ? ' is-active' : ''; ?>"
				data-slide="<?php echo $i; ?>"
				data-season="<?php echo esc_attr( $slide['season'] ); ?>"
				aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
			>
				<picture>
					<?php if ( $mobile_url && $mobile_url !== $desktop_url ) : ?>
					<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_url ); ?>">
					<?php endif; ?>
					<img
						class="ame-hero__image"
						src="<?php echo esc_url( $desktop_url ); ?>"
						alt="<?php echo esc_attr( $slide['label'] ) . ' — ' . esc_attr__( 'AME Bazaar Premium Family Fashion', 'ame-bazaar' ); ?>"
						loading="<?php echo $loading; ?>"
						<?php echo $priority; ?>
						decoding="async"
						draggable="false"
					>
				</picture>
			</div>
			<?php endforeach; ?>
		</div>

		<?php else : ?>
		<!-- Brand void: premium dark background when no images uploaded -->
		<div class="ame-hero__void" aria-hidden="true"></div>
		<?php endif; ?>

		<!-- Cinematic depth overlay — left-legibility + bottom vignette -->
		<div class="ame-hero__overlay" aria-hidden="true"></div>

	</div><!-- /.ame-hero__canvas -->

	<!-- ═══ EDITORIAL COMPOSITION ═══ -->
	<div class="ame-hero__editorial" id="ame-hero-editorial">

		<!-- Collection label — changes with each slide transition -->
		<div class="ame-hero__eyebrow" id="ame-hero-eyebrow">
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
			<span class="ame-hero__eyebrow-label" id="ame-hero-season-label">
				<?php echo esc_html( $has_images ? $slides[0]['label'] : __( 'Premium Fashion', 'ame-bazaar' ) ); ?>
			</span>
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
		</div>

		<!-- Hero title — Playfair Display, gradient 3D text -->
		<h1 class="ame-hero__title" id="ame-hero-title">
			<span class="ame-hero__title-l1" id="ame-hero-line1"><?php esc_html_e( 'Dress The', 'ame-bazaar' ); ?></span>
			<span class="ame-hero__title-l2 ame-hero__title-l2--italic" id="ame-hero-line2"><?php esc_html_e( 'Moment.', 'ame-bazaar' ); ?></span>
		</h1>

		<!-- Subline -->
		<p class="ame-hero__sub" id="ame-hero-sub">
			<?php esc_html_e( 'Premium fashion for every occasion — Men, Women &amp; Kids', 'ame-bazaar' ); ?>
		</p>

		<!-- Business CTAs — all original, redesigned -->
		<div class="ame-hero__actions" id="ame-hero-actions">

			<!-- Shop Collection (primary glass) -->
			<a href="<?php echo esc_url( $shop_url ); ?>"
				class="ame-hero-btn ame-hero-btn--glass"
				id="ame-hero-btn-shop"
			>
				<span class="ame-hero-btn__label"><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
				<svg class="ame-hero-btn__arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
					<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
				</svg>
			</a>

			<!-- Visit Store (secondary navy) -->
			<a href="<?php echo esc_url( $maps_url ); ?>"
				target="_blank" rel="noopener noreferrer"
				class="ame-hero-btn ame-hero-btn--navy"
				id="ame-hero-btn-store"
			>
				<span class="ame-hero-btn__label"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>

		</div><!-- /.ame-hero__actions -->

		<!-- Slide indicator dots (hidden if only 1 slide) -->
		<?php if ( count( $slides ) > 1 ) : ?>
		<div class="ame-hero__dots" id="ame-hero-dots" role="tablist" aria-label="<?php esc_attr_e( 'Collection slides', 'ame-bazaar' ); ?>">
			<?php foreach ( $slides as $i => $slide ) : ?>
			<button
				class="ame-hero__dot<?php echo $i === 0 ? ' is-active' : ''; ?>"
				data-slide="<?php echo $i; ?>"
				role="tab"
				aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
				aria-label="<?php echo esc_attr( $slide['label'] ); ?>"
			></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<!-- Scroll cue -->
		<div class="ame-hero__scroll-cue" aria-label="<?php esc_attr_e( 'Scroll to explore', 'ame-bazaar' ); ?>" aria-hidden="true">
			<div class="ame-hero__scroll-line"></div>
		</div>

	</div><!-- /.ame-hero__editorial -->

</section><!-- /#ame-hero -->

<?php
// Expose slide data & labels to JS (zero hardcoded strings in JS)
$js_slides = array();
foreach ( $slides as $i => $s ) {
	$js_slides[] = array(
		'index'  => $i,
		'season' => $s['season'],
		'label'  => $s['label'],
	);
}
?>

<script id="ame-hero-data">
/* AME Hero config — generated server-side, never hardcoded */
window.AME_HERO = {
	slides:        <?php echo wp_json_encode( $js_slides ); ?>,
	slideCount:    <?php echo count( $slides ); ?>,
	slideDuration: 7,   // seconds per slide
	transitionDur: 1.4, // seconds for crossfade
	parallaxDepth: 18,  // px for mouse parallax
	shopUrl:       <?php echo wp_json_encode( esc_url( $shop_url ) ); ?>,
};
</script>

<script id="ame-hero-motion">
/* ─────────────────────────────────────────────────────────────────────────────
   AME Bazaar — Cinematic Hero Engine
   Technology: GSAP 3 (GPU-safe transform/opacity only)
   Philosophy: Real photography + camera motion + collection crossfade
   No particles. No cheap effects. Luxury motion.
───────────────────────────────────────────────────────────────────────────────*/
(function () {
	'use strict';

	var hero   = document.getElementById('ame-hero');
	var canvas = document.getElementById('ame-hero-canvas');

	if (!hero || typeof gsap === 'undefined') {
		// No GSAP — ensure editorial is visible (CSS fallback)
		if (document.getElementById('ame-hero-editorial')) {
			document.getElementById('ame-hero-editorial').style.opacity = '1';
		}
		return;
	}

	var cfg     = window.AME_HERO || {};
	var slides  = cfg.slides      || [];
	var dur     = cfg.slideDuration   || 7;
	var txDur   = cfg.transitionDur   || 1.4;
	var pxDepth = cfg.parallaxDepth   || 18;

	var slideEls      = document.querySelectorAll('.ame-hero__slide');
	var slideImgs     = document.querySelectorAll('.ame-hero__image');
	var seasonLabel   = document.getElementById('ame-hero-season-label');
	var editorial     = document.getElementById('ame-hero-editorial');
	var eyebrow       = document.getElementById('ame-hero-eyebrow');
	var line1         = document.getElementById('ame-hero-line1');
	var line2         = document.getElementById('ame-hero-line2');
	var sub           = document.getElementById('ame-hero-sub');
	var actions       = document.getElementById('ame-hero-actions');
	var dots          = document.querySelectorAll('.ame-hero__dot');
	var scrollCue     = document.querySelector('.ame-hero__scroll-cue');

	var current = 0;
	var total   = slideEls.length;
	var loopTimer = null;

	/* ── 1. Initial state: hide all slides except first ── */
	gsap.set(slideEls, { opacity: 0, scale: 1.06 });
	gsap.set(slideEls[0], { opacity: 1, scale: 1.03 });

	/* ── 2. Hero editorial entrance (stagger) ── */
	var entranceTL = gsap.timeline({ delay: 0.2 });
	entranceTL
		.set(editorial, { opacity: 1 })
		.fromTo(eyebrow,
			{ opacity: 0, y: 16 },
			{ opacity: 1, y: 0, duration: 0.9, ease: 'power2.out' }
		)
		.fromTo(line1,
			{ opacity: 0, y: 36, skewX: -2 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.1, ease: 'power3.out' },
			'-=0.4'
		)
		.fromTo(line2,
			{ opacity: 0, y: 44, skewX: -2 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.1, ease: 'power3.out' },
			'-=0.75'
		)
		.fromTo(sub,
			{ opacity: 0, y: 20 },
			{ opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
			'-=0.5'
		)
		.fromTo(actions,
			{ opacity: 0, y: 16 },
			{ opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
			'-=0.4'
		);

	if (scrollCue) {
		gsap.fromTo(scrollCue,
			{ opacity: 0 },
			{ opacity: 1, duration: 1, delay: 2, ease: 'power1.out' }
		);
	}

	/* ── 3. First slide: slow cinematic breathing zoom ── */
	if (slideImgs[0]) {
		gsap.fromTo(slideImgs[0],
			{ scale: 1.06 },
			{ scale: 1.0, duration: dur * 1.2, ease: 'power1.out' }
		);
	}

	/* ── 4. Cross-fade to next slide ── */
	function goToSlide(next) {
		if (total <= 1) return;
		next = ((next % total) + total) % total;

		var prevEl   = slideEls[current];
		var nextEl   = slideEls[next];
		var prevImg  = prevEl ? prevEl.querySelector('.ame-hero__image') : null;
		var nextImg  = nextEl ? nextEl.querySelector('.ame-hero__image') : null;

		// Update dots
		dots.forEach(function (d, i) {
			d.classList.toggle('is-active', i === next);
			d.setAttribute('aria-selected', i === next ? 'true' : 'false');
		});

		// Season label fade-swap
		if (seasonLabel && slides[next]) {
			gsap.to(seasonLabel, {
				opacity: 0, y: -8, duration: 0.35, ease: 'power2.in',
				onComplete: function () {
					seasonLabel.textContent = slides[next].label;
					gsap.fromTo(seasonLabel,
						{ opacity: 0, y: 8 },
						{ opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' }
					);
				}
			});
		}

		// Crossfade images
		var crossTL = gsap.timeline();

		// Reset incoming slide position
		crossTL.set(nextEl, { opacity: 0 });
		if (nextImg) crossTL.set(nextImg, { scale: 1.05 });

		// Fade out prev, fade in next
		crossTL
			.to(prevEl, { opacity: 0, duration: txDur, ease: 'power2.inOut' }, 0)
			.to(nextEl, { opacity: 1, duration: txDur, ease: 'power2.inOut' }, 0);

		// Cinematic slow zoom on incoming image
		if (nextImg) {
			crossTL.to(nextImg, { scale: 1.0, duration: dur * 1.1, ease: 'power1.out' }, 0);
		}

		// Aria
		if (prevEl) prevEl.setAttribute('aria-hidden', 'true');
		if (nextEl) nextEl.setAttribute('aria-hidden', 'false');

		current = next;
	}

	/* ── 5. Auto-advance loop ── */
	function startLoop() {
		clearTimeout(loopTimer);
		if (total > 1) {
			loopTimer = setTimeout(function () {
				goToSlide(current + 1);
				startLoop();
			}, dur * 1000);
		}
	}

	// Start after entrance completes
	entranceTL.call(startLoop);

	/* ── 6. Manual dot navigation ── */
	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () {
			if (i === current) return;
			clearTimeout(loopTimer);
			goToSlide(i);
			startLoop();
		});
	});

	/* ── 7. Luxury mouse parallax ── */
	if (window.innerWidth > 768 && canvas) {
		var mouseX = 0, mouseY = 0;
		var raf    = null;

		function applyParallax() {
			var activeImg = slideEls[current] ? slideEls[current].querySelector('.ame-hero__image') : null;
			if (!activeImg) return;
			gsap.to(activeImg, {
				x: mouseX * pxDepth,
				y: mouseY * pxDepth * 0.6,
				duration: 2.2,
				ease: 'power1.out',
				overwrite: 'auto'
			});
		}

		canvas.addEventListener('mousemove', function (e) {
			var r  = canvas.getBoundingClientRect();
			mouseX = (e.clientX - r.left) / r.width  - 0.5;
			mouseY = (e.clientY - r.top)  / r.height - 0.5;
			if (!raf) raf = requestAnimationFrame(function () { applyParallax(); raf = null; });
		}, { passive: true });

		canvas.addEventListener('mouseleave', function () {
			mouseX = 0; mouseY = 0;
			var activeImg = slideEls[current] ? slideEls[current].querySelector('.ame-hero__image') : null;
			if (activeImg) {
				gsap.to(activeImg, { x: 0, y: 0, duration: 2, ease: 'power2.out', overwrite: 'auto' });
			}
		});
	}

	/* ── 8. Scroll-based parallax (subtle depth on scroll) ── */
	window.addEventListener('scroll', function () {
		var y = window.pageYOffset;
		if (y > window.innerHeight) return;
		var activeImg = slideEls[current] ? slideEls[current].querySelector('.ame-hero__image') : null;
		if (activeImg && !canvas.matches(':hover')) {
			gsap.to(activeImg, {
				y: y * 0.3,
				duration: 0.6,
				ease: 'none',
				overwrite: 'auto'
			});
		}
		if (editorial) {
			gsap.to(editorial, {
				y: y * 0.12,
				duration: 0.6,
				ease: 'none',
				overwrite: 'auto'
			});
		}
	}, { passive: true });

})();
</script>
