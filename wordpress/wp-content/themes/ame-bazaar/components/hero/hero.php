<?php
/**
 * Cinematic Living Hero — Wardrobe Transformation Edition
 *
 * Architecture:
 *   - Three image layers stacked absolutely (summer / festive / winter)
 *   - GSAP luminance veil disguises image swap as sunlight shifting
 *   - Micro-motion: Ken-Burns, depth breathe, light shimmer, bokeh particles
 *   - Mouse parallax on editorial panel only (desktop)
 *   - Per-collection headlines that swap on transition
 *   - Progress bar + scroll cue
 *
 * Media Manager is the ONLY source of truth.
 * Falls back gracefully if images are missing.
 * PHP 5.6+ compatible. UTF-8 without BOM.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Per-collection data ───────────────────────────────────────────────────────
$collection_data = array(
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_desktop' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_summer_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_summer_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_summer_poster' ),
		'season'           => 'summer',
		'label'            => __( 'Summer Collection', 'ame-bazaar' ),
		'headline_l1'      => __( 'Dress The', 'ame-bazaar' ),
		'headline_l2'      => __( 'Moment.', 'ame-bazaar' ),
		'subline'          => __( 'Light fabrics for warm days — breathable, graceful, alive.', 'ame-bazaar' ),
	),
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_festive' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_festive_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_festive_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_festive_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_festive_poster' ),
		'season'           => 'festive',
		'label'            => __( 'Festive Collection', 'ame-bazaar' ),
		'headline_l1'      => __( 'Celebrate', 'ame-bazaar' ),
		'headline_l2'      => __( 'Every Thread.', 'ame-bazaar' ),
		'subline'          => __( 'Curated festive wear for the moments that truly matter.', 'ame-bazaar' ),
	),
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_winter' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_winter_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_winter_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_winter_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_winter_poster' ),
		'season'           => 'winter',
		'label'            => __( 'Winter Collection', 'ame-bazaar' ),
		'headline_l1'      => __( 'Heritage', 'ame-bazaar' ),
		'headline_l2'      => __( 'Warmth.', 'ame-bazaar' ),
		'subline'          => __( 'Kashmiri refinement and wool craftsmanship for every occasion.', 'ame-bazaar' ),
	),
);

// Filter: keep only slides with a desktop image OR a video set
$slides = array_values( array_filter( $collection_data, function( $s ) {
	return $s['desktop_id'] > 0 || $s['video_id'] > 0;
} ) );

$has_images  = ! empty( $slides );
$slide_count = count( $slides );

// Business URLs — always from settings, never hardcoded
$shop_url = home_url( '/shop/' );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );

// First slide data (for initial server-rendered text)
$first = $has_images ? $slides[0] : array(
	'label'       => __( 'Premium Fashion', 'ame-bazaar' ),
	'headline_l1' => __( 'Dress The', 'ame-bazaar' ),
	'headline_l2' => __( 'Moment.', 'ame-bazaar' ),
	'subline'     => __( 'Premium fashion for every occasion — Men, Women & Kids', 'ame-bazaar' ),
	'season'      => '',
);
?>

<section
	class="ame-hero<?php echo $has_images ? '' : ' ame-hero--void'; ?>"
	id="ame-hero"
	aria-label="<?php esc_attr_e( 'AME Bazaar Fashion Campaign', 'ame-bazaar' ); ?>"
	data-slide-count="<?php echo $slide_count; ?>"
>

	<!-- ═══ IMAGE STACK ═══ -->
	<div class="ame-hero__stage" id="ame-hero-stage" aria-hidden="true">

		<?php if ( $has_images ) : ?>
		<div class="ame-hero__slides" id="ame-hero-slides">
			<?php foreach ( $slides as $i => $slide ) :
				$desktop_url = wp_get_attachment_image_url( $slide['desktop_id'], 'full' );
				$mobile_url  = $slide['mobile_id'] > 0
					? wp_get_attachment_image_url( $slide['mobile_id'], 'full' )
					: $desktop_url;
				
				$video_url = $slide['video_id'] > 0 ? wp_get_attachment_url( $slide['video_id'] ) : '';
				$video_mobile_url = $slide['video_mobile_id'] > 0 ? wp_get_attachment_url( $slide['video_mobile_id'] ) : $video_url;
				
				$poster_url = $slide['poster_id'] > 0 ? wp_get_attachment_image_url( $slide['poster_id'], 'full' ) : $desktop_url;

				$is_first    = ( 0 === $i );
				$loading     = $is_first ? 'eager' : 'lazy';
				$fetch_pri   = $is_first ? ' fetchpriority="high"' : '';
			?>
			<div
				class="ame-hero__slide<?php echo $is_first ? ' is-active' : ''; ?>"
				data-slide="<?php echo $i; ?>"
				data-season="<?php echo esc_attr( $slide['season'] ); ?>"
				aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
			>
				<?php if ( $video_url ) : ?>
					<video
						class="ame-hero__video"
						autoplay
						muted
						loop
						playsinline
						preload="metadata"
						poster="<?php echo esc_url( $poster_url ); ?>"
						draggable="false"
					>
						<?php if ( $video_mobile_url && $video_mobile_url !== $video_url ) : ?>
						<source src="<?php echo esc_url( $video_mobile_url ); ?>" type="video/mp4" media="(max-width: 767px)">
						<?php endif; ?>
						<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $desktop_url || $poster_url ) : ?>
					<picture class="ame-hero__picture">
						<?php if ( $mobile_url && $mobile_url !== $desktop_url ) : ?>
						<source media="(max-width:767px)" srcset="<?php echo esc_url( $mobile_url ); ?>">
						<?php endif; ?>
						<img
							class="ame-hero__image"
							src="<?php echo esc_url( $desktop_url ?: $poster_url ); ?>"
							alt="<?php echo esc_attr( $slide['label'] ) . ' — ' . esc_attr__( 'AME Bazaar Premium Family Fashion', 'ame-bazaar' ); ?>"
							loading="<?php echo $loading; ?>"
							<?php echo $fetch_pri; ?>
							decoding="async"
							draggable="false"
						>
					</picture>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div><!-- /.ame-hero__slides -->
		<?php else : ?>
		<div class="ame-hero__void"></div>
		<?php endif; ?>

		<!-- Luminance veil — disguises wardrobe swap as sunlight shift -->
		<div class="ame-hero__veil" id="ame-hero-veil" aria-hidden="true"></div>

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
				<?php echo esc_html( $first['label'] ); ?>
			</span>
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
		</div>

		<!-- Main headline (changes per collection) -->
		<h1 class="ame-hero__headline" id="ame-hero-headline">
			<span class="ame-hero__hl1" id="ame-hero-hl1"><?php echo esc_html( $first['headline_l1'] ); ?></span>
			<em class="ame-hero__hl2" id="ame-hero-hl2"><?php echo esc_html( $first['headline_l2'] ); ?></em>
		</h1>

		<!-- Subline (changes per collection) -->
		<p class="ame-hero__sub" id="ame-hero-sub">
			<?php echo esc_html( $first['subline'] ); ?>
		</p>

		<!-- CTAs -->
		<div class="ame-hero__actions" id="ame-hero-actions">
			<a href="<?php echo esc_url( $shop_url ); ?>"
				class="ame-hero-btn ame-hero-btn--glass"
				id="ame-hero-btn-shop">
				<span class="ame-hero-btn__text"><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
				<svg class="ame-hero-btn__arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
					<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
				</svg>
			</a>
			<a href="<?php echo esc_url( $maps_url ); ?>"
				target="_blank" rel="noopener noreferrer"
				class="ame-hero-btn ame-hero-btn--navy"
				id="ame-hero-btn-store">
				<span class="ame-hero-btn__text"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>
		</div>

		<!-- Progress bar + counter (hidden if single slide) -->
		<?php if ( $slide_count > 1 ) : ?>
		<div class="ame-hero__progress-wrap" id="ame-hero-progress-wrap">
			<div class="ame-hero__progress-track" aria-hidden="true">
				<div class="ame-hero__progress-fill" id="ame-hero-progress-fill"></div>
			</div>
			<span class="ame-hero__counter" id="ame-hero-counter" aria-live="polite">
				01&thinsp;/&thinsp;<?php echo str_pad( $slide_count, 2, '0', STR_PAD_LEFT ); ?>
			</span>
		</div>
		<?php endif; ?>

		<!-- Scroll cue -->
		<div class="ame-hero__scroll-cue" aria-hidden="true">
			<div class="ame-hero__scroll-line"></div>
		</div>

	</div><!-- /.ame-hero__editorial -->

</section><!-- /#ame-hero -->

<?php
// ── Pass collection data to JS ─────────────────────────────────────────────
$js_collections = array();
foreach ( $slides as $i => $s ) {
	$js_collections[] = array(
		'index'      => $i,
		'season'     => $s['season'],
		'label'      => $s['label'],
		'headline_l1'=> $s['headline_l1'],
		'headline_l2'=> $s['headline_l2'],
		'subline'    => $s['subline'],
	);
}
?>
<script id="ame-hero-data">
/* AME Hero config — server-generated, never hardcoded */
window.AME_HERO = {
	collections:   <?php echo wp_json_encode( $js_collections ); ?>,
	slideCount:    <?php echo $slide_count; ?>,
	holdDuration:  10,   /* seconds per slide — longer = more cinematic */
	veilDuration:  0.9,  /* seconds for luminance veil rise */
	veilPeak:      1.1,  /* seconds veil hold at peak before recede */
	veilRecede:    1.2,  /* seconds veil fade out */
	kenBurnsDur:   13,   /* seconds for Ken-Burns zoom cycle */
	parallaxDepth: 14,   /* px max for mouse parallax on editorial */
	shopUrl:       <?php echo wp_json_encode( esc_url( $shop_url ) ); ?>,
};
</script>

<script id="ame-hero-engine">
/* =============================================================================
   AME Bazaar — Cinematic Living Hero Engine
   Technology: GSAP 3 — opacity + transform ONLY (zero WebGL, zero canvas)
   Technique:  Luminance veil disguises image swap as sunlight shifting
   Micro-motion: Ken-Burns, depth breathe, light shimmer, bokeh, parallax
   PHP safety: inline script, no require, no external deps beyond GSAP CDN
============================================================================= */
(function () {
	'use strict';

	/* ── Guard: GSAP must be loaded ── */
	if ( typeof gsap === 'undefined' ) {
		var ed = document.getElementById('ame-hero-editorial');
		if ( ed ) ed.style.opacity = '1';
		return;
	}

	var cfg   = window.AME_HERO || {};
	var cols  = cfg.collections  || [];
	var total = cfg.slideCount   || 0;
	var hold  = cfg.holdDuration || 10;
	var vRise = cfg.veilDuration || 0.9;
	var vPeak = cfg.veilPeak     || 1.1;
	var vFade = cfg.veilRecede   || 1.2;
	var kbDur = cfg.kenBurnsDur  || 13;
	var pxD   = cfg.parallaxDepth|| 14;

	/* ── DOM references ── */
	var hero      = document.getElementById('ame-hero');
	var stage     = document.getElementById('ame-hero-stage');
	var editorial = document.getElementById('ame-hero-editorial');
	var slides    = document.querySelectorAll('.ame-hero__slide');
	var veil      = document.getElementById('ame-hero-veil');
	var shimmer   = document.getElementById('ame-hero-shimmer');
	var bokehWrap = document.getElementById('ame-hero-bokeh');
	var eyebrow   = document.getElementById('ame-hero-eyebrow');
	var labelEl   = document.getElementById('ame-hero-label');
	var hl1       = document.getElementById('ame-hero-hl1');
	var hl2       = document.getElementById('ame-hero-hl2');
	var subEl     = document.getElementById('ame-hero-sub');
	var actions   = document.getElementById('ame-hero-actions');
	var progFill  = document.getElementById('ame-hero-progress-fill');
	var counter   = document.getElementById('ame-hero-counter');
	var progWrap  = document.getElementById('ame-hero-progress-wrap');
	var scrollCue = document.querySelector('.ame-hero__scroll-cue');

	if ( !hero || !editorial ) return;

	/* ── State ── */
	var current   = 0;
	var loopTimer = null;
	var progTween = null;

	/* ── Utility: pad number ── */
	function pad( n ) {
		return n < 10 ? '0' + n : '' + n;
	}

	/* ══════════════════════════════════════════════
	   1. INITIAL STATES
	══════════════════════════════════════════════ */
	gsap.set( editorial, { opacity: 0 } );
	gsap.set( slides, { opacity: 0 } );
	if ( slides[0] ) gsap.set( slides[0], { opacity: 1 } );
	if ( veil ) gsap.set( veil, { opacity: 0 } );
	if ( shimmer ) gsap.set( shimmer, { opacity: 0 } );

	/* Ken-Burns initial state: first image starts zoomed in */
	var firstImg = slides[0] ? slides[0].querySelector('.ame-hero__image, .ame-hero__video') : null;
	if ( firstImg ) gsap.set( firstImg, { scale: 1.06, transformOrigin: '55% 50%' } );

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
		)
		.fromTo( hl2,
			{ opacity: 0, y: 50, skewX: -2 },
			{ opacity: 1, y: 0, skewX: 0, duration: 1.1, ease: 'power3.out' },
			'-=0.75'
		)
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

	if ( progWrap ) {
		entranceTL.fromTo( progWrap,
			{ opacity: 0 },
			{ opacity: 1, duration: 0.6, ease: 'power1.out' },
			'-=0.3'
		);
	}

	if ( scrollCue ) {
		gsap.fromTo( scrollCue,
			{ opacity: 0 },
			{ opacity: 1, duration: 1.2, delay: 2.8, ease: 'power1.out' }
		);
	}

	/* ══════════════════════════════════════════════
	   3. KEN-BURNS — first slide starts immediately
	══════════════════════════════════════════════ */
	function startKenBurns( imgEl ) {
		if ( !imgEl ) return;
		gsap.fromTo( imgEl,
			{ scale: 1.06, transformOrigin: '55% 50%' },
			{ scale: 1.0,  duration: kbDur, ease: 'power1.out' }
		);
	}
	if ( firstImg ) startKenBurns( firstImg );

	/* ══════════════════════════════════════════════
	   4. DEPTH BREATHE — perpetual subtle scale pulse
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
	   5. LIGHT SHIMMER — warm sunbeam drift
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
	   6. BOKEH PARTICLES — floating dust motes
	══════════════════════════════════════════════ */
	(function () {
		if ( !bokehWrap ) return;
		var N = 10;
		for ( var p = 0; p < N; p++ ) {
			var dot = document.createElement('span');
			dot.className = 'ame-hero__particle';
			var sz = Math.random() * 3 + 2; /* 2–5px */
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
	   7. PROGRESS BAR
	══════════════════════════════════════════════ */
	function startProgress() {
		if ( !progFill || total <= 1 ) return;
		if ( progTween ) progTween.kill();
		gsap.set( progFill, { scaleX: 0, transformOrigin: 'left center' } );
		progTween = gsap.to( progFill, {
			scaleX: 1,
			duration: hold,
			ease: 'none'
		} );
	}

	/* ══════════════════════════════════════════════
	   8. UPDATE TEXT (label, headline, subline)
	══════════════════════════════════════════════ */
	function swapText( colData ) {
		/* Fade out */
		var outTL = gsap.timeline();
		outTL
			.to( labelEl, { opacity: 0, y: -7, duration: 0.3, ease: 'power2.in' }, 0 )
			.to( hl1,     { opacity: 0, y: -12, duration: 0.35, ease: 'power2.in' }, 0.05 )
			.to( hl2,     { opacity: 0, y: -14, duration: 0.35, ease: 'power2.in' }, 0.1 )
			.to( subEl,   { opacity: 0, y: -8, duration: 0.3, ease: 'power2.in' }, 0.05 )
			.call( function () {
				/* Update DOM while invisible */
				if ( labelEl ) labelEl.textContent = colData.label;
				if ( hl1 )     hl1.textContent     = colData.headline_l1;
				if ( hl2 )     hl2.textContent      = colData.headline_l2;
				if ( subEl )   subEl.textContent    = colData.subline;
			} )
			/* Fade in */
			.fromTo( labelEl, { opacity: 0, y: 7 },  { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' } )
			.fromTo( hl1,     { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.35' )
			.fromTo( hl2,     { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.45' )
			.fromTo( subEl,   { opacity: 0, y: 10 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' }, '-=0.35' );
	}

	/* ══════════════════════════════════════════════
	   9. UPDATE COUNTER
	══════════════════════════════════════════════ */
	function updateCounter( idx ) {
		if ( counter ) {
			counter.textContent = pad( idx + 1 ) + '\u202f/\u202f' + pad( total );
		}
	}

	/* ══════════════════════════════════════════════
	   10. LUMINANCE VEIL TRANSITION (the wardrobe magic)
	══════════════════════════════════════════════ */
	function goToSlide( next ) {
		if ( total <= 1 ) return;
		next = ( ( next % total ) + total ) % total;
		if ( next === current ) return;

		var prevSlide = slides[ current ];
		var nextSlide = slides[ next ];
		var nextImg   = nextSlide ? nextSlide.querySelector('.ame-hero__image, .ame-hero__video') : null;
		var nextCol   = cols[ next ] || {};

		/* ── Veil rises: warm sunlight floods the room ── */
		var veilTL = gsap.timeline();
		veilTL
			/* Veil rise */
			.to( veil, { opacity: 0.82, duration: vRise, ease: 'power3.in' }, 0 )
			/* At 55% of rise: silently swap the image underneath */
			.call( function () {
				/* Prepare incoming */
				gsap.set( nextSlide, { opacity: 1 } );
				if ( nextImg ) gsap.set( nextImg, { scale: 1.06, transformOrigin: '55% 50%' } );
				/* Hide outgoing */
				gsap.set( prevSlide, { opacity: 0 } );
				/* Update aria */
				if ( prevSlide ) prevSlide.setAttribute( 'aria-hidden', 'true' );
				if ( nextSlide ) nextSlide.setAttribute( 'aria-hidden', 'false' );
				/* Swap text while veil covers everything */
				swapText( nextCol );
				/* Update counter */
				updateCounter( next );
			}, null, vRise * 0.55 )
			/* Veil peak hold */
			.to( {}, { duration: vPeak - vRise * 0.55 } )
			/* Veil recedes: light pulls back, new collection revealed */
			.to( veil, { opacity: 0, duration: vFade, ease: 'power2.out' } )
			/* Ken-Burns on incoming image starts */
			.call( function () {
				startKenBurns( nextImg );
			}, null, '-=' + vFade );

		/* Update state */
		current = next;

		/* Restart progress */
		startProgress();
	}

	/* ══════════════════════════════════════════════
	   11. AUTO-ADVANCE LOOP
	══════════════════════════════════════════════ */
	function scheduleNext() {
		clearTimeout( loopTimer );
		if ( total > 1 ) {
			loopTimer = setTimeout( function () {
				goToSlide( current + 1 );
				scheduleNext();
			}, hold * 1000 );
		}
	}

	/* Start loop after entrance completes */
	entranceTL.call( function () {
		startProgress();
		scheduleNext();
	} );

	/* ══════════════════════════════════════════════
	   12. MOUSE PARALLAX — editorial panel only
	══════════════════════════════════════════════ */
	if ( window.innerWidth > 900 && hero ) {
		var rafId = null;
		var mx = 0, my = 0;

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
	   13. SCROLL PARALLAX — subtle image depth
	══════════════════════════════════════════════ */
	window.addEventListener( 'scroll', function () {
		var sy = window.pageYOffset;
		if ( sy > window.innerHeight ) return;
		var activeImg = slides[ current ] ? slides[ current].querySelector('.ame-hero__image, .ame-hero__video') : null;
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
	   14. SCROLL CUE — subtle bounce loop
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
