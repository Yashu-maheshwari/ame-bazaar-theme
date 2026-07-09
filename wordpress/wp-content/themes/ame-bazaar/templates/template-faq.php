<?php
/**
 * Template Name: FAQ Page Template
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Fetch FAQ categories and questions
if ( function_exists( 'ame_bazaar_get_knowledge_base_faqs' ) ) {
	$faq_categories = ame_bazaar_get_knowledge_base_faqs();
} else {
	$faq_categories = array();
}
?>

<main id="primary" class="site-main ame-faq-page-main" role="main" style="background: #fafaf9; padding-bottom: 5rem;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<!-- Hero Header -->
	<header class="ame-faq-hero-header" style="background: var(--ame-color-navy); color: #ffffff; padding: 5rem 0; border-bottom: 3px solid var(--ame-color-gold); text-align: center;">
		<div class="ame-bazaar-container">
			<span style="background: rgba(255,255,255,0.1); color: var(--ame-color-gold); padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1.25rem;">
				<?php esc_html_e( 'Help & Information', 'ame-bazaar' ); ?>
			</span>
			<h1 class="entry-title" style="font-size: clamp(2.25rem, 5vw, 3.5rem); font-weight: 800; margin: 0 0 1rem; letter-spacing: -0.02em;"><?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?></h1>
			<p style="max-width: 650px; margin-inline: auto; font-size: 1.15rem; opacity: 0.9; line-height: 1.6;"><?php esc_html_e( 'Find instant answers regarding clothing availability, store timings, custom tailoring, exchanges, and directions.', 'ame-bazaar' ); ?></p>
		</div>
	</header>

	<div class="ame-bazaar-container" style="margin-top: 3rem;">
		<div class="ame-faq-layout" style="max-width: 900px; margin-inline: auto;">
			
			<!-- Interactive Live FAQ Filter Box -->
			<div class="ame-faq-search-wrapper" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 1.5rem; margin-bottom: 2.5rem; box-shadow: var(--ame-shadow-sm);">
				<input type="text" id="ame-faq-search-input" placeholder="<?php esc_attr_e( 'Type keywords (e.g. tailoring, parking, hours, sarees)...', 'ame-bazaar' ); ?>" style="width: 100%; height: 48px; font-size: 1rem; padding: 0 1.25rem; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-sm); outline: none; transition: border-color 0.2s;" />
			</div>

			<!-- FAQ Categories Navigation Chips -->
			<div class="ame-faq-chips-container" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 2.5rem; justify-content: center;">
				<button class="ame-faq-chip active" data-target="all" style="background: var(--ame-color-navy); color: #ffffff; border: 1px solid var(--ame-color-navy); padding: 0.5rem 1.25rem; border-radius: 999px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
					<?php esc_html_e( 'All Topics', 'ame-bazaar' ); ?>
				</button>
				<?php foreach ( $faq_categories as $key => $cat ) : ?>
					<button class="ame-faq-chip" data-target="<?php echo esc_attr( $key ); ?>" style="background: #ffffff; color: var(--ame-color-navy); border: 1px solid var(--ame-color-border); padding: 0.5rem 1.25rem; border-radius: 999px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
						<?php echo esc_html( $cat['title'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- FAQ Accordion Blocks -->
			<div class="ame-faq-categories-list">
				<?php 
				if ( ! empty( $faq_categories ) ) :
					foreach ( $faq_categories as $key => $cat ) : 
				?>
					<div class="ame-faq-category-section" id="faq-cat-<?php echo esc_attr( $key ); ?>" data-category="<?php echo esc_attr( $key ); ?>" style="margin-bottom: 3rem;">
						<h2 style="font-size: 1.4rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid var(--ame-color-gold); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
							<span><?php echo esc_html( $cat['title'] ); ?></span>
							<span style="font-size: 0.85rem; font-weight: 600; background: var(--ame-color-cream); color: var(--ame-color-navy); padding: 0.2rem 0.6rem; border-radius: 999px; margin-left: auto;">
								<?php echo count( $cat['faqs'] ); ?> <?php esc_html_e( 'Questions', 'ame-bazaar' ); ?>
							</span>
						</h2>

						<div class="ame-faq-accordion-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
							<?php foreach ( $cat['faqs'] as $faq_index => $faq ) : ?>
								<details class="ame-faq-item" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-sm); overflow: hidden; transition: border-color 0.2s; cursor: pointer;">
									<summary style="font-weight: 700; color: var(--ame-color-navy); padding: 1.15rem 1.5rem; display: flex; justify-content: space-between; align-items: center; list-style: none; outline: none; user-select: none;">
										<span style="padding-right: 1.5rem; line-height: 1.4;"><?php echo esc_html( $faq['q'] ); ?></span>
										<span class="ame-faq-arrow" style="font-size: 0.95rem; font-weight: 400; transition: transform 0.2s;">▼</span>
									</summary>
									<div class="ame-faq-answer" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ame-color-border); background: #fcfcfb; color: #475569; line-height: 1.6; font-size: 0.95rem;">
										<?php echo esc_html( $faq['a'] ); ?>
									</div>
								</details>
							<?php endforeach; ?>
						</div>
					</div>
				<?php 
					endforeach;
				else :
				?>
					<div style="text-align: center; padding: 4rem 2rem; color: #64748b; background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md);">
						<p><?php esc_html_e( 'No FAQ data is currently synchronized.', 'ame-bazaar' ); ?></p>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const searchInput = document.getElementById('ame-faq-search-input');
	const chips = document.querySelectorAll('.ame-faq-chip');
	const catSections = document.querySelectorAll('.ame-faq-category-section');
	const faqItems = document.querySelectorAll('.ame-faq-item');

	// FAQ Expand/Collapse Indicator
	faqItems.forEach(item => {
		const summary = item.querySelector('summary');
		const arrow = item.querySelector('.ame-faq-arrow');
		item.addEventListener('toggle', () => {
			if (item.open) {
				arrow.style.transform = 'rotate(180deg)';
				item.style.borderColor = 'var(--ame-color-gold)';
			} else {
				arrow.style.transform = 'rotate(0deg)';
				item.style.borderColor = 'var(--ame-color-border)';
			}
		});
	});

	// Filtering by Category Chip
	chips.forEach(chip => {
		chip.addEventListener('click', function() {
			chips.forEach(c => {
				c.classList.remove('active');
				c.style.background = '#ffffff';
				c.style.color = 'var(--ame-color-navy)';
				c.style.borderColor = 'var(--ame-color-border)';
			});
			this.classList.add('active');
			this.style.background = 'var(--ame-color-navy)';
			this.style.color = '#ffffff';
			this.style.borderColor = 'var(--ame-color-navy)';

			const target = this.getAttribute('data-target');
			catSections.forEach(section => {
				if (target === 'all' || section.getAttribute('data-category') === target) {
					section.style.display = 'block';
				} else {
					section.style.display = 'none';
				}
			});
		});
	});

	// Live Text Keyword Search Filter
	searchInput.addEventListener('input', function() {
		const query = this.value.toLowerCase().trim();
		
		// If query is empty, show all items and respect current active category chip
		if (query === '') {
			const activeChip = document.querySelector('.ame-faq-chip.active');
			const activeTarget = activeChip ? activeChip.getAttribute('data-target') : 'all';
			
			catSections.forEach(section => {
				if (activeTarget === 'all' || section.getAttribute('data-category') === activeTarget) {
					section.style.display = 'block';
				} else {
					section.style.display = 'none';
				}
				section.querySelectorAll('.ame-faq-item').forEach(item => {
					item.style.display = 'block';
					item.open = false;
				});
			});
			return;
		}

		// Search filtering
		catSections.forEach(section => {
			let sectionHasMatches = false;
			section.querySelectorAll('.ame-faq-item').forEach(item => {
				const qText = item.querySelector('summary span').textContent.toLowerCase();
				const aText = item.querySelector('.ame-faq-answer').textContent.toLowerCase();
				
				if (qText.includes(query) || aText.includes(query)) {
					item.style.display = 'block';
					item.open = true; // Auto-expand matches
					sectionHasMatches = true;
				} else {
					item.style.display = 'none';
					item.open = false;
				}
			});

			if (sectionHasMatches) {
				section.style.display = 'block';
			} else {
				section.style.display = 'none';
			}
		});
	});
});
</script>

<?php
get_footer();
