<?php
/**
 * AME Bazaar final production UI fixes.
 *
 * This file intentionally lives inside the deployable theme because the
 * Hostinger workflow syncs the theme directory only.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Featured Products admin control. */
add_action( 'admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'AME Bazaar Featured Products',
        'AME Featured Products',
        'manage_woocommerce',
        'ame-featured-products',
        'ame_render_featured_products_settings'
    );
} );

add_action( 'admin_init', function () {
    register_setting( 'ame_featured_products_group', 'ame_featured_product_ids', array(
        'type'              => 'string',
        'sanitize_callback' => function ( $value ) {
            $ids = array_filter( array_map( 'absint', preg_split( '/[\s,]+/', (string) $value ) ) );
            return implode( ',', array_values( array_unique( $ids ) ) );
        },
        'default'           => '',
    ) );
} );

function ame_render_featured_products_settings() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
    $value = get_option( 'ame_featured_product_ids', '' );
    ?>
    <div class="wrap">
        <h1>AME Bazaar — Featured Products</h1>
        <p style="max-width:760px">Homepage ke Featured Products ko yahin se control karein. WooCommerce Product IDs comma-separated enter karein, example: <code>123,456,789</code>.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'ame_featured_products_group' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="ame_featured_product_ids">Product IDs</label></th>
                    <td>
                        <input id="ame_featured_product_ids" name="ame_featured_product_ids" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="123,456,789" style="width:min(760px,100%)" />
                        <p class="description">WooCommerce → Products mein product edit karke ID dekhi ja sakti hai. Blank karne par existing WooCommerce Featured behaviour preserve rahega.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Featured Products' ); ?>
        </form>
    </div>
    <?php
}

function ame_get_selected_featured_ids() {
    $raw = get_option( 'ame_featured_product_ids', '' );
    if ( ! $raw ) { return array(); }
    return array_values( array_filter( array_map( 'absint', preg_split( '/[\s,]+/', $raw ) ) ) );
}

add_action( 'woocommerce_product_query', function ( $query ) {
    if ( ! is_front_page() ) { return; }
    $ids = ame_get_selected_featured_ids();
    if ( ! $ids ) { return; }
    $featured  = $query->get( 'featured' );
    $visibility = $query->get( 'visibility' );
    if ( $featured || ( is_array( $visibility ) && in_array( 'featured', $visibility, true ) ) || $visibility === 'featured' ) {
        $query->set( 'post__in', $ids );
        $query->set( 'posts_per_page', count( $ids ) );
    }
}, 20 );

add_filter( 'woocommerce_shortcode_products_query', function ( $query_args, $attributes ) {
    if ( ! is_front_page() ) { return $query_args; }
    $ids = ame_get_selected_featured_ids();
    if ( ! $ids ) { return $query_args; }
    if ( isset( $attributes['visibility'] ) && $attributes['visibility'] === 'featured' ) {
        $query_args['post__in'] = $ids;
        $query_args['posts_per_page'] = count( $ids );
    }
    return $query_args;
}, 20, 2 );

/**
 * Final UI CSS. Loaded after the existing premium stylesheet so inline/template
 * styles cannot leave the inner-page hero text dark.
 */
add_action( 'wp_enqueue_scripts', function () {
    $css = <<<'CSS'
/* ===== AME FINAL PRODUCTION FIXES ===== */

/* 1. Inner-page hero contrast — beat inline/template styles. */
.ame-faq-hero-header,
.ame-contact-hero-header,
.ame-about-hero-header,
.ame-inner-hero-fixed {
    background: linear-gradient(135deg,#00182f 0%,#002b50 58%,#063c67 100%) !important;
    color: #fff !important;
    isolation: isolate !important;
    position: relative !important;
    overflow: hidden !important;
}
.ame-faq-hero-header::before,
.ame-contact-hero-header::before,
.ame-about-hero-header::before,
.ame-inner-hero-fixed::before {
    content: "" !important;
    position: absolute !important;
    inset: 0 !important;
    pointer-events: none !important;
    z-index: -1 !important;
    background: radial-gradient(circle at 15% 20%,rgba(245,158,11,.13),transparent 34%),radial-gradient(circle at 85% 80%,rgba(255,255,255,.08),transparent 38%) !important;
}
.ame-faq-hero-header h1,
.ame-faq-hero-header h2,
.ame-faq-hero-header h3,
.ame-contact-hero-header h1,
.ame-contact-hero-header h2,
.ame-contact-hero-header h3,
.ame-about-hero-header h1,
.ame-about-hero-header h2,
.ame-about-hero-header h3,
.ame-inner-hero-fixed h1,
.ame-inner-hero-fixed h2,
.ame-inner-hero-fixed h3 {
    color: #fff !important;
    opacity: 1 !important;
    text-shadow: 0 4px 22px rgba(0,0,0,.28) !important;
}
.ame-faq-hero-header p,
.ame-contact-hero-header p,
.ame-about-hero-header p,
.ame-inner-hero-fixed p,
.ame-inner-hero-fixed li {
    color: rgba(255,255,255,.94) !important;
    opacity: 1 !important;
}
.ame-faq-hero-header a,
.ame-contact-hero-header a,
.ame-about-hero-header a,
.ame-inner-hero-fixed a {
    color: #002347 !important;
}

/* 2. Real mobile header: never squeeze the desktop menu into phone width. */
@media (max-width:1023px) {
    .ame-header-luxury-wrapper {
        min-height: 72px !important;
        padding-block: .65rem !important;
    }
    .ame-header-luxury-inner {
        grid-template-columns: 38px minmax(0,1fr) auto !important;
        gap: .5rem !important;
        min-height: 48px !important;
        width: 100% !important;
    }
    .ame-header-luxury-left {
        min-width: 0 !important;
        width: 38px !important;
        gap: 0 !important;
        justify-content: flex-start !important;
    }
    .ame-luxury-menu-toggle {
        display: flex !important;
        flex: 0 0 22px !important;
        visibility: visible !important;
    }
    .ame-desktop-nav-luxury {
        display: none !important;
    }
    .ame-header-luxury-center {
        min-width: 0 !important;
        justify-content: flex-start !important;
        overflow: hidden !important;
    }
    .ame-logo-link,
    .ame-logo-img {
        max-width: 100% !important;
    }
    .ame-logo-img { max-height: 34px !important; }
    .ame-header-luxury-right {
        min-width: 0 !important;
        gap: .65rem !important;
        justify-content: flex-end !important;
    }
    .ame-luxury-pill-btn { display: none !important; }
    .ame-luxury-action-btn { flex: 0 0 auto !important; }
    .ame-luxury-icon { width: 17px !important; height: 17px !important; }
}
@media (max-width:380px) {
    .ame-header-luxury-inner { grid-template-columns: 34px minmax(0,1fr) auto !important; gap: .35rem !important; }
    .ame-header-luxury-left { width: 34px !important; }
    .ame-header-luxury-right { gap: .5rem !important; }
    .ame-logo-img { max-height: 30px !important; }
}

/* 3. One permanent WhatsApp floating button. */
#ame-global-whatsapp-cta {
    position: fixed !important;
    right: 22px !important;
    bottom: 22px !important;
    width: 58px !important;
    height: 58px !important;
    border-radius: 50% !important;
    z-index: 2147483000 !important;
    display: grid !important;
    place-items: center !important;
    background: #25D366 !important;
    color: #fff !important;
    border: 3px solid #fff !important;
    box-shadow: 0 10px 28px rgba(0,0,0,.22) !important;
    text-decoration: none !important;
}
#ame-global-whatsapp-cta svg { width: 31px !important; height: 31px !important; display:block !important; }
@media(max-width:782px){
    #ame-global-whatsapp-cta { right: 15px !important; bottom: 15px !important; width:56px !important; height:56px !important; }
    #ame-global-whatsapp-cta svg { width:30px !important; height:30px !important; }
}

/* Hide any other tiny/floating WhatsApp widgets once our canonical button exists. */
body.ame-has-global-whatsapp a.ame-legacy-whatsapp-float { display:none !important; }
CSS;

    wp_register_style( 'ame-bazaar-final-production-fixes', false );
    wp_enqueue_style( 'ame-bazaar-final-production-fixes' );
    wp_add_inline_style( 'ame-bazaar-final-production-fixes', $css );
}, 1000 );

/** Permanent WhatsApp button + broken-review link correction. */
add_action( 'wp_footer', function () {
    $whatsapp = function_exists( 'ame_bazaar_get_business_setting' ) ? ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' ) : '+91 99535 69533';
    $wa_digits = preg_replace( '/\D+/', '', (string) $whatsapp );
    if ( strlen( $wa_digits ) === 10 ) { $wa_digits = '91' . $wa_digits; }
    $wa_url = 'https://wa.me/' . $wa_digits;
    $review_url = 'https://www.google.com/maps/search/?api=1&query=AME%20Bazaar%20Kirari%20Delhi';
    ?>
    <a id="ame-global-whatsapp-cta" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat with AME Bazaar on WhatsApp" title="Chat with AME Bazaar on WhatsApp">
        <svg viewBox="0 0 32 32" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M16 3.2A12.7 12.7 0 0 0 5.2 22.9L3.6 28.8l6.1-1.6A12.8 12.8 0 1 0 16 3.2Zm0 23.2c-2 0-3.9-.5-5.6-1.5l-.4-.2-3.6.9 1-3.5-.2-.4a10.5 10.5 0 1 1 8.8 4.7Zm5.8-7.9c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-1.6-.8-2.7-1.5-3.8-3.3-.3-.5.3-.5.8-1.7.1-.2 0-.4-.1-.6-.1-.2-.7-1.7-1-1-2.3-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.1 1.1-1.1 2.7s1.1 3.1 1.2 3.3c.2.2 2.2 3.4 5.4 4.8 2 .9 2.8 1 3.8.8.6-.1 1.8-.7 2.1-1.4.3-.7.3-1.3.2-1.4-.2-.2-.4-.3-.7-.4Z"/></svg>
    </a>
    <script>
    (function(){
        document.body.classList.add('ame-has-global-whatsapp');
        var reviewUrl=<?php echo wp_json_encode( $review_url ); ?>;
        var anchors=document.querySelectorAll('a');
        anchors.forEach(function(a){
            var text=(a.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            var href=(a.getAttribute('href')||'').toLowerCase();
            if ((text.indexOf('google reviews')!==-1) || (text.indexOf('781')!==-1 && text.indexOf('review')!==-1) || href.indexOf('search.google.com')!==-1 || (href.indexOf('/reviews')!==-1 && href.indexOf('amebazaar')!==-1)) {
                a.setAttribute('href',reviewUrl);
                a.setAttribute('target','_blank');
                a.setAttribute('rel','noopener noreferrer');
            }
            if (href.indexOf('wa.me')!==-1 || href.indexOf('whatsapp.com')!==-1 || href.indexOf('api.whatsapp.com')!==-1) {
                var r=a.getBoundingClientRect(), cs=window.getComputedStyle(a);
                if (a.id!=='ame-global-whatsapp-cta' && (cs.position==='fixed' || (r.width<=100 && r.height<=100))) {
                    a.classList.add('ame-legacy-whatsapp-float');
                }
            }
        });
    })();
    </script>
    <?php
}, 9999 );
