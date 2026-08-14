<?php
/**
 * AME Bazaar — Final inner-page UI, global WhatsApp CTA and featured-products controls.
 */
if (!defined('ABSPATH')) { exit; }

add_action('admin_menu', function () {
    add_submenu_page('woocommerce','AME Bazaar Featured Products','AME Featured Products','manage_woocommerce','ame-featured-products','ame_render_featured_products_settings');
});

add_action('admin_init', function () {
    register_setting('ame_featured_products_group', 'ame_featured_product_ids', array(
        'type' => 'string',
        'sanitize_callback' => function ($value) {
            $ids = array_filter(array_map('absint', preg_split('/[\s,]+/', (string) $value)));
            return implode(',', array_values(array_unique($ids)));
        },
        'default' => '',
    ));
});

function ame_render_featured_products_settings() {
    if (!current_user_can('manage_woocommerce')) { return; }
    $value = get_option('ame_featured_product_ids', '');
    ?>
    <div class="wrap">
        <h1>AME Bazaar — Featured Products</h1>
        <p style="max-width:760px">Homepage ke Featured Products ko yahin se control karein. WooCommerce Product IDs comma-separated enter karein, example: <code>123,456,789</code>.</p>
        <form method="post" action="options.php">
            <?php settings_fields('ame_featured_products_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="ame_featured_product_ids">Product IDs</label></th>
                    <td>
                        <input id="ame_featured_product_ids" name="ame_featured_product_ids" type="text" class="regular-text code" value="<?php echo esc_attr($value); ?>" placeholder="123,456,789" style="width:min(760px,100%)" />
                        <p class="description">WooCommerce → Products mein product edit karke ID dekhi ja sakti hai. Blank karne par existing WooCommerce Featured behaviour preserve rahega.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Featured Products'); ?>
        </form>
    </div>
    <?php
}

function ame_get_selected_featured_ids() {
    $raw = get_option('ame_featured_product_ids', '');
    if (!$raw) { return array(); }
    return array_values(array_filter(array_map('absint', preg_split('/[\s,]+/', $raw))));
}

add_action('woocommerce_product_query', function ($query) {
    if (!is_front_page()) { return; }
    $ids = ame_get_selected_featured_ids();
    if (!$ids) { return; }
    $featured = $query->get('featured');
    $visibility = $query->get('visibility');
    if ($featured || (is_array($visibility) && in_array('featured', $visibility, true)) || $visibility === 'featured') {
        $query->set('post__in', $ids);
        $query->set('posts_per_page', count($ids));
    }
}, 20);

add_filter('woocommerce_shortcode_products_query', function ($query_args, $attributes) {
    if (!is_front_page()) { return $query_args; }
    $ids = ame_get_selected_featured_ids();
    if (!$ids) { return $query_args; }
    if (isset($attributes['visibility']) && $attributes['visibility'] === 'featured') {
        $query_args['post__in'] = $ids;
        $query_args['posts_per_page'] = count($ids);
    }
    return $query_args;
}, 20, 2);

add_action('wp_enqueue_scripts', function () {
    $css = <<<'CSS'
.ame-inner-hero-fixed,.ame-contact-hero-header.ame-inner-hero-fixed,.ame-about-hero-header.ame-inner-hero-fixed,.ame-faq-hero-header.ame-inner-hero-fixed{position:relative!important;isolation:isolate!important;overflow:hidden!important;background:linear-gradient(135deg,#00182f 0%,#002b50 58%,#063c67 100%)!important}
.ame-inner-hero-fixed:before{content:"";position:absolute;inset:0;z-index:-1;pointer-events:none;background:radial-gradient(circle at 15% 20%,rgba(245,158,11,.13),transparent 34%),radial-gradient(circle at 85% 80%,rgba(255,255,255,.08),transparent 38%)!important}
.ame-inner-hero-fixed :is(h1,h2,h3){color:#fff!important;text-shadow:0 4px 22px rgba(0,0,0,.28)!important;opacity:1!important}
.ame-inner-hero-fixed :is(p,li){color:rgba(255,255,255,.92)!important;opacity:1!important}
.ame-inner-hero-fixed :is(a,.button,.wp-element-button){color:#002347!important;background:#fff!important;border-color:#fff!important}
.ame-inner-hero-fixed :is(.eyebrow,.label,small){color:#f59e0b!important}
@media(max-width:1023px){
 .ame-desktop-menu-luxury{display:flex!important;flex-wrap:nowrap!important;align-items:center!important;justify-content:flex-start!important;gap:6px!important;width:100%!important;min-width:0!important;overflow-x:auto!important;overflow-y:hidden!important;white-space:nowrap!important;padding:5px!important;scrollbar-width:none!important;box-sizing:border-box!important}
 .ame-desktop-menu-luxury::-webkit-scrollbar{display:none!important}
 .ame-desktop-menu-luxury li{display:flex!important;flex:0 0 auto!important;min-width:max-content!important}
 .ame-desktop-menu-luxury a{display:inline-flex!important;flex:0 0 auto!important;width:auto!important;min-width:max-content!important;white-space:nowrap!important;word-break:keep-all!important;overflow:visible!important;padding-inline:11px!important}
}
@media(max-width:782px){
 .ame-header-luxury-wrapper{overflow:visible!important}
 .ame-header-luxury-left,.ame-desktop-nav-luxury{min-width:0!important;max-width:100%!important}
 .ame-desktop-menu-luxury a{font-size:10.5px!important;letter-spacing:.035em!important}
 .ame-inner-hero-fixed{padding:3.25rem 1rem!important}
 .ame-inner-hero-fixed :is(h1,h2){font-size:clamp(2rem,9vw,2.65rem)!important;line-height:1.08!important}
}
.ame-google-review-fixed{cursor:pointer!important}
#ame-global-whatsapp-cta{position:fixed!important;right:24px!important;bottom:24px!important;width:58px!important;height:58px!important;border-radius:50%!important;z-index:100000!important;display:grid!important;place-items:center!important;background:#25D366!important;color:#fff!important;box-shadow:0 12px 30px rgba(0,0,0,.2)!important;border:3px solid #fff!important;text-decoration:none!important;transition:transform .2s ease,box-shadow .2s ease!important}
#ame-global-whatsapp-cta:hover{transform:translateY(-3px) scale(1.03)!important;box-shadow:0 16px 34px rgba(0,0,0,.25)!important}
#ame-global-whatsapp-cta svg{width:30px!important;height:30px!important;display:block!important}
@media(max-width:782px){#ame-global-whatsapp-cta{right:16px!important;bottom:16px!important;width:56px!important;height:56px!important}#ame-global-whatsapp-cta svg{width:29px!important;height:29px!important}}
CSS;
    wp_register_style('ame-bazaar-final-ui-fixes', false);
    wp_enqueue_style('ame-bazaar-final-ui-fixes');
    wp_add_inline_style('ame-bazaar-final-ui-fixes', $css);

    $js = <<<'JS'
(function(){
  function ready(fn){ if(document.readyState !== 'loading'){fn();} else {document.addEventListener('DOMContentLoaded',fn);} }
  ready(function(){
    document.querySelectorAll('h1,h2').forEach(function(heading){
      var t=(heading.textContent||'').trim().toLowerCase();
      if(t.indexOf('frequently asked questions')!==-1 || t.indexOf('contact our store')!==-1 || t.indexOf('about ame bazaar')!==-1){
        var hero=heading.closest('section,header,div');
        if(hero){ hero.classList.add('ame-inner-hero-fixed'); }
      }
    });

    var reviewUrl='https://www.google.com/maps/search/?api=1&query=AME%20Bazaar%20Kirari%20Delhi';
    document.querySelectorAll('a').forEach(function(a){
      var text=(a.textContent||'').trim().toLowerCase();
      var href=(a.getAttribute('href')||'').toLowerCase();
      if(text.indexOf('google reviews')!==-1 || (text.indexOf('781')!==-1 && text.indexOf('review')!==-1) || (href.indexOf('google.com')!==-1 && href.indexOf('review')!==-1)){
        a.href=reviewUrl; a.target='_blank'; a.rel='noopener noreferrer'; a.classList.add('ame-google-review-fixed');
      }
    });

    var wa=null;
    document.querySelectorAll('a[href*="wa.me"],a[href*="whatsapp.com"],a[href*="api.whatsapp.com"]').forEach(function(a){
      var r=a.getBoundingClientRect(), cs=window.getComputedStyle(a);
      if(!wa && (cs.position==='fixed' || (r.width<=90 && r.height<=90))){ wa=a; }
    });
    if(wa){
      wa.id='ame-global-whatsapp-cta';
      wa.setAttribute('aria-label','Chat with AME Bazaar on WhatsApp');
      wa.setAttribute('title','Chat with AME Bazaar on WhatsApp');
      wa.innerHTML='<svg viewBox="0 0 32 32" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M16 3.2A12.7 12.7 0 0 0 5.2 22.9L3.6 28.8l6.1-1.6A12.8 12.8 0 1 0 16 3.2Zm0 23.2c-2 0-3.9-.5-5.6-1.5l-.4-.2-3.6.9 1-3.5-.2-.4a10.5 10.5 0 1 1 8.8 4.7Zm5.8-7.9c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-1.6-.8-2.7-1.5-3.8-3.3-.3-.5.3-.5.8-1.7.1-.2 0-.4-.1-.6-.1-.2-.7-1.7-1-1-2.3-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.1 1.1-1.1 2.7s1.1 3.1 1.2 3.3c.2.2 2.2 3.4 5.4 4.8 2 .9 2.8 1 3.8.8.6-.1 1.8-.7 2.1-1.4.3-.7.3-1.3.2-1.4-.2-.2-.4-.3-.7-.4Z"/></svg>';
      document.querySelectorAll('a[href*="wa.me"],a[href*="whatsapp.com"],a[href*="api.whatsapp.com"]').forEach(function(other){ if(other!==wa && other.getBoundingClientRect().width<100) other.style.display='none'; });
    }
  });
})();
JS;
    wp_add_inline_script('jquery', $js, 'after');
}, 999);
