(() => {
	// Initialize Page JS State
	document.documentElement.classList.remove('no-js');
	document.documentElement.classList.add('js-ready');

	// DOM Elements
	const header = document.querySelector('.ame-header-main-wrapper');
	const menuToggle = document.getElementById('ame-menu-toggle-btn');
	const menuDrawer = document.getElementById('ame-mobile-menu-drawer');
	const menuClose = document.getElementById('ame-menu-close-btn');
	const menuOverlayBg = document.getElementById('ame-menu-overlay-bg');

	// Active focus trap targets
	let focusTrapCleanup = null;

	/* ==========================================================================
	   1. STICKY HEADER SCROLL TRIGGER
	   ========================================================================== */
	if (header) {
		const handleScroll = () => {
			if (window.scrollY > 20) {
				header.classList.add('is-scrolled');
			} else {
				header.classList.remove('is-scrolled');
			}
		};
		window.addEventListener('scroll', handleScroll, { passive: true });
		// Initial check
		handleScroll();
	}

	/* ==========================================================================
	   2. ACCESSIBILITY FOCUS TRAP HELPER (WCAG 2.2 AA)
	   ========================================================================== */
	const createFocusTrap = (container) => {
		const focusableElements = container.querySelectorAll(
			'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable]'
		);
		const firstElement = focusableElements[0];
		const lastElement = focusableElements[focusableElements.length - 1];

		// Save currently focused element to return later
		const previouslyFocused = document.activeElement;

		// Focus the first element
		setTimeout(() => {
			if (firstElement) firstElement.focus();
		}, 100);

		const handleKeyDown = (e) => {
			if (e.key !== 'Tab') return;

			if (e.shiftKey) {
				// Shift + Tab (Backward)
				if (document.activeElement === firstElement) {
					lastElement.focus();
					e.preventDefault();
				}
			} else {
				// Tab (Forward)
				if (document.activeElement === lastElement) {
					firstElement.focus();
					e.preventDefault();
				}
			}
		};

		container.addEventListener('keydown', handleKeyDown);

		// Return a cleanup function
		return () => {
			container.removeEventListener('keydown', handleKeyDown);
			if (previouslyFocused && typeof previouslyFocused.focus === 'function') {
				previouslyFocused.focus();
			}
		};
	};

	/* ==========================================================================
	   3. MOBILE MENU DRAWER CONTROLS
	   ========================================================================== */
	const openMenu = () => {
		if (!menuDrawer || !menuToggle) return;
		menuDrawer.classList.add('is-active');
		menuToggle.setAttribute('aria-expanded', 'true');
		document.body.style.overflow = 'hidden'; // Prevent background scrolling

		// Trap focus inside menu drawer
		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(menuDrawer);
	};

	const closeMenu = () => {
		if (!menuDrawer || !menuToggle) return;
		menuDrawer.classList.remove('is-active');
		menuToggle.setAttribute('aria-expanded', 'false');
		document.body.style.overflow = '';

		// Clean up focus trap
		if (focusTrapCleanup) {
			focusTrapCleanup();
			focusTrapCleanup = null;
		}
	};

	if (menuToggle) menuToggle.addEventListener('click', openMenu);
	if (menuClose) menuClose.addEventListener('click', closeMenu);
	if (menuOverlayBg) menuOverlayBg.addEventListener('click', closeMenu);

	/* ==========================================================================
	   4. GLOBAL KEYBOARD LISTENERS (ESC key close)
	   ========================================================================== */
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (menuDrawer && menuDrawer.classList.contains('is-active')) {
				closeMenu();
			}
		}
	/* ==========================================================================
	   5. READING PROGRESS INDICATOR
	   ========================================================================== */
	const progressIndicator = document.getElementById('ame-reading-progress-bar');
	if (progressIndicator) {
		const updateProgress = () => {
			const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
			if (totalHeight > 0) {
				const progress = (window.scrollY / totalHeight) * 100;
				progressIndicator.style.width = `${progress}%`;
			}
		};
		window.addEventListener('scroll', updateProgress, { passive: true });
		updateProgress();
	}
})();
