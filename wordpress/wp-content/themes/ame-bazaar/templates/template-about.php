<?php
/**
 * Template Name: About Page Template
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Fetch dynamic customizer settings
$section_title = get_theme_mod( 'ame_bazaar_about_section_title', 'About AME Bazaar' );
$section_subtitle = get_theme_mod( 'ame_bazaar_about_section_subtitle', 'Discover our heritage, collections, and values as a trusted local family store.' );
$story_headline = get_theme_mod( 'ame_bazaar_about_story_headline', 'Apparel Maheshwari Enterprises - Rooted in Trust' );
$story_content = get_theme_mod( 'ame_bazaar_about_story_content', 'Located on Mubarakpur Road in Kirari, Delhi, AME Bazaar is dedicated to providing high-quality garments for your entire family. We offer premium Men\'s Wear, Women\'s Wear, Kids\' Wear, Sarees, and fashion Accessories. In addition, our in-store tailoring and alterations service ensures a custom fit for every customer. We encourage you to visit our store for a premium minimal shopping experience.' );

$custom_about_url = get_theme_mod( 'ame_bazaar_img_about' );
if ( ! empty( $custom_about_url ) ) {
	$custom_about_id = attachment_url_to_postid( $custom_about_url );
	$about_img_id = $custom_about_id ?: 0;
} else {
	$about_img_id = get_option( 'ame_bazaar_media_about' ) ?: 540;
}
?>

<main id="primary" class="site-main ame-about-page-main" role="main" style="background: #fafaf9; padding-bottom: 5rem;">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

	<!-- Hero Header -->
	<header class="ame-about-hero-header" style="background: var(--ame-color-navy); color: #ffffff; padding: 5rem 0; border-bottom: 3px solid var(--ame-color-gold); text-align: center;">
		<div class="ame-bazaar-container">
			<span style="background: rgba(255,255,255,0.1); color: var(--ame-color-gold); padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1.25rem;">
				<?php esc_html_e( 'Our Story & Brand', 'ame-bazaar' ); ?>
			</span>
			<h1 class="entry-title" style="font-size: clamp(2.25rem, 5vw, 3.5rem); font-weight: 800; margin: 0 0 1rem; letter-spacing: -0.02em;"><?php echo esc_html( $section_title ); ?></h1>
			<p style="max-width: 650px; margin-inline: auto; font-size: 1.15rem; opacity: 0.9; line-height: 1.6;"><?php echo esc_html( $section_subtitle ); ?></p>
		</div>
	</header>

	<div class="ame-bazaar-container" style="margin-top: 4rem;">
		<div class="ame-about-grid" style="display: grid; grid-template-columns: 1fr; gap: 4rem; max-width: 1050px; margin-inline: auto;">
			
			<!-- Split Row: Text vs Image -->
			<div class="ame-about-row-split" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem; align-items: center;">
				<div class="ame-about-text-column">
					<h2 style="font-size: 1.8rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.25rem; letter-spacing: -0.01em;">
						<?php echo esc_html( $story_headline ); ?>
					</h2>
					<p style="font-size: 1.05rem; line-height: 1.7; color: #475569; margin-bottom: 2rem;">
						<?php echo esc_html( $story_content ); ?>
					</p>
					<p style="font-size: 0.95rem; line-height: 1.7; color: #475569; margin-bottom: 2rem;">
						<a href="<?php echo esc_url( home_url( '/our-store-in-kirari/' ) ); ?>" style="color: var(--ame-color-navy); text-decoration: underline; font-weight: 600;"><?php esc_html_e( 'Visit Our Kirari Showroom', 'ame-bazaar' ); ?></a>
					</p>
					
					<div class="ame-usp-box" style="background-color: var(--ame-color-cream); border-left: 5px solid var(--ame-color-gold); padding: 1.25rem 1.5rem; border-radius: var(--ame-radius-sm);">
						<h4 style="margin: 0 0 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ame-color-navy); font-weight: bold;"><?php esc_html_e( 'Our Brand Promise', 'ame-bazaar' ); ?></h4>
						<p style="margin: 0; font-size: 1.1rem; font-style: italic; font-weight: 600; color: #334155;">
							"<?php esc_html_e( 'Premium Quality. Fair Prices. Trusted Service. Fashion for Every Family.', 'ame-bazaar' ); ?>"
						</p>
					</div>
				</div>
				
				<div class="ame-about-image-column" style="text-align: center;">
					<?php 
					$img_html = '';
					if ( $about_img_id ) {
						$img_html = wp_get_attachment_image( $about_img_id, 'large', false, array( 'style' => 'width: 100%; height: auto; display: block;' ) );
					} elseif ( ! empty( $custom_about_url ) ) {
						$img_html = '<img src="' . esc_url( $custom_about_url ) . '" style="width: 100%; height: auto; display: block;" />';
					}
					if ( $img_html ) : ?>
						<div style="border-radius: var(--ame-radius-md); overflow: hidden; box-shadow: var(--ame-shadow-md); border: 1px solid var(--ame-color-border);">
							<?php echo $img_html; ?>
						</div>
					<?php else : ?>
						<div style="background: #e2e8f0; border-radius: var(--ame-radius-md); padding: 5rem 2rem; border: 1px solid var(--ame-color-border); color: #64748b; font-style: italic;">
							<?php esc_html_e( 'AME Bazaar Kirari Showroom Photo', 'ame-bazaar' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Three Pillars Grid -->
			<div class="ame-about-pillars-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
				
				<!-- Pillar 1: Mission -->
				<div class="ame-pillar-box" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 2rem; border-radius: var(--ame-radius-md); border-top: 4px solid var(--ame-color-navy); box-shadow: var(--ame-shadow-sm);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.75rem;"><?php esc_html_e( 'Our Mission', 'ame-bazaar' ); ?></h3>
					<p style="font-size: 0.95rem; line-height: 1.6; color: #475569; margin: 0;">
						<?php esc_html_e( 'To provide high-quality family fashion at honest prices while delivering exceptional customer service and long-term trust.', 'ame-bazaar' ); ?>
					</p>
				</div>

				<!-- Pillar 2: Vision -->
				<div class="ame-pillar-box" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 2rem; border-radius: var(--ame-radius-md); border-top: 4px solid var(--ame-color-gold); box-shadow: var(--ame-shadow-sm);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.75rem;"><?php esc_html_e( 'Our Vision', 'ame-bazaar' ); ?></h3>
					<p style="font-size: 0.95rem; line-height: 1.6; color: #475569; margin: 0;">
						<?php esc_html_e( 'To become India\'s most trusted family fashion retail brand, combining offline hospitality with seamless digital commerce.', 'ame-bazaar' ); ?>
					</p>
				</div>

				<!-- Pillar 3: Core Value -->
				<div class="ame-pillar-box" style="background: #ffffff; border: 1px solid var(--ame-color-border); padding: 2rem; border-radius: var(--ame-radius-md); border-top: 4px solid var(--ame-color-navy); box-shadow: var(--ame-shadow-sm);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 0.75rem;"><?php esc_html_e( 'Core Values', 'ame-bazaar' ); ?></h3>
					<p style="font-size: 0.95rem; line-height: 1.6; color: #475569; margin: 0;">
						<?php esc_html_e( 'Built on honesty, weaver direct sourcing, in-store alterations support, and responsive family shopping solutions.', 'ame-bazaar' ); ?>
					</p>
				</div>

			</div>

			<!-- Founder Info block -->
			<div class="ame-about-founder-block" style="background: #ffffff; border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 3rem 2.5rem; text-align: center; box-shadow: var(--ame-shadow-sm);">
				<h3 style="font-size: 1.5rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1rem;"><?php esc_html_e( 'Founder Philosophy', 'ame-bazaar' ); ?></h3>
				<p style="max-width: 750px; margin-inline: auto; font-size: 1rem; line-height: 1.7; color: #475569; font-style: italic;">
					<?php esc_html_e( '"At AME Bazaar, we believe fitting and quality should never be compromised for price. By skipping middlemen distribution nodes, we bring authentic craftsmanship and premium fabrics straight from weaving mills to local families in Delhi 110086."', 'ame-bazaar' ); ?>
				</p>
				<span style="font-weight: 700; color: var(--ame-color-navy); display: block; margin-top: 1.5rem; font-size: 1rem;"><?php esc_html_e( 'Apparel Maheshwari Enterprises Team', 'ame-bazaar' ); ?></span>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
