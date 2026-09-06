<?php
/**
 * Live Facebook + Instagram feed for the AME Bazaar homepage.
 *
 * The browser talks only to the same-origin WordPress REST proxy. The proxy
 * reads the public feed from the existing Google Apps Script, keeping Meta
 * credentials out of WordPress and the browser.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$facebook_url  = 'https://www.facebook.com/AMETTBAZAAR';
$instagram_url = 'https://www.instagram.com/ame_bazaar/';
$feed_api_url  = rest_url( 'ame/v1/social-feed' );
?>

<section class="ame-social-feed-section" aria-labelledby="ame-social-feed-title" data-social-feed-api="<?php echo esc_url( $feed_api_url ); ?>">
	<div class="ame-bazaar-container">
		<div class="ame-social-feed-unified-container">
			<div class="ame-social-feed-header">
				<div class="ame-social-feed-live"><span></span> LIVE FROM AME BAZAAR</div>
				<h2 id="ame-social-feed-title" class="ame-h2">Follow AME Bazaar</h2>
				<p class="ame-body">See our latest styles, arrivals and updates</p>
			</div>

			<div class="ame-social-feed-scroll-area">
				<div class="ame-social-feed-columns">
					
					<article class="ame-social-feed-column facebook-column" data-social-card="facebook">
						<header class="ame-social-feed-card-head">
							<div class="ame-social-feed-brand"><div class="ame-social-feed-brand-icon facebook">f</div><div><strong data-profile-name>AME Bazaar</strong><small>Facebook</small></div></div>
							<a class="ame-social-feed-follow facebook" href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">Follow Page</a>
						</header>
						<div class="ame-social-feed-facebook-profile"><div class="ame-social-feed-facebook-cover"></div><div class="ame-social-feed-facebook-profile-row"><div class="ame-social-feed-profile-icon facebook">f</div><div><strong data-profile-title>AME Bazaar</strong><small data-profile-stats>Official Facebook Page</small></div></div></div>
						<div class="ame-social-feed-posts" data-posts><div class="ame-social-feed-loading">Loading live Facebook posts…</div></div>
					</article>

					<div class="ame-social-feed-divider"></div>

					<article class="ame-social-feed-column instagram-column" data-social-card="instagram">
						<header class="ame-social-feed-card-head">
							<div class="ame-social-feed-brand"><div class="ame-social-feed-brand-icon instagram">◎</div><div><strong data-profile-name>ame_bazaar</strong><small>Instagram</small></div></div>
							<a class="ame-social-feed-follow instagram" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">Follow</a>
						</header>
						<div class="ame-social-feed-instagram-profile"><div class="ame-social-feed-profile-icon instagram">◎</div><div class="ame-social-feed-instagram-details"><strong data-profile-title>@ame_bazaar</strong><div class="ame-social-feed-instagram-stats"><span><b data-stat-posts>—</b> posts</span><span><b data-stat-followers>—</b> followers</span><span><b data-stat-following>—</b> following</span></div><p data-profile-bio>AME Bazaar · Family Garment Store</p></div></div>
						<div class="ame-social-feed-posts ame-social-feed-instagram-grid" data-posts><div class="ame-social-feed-loading">Loading live Instagram posts…</div></div>
					</article>

				</div>
			</div>

			<div class="ame-social-feed-status" data-feed-status><span class="dot"></span> Live feed · checking for new posts every 5 minutes</div>
		</div>
	</div>
</section>

<style>
.ame-social-feed-section{padding:5rem 0;background:linear-gradient(180deg,#f7f8fb 0%,#fff 100%)}
.ame-bazaar-container{max-width:1200px;margin:0 auto;padding:0 1rem}
.ame-social-feed-unified-container{max-width:1080px;margin:0 auto;background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:24px;box-shadow:0 20px 60px rgba(15,23,42,.08);overflow:hidden;display:flex;flex-direction:column}
.ame-social-feed-header{padding:2.5rem 2rem 1.5rem;text-align:center;border-bottom:1px solid rgba(15,23,42,.05)}
.ame-social-feed-live{display:inline-flex;align-items:center;gap:.45rem;margin-bottom:.7rem;font-size:.7rem;font-weight:900;letter-spacing:.16em;color:#b08a32}
.ame-social-feed-live span,.ame-social-feed-status .dot{width:7px;height:7px;border-radius:50%;background:#16a34a;box-shadow:0 0 0 4px rgba(22,163,74,.1)}
.ame-social-feed-header .ame-h2{margin:0 0 .5rem;color:#0f172a;font-size:1.8rem;font-weight:850}
.ame-social-feed-header .ame-body{margin:0;color:#64748b;font-size:1rem}
.ame-social-feed-scroll-area{max-height:720px;overflow-y:auto;overflow-x:hidden;scroll-behavior:smooth}
.ame-social-feed-scroll-area::-webkit-scrollbar{width:8px}
.ame-social-feed-scroll-area::-webkit-scrollbar-track{background:rgba(15,23,42,.02)}
.ame-social-feed-scroll-area::-webkit-scrollbar-thumb{background:rgba(15,23,42,.15);border-radius:4px}
.ame-social-feed-scroll-area::-webkit-scrollbar-thumb:hover{background:rgba(15,23,42,.25)}
.ame-social-feed-columns{display:grid;grid-template-columns:1fr 1px 1fr;min-height:0}
.ame-social-feed-divider{background:rgba(15,23,42,.05)}
.ame-social-feed-column{min-width:0;display:flex;flex-direction:column}
.ame-social-feed-card-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1.2rem 1.5rem;position:sticky;top:0;background:rgba(255,255,255,.95);backdrop-filter:blur(8px);z-index:10;border-bottom:1px solid rgba(15,23,42,.05)}
.ame-social-feed-brand{display:flex;align-items:center;gap:.7rem;min-width:0}
.ame-social-feed-brand strong{display:block;color:#111827;font-size:1rem}
.ame-social-feed-brand small{display:block;margin-top:.15rem;color:#64748b;font-size:.75rem}
.ame-social-feed-brand-icon,.ame-social-feed-profile-icon{display:grid;place-items:center;flex:0 0 40px;width:40px;height:40px;border-radius:12px;color:#fff;font-weight:900}
.ame-social-feed-brand-icon.facebook,.ame-social-feed-profile-icon.facebook{background:#1877f2;font-family:Arial,sans-serif;font-size:1.4rem}
.ame-social-feed-brand-icon.instagram,.ame-social-feed-profile-icon.instagram{background:linear-gradient(135deg,#f58529,#dd2a7b 52%,#8134af 82%,#515bd4);font-size:1.5rem}
.ame-social-feed-follow{display:inline-flex;align-items:center;justify-content:center;height:32px;padding:0 1rem;border-radius:999px;text-decoration:none!important;font-size:.75rem;font-weight:800;white-space:nowrap;transition:transform .2s, opacity .2s}
.ame-social-feed-follow:hover{transform:translateY(-1px);opacity:.9}
.ame-social-feed-follow.facebook{background:#1877f2;color:#fff!important}
.ame-social-feed-follow.instagram{background:#111827;color:#fff!important}

.ame-social-feed-facebook-profile{padding-bottom:1rem;border-bottom:1px solid rgba(15,23,42,.05)}
.ame-social-feed-facebook-cover{height:90px;background:linear-gradient(115deg,#1877f2 0%,#60a5fa 52%,#dbeafe 100%)}
.ame-social-feed-facebook-profile-row{display:flex;align-items:center;gap:1rem;margin-top:-20px;padding:0 1.5rem}
.ame-social-feed-facebook-profile-row .ame-social-feed-profile-icon{border:3px solid #fff;border-radius:50%;box-shadow:0 4px 12px rgba(15,23,42,.12);width:50px;height:50px;flex:0 0 50px;font-size:1.6rem}
.ame-social-feed-facebook-profile-row strong{display:block;font-size:1.1rem;color:#0f172a;font-weight:800}
.ame-social-feed-facebook-profile-row small{display:block;margin-top:.15rem;font-size:.8rem;color:#64748b}

.ame-social-feed-instagram-profile{display:flex;align-items:center;gap:1.2rem;padding:1.5rem;border-bottom:1px solid rgba(15,23,42,.05)}
.ame-social-feed-instagram-profile .ame-social-feed-profile-icon{border-radius:50%;width:56px;height:56px;flex:0 0 56px;font-size:2rem}
.ame-social-feed-instagram-details{min-width:0}
.ame-social-feed-instagram-details>strong{display:block;color:#0f172a;font-size:1.1rem;font-weight:800}
.ame-social-feed-instagram-stats{display:flex;flex-wrap:wrap;gap:1rem;margin-top:.4rem;color:#64748b;font-size:.8rem}
.ame-social-feed-instagram-stats b{color:#0f172a;font-weight:700}
.ame-social-feed-instagram-details p{margin:.5rem 0 0;color:#475569;font-size:.85rem}

.ame-social-feed-posts{min-height:300px;padding:1rem 1.5rem}
.ame-social-feed-post{margin-bottom:1.5rem;background:#fff;border:1px solid rgba(15,23,42,.06);border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,.03)}
.ame-social-feed-post:last-child{margin-bottom:0}
.ame-social-feed-post-copy{padding:1.2rem 1.2rem .8rem;color:#334155;font-size:.9rem;line-height:1.6;white-space:pre-line}
.ame-social-feed-post-image-wrap{overflow:hidden;background:#f8fafc;border-top:1px solid rgba(15,23,42,.04)}
.ame-social-feed-post-image{display:block;width:100%;max-height:500px;object-fit:cover}
.ame-social-feed-post-meta{display:flex;justify-content:space-between;gap:1rem;padding:1rem 1.2rem;background:#f8fafc;border-top:1px solid rgba(15,23,42,.04);color:#64748b;font-size:.8rem}
.ame-social-feed-post-meta a{color:#0f172a;text-decoration:none;font-weight:700}

.ame-social-feed-instagram-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;padding:1.5rem;min-height:0}
.ame-social-feed-instagram-tile{position:relative;display:block;aspect-ratio:1;overflow:hidden;background:#f1f5f9;border-radius:8px}
.ame-social-feed-instagram-tile img{display:block;width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
.ame-social-feed-instagram-tile:hover img{transform:scale(1.05)}
.ame-social-feed-instagram-tile:after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 60%,rgba(0,0,0,.28));pointer-events:none}
.ame-social-feed-instagram-tile-label{position:absolute;z-index:1;left:.6rem;bottom:.6rem;color:#fff;font-size:.7rem;font-weight:800;text-shadow:0 1px 4px rgba(0,0,0,.5)}

.ame-social-feed-loading,.ame-social-feed-empty,.ame-social-feed-error{display:grid;place-items:center;min-height:300px;padding:2rem;text-align:center;color:#64748b;font-size:.9rem;line-height:1.5}
.ame-social-feed-empty a,.ame-social-feed-error a{color:#0f172a;font-weight:800}
.ame-social-feed-instagram-grid .ame-social-feed-loading,.ame-social-feed-instagram-grid .ame-social-feed-empty,.ame-social-feed-instagram-grid .ame-social-feed-error{grid-column:1/-1}
.ame-social-feed-status{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:1rem;background:#f8fafc;border-top:1px solid rgba(15,23,42,.05);color:#64748b;font-size:.75rem}

@media(max-width:900px){
    .ame-social-feed-columns{grid-template-columns:1fr;grid-template-rows:auto auto}
    .ame-social-feed-divider{height:1px;width:100%;background:rgba(15,23,42,.08);margin:0}
    .ame-social-feed-scroll-area{max-height:80vh}
}
@media(max-width:560px){
    .ame-social-feed-section{padding:3rem 0}
    .ame-social-feed-header{padding:1.5rem 1rem 1rem}
    .ame-social-feed-header .ame-h2{font-size:1.5rem}
    .ame-social-feed-card-head{padding:1rem}
    .ame-social-feed-facebook-profile-row{padding:0 1rem}
    .ame-social-feed-instagram-profile{padding:1rem}
    .ame-social-feed-posts{padding:1rem}
    .ame-social-feed-instagram-grid{padding:1rem;gap:2px}
    .ame-social-feed-instagram-tile{border-radius:4px}
    .ame-social-feed-brand-icon,.ame-social-feed-profile-icon{width:32px;height:32px;flex-basis:32px;font-size:1.1rem!important}
}
</style>

<script>
(function(){
	var section=document.querySelector('.ame-social-feed-section[data-social-feed-api]');
	if(!section||section.dataset.socialFeedReady==='1')return;section.dataset.socialFeedReady='1';
	var endpoint=section.getAttribute('data-social-feed-api'),fb=section.querySelector('[data-social-card="facebook"]'),ig=section.querySelector('[data-social-card="instagram"]'),status=section.querySelector('[data-feed-status]');
	function val(o,keys,f){for(var i=0;i<keys.length;i++){if(o&&o[keys[i]]!==undefined&&o[keys[i]]!==null&&o[keys[i]]!=='')return o[keys[i]]}return f}function arr(v){return Array.isArray(v)?v:[]}function esc(v){return String(v==null?'':v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}function count(v){var n=Number(v);if(!isFinite(n))return v||'—';if(n>=1000000)return(n/1000000).toFixed(n%1000000?1:0)+'M';if(n>=1000)return(n/1000).toFixed(n%1000?1:0)+'K';return String(n)}function date(v){if(!v)return'';var d=new Date(v);return isNaN(d.getTime())?'':d.toLocaleDateString(undefined,{day:'numeric',month:'short',year:'numeric'})}function media(o){return val(o,['media_url','image_url','full_picture','thumbnail_url','picture','display_url'],'')}function link(o,f){return val(o,['permalink','permalink_url','link','url'],f)}function text(o){return val(o,['caption','message','text','description'],'')}
	function normalize(p){var r=p&&p.data&&typeof p.data==='object'?p.data:p||{},i=r.instagram||{},f=r.facebook||{},ip=arr(val(i,['posts','media','items','data'],[])),fp=arr(val(f,['posts','feed','items','data'],[]));if(!ip.length)ip=arr(r.instagram_posts);if(!fp.length)fp=arr(r.facebook_posts);return{instagram:{profile:i.profile||r.instagram_profile||{},items:ip},facebook:{profile:f.profile||r.facebook_profile||{},items:fp}}}
	function renderFb(p,items){var name=val(p,['name','username','title'],'AME Bazaar'),followers=val(p,['followers_count','followers','fan_count'],'');fb.querySelectorAll('[data-profile-name]').forEach(function(e){e.textContent=name});fb.querySelector('[data-profile-title]').textContent=name;if(followers)fb.querySelector('[data-profile-stats]').textContent=count(followers)+' followers';var t=fb.querySelector('[data-posts]');if(!items.length){t.innerHTML='<div class="ame-social-feed-empty">No Facebook posts were returned by the live source.<br><a href="<?php echo esc_js( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">Open Facebook</a></div>';return}t.innerHTML=items.slice(0,3).map(function(o){var im=media(o),l=link(o,'<?php echo esc_js( $facebook_url ); ?>'),tx=text(o),d=date(val(o,['created_time','timestamp','published_at','date'],''));return'<div class="ame-social-feed-post">'+(tx?'<div class="ame-social-feed-post-copy">'+esc(tx)+'</div>':'')+(im?'<div class="ame-social-feed-post-image-wrap"><a href="'+esc(l)+'" target="_blank" rel="noopener noreferrer"><img class="ame-social-feed-post-image" loading="lazy" src="'+esc(im)+'" alt="AME Bazaar Facebook post"></a></div>':'')+'<div class="ame-social-feed-post-meta"><span>'+esc(d)+'</span><a href="'+esc(l)+'" target="_blank" rel="noopener noreferrer">View post ↗</a></div></div>'}).join('')}
	function renderIg(p,items){var u=val(p,['username','handle'],'ame_bazaar').replace(/^@/,''),posts=val(p,['media_count','posts','post_count'],''),followers=val(p,['followers_count','followers'],''),following=val(p,['follows_count','following_count','following'],''),bio=val(p,['biography','bio','description'],'AME Bazaar · Family Garment Store');ig.querySelectorAll('[data-profile-name]').forEach(function(e){e.textContent=u});ig.querySelector('[data-profile-title]').textContent='@'+u;ig.querySelector('[data-stat-posts]').textContent=count(posts);ig.querySelector('[data-stat-followers]').textContent=count(followers);ig.querySelector('[data-stat-following]').textContent=count(following);ig.querySelector('[data-profile-bio]').textContent=bio;var t=ig.querySelector('[data-posts]');if(!items.length){t.innerHTML='<div class="ame-social-feed-empty">No Instagram posts were returned by the live source.<br><a href="<?php echo esc_js( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">Open Instagram</a></div>';return}t.innerHTML=items.slice(0,9).map(function(o){var im=media(o),l=link(o,'<?php echo esc_js( $instagram_url ); ?>'),type=val(o,['media_type','type'],'');return'<a class="ame-social-feed-instagram-tile" href="'+esc(l)+'" target="_blank" rel="noopener noreferrer" aria-label="View Instagram post">'+(im?'<img loading="lazy" src="'+esc(im)+'" alt="AME Bazaar Instagram post">':'')+(type&&type!=='IMAGE'?'<span class="ame-social-feed-instagram-tile-label">'+esc(type.toLowerCase())+'</span>':'')+'</a>'}).join('')}
	function load(){fetch(endpoint+'?limit=9&_='+Date.now(),{credentials:'same-origin',cache:'no-store',headers:{Accept:'application/json'}}).then(function(r){if(!r.ok)throw new Error('HTTP '+r.status);return r.json()}).then(function(p){var d=normalize(p);renderFb(d.facebook.profile,d.facebook.items);renderIg(d.instagram.profile,d.instagram.items);status.innerHTML='<span class="dot"></span> Live data · last checked '+new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});}).catch(function(e){console.warn('AME Bazaar social feed:',e);fb.querySelector('[data-posts]').innerHTML='<div class="ame-social-feed-error">Facebook live feed is temporarily unavailable.<br><a href="<?php echo esc_js( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">Open Facebook</a></div>';ig.querySelector('[data-posts]').innerHTML='<div class="ame-social-feed-error">Instagram live feed is temporarily unavailable.<br><a href="<?php echo esc_js( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">Open Instagram</a></div>';status.innerHTML='<span class="dot"></span> Live feed temporarily unavailable · retrying automatically';})}
	load();setInterval(load,300000);
})();
</script>
