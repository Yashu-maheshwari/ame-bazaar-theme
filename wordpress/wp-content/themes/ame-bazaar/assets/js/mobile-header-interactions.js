(() => {
    'use strict';

    const init = () => {
        const mobileHeader = document.querySelector('.ame-mobile-header-premium');
        if (!mobileHeader) return;

        /* ------------------------------------------------------------------
         * Mobile Search
         * The mobile search control is intentionally an anchor for styling,
         * while the existing global search controller listens only to the
         * desktop button ID. Bridge the two controls here.
         * ------------------------------------------------------------------ */
        const searchOverlay = document.getElementById('ame-desktop-search-overlay');
        const searchInput = document.getElementById('ame-search-input');
        const searchClose = document.getElementById('ame-search-close-btn');
        const mobileSearchButtons = mobileHeader.querySelectorAll('.ame-search-toggle');

        const openSearch = (event) => {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            if (!searchOverlay) {
                // Safe fallback if the overlay is unavailable for any reason.
                const searchUrl = new URL('/', window.location.origin);
                searchUrl.searchParams.set('s', '');
                window.location.assign(searchUrl.toString());
                return;
            }

            searchOverlay.classList.add('is-active');
            searchOverlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            window.setTimeout(() => {
                if (searchInput) searchInput.focus();
            }, 80);
        };

        mobileSearchButtons.forEach((button) => {
            button.addEventListener('click', openSearch, { passive: false });
        });

        if (searchClose) {
            searchClose.addEventListener('click', () => {
                if (searchOverlay) {
                    searchOverlay.classList.remove('is-active');
                    searchOverlay.setAttribute('aria-hidden', 'true');
                }
                document.body.style.overflow = '';
            });
        }

        /* ------------------------------------------------------------------
         * Mobile Wishlist
         * Reuse the same wishlist destination as the desktop header. This
         * fixes the mobile control when the YITH URL is available only after
         * the header has rendered, and provides a sensible /wishlist/ fallback
         * when the plugin API is unavailable.
         * ------------------------------------------------------------------ */
        const mobileWishlistButtons = mobileHeader.querySelectorAll(
            '.ame-mobile-icon-btn[aria-label="Wishlist"]'
        );
        const desktopWishlist = document.querySelector(
            '.ame-header-luxury-right a[aria-label="Wishlist"]'
        );

        mobileWishlistButtons.forEach((button) => {
            let destination = button.getAttribute('href') || '';
            const desktopDestination = desktopWishlist
                ? (desktopWishlist.getAttribute('href') || '')
                : '';

            if (!destination || destination === '#') {
                if (desktopDestination && desktopDestination !== '#') {
                    destination = desktopDestination;
                } else {
                    destination = '/wishlist/';
                }
                button.setAttribute('href', destination);
            }

            button.addEventListener('click', (event) => {
                const href = button.getAttribute('href');
                if (!href || href === '#') {
                    event.preventDefault();
                    window.location.assign('/wishlist/');
                }
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
