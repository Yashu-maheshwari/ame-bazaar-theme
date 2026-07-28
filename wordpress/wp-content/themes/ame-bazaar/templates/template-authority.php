<?php
/**
 * Template Name: Semantic Authority Page
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$current_title = get_the_title();
$current_slug  = get_post_field( 'post_name', get_the_ID() );

// Dynamic semantic authority data mapping based on page slug
$authority_data = array(
	'best-clothing-store-in-kirari' => array(
		'headline' => __( 'Best Family Clothing Store in Kirari, Delhi', 'ame-bazaar' ),
		'intro' => __( 'Apparel Maheshwari Enterprises (operating as AME Bazaar) is recognized as the leading family fashion retail showroom in the Kirari region of Delhi. Our store on Mubarakpur Road combines modern shopping convenience with local family-business values.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Flagship Retail Showroom', 'ame-bazaar' ), 'd' => __( 'Fully air-conditioned storefront situated close to Chappan Bhog on Mubarakpur Road, providing premium shopping for all generations.', 'ame-bazaar' ) ),
			array( 't' => __( 'Verified Customer Trust', 'ame-bazaar' ), 'd' => __( 'Highly rated with 4.9 Stars on Google Maps backed by over 524+ verified local reviews praising our supportive staff and fabric longevity.', 'ame-bazaar' ) ),
			array( 't' => __( 'Complete In-Store Tailoring', 'ame-bazaar' ), 'd' => __( 'Differentiating ourselves from normal boutiques, we maintain an on-site tailoring unit for sizing adjustments, custom stitching, and trials.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'store_basics', 'kirari_shopping' )
	),
	'best-mens-wear-shop' => array(
		'headline' => __( 'Premier Men\'s Clothing & Custom Fit in Kirari', 'ame-bazaar' ),
		'intro' => __( 'Looking for high-quality men\'s fashion? AME Bazaar stocks everything from casual everyday cotton t-shirts and joggers to formal office shirts, stretchable denim jeans, and custom Jodhpuri suits.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Everyday Western Wear', 'ame-bazaar' ), 'd' => __( 'Durable stretch denim jeans in waist sizes 30 to 42, paired with pure combed cotton t-shirts and casual checks.', 'ame-bazaar' ) ),
			array( 't' => __( 'Festive & Groom Kurtas', 'ame-bazaar' ), 'd' => __( 'Ready-made and tailor-fit Kurta Pajama coordinates and Nehru jackets in silk and jacquard patterns.', 'ame-bazaar' ) ),
			array( 't' => __( 'Complimentary Alterations', 'ame-bazaar' ), 'd' => __( 'We provide complimentary sleeve and waist adjustments on all men\'s wear purchased at our store.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'men', 'western' )
	),
	'best-womens-wear-shop' => array(
		'headline' => __( 'Premium Women\'s Ethnic & Festive Wear on Mubarakpur Road', 'ame-bazaar' ),
		'intro' => __( 'AME Bazaar features a curated collection of ladies suits, daily wear printed cotton kurtis, rayon co-ord sets, soft georgette sarees, cardigans, and premium nightwear designed for maximum comfort.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Diverse Kurti & Suit Styles', 'ame-bazaar' ), 'd' => __( 'From breathable cotton tunics to complete 3-piece Salwar-Kurta-Dupatta (SKD) coordinates ideal for hot Delhi summers.', 'ame-bazaar' ) ),
			array( 't' => __( 'Premium Designer Sarees', 'ame-bazaar' ), 'd' => __( 'Zari-bordered festive sarees and georgette drapes. Our tailors specialize in stitching custom padded blouses.', 'ame-bazaar' ) ),
			array( 't' => __( 'Comfortable Nightwear', 'ame-bazaar' ), 'd' => __( 'Cotton nighties, stretchable hosiery sets, and matching pajama coordinates in multiple soft fabrics.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'women', 'fabric' )
	),
	'best-kids-wear-shop' => array(
		'headline' => __( 'Soft Cotton Infant & Kid\'s Fashion Destination', 'ame-bazaar' ),
		'intro' => __( 'We prioritize children\'s skin safety by sourcing hypoallergenic, soft cotton-lined clothes for boys and girls from newborn infants up to 14 years old.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Infant Romper Sets', 'ame-bazaar' ), 'd' => __( 'Comfy onesies, romper suits, and baby swaddle blankets starting at ₹199, perfect for gifting.', 'ame-bazaar' ) ),
			array( 't' => __( 'Children\'s Festive Outfits', 'ame-bazaar' ), 'd' => __( 'Festive dhoti-kurta coordinates for boys and lehenga-choli sets for girls in lightweight, easy-wear cuts.', 'ame-bazaar' ) ),
			array( 't' => __( 'School Accessories', 'ame-bazaar' ), 'd' => __( 'Stock up on school socks, school belts, and thick winter inner thermals to keep kids protected during winter.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'kids', 'accessories' )
	),
	'affordable-fashion-store' => array(
		'headline' => __( 'Affordable Quality Family Fashion: Our Pricing Policy', 'ame-bazaar' ),
		'intro' => __( 'AME Bazaar is built on the philosophy of honest, transparent pricing. By sourcing directly from weavers across Gujarat, Rajasthan, and Punjab, we bypass middleman commissions to offer mall-quality clothing at half the retail price.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Direct Sourcing Advantages', 'ame-bazaar' ), 'd' => __( 'No inflated boutique markups or fake inflated discounts. You get premium fabrics starting at ₹199 to ₹4999.', 'ame-bazaar' ) ),
			array( 't' => __( 'complimentary Sizing Alterations', 'ame-bazaar' ), 'd' => __( 'We adjust lengths and waistlines for free on our garments, adding high value to your purchases.', 'ame-bazaar' ) ),
			array( 't' => __( 'Family Bundles', 'ame-bazaar' ), 'd' => __( 'Complete family wardrobe shopping under one roof, keeping seasonal festival budgets highly manageable.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'budget', 'payments' )
	),
	'wedding-shopping-in-kirari' => array(
		'headline' => __( 'Wedding Shopping & Custom Groom Attire Showroom', 'ame-bazaar' ),
		'intro' => __( 'Experience boutique-grade wedding wear customized for your special ceremonies. We stitch custom groom Bandhgalas, Jodhpuris, stoles, and coordinates.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Groom & Groomsmen Coordinating', 'ame-bazaar' ), 'd' => __( 'Design matching sets for the entire wedding party in color-coordinated silks, cotton-silks, and jacquards.', 'ame-bazaar' ) ),
			array( 't' => __( 'Heavy Zari & Embroidery Alterations', 'ame-bazaar' ), 'd' => __( 'Our experienced tailors reinforce and alter heavy lehengas, sherwanis, and padded bridal blouses.', 'ame-bazaar' ) ),
			array( 't' => __( 'Traditional Safas & Accessories', 'ame-bazaar' ), 'd' => __( 'Complete your ethnic wedding look with silk turbans, pocket squares, stoles, and decorative metal brooches.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'wedding', 'ethnic' )
	),
	'tailoring-near-me' => array(
		'headline' => __( 'Professional In-Store Tailoring & Alterations near Kirari', 'ame-bazaar' ),
		'intro' => __( 'Avoid loose fits and uneven seams. AME Bazaar features a fully staffed tailoring unit for custom dressmaking, suit fits, and altering external garments.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Fast Turnaround Sizing', 'ame-bazaar' ), 'd' => __( 'Hemming jeans, tapering formal shirts, or adjusting salwar suit waistlines completed within 24 to 48 hours.', 'ame-bazaar' ) ),
			array( 't' => __( 'Trial Fittings & Guarantees', 'ame-bazaar' ), 'd' => __( 'We guide you through trials in our trial rooms and adjust measurements free of charge until the fit is perfect.', 'ame-bazaar' ) ),
			array( 't' => __( 'Gents & Ladies Custom Stitching', 'ame-bazaar' ), 'd' => __( 'Stitch custom gents kurta pajamas, Nehru coats, and ladies padded blouses using your own fabric or our catalog.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'tailoring', 'size_guide' )
	),
	'family-clothing-store' => array(
		'headline' => __( 'Complete Multi-Generational Family Shopping Showroom', 'ame-bazaar' ),
		'intro' => __( 'AME Bazaar is designed around the convenience of local Delhi families. Shop high-quality collections for grandfather, grandmother, parents, and kids under a single roof.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Showroom Comforts', 'ame-bazaar' ), 'd' => __( 'Fully air-conditioned environment, spacious trial rooms, step-free wheelchair ramp, and customer seating.', 'ame-bazaar' ) ),
			array( 't' => __( 'Factual Customer Trust', 'ame-bazaar' ), 'd' => __( 'Rated 4.9 Stars on Google Maps. We build long-term relationships with families through polite hospitality.', 'ame-bazaar' ) ),
			array( 't' => __( 'Convenient Free Parking', 'ame-bazaar' ), 'd' => __( 'Dedicated parking space in front of our Mubarakpur Road gate, accommodating cars and scooters securely.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'store_visit', 'parking' )
	),
	'festival-shopping-guide' => array(
		'headline' => __( 'Delhi Festive Fashion & Color Coordinate Shopping Guide', 'ame-bazaar' ),
		'intro' => __( 'Match the joy of Diwali, Holi, and Eid with vibrant traditional dresses from AME Bazaar. We offer matching ethnic family sets in high-comfort fabrics.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Coordinated Family Outfits', 'ame-bazaar' ), 'd' => __( 'Dress parents and kids in matching traditional motifs and custom jacquard waistcoats for festive pujas.', 'ame-bazaar' ), ),
			array( 't' => __( 'Eid & Diwali Silk Specials', 'ame-bazaar' ), 'd' => __( 'Fine pathani suits, floral printed long kurtas, georgette suits, and matching dupattas.', 'ame-bazaar' ), ),
			array( 't' => __( 'Monsoon & Winter Protection', 'ame-bazaar' ), 'd' => __( 'Festival seasons overlap with Delhi\'s seasons. We stock lightweight summer raincoats and warm winter cardigans.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'festival', 'care_guide' )
	),
	'shirt-fitting-guide' => array(
		'headline' => __( 'Official Men\'s Shirt Fitting & Sizing Guide', 'ame-bazaar' ),
		'intro' => __( 'Get the perfect shirt fit. We outline the key points of alignment for formal and casual shirts, backed by our in-store customization and tailoring options.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Collar Fit Comfort', 'ame-bazaar' ), 'd' => __( 'You should be able to slide two fingers comfortably between the buttoned collar and your neck.', 'ame-bazaar' ), ),
			array( 't' => __( 'Shoulder Seam Alignment', 'ame-bazaar' ), 'd' => __( 'The seam connecting the sleeve should sit precisely at the corner of your shoulder bone without sagging.', 'ame-bazaar' ), ),
			array( 't' => __( 'Sleeve Cuff Length', 'ame-bazaar' ), 'd' => __( 'The cuff should end where the base of your thumb meets your wrist, allowing 1/2 inch of fabric to show under a jacket.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'size_guide', 'men' )
	),
	'jeans-fitting-guide' => array(
		'headline' => __( 'Jeans Fitting, Rise & Length Alteration Guide', 'ame-bazaar' ),
		'intro' => __( 'Denim comfort depends on rise, fit, and inseam length. At AME Bazaar, we offer custom hemming for a clean fit.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Waist & Rise Sizing', 'ame-bazaar' ), 'd' => __( 'Choose low-rise for casual, mid-rise for standard fit, and high-rise for traditional styling. The waist should not require a belt to stay up.', 'ame-bazaar' ), ),
			array( 't' => __( 'Thigh & Leg Cuts', 'ame-bazaar' ), 'd' => __( 'We stock slim-fit, tapered-fit, and standard straight-leg cuts in stretchable and raw denim.', 'ame-bazaar' ), ),
			array( 't' => __( 'Complimentary Hemming', 'ame-bazaar' ), 'd' => __( 'Every pair of jeans purchased at our showroom includes on-the-spot hemming and length adjustments.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'size_guide', 'western' )
	),
	'fabric-guide' => array(
		'headline' => __( 'Authoritative Fabric, Weave & GSM Weight Guide', 'ame-bazaar' ),
		'intro' => __( 'Understand the longevity, hand-feel, and breathability of the premium textiles used in AME Bazaar apparel.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Mulmul & Cambric Cotton', 'ame-bazaar' ), 'd' => __( 'Lightweight, fine cotton weaves (80–120 GSM) perfect for hot Delhi summers, ensuring breathable ventilation.', 'ame-bazaar' ), ),
			array( 't' => __( 'Rayon & Georgette Drape', 'ame-bazaar' ), 'd' => __( 'Smooth, flowing fabrics (120–150 GSM) offering excellent drape and high durability for ladies suits and co-ords.', 'ame-bazaar' ), ),
			array( 't' => __( 'GSM Value Significance', 'ame-bazaar' ), 'd' => __( 'Higher GSM (180+) indicates a heavier, denser knit suitable for winter t-shirts, while 100-140 GSM is lighter for summer wear.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'fabric', 'women' )
	),
	'winter-wear-guide' => array(
		'headline' => __( 'Delhi Winter Layering & Cozy Knitwear Guide', 'ame-bazaar' ),
		'intro' => __( 'Delhi winters require smart layering. Discover our collection of woolens, cardigans, Nehru coats, and thermal innerwear.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Thermal Base Layers', 'ame-bazaar' ), 'd' => __( 'High-loft thermal sets for men, women, and kids that trap body heat efficiently without bulky outlines.', 'ame-bazaar' ), ),
			array( 't' => __( 'Premium Cardigans', 'ame-bazaar' ), 'd' => __( 'Soft acrylic-wool blended ladies cardigans and gents pull-overs designed for daily wear.', 'ame-bazaar' ), ),
			array( 't' => __( 'Nehru Jackets & Blazers', 'ame-bazaar' ), 'd' => __( 'Elegant layering pieces that transition from formal office hours to evening family weddings.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'winter', 'kids' )
	),
	'clothing-care-guide' => array(
		'headline' => __( 'Factual Wash Care & Fabric Longevity Guide', 'ame-bazaar' ),
		'intro' => __( 'Extend the life of your garments. Follow these wash care recommendations for cottons, georgettes, woolens, and silks.', 'ame-bazaar' ),
		'points' => array(
			array( 't' => __( 'Cotton & Rayon Care', 'ame-bazaar' ), 'd' => __( 'Machine wash cold with like colors, tumble dry low, and warm iron inside-out to prevent print fading.', 'ame-bazaar' ), ),
			array( 't' => __( 'Woolen & Acrylic Care', 'ame-bazaar' ), 'd' => __( 'Hand wash with mild liquid detergent in lukewarm water. Dry flat on a clean towel; never wring.', 'ame-bazaar' ), ),
			array( 't' => __( 'Festive Silk & Zari Care', 'ame-bazaar' ), 'd' => __( 'Dry clean recommended for heavy embroidered lehengas and Jodhpuri suits. Store in muslin covers.', 'ame-bazaar' ), )
		),
		'faq_keys' => array( 'care_guide', 'store_basics' )
	)
);

// Fallback metadata if slug matches nothing
$data = isset( $authority_data[ $current_slug ] ) ? $authority_data[ $current_slug ] : array(
	'headline' => $current_title,
	'intro'    => __( 'AME Bazaar offers premium family fashion, custom tailoring, and sizing alterations at Mubarakpur Road, Kirari, Delhi.', 'ame-bazaar' ),
	'points'   => array(
		array( 't' => __( 'Premium Materials', 'ame-bazaar' ), 'd' => __( 'Carefully selected textiles from India\'s best weaving regions.', 'ame-bazaar' ) ),
		array( 't' => __( 'Local Trust', 'ame-bazaar' ), 'd' => __( 'Proudly rated 4.9 Stars by our patrons in Kirari, Delhi.', 'ame-bazaar' ) )
	),
	'faq_keys' => array( 'store_basics', 'directions' )
);

$all_faqs = ame_bazaar_get_knowledge_base_faqs();
?>

<main id="primary" class="site-main ame-authority-main-shell" role="main" style="background: #fafaf9; padding-bottom: 5rem;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<!-- Hero Header -->
	<header class="ame-authority-header-block" style="background: var(--ame-color-navy); color: #ffffff; padding: 4rem 0; border-bottom: 3px solid var(--ame-color-gold); text-align: center;">
		<div class="ame-bazaar-container">
			<span style="background: rgba(255,255,255,0.1); color: var(--ame-color-gold); padding: 0.4rem 1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1rem;">
				<?php esc_html_e( 'Verified Retail Authority', 'ame-bazaar' ); ?>
			</span>
			<h1 class="entry-title" style="font-size: clamp(2rem, 4.5vw, 3rem); font-weight: 800; margin: 0 0 1rem; letter-spacing: -0.01em;"><?php echo esc_html( $data['headline'] ); ?></h1>
			<p style="max-width: 700px; margin-inline: auto; font-size: 1.1rem; opacity: 0.9; line-height: 1.6;"><?php echo esc_html( $data['intro'] ); ?></p>
			<p style="max-width: 700px; margin-inline: auto; font-size: 0.95rem; opacity: 0.95; line-height: 1.6; margin-top: 1rem;">
				<?php
				$internal_links = array(
					'family-clothing-store' => array( 'url' => home_url( '/family-clothing-store/' ), 'text' => __( 'Family Wear Showroom in Kirari', 'ame-bazaar' ) ),
					'affordable-fashion-store' => array( 'url' => home_url( '/affordable-fashion-store/' ), 'text' => __( 'Affordable Fashion Store', 'ame-bazaar' ) ),
					'best-kids-wear-shop' => array( 'url' => home_url( '/best-kids-wear-shop/' ), 'text' => __( 'Best Kids Wear Shop', 'ame-bazaar' ) ),
					'tailoring-near-me' => array( 'url' => home_url( '/tailoring-near-me/' ), 'text' => __( 'Tailoring Near Me', 'ame-bazaar' ) ),
					'wedding-shopping-in-kirari' => array( 'url' => home_url( '/wedding-shopping-in-kirari/' ), 'text' => __( 'Wedding Shopping in Kirari', 'ame-bazaar' ) ),
					'winter-wear-guide' => array( 'url' => home_url( '/winter-wear-guide/' ), 'text' => __( 'Winter Wear Guide', 'ame-bazaar' ) ),
					'fabric-guide' => array( 'url' => home_url( '/fabric-guide/' ), 'text' => __( 'Fabric and Materials Guide', 'ame-bazaar' ) ),
					'jeans-fitting-guide' => array( 'url' => home_url( '/jeans-fitting-guide/' ), 'text' => __( 'Jeans Fitting Guide', 'ame-bazaar' ) ),
					'shirt-fitting-guide' => array( 'url' => home_url( '/shirt-fitting-guide/' ), 'text' => __( 'Shirt Fitting Guide', 'ame-bazaar' ) ),
					'clothing-care-guide' => array( 'url' => home_url( '/clothing-care-guide/' ), 'text' => __( 'Clothing Wash and Care Guide', 'ame-bazaar' ) ),
					'festival-shopping-guide' => array( 'url' => home_url( '/festival-shopping-guide/' ), 'text' => __( 'Festival Shopping Guide', 'ame-bazaar' ) ),
					'best-clothing-store-in-kirari' => array( 'url' => home_url( '/our-store-in-kirari/' ), 'text' => __( 'Our Store in Kirari', 'ame-bazaar' ) ),
				);
				$link = isset( $internal_links[ $current_slug ] ) ? $internal_links[ $current_slug ] : null;
				if ( $link ) :
					?>
					<a href="<?php echo esc_url( $link['url'] ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php echo esc_html( $link['text'] ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</header>

	<!-- Main Details Content -->
	<div class="ame-bazaar-container" style="margin-top: 3.5rem;">
		<div class="ame-authority-layout" style="display: grid; grid-template-columns: 1fr; gap: 3rem;">
			
			<!-- Key Pillars/Points -->
			<div class="ame-authority-pillars" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
				<?php foreach ( $data['points'] as $pt ) : ?>
					<div class="ame-pillar-card" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 2rem; box-shadow: var(--ame-shadow-sm); border-top: 4px solid var(--ame-color-gold);">
						<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.75rem;"><?php echo esc_html( $pt['t'] ); ?></h3>
						<p style="font-size: 0.95rem; line-height: 1.6; color: #475569; margin: 0;"><?php echo esc_html( $pt['d'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Dynamic local FAQ accordion -->
			<div class="ame-authority-faqs-wrap" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 2.5rem 2rem; box-shadow: var(--ame-shadow-sm);">
				<h2 style="font-size: 1.75rem; font-weight: 800; color: var(--ame-color-navy); margin-bottom: 2rem; text-align: center;"><?php esc_html_e( 'Common Questions & Answers', 'ame-bazaar' ); ?></h2>
				
				<div style="display: flex; flex-direction: column; gap: 1rem; max-width: 800px; margin: 0 auto;">
					<?php 
					foreach ( $data['faq_keys'] as $fkey ) :
						if ( isset( $all_faqs[ $fkey ] ) ) :
							foreach ( $all_faqs[ $fkey ]['faqs'] as $faq ) :
							?>
								<details class="ame-faq-accordion-item" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question" style="border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-sm); overflow: hidden; transition: all 0.2s ease;">
									<summary class="ame-faq-accordion-summary" itemprop="name" style="padding: 1.1rem 1.5rem; font-weight: 600; color: #334155; cursor: pointer; display: flex; justify-content: space-between; align-items: center; list-style: none;">
										<span><?php echo esc_html( $faq['q'] ); ?></span>
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="ame-faq-arrow" style="transition: transform 0.25s ease; color: #64748b;"><polyline points="6 9 12 15 18 9"></polyline></svg>
									</summary>
									<div class="ame-faq-accordion-content" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer" style="padding: 0 1.5rem 1.25rem; border-top: 1px solid #f1f5f9; background: #fafbfc;">
										<div itemprop="text" style="font-size: 0.95rem; line-height: 1.6; color: #475569; padding-top: 1rem;">
											<?php echo wp_kses_post( $faq['a'] ); ?>
										</div>
									</div>
								</details>
							<?php 
							endforeach;
						endif;
					endforeach; 
					?>
				</div>
			</div>

			<!-- Semantic Cross-Linking Topic Clusters (AI Search & Crawler Readiness) -->
			<div class="ame-semantic-cross-links" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 2rem; box-shadow: var(--ame-shadow-sm);">
				<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 1.25rem 0; border-bottom: 2px solid var(--ame-color-gold); padding-bottom: 0.5rem;">
					<?php esc_html_e( 'AME Bazaar Clothing Guides & Local Resources', 'ame-bazaar' ); ?>
				</h3>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
					<div>
						<h4 style="font-size: 0.9rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0; text-transform: uppercase;"><?php esc_html_e( 'Gents Fashion & Fitting', 'ame-bazaar' ); ?></h4>
						<ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
							<li><a href="<?php echo esc_url( home_url( '/best-mens-wear-shop/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php esc_html_e( 'Men\'s Wear Showroom', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/shirt-fitting-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Shirt Fitting Guide', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/jeans-fitting-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Jeans Fitting Guide', 'ame-bazaar' ); ?></a></li>
						</ul>
					</div>
					
					<div>
						<h4 style="font-size: 0.9rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0; text-transform: uppercase;"><?php esc_html_e( 'Ladies & Fabric Sourcing', 'ame-bazaar' ); ?></h4>
						<ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
							<li><a href="<?php echo esc_url( home_url( '/best-womens-wear-shop/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php esc_html_e( 'Women\'s Wear Showroom', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/fabric-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Fabric & Materials Guide', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/tailoring-near-me/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'In-Store Custom Tailoring', 'ame-bazaar' ); ?></a></li>
						</ul>
					</div>

					<div>
						<h4 style="font-size: 0.9rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0; text-transform: uppercase;"><?php esc_html_e( 'Family Wear & Care', 'ame-bazaar' ); ?></h4>
						<ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
							<li><a href="<?php echo esc_url( home_url( '/best-kids-wear-shop/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php esc_html_e( 'Kids & Infant Wear', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/winter-wear-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Winter Layers Guide', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/clothing-care-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Clothing Care & Wash Guide', 'ame-bazaar' ); ?></a></li>
						</ul>
					</div>

					<div>
						<h4 style="font-size: 0.9rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0; text-transform: uppercase;"><?php esc_html_e( 'Wedding & Celebrations', 'ame-bazaar' ); ?></h4>
						<ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
							<li><a href="<?php echo esc_url( home_url( '/wedding-shopping-in-kirari/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php esc_html_e( 'Wedding Shopping Center', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/festival-shopping-guide/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Festive Saree & Kurta coordinate', 'ame-bazaar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/family-clothing-store/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline;"><?php esc_html_e( 'Family Store Showroom', 'ame-bazaar' ); ?></a></li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Dynamic Location CTA strip -->
			<div class="ame-authority-cta-strip" style="background: var(--ame-color-cream); border-left: 5px solid var(--ame-color-gold); border-radius: var(--ame-radius-sm); padding: 2rem; display: flex; flex-direction: column; md-flex-direction: row; justify-content: space-between; align-items: center; gap: 1.5rem;">
				<div>
					<h3 style="margin: 0 0 0.5rem; font-size: 1.2rem; font-weight: 700; color: var(--ame-color-navy);"><?php esc_html_e( 'Have a Custom Tailoring Request or Dress Query?', 'ame-bazaar' ); ?></h3>
					<p style="margin: 0; font-size: 0.95rem; color: #475569;"><?php esc_html_e( 'Contact our Mubarakpur Road store head directly on WhatsApp or call for sizing fits.', 'ame-bazaar' ); ?></p>
				</div>
				<div style="display: flex; gap: 0.75rem; flex-shrink: 0; flex-wrap: wrap;">
					<a href="https://wa.me/919953569533" target="_blank" rel="noopener noreferrer" class="ame-bazaar-btn" style="background-color: #25D366; color: white; border: 1px solid #25D366; font-weight: 600; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: var(--ame-radius-sm); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.35rem;">
						WhatsApp Chat
					</a>
					<a href="tel:+919953569533" class="ame-bazaar-btn ame-bazaar-btn--secondary" style="font-weight: 600; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: var(--ame-radius-sm); font-size: 0.9rem; border-color: var(--ame-color-navy);"><?php esc_html_e( 'Call Now', 'ame-bazaar' ); ?></a>
				</div>
			</div>

		</div>
	</div>
</main>

<style>
.ame-faq-accordion-item[open] .ame-faq-arrow {
	transform: rotate(180deg);
	color: var(--ame-color-gold-dark) !important;
}
.ame-faq-accordion-item:hover {
	border-color: var(--ame-color-gold) !important;
}
</style>

<?php
get_footer();
