<?php
/**
 * AME Bazaar mobile header final layout.
 * Desktop remains untouched. Mobile gets a clean 3-zone first row:
 * Call/Visit | centered logo | action icons, with navigation below.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', function () {
    $css = <<<'CSS'
/* ===== AME MOBILE HEADER — FINAL 3-ZONE LAYOUT ===== */
@media (max-width: 1023px) {
    .ame-header-luxury-inner {
        display: grid !important;
        grid-template-columns: minmax(0,1fr) auto minmax(0,1fr) !important;
        grid-template-rows: 52px 44px !important;
        column-gap: 6px !important;
        row-gap: 6px !important;
        align-items: center !important;
        width: 100% !important;
        min-height: 102px !important;
    }
    .ame-mobile-pill-zone {
        grid-column: 1 !important;
        grid-row: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 5px !important;
        min-width: 0 !important;
        overflow: visible !important;
    }
    .ame-mobile-pill-zone .ame-luxury-pill-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 0 !important;
        min-height: 34px !important;
        padding: 7px 8px !important;
        border-radius: 999px !important;
        font-size: 8px !important;
        line-height: 1 !important;
        letter-spacing: .02em !important;
        white-space: nowrap !important;
        flex: 1 1 0 !important;
        max-width: 86px !important;
    }
    .ame-header-luxury-center {
        grid-column: 2 !important;
        grid-row: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 0 !important;
        width: auto !important;
        z-index: 3 !important;
    }
    .ame-logo-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: auto !important;
        margin: 0 !important;
    }
    .ame-logo-img {
        width: auto !important;
        max-width: 58px !important;
        max-height: 42px !important;
        object-fit: contain !important;
    }
    .ame-header-luxury-right {
        grid-column: 3 !important;
        grid-row: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 4px !important;
        min-width: 0 !important;
        white-space: nowrap !important;
    }
    .ame-header-luxury-right .ame-luxury-pill-btn { display: none !important; }
    .ame-header-luxury-right .ame-luxury-action-btn {
        flex: 0 0 auto !important;
        width: 30px !important;
        height: 30px !important;
        min-width: 30px !important;
        min-height: 30px !important;
        padding: 0 !important;
        display: grid !important;
        place-items: center !important;
    }
    .ame-header-luxury-right .ame-luxury-icon { width: 15px !important; height: 15px !important; }
    .ame-header-luxury-left {
        grid-column: 1 / -1 !important;
        grid-row: 2 !important;
        width: 100% !important;
        min-width: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        overflow: visible !important;
    }
    .ame-luxury-menu-toggle { display: none !important; }
    .ame-desktop-nav-luxury {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0 !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        white-space: nowrap !important;
        scrollbar-width: none !important;
    }
    .ame-desktop-nav-luxury::-webkit-scrollbar { display: none !important; }
    .ame-desktop-nav-luxury a {
        font-size: 9px !important;
        line-height: 1 !important;
        padding: 9px 9px !important;
        letter-spacing: .02em !important;
        white-space: nowrap !important;
    }
}
@media (max-width: 390px) {
    .ame-header-luxury-wrapper { padding-inline: 7px !important; }
    .ame-header-luxury-inner {
        grid-template-columns: minmax(0,1fr) 58px minmax(0,1fr) !important;
        column-gap: 3px !important;
    }
    .ame-mobile-pill-zone { gap: 3px !important; }
    .ame-mobile-pill-zone .ame-luxury-pill-btn {
        padding-inline: 5px !important;
        font-size: 7.2px !important;
        max-width: 78px !important;
    }
    .ame-logo-img { max-width: 54px !important; max-height: 42px !important; }
    .ame-header-luxury-right { gap: 2px !important; }
    .ame-header-luxury-right .ame-luxury-action-btn {
        width: 27px !important;
        height: 27px !important;
        min-width: 27px !important;
        min-height: 27px !important;
    }
    .ame-header-luxury-right .ame-luxury-icon { width: 14px !important; height: 14px !important; }
    .ame-desktop-nav-luxury a { font-size: 8px !important; padding-inline: 8px !important; }
}
CSS;

    wp_register_style( 'ame-bazaar-mobile-header-layout-final', false );
    wp_enqueue_style( 'ame-bazaar-mobile-header-layout-final' );
    wp_add_inline_style( 'ame-bazaar-mobile-header-layout-final', $css );
}, 1200 );

add_action( 'wp_footer', function () {
    ?>
    <script>
    (function () {
        function arrangeAmeMobileHeader() {
            if (window.innerWidth > 1023) return;
            var inner = document.querySelector('.ame-header-luxury-inner');
            var right = inner && inner.querySelector('.ame-header-luxury-right');
            var center = inner && inner.querySelector('.ame-header-luxury-center');
            if (!inner || !right || !center) return;
            if (inner.querySelector('.ame-mobile-pill-zone')) return;
            var pills = right.querySelectorAll('.ame-luxury-pill-btn');
            if (!pills.length) return;
            var zone = document.createElement('div');
            zone.className = 'ame-mobile-pill-zone';
            pills.forEach(function (pill) { zone.appendChild(pill); });
            inner.insertBefore(zone, center);
        }
        function run() {
            arrangeAmeMobileHeader();
            setTimeout(arrangeAmeMobileHeader, 100);
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
        else run();
        window.addEventListener('resize', arrangeAmeMobileHeader, { passive: true });
    })();
    </script>
    <?php
}, 10002 );
