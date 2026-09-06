<?php
/**
 * Live Social Media Feed Section.
 *
 * Renders the official public Facebook Page timeline and Instagram profile
 * embeds side-by-side so new social posts can appear without copying content
 * into WordPress.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$facebook_url  = 'https://www.facebook.com/AMETTBAZAAR';
$instagram_url = 'https://www.instagram.com/ame_bazaar/';
?>

<section class="ame-social-feed-section" aria-labelledby="ame-social-feed-title">
	<div class="ame-bazaar-container">
		<div class="ame-social-feed-header">
			<span class="ame-social-feed-kicker">Follow AME Bazaar</span>
			<h2 id="ame-social-feed-title" class="ame-h2">See What's New at AME Bazaar</h2>
			<p class="ame-body">
				Our latest fashion updates, new arrivals, festive looks and showroom moments — directly from our Facebook and Instagram pages.
			</p>
		</div>

		<div class="ame-social-feed-grid">
			<article class="ame-social-feed-card ame-social-feed-card-facebook">
				<div class="ame-social-feed-card-head">
					<div class="ame-social-feed-brand">
						<span class="ame-social-feed-icon ame-social-feed-icon-facebook" aria-hidden="true">f</span>
						<div>
							<h3>AME Bazaar – Kirari Delhi</h3>
							<span>Facebook</span>
						</div>
					</div>
					<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-social-feed-follow ame-social-feed-follow-facebook">Follow Page</a>
				</div>

				<div class="ame-social-feed-embed ame-social-feed-embed-facebook">
					<iframe
						title="AME Bazaar Facebook Page timeline"
						src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FAMETTBAZAAR&tabs=timeline&width=500&height=720&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&show_posts=true"
						width="500"
						height="720"
						style="border:none;overflow:hidden;max-width:100%;"
						scrolling="no"
						frameborder="0"
						allowfullscreen="true"
						allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
					></iframe>
				</div>
			</article>

			<article class="ame-social-feed-card ame-social-feed-card-instagram">
				<div class="ame-social-feed-card-head">
					<div class="ame-social-feed-brand">
						<span class="ame-social-feed-icon ame-social-feed-icon-instagram" aria-hidden="true">◎</span>
						<div>
							<h3>ame_bazaar</h3>
							<span>Instagram</span>
						</div>
					</div>
					<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-social-feed-follow ame-social-feed-follow-instagram">Follow</a>
				</div>

				<div class="ame-social-feed-embed ame-social-feed-embed-instagram">
					<blockquote
						class="instagram-media"
						data-instgrm-permalink="<?php echo esc_url( $instagram_url ); ?>"
						data-instgrm-version="14"
						style="background:#FFF;border:0;border-radius:12px;box-shadow:none;margin:0;max-width:540px;min-width:326px;padding:0;width:calc(100% - 2px);"
					>
						<div style="padding:16px;">
							<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">View @ame_bazaar on Instagram</a>
						</div>
					</blockquote>
				</div>
			</article>
		</div>
	</div>
</section>

<style>
.ame-social-feed-section {
	padding-block: 5.5rem;
	background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.ame-social-feed-header {
	max-width: 760px;
	margin: 0 auto 2.75rem;
	text-align: center;
}

.ame-social-feed-kicker {
	display: inline-block;
	margin-bottom: .55rem;
	font-size: .76rem;
	font-weight: 800;
	letter-spacing: .16em;
	text-transform: uppercase;
	color: var(--ame-color-gold-dark);
}

.ame-social-feed-header .ame-h2 {
	margin-bottom: .65rem;
	font-weight: 800;
	color: var(--ame-color-navy);
}

.ame-social-feed-header .ame-body {
	max-width: 680px;
	margin: 0 auto;
	color: var(--ame-color-slate);
}

.ame-social-feed-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 1.5rem;
	align-items: start;
}

.ame-social-feed-card {
	min-width: 0;
	overflow: hidden;
	border: 1px solid rgba(15, 23, 42, .08);
	border-radius: 20px;
	background: #fff;
	box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
}

.ame-social-feed-card-head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 1rem;
	padding: 1rem 1.05rem;
	border-bottom: 1px solid rgba(15, 23, 42, .07);
}

.ame-social-feed-brand {
	display: flex;
	align-items: center;
	gap: .75rem;
	min-width: 0;
}

.ame-social-feed-brand h3 {
	margin: 0;
	font-size: .98rem;
	font-weight: 800;
	line-height: 1.2;
	color: var(--ame-color-navy);
}

.ame-social-feed-brand span:not(.ame-social-feed-icon) {
	display: block;
	margin-top: .2rem;
	font-size: .76rem;
	color: var(--ame-color-slate);
}

.ame-social-feed-icon {
	display: grid;
	width: 38px;
	height: 38px;
	flex: 0 0 38px;
	place-items: center;
	border-radius: 11px;
	font-weight: 900;
	font-size: 1.25rem;
	color: #fff;
}

.ame-social-feed-icon-facebook {
	background: #1877f2;
	font-family: Arial, sans-serif;
}

.ame-social-feed-icon-instagram {
	background: linear-gradient(135deg, #f58529, #dd2a7b 55%, #8134af 82%, #515bd4);
	font-size: 1.55rem;
}

.ame-social-feed-follow {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 34px;
	padding: 0 .85rem;
	border-radius: 999px;
	font-size: .78rem;
	font-weight: 800;
	text-decoration: none !important;
	white-space: nowrap;
}

.ame-social-feed-follow-facebook {
	background: #1877f2;
	color: #fff !important;
}

.ame-social-feed-follow-instagram {
	background: #111827;
	color: #fff !important;
}

.ame-social-feed-embed {
	width: 100%;
	min-height: 680px;
	background: #fff;
}

.ame-social-feed-embed-facebook {
	display: flex;
	justify-content: center;
	overflow: hidden;
	padding: .25rem 0 0;
}

.ame-social-feed-embed-instagram {
	display: flex;
	justify-content: center;
	padding: .25rem 0 1rem;
	overflow: hidden;
}

.ame-social-feed-embed-instagram .instagram-media {
	margin-left: auto !important;
	margin-right: auto !important;
}

@media (max-width: 900px) {
	.ame-social-feed-grid {
		grid-template-columns: 1fr;
		max-width: 620px;
		margin-inline: auto;
	}
}

@media (max-width: 560px) {
	.ame-social-feed-section {
		padding-block: 4rem;
	}

	.ame-social-feed-card-head {
		padding: .85rem;
	}

	.ame-social-feed-icon {
		width: 34px;
		height: 34px;
		flex-basis: 34px;
	}

	.ame-social-feed-brand h3 {
		font-size: .9rem;
	}

	.ame-social-feed-follow {
		min-height: 32px;
		padding-inline: .7rem;
		font-size: .72rem;
	}

	.ame-social-feed-embed {
		min-height: 640px;
	}
}
</style>

<script>
(function () {
	function loadInstagramEmbed() {
		if (window.instgrm && window.instgrm.Embeds) {
			window.instgrm.Embeds.process();
			return;
		}

		if (document.querySelector('script[data-ame-instagram-embed]')) {
			return;
		}

		var script = document.createElement('script');
		script.async = true;
		script.src = 'https://www.instagram.com/embed.js';
		script.dataset.ameInstagramEmbed = '1';
		document.body.appendChild(script);
	} 

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', loadInstagramEmbed, { once: true });
	} else {
		loadInstagramEmbed();
	}
})();
</script>
