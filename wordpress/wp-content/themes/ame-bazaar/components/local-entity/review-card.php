<?php
/**
 * Dynamic Reusable Review Card Component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Review Card.
 *
 * @param array $review Individual review data array.
 */
function ame_bazaar_render_review_card( $review = array() ) {
	if ( empty( $review ) ) {
		// Elegant placeholder state
		?>
		<div class="ame-review-card placeholder" style="background:#fff; border:1px dashed var(--ame-color-border); border-radius:var(--ame-radius-md); padding:1.5rem; text-align:center; min-height:180px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
			<div style="font-size:1.5rem; color:var(--ame-color-border); margin-bottom:0.5rem;">★</div>
			<p style="margin:0; font-size:0.8rem; color:var(--ame-color-slate); font-style:italic;">No customer reviews loaded yet. Visit us in-store to share your feedback!</p>
		</div>
		<?php
		return;
	}

	$name       = isset( $review['name'] ) ? esc_html( $review['name'] ) : 'Anonymous Buyer';
	$rating     = isset( $review['rating'] ) ? (int) $review['rating'] : 5;
	$date       = isset( $review['date'] ) ? esc_html( $review['date'] ) : 'Recently';
	$text       = isset( $review['text'] ) ? esc_html( $review['text'] ) : '';
	$category   = isset( $review['category'] ) ? esc_html( $review['category'] ) : '';
	$verified   = isset( $review['verified'] ) && $review['verified'];
	$photo      = isset( $review['photo'] ) ? esc_url( $review['photo'] ) : '';
	$initial    = ! empty( $name ) ? substr( $name, 0, 1 ) : 'A';
	$g_url      = ame_bazaar_get_business_setting( 'google_review_url', '#' );
	
	?>
	<div class="ame-review-card" style="background:#fff; border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:1.5rem; box-shadow:var(--ame-shadow-sm); display:flex; flex-direction:column; justify-content:space-between; height:100%; box-sizing:border-box;">
		<div>
			<div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
				<?php if ( ! empty( $photo ) ) : ?>
					<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;" loading="lazy" />
				<?php else : ?>
					<div style="width:40px; height:40px; border-radius:50%; background:var(--ame-color-cream); color:var(--ame-color-navy); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem;">
						<?php echo esc_html( $initial ); ?>
					</div>
				<?php endif; ?>
				<div>
					<h4 style="margin:0; font-size:0.9rem; font-weight:700; color:var(--ame-color-navy);"><?php echo esc_html( $name ); ?></h4>
					<span style="font-size:0.75rem; color:var(--ame-color-slate);"><?php echo esc_html( $date ); ?></span>
				</div>
			</div>
			
			<div style="color:#facc15; font-size:1.1rem; margin-bottom:0.75rem;">
				<?php echo esc_html( str_repeat( '★', $rating ) ); ?>
			</div>

			<?php if ( ! empty( $category ) ) : ?>
				<div style="margin-bottom:0.5rem;"><span style="font-size:0.7rem; background:var(--ame-color-cream); color:var(--ame-color-navy); padding:0.15rem 0.5rem; border-radius:10px; font-weight:700; text-transform:uppercase;"><?php echo esc_html( $category ); ?></span></div>
			<?php endif; ?>

			<p style="margin:0; font-size:0.85rem; color:var(--ame-color-slate); line-height:1.6; font-style:italic;">
				"<?php echo esc_html( $text ); ?>"
			</p>
		</div>

		<div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid var(--ame-color-border); display:flex; justify-content:space-between; align-items:center; font-size:0.75rem;">
			<?php if ( $verified ) : ?>
				<span style="color:#16a34a; font-weight:700; display:inline-flex; align-items:center; gap:0.25rem;">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:12px; height:12px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
					Verified Buyer
				</span>
			<?php else : ?>
				<span></span>
			<?php endif; ?>
			<a href="<?php echo esc_url( $g_url ); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--ame-color-navy); text-decoration:underline; font-weight:700;">View on Google</a>
		</div>
	</div>
	<?php
}
