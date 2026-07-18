<?php
/**
 * Fashion Advisor Section — Homepage Component
 *
 * Promotes the AI Fashion Advisor with premium editorial design.
 * Links to the Fashion Advisor page, shows category quick-links.
 * PHP 5.6+ compatible. UTF-8 without BOM.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$advisor_url  = home_url( '/fashion-advisor/' );
$whatsapp     = ame_bazaar_get_business_setting( 'whatsapp', '+91 99535 69533' );
$wa_number    = preg_replace( '/[^0-9]/', '', $whatsapp );
$wa_msg       = rawurlencode( 'Hello! I need help choosing an outfit for an upcoming occasion.' );
$wa_url       = 'https://wa.me/' . $wa_number . '?text=' . $wa_msg;

$quick_topics = array(
	array( 'label' => __( 'Wedding Season', 'ame-bazaar' ), 'icon' => '✦' ),
	array( 'label' => __( 'Office Wear', 'ame-bazaar' ),   'icon' => '◈' ),
	array( 'label' => __( 'Festival Outfits', 'ame-bazaar' ), 'icon' => '◆' ),
	array( 'label' => __( 'Kids Fashion', 'ame-bazaar' ),  'icon' => '◉' ),
	array( 'label' => __( 'Winter Styling', 'ame-bazaar' ), 'icon' => '◇' ),
	array( 'label' => __( 'Casual Daily', 'ame-bazaar' ),  'icon' => '○' ),
);
?>

<section class="ame-advisor-section" aria-labelledby="ame-advisor-title">
	<div class="ame-advisor-inner">

		<!-- Left: editorial copy -->
		<div class="ame-advisor-content">
			<span class="ame-advisor-eyebrow"><?php esc_html_e( 'Personalised Style Intelligence', 'ame-bazaar' ); ?></span>
			<h2 id="ame-advisor-title" class="ame-advisor-headline">
				<?php esc_html_e( 'Your Personal', 'ame-bazaar' ); ?><br>
				<em><?php esc_html_e( 'Fashion Advisor', 'ame-bazaar' ); ?></em>
			</h2>
			<p class="ame-advisor-body">
				<?php esc_html_e( 'Not sure what to wear for a wedding, festival, or office occasion? Our AI-powered style advisor suggests outfits based on your body type, occasion, and budget — in seconds.', 'ame-bazaar' ); ?>
			</p>

			<!-- Quick topic chips -->
			<div class="ame-advisor-chips" aria-label="<?php esc_attr_e( 'Quick style topics', 'ame-bazaar' ); ?>">
				<?php foreach ( $quick_topics as $topic ) : ?>
				<a href="<?php echo esc_url( $advisor_url ); ?>" class="ame-advisor-chip">
					<span class="ame-advisor-chip-icon" aria-hidden="true"><?php echo esc_html( $topic['icon'] ); ?></span>
					<?php echo esc_html( $topic['label'] ); ?>
				</a>
				<?php endforeach; ?>
			</div>

			<!-- CTAs -->
			<div class="ame-advisor-actions">
				<a href="<?php echo esc_url( $advisor_url ); ?>"
					class="ame-section-btn ame-section-btn--primary"
					id="ame-advisor-cta-main">
					<?php esc_html_e( 'Ask the Fashion Advisor', 'ame-bazaar' ); ?>
					<svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
						<path d="M1 5h12M8 1l5 4-5 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/>
					</svg>
				</a>
				<a href="<?php echo esc_url( $wa_url ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="ame-section-btn ame-section-btn--whatsapp"
					id="ame-advisor-cta-whatsapp">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
					</svg>
					<?php esc_html_e( 'Chat on WhatsApp', 'ame-bazaar' ); ?>
				</a>
			</div>
		</div>

		<!-- Right: visual card -->
		<div class="ame-advisor-visual" aria-hidden="true">
			<div class="ame-advisor-card">
				<div class="ame-advisor-card-header">
					<div class="ame-advisor-card-avatar">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
						</svg>
					</div>
					<div class="ame-advisor-card-meta">
						<strong><?php esc_html_e( 'AME Style Advisor', 'ame-bazaar' ); ?></strong>
						<span><?php esc_html_e( 'Online · Instant reply', 'ame-bazaar' ); ?></span>
					</div>
					<div class="ame-advisor-card-dot" aria-hidden="true"></div>
				</div>

				<div class="ame-advisor-chat">
					<div class="ame-chat-bubble ame-chat-bubble--bot">
						<?php esc_html_e( 'What\'s the occasion? I\'ll find the perfect outfit for you.', 'ame-bazaar' ); ?>
					</div>
					<div class="ame-chat-suggestions">
						<?php
						$suggest = array(
							__( 'Engagement ceremony', 'ame-bazaar' ),
							__( 'Office meeting', 'ame-bazaar' ),
							__( 'Diwali celebration', 'ame-bazaar' ),
						);
						foreach ( $suggest as $s ) :
						?>
						<span class="ame-chat-suggest-pill"><?php echo esc_html( $s ); ?></span>
						<?php endforeach; ?>
					</div>
					<div class="ame-chat-bubble ame-chat-bubble--user">
						<?php esc_html_e( 'Diwali celebration', 'ame-bazaar' ); ?>
					</div>
					<div class="ame-chat-bubble ame-chat-bubble--bot ame-chat-bubble--typing">
						<span class="ame-typing-dot"></span>
						<span class="ame-typing-dot"></span>
						<span class="ame-typing-dot"></span>
					</div>
				</div>

				<div class="ame-advisor-card-footer">
					<div class="ame-advisor-input-mock">
						<span><?php esc_html_e( 'Ask anything about outfits…', 'ame-bazaar' ); ?></span>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
						</svg>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
