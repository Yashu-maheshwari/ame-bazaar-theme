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

/* Hide any other tiny/floating WhatsApp widgets once our canonical button exists. */
body.ame-has-global-whatsapp a.ame-legacy-whatsapp-float { display:none !important; }
CSS;

    wp_register_style( 'ame-bazaar-final-production-fixes', false );
    wp_enqueue_style( 'ame-bazaar-final-production-fixes' );
    wp_add_inline_style( 'ame-bazaar-final-production-fixes', $css );
}, 1000 );

/** Broken-review link correction. */
add_action( 'wp_footer', function () {
    $review_url = 'https://www.google.com/maps/search/?api=1&query=AME%20Bazaar%20Kirari%20Delhi';
    ?>
    <script>
    (function(){
        /* Google Reviews: replace stale internal link with a safe Google Maps destination. */
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
        });
    })();
    </script>
    <?php
}, 9999 );
