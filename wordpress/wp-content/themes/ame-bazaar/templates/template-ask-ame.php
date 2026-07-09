<?php
/**
 * Template Name: Internal AI Search (Ask AME)
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Fetch 220 structured FAQs
$grouped_faqs = ame_bazaar_get_knowledge_base_faqs();
?>

<main id="primary" class="site-main ame-ask-ame-main-shell" role="main" style="background: #fafaf9; min-height: 90vh;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<div class="ame-bazaar-container ame-bazaar-section" style="padding-top: 2rem;">
		
		<!-- Search Header -->
		<div class="ame-search-hero-header" style="text-align: center; margin-bottom: 3.5rem;">
			<span class="ame-search-badge" style="background: var(--ame-color-navy); color: #ffffff; padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1rem;">
				<?php esc_html_e( 'Semantic Search Engine', 'ame-bazaar' ); ?>
			</span>
			<h1 class="ame-search-title" style="font-size: clamp(2rem, 5vw, 3.25rem); font-weight: 800; color: var(--ame-color-navy); margin: 0 0 1rem; letter-spacing: -0.02em;"><?php esc_html_e( 'Ask AME Bazaar', 'ame-bazaar' ); ?></h1>
			<p class="ame-search-desc" style="max-width: 600px; margin-inline: auto; font-size: 1.1rem; color: #475569; line-height: 1.6;">
				<?php esc_html_e( 'Query our complete index of product lines, store policies, custom alterations, and local Delhi shopping guides.', 'ame-bazaar' ); ?>
			</p>
		</div>

		<!-- Search Input Container -->
		<div class="ame-search-console-wrapper" style="max-width: 750px; margin: 0 auto 4rem; display: flex; flex-direction: column; gap: 2rem;">
			
			<div class="ame-search-bar-wrap" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); box-shadow: var(--ame-shadow-md); padding: 1.5rem 2rem;">
				<form id="ame-search-form" onsubmit="event.preventDefault(); window.ameRunSemanticSearch();" style="display: flex; gap: 0.75rem;">
					<input type="text" id="ame-search-query-input" placeholder="<?php esc_attr_e( 'Search matching pants, returns policy, parking, store timings...', 'ame-bazaar' ); ?>" style="width: 100%; padding: 1rem 1.5rem; border-radius: var(--ame-radius-sm); border: 2px solid var(--ame-color-border); outline: none; font-size: 1rem; transition: border-color 0.2s ease;" />
					<button type="submit" class="ame-bazaar-btn ame-bazaar-btn--primary" style="padding-inline: 2rem; border-radius: var(--ame-radius-sm);">
						<?php esc_html_e( 'Search', 'ame-bazaar' ); ?>
					</button>
				</form>
				
				<!-- Prompt Chips -->
				<div class="ame-search-preset-chips" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1.25rem; align-items: center;">
					<span style="font-size: 0.85rem; font-weight: 700; color: #64748b;"><?php esc_html_e( 'Quick Searches:', 'ame-bazaar' ); ?></span>
					<button onclick="window.amePresetSearch('Returns Policy')" style="background: #f1f5f9; border: none; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 999px; color: #475569; cursor: pointer; transition: all 0.2s ease;"><?php esc_html_e( 'Returns Policy', 'ame-bazaar' ); ?></button>
					<button onclick="window.amePresetSearch('Where to park')" style="background: #f1f5f9; border: none; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 999px; color: #475569; cursor: pointer; transition: all 0.2s ease;"><?php esc_html_e( 'Where to park', 'ame-bazaar' ); ?></button>
					<button onclick="window.amePresetSearch('Sleeve alteration')" style="background: #f1f5f9; border: none; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 999px; color: #475569; cursor: pointer; transition: all 0.2s ease;"><?php esc_html_e( 'Sleeve alteration', 'ame-bazaar' ); ?></button>
					<button onclick="window.amePresetSearch('Metro station landmark')" style="background: #f1f5f9; border: none; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 999px; color: #475569; cursor: pointer; transition: all 0.2s ease;"><?php esc_html_e( 'Metro Landmark', 'ame-bazaar' ); ?></button>
				</div>
			</div>

			<!-- Dynamic Results Container -->
			<div id="ame-search-results-root" style="display: flex; flex-direction: column; gap: 1.5rem;">
				
				<!-- Idle / Welcome State -->
				<div class="ame-search-idle-card" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 3rem 2rem; text-align: center; color: #64748b; box-shadow: var(--ame-shadow-sm);">
					<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 1rem; color: var(--ame-color-gold);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					<h3 style="margin: 0 0 0.5rem; font-size: 1.15rem; font-weight: 700; color: var(--ame-color-navy);"><?php esc_html_e( 'Factual Search Ready', 'ame-bazaar' ); ?></h3>
					<p style="margin: 0; font-size: 0.95rem; line-height: 1.5;"><?php esc_html_e( 'Enter a natural language search query above to look up sizing metrics, custom stitch options, pricing guides, and brand policies.', 'ame-bazaar' ); ?></p>
				</div>

			</div>

		</div>

	</div>
</main>

<script>
// Load 220 FAQs locally for client-side search simulation
window.ameSearchDb = <?php echo json_encode( $grouped_faqs ); ?>;

window.amePresetSearch = function(queryText) {
	document.getElementById('ame-search-query-input').value = queryText;
	window.ameRunSemanticSearch();
};

window.ameRunSemanticSearch = function() {
	const query = document.getElementById('ame-search-query-input').value.trim().toLowerCase();
	const resultsRoot = document.getElementById('ame-search-results-root');
	if (!query) return;

	// Show loading skeleton card
	resultsRoot.innerHTML = `
		<div style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 2rem; box-shadow: var(--ame-shadow-sm); text-align: center; color: var(--ame-color-navy); font-weight: 700;">
			<span style="display: inline-block; animation: ameSpin 1s linear infinite; margin-right: 0.5rem;">↻</span> Searching AME database...
		</div>
	`;

	// Filter matches
	const matchedFaqs = [];
	const queryWords = query.split(/\s+/).filter(w => w.length > 2);

	for (const groupKey in window.ameSearchDb) {
		const faqs = window.ameSearchDb[groupKey].faqs;
		for (const faq of faqs) {
			const qText = faq.q.toLowerCase();
			const aText = faq.a.toLowerCase();
			let score = 0;

			if (qText.includes(query)) score += 10;
			for (const word of queryWords) {
				if (qText.includes(word)) score += 3;
				if (aText.includes(word)) score += 1;
			}

			if (score > 1) {
				matchedFaqs.push({
					q: faq.q,
					a: faq.a,
					category: window.ameSearchDb[groupKey].title,
					score: score
				});
			}
		}
	}

	// Sort by matching score descending
	matchedFaqs.sort((a, b) => b.score - a.score);

	setTimeout(function() {
		resultsRoot.innerHTML = '';

		if (matchedFaqs.length === 0) {
			resultsRoot.innerHTML = `
				<div style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 3rem 2rem; text-align: center; color: #64748b; box-shadow: var(--ame-shadow-sm);">
					<h3 style="margin: 0 0 0.5rem; font-size: 1.15rem; font-weight: 700; color: #b91c1c;">No direct matching answers found</h3>
					<p style="margin: 0 0 1.5rem; font-size: 0.95rem;">Try using broader terms or connect with our support desk on WhatsApp for assistance.</p>
					<a href="https://wa.me/919953569533" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn ame-bazaar-btn--primary">WhatsApp Support Desk</a>
				</div>
			`;
			return;
		}

		// Output matching results (limit to top 5)
		const topMatches = matchedFaqs.slice(0, 5);
		topMatches.forEach(match => {
			const card = document.createElement('div');
			card.className = 'ame-search-result-card';
			card.style.background = '#ffffff';
			card.style.border = '1px solid var(--ame-color-border)';
			card.style.borderRadius = 'var(--ame-radius-md)';
			card.style.padding = '2rem';
			card.style.boxShadow = 'var(--ame-shadow-sm)';
			card.style.transition = 'all 0.2s ease';
			card.innerHTML = `
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
					<span style="font-size: 0.75rem; font-weight: 700; color: var(--ame-color-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfaf2; padding: 0.25rem 0.75rem; border-radius: 999px;">
						Topic: ${match.category}
					</span>
					<span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Match Confidence: High</span>
				</div>
				<h3 style="margin: 0 0 0.75rem; font-size: 1.15rem; font-weight: 800; color: var(--ame-color-navy);">${match.q}</h3>
				<p style="margin: 0 0 1.5rem; font-size: 0.95rem; line-height: 1.6; color: #334155;">${match.a}</p>
				
				<div style="display: flex; flex-wrap: wrap; gap: 0.5rem; border-top: 1px solid var(--ame-color-border); padding-top: 1rem;">
					<a href="https://wa.me/919953569533?text=Inquiry%20about%20${encodeURIComponent(match.q)}" target="_blank" rel="noopener noreferrer" style="background: #25D366; color: white; padding: 0.4rem 0.8rem; border-radius: var(--ame-radius-sm); font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
						WhatsApp Ask
					</a>
					<a href="https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi" target="_blank" rel="noopener noreferrer" style="background: var(--ame-color-navy); color: white; padding: 0.4rem 0.8rem; border-radius: var(--ame-radius-sm); font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
						Store Address Map
					</a>
				</div>
			`;
			resultsRoot.appendChild(card);
		});
	}, 400);
};
</script>

<style>
@keyframes ameSpin {
	from { transform: rotate(0deg); }
	to { transform: rotate(360deg); }
}

.ame-search-result-card:hover {
	border-color: var(--ame-color-gold) !important;
	transform: translateY(-2px);
	box-shadow: var(--ame-shadow-md);
}

#ame-search-query-input:focus {
	border-color: var(--ame-color-navy) !important;
	box-shadow: 0 0 0 3px rgba(0, 35, 71, 0.1);
}
</style>

<?php
get_footer();
