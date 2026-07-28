<?php
/**
 * Template Name: Our Store in Kirari
 * Template Post Type: page
 *
 * Local SEO landing page for AME Bazaar's physical store in Kirari, Delhi.
 * Extends the existing Local Entity architecture; reuses all existing schema,
 * SEO helpers, components, and CSS — no new stylesheets, no new PHP modules.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// Page-level SEO title override.
// Uses WordPress core title-tag filter — fully compatible with Yoast / Rank Math
// because those plugins hook in at higher priority and will override this.
// ---------------------------------------------------------------------------
add_filter(
	'pre_get_document_title',
	static function () {
		return esc_html__( 'Our Store in Kirari, Delhi | AME Bazaar – Family Fashion Showroom', 'ame-bazaar' );
	},
	5
);

// ---------------------------------------------------------------------------
// Page-level meta / Open Graph tags.
// Identical plugin guard as inc/seo.php — skipped when Rank Math / Yoast /
// SEOPress is active to avoid duplicate meta.
// ---------------------------------------------------------------------------
add_action(
	'wp_head',
	static function () {
		if (
			class_exists( 'RankMath' ) ||
			defined( 'WPSEO_VERSION' ) ||
			class_exists( 'WPSEO_Frontend' ) ||
			defined( 'SEOPRESS_VERSION' )
		) {
			return;
		}

		$desc     = 'Visit AME Bazaar in Kirari, Delhi – a trusted family wear showroom offering Men\'s Wear, Women\'s Wear, Kids\' Wear, Sarees, Seasonal Collections, and in-store Tailoring. Serving Kirari, Mubarakpur, Nangloi, Baljit Vihar, and nearby areas.';
		$og_title = 'Our Store in Kirari, Delhi | AME Bazaar';
		$page_url = get_permalink();
		$logo_url = ame_bazaar_get_custom_logo_url();

		echo "\n<!-- AME Bazaar: Our Store in Kirari – page meta -->\n";
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( $page_url ) . '">' . "\n";
		echo '<meta property="og:type" content="website">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $page_url ) . '">' . "\n";
		if ( $logo_url ) {
			echo '<meta property="og:image" content="' . esc_url( $logo_url ) . '">' . "\n";
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '">' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	},
	5
);

// ---------------------------------------------------------------------------
// LocalBusiness JSON-LD — extend existing schema, never duplicate.
// ame_bazaar_get_clothing_store_schema() already includes PostalAddress, Geo,
// openingHoursSpecification, hasMap, telephone, sameAs, areaServed, etc.
// ---------------------------------------------------------------------------
add_action(
	'wp_head',
	static function () {
		$page_url    = get_permalink();
		$store       = ame_bazaar_get_clothing_store_schema();

		// Extend: give this page its own scoped @id so Google can distinguish
		// it from the home-page entity (home_url()/#store).
		$store['@id'] = esc_url( $page_url ) . '#store-kirari';
		$store['url'] = esc_url( $page_url );

		// Merge homepage into sameAs so it strengthens the brand entity.
		$same_as   = isset( $store['sameAs'] ) ? (array) $store['sameAs'] : array();
		$same_as[] = home_url( '/' );
		$store['sameAs'] = array_values( array_unique( $same_as ) );

		$graph = array(
			'@context' => 'https://schema.org',
			'@graph'   => array( $store ),
		);

		echo '<script type="application/ld+json">' . "\n";
		echo wp_json_encode( $graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		echo "\n</script>\n";
	},
	6 // After meta block (priority 5), before wp_head default (10).
);

// ---------------------------------------------------------------------------
// Business data — pulled exclusively through existing helpers.
// No value is hardcoded that already exists in settings.
// ---------------------------------------------------------------------------
$brand_name   = ame_bazaar_get_brand_name();
$phone        = ame_bazaar_get_business_setting( 'phone', '+91 99535 69533' );
$email        = ame_bazaar_get_business_setting( 'email', 'contact@amebazaar.com' );
$maps_url     = ame_bazaar_get_business_setting( 'maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );
$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp', '' );
$wa_clean     = $whatsapp ? preg_replace( '/[^0-9]/', '', $whatsapp ) : '';
$wa_url       = $wa_clean ? 'https://wa.me/' . $wa_clean : '';
$street       = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
$city         = ame_bazaar_get_business_setting( 'city', 'Kirari' );
$state_name   = ame_bazaar_get_business_setting( 'state', 'Delhi' );
$zip          = ame_bazaar_get_business_setting( 'postal_code', '110086' );
$hours        = get_theme_mod( 'ame_bazaar_store_hours', 'Daily 09:00 AM – 10:00 PM' );
$areas        = get_theme_mod( 'ame_bazaar_areas_served', 'Kirari, Mubarakpur, Meer Vihar, Baljit Vihar, Prem Nagar, Nangloi, Budh Vihar, Rohini' );
$rating_val   = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
$review_count = ame_bazaar_get_business_setting( 'google_reviews_count', '500+' );
$maps_embed   = ame_bazaar_get_business_setting(
	'maps_embed_url',
	'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3499.185!2d77.0583!3d28.7051!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zAME+Bazaar+Kirari!5e0!3m2!1sen!2sin!4v1'
);

// Build full address string without duplicating segments.
$full_address = $street;
if ( $city && false === stripos( $full_address, $city ) ) {
	$full_address .= ', ' . $city;
}
if ( $state_name && false === stripos( $full_address, $state_name ) ) {
	$full_address .= ', ' . $state_name;
}
if ( $zip ) {
	$full_address .= ' – ' . $zip;
}

// ---------------------------------------------------------------------------
// Collections — link via WordPress home_url() so URLs stay portable.
// ---------------------------------------------------------------------------
$collections = array(
	array(
		'id'    => 'mens-wear',
		'icon'  => '👔',
		'label' => __( "Men's Wear", 'ame-bazaar' ),
		'desc'  => __( 'Kurtas, shirts, trousers, sherwanis, ethnic sets, and formal occasion wear. Quality fabrics and classic tailoring for every man.', 'ame-bazaar' ),
		'url'   => home_url( '/product-category/mens-wear/' ),
	),
	array(
		'id'    => 'womens-wear',
		'icon'  => '👗',
		'label' => __( "Women's Wear", 'ame-bazaar' ),
		'desc'  => __( 'Sarees, salwar suits, lehengas, kurtis, ethnic tops, and everyday essentials. A curated range for every occasion and every age.', 'ame-bazaar' ),
		'url'   => home_url( '/product-category/womens-wear/' ),
	),
	array(
		'id'    => 'kids-wear',
		'icon'  => '🧒',
		'label' => __( "Kids' Wear", 'ame-bazaar' ),
		'desc'  => __( 'Comfortable, durable, and colourful clothing for toddlers, girls, and boys. Ethnic wear for festivals, casual wear for every day.', 'ame-bazaar' ),
		'url'   => home_url( '/product-category/kids-wear/' ),
	),
	array(
		'id'    => 'seasonal-collections',
		'icon'  => '🌸',
		'label' => __( 'Seasonal Collections', 'ame-bazaar' ),
		'desc'  => __( 'Fresh picks every season – wedding collections, Eid specials, Diwali kurtas, summer linen, and winter woollens.', 'ame-bazaar' ),
		'url'   => home_url( '/product-category/new-arrivals/' ),
	),
	array(
		'id'    => 'tailoring-service',
		'icon'  => '✂️',
		'label' => __( 'Tailoring Service', 'ame-bazaar' ),
		'desc'  => __( 'In-store custom stitching and alterations. Bring your own fabric or choose from our collection for a perfectly fitted garment.', 'ame-bazaar' ),
		'url'   => home_url( '/tailoring/' ),
	),
);

// ---------------------------------------------------------------------------
// FAQ data — used for both the visible accordion and the FAQPage schema.
// Write answers as AI-quotable factual sentences.
// ---------------------------------------------------------------------------
$faqs = array(
	array(
		'q' => __( 'Where is AME Bazaar located in Kirari?', 'ame-bazaar' ),
		/* translators: 1: street, 2: city, 3: state, 4: pin code */
		'a' => sprintf(
			__( 'AME Bazaar is located on %1$s, %2$s, %3$s – %4$s. It is easily accessible from Mubarakpur Chowk and is well-known to local residents as a trusted family clothing showroom.', 'ame-bazaar' ),
			esc_html( $street ),
			esc_html( $city ),
			esc_html( $state_name ),
			esc_html( $zip )
		),
	),
	array(
		'q' => __( 'What products are available at AME Bazaar Kirari?', 'ame-bazaar' ),
		'a' => __( "AME Bazaar offers Men's Wear (kurtas, shirts, sherwanis, trousers), Women's Wear (sarees, suits, lehengas, kurtis), Kids' Wear (ethnic and casual), Accessories, and Seasonal Collections. An in-store Tailoring and Alterations service is also available.", 'ame-bazaar' ),
	),
	array(
		'q' => __( "Does AME Bazaar have men's clothing in Kirari?", 'ame-bazaar' ),
		'a' => __( "Yes. The Men's Wear section at AME Bazaar Kirari carries a wide range of kurtas, shirts, trousers, ethnic sets, and formal wear suitable for weddings, festivals, and daily use.", 'ame-bazaar' ),
	),
	array(
		'q' => __( "Does AME Bazaar have women's clothing?", 'ame-bazaar' ),
		'a' => __( "Yes. AME Bazaar stocks an extensive Women's Wear range including sarees, salwar kameez, lehengas, kurtis, and ethnic tops for all occasions and age groups.", 'ame-bazaar' ),
	),
	array(
		'q' => __( "Is kids' wear available at AME Bazaar in Kirari?", 'ame-bazaar' ),
		'a' => __( "Yes. AME Bazaar carries a curated Kids' Wear section featuring ethnic wear for festivals and comfortable everyday clothing for toddlers, girls, and boys.", 'ame-bazaar' ),
	),
	array(
		'q' => __( 'Is tailoring available at the Kirari showroom?', 'ame-bazaar' ),
		'a' => __( 'Yes. AME Bazaar offers in-store tailoring and garment alteration services. Customers can bring their own fabric or select from available cloth in the store for custom stitching.', 'ame-bazaar' ),
	),
	array(
		'q' => __( 'What are the store timings for AME Bazaar Kirari?', 'ame-bazaar' ),
		/* translators: %s: store hours */
		'a' => sprintf(
			__( 'AME Bazaar Kirari is open %s, seven days a week, including Sundays and most public holidays.', 'ame-bazaar' ),
			esc_html( $hours )
		),
	),
	array(
		'q' => __( 'How can customers reach AME Bazaar in Kirari?', 'ame-bazaar' ),
		/* translators: 1: phone, 2: maps URL */
		'a' => sprintf(
			__( 'Customers can visit AME Bazaar at %1$s, call on %2$s, or use Google Maps for turn-by-turn directions to the showroom.', 'ame-bazaar' ),
			esc_html( $full_address ),
			esc_html( $phone )
		),
	),
);

get_header();
?>

<main id="primary" class="site-main" role="main">

	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<!-- ===================================================================
	     HERO
	     =================================================================== -->
	<header class="ame-local-entity-hero" style="background:var(--ame-color-navy);color:#fff;padding:clamp(3rem,8vw,6rem) 0;border-bottom:3px solid var(--ame-color-gold);overflow:hidden;position:relative;">
		<div class="ame-bazaar-container" style="position:relative;z-index:1;text-align:center;">

			<span style="display:inline-block;background:rgba(255,255,255,0.1);color:var(--ame-color-gold);padding:0.4rem 1.25rem;border-radius:999px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.5rem;">
				<?php esc_html_e( 'Our Physical Store · Kirari, Delhi', 'ame-bazaar' ); ?>
			</span>

			<h1 class="entry-title" style="font-size:clamp(2rem,5.5vw,3.5rem);font-weight:800;line-height:1.15;letter-spacing:-0.025em;margin:0 0 1.25rem;color:#fff;">
				<?php esc_html_e( 'AME Bazaar – Our Store in Kirari', 'ame-bazaar' ); ?>
			</h1>

			<p style="max-width:700px;margin:0 auto 2rem;font-size:clamp(1rem,2vw,1.2rem);line-height:1.7;opacity:0.9;">
				<?php
				printf(
					/* translators: 1: brand name, 2: city + state */
					esc_html__( '%1$s is a trusted family wear showroom in %2$s. We offer clothing for men, women, and children under one roof – from everyday wear to festive and bridal collections – along with an in-store tailoring service.', 'ame-bazaar' ),
					'<strong>' . esc_html( $brand_name ) . '</strong>',
					'<strong>' . esc_html( $city . ', ' . $state_name ) . '</strong>'
				);
				?>
			</p>

			<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
				<a href="<?php echo esc_url( $maps_url ); ?>"
				   target="_blank" rel="noopener noreferrer"
				   id="hero-directions-btn"
				   class="ame-bazaar-btn ame-bazaar-btn--primary"
				   style="background:var(--ame-color-gold);color:var(--ame-color-navy);border-color:var(--ame-color-gold);font-weight:700;">
					📍 <?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?>
				</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"
					   id="hero-call-btn"
					   class="ame-bazaar-btn"
					   style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,0.4);">
						📞 <?php echo esc_html( $phone ); ?>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</header>

	<!-- Trust ribbon -->
	<div style="background:var(--ame-color-cream);border-bottom:1px solid var(--ame-color-border);padding:0.85rem 0;">
		<div class="ame-bazaar-container" style="display:flex;flex-wrap:wrap;gap:1rem 2rem;align-items:center;justify-content:center;font-size:0.82rem;color:var(--ame-color-slate);">
			<span>⭐ <strong><?php echo esc_html( $rating_val ); ?>/5</strong> · <?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'Google Reviews', 'ame-bazaar' ); ?></span>
			<span aria-hidden="true" style="color:var(--ame-color-border);">|</span>
			<span>🕘 <?php echo esc_html( $hours ); ?></span>
			<span aria-hidden="true" style="color:var(--ame-color-border);">|</span>
			<span>📍 <?php echo esc_html( $full_address ); ?></span>
		</div>
	</div>

	<!-- ===================================================================
	     TWO-COLUMN LAYOUT  — reuses existing .ame-local-entity-layout grid
	     =================================================================== -->
	<div class="ame-bazaar-container ame-bazaar-section">
		<div class="ame-local-entity-layout">

			<!-- MAIN CONTENT ------------------------------------------------>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-local-entity-main' ); ?>>

				<!-- ── Store Introduction ──────────────────────────────────── -->
				<section aria-labelledby="intro-heading" style="margin-bottom:3rem;">
					<h2 id="intro-heading" style="font-size:clamp(1.4rem,3vw,2rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 1rem;">
						<?php
						printf(
							/* translators: %s: city + state */
							esc_html__( 'Clothing Store in %s', 'ame-bazaar' ),
							esc_html( $city . ', ' . $state_name )
						);
						?>
					</h2>
					<p style="font-size:1.05rem;line-height:1.8;color:var(--ame-color-slate);max-width:700px;margin:0 0 1rem;">
						<?php
						esc_html_e(
							'AME Bazaar on Mubarakpur Road is one of Kirari\'s most recognised garment shops for families. Whether you are looking for traditional ethnic wear, smart casuals, or customised tailoring, you will find it all in a single, comfortable showroom. Our team helps you find the right style, fabric, and fit – no pressure, just good service.',
							'ame-bazaar'
						);
						?>
					</p>
					<p style="font-size:1.05rem;line-height:1.8;color:var(--ame-color-slate);max-width:700px;">
						<?php
						printf(
							/* translators: %s: areas served list */
							esc_html__( 'We serve shoppers from Kirari and nearby areas including %s. Come visit us – walk in anytime during store hours.', 'ame-bazaar' ),
							esc_html( $areas )
						);
						?>
					</p>
				</section>

				<!-- ── Why Choose AME Bazaar ───────────────────────────────── -->
				<section aria-labelledby="why-heading" style="background:var(--ame-color-cream);border-radius:var(--ame-radius-lg);padding:2.5rem;margin-bottom:3.5rem;">
					<h2 id="why-heading" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 1.5rem;">
						<?php esc_html_e( 'Why Families in Kirari Choose AME Bazaar', 'ame-bazaar' ); ?>
					</h2>
					<?php get_template_part( 'components/local-entity/trust-cards' ); ?>
				</section>

				<!-- ── Collections Grid ─────────────────────────────────────── -->
				<section aria-labelledby="collections-heading" style="margin-bottom:3.5rem;">
					<h2 id="collections-heading" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 1.5rem;">
						<?php esc_html_e( 'Clothing Available at Our Kirari Showroom', 'ame-bazaar' ); ?>
					</h2>
					<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.25rem;">
						<?php foreach ( $collections as $col ) : ?>
							<div style="background:var(--ame-color-white);border:1px solid var(--ame-color-border);border-radius:var(--ame-radius-md);padding:1.5rem;box-shadow:var(--ame-shadow-sm);display:flex;flex-direction:column;gap:0.5rem;">
								<span style="font-size:2rem;" aria-hidden="true"><?php echo $col['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<h3 style="margin:0;font-size:1.05rem;font-weight:700;color:var(--ame-color-navy);">
									<?php echo esc_html( $col['label'] ); ?>
								</h3>
								<p style="margin:0 0 auto;font-size:0.875rem;line-height:1.6;color:var(--ame-color-slate);">
									<?php echo esc_html( $col['desc'] ); ?>
								</p>
								<a href="<?php echo esc_url( $col['url'] ); ?>"
								   id="collection-<?php echo esc_attr( $col['id'] ); ?>-link"
								   class="ame-bazaar-btn ame-bazaar-btn--secondary"
								   style="align-self:flex-start;margin-top:1rem;font-size:0.82rem;padding:0.55rem 1.1rem;border-color:var(--ame-color-navy);color:var(--ame-color-navy);">
									<?php
									printf(
										/* translators: %s: collection name */
										esc_html__( 'Browse %s', 'ame-bazaar' ),
										esc_html( $col['label'] )
									);
									?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</section>

				<!-- ── Google Maps ──────────────────────────────────────────── -->
				<section aria-labelledby="map-heading" style="margin-bottom:3.5rem;">
					<h2 id="map-heading" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 0.5rem;">
						<?php esc_html_e( 'How to Reach Our Kirari Store', 'ame-bazaar' ); ?>
					</h2>
					<p style="color:var(--ame-color-slate);font-size:0.9rem;margin:0 0 1.25rem;">
						<?php echo esc_html( $full_address ); ?>
					</p>
					<div style="position:relative;width:100%;padding-top:42%;border-radius:var(--ame-radius-md);overflow:hidden;border:1px solid var(--ame-color-border);background:var(--ame-color-cream);">
						<iframe
							title="<?php esc_attr_e( 'AME Bazaar – Kirari store on Google Maps', 'ame-bazaar' ); ?>"
							src="<?php echo esc_url( $maps_embed ); ?>"
							width="100%"
							height="100%"
							style="position:absolute;top:0;left:0;border:0;"
							allowfullscreen=""
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade">
						</iframe>
					</div>
					<div style="margin-top:1.25rem;display:flex;flex-wrap:wrap;gap:0.75rem;">
						<a href="<?php echo esc_url( $maps_url ); ?>"
						   target="_blank" rel="noopener noreferrer"
						   id="map-directions-btn"
						   class="ame-bazaar-btn ame-bazaar-btn--primary">
							📍 <?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?>
						</a>
						<?php if ( $wa_url ) : ?>
							<a href="<?php echo esc_url( $wa_url ); ?>"
							   target="_blank" rel="noopener noreferrer"
							   id="map-whatsapp-btn"
							   class="ame-bazaar-btn"
							   style="background:#25d366;color:#fff;border-color:#25d366;">
								💬 <?php esc_html_e( 'WhatsApp Us', 'ame-bazaar' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</section>

				<!-- ── Customer Reviews ─────────────────────────────────────── -->
				<section aria-labelledby="reviews-heading" style="margin-bottom:3.5rem;">
					<h2 id="reviews-heading" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 0.5rem;">
						<?php esc_html_e( 'What Our Customers Say', 'ame-bazaar' ); ?>
					</h2>
					<p style="color:var(--ame-color-slate);font-size:0.88rem;margin:0 0 1.5rem;">
						<?php
						printf(
							/* translators: 1: rating, 2: count */
							esc_html__( 'Rated %1$s / 5 based on %2$s Google reviews.', 'ame-bazaar' ),
							esc_html( $rating_val ),
							esc_html( $review_count )
						);
						?>
					</p>
					<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.25rem;">
						<?php
						// Renders placeholder cards via the existing reusable component.
						// Replace with real review data when available via get_post_meta / CPT.
						for ( $i = 0; $i < 3; $i++ ) :
							get_template_part( 'components/local-entity/review-card' );
						endfor;
						?>
					</div>
					<?php get_template_part( 'components/local-entity/review-cta' ); ?>
				</section>

				<!-- ── FAQ ──────────────────────────────────────────────────── -->
				<section aria-labelledby="faq-heading" style="margin-bottom:3.5rem;">
					<h2 id="faq-heading" style="font-size:clamp(1.35rem,2.5vw,1.75rem);font-weight:800;color:var(--ame-color-navy);margin:0 0 1.5rem;">
						<?php esc_html_e( 'Frequently Asked Questions', 'ame-bazaar' ); ?>
					</h2>

					<?php
					// Build FAQPage schema inline from the same $faqs array — no duplication.
					$faq_entities = array();
					foreach ( $faqs as $faq_item ) {
						$faq_entities[] = array(
							'@type'          => 'Question',
							'name'           => $faq_item['q'],
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text'  => $faq_item['a'],
							),
						);
					}
					$faq_schema = array(
						'@context'   => 'https://schema.org',
						'@type'      => 'FAQPage',
						'mainEntity' => $faq_entities,
					);
					?>
					<script type="application/ld+json">
					<?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>
					</script>

					<div role="list" style="border-top:1px solid var(--ame-color-border);">
						<?php foreach ( $faqs as $faq_idx => $faq_item ) : ?>
							<details id="faq-<?php echo absint( $faq_idx + 1 ); ?>"
							         role="listitem"
							         style="border-bottom:1px solid var(--ame-color-border);">
								<summary style="cursor:pointer;padding:1.1rem 0;font-weight:600;font-size:0.95rem;color:var(--ame-color-navy);list-style:none;display:flex;justify-content:space-between;align-items:center;gap:1rem;">
									<?php echo esc_html( $faq_item['q'] ); ?>
									<span aria-hidden="true" style="font-size:1.2rem;color:var(--ame-color-gold);flex-shrink:0;">+</span>
								</summary>
								<p style="padding:0 0 1.1rem;margin:0;font-size:0.9rem;line-height:1.75;color:var(--ame-color-slate);">
									<?php echo esc_html( $faq_item['a'] ); ?>
								</p>
							</details>
						<?php endforeach; ?>
					</div>
				</section>

				<!-- ── Internal Links ───────────────────────────────────────── -->
				<nav aria-label="<?php esc_attr_e( 'Explore AME Bazaar', 'ame-bazaar' ); ?>" style="margin-bottom:2rem;">
					<p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--ame-color-slate);margin:0 0 0.75rem;">
						<?php esc_html_e( 'Explore', 'ame-bazaar' ); ?>
					</p>
					<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
						<?php
						$nav_links = array(
							array(
								'id'    => 'int-link-home',
								'label' => __( 'Home', 'ame-bazaar' ),
								'url'   => home_url( '/' ),
							),
							array(
								'id'    => 'int-link-about',
								'label' => __( 'About AME Bazaar', 'ame-bazaar' ),
								'url'   => home_url( '/about-ame-bazaar/' ),
							),
							array(
								'id'    => 'int-link-contact',
								'label' => __( 'Contact Us', 'ame-bazaar' ),
								'url'   => home_url( '/contact/' ),
							),
							array(
								'id'    => 'int-link-family',
								'label' => __( 'Family Clothing Store', 'ame-bazaar' ),
								'url'   => home_url( '/family-clothing-store/' ),
							),
							array(
								'id'    => 'int-link-affordable',
								'label' => __( 'Affordable Fashion Store', 'ame-bazaar' ),
								'url'   => home_url( '/affordable-fashion-store/' ),
							),
							array(
								'id'    => 'int-link-kids',
								'label' => __( 'Best Kids Wear Shop', 'ame-bazaar' ),
								'url'   => home_url( '/best-kids-wear-shop/' ),
							),
							array(
								'id'    => 'int-link-tailoring',
								'label' => __( 'Tailoring Near Me', 'ame-bazaar' ),
								'url'   => home_url( '/tailoring-near-me/' ),
							),
							array(
								'id'    => 'int-link-wedding',
								'label' => __( 'Wedding Shopping in Kirari', 'ame-bazaar' ),
								'url'   => home_url( '/wedding-shopping-in-kirari/' ),
							),
							array(
								'id'    => 'int-link-winter',
								'label' => __( 'Winter Wear Guide', 'ame-bazaar' ),
								'url'   => home_url( '/winter-wear-guide/' ),
							),
							array(
								'id'    => 'int-link-home-store',
								'label' => __( 'Visit Our Kirari Showroom', 'ame-bazaar' ),
								'url'   => home_url( '/our-store-in-kirari/' ),
							),
							array(
								'id'    => 'int-link-mens',
								'label' => __( "Men's Collection", 'ame-bazaar' ),
								'url'   => home_url( '/product-category/mens-wear/' ),
							),
							array(
								'id'    => 'int-link-womens',
								'label' => __( "Women's Collection", 'ame-bazaar' ),
								'url'   => home_url( '/product-category/womens-wear/' ),
							),
							array(
								'id'    => 'int-link-kids',
								'label' => __( "Kids' Collection", 'ame-bazaar' ),
								'url'   => home_url( '/product-category/kids-wear/' ),
							),
							array(
								'id'    => 'int-link-tailoring',
								'label' => __( 'Tailoring', 'ame-bazaar' ),
								'url'   => home_url( '/tailoring/' ),
							),
						);

						// Add blog link only if blog page is configured.
						$blog_page_id = get_option( 'page_for_posts' );
						if ( $blog_page_id ) {
							$nav_links[] = array(
								'id'    => 'int-link-blog',
								'label' => __( 'Blog', 'ame-bazaar' ),
								'url'   => get_permalink( $blog_page_id ),
							);
						}

						foreach ( $nav_links as $nav_link ) :
							?>
							<a href="<?php echo esc_url( $nav_link['url'] ); ?>"
							   id="<?php echo esc_attr( $nav_link['id'] ); ?>"
							   style="display:inline-block;padding:0.4rem 0.9rem;border:1px solid var(--ame-color-border);border-radius:999px;font-size:0.8rem;color:var(--ame-color-slate);text-decoration:none;transition:background var(--ame-transition),color var(--ame-transition),border-color var(--ame-transition);"
							   onmouseenter="this.style.background='var(--ame-color-navy)';this.style.color='#fff';this.style.borderColor='var(--ame-color-navy)';"
							   onmouseleave="this.style.background='';this.style.color='var(--ame-color-slate)';this.style.borderColor='var(--ame-color-border)';">
								<?php echo esc_html( $nav_link['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</nav>

			</article><!-- .ame-local-entity-main -->

			<!-- SIDEBAR ─────────────────────────────────────────────────────>
			<aside class="ame-local-entity-sidebar" aria-label="<?php esc_attr_e( 'Store information', 'ame-bazaar' ); ?>">

				<?php get_template_part( 'components/local-entity/business-info' ); ?>
				<?php get_template_part( 'components/local-entity/opening-hours' ); ?>
				<?php get_template_part( 'components/local-entity/google-rating-widget' ); ?>
				<?php get_template_part( 'components/local-entity/tailoring-status' ); ?>
				<?php get_template_part( 'components/local-entity/payment-methods' ); ?>

			</aside><!-- .ame-local-entity-sidebar -->

		</div><!-- .ame-local-entity-layout -->
	</div><!-- .ame-bazaar-container -->

	<!-- ===================================================================
	     VISIT STORE CTA BAR
	     =================================================================== -->
	<section aria-labelledby="cta-heading" style="background:var(--ame-color-navy);color:#fff;padding:clamp(3rem,6vw,5rem) 0;border-top:3px solid var(--ame-color-gold);text-align:center;">
		<div class="ame-bazaar-container">
			<p style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--ame-color-gold);font-weight:700;margin:0 0 0.75rem;">
				<?php esc_html_e( 'Open Seven Days a Week', 'ame-bazaar' ); ?>
			</p>
			<h2 id="cta-heading" style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:800;margin:0 0 1rem;line-height:1.2;">
				<?php esc_html_e( 'Visit Our Store in Kirari Today', 'ame-bazaar' ); ?>
			</h2>
			<p style="opacity:0.88;max-width:540px;margin:0 auto 2rem;font-size:0.95rem;line-height:1.7;">
				<?php echo esc_html( $full_address ); ?><br>
				<?php echo esc_html( $hours ); ?>
			</p>
			<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
				<a href="<?php echo esc_url( $maps_url ); ?>"
				   target="_blank" rel="noopener noreferrer"
				   id="cta-directions-btn"
				   class="ame-bazaar-btn ame-bazaar-btn--primary"
				   style="background:var(--ame-color-gold);color:var(--ame-color-navy);border-color:var(--ame-color-gold);font-weight:700;font-size:1rem;padding:1rem 2rem;">
					📍 <?php esc_html_e( 'Get Directions', 'ame-bazaar' ); ?>
				</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"
					   id="cta-call-btn"
					   class="ame-bazaar-btn"
					   style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,0.5);font-size:1rem;padding:1rem 2rem;">
						📞 <?php echo esc_html( $phone ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $wa_url ) : ?>
					<a href="<?php echo esc_url( $wa_url ); ?>"
					   target="_blank" rel="noopener noreferrer"
					   id="cta-whatsapp-btn"
					   class="ame-bazaar-btn"
					   style="background:#25d366;color:#fff;border-color:#25d366;font-size:1rem;padding:1rem 2rem;">
						💬 <?php esc_html_e( 'WhatsApp', 'ame-bazaar' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main><!-- #primary -->

<?php get_footer(); ?>
