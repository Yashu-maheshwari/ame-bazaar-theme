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

	// Search Elements
	const searchOpenBtn = document.getElementById('ame-search-open-btn');
	const searchCloseBtn = document.getElementById('ame-search-close-btn');
	const searchOverlay = document.getElementById('ame-desktop-search-overlay');
	const searchInput = document.getElementById('ame-search-input');

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
	   4. DESKTOP SEARCH OVERLAY CONTROLS
	   ========================================================================== */
	const openSearch = () => {
		if (!searchOverlay) return;
		searchOverlay.classList.add('is-active');
		document.body.style.overflow = 'hidden';

		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(searchOverlay);

		setTimeout(() => {
			if (searchInput) searchInput.focus();
		}, 100);
	};

	const closeSearch = () => {
		if (!searchOverlay) return;
		searchOverlay.classList.remove('is-active');
		document.body.style.overflow = '';

		if (focusTrapCleanup) {
			focusTrapCleanup();
			focusTrapCleanup = null;
		}
	};

	if (searchOpenBtn) searchOpenBtn.addEventListener('click', openSearch);
	if (searchCloseBtn) searchCloseBtn.addEventListener('click', closeSearch);

	/* ==========================================================================
	   5. GLOBAL KEYBOARD LISTENERS (ESC key close)
	   ========================================================================== */
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (menuDrawer && menuDrawer.classList.contains('is-active')) {
				closeMenu();
			}
			if (searchOverlay && searchOverlay.classList.contains('is-active')) {
				closeSearch();
			}
		}
	});

	/* ==========================================================================
	   6. READING PROGRESS INDICATOR
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

	/* ==========================================================================
	   7. DESIGN SYSTEM INTERACTIVE COMPONENTS (MODAL, DRAWER, TOAST, ACCORDION, TABS)
	   ========================================================================== */

	// 7.1 MODAL & DRAWER TOGGLES
	const demoModal = document.getElementById('sg-demo-modal');
	const triggerModal = document.getElementById('trigger-sg-modal');
	const demoDrawer = document.getElementById('sg-demo-drawer');
	const triggerDrawer = document.getElementById('trigger-sg-drawer');

	const openModal = () => {
		if (!demoModal) return;
		demoModal.classList.add('is-active');
		demoModal.removeAttribute('aria-hidden');
		document.body.style.overflow = 'hidden';

		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(demoModal);
	};

	const closeModal = () => {
		if (!demoModal) return;
		demoModal.classList.remove('is-active');
		demoModal.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';

		if (focusTrapCleanup) {
			focusTrapCleanup();
			focusTrapCleanup = null;
		}
	};

	const openDrawer = () => {
		if (!demoDrawer) return;
		demoDrawer.classList.add('is-active');
		demoDrawer.removeAttribute('aria-hidden');
		document.body.style.overflow = 'hidden';

		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(demoDrawer);
	};

	const closeDrawer = () => {
		if (!demoDrawer) return;
		demoDrawer.classList.remove('is-active');
		demoDrawer.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';

		if (focusTrapCleanup) {
			focusTrapCleanup();
			focusTrapCleanup = null;
		}
	};

	if (triggerModal) triggerModal.addEventListener('click', openModal);
	if (demoModal) {
		demoModal.querySelectorAll('.ame-modal-close-btn, .ame-modal-overlay').forEach(el => {
			el.addEventListener('click', closeModal);
		});
	}

	if (triggerDrawer) triggerDrawer.addEventListener('click', openDrawer);
	if (demoDrawer) {
		demoDrawer.querySelectorAll('.ame-drawer-close-btn, .ame-drawer-overlay').forEach(el => {
			el.addEventListener('click', closeDrawer);
		});
	}

	// 7.2 ACCORDION INTERACTION
	const accordions = document.querySelectorAll('.ame-accordion-header');
	accordions.forEach(header => {
		header.addEventListener('click', () => {
			const panelId = header.getAttribute('aria-controls');
			const panel = document.getElementById(panelId);
			const isExpanded = header.getAttribute('aria-expanded') === 'true';

			header.setAttribute('aria-expanded', !isExpanded);
			if (panel) {
				if (isExpanded) {
					panel.style.maxHeight = panel.scrollHeight + 'px';
					setTimeout(() => {
						panel.style.maxHeight = '0';
						panel.setAttribute('hidden', '');
					}, 10);
				} else {
					panel.removeAttribute('hidden');
					panel.style.maxHeight = '0';
					setTimeout(() => {
						panel.style.maxHeight = panel.scrollHeight + 'px';
					}, 10);
					setTimeout(() => {
						panel.style.maxHeight = '';
					}, 300);
				}
			}
		});
	});

	// 7.3 TABS INTERACTION
	const tabs = document.querySelectorAll('.ame-tab-btn');
	tabs.forEach(tab => {
		tab.addEventListener('click', () => {
			const tabList = tab.closest('.ame-tabs-list');
			const tabsContainer = tab.closest('.ame-tabs');
			if (!tabList || !tabsContainer) return;

			// Deactivate all tabs in list
			tabList.querySelectorAll('.ame-tab-btn').forEach(t => {
				t.classList.remove('ame-tab-btn-active');
				t.setAttribute('aria-selected', 'false');
				t.setAttribute('tabindex', '-1');
			});

			// Hide all panels
			tabsContainer.querySelectorAll('.ame-tab-panel').forEach(p => {
				p.classList.remove('ame-tab-panel-active');
				p.setAttribute('hidden', '');
			});

			// Activate current tab
			tab.classList.add('ame-tab-btn-active');
			tab.setAttribute('aria-selected', 'true');
			tab.removeAttribute('tabindex');

			// Show current panel
			const panelId = tab.getAttribute('aria-controls');
			const panel = document.getElementById(panelId);
			if (panel) {
				panel.classList.add('ame-tab-panel-active');
				panel.removeAttribute('hidden');
			}
		});
	});

	// 7.4 TOAST NOTIFICATION GENERATOR
	const toastContainer = document.getElementById('ame-global-toast-container');
	const triggerToastSuccess = document.getElementById('trigger-sg-toast-success');
	const triggerToastError = document.getElementById('trigger-sg-toast-error');

	const showToast = (message, type = 'success') => {
		if (!toastContainer) return;
		const toast = document.createElement('div');
		toast.className = `ame-toast ame-toast-${type}`;
		toast.setAttribute('role', 'status');

		const textSpan = document.createElement('span');
		textSpan.textContent = message;
		toast.appendChild(textSpan);

		const closeBtn = document.createElement('button');
		closeBtn.className = 'ame-toast-close';
		closeBtn.innerHTML = '&times;';
		closeBtn.setAttribute('aria-label', 'Dismiss toast');
		closeBtn.addEventListener('click', () => {
			toast.remove();
		});
		toast.appendChild(closeBtn);

		toastContainer.appendChild(toast);

		// Auto dismiss after 4 seconds
		setTimeout(() => {
			if (toast.parentNode) {
				toast.style.opacity = '0';
				toast.style.transform = 'translateX(100%)';
				toast.style.transition = 'all 0.3s ease';
				setTimeout(() => {
					toast.remove();
				}, 300);
			}
		}, 4000);
	};

	if (triggerToastSuccess) {
		triggerToastSuccess.addEventListener('click', () => {
			showToast('Tailoring measurement settings saved successfully!', 'success');
		});
	}

	if (triggerToastError) {
		triggerToastError.addEventListener('click', () => {
			showToast('Failed to connect to WordPress database. Please try again.', 'error');
		});
	}

	// Update Escape key listener to close Styleguide Modals/Drawers too
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (demoModal && demoModal.classList.contains('is-active')) {
				closeModal();
			}
			if (demoDrawer && demoDrawer.classList.contains('is-active')) {
				closeDrawer();
			}
		}
	});
})();
