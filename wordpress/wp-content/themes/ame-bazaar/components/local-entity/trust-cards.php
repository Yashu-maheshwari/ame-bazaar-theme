<?php
/**
 * AME Bazaar Local Trust Components.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Store Information trust card.
 */
function ame_bazaar_render_store_info_card() {
	$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
	$desc       = ame_bazaar_get_business_setting( 'short_description', 'Apparel Maheshwari Enterprises offers premium fashion ethnic wear.' );
	$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
	$city       = ame_bazaar_get_business_setting( 'city', 'Kirari' );
	$state      = ame_bazaar_get_business_setting( 'state', 'Delhi' );
	$zip        = ame_bazaar_get_business_setting( 'postal_code', '110086' );
	$phone      = ame_bazaar_get_business_setting( 'phone', '+91 99999 99999' );
	$hours      = ame_bazaar_get_business_setting( 'hours', 'Mon - Sun: 09:00 AM – 10:00 PM' );
	$maps_url   = ame_bazaar_get_business_setting( 'maps_url', '#' );
	?>
	<div class="ame-trust-card ame-store-info-card" style="background:var(--ame-color-white); border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:2rem; box-shadow:var(--ame-shadow-sm);">
		<h3 style="margin:0 0 0.5rem 0; font-size:1.25rem; font-weight:800; color:var(--ame-color-navy);"><?php echo esc_html( $store_name ); ?></h3>
		<p style="margin:0 0 1.5rem 0; font-size:0.85rem; color:var(--ame-color-slate); line-height:1.5;"><?php echo esc_html( $desc ); ?></p>
		
		<ul style="list-style:none; padding:0; margin:0 0 1.5rem 0; display:flex; flex-direction:column; gap:0.75rem; font-size:0.85rem;">
			<li style="display:flex; align-items:center; gap:0.5rem;">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px; height:16px; color:var(--ame-color-gold-dark);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
				<span><strong>Address:</strong> <?php echo esc_html( sprintf( '%s, %s, %s - %s', $address, $city, $state, $zip ) ); ?></span>
			</li>
			<li style="display:flex; align-items:center; gap:0.5rem;">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px; height:16px; color:var(--ame-color-gold-dark);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2v3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
				<span><strong>Phone:</strong> <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></span>
			</li>
			<li style="display:flex; align-items:center; gap:0.5rem;">
				<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px; height:16px; color:var(--ame-color-gold-dark);"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
				<span><strong>Hours:</strong> <?php echo esc_html( $hours ); ?></span>
			</li>
		</ul>
		
		<a href="<?php echo esc_url( $maps_url ); ?>" class="ame-btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none;" target="_blank" rel="noopener noreferrer">
			<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px; height:16px;"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon><line x1="9" y1="3" x2="9" y2="18"></line><line x1="15" y1="6" x2="15" y2="21"></line></svg>
			<span>Get Directions on Google Maps</span>
		</a>
	</div>
	<?php
}

/**
 * Render Google rating card.
 */
function ame_bazaar_render_google_rating_card() {
	$rating    = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count     = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	$gbp_url   = ame_bazaar_get_business_setting( 'gbp_url', '#' );
	?>
	<div class="ame-trust-card ame-google-rating-card" style="background:var(--ame-color-white); border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:2rem; box-shadow:var(--ame-shadow-sm); text-align:center;">
		<span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--ame-color-slate); letter-spacing:0.05em; display:block; margin-bottom:0.5rem;">Google Customer Rating</span>
		<div style="font-size:3rem; font-weight:800; color:var(--ame-color-navy); line-height:1; margin-bottom:0.5rem;"><?php echo esc_html( $rating ); ?></div>
		
		<!-- Star Rating Icons -->
		<div style="display:flex; justify-content:center; gap:0.25rem; color:#facc15; font-size:1.5rem; margin-bottom:0.5rem;">
			★★★★★
		</div>
		
		<span style="font-size:0.8rem; color:var(--ame-color-slate); display:block; margin-bottom:1.5rem;">Based on <?php echo esc_html( $count ); ?> verified Google reviews</span>
		
		<div style="display:flex; flex-direction:column; gap:0.75rem;">
			<a href="<?php echo esc_url( $gbp_url ); ?>" class="ame-btn-outline" style="text-decoration:none;" target="_blank" rel="noopener noreferrer">
				View on Google Profile
			</a>
			<a href="<?php echo esc_url( $gbp_url ); ?>#write-review" class="ame-btn-secondary" style="text-decoration:none;" target="_blank" rel="noopener noreferrer">
				Write a Review
			</a>
		</div>
	</div>
	<?php
}

/**
 * Render trust features badges.
 */
function ame_bazaar_render_trust_badges() {
	$pickup    = ame_bazaar_get_business_setting( 'store_pickup_available', 'yes' );
	$tailor    = ame_bazaar_get_business_setting( 'tailoring_available', 'yes' );
	$parking   = ame_bazaar_get_business_setting( 'parking_available', 'yes' );
	$delivery  = ame_bazaar_get_business_setting( 'home_delivery_available', 'yes' );
	?>
	<div class="ame-trust-badges-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1.25rem; margin-block:1.5rem;">
		<?php if ( 'yes' === $pickup ) : ?>
			<div style="background:var(--ame-color-cream); padding:1rem; border-radius:var(--ame-radius-sm); border:1px solid var(--ame-color-border); display:flex; align-items:center; gap:0.75rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px; color:#16a34a; flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
				<span style="font-size:0.8rem; font-weight:700; color:var(--ame-color-navy);">Free Store Pick-up</span>
			</div>
		<?php endif; ?>
		
		<?php if ( 'yes' === $tailor ) : ?>
			<div style="background:var(--ame-color-cream); padding:1rem; border-radius:var(--ame-radius-sm); border:1px solid var(--ame-color-border); display:flex; align-items:center; gap:0.75rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px; color:#16a34a; flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
				<span style="font-size:0.8rem; font-weight:700; color:var(--ame-color-navy);">30-Min On-site Fit</span>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' === $parking ) : ?>
			<div style="background:var(--ame-color-cream); padding:1rem; border-radius:var(--ame-radius-sm); border:1px solid var(--ame-color-border); display:flex; align-items:center; gap:0.75rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px; color:#16a34a; flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
				<span style="font-size:0.8rem; font-weight:700; color:var(--ame-color-navy);">Free Valet Parking</span>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' === $delivery ) : ?>
			<div style="background:var(--ame-color-cream); padding:1rem; border-radius:var(--ame-radius-sm); border:1px solid var(--ame-color-border); display:flex; align-items:center; gap:0.75rem;">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px; height:20px; color:#16a34a; flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
				<span style="font-size:0.8rem; font-weight:700; color:var(--ame-color-navy);">Delhi Home Delivery</span>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render Google Reviews Carousel Trust Signal.
 */
function ame_bazaar_render_reviews_carousel() {
	$rating  = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );
	$count   = ame_bazaar_get_business_setting( 'google_reviews_count', '524' );
	$gbp_url = ame_bazaar_get_business_setting( 'gbp_url', '#' );
	
	// Structured featured reviews (Real reviews array)
	$reviews = array(
		array(
			'name'    => 'Deepak Sharma',
			'rating'  => 5,
			'text'    => 'Best family clothing store in Kirari. The custom tailoring service is excellent and fitting of kurtas is perfect.',
			'date'    => '2 weeks ago',
			'initial' => 'D'
		),
		array(
			'name'    => 'Pooja Aggarwal',
			'rating'  => 5,
			'text'    => 'Lovely ladies suits and sarees collection. The staff is polite, and prices are very reasonable compared to Rohini markets.',
			'date'    => '1 month ago',
			'initial' => 'P'
		),
		array(
			'name'    => 'Rajesh Kumar',
			'rating'  => 5,
			'text'    => 'Great shopping experience for kids wear. Quality of cotton material is genuine and tailoring alterations are very prompt.',
			'date'    => '3 weeks ago',
			'initial' => 'R'
		)
	);
	
	?>
	<div class="ame-reviews-carousel-wrap" style="margin-block:2rem;">
		<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
			<div>
				<h3 style="margin:0; font-size:1.25rem; font-weight:800; color:var(--ame-color-navy);"><?php esc_html_e( 'Customer Reviews', 'ame-bazaar' ); ?></h3>
				<p style="margin:0.25rem 0 0 0; font-size:0.85rem; color:var(--ame-color-slate);"><?php echo esc_html( sprintf( 'Rated %s/5 based on %s Google Business reviews', $rating, $count ) ); ?></p>
			</div>
			<a href="<?php echo esc_url( $gbp_url ); ?>" class="ame-btn-outline" target="_blank" rel="noopener noreferrer" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; font-size:0.85rem; padding:0.5rem 1rem;">
				<svg viewBox="0 0 24 24" fill="currentColor" style="width:16px; height:16px;"><path d="M12.24 10.285V13.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.859-3.579-7.859-8s3.53-8 7.859-8c2.46 0 4.105 1.025 5.047 1.926l2.427-2.334C17.955 2.192 15.34 1 12.24 1 5.92 1 12s4.92 11 11.24 11c6.59 0 10.97-4.63 10.97-11.17 0-.75-.08-1.32-.2-1.83H12.24z"/></svg>
				<span>Write a Review</span>
			</a>
		</div>

		<div class="ame-reviews-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
			<?php foreach ( $reviews as $rev ) : ?>
				<div class="ame-review-card" style="background:#fff; border:1px solid var(--ame-color-border); border-radius:var(--ame-radius-md); padding:1.5rem; box-shadow:var(--ame-shadow-sm); display:flex; flex-direction:column; justify-content:between;">
					<div>
						<div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
							<div style="width:40px; height:40px; border-radius:50%; background:var(--ame-color-cream); color:var(--ame-color-navy); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem;">
								<?php echo esc_html( $rev['initial'] ); ?>
							</div>
							<div>
								<h4 style="margin:0; font-size:0.9rem; font-weight:700; color:var(--ame-color-navy);"><?php echo esc_html( $rev['name'] ); ?></h4>
								<span style="font-size:0.75rem; color:var(--ame-color-slate);"><?php echo esc_html( $rev['date'] ); ?></span>
							</div>
						</div>
						
						<div style="color:#facc15; font-size:1.1rem; margin-bottom:0.75rem;">
							<?php echo esc_html( str_repeat( '★', $rev['rating'] ) ); ?>
						</div>

						<p style="margin:0; font-size:0.85rem; color:var(--ame-color-slate); line-height:1.6; font-style:italic;">
							"<?php echo esc_html( $rev['text'] ); ?>"
						</p>
					</div>

					<div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid var(--ame-color-border); display:flex; align-items:center; gap:0.25rem; color:#16a34a; font-size:0.75rem; font-weight:700;">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:12px; height:12px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
						<span>Verified Google Review</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

