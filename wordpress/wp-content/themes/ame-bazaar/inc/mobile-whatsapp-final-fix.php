<?php
/**
 * AME Bazaar final mobile header + WhatsApp brand icon fix.
 * Loaded after final-ui-fixes.php so these rules intentionally win.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', function () {
    $css = <<<'CSS'
/* ===== FINAL MOBILE HEADER: DESKTOP-STYLE, NO OVERLAP ===== */
@media (max-width: 1023px) {
    .ame-header-luxury-wrapper {
        min-height: 112px !important;
        padding: 8px 10px 10px !important;
        overflow: visible !important;
    }

    .ame-header-luxury-inner {
        display: grid !important;
        grid-template-columns: minmax(0,1fr) auto !important;
        grid-template-rows: 48px 42px !important;
        gap: 6px 8px !important;
        width: 100% !important;
        min-height: 96px !important;
        align-items: center !important;
    }

    /* Keep logo + actions on the first row. */
    .ame-header-luxury-center {
        grid-column: 1 !important;
        grid-row: 1 !important;
        min-width: 0 !important;
        justify-content: flex-start !important;
        overflow: visible !important;
    }
    .ame-logo-link { display: flex !important; align-items: center !important; }
    .ame-logo-img { max-height: 34px !important; width: auto !important; max-width: 150px !important; }

    /* Desktop navigation becomes the second row instead of disappearing. */
    .ame-header-luxury-left {
        grid-column: 1 / -1 !important;
        grid-row: 2 !important;
        width: 100% !important;
        min-width: 0 !important;
        justify-content: center !important;
        overflow: visible !important;
    }
    .ame-luxury-menu-toggle { display: none !important; }
    .ame-desktop-nav-luxury {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        justify-content: center !important;
        gap: 0 !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        white-space: nowrap !important;
        scrollbar-width: none !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .ame-desktop-nav-luxury::-webkit-scrollbar { display: none !important; }
    .ame-desktop-nav-luxury a {
        font-size: 10px !important;
        line-height: 1 !important;
        padding: 10px 11px !important;
        letter-spacing: .02em !important;
    }

    /* Keep Call/Visit and the useful icons on row one, without overlap. */
    .ame-header-luxury-right {
        grid-column: 2 !important;
        grid-row: 1 !important;
        min-width: 0 !important;
        gap: 7px !important;
        justify-content: flex-end !important;
        white-space: nowrap !important;
    }
    .ame-luxury-pill-btn {
        display: inline-flex !important;
        min-height: 34px !important;
        padding: 8px 11px !important;
        font-size: 9px !important;
        line-height: 1 !important;
        white-space: nowrap !important;
    }
    .ame-luxury-action-btn { flex: 0 0 auto !important; }
    .ame-luxury-icon { width: 16px !important; height: 16px !important; }
}

@media (max-width: 560px) {
    .ame-header-luxury-wrapper { min-height: 108px !important; }
    .ame-header-luxury-inner { grid-template-columns: minmax(0,1fr) auto !important; }
    .ame-logo-img { max-height: 31px !important; max-width: 125px !important; }
    .ame-header-luxury-right { gap: 5px !important; }
    .ame-luxury-pill-btn { padding: 7px 8px !important; font-size: 8px !important; }
    .ame-luxury-icon { width: 15px !important; height: 15px !important; }
    .ame-desktop-nav-luxury a { font-size: 9px !important; padding: 9px 9px !important; }
}

@media (max-width: 390px) {
    .ame-header-luxury-wrapper { padding-inline: 7px !important; }
    .ame-header-luxury-right { gap: 4px !important; }
    .ame-luxury-pill-btn { padding: 7px 6px !important; font-size: 7.5px !important; }
    .ame-logo-img { max-width: 112px !important; }
    .ame-desktop-nav-luxury a { font-size: 8px !important; padding-inline: 8px !important; }
}

/* ===== REAL WHATSAPP BRAND ICON ===== */
#ame-global-whatsapp-cta svg {
    width: 32px !important;
    height: 32px !important;
    display: block !important;
}
CSS;

    wp_register_style( 'ame-bazaar-mobile-whatsapp-final', false );
    wp_enqueue_style( 'ame-bazaar-mobile-whatsapp-final' );
    wp_add_inline_style( 'ame-bazaar-mobile-whatsapp-final', $css );
}, 1100 );

/* Replace the earlier approximate drawing with the standard WhatsApp icon geometry. */
add_action( 'wp_footer', function () {
    ?>
    <script>
    (function () {
        function fixAmeWhatsAppIcon() {
            var button = document.getElementById('ame-global-whatsapp-cta');
            if (!button) return;
            button.innerHTML = '<svg viewBox="0 0 360 362" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" fill-rule="evenodd" d="M307.546 52.566C273.709 18.684 228.706.017 180.756 0 81.951 0 1.538 80.404 1.504 179.235c-.017 31.594 8.242 62.432 23.928 89.609L0 361.736l95.024-24.925c26.179 14.285 55.659 21.805 85.655 21.814h.077c98.788 0 179.21-80.413 179.244-179.244.017-47.898-18.608-92.926-52.454-126.807v-.008Zm-126.79 275.788h-.06c-26.73-.008-52.952-7.194-75.831-20.765l-5.44-3.231-56.391 14.791 15.05-54.981-3.542-5.638c-14.912-23.721-22.793-51.139-22.776-79.286.035-82.14 66.867-148.973 149.051-148.973 39.793.017 77.198 15.53 105.328 43.695 28.131 28.157 43.61 65.596 43.593 105.398-.035 82.149-66.867 148.982-148.982 148.982v.008Zm81.719-111.577c-4.478-2.243-26.497-13.073-30.606-14.568-4.108-1.496-7.09-2.243-10.073 2.243-2.982 4.487-11.568 14.577-14.181 17.559-2.613 2.991-5.226 3.361-9.704 1.117-4.477-2.243-18.908-6.97-36.02-22.226-13.313-11.878-22.304-26.54-24.916-31.027-2.613-4.486-.275-6.91 1.959-9.136 2.011-2.011 4.478-5.234 6.721-7.847 2.244-2.613 2.983-4.486 4.478-7.469 1.496-2.991.748-5.603-.369-7.847-1.118-2.243-10.073-24.289-13.812-33.253-3.636-8.732-7.331-7.546-10.073-7.692-2.613-.13-5.595-.155-8.586-.155-2.991 0-7.839 1.118-11.947 5.604-4.108 4.486-15.677 15.324-15.677 37.361s16.047 43.344 18.29 46.335c2.243 2.991 31.585 48.225 76.51 67.632 10.684 4.615 19.029 7.374 25.535 9.437 10.727 3.412 20.49 2.931 28.208 1.779 8.604-1.289 26.498-10.838 30.228-21.298 3.73-10.46 3.73-19.433 2.613-21.298-1.117-1.865-4.108-2.991-8.586-5.234l.008-.017Z" clip-rule="evenodd"/></svg>';
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fixAmeWhatsAppIcon);
        } else {
            fixAmeWhatsAppIcon();
        }
    })();
    </script>
    <?php
}, 10001 );
