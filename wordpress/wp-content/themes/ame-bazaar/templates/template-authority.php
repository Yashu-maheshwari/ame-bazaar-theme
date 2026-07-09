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
			array( 't' => __( 'Coordinated Family Outfits', 'ame-bazaar' ), 'd' => __( 'Dress parents and kids in matching traditional motifs and custom jacquard waistcoats for festive pujas.', 'ame-bazaar' ) ),
			array( 't' => __( 'Eid & Diwali Silk Specials', 'ame-bazaar' ), 'd' => __( 'Fine pathani suits, floral printed long kurtas, georgette suits, and matching dupattas.', 'ame-bazaar' ) ),
			array( 't' => __( 'Monsoon & Winter Protection', 'ame-bazaar' ), 'd' => __( 'Festival seasons overlap with Delhi\'s seasons. We stock lightweight summer raincoats and warm winter cardigans.', 'ame-bazaar' ) )
		),
		'faq_keys' => array( 'festival', 'care_guide' )
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
