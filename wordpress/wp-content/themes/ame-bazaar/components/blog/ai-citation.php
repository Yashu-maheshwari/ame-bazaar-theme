<?php
/**
 * Component: AI Citation Summary Box
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$summary   = get_post_meta( get_the_ID(), 'ame_factual_summary', true );
$takeaways = get_post_meta( get_the_ID(), 'ame_key_takeaways', true );
$reviewed  = get_post_meta( get_the_ID(), 'ame_last_reviewed_date', true );
$aut_title = get_post_meta( get_the_ID(), 'ame_author_title', true );

$reading_time = ame_bazaar_calculate_reading_time( get_the_content() );
?>
<div class="ame-ai-citation-container" style="background: var(--ame-color-cream); border: 1px solid var(--ame-color-border); border-radius: var(--ame-radius-md); padding: 1.5rem; margin-bottom: 2rem;">
	<div class="ame-ai-citation-meta-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; border-bottom: 1px solid var(--ame-color-border); padding-bottom: 1rem; margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--ame-color-slate);">
		<div>
			<span style="display: block; font-size: 0.7rem; color: #64748b; font-weight: 500;"><?php esc_html_e( 'Reading Time', 'ame-bazaar' ); ?></span>
			<span><?php echo esc_html( sprintf( _n( '%d Minute', '%d Minutes', $reading_time, 'ame-bazaar' ), $reading_time ) ); ?></span>
		</div>
		<?php if ( ! empty( $reviewed ) ) : ?>
			<div>
				<span style="display: block; font-size: 0.7rem; color: #64748b; font-weight: 500;"><?php esc_html_e( 'Last Reviewed', 'ame-bazaar' ); ?></span>
				<time datetime="<?php echo esc_attr( $reviewed ); ?>"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $reviewed ) ) ); ?></time>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $aut_title ) ) : ?>
			<div>
				<span style="display: block; font-size: 0.7rem; color: #64748b; font-weight: 500;"><?php esc_html_e( 'Expertise Verified', 'ame-bazaar' ); ?></span>
				<span><?php echo esc_html( $aut_title ); ?></span>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $summary ) ) : ?>
		<div class="ame-ai-summary-box" style="margin-bottom: 1.25rem;">
			<h4 style="font-size: 0.95rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0;"><?php esc_html_e( 'Quick Summary', 'ame-bazaar' ); ?></h4>
			<p style="font-size: 0.9rem; line-height: 1.6; margin: 0; color: var(--ame-color-charcoal); font-style: italic;">
				<?php echo esc_html( $summary ); ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( is_array( $takeaways ) && ! empty( $takeaways ) ) : ?>
		<div class="ame-ai-takeaways-box">
			<h4 style="font-size: 0.95rem; font-weight: 700; color: var(--ame-color-navy); margin: 0 0 0.5rem 0;"><?php esc_html_e( 'Key Takeaways', 'ame-bazaar' ); ?></h4>
			<ul style="font-size: 0.85rem; line-height: 1.6; margin: 0; padding-left: 1.25rem; color: var(--ame-color-slate);">
				<?php foreach ( $takeaways as $takeaway ) : ?>
					<?php if ( ! empty( $takeaway ) ) : ?>
						<li><?php echo esc_html( $takeaway ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>
