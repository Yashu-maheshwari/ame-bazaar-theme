<?php
/**
 * Template Name: AI Fashion Advisor
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

<main id="primary" class="site-main ame-ai-advisor-main-shell" role="main" style="background: #fafaf9; min-height: 90vh;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<div class="ame-bazaar-container ame-bazaar-section" style="padding-top: 2rem;">
		
		<!-- Advisor Header -->
		<div class="ame-advisor-hero-header" style="text-align: center; margin-bottom: 3rem;">
			<span class="ame-advisor-tag" style="background: var(--ame-color-navy); color: #ffffff; padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1rem;">
				<?php esc_html_e( 'AI Fashion Advisor', 'ame-bazaar' ); ?>
			</span>
			<h1 class="ame-advisor-title" style="font-size: clamp(2rem, 5vw, 3.25rem); font-weight: 800; color: var(--ame-color-navy); margin: 0 0 1rem; letter-spacing: -0.02em;"><?php esc_html_e( 'Find Your Perfect Fit', 'ame-bazaar' ); ?></h1>
			<p class="ame-advisor-desc" style="max-width: 600px; margin-inline: auto; font-size: 1.1rem; color: #475569; line-height: 1.6;">
				<?php esc_html_e( 'Ask about custom tailoring fits, matching colors, wedding fabrics, or budget shopping. Our AI advisor searches our official local retail knowledge base.', 'ame-bazaar' ); ?>
			</p>
		</div>

		<!-- Chat Layout Grid -->
		<div class="ame-advisor-layout-grid" style="display: grid; grid-template-columns: 1fr; gap: 2.5rem; max-width: 1100px; margin: 0 auto 5rem;">
			
			<!-- Left Column: Preference Selector Panel -->
			<div class="ame-advisor-preferences-panel" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 1.75rem; box-shadow: var(--ame-shadow-sm); display: flex; flex-direction: column; gap: 1.25rem; height: fit-content;">
				<h3 style="font-size: 1.15rem; font-weight: 800; color: var(--ame-color-navy); margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid var(--ame-color-gold); padding-bottom: 0.5rem;">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--ame-color-gold);"><circle cx="4" cy="4" r="2"></circle><circle cx="12" cy="4" r="2"></circle><circle cx="20" cy="4" r="2"></circle><circle cx="4" cy="12" r="2"></circle><circle cx="12" cy="12" r="2"></circle><circle cx="20" cy="12" r="2"></circle><circle cx="4" cy="20" r="2"></circle><circle cx="12" cy="20" r="2"></circle><circle cx="20" cy="20" r="2"></circle></svg>
					<span><?php esc_html_e( 'Assistant Config', 'ame-bazaar' ); ?></span>
				</h3>
				
				<!-- Gender Selector -->
				<div>
					<label style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.35rem; text-transform: uppercase;"><?php esc_html_e( 'Target Gender', 'ame-bazaar' ); ?></label>
					<select id="ame-pref-gender" class="ame-pref-select" style="width: 100%; padding: 0.6rem; border-radius: var(--ame-radius-sm); border: 1px solid var(--ame-color-border); background: #f8fafc; font-size: 0.85rem; font-weight: 600; color: #334155;">
						<option value="unisex"><?php esc_html_e( 'Unisex / All', 'ame-bazaar' ); ?></option>
						<option value="men"><?php esc_html_e( 'Men\'s Wear', 'ame-bazaar' ); ?></option>
						<option value="women"><?php esc_html_e( 'Women\'s Wear', 'ame-bazaar' ); ?></option>
						<option value="kids"><?php esc_html_e( 'Kids & Infant', 'ame-bazaar' ); ?></option>
					</select>
				</div>

				<!-- Age Selector -->
				<div>
					<label style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.35rem; text-transform: uppercase;"><?php esc_html_e( 'Age Segment', 'ame-bazaar' ); ?></label>
					<select id="ame-pref-age" class="ame-pref-select" style="width: 100%; padding: 0.6rem; border-radius: var(--ame-radius-sm); border: 1px solid var(--ame-color-border); background: #f8fafc; font-size: 0.85rem; font-weight: 600; color: #334155;">
						<option value="adult"><?php esc_html_e( 'Adult (15-60y)', 'ame-bazaar' ); ?></option>
						<option value="infant"><?php esc_html_e( 'Infant (0-2y)', 'ame-bazaar' ); ?></option>
						<option value="kids"><?php esc_html_e( 'Child (3-14y)', 'ame-bazaar' ); ?></option>
						<option value="senior"><?php esc_html_e( 'Senior (60y+)', 'ame-bazaar' ); ?></option>
					</select>
				</div>

				<!-- Occasion Selector -->
				<div>
					<label style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.35rem; text-transform: uppercase;"><?php esc_html_e( 'Occasion Type', 'ame-bazaar' ); ?></label>
					<select id="ame-pref-occasion" class="ame-pref-select" style="width: 100%; padding: 0.6rem; border-radius: var(--ame-radius-sm); border: 1px solid var(--ame-color-border); background: #f8fafc; font-size: 0.85rem; font-weight: 600; color: #334155;">
						<option value="wedding"><?php esc_html_e( 'Wedding / Ceremony', 'ame-bazaar' ); ?></option>
						<option value="festival"><?php esc_html_e( 'Festival Shopping', 'ame-bazaar' ); ?></option>
						<option value="casual"><?php esc_html_e( 'Casual Daily', 'ame-bazaar' ); ?></option>
						<option value="formal"><?php esc_html_e( 'Office Formal', 'ame-bazaar' ); ?></option>
					</select>
				</div>

				<!-- Season Selector -->
				<div>
					<label style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.35rem; text-transform: uppercase;"><?php esc_html_e( 'Season / Fabric', 'ame-bazaar' ); ?></label>
					<select id="ame-pref-season" class="ame-pref-select" style="width: 100%; padding: 0.6rem; border-radius: var(--ame-radius-sm); border: 1px solid var(--ame-color-border); background: #f8fafc; font-size: 0.85rem; font-weight: 600; color: #334155;">
						<option value="summer"><?php esc_html_e( 'Summer (Mulmul Cotton)', 'ame-bazaar' ); ?></option>
						<option value="winter"><?php esc_html_e( 'Winter Layers', 'ame-bazaar' ); ?></option>
						<option value="monsoon"><?php esc_html_e( 'Monsoon Wear', 'ame-bazaar' ); ?></option>
					</select>
				</div>

				<!-- Budget Selector -->
				<div>
					<label style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.35rem; text-transform: uppercase;"><?php esc_html_e( 'Budget Limit', 'ame-bazaar' ); ?></label>
					<select id="ame-pref-budget" class="ame-pref-select" style="width: 100%; padding: 0.6rem; border-radius: var(--ame-radius-sm); border: 1px solid var(--ame-color-border); background: #f8fafc; font-size: 0.85rem; font-weight: 600; color: #334155;">
						<option value="all"><?php esc_html_e( 'Any Budget Range', 'ame-bazaar' ); ?></option>
						<option value="999"><?php esc_html_e( 'Under ₹999', 'ame-bazaar' ); ?></option>
						<option value="2499"><?php esc_html_e( '₹1000 - ₹2499', 'ame-bazaar' ); ?></option>
						<option value="4999"><?php esc_html_e( '₹2500 - ₹4999', 'ame-bazaar' ); ?></option>
						<option value="premium"><?php esc_html_e( 'Premium ₹5000+', 'ame-bazaar' ); ?></option>
					</select>
				</div>

				<!-- Quick Reset -->
				<button onclick="window.ameResetPreferences()" style="width: 100%; padding: 0.5rem; background: none; border: 1px dashed #94a3b8; border-radius: var(--ame-radius-sm); color: #64748b; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
					<?php esc_html_e( 'Reset Filters', 'ame-bazaar' ); ?>
				</button>
			</div>

			<!-- Interactive Chat Console -->
			<div class="ame-advisor-console-box" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); box-shadow: var(--ame-shadow-md); display: flex; flex-direction: column; overflow: hidden; min-height: 500px;">
				
				<!-- Console Header -->
				<div class="ame-console-header-bar" style="background: var(--ame-color-navy); padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid var(--ame-color-gold);">
					<div style="display: flex; align-items: center; gap: 0.75rem;">
						<span style="width: 10px; height: 10px; background: #22c55e; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #22c55e;"></span>
						<span style="color: #ffffff; font-weight: 700; font-size: 1rem; letter-spacing: 0.02em;"><?php esc_html_e( 'AME Bazaar Retail Advisor', 'ame-bazaar' ); ?></span>
					</div>
					<span style="color: rgba(255,255,255,0.7); font-size: 0.8rem; font-weight: 600;"><?php esc_html_e( 'Factual Mode Active', 'ame-bazaar' ); ?></span>
				</div>

				<!-- Chat Screen -->
				<div id="ame-advisor-chat-screen" style="flex-grow: 1; padding: 2rem; overflow-y: auto; background: #fdfdfd; display: flex; flex-direction: column; gap: 1.5rem; max-height: 400px;">
					
					<!-- Welcome Message bubble -->
					<div class="ame-chat-bubble-agent" style="align-self: flex-start; max-width: 80%; display: flex; gap: 0.75rem;">
						<div style="background: var(--ame-color-navy); color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">AI</div>
						<div style="background: #f1f5f9; padding: 1.1rem 1.4rem; border-radius: 0 var(--ame-radius-md) var(--ame-radius-md) var(--ame-radius-md); color: #334155; font-size: 0.95rem; line-height: 1.6;">
							<?php esc_html_e( 'Namaste! I am your AME Fashion Advisor. How can I help you shop today? You can type a question, select a topic chip, or try a popular question from below.', 'ame-bazaar' ); ?>
						</div>
					</div>

				</div>

				<!-- Typing Indicator Bubble (Hidden by default) -->
				<div id="ame-advisor-typing-indicator" style="display: none; align-self: flex-start; margin-left: 2rem; margin-bottom: 1.5rem; gap: 0.75rem; align-items: center;">
					<div style="background: var(--ame-color-navy); color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">AI</div>
					<div style="background: #f1f5f9; padding: 0.75rem 1.25rem; border-radius: 0 var(--ame-radius-md) var(--ame-radius-md) var(--ame-radius-md); display: flex; gap: 0.35rem; align-items: center;">
						<span class="ame-typing-dot" style="animation: ameDotBounce 1.4s infinite; font-size: 1.5rem; line-height: 0; color: var(--ame-color-navy);">.</span>
						<span class="ame-typing-dot" style="animation: ameDotBounce 1.4s infinite 0.2s; font-size: 1.5rem; line-height: 0; color: var(--ame-color-navy);">.</span>
						<span class="ame-typing-dot" style="animation: ameDotBounce 1.4s infinite 0.4s; font-size: 1.5rem; line-height: 0; color: var(--ame-color-navy);">.</span>
					</div>
				</div>

				<!-- Input Area Form -->
				<div class="ame-console-input-area" style="background: #f8fafc; border-top: 1px solid var(--ame-color-border); padding: 1.25rem 2rem;">
					<form id="ame-advisor-input-form" onsubmit="event.preventDefault(); window.ameSendAdvisorMessage();" style="display: flex; gap: 0.75rem; position: relative;">
						<input type="text" id="ame-advisor-user-input" placeholder="<?php esc_attr_e( 'Ask about wedding sherwanis, sizing guides, or wash care...', 'ame-bazaar' ); ?>" style="width: 100%; padding: 1rem 1.5rem; border-radius: 999px; border: 2px solid var(--ame-color-border); outline: none; font-size: 0.95rem; transition: border-color 0.2s ease;" />
						<button type="submit" class="ame-bazaar-btn ame-bazaar-btn--primary" style="padding: 0.9rem 2rem; border-radius: 999px;">
							<?php esc_html_e( 'Send', 'ame-bazaar' ); ?>
						</button>
					</form>
				</div>

			</div>

			<!-- Quick Prompt Selection Cards -->
			<div class="ame-advisor-quick-prompts">
				<h3 style="font-size: 1.2rem; font-weight: 700; color: var(--ame-color-navy); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--ame-color-gold);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
					<span><?php esc_html_e( 'Try Asking these Popular Questions:', 'ame-bazaar' ); ?></span>
				</h3>
				<div class="ame-advisor-prompts-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 0.75rem;">
					<button class="ame-prompt-tap" onclick="window.ameTapPrompt('What should I wear for a wedding?')" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 1rem 1.25rem; border-radius: var(--ame-radius-sm); text-align: left; font-size: 0.9rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease;">
						<?php esc_html_e( 'What should I wear for a wedding?', 'ame-bazaar' ); ?>
					</button>
					<button class="ame-prompt-tap" onclick="window.ameTapPrompt('Best shirt under ₹1200')" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 1rem 1.25rem; border-radius: var(--ame-radius-sm); text-align: left; font-size: 0.9rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease;">
						<?php esc_html_e( 'Best shirt under ₹1200', 'ame-bazaar' ); ?>
					</button>
					<button class="ame-prompt-tap" onclick="window.ameTapPrompt('Can you adjust the sleeve size of a Kurti in-store?')" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 1rem 1.25rem; border-radius: var(--ame-radius-sm); text-align: left; font-size: 0.9rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease;">
						<?php esc_html_e( 'Can you adjust the sleeve size of a Kurti in-store?', 'ame-bazaar' ); ?>
					</button>
					<button class="ame-prompt-tap" onclick="window.ameTapPrompt('How should I wash my woolens bought from AME Bazaar?')" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 1rem 1.25rem; border-radius: var(--ame-radius-sm); text-align: left; font-size: 0.9rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease;">
						<?php esc_html_e( 'How should I wash my woolens bought from AME Bazaar?', 'ame-bazaar' ); ?>
					</button>
				</div>
			</div>

		</div>

		<!-- Knowledge Base Accordion Section (For Schema/SEO crawler visibility) -->
		<div class="ame-advisor-faq-block" style="border-top: 1px solid var(--ame-color-border); padding-top: 4rem;">
			<h2 style="font-size: 2rem; font-weight: 800; color: var(--ame-color-navy); text-align: center; margin: 0 0 1rem;"><?php esc_html_e( 'Retail Knowledge Base & FAQs', 'ame-bazaar' ); ?></h2>
			<p style="text-align: center; color: #475569; max-width: 600px; margin: 0 auto 3rem; font-size: 1.05rem;">
				<?php esc_html_e( 'Search our categorized reference catalog of 220 fashion and tailoring questions.', 'ame-bazaar' ); ?>
			</p>
			
			<div class="ame-faq-accordion-grid" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">
				<?php foreach ( $grouped_faqs as $key => $group ) : ?>
					<div class="ame-faq-group-wrapper" data-group-key="<?php echo esc_attr( $key ); ?>">
						<h3 style="font-size: 1.2rem; font-weight: 700; color: var(--ame-color-navy); margin-bottom: 1.25rem; border-bottom: 2px solid var(--ame-color-gold); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
							<span style="width: 8px; height: 8px; background: var(--ame-color-gold); border-radius: 50%;"></span>
							<span><?php echo esc_html( $group['title'] ); ?></span>
						</h3>
						<div style="display: flex; flex-direction: column; gap: 0.75rem;">
							<?php foreach ( $group['faqs'] as $faq ) : ?>
								<details class="ame-faq-accordion-item" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-sm); overflow: hidden; transition: all 0.2s ease;">
									<summary class="ame-faq-accordion-summary" itemprop="name" style="padding: 1.1rem 1.5rem; font-weight: 600; color: #334155; cursor: pointer; user-select: none; display: flex; justify-content: space-between; align-items: center; list-style: none;">
										<span><?php echo esc_html( $faq['q'] ); ?></span>
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="ame-faq-arrow" style="transition: transform 0.25s ease; color: #64748b;"><polyline points="6 9 12 15 18 9"></polyline></svg>
									</summary>
									<div class="ame-faq-accordion-content" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer" style="padding: 0 1.5rem 1.25rem; border-top: 1px solid #f1f5f9; background: #fafbfc;">
										<div itemprop="text" style="font-size: 0.95rem; line-height: 1.6; color: #475569; padding-top: 1rem;">
											<?php echo wp_kses_post( $faq['a'] ); ?>
										</div>
									</div>
								</details>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</main>

<script>
// Local FAQs database for search matching
window.ameAdvisorDb = <?php echo json_encode( $grouped_faqs ); ?>;

window.ameResetPreferences = function() {
	document.getElementById('ame-pref-gender').value = 'unisex';
	document.getElementById('ame-pref-age').value = 'adult';
	document.getElementById('ame-pref-occasion').value = 'wedding';
	document.getElementById('ame-pref-season').value = 'summer';
	document.getElementById('ame-pref-budget').value = 'all';
	
	if (typeof window.trackAmeEvent === 'function') {
		window.trackAmeEvent('ai_advisor_interaction', { action_type: 'filters_reset' });
	}
};

window.ameTapPrompt = function(promptText) {
	document.getElementById('ame-advisor-user-input').value = promptText;
	
	if (typeof window.trackAmeEvent === 'function') {
		window.trackAmeEvent('ai_advisor_interaction', { action_type: 'prompt_tapped', query: promptText });
	}
	
	window.ameSendAdvisorMessage();
	// Scroll smoothly to console input
	document.getElementById('ame-advisor-input-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
};

window.ameSendAdvisorMessage = function() {
	const inputElem = document.getElementById('ame-advisor-user-input');
	const query = inputElem.value.trim();
	if (!query) return;

	// Fetch current configuration dropdown preferences
	const prefGender = document.getElementById('ame-pref-gender').value;
	const prefAge = document.getElementById('ame-pref-age').value;
	const prefOccasion = document.getElementById('ame-pref-occasion').value;
	const prefSeason = document.getElementById('ame-pref-season').value;
	const prefBudget = document.getElementById('ame-pref-budget').value;

	if (typeof window.trackAmeEvent === 'function') {
		window.trackAmeEvent('ai_advisor_interaction', {
			action_type: 'message_sent',
			query: query,
			pref_gender: prefGender,
			pref_age: prefAge,
			pref_occasion: prefOccasion,
			pref_season: prefSeason,
			pref_budget: prefBudget
		});
	}

	const chatScreen = document.getElementById('ame-advisor-chat-screen');

	// 1. Add User message bubble
	const userBubble = document.createElement('div');
	userBubble.style.alignSelf = 'flex-end';
	userBubble.style.maxWidth = '80%';
	userBubble.style.display = 'flex';
	userBubble.style.gap = '0.75rem';
	userBubble.style.flexDirection = 'row-reverse';
	userBubble.innerHTML = `
		<div style="background: var(--ame-color-gold); color: #334155; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; border: 1px solid var(--ame-color-navy);">ME</div>
		<div style="background: var(--ame-color-navy); padding: 1.1rem 1.4rem; border-radius: var(--ame-radius-md) 0 var(--ame-radius-md) var(--ame-radius-md); color: #ffffff; font-size: 0.95rem; line-height: 1.6;">
			${escapeHtml(query)}
		</div>
	`;
	chatScreen.appendChild(userBubble);
	inputElem.value = '';

	// Scroll to bottom
	chatScreen.scrollTop = chatScreen.scrollHeight;

	// 2. Show Typing Indicator
	const typingInd = document.getElementById('ame-advisor-typing-indicator');
	typingInd.style.display = 'flex';
	chatScreen.appendChild(typingInd);
	chatScreen.scrollTop = chatScreen.scrollHeight;

	// 3. Search database for best QA match with preference weights
	let bestMatch = null;
	let highestScore = 0;
	let matchedCategory = 'general';
	const queryLower = query.toLowerCase();
	const queryWords = queryLower.split(/\s+/).filter(w => w.length > 2);

	for (const groupKey in window.ameAdvisorDb) {
		const faqs = window.ameAdvisorDb[groupKey].faqs;
		for (const faq of faqs) {
			const qText = faq.q.toLowerCase();
			const aText = faq.a.toLowerCase();
			let score = 0;

			// Base match
			if (qText.includes(queryLower)) score += 10;

			// Keyword match
			for (const word of queryWords) {
				if (qText.includes(word)) score += 3;
				if (aText.includes(word)) score += 1;
			}

			// Apply preference weight offsets to boost matching categories
			if (prefGender !== 'unisex' && groupKey === prefGender) score += 5;
			if (prefOccasion !== 'casual' && groupKey === prefOccasion) score += 5;
			if (prefSeason !== 'summer' && groupKey === prefSeason) score += 5;
			if (prefBudget !== 'all' && groupKey === 'budget') score += 4;

			if (score > highestScore) {
				highestScore = score;
				bestMatch = faq;
				matchedCategory = groupKey;
			}
		}
	}

	// 4. Resolve response metadata (Related categories, products, CTAs)
	let relatedCatName = "Clothing Showroom";
	let relatedCatUrl = "<?php echo esc_url( home_url( '/shop/' ) ); ?>";
	let whatsappMsg = "Hello AME Bazaar, I have a question about " + encodeURIComponent(query);
	
	if (matchedCategory === 'men') {
		relatedCatName = "Men's Wear Collection";
		relatedCatUrl = "<?php echo esc_url( home_url( '/product-category/mens-wear/' ) ); ?>";
	} else if (matchedCategory === 'women') {
		relatedCatName = "Women's Wear Collection";
		relatedCatUrl = "<?php echo esc_url( home_url( '/product-category/womens-wear/' ) ); ?>";
	} else if (matchedCategory === 'kids') {
		relatedCatName = "Kids Wear Collection";
		relatedCatUrl = "<?php echo esc_url( home_url( '/product-category/kids-wear/' ) ); ?>";
	} else if (matchedCategory === 'tailoring') {
		relatedCatName = "In-Store Tailoring & Alteration";
		relatedCatUrl = "<?php echo esc_url( home_url( '/tailoring-near-me/' ) ); ?>";
	} else if (matchedCategory === 'wedding') {
		relatedCatName = "Wedding Styling Services";
		relatedCatUrl = "<?php echo esc_url( home_url( '/wedding-shopping-in-kirari/' ) ); ?>";
	} else if (matchedCategory === 'festival') {
		relatedCatName = "Festival Shopping Guide";
		relatedCatUrl = "<?php echo esc_url( home_url( '/festival-shopping-guide/' ) ); ?>";
	}

	setTimeout(function() {
		// Hide typing indicator
		typingInd.style.display = 'none';

		// Add Agent response bubble
		const agentBubble = document.createElement('div');
		agentBubble.style.alignSelf = 'flex-start';
		agentBubble.style.maxWidth = '85%';
		agentBubble.style.display = 'flex';
		agentBubble.style.gap = '0.75rem';

		let answerHtml = '';
		if (bestMatch && highestScore > 0) {
			answerHtml = `
				<div style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 1.25rem; border-radius: var(--ame-radius-sm); margin-top: 0.75rem; box-shadow: var(--ame-shadow-sm);">
					<span style="font-size: 0.75rem; font-weight: 700; color: var(--ame-color-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Recommended Department</span>
					<a href="${relatedCatUrl}" style="color: var(--ame-color-navy); font-weight: 700; text-decoration: underline; font-size: 0.9rem;">Browse ${relatedCatName} →</a>
				</div>
			`;
		}

		const botResponse = bestMatch && highestScore > 0 ? bestMatch.a : "I couldn't find a direct matching answer in my structured knowledge graph. AME Bazaar is a premium family clothing store located on Mubarakpur Road, Kirari, Delhi, specializing in Men, Women, and Kids wear and custom tailoring. You can connect with our support desk on WhatsApp for assistance.";

		agentBubble.innerHTML = `
			<div style="background: var(--ame-color-navy); color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; border: 1px solid var(--ame-color-gold);">AI</div>
			<div style="background: #f1f5f9; padding: 1.1rem 1.4rem; border-radius: 0 var(--ame-radius-md) var(--ame-radius-md) var(--ame-radius-md); color: #334155; font-size: 0.95rem; line-height: 1.6; width: 100%;">
				<div>${botResponse}</div>
				${answerHtml}
				
				<!-- Action card links -->
				<div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid var(--ame-color-border); padding-top: 0.75rem;">
					<a href="https://wa.me/919953569533?text=${whatsappMsg}" target="_blank" rel="noopener noreferrer" style="background: #25D366; color: white; padding: 0.4rem 0.8rem; border-radius: var(--ame-radius-sm); font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
						WhatsApp Ask
					</a>
					<a href="https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi" target="_blank" rel="noopener noreferrer" style="background: var(--ame-color-navy); color: white; padding: 0.4rem 0.8rem; border-radius: var(--ame-radius-sm); font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
						Visit Store Map
					</a>
				</div>
			</div>
		`;
		chatScreen.appendChild(agentBubble);

		// Scroll to bottom
		chatScreen.scrollTop = chatScreen.scrollHeight;
	}, 500 + Math.random() * 400);
};

function escapeHtml(text) {
	return text
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}

// Track Assistant configuration selections
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.ame-pref-select').forEach(select => {
		select.addEventListener('change', () => {
			if (typeof window.trackAmeEvent === 'function') {
				window.trackAmeEvent('ai_advisor_interaction', {
					action_type: 'preference_changed',
					preference_name: select.id.replace('ame-pref-', ''),
					preference_value: select.value
				});
			}
		});
	});
});
</script>

<style>
@keyframes ameDotBounce {
	0%, 100% { transform: translateY(0); }
	50% { transform: translateY(-5px); }
}

.ame-faq-accordion-item[open] .ame-faq-arrow {
	transform: rotate(180deg);
	color: var(--ame-color-gold-dark) !important;
}

.ame-faq-accordion-item:hover {
	border-color: var(--ame-color-gold) !important;
}

.ame-prompt-tap:hover {
	border-color: var(--ame-color-navy) !important;
	color: var(--ame-color-navy) !important;
	background: #f8fafc !important;
	transform: translateY(-1px);
	box-shadow: var(--ame-shadow-sm);
}

#ame-advisor-user-input:focus {
	border-color: var(--ame-color-navy) !important;
	box-shadow: 0 0 0 3px rgba(0, 35, 71, 0.1);
}

.ame-pref-select:focus {
	border-color: var(--ame-color-gold) !important;
	outline: none;
	box-shadow: 0 0 0 3px rgba(202, 138, 4, 0.15);
}

@media (min-width: 768px) {
	.ame-advisor-layout-grid {
		grid-template-columns: 280px 1fr !important;
	}
}
</style>

<?php
get_footer();
