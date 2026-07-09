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

	// Update Escape key listener to close Styleguide Modals/Drawers and Mini-Cart too
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (demoModal && demoModal.classList.contains('is-active')) {
				closeModal();
			}
			if (demoDrawer && demoDrawer.classList.contains('is-active')) {
				closeDrawer();
			}
			if (miniCartDrawer && miniCartDrawer.classList.contains('is-active')) {
				closeMiniCart();
			}
		}
	});

	/* ==========================================================================
	   8. WOOCOMMERCE COMMERCE ACTIONS (MINI CART, SEARCH, WISHLIST, STICKY CART)
	   ========================================================================== */

	// 8.1 MINI CART DRAWER
	const miniCartDrawer = document.getElementById('ame-mini-cart-drawer');
	const cartTriggers = document.querySelectorAll('.ame-cart-link');
	
	const openMiniCart = (e) => {
		if (e) e.preventDefault();
		if (!miniCartDrawer) return;
		miniCartDrawer.classList.add('is-active');
		miniCartDrawer.removeAttribute('aria-hidden');
		document.body.style.overflow = 'hidden';
		
		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(miniCartDrawer);
	};
	
	const closeMiniCart = () => {
		if (!miniCartDrawer) return;
		miniCartDrawer.classList.remove('is-active');
		miniCartDrawer.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';
		
		if (focusTrapCleanup) {
			focusTrapCleanup();
			focusTrapCleanup = null;
		}
	};
	
	cartTriggers.forEach(trigger => trigger.addEventListener('click', openMiniCart));
	if (miniCartDrawer) {
		miniCartDrawer.querySelectorAll('#ame-cart-close-btn, #ame-cart-overlay-bg').forEach(el => {
			el.addEventListener('click', closeMiniCart);
		});
	}

	// 8.2 AJAX SEARCH SUGGESTIONS
	const searchInputs = document.querySelectorAll('#ame-search-input, #ame-mobile-search-input');
	searchInputs.forEach(input => {
		let debounceTimer;
		let suggestionBox = document.createElement('div');
		suggestionBox.className = 'ame-search-suggestions-overlay';
		input.parentNode.appendChild(suggestionBox);

		input.addEventListener('input', () => {
			clearTimeout(debounceTimer);
			const query = input.value.trim();
			
			if (query.length < 3) {
				suggestionBox.innerHTML = '';
				suggestionBox.style.display = 'none';
				return;
			}
			
			debounceTimer = setTimeout(() => {
				if (typeof ameBazaarAjax === 'undefined') return;

				const formData = new FormData();
				formData.append('action', 'ame_bazaar_search');
				formData.append('query', query);
				formData.append('nonce', ameBazaarAjax.nonce);

				fetch(ameBazaarAjax.ajaxurl, {
					method: 'POST',
					body: formData
				})
				.then(res => res.json())
				.then(response => {
					if (response.success && response.data.length > 0) {
						suggestionBox.innerHTML = '';
						const ul = document.createElement('ul');
						ul.className = 'ame-suggestions-list';
						
						response.data.forEach(item => {
							const li = document.createElement('li');
							li.className = 'ame-suggestion-item';
							li.innerHTML = `
								<a href="${item.link}" class="ame-suggestion-link">
									${item.image ? `<img src="${item.image}" alt="" class="ame-suggestion-img">` : ''}
									<div class="ame-suggestion-info">
										<span class="ame-suggestion-title">${item.title}</span>
										<span class="ame-suggestion-price">${item.price}</span>
									</div>
								</a>
							`;
							ul.appendChild(li);
						});
						suggestionBox.appendChild(ul);
						suggestionBox.style.display = 'block';
					} else {
						suggestionBox.innerHTML = '<p class="ame-no-suggestions">No products found</p>';
						suggestionBox.style.display = 'block';
					}
				})
				.catch(err => console.error(err));
			}, 300);
		});

		// Close suggestions when clicking outside
		document.addEventListener('click', (e) => {
			if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
				suggestionBox.style.display = 'none';
			}
		});
	});

	// 8.3 MOBILE STICKY ADD TO CART
	const stickyCartMobile = document.getElementById('ame-mobile-sticky-cart');
	const mainAddCartBtn = document.querySelector('.single_add_to_cart_button');
	if (stickyCartMobile && mainAddCartBtn) {
		const handleScroll = () => {
			if (window.innerWidth >= 768) {
				stickyCartMobile.style.display = 'none';
				stickyCartMobile.setAttribute('aria-hidden', 'true');
				return;
			}
			const rect = mainAddCartBtn.getBoundingClientRect();
			const isOutOfView = rect.bottom < 0;
			
			if (isOutOfView) {
				stickyCartMobile.style.display = 'flex';
				stickyCartMobile.removeAttribute('aria-hidden');
			} else {
				stickyCartMobile.style.display = 'none';
				stickyCartMobile.setAttribute('aria-hidden', 'true');
			}
		};
		window.addEventListener('scroll', handleScroll, { passive: true });
	}

	// 8.4 WISHLIST & COMPARE TOAST TRIGGER
	document.addEventListener('click', (e) => {
		const wishlistBtn = e.target.closest('.ame-wishlist-action-btn');
		if (wishlistBtn) {
			e.preventDefault();
			showToast('Product added to Wishlist! (Architecture Demo, No plugin required)', 'success');
		}

		const compareBtn = e.target.closest('.ame-compare-action-btn');
		if (compareBtn) {
			e.preventDefault();
			showToast('Product added to Compare! (Architecture Demo, No plugin required)', 'success');
		}
	});

	// 8.5 QUICK VIEW MODAL
	document.addEventListener('click', (e) => {
		const qvBtn = e.target.closest('.ame-quickview-btn');
		if (!qvBtn) return;
		e.preventDefault();
		
		const productId = qvBtn.getAttribute('data-product-id');
		if (!productId || typeof ameBazaarAjax === 'undefined') return;

		const formData = new FormData();
		formData.append('action', 'ame_bazaar_quick_view');
		formData.append('product_id', productId);

		fetch(ameBazaarAjax.ajaxurl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(response => {
			if (response.success && response.data.html) {
				// Remove existing quickview modal if any
				const existingModal = document.getElementById('ame-quickview-modal');
				if (existingModal) existingModal.remove();

				// Create modal markup
				const modal = document.createElement('div');
				modal.id = 'ame-quickview-modal';
				modal.className = 'ame-modal ame-quickview-modal is-active';
				modal.setAttribute('role', 'dialog');
				modal.setAttribute('aria-modal', 'true');
				modal.setAttribute('aria-label', 'Product Quick View');

				modal.innerHTML = `
					<div class="ame-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,35,71,0.5); backdrop-filter: blur(4px); z-index: 1100;"></div>
					<div class="ame-modal-container" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,35,71,0.15); z-index: 1200; max-width: 800px; width: 90%; overflow: hidden; display: flex; flex-direction: column;">
						<button class="ame-modal-close-btn" style="position: absolute; top: 1.5rem; right: 1.5rem; font-size: 2rem; background: none; border: none; cursor: pointer; color: #64748b; line-height: 1; transition: color 0.2s;" aria-label="Close modal">&times;</button>
						<div class="ame-modal-content" style="padding: 3rem;">
							${response.data.html}
						</div>
					</div>
				`;

				document.body.appendChild(modal);
				document.body.style.overflow = 'hidden';

				// Trap focus inside modal
				if (focusTrapCleanup) focusTrapCleanup();
				focusTrapCleanup = createFocusTrap(modal);

				// Bind close events
				const closeModal = () => {
					modal.classList.remove('is-active');
					document.body.style.overflow = '';
					if (focusTrapCleanup) {
						focusTrapCleanup();
						focusTrapCleanup = null;
					}
					modal.remove();
				};

				modal.querySelectorAll('.ame-modal-close-btn, .ame-modal-overlay').forEach(el => {
					el.addEventListener('click', closeModal);
				});

				// Bind Escape key specifically for this modal
				const escHandler = (event) => {
					if (event.key === 'Escape') {
						closeModal();
						document.removeEventListener('keydown', escHandler);
					}
				};
				document.addEventListener('keydown', escHandler);
			} else {
				showToast('Failed to load product preview.', 'error');
			}
		})
		.catch(err => {
			console.error(err);
			showToast('Failed to load product preview.', 'error');
		});
	});

	// Size Chart Modal Trigger
	document.addEventListener('click', (e) => {
		const sizeBtn = e.target.closest('#ame-trigger-size-chart-modal');
		if (!sizeBtn) return;
		e.preventDefault();

		// Remove existing modal if any
		const existingModal = document.getElementById('ame-size-chart-modal');
		if (existingModal) existingModal.remove();

		// Create modal
		const modal = document.createElement('div');
		modal.id = 'ame-size-chart-modal';
		modal.className = 'ame-modal ame-size-chart-modal is-active';
		modal.setAttribute('role', 'dialog');
		modal.setAttribute('aria-modal', 'true');
		modal.setAttribute('aria-label', 'Size and Fitting Chart');

		modal.innerHTML = `
			<div class="ame-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,35,71,0.5); backdrop-filter: blur(4px); z-index: 1100;"></div>
			<div class="ame-modal-container" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,35,71,0.15); z-index: 1200; max-width: 600px; width: 90%; overflow: hidden; display: flex; flex-direction: column;">
				<button class="ame-modal-close-btn" style="position: absolute; top: 1.5rem; right: 1.5rem; font-size: 2rem; background: none; border: none; cursor: pointer; color: #64748b; line-height: 1; transition: color 0.2s;" aria-label="Close modal">&times;</button>
				<div class="ame-modal-content" style="padding: 3rem;">
					<h3 style="font-family: 'Outfit', 'Inter', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--ame-color-primary); margin-top: 0; margin-bottom: 1.5rem; text-align: center;">Indian Standard Ethnic Size Chart</h3>
					<div style="overflow-x: auto;">
						<table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.9rem; min-width: 400px;">
							<thead>
								<tr style="background: #f8fafc; border-bottom: 2px solid #cbd5e1; color: var(--ame-color-primary); font-weight: 700;">
									<th style="padding: 0.75rem; border: 1px solid #cbd5e1;">Size</th>
									<th style="padding: 0.75rem; border: 1px solid #cbd5e1;">Bust (in)</th>
									<th style="padding: 0.75rem; border: 1px solid #cbd5e1;">Waist (in)</th>
									<th style="padding: 0.75rem; border: 1px solid #cbd5e1;">Shoulder (in)</th>
									<th style="padding: 0.75rem; border: 1px solid #cbd5e1;">Length (in)</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border-bottom: 1px solid #e2e8f0;">
									<td style="padding: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">S (36)</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">36</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">32</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">14.5</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">42</td>
								</tr>
								<tr style="border-bottom: 1px solid #e2e8f0; background: #fafafa;">
									<td style="padding: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">M (38)</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">38</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">34</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">15</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">42</td>
								</tr>
								<tr style="border-bottom: 1px solid #e2e8f0;">
									<td style="padding: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">L (40)</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">40</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">36</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">15.5</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">44</td>
								</tr>
								<tr style="border-bottom: 1px solid #e2e8f0; background: #fafafa;">
									<td style="padding: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">XL (42)</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">42</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">38</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">16</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">44</td>
								</tr>
								<tr style="border-bottom: 1px solid #e2e8f0;">
									<td style="padding: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">XXL (44)</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">44</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">40</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">16.5</td>
									<td style="padding: 0.75rem; border: 1px solid #e2e8f0;">45</td>
								</tr>
							</tbody>
						</table>
					</div>
					<p style="margin-top: 1.5rem; font-size: 0.8rem; color: #64748b; line-height: 1.5; text-align: center;">
						* Need custom adjustments? We offer free alteration support at our Mubarakpur Road outlet. Bring your online invoice!
					</p>
				</div>
			</div>
		`;

		document.body.appendChild(modal);
		document.body.style.overflow = 'hidden';

		// Trap focus inside modal
		if (focusTrapCleanup) focusTrapCleanup();
		focusTrapCleanup = createFocusTrap(modal);

		// Bind close events
		const closeModal = () => {
			modal.classList.remove('is-active');
			document.body.style.overflow = '';
			if (focusTrapCleanup) {
				focusTrapCleanup();
				focusTrapCleanup = null;
			}
			modal.remove();
		};

		modal.querySelectorAll('.ame-modal-close-btn, .ame-modal-overlay').forEach(el => {
			el.addEventListener('click', closeModal);
		});

		// Bind Escape key specifically for this modal
		const escHandler = (event) => {
			if (event.key === 'Escape') {
				closeModal();
				document.removeEventListener('keydown', escHandler);
			}
		};
		document.addEventListener('keydown', escHandler);
	});

	/* ==========================================================================
	   8. LIGHTWEIGHT ANALYTICS EVENT ARCHITECTURE (RULE 4 & 5)
	   ========================================================================== */
	window.ameDataLayer = window.ameDataLayer || [];

	const trackAmeEvent = (eventName, eventParams = {}) => {
		const payload = {
			event: eventName,
			timestamp: new Date().toISOString(),
			page_path: window.location.pathname,
			page_title: document.title,
			...eventParams
		};
		window.ameDataLayer.push(payload);

		// Dispatch custom Javascript Event on window for extensibility
		const customEvent = new CustomEvent('ame_analytics_event', { detail: payload });
		window.dispatchEvent(customEvent);

		console.log('[AME Analytics]', payload);
	};

	// Expose globally
	window.trackAmeEvent = trackAmeEvent;

	// Global Event Delegation click listener
	document.body.addEventListener('click', (e) => {
		const link = e.target.closest('a');
		const element = e.target.closest('button, a, [role="button"], .ame-bazaar-btn, .ame-btn-primary, .ame-btn-secondary');

		if (!element) return;

		// 1. WhatsApp Clicks
		if (link && (link.href.includes('wa.me') || link.href.includes('whatsapp.com'))) {
			trackAmeEvent('whatsapp_click', {
				href: link.href,
				text: link.innerText.trim() || 'WhatsApp Link',
				location: link.closest('header') ? 'header' : (link.closest('footer') ? 'footer' : 'body')
			});
			return;
		}

		// 2. Call Clicks
		if (link && link.href.startsWith('tel:')) {
			trackAmeEvent('call_click', {
				href: link.href,
				text: link.innerText.trim() || 'Call Link'
			});
			return;
		}

		// 3. Directions Clicks
		if (link && (link.href.includes('google.com/maps') || link.href.includes('maps.google.com') || link.href.includes('maps.app.goo.gl'))) {
			trackAmeEvent('directions_click', {
				href: link.href,
				text: link.innerText.trim() || 'Get Directions'
			});
			return;
		}

		// 4. Category Clicks
		const categorySlug = element.getAttribute('data-category-slug') || (link && link.href.includes('/product-category/') ? link.href.split('/product-category/')[1].replace('/', '') : null);
		if (categorySlug) {
			trackAmeEvent('category_click', {
				category: categorySlug,
				text: element.innerText.trim()
			});
			return;
		}

		// 5. Product Clicks
		const isProductLink = link && (link.href.includes('/product/') || link.closest('.ame-product-card') || link.closest('.product'));
		if (isProductLink && !link.href.includes('wa.me') && !link.href.startsWith('tel:')) {
			trackAmeEvent('product_click', {
				href: link.href,
				text: link.innerText.trim() || link.closest('.ame-product-card')?.querySelector('.ame-product-title')?.innerText?.trim() || 'Product Card'
			});
			return;
		}

		// 6. AI Advisor Interactions (data-ai-action matches)
		const aiAction = element.getAttribute('data-ai-action');
		if (aiAction) {
			trackAmeEvent('ai_advisor_interaction', {
				action_type: aiAction,
				text: element.innerText.trim()
			});
			return;
		}

		// 7. Generic CTA Clicks
		const isCta = element.hasAttribute('data-ame-cta') || 
		              element.classList.contains('ame-bazaar-btn') || 
		              element.classList.contains('ame-btn-primary') || 
		              element.classList.contains('ame-btn-secondary') || 
		              element.classList.contains('button') || 
		              element.getAttribute('role') === 'button';

		if (isCta) {
			trackAmeEvent('cta_click', {
				cta_label: element.innerText.trim() || element.value || element.ariaLabel || 'Unnamed CTA',
				element_id: element.id || null,
				element_class: element.className || null
			});
		}
	});
})();
