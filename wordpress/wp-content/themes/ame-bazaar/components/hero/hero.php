<?php
/**
 * AME Bazaar — Living Fashion Hero
 * "One Family. Many Collections."
 *
 * Engine  : Three.js r134 + GSAP 3.12
 * Technique: WebGL displacement-morph between collection shots.
 *            The same family stays in frame — only the fashion evolves.
 *            A custom GLSL shader warps pixel positions using a displacement
 *            map so the transition looks like fabric literally morphing.
 *
 * Media   : 100% Media Manager — zero hardcoded URLs.
 * Fallback: CSS-only branded void when images not set.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Media Manager Image IDs ───────────────────────────────────────────────────
$collection_data = array(
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_desktop' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_mobile' ),
		'label'      => __( 'Summer Collection', 'ame-bazaar' ),
		'season'     => 'summer',
		'tagline'    => __( 'Light. Breathable. Alive.', 'ame-bazaar' ),
	),
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_festive' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_festive_mobile' ),
		'label'      => __( 'Festive Collection', 'ame-bazaar' ),
		'season'     => 'festive',
		'tagline'    => __( 'Celebrate every thread.', 'ame-bazaar' ),
	),
	array(
		'desktop_id' => (int) get_option( 'ame_bazaar_media_hero_winter' ),
		'mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_winter_mobile' ),
		'label'      => __( 'Winter Collection', 'ame-bazaar' ),
		'season'     => 'winter',
		'tagline'    => __( 'Warmth, refined.', 'ame-bazaar' ),
	),
);

// Filter to only collections with an image uploaded
$collections = array_values( array_filter( $collection_data, fn( $c ) => $c['desktop_id'] > 0 ) );

// Resolve full URLs (server-side, WordPress srcset intelligence)
$js_collections = array();
foreach ( $collections as $c ) {
	$desk_url = wp_get_attachment_image_url( $c['desktop_id'], 'full' );
	$mob_id   = $c['mobile_id'] > 0 ? $c['mobile_id'] : $c['desktop_id'];
	$mob_url  = wp_get_attachment_image_url( $mob_id, 'full' );
	$js_collections[] = array(
		'season'  => $c['season'],
		'label'   => $c['label'],
		'tagline' => $c['tagline'],
		'desktop' => $desk_url ?: '',
		'mobile'  => $mob_url  ?: $desk_url ?: '',
	);
}

$has_images = ! empty( $js_collections );
$first      = $has_images ? $js_collections[0] : null;

// Business CTAs
$shop_url = home_url( '/shop/' );
$maps_url = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
?>

<section
	class="ame-hero<?php echo $has_images ? '' : ' ame-hero--void'; ?>"
	id="ame-hero"
	aria-label="<?php esc_attr_e( 'AME Bazaar — One Family, Many Collections', 'ame-bazaar' ); ?>"
>

	<!-- ═══ WEBGL MORPH CANVAS — The living fashion engine ═══ -->
	<canvas
		class="ame-hero__gl-canvas"
		id="ame-hero-gl"
		aria-hidden="true"
	></canvas>

	<!-- SEO/accessibility hidden image (crawlers + screen readers) -->
	<?php if ( $has_images ) : ?>
	<img
		class="ame-hero__seo-img"
		src="<?php echo esc_url( $first['desktop'] ); ?>"
		alt="<?php echo esc_attr( $first['label'] ) . ' — ' . esc_attr__( 'AME Bazaar Premium Family Fashion', 'ame-bazaar' ); ?>"
		loading="eager"
		fetchpriority="high"
		decoding="async"
	>
	<?php endif; ?>

	<!-- Cinematic depth overlay -->
	<div class="ame-hero__overlay" aria-hidden="true"></div>

	<!-- ═══ EDITORIAL COMPOSITION ═══ -->
	<div class="ame-hero__editorial" id="ame-hero-editorial">

		<!-- Season eyebrow label -->
		<div class="ame-hero__eyebrow" id="ame-hero-eyebrow">
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
			<span class="ame-hero__eyebrow-label" id="ame-hero-season-label">
				<?php echo esc_html( $has_images ? $first['label'] : __( 'Premium Fashion', 'ame-bazaar' ) ); ?>
			</span>
			<span class="ame-hero__eyebrow-rule" aria-hidden="true"></span>
		</div>

		<!-- Hero headline -->
		<h1 class="ame-hero__title" id="ame-hero-title">
			<span class="ame-hero__title-l1" id="ame-hero-line1"><?php esc_html_e( 'One Family.', 'ame-bazaar' ); ?></span>
			<span class="ame-hero__title-l2 ame-hero__title-l2--italic" id="ame-hero-line2"><?php esc_html_e( 'Every Season.', 'ame-bazaar' ); ?></span>
		</h1>

		<!-- Season tagline -->
		<p class="ame-hero__sub" id="ame-hero-sub">
			<?php echo esc_html( $has_images ? $first['tagline'] : __( 'Premium fashion for every occasion — Men, Women & Kids', 'ame-bazaar' ) ); ?>
		</p>

		<!-- Business CTAs -->
		<div class="ame-hero__actions" id="ame-hero-actions">

			<a href="<?php echo esc_url( $shop_url ); ?>"
				class="ame-hero-btn ame-hero-btn--glass"
				id="ame-hero-btn-shop"
			>
				<span class="ame-hero-btn__label"><?php esc_html_e( 'Shop Collection', 'ame-bazaar' ); ?></span>
				<svg class="ame-hero-btn__arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
					<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
				</svg>
			</a>

			<a href="<?php echo esc_url( $maps_url ); ?>"
				target="_blank" rel="noopener noreferrer"
				class="ame-hero-btn ame-hero-btn--navy"
				id="ame-hero-btn-store"
			>
				<span class="ame-hero-btn__label"><?php esc_html_e( 'Visit Store', 'ame-bazaar' ); ?></span>
			</a>

		</div>

		<!-- Progress indicator (thin bar + counter) -->
		<?php if ( count( $js_collections ) > 1 ) : ?>
		<div class="ame-hero__progress" id="ame-hero-progress" aria-hidden="true">
			<div class="ame-hero__progress-track">
				<div class="ame-hero__progress-fill" id="ame-hero-progress-fill"></div>
			</div>
			<span class="ame-hero__progress-count" id="ame-hero-progress-count">
				01 / <?php echo str_pad( count( $js_collections ), 2, '0', STR_PAD_LEFT ); ?>
			</span>
		</div>
		<?php endif; ?>

		<!-- Scroll cue -->
		<div class="ame-hero__scroll-cue" aria-hidden="true">
			<div class="ame-hero__scroll-line"></div>
		</div>

	</div>

</section>

<?php
// Pass all collection data to JavaScript — zero hardcoded strings in JS
?>

<script id="ame-hero-data">
window.AME_HERO = {
	collections:    <?php echo wp_json_encode( $js_collections ); ?>,
	count:          <?php echo count( $js_collections ); ?>,
	holdDuration:   7.5,
	morphDuration:  2.0,
	morphIntensity: 0.18,
	breatheAmp:     0.010,
	breatheSpd:     0.003,
	parallaxAmt:    0.012,
	isMobile:       false,
};
</script>
<script id="ame-hero-engine">
/*
 * AME Bazaar — "One Family. Every Season." — WebGL Displacement Morph Engine
 *
 * TECHNIQUE : Three.js r134 orthographic plane + custom GLSL fragment shader.
 *             A displacement map warps two image textures in opposite directions.
 *             At the midpoint of the morph, both are at peak distortion —
 *             creating a liquid fabric-morph rather than a crossfade or a slide.
 *
 * LAYERS    : 1. WebGL canvas (Three.js fullscreen plane, GPU only)
 *             2. Breathing animation (GLSL sin-wave, zero CPU cost)
 *             3. Mouse camera dolly (smooth lerp → shader uniform)
 *             4. Scroll depth parallax (GSAP on editorial only)
 *             5. Editorial entrance + tagline swap (GSAP timeline)
 *
 * PHILOSOPHY: Real photography. Living motion. Zero particles. Zero CSS tricks.
 */
(function () {
	'use strict';

	var cfg      = window.AME_HERO || {};
	var hasGL    = (function(){ try{ var c=document.createElement('canvas'); return !!(c.getContext('webgl')||c.getContext('experimental-webgl')); }catch(e){return false;} })();
	var hasGSAP  = typeof gsap !== 'undefined';
	var hasThree = typeof THREE !== 'undefined';

	var editorial   = document.getElementById('ame-hero-editorial');
	var eyebrow     = document.getElementById('ame-hero-eyebrow');
	var line1       = document.getElementById('ame-hero-line1');
	var line2       = document.getElementById('ame-hero-line2');
	var sub         = document.getElementById('ame-hero-sub');
	var actions     = document.getElementById('ame-hero-actions');
	var seasonLabel = document.getElementById('ame-hero-season-label');
	var scrollCue   = document.querySelector('.ame-hero__scroll-cue');
	var pFill       = document.getElementById('ame-hero-progress-fill');
	var pCount      = document.getElementById('ame-hero-progress-count');
	var glCanvas    = document.getElementById('ame-hero-gl');

	/* Always make editorial visible as the baseline */
	if (editorial) editorial.style.opacity = '1';

	/* ═══════════════════════════════════════════════════════════════════
	   PART 1 — EDITORIAL ENTRANCE (GSAP)
	   Always runs; independent of WebGL availability.
	   ═══════════════════════════════════════════════════════════════════ */
	if (hasGSAP && editorial) {
		gsap.set(editorial, { opacity: 0 });
		var tl = gsap.timeline({ delay: 0.35 });
		tl
			.set(editorial, { opacity: 1 })
			.fromTo(eyebrow,
				{ opacity: 0, y: 20 },
				{ opacity: 1, y: 0, duration: 1.0, ease: 'power3.out' }
			)
			.fromTo(line1,
				{ opacity: 0, y: 44, skewX: -3 },
				{ opacity: 1, y: 0, skewX: 0, duration: 1.2, ease: 'power3.out' },
				'-=0.5'
			)
			.fromTo(line2,
				{ opacity: 0, y: 52, skewX: -3 },
				{ opacity: 1, y: 0, skewX: 0, duration: 1.2, ease: 'power3.out' },
				'-=0.8'
			)
			.fromTo(sub,
				{ opacity: 0, y: 24 },
				{ opacity: 1, y: 0, duration: 0.9, ease: 'power2.out' },
				'-=0.6'
			)
			.fromTo(actions,
				{ opacity: 0, y: 18 },
				{ opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
				'-=0.5'
			);

		if (scrollCue) {
			gsap.fromTo(scrollCue,
				{ opacity: 0 },
				{ opacity: 1, duration: 1.2, delay: 3.0, ease: 'power1.out' }
			);
		}
	}

	/* Abort if no WebGL / no Three.js / no images */
	if (!hasGL || !hasThree || !glCanvas || !cfg.collections || cfg.count < 1) { return; }

	/* ═══════════════════════════════════════════════════════════════════
	   PART 2 — WebGL DISPLACEMENT MORPH ENGINE
	   ═══════════════════════════════════════════════════════════════════ */
	cfg.isMobile = window.innerWidth < 768;
	var W = window.innerWidth;
	var H = window.innerHeight;

	/* ── Renderer ── */
	var renderer = new THREE.WebGLRenderer({
		canvas:          glCanvas,
		antialias:       false,
		alpha:           false,
		powerPreference: 'high-performance',
	});
	renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
	renderer.setSize(W, H);

	/* ── Orthographic camera (fullscreen quad) ── */
	var camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
	var scene  = new THREE.Scene();
	var geom   = new THREE.PlaneGeometry(2, 2);

	/* ─────────────────────────────────────────────────────────────────
	   GLSL — Vertex shader (simple UV pass-through)
	   ───────────────────────────────────────────────────────────────── */
	var vert = [
		'varying vec2 vUv;',
		'void main() {',
		'  vUv = uv;',
		'  gl_Position = vec4(position, 1.0);',
		'}'
	].join('\n');

	/* ─────────────────────────────────────────────────────────────────
	   GLSL — Fragment shader: displacement-morph
	   The displacement map warps both textures in opposing directions.
	   At uProgress 0.5 both images are at peak warp — the "peak fabric"
	   moment. smoothstep blends across this warp for a liquid dissolve.
	   ───────────────────────────────────────────────────────────────── */
	var frag = [
		'precision highp float;',
		'uniform sampler2D uTexA;',
		'uniform sampler2D uTexB;',
		'uniform sampler2D uDisp;',
		'uniform float uProg;',
		'uniform float uInt;',
		'uniform float uTime;',
		'uniform float uBreath;',
		'uniform float uBreathSpd;',
		'uniform vec2  uMouse;',
		'varying vec2 vUv;',
		'void main() {',
		'  vec2 uv = vUv;',
		/* Breathing: gentle scale pulse */
		'  float b = sin(uTime * uBreathSpd * 6.2832) * uBreath;',
		'  uv = (uv - 0.5) * (1.0 - b) + 0.5;',
		/* Mouse dolly */
		'  uv -= uMouse * 0.012;',
		/* Read displacement map */
		'  float d = texture2D(uDisp, uv).r;',
		/* Opposing warps */
		'  vec2 offA = vec2(d * uInt *  uProg,        d * uInt *  uProg * 0.55);',
		'  vec2 offB = vec2(d * uInt * (1.0 - uProg), d * uInt * (1.0 - uProg) * 0.55);',
		'  vec4 colA = texture2D(uTexA, clamp(uv + offA, 0.001, 0.999));',
		'  vec4 colB = texture2D(uTexB, clamp(uv - offB, 0.001, 0.999));',
		'  float blend = smoothstep(0.0, 1.0, uProg);',
		'  gl_FragColor = mix(colA, colB, blend);',
		'}'
	].join('\n');

	/* ── Procedural displacement texture — no external file needed ── */
	function makeDispTex() {
		var sz = 512;
		var c = document.createElement('canvas');
		c.width = c.height = sz;
		var ctx = c.getContext('2d');
		ctx.fillStyle = '#1a1a1a';
		ctx.fillRect(0, 0, sz, sz);
		var pairs = [
			[100, 150, 260, 0.75], [370, 90,  210, 0.70],
			[260, 360, 230, 0.72], [55,  390, 190, 0.65],
			[440, 290, 250, 0.78], [190, 480, 200, 0.68],
			[330, 200, 170, 0.60], [80,  220, 150, 0.55],
		];
		pairs.forEach(function(p) {
			var g = ctx.createRadialGradient(p[0], p[1], 0, p[0], p[1], p[2]);
			g.addColorStop(0,   'rgba(255,255,255,' + p[3] + ')');
			g.addColorStop(0.5, 'rgba(150,150,150,' + (p[3]*0.35) + ')');
			g.addColorStop(1,   'rgba(0,0,0,0)');
			ctx.globalCompositeOperation = 'screen';
			ctx.fillStyle = g;
			ctx.beginPath();
			ctx.arc(p[0], p[1], p[2], 0, Math.PI*2);
			ctx.fill();
		});
		/* Fine grain */
		var id = ctx.getImageData(0, 0, sz, sz);
		var px = id.data;
		for (var i = 0; i < px.length; i += 4) {
			var n = (Math.random() - 0.5) * 28;
			px[i] = px[i+1] = px[i+2] = Math.min(255, Math.max(0, px[i] + n));
		}
		ctx.putImageData(id, 0, 0);
		var t = new THREE.CanvasTexture(c);
		t.minFilter = THREE.LinearFilter;
		t.needsUpdate = true;
		return t;
	}

	/* ── Uniforms ── */
	var uni = {
		uTexA:      { value: null },
		uTexB:      { value: null },
		uDisp:      { value: makeDispTex() },
		uProg:      { value: 0.0  },
		uInt:       { value: cfg.morphIntensity || 0.18 },
		uTime:      { value: 0.0  },
		uBreath:    { value: cfg.breatheAmp  || 0.010 },
		uBreathSpd: { value: cfg.breatheSpd  || 0.003 },
		uMouse:     { value: new THREE.Vector2(0, 0) },
	};

	var mat  = new THREE.ShaderMaterial({ vertexShader: vert, fragmentShader: frag, uniforms: uni });
	var mesh = new THREE.Mesh(geom, mat);
	scene.add(mesh);

	/* ── Texture loader ── */
	var loader   = new THREE.TextureLoader();
	loader.crossOrigin = 'anonymous';
	var textures = new Array(cfg.count).fill(null);
	var loaded   = 0;

	cfg.collections.forEach(function(col, i) {
		var url = (cfg.isMobile && col.mobile) ? col.mobile : col.desktop;
		if (!url) { loaded++; if (loaded === cfg.count) start(); return; }
		loader.load(url,
			function(t) {
				t.minFilter = THREE.LinearFilter;
				t.magFilter = THREE.LinearFilter;
				t.generateMipmaps = false;
				textures[i] = t;
				loaded++;
				if (loaded === cfg.count) start();
			},
			undefined,
			function() { loaded++; if (loaded === cfg.count) start(); }
		);
	});

	/* ─────────────────────────────────────────────────────────────────
	   STATE MACHINE
	   ───────────────────────────────────────────────────────────────── */
	var current   = 0;
	var morphing  = false;
	var holdTimer = null;
	var clock     = new THREE.Clock();
	var mX = 0, mY = 0, tX = 0, tY = 0;

	function start() {
		/* First frame: both uniforms point to collection 0 */
		uni.uTexA.value = textures[0] || null;
		uni.uTexB.value = textures[0] || null;
		uni.uProg.value = 0.0;

		/* Ken-Burns open: breathe amplitude starts high then settles */
		if (hasGSAP) {
			gsap.fromTo(uni.uBreath,
				{ value: 0.048 },
				{ value: cfg.breatheAmp || 0.010, duration: 10, ease: 'power1.out' }
			);
		}

		startHold();
		renderer.setAnimationLoop(render);
	}

	function morphTo(next) {
		if (morphing) return;
		next = ((next % cfg.count) + cfg.count) % cfg.count;
		var nTex = textures[next];
		if (!nTex) { current = next; startHold(); return; }

		morphing = true;
		uni.uTexB.value = nTex;
		uni.uProg.value = 0.0;

		var dur = cfg.morphDuration || 2.0;

		if (hasGSAP) {
			/* Swap editorial text halfway through morph */
			setTimeout(function() { swapEditorial(next); }, dur * 500);

			gsap.to(uni.uProg, {
				value: 1.0,
				duration: dur,
				ease: 'power2.inOut',
				onComplete: function() {
					uni.uTexA.value = nTex;
					uni.uProg.value = 0.0;
					current  = next;
					morphing = false;
					startHold();
				}
			});
		} else {
			/* No GSAP: instant swap */
			uni.uTexA.value = nTex;
			uni.uProg.value = 0.0;
			current  = next;
			morphing = false;
			swapEditorial(next);
			startHold();
		}
	}

	function swapEditorial(idx) {
		var col = cfg.collections[idx];
		if (!col) return;

		if (pCount) {
			pCount.textContent = String(idx + 1).padStart(2,'0') + ' / ' + String(cfg.count).padStart(2,'0');
		}

		function fadeSwap(el, newText) {
			if (!el || !hasGSAP) return;
			gsap.to(el, { opacity: 0, y: -10, duration: 0.3, ease: 'power2.in',
				onComplete: function() {
					el.textContent = newText;
					gsap.fromTo(el, { opacity: 0, y: 10 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' });
				}
			});
		}

		fadeSwap(seasonLabel, col.label);
		fadeSwap(sub,         col.tagline);
	}

	function startHold() {
		clearTimeout(holdTimer);
		if (cfg.count < 2) return;

		/* Animate progress bar */
		if (pFill && hasGSAP) {
			gsap.fromTo(pFill,
				{ scaleX: 0 },
				{ scaleX: 1, duration: cfg.holdDuration || 7.5, ease: 'none',
				  transformOrigin: 'left center' }
			);
		}

		holdTimer = setTimeout(function() {
			morphTo(current + 1);
		}, (cfg.holdDuration || 7.5) * 1000);
	}

	/* ─────────────────────────────────────────────────────────────────
	   RENDER LOOP (requestAnimationFrame via Three.js setAnimationLoop)
	   ───────────────────────────────────────────────────────────────── */
	function render() {
		uni.uTime.value = clock.getElapsedTime();
		/* Smooth mouse lerp */
		mX += (tX - mX) * 0.06;
		mY += (tY - mY) * 0.06;
		uni.uMouse.value.set(mX, mY);
		renderer.render(scene, camera);
	}

	/* ── Events ── */

	/* Mouse parallax — desktop */
	var heroEl = document.getElementById('ame-hero');
	if (heroEl && window.innerWidth > 768) {
		heroEl.addEventListener('mousemove', function(e) {
			var r = heroEl.getBoundingClientRect();
			tX =  (e.clientX - r.left)  / r.width  - 0.5;
			tY = -(e.clientY - r.top)   / r.height + 0.5;
		}, { passive: true });
		heroEl.addEventListener('mouseleave', function() { tX = tY = 0; });
	}

	/* Scroll parallax on editorial */
	if (hasGSAP && editorial) {
		window.addEventListener('scroll', function() {
			var y = window.pageYOffset;
			if (y > window.innerHeight) return;
			gsap.to(editorial, { y: y * 0.14, duration: 0.5, ease: 'none', overwrite: 'auto' });
		}, { passive: true });
	}

	/* Resize */
	window.addEventListener('resize', function() {
		W = window.innerWidth;
		H = window.innerHeight;
		renderer.setSize(W, H);
		cfg.isMobile = W < 768;
	});

})();
</script>
