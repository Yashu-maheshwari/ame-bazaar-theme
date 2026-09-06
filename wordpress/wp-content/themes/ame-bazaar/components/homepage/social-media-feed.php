<?php
/**
 * Live Social Media Feed Section.
 *
 * Uses the existing AME Bazaar Google Apps Script web app as the read-only
 * feed source. Meta credentials remain inside Apps Script; WordPress only
 * receives public post/profile data.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$facebook_url  = 'https://www.facebook.com/AMETTBAZAAR';
$instagram_url = 'https://www.instagram.com/ame_bazaar/';
$gas_feed_url  = 'https://script.google.com/macros/s/AKfycbxoBZ3tVKbFto_3DqrM0qVUd0Nda09cacHmOCi2p_y0bFcUuljQiGzx3sUBpO4RNmNf/exec';
?>

<section class="ame-social-feed-section" aria-labelledby="ame-social-feed-title" data-gas-feed-url="<?php echo esc_url( $gas_feed_url ); ?>">
	<div class="ame-bazaar-container">
		<div class="ame-social-feed-header">
			<span class="ame-social-feed-kicker">Live from social</span>
			<h2 id="ame-social-feed-title" class="ame-h2">AME Bazaar on Facebook &amp; Instagram</h2>
			<p class="ame-body">Real posts from our official pages — new arrivals, festive looks, fashion updates and showroom moments.</p>
		</div>

		<div class="ame-social-feed-grid">
			<article class="ame-social-feed-card ame-social-feed-card-facebook" data-social-card="facebook">
				<div class="ame-social-feed-card-head">
					<div class="ame-social-feed-brand">
						<span class="ame-social-feed-avatar ame-social-feed-avatar-facebook" aria-hidden="true">f</span>
						<div>
							<h3 data-profile-name>AME Bazaar</h3>
							<span>Facebook</span>
						</div>
					</div>
					<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-social-feed-follow ame-social-feed-follow-facebook">Follow Page</a>
				</div>

				<div class="ame-social-feed-profile" data-profile-meta>
					<div class="ame-social-feed-profile-cover ame-social-feed-profile-cover-facebook"></div>
					<div class="ame-social-feed-profile-row">
						<div class="ame-social-feed-profile-logo ame-social-feed-profile-logo-facebook">f</div>
						<div>
							<strong data-profile-title>AME Bazaar</strong>
							<span data-profile-stats>Official Facebook Page</span>
						</div>
					</div>
				</div>

				<div class="ame-social-feed-posts" data-posts>
					<div class="ame-social-feed-loading">Loading latest Facebook posts…</div>
				</div>
			</article>

			<article class="ame-social-feed-card ame-social-feed-card-instagram" data-social-card="instagram">
				<div class="ame-social-feed-card-head">
					<div class="ame-social-feed-brand">
						<span class="ame-social-feed-avatar ame-social-feed-avatar-instagram" aria-hidden="true">◎</span>
						<div>
							<h3 data-profile-name>ame_bazaar</h3>
							<span>Instagram</span>
						</div>
					</div>
					<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="ame-social-feed-follow ame-social-feed-follow-instagram">Follow</a>
				</div>

				<div class="ame-social-feed-instagram-profile" data-profile-meta>
					<div class="ame-social-feed-instagram-main">
						<div class="ame-social-feed-profile-logo ame-social-feed-profile-logo-instagram">◎</div>
						<div class="ame-social-feed-instagram-details">
							<strong data-profile-title>@ame_bazaar</strong>
							<div class="ame-social-feed-instagram-stats" data-profile-stats>
								<span><b data-stat-posts>—</b> posts</span>
								<span><b data-stat-followers>—</b> followers</span>
								<span><b data-stat-following>—</b> following</span>
							</div>
							<p data-profile-bio>AME Bazaar · Family Garment Store</p>
						</div>
					</div>
				</div>

				<div class="ame-social-feed-posts ame-social-feed-instagram-grid" data-posts>
					<div class="ame-social-feed-loading">Loading latest Instagram posts…</div>
				</div>
			</article>
		</div>

		<div class="ame-social-feed-status" data-feed-status aria-live="polite"></div>
	</div>
</section>

<style>
.ame-social-feed-section {
	padding-block: 5.5rem;
	background: linear-gradient(180deg, #f7f8fb 0%, #ffffff 100%);
}

.ame-social-feed-header {
	max-width: 820px;
	margin: 0 auto 2.6rem;
	text-align: center;
}

.ame-social-feed-kicker {
	display: inline-block;
	margin-bottom: .55rem;
	font-size: .74rem;
	font-weight: 900;
	letter-spacing: .16em;
	text-transform: uppercase;
	color: var(--ame-color-gold-dark);
}

.ame-social-feed-header .ame-h2 {
	margin-bottom: .65rem;
	font-weight: 850;
	color: var(--ame-color-navy);
}

.ame-social-feed-header .ame-body {
	max-width: 720px;
	margin: 0 auto;
	color: var(--ame-color-slate);
}

.ame-social-feed-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 1.75rem;
	align-items: start;
}

.ame-social-feed-card {
	min-width: 0;
	overflow: hidden;
	border: 1px solid rgba(15, 23, 42, .09);
	border-radius: 22px;
	background: #fff;
	box-shadow: 0 18px 55px rgba(15, 23, 42, .09);
}

.ame-social-feed-card-head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 1rem;
	padding: 1rem 1.15rem;
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
	font-size: 1rem;
	font-weight: 850;
	line-height: 1.2;
	color: var(--ame-color-navy);
}

.ame-social-feed-brand span:not(.ame-social-feed-avatar) {
	display: block;
	margin-top: .18rem;
	font-size: .76rem;
	color: var(--ame-color-slate);
}

.ame-social-feed-avatar {
	display: grid;
	width: 42px;
	height: 42px;
	flex: 0 0 42px;
	place-items: center;
	border-radius: 13px;
	font-weight: 900;
	color: #fff;
}

.ame-social-feed-avatar-facebook,
.ame-social-feed-profile-logo-facebook {
	background: #1877f2;
}

.ame-social-feed-avatar-facebook {
	font-family: Arial, sans-serif;
	font-size: 1.55rem;
}

.ame-social-feed-avatar-instagram,
.ame-social-feed-profile-logo-instagram {
	background: linear-gradient(135deg, #f58529, #dd2a7b 52%, #8134af 82%, #515bd4);
}

.ame-social-feed-avatar-instagram {
	font-size: 1.7rem;
}

.ame-social-feed-follow {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 35px;
	padding: 0 .9rem;
	border-radius: 999px;
	font-size: .78rem;
	font-weight: 850;
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

.ame-social-feed-profile {
	border-bottom: 1px solid rgba(15, 23, 42, .07);
}

.ame-social-feed-profile-cover {
	height: 82px;
}

.ame-social-feed-profile-cover-facebook {
	background: linear-gradient(115deg, #1877f2 0%, #3b82f6 45%, #dbeafe 100%);
}

.ame-social-feed-profile-row {
	display: flex;
	align-items: center;
	gap: .85rem;
	margin-top: -26px;
	padding: 0 1.15rem 1rem;
}

.ame-social-feed-profile-logo {
	display: grid;
	width: 64px;
	height: 64px;
	flex: 0 0 64px;
	place-items: center;
	border: 4px solid #fff;
	border-radius: 50%;
	font-weight: 900;
	color: #fff;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .14);
}

.ame-social-feed-profile-logo-facebook {
	font: 900 2rem Arial, sans-serif;
}

.ame-social-feed-profile-row strong,
.ame-social-feed-instagram-details strong {
	display: block;
	font-size: .98rem;
	color: #111827;
}

.ame-social-feed-profile-row span {
	display: block;
	margin-top: .25rem;
	font-size: .76rem;
	color: #64748b;
}

.ame-social-feed-instagram-profile {
	padding: 1.1rem 1.15rem 1rem;
	border-bottom: 1px solid rgba(15, 23, 42, .07);
}

.ame-social-feed-instagram-main {
	display: flex;
	align-items: center;
	gap: 1rem;
}

.ame-social-feed-instagram .ame-social-feed-profile-logo {
	border: 0;
}

.ame-social-feed-profile-logo-instagram {
	font-size: 2rem;
}

.ame-social-feed-instagram-details {
	min-width: 0;
	flex: 1;
}

.ame-social-feed-instagram-stats {
	display: flex;
	flex-wrap: wrap;
	gap: .75rem;
	margin-top: .35rem;
	font-size: .75rem;
	color: #64748b;
}

.ame-social-feed-instagram-stats b {
	color: #111827;
}

.ame-social-feed-instagram-details p {
	margin: .35rem 0 0;
	font-size: .76rem;
	color: #475569;
}

.ame-social-feed-posts {
	min-height: 360px;
}

.ame-social-feed-loading,
.ame-social-feed-empty,
.ame-social-feed-error {
	display: grid;
	min-height: 280px;
	place-items: center;
	padding: 2rem;
	text-align: center;
	font-size: .86rem;
	color: #64748b;
}

.ame-social-feed-post {
	border-bottom: 1px solid rgba(15, 23, 42, .08);
}

.ame-social-feed-post:last-child {
	border-bottom: 0;
}

.ame-social-feed-post-copy {
	padding: 1rem 1.1rem .75rem;
	font-size: .84rem;
	line-height: 1.55;
	color: #334155;
	white-space: pre-line;
}

.ame-social-feed-post-image-wrap {
	position: relative;
	overflow: hidden;
	background: #f1f5f9;
}

.ame-social-feed-post-image {
	display: block;
	width: 100%;
	height: auto;
	max-height: 470px;
	object-fit: cover;
}

.ame-social-feed-post-meta {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: .75rem;
	padding: .75rem 1.1rem 1rem;
	font-size: .72rem;
	color: #64748b;
}

.ame-social-feed-post-meta a {
	font-weight: 750;
	color: #475569;
	text-decoration: none;
}

.ame-social-feed-instagram-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 3px;
	min-height: 0;
}

.ame-social-feed-instagram-grid .ame-social-feed-loading,
.ame-social-feed-instagram-grid .ame-social-feed-empty,
.ame-social-feed-instagram-grid .ame-social-feed-error {
	grid-column: 1 / -1;
	min-height: 280px;
}

.ame-social-feed-instagram-tile {
	position: relative;
	aspect-ratio: 1 / 1;
	overflow: hidden;
	background: #f1f5f9;
}

.ame-social-feed-instagram-tile img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform .25s ease;
}

.ame-social-feed-instagram-tile:hover img {
	transform: scale(1.035);
}

.ame-social-feed-instagram-tile::after {
	content: '';
	position: absolute;
	inset: 0;
	background: linear-gradient(180deg, transparent 65%, rgba(0,0,0,.32));
	pointer-events: none;
}

.ame-social-feed-instagram-tile-label {
	position: absolute;
	z-index: 1;
	left: .65rem;
	bottom: .55rem;
	font-size: .68rem;
	font-weight: 750;
	color: #fff;
	text-shadow: 0 1px 4px rgba(0,0,0,.4);
}

.ame-social-feed-status {
	min-height: 1.2rem;
	margin-top: .8rem;
	text-align: center;
	font-size: .72rem;
	color: #94a3b8;
}

@media (max-width: 900px) {
	.ame-social-feed-grid {
		grid-template-columns: 1fr;
		max-width: 680px;
		margin-inline: auto;
	}
}

@media (max-width: 560px) {
	.ame-social-feed-section {
		padding-block: 4rem;
	}

	.ame-social-feed-card-head,
	.ame-social-feed-profile-row,
	.ame-social-feed-instagram-profile {
		padding-left: .85rem;
		padding-right: .85rem;
	}

	.ame-social-feed-avatar {
		width: 36px;
		height: 36px;
		flex-basis: 36px;
	}

	.ame-social-feed-brand h3 {
		font-size: .9rem;
	}

	.ame-social-feed-follow {
		min-height: 32px;
		padding-inline: .7rem;
		font-size: .72rem;
	}
}
</style>

<script>
(function () {
	var section = document.querySelector('.ame-social-feed-section[data-gas-feed-url]');
	if (!section || section.dataset.socialFeedReady === '1') {
		return;
	}
	section.dataset.socialFeedReady = '1';

	var endpoint = section.getAttribute('data-gas-feed-url');
	var cards = {
		facebook: section.querySelector('[data-social-card="facebook"]'),
		instagram: section.querySelector('[data-social-card="instagram"]')
	};
	var status = section.querySelector('[data-feed-status]');

	function escapeHtml(value) {
		return String(value == null ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function firstValue(obj, keys, fallback) {
		for (var i = 0; i < keys.length; i++) {
			var key = keys[i];
			if (obj && obj[key] !== undefined && obj[key] !== null && obj[key] !== '') {
				return obj[key];
			}
		}
		return fallback;
	}

	function asArray(value) {
		return Array.isArray(value) ? value : [];
	}

	function normalizePayload(payload) {
		var root = payload && payload.data && typeof payload.data === 'object' ? payload.data : payload;
		var instagram = root && root.instagram ? root.instagram : {};
		var facebook = root && root.facebook ? root.facebook : {};

		var instagramItems = asArray(firstValue(instagram, ['posts', 'media', 'items', 'data'], []));
		var facebookItems = asArray(firstValue(facebook, ['posts', 'feed', 'items', 'data'], []));

		if (!instagramItems.length && root && Array.isArray(root.instagram_posts)) {
			instagramItems = root.instagram_posts;
		}
		if (!facebookItems.length && root && Array.isArray(root.facebook_posts)) {
			facebookItems = root.facebook_posts;
		}

		return {
			instagram: {
				profile: instagram.profile || root.instagram_profile || {},
				items: instagramItems
			},
			facebook: {
				profile: facebook.profile || root.facebook_profile || {},
				items: facebookItems
			}
		};
	}

	function formatCount(value) {
		var n = Number(value);
		if (!isFinite(n)) {
			return value || '—';
		}
		if (n >= 1000000) return (n / 1000000).toFixed(n % 1000000 ? 1 : 0) + 'M';
		if (n >= 1000) return (n / 1000).toFixed(n % 1000 ? 1 : 0) + 'K';
		return String(n);
	}

	function formatDate(value) {
		if (!value) return '';
		var date = new Date(value);
		if (isNaN(date.getTime())) return '';
		return date.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' });
	}

	function mediaUrl(item) {
		return firstValue(item, ['media_url', 'image_url', 'full_picture', 'thumbnail_url', 'picture', 'url'], '');
	}

	function permalink(item, fallback) {
		return firstValue(item, ['permalink', 'permalink_url', 'link', 'url'], fallback);
	}

	function caption(item) {
		return firstValue(item, ['caption', 'message', 'text', 'description'], '');
	}

	function renderFacebook(profile, items) {
		var name = firstValue(profile, ['name', 'username', 'title'], 'AME Bazaar');
		var followers = firstValue(profile, ['followers_count', 'followers', 'fan_count'], '');
		var nameEls = cards.facebook.querySelectorAll('[data-profile-name]');
		nameEls.forEach(function (el) { el.textContent = name; });
		var title = cards.facebook.querySelector('[data-profile-title]');
		var stats = cards.facebook.querySelector('[data-profile-stats]');
		if (title) title.textContent = name;
		if (stats && followers) stats.textContent = formatCount(followers) + ' followers';

		var target = cards.facebook.querySelector('[data-posts]');
		if (!items.length) {
			target.innerHTML = '<div class="ame-social-feed-empty">Facebook feed is temporarily unavailable. <a href="<?php echo esc_js( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">View the latest posts on Facebook</a>.</div>';
			return;
		}

		target.innerHTML = items.slice(0, 3).map(function (item) {
			var image = mediaUrl(item);
			var text = caption(item);
			var link = permalink(item, '<?php echo esc_js( $facebook_url ); ?>');
			var date = formatDate(firstValue(item, ['created_time', 'timestamp', 'published_at', 'date'], ''));
			return '<div class="ame-social-feed-post">' +
				(text ? '<div class="ame-social-feed-post-copy">' + escapeHtml(text) + '</div>' : '') +
				(image ? '<div class="ame-social-feed-post-image-wrap"><a href="' + escapeHtml(link) + '" target="_blank" rel="noopener noreferrer"><img class="ame-social-feed-post-image" loading="lazy" src="' + escapeHtml(image) + '" alt="AME Bazaar Facebook post" /></a></div>' : '') +
				'<div class="ame-social-feed-post-meta"><span>' + escapeHtml(date) + '</span><a href="' + escapeHtml(link) + '" target="_blank" rel="noopener noreferrer">View post ↗</a></div>' +
			'</div>';
		}).join('');
	}

	function renderInstagram(profile, items) {
		var username = firstValue(profile, ['username', 'handle'], 'ame_bazaar').replace(/^@/, '');
		var posts = firstValue(profile, ['media_count', 'posts', 'post_count'], '');
		var followers = firstValue(profile, ['followers_count', 'followers'], '');
		var following = firstValue(profile, ['follows_count', 'following_count', 'following'], '');
		var bio = firstValue(profile, ['biography', 'bio', 'description'], 'AME Bazaar · Family Garment Store');

		var names = cards.instagram.querySelectorAll('[data-profile-name]');
		names.forEach(function (el) { el.textContent = username; });
		var title = cards.instagram.querySelector('[data-profile-title]');
		if (title) title.textContent = '@' + username;
		var statPosts = cards.instagram.querySelector('[data-stat-posts]');
		var statFollowers = cards.instagram.querySelector('[data-stat-followers]');
		var statFollowing = cards.instagram.querySelector('[data-stat-following]');
		if (statPosts) statPosts.textContent = formatCount(posts);
		if (statFollowers) statFollowers.textContent = formatCount(followers);
		if (statFollowing) statFollowing.textContent = formatCount(following);
		var bioEl = cards.instagram.querySelector('[data-profile-bio]');
		if (bioEl) bioEl.textContent = bio;

		var target = cards.instagram.querySelector('[data-posts]');
		if (!items.length) {
			target.innerHTML = '<div class="ame-social-feed-empty">Instagram feed is temporarily unavailable. <a href="<?php echo esc_js( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">View the latest posts on Instagram</a>.</div>';
			return;
		}

		target.innerHTML = items.slice(0, 9).map(function (item) {
			var image = mediaUrl(item);
			var link = permalink(item, '<?php echo esc_js( $instagram_url ); ?>');
			var type = firstValue(item, ['media_type', 'type'], '');
			var label = type && type !== 'IMAGE' ? type.toLowerCase() : '';
			return '<a class="ame-social-feed-instagram-tile" href="' + escapeHtml(link) + '" target="_blank" rel="noopener noreferrer" aria-label="View Instagram post">' +
				(image ? '<img loading="lazy" src="' + escapeHtml(image) + '" alt="AME Bazaar Instagram post" />' : '') +
				(label ? '<span class="ame-social-feed-instagram-tile-label">' + escapeHtml(label) + '</span>' : '') +
				'</a>';
		}).join('');
	}

	fetch(endpoint + '?action=social_feed&limit=9&_=' + Date.now(), {
		method: 'GET',
		credentials: 'omit',
		cache: 'no-store',
		headers: { 'Accept': 'application/json' }
	})
		.then(function (response) {
			if (!response.ok) throw new Error('HTTP ' + response.status);
			return response.text();
		})
		.then(function (text) {
			var payload;
			try {
				payload = JSON.parse(text);
			} catch (error) {
				throw new Error('GAS endpoint did not return JSON');
			}
			var data = normalizePayload(payload);
			renderFacebook(data.facebook.profile, data.facebook.items);
			renderInstagram(data.instagram.profile, data.instagram.items);
			status.textContent = 'Live data · refreshed from the AME Bazaar social feed';
		})
		.catch(function (error) {
			console.warn('AME Bazaar social feed:', error);
			cards.facebook.querySelector('[data-posts]').innerHTML = '<div class="ame-social-feed-error">Facebook live feed could not be loaded right now. <a href="<?php echo esc_js( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">Open Facebook</a>.</div>';
			cards.instagram.querySelector('[data-posts]').innerHTML = '<div class="ame-social-feed-error">Instagram live feed could not be loaded right now. <a href="<?php echo esc_js( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">Open Instagram</a>.</div>';
			status.textContent = 'Social feed temporarily unavailable';
		});
})();
</script>
