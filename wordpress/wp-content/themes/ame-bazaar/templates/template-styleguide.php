<?php
/**
 * Template Name: Design Style Guide
 *
 * Description: Living Style Guide and Reusable Design System showcase for AME Bazaar.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="ame-styleguide-wrapper">
	<div class="ame-bazaar-container">
		
		<!-- Style Guide Header -->
		<header class="ame-styleguide-hero">
			<h1 class="ame-styleguide-title"><?php esc_html_e( 'AME Bazaar Design System', 'ame-bazaar' ); ?></h1>
			<p class="ame-styleguide-subtitle"><?php esc_html_e( 'Global reusable components, utility classes, and typography tokens for production.', 'ame-bazaar' ); ?></p>
		</header>

		<!-- 3. TYPOGRAPHY SYSTEM -->
		<section class="ame-sg-section" aria-labelledby="sg-typography-title">
			<h2 id="sg-typography-title" class="ame-sg-section-title"><?php esc_html_e( '3. Typography System', 'ame-bazaar' ); ?></h2>
			<div class="ame-sg-card">
				<div class="ame-sg-item">
					<span class="ame-sg-label">H1 Heading (.ame-h1)</span>
					<h1 class="ame-h1">H1 Heading - AME Bazaar Saree Collection</h1>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">H2 Heading (.ame-h2)</span>
					<h2 class="ame-h2">H2 Heading - Premium Tailoring Service</h2>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">H3 Heading (.ame-h3)</span>
					<h3 class="ame-h3">H3 Heading - Men's Kurta & Shirts</h3>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">H4 Heading (.ame-h4)</span>
					<h4 class="ame-h4">H4 Heading - Kids Festive Wear</h4>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">H5 Heading (.ame-h5)</span>
					<h5 class="ame-h5">H5 Heading - Leather Accessories</h5>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">H6 Heading (.ame-h6)</span>
					<h6 class="ame-h6">H6 Heading - Sizing & Alteration FAQs</h6>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">Body Text (.ame-body)</span>
					<p class="ame-body">Located on Mubarakpur Road in Kirari, Delhi, AME Bazaar is dedicated to providing high-quality garments for your entire family. We offer premium Men's Wear, Women's Wear, Kids' Wear, Sarees, and fashion Accessories.</p>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">Small Text (.ame-small)</span>
					<p class="ame-small">Shipping weight is calculated during checkout. Handloomed sarees require gentle dry clean only to maintain texture.</p>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">Caption (.ame-caption)</span>
					<span class="ame-caption">* Image displays handloom silk embroidery stitch detail.</span>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">Label (.ame-label)</span>
					<label class="ame-label-style">Enter Saree Length (meters):</label>
				</div>
				<div class="ame-sg-item">
					<span class="ame-sg-label">Link Style (.ame-link)</span>
					<a href="#" class="ame-link">Explore custom tailoring fitting services &rarr;</a>
				</div>
			</div>
		</section>

		<!-- 1. BUTTONS -->
		<section class="ame-sg-section" aria-labelledby="sg-buttons-title">
			<h2 id="sg-buttons-title" class="ame-sg-section-title"><?php esc_html_e( '1. Reusable Buttons', 'ame-bazaar' ); ?></h2>
			<div class="ame-sg-card">
				<div class="ame-btn-showcase-grid">
					
					<div class="ame-sg-item">
						<span class="ame-sg-label">Primary Button (.ame-btn-primary)</span>
						<button class="ame-btn-primary">Shop Collection</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Secondary Button (.ame-btn-secondary)</span>
						<button class="ame-btn-secondary">Visit Store</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Outline Button (.ame-btn-outline)</span>
						<button class="ame-btn-outline">Inquire Sizing</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Ghost Button (.ame-btn-ghost)</span>
						<button class="ame-btn-ghost">View Cart</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Icon Button (.ame-btn-icon)</span>
						<button class="ame-btn-icon" aria-label="Favorite">
							<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
							</svg>
						</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Loading State (.ame-btn-loading)</span>
						<button class="ame-btn-primary ame-btn-loading" aria-busy="true" disabled>
							<svg class="ame-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
								<circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.2)"></circle>
								<path d="M4 12a8 8 0 0 1 8-8" stroke="currentColor"></path>
							</svg>
							<span>Saving...</span>
						</button>
					</div>

					<div class="ame-sg-item">
						<span class="ame-sg-label">Disabled State (:disabled)</span>
						<button class="ame-btn-primary" aria-disabled="true" disabled>Out of Stock</button>
					</div>

				</div>
			</div>
		</section>

		<!-- 2. CARDS -->
		<section class="ame-sg-section" aria-labelledby="sg-cards-title">
			<h2 id="sg-cards-title" class="ame-sg-section-title"><?php esc_html_e( '2. Reusable Cards', 'ame-bazaar' ); ?></h2>
			<div class="ame-grid ame-grid-3">
				
				<!-- Product Card -->
				<article class="ame-product-card">
					<div class="ame-product-visual-wrap">
						<div class="ame-product-img-placeholder">
							<span class="ame-placeholder-tag"><?php esc_html_e( 'Product Mock', 'ame-bazaar' ); ?></span>
						</div>
						<div class="ame-product-hover-actions">
							<button class="ame-product-action-btn" aria-label="Add to Cart">
								<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path></svg>
							</button>
						</div>
					</div>
					<div class="ame-product-info">
						<span class="ame-badge-sale">Sale</span>
						<h3 class="ame-product-title"><a href="#">Pure Silk Brocade Saree</a></h3>
						<div class="ame-product-price">
							<span class="price">&#8377;4,499</span>
						</div>
					</div>
				</article>

				<!-- Blog Card -->
				<article class="ame-blog-card">
					<div class="ame-blog-card-visual-wrap">
						<div class="ame-blog-img-placeholder">
							<span class="ame-placeholder-tag"><?php esc_html_e( 'Journal', 'ame-bazaar' ); ?></span>
						</div>
					</div>
					<div class="ame-blog-card-content">
						<span class="ame-blog-card-date">July 2, 2026</span>
						<h3 class="ame-blog-card-title"><a href="#">How to choose fabrics for Delhi summers</a></h3>
						<p class="ame-blog-card-excerpt">Learn about the breathability of mulmul cotton, pure linen, and fine counts.</p>
						<a href="#" class="ame-blog-card-link"><?php esc_html_e( 'Read More', 'ame-bazaar' ); ?> &rarr;</a>
					</div>
				</article>

				<!-- Feature Card -->
				<article class="ame-feature-card">
					<div class="ame-feature-icon-wrap">
						<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
					</div>
					<h3 class="ame-feature-title">On-site Custom Tailoring</h3>
					<p class="ame-feature-desc">Our master tailors ensure every ethnic coordinate and shirt fit perfectly before you head home.</p>
				</article>

			</div>
		</section>

		<!-- 4. FORM COMPONENTS -->
		<section class="ame-sg-section" aria-labelledby="sg-forms-title">
			<h2 id="sg-forms-title" class="ame-sg-section-title"><?php esc_html_e( '4. Form Components', 'ame-bazaar' ); ?></h2>
			<div class="ame-sg-card">
				<form class="ame-sg-form-layout" onsubmit="event.preventDefault();">
					
					<div class="ame-grid ame-grid-2">
						<div class="ame-sg-item">
							<label for="input-text" class="ame-label-style">Input Text</label>
							<input type="text" id="input-text" class="ame-input-field" placeholder="Enter your full name..." />
						</div>

						<div class="ame-sg-item">
							<label for="input-search" class="ame-label-style">Search Field</label>
							<input type="search" id="input-search" class="ame-search-field" placeholder="Search store..." />
						</div>

						<div class="ame-sg-item">
							<label for="select-cat" class="ame-label-style">Select Box</label>
							<select id="select-cat" class="ame-select-field">
								<option value="">Select category...</option>
								<option>Men's Wear</option>
								<option>Women's Wear</option>
								<option>Tailoring Service</option>
							</select>
						</div>

						<div class="ame-sg-item">
							<label for="input-validation" class="ame-label-style">Validation Error State</label>
							<input type="email" id="input-validation" class="ame-input-field ame-input-error" value="invalid-email@" />
							<span class="ame-validation-error" role="alert">Please enter a valid email address.</span>
						</div>
					</div>

					<div class="ame-sg-item">
						<label for="input-textarea" class="ame-label-style">Textarea (Special Instructions)</label>
						<textarea id="input-textarea" class="ame-textarea-field" rows="3" placeholder="Describe tailoring measurement specifications..."></textarea>
					</div>

					<div class="ame-grid ame-grid-3">
						<div class="ame-checkbox-wrap">
							<input type="checkbox" id="check-saree" class="ame-checkbox" />
							<label for="check-saree" class="ame-checkbox-label">Request fall & picot stitching</label>
						</div>

						<div class="ame-radio-wrap">
							<input type="radio" name="delivery" id="radio-delivery" class="ame-radio" checked />
							<label for="radio-delivery" class="ame-radio-label">In-store pickup</label>
						</div>

						<div class="ame-toggle-wrap">
							<label class="ame-toggle-switch">
								<input type="checkbox" class="ame-toggle-input" />
								<span class="ame-toggle-slider"></span>
							</label>
							<span class="ame-toggle-label">Subscribe to WhatsApp updates</span>
						</div>
					</div>

				</form>
			</div>
		</section>

		<!-- 5. BADGES & 6. ALERTS -->
		<section class="ame-sg-section">
			<div class="ame-grid ame-grid-2">
				
				<!-- Badges Column -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '5. Product Badges', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card">
						<div class="ame-badge-row">
							<span class="ame-badge-sale">Sale</span>
							<span class="ame-badge-new">New</span>
							<span class="ame-badge-bestseller">Bestseller</span>
							<span class="ame-badge-outofstock">Out of Stock</span>
							<span class="ame-badge-limited">Limited Stock</span>
						</div>
					</div>
				</div>

				<!-- Alerts Column -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '6. Status Alerts', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card ame-alert-stack">
						<div class="ame-alert ame-alert-success" role="alert">
							<svg class="ame-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
							<span>Your tailoring fitting appointment has been booked successfully!</span>
						</div>
						<div class="ame-alert ame-alert-warning" role="alert">
							<svg class="ame-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
							<span>Only 2 items left in stock for this fabric roll.</span>
						</div>
						<div class="ame-alert ame-alert-error" role="alert">
							<svg class="ame-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
							<span>Verification failed. Please enter a valid telephone number.</span>
						</div>
						<div class="ame-alert ame-alert-info" role="alert">
							<svg class="ame-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
							<span>Free delivery is available within a 5km radius of Kirari, Delhi.</span>
						</div>
					</div>
				</div>

			</div>
		</section>

		<!-- 7. LOADING COMPONENTS & 10. EMPTY STATE -->
		<section class="ame-sg-section">
			<div class="ame-grid ame-grid-2">
				
				<!-- Loading Components -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '7. Loading States & Skeletons', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card">
						<div class="ame-loading-showcase">
							
							<div class="ame-sg-item">
								<span class="ame-sg-label">Skeleton Text Line Loader</span>
								<div class="ame-skeleton ame-skeleton-text"></div>
								<div class="ame-skeleton ame-skeleton-text" style="width: 80%;"></div>
							</div>

							<div class="ame-sg-item">
								<span class="ame-sg-label">Product Placeholder Card</span>
								<div class="ame-product-placeholder">
									<div class="ame-skeleton ame-skeleton-rect" style="aspect-ratio: 1/1;"></div>
									<div class="ame-skeleton ame-skeleton-text" style="width: 60%; margin-top: 1rem;"></div>
									<div class="ame-skeleton ame-skeleton-text" style="width: 40%; margin-top: 0.5rem;"></div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- Empty State Components -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '10. Empty State', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card">
						<div class="ame-empty-state">
							<div class="ame-empty-icon-wrap">
								<svg class="ame-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
									<circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line>
								</svg>
							</div>
							<h3 class="ame-empty-title"><?php esc_html_e( 'No Items Found', 'ame-bazaar' ); ?></h3>
							<p class="ame-empty-desc"><?php esc_html_e( 'We couldn\'t find any fabrics matching your custom filter criteria. Please reset selection and try again.', 'ame-bazaar' ); ?></p>
							<button class="ame-btn-secondary"><?php esc_html_e( 'Reset Filters', 'ame-bazaar' ); ?></button>
						</div>
					</div>
				</div>

			</div>
		</section>

		<!-- 8. BREADCRUMB & 9. PAGINATION -->
		<section class="ame-sg-section">
			<h2 class="ame-sg-section-title"><?php esc_html_e( '8 & 9. Breadcrumb & Pagination', 'ame-bazaar' ); ?></h2>
			<div class="ame-sg-card">
				
				<div class="ame-sg-item" style="margin-bottom: 2rem;">
					<span class="ame-sg-label">Breadcrumb Navigation</span>
					<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>
				</div>

				<div class="ame-sg-item">
					<span class="ame-sg-label">Pagination</span>
					<nav class="ame-pagination" aria-label="Pagination Navigation">
						<ul class="ame-pagination-list">
							<li><a href="#" class="ame-pagination-link" aria-label="Previous Page">&laquo;</a></li>
							<li><a href="#" class="ame-pagination-link">1</a></li>
							<li><a href="#" class="ame-pagination-link ame-pagination-link-active" aria-current="page">2</a></li>
							<li><a href="#" class="ame-pagination-link">3</a></li>
							<li><a href="#" class="ame-pagination-link" aria-label="Next Page">&raquo;</a></li>
						</ul>
					</nav>
				</div>

			</div>
		</section>

		<!-- 11. MODAL, 12. DRAWER & 13. TOAST -->
		<section class="ame-sg-section">
			<h2 class="ame-sg-section-title"><?php esc_html_e( '11, 12 & 13. Modals, Drawers & Toast Triggers', 'ame-bazaar' ); ?></h2>
			<div class="ame-sg-card">
				<div class="ame-interactive-showcase-grid">
					
					<button class="ame-btn-primary" id="trigger-sg-modal">Open Custom Modal</button>
					<button class="ame-btn-secondary" id="trigger-sg-drawer">Open Custom Drawer</button>
					<button class="ame-btn-outline" id="trigger-sg-toast-success">Trigger Success Toast</button>
					<button class="ame-btn-outline" id="trigger-sg-toast-error">Trigger Error Toast</button>

				</div>
			</div>
		</section>

		<!-- 14. ACCORDION & 15. TABS -->
		<section class="ame-sg-section">
			<div class="ame-grid ame-grid-2">
				
				<!-- Accordion -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '14. Accessible Accordion', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card">
						<div class="ame-accordion">
							
							<div class="ame-accordion-item">
								<button class="ame-accordion-header" id="acc-header-1" aria-expanded="false" aria-controls="acc-panel-1">
									<span>Is tailoring support available in-store?</span>
									<svg class="ame-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</button>
								<div class="ame-accordion-panel" id="acc-panel-1" aria-labelledby="acc-header-1" role="region" hidden>
									<div class="ame-accordion-content">
										<p>Yes, Apparel Maheshwari Enterprises offers on-site measurements, alterations, and custom tailoring services for all coordinates, sarees, and suits purchased at our Kirari store.</p>
									</div>
								</div>
							</div>

							<div class="ame-accordion-item">
								<button class="ame-accordion-header" id="acc-header-2" aria-expanded="false" aria-controls="acc-panel-2">
									<span>What are your business hours?</span>
									<svg class="ame-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</button>
								<div class="ame-accordion-panel" id="acc-panel-2" aria-labelledby="acc-header-2" role="region" hidden>
									<div class="ame-accordion-content">
										<p>We are open Monday to Sunday from 10:00 AM to 9:00 PM on Mubarakpur Road in Kirari, Delhi.</p>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- Tabs -->
				<div class="ame-sg-col">
					<h2 class="ame-sg-section-title"><?php esc_html_e( '15. Reusable Tabs System', 'ame-bazaar' ); ?></h2>
					<div class="ame-sg-card">
						<div class="ame-tabs">
							
							<div class="ame-tabs-list" role="tablist" aria-label="Store departments list">
								<button class="ame-tab-btn ame-tab-btn-active" id="tab-men" role="tab" aria-selected="true" aria-controls="panel-men">Men's Wear</button>
								<button class="ame-tab-btn" id="tab-women" role="tab" aria-selected="false" aria-controls="panel-women" tabindex="-1">Women's Wear</button>
								<button class="ame-tab-btn" id="tab-kids" role="tab" aria-selected="false" aria-controls="panel-kids" tabindex="-1">Kids Wear</button>
							</div>

							<div class="ame-tab-panel ame-tab-panel-active" id="panel-men" role="tabpanel" aria-labelledby="tab-men">
								<p>Premium kurtas, casual shirts, tailored coordinates, and formal apparel designed with premium breathable fabrics for everyday Delhi summers.</p>
							</div>

							<div class="ame-tab-panel" id="panel-women" role="tabpanel" aria-labelledby="tab-women" hidden>
								<p>Banarasi, Kanjeevaram, and handloomed coordinates, ethnic dresses, salwar suits, and designer wear crafted for elegance and premium style.</p>
							</div>

							<div class="ame-tab-panel" id="panel-kids" role="tabpanel" aria-labelledby="tab-kids" hidden>
								<p>Festival lehengas, soft cotton coordinates, playwear shirts, and skin-friendly fabric items for babies, toddlers, boys, and girls.</p>
							</div>

						</div>
					</div>
				</div>

			</div>
		</section>

	</div>
</div>

<!-- ==========================================================================
   11. STYLEGUIDE MODAL ELEMENT
   ========================================================================== -->
<div class="ame-modal" id="sg-demo-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="sg-modal-title">
	<div class="ame-modal-inner">
		<header class="ame-modal-header">
			<h3 id="sg-modal-title" class="ame-modal-title">Tailoring Booking Details</h3>
			<button class="ame-modal-close-btn" aria-label="Close Modal">&times;</button>
		</header>
		<div class="ame-modal-body">
			<p>Your custom measurement record has been successfully synced with Apparel Maheshwari Enterprises tailoring team. Please present your booking ID at the Mubarakpur Road desk during fitting.</p>
		</div>
		<footer class="ame-modal-footer">
			<button class="ame-btn-ghost ame-modal-close-btn">Cancel</button>
			<button class="ame-btn-primary">Confirm Appointment</button>
		</footer>
	</div>
	<div class="ame-modal-overlay"></div>
</div>

<!-- ==========================================================================
   12. STYLEGUIDE DRAWER ELEMENT
   ========================================================================== -->
<div class="ame-drawer ame-drawer-right" id="sg-demo-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="sg-drawer-title">
	<div class="ame-drawer-inner">
		<header class="ame-drawer-header">
			<h3 id="sg-drawer-title" class="ame-drawer-title">Shopping Cart (2)</h3>
			<button class="ame-drawer-close-btn" aria-label="Close Drawer">&times;</button>
		</header>
		<div class="ame-drawer-body">
			<ul class="ame-drawer-item-list" style="list-style:none; padding:0; margin:0;">
				<li style="border-bottom:1px solid var(--ame-color-border); padding-block:1rem; display:flex; gap:1rem;">
					<div style="width:60px; height:60px; background:var(--ame-color-cream); border-radius:4px;"></div>
					<div>
						<h4 style="font-size:0.9rem; margin:0 0 0.25rem 0;">Pure Cotton Kurta</h4>
						<span style="font-size:0.8rem; color:var(--ame-color-gold-dark); font-weight:700;">&#8377;1,499</span>
					</div>
				</li>
				<li style="padding-block:1rem; display:flex; gap:1rem;">
					<div style="width:60px; height:60px; background:var(--ame-color-cream); border-radius:4px;"></div>
					<div>
						<h4 style="font-size:0.9rem; margin:0 0 0.25rem 0;">Silk Saree</h4>
						<span style="font-size:0.8rem; color:var(--ame-color-gold-dark); font-weight:700;">&#8377;4,499</span>
					</div>
				</li>
			</ul>
		</div>
		<footer class="ame-drawer-footer" style="border-top:1px solid var(--ame-color-border); padding:1rem 0 0 0;">
			<div style="display:flex; justify-content:space-between; margin-bottom:1rem; font-weight:700;">
				<span>Subtotal:</span>
				<span>&#8377;5,998</span>
			</div>
			<button class="ame-btn-primary" style="width:100%;">Proceed to checkout</button>
		</footer>
	</div>
	<div class="ame-drawer-overlay"></div>
</div>

<!-- ==========================================================================
   13. TOAST NOTIFICATION CONTAINER
   ========================================================================== -->
<div class="ame-toast-container" id="ame-global-toast-container"></div>

<?php
get_footer();
