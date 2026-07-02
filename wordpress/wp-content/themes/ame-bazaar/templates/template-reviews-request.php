<?php
/**
 * Template Name: AME Reviews Request QR System
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout = isset( $_GET['layout'] ) ? sanitize_key( $_GET['layout'] ) : 'dashboard';
$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
$review_url = ame_bazaar_get_business_setting( 'google_review_url', '#' );
$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road, Kirari, Delhi' );
$rating     = ame_bazaar_get_business_setting( 'google_reviews_rating', '4.9' );

// Smart review flow link
$flow_link  = home_url( '/rate-experience/' );

// Clean API generated QR source (changes dynamically with option)
$qr_src = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . rawurlencode( $flow_link );

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( sprintf( 'Collect Google Reviews - %s', $store_name ) ); ?></title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
			background: #f1f5f9;
			color: #0f172a;
			margin: 0;
			padding: 2rem;
			text-align: center;
		}
		.print-container {
			background: #fff;
			border: 2px dashed #cbd5e1;
			border-radius: 10px;
			padding: 3rem;
			max-width: 600px;
			margin: 2rem auto;
			box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
			box-sizing: border-box;
		}
		h1 { font-size: 2.25rem; font-weight: 800; color: #1e1b4b; margin: 0 0 1rem 0; }
		p { font-size: 1.1rem; color: #475569; margin: 0 0 2rem 0; line-height: 1.6; }
		.qr-box {
			width: 260px;
			height: 260px;
			margin: 0 auto 2rem auto;
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
		}
		.btn-print {
			background: #1e1b4b;
			color: #fff;
			border: none;
			padding: 0.75rem 2rem;
			font-size: 1rem;
			font-weight: 700;
			border-radius: 5px;
			cursor: pointer;
			margin-top: 1rem;
			transition: background 0.2s;
		}
		.btn-print:hover { background: #312e81; }
		
		@media print {
			body { background: #fff; padding: 0; }
			.print-container { border: none; box-shadow: none; margin: 0; width: 100%; max-width: 100%; height: 100%; }
			.btn-print { display: none; }
		}

		.layout-stand { max-width: 400px; padding: 2rem; }
		.layout-card { max-width: 320px; padding: 1.5rem; }
		.layout-card h1 { font-size: 1.5rem; }
	</style>
</head>
<body>

	<?php if ( 'a4' === $layout ) : ?>
		<div class="print-container layout-a4">
			<h1>Help Us Grow!</h1>
			<p>Scan the QR code below using your mobile phone to rate <strong><?php echo esc_html( $store_name ); ?></strong> on Google Maps.</p>
			
			<div class="qr-box">
				<img src="<?php echo esc_url( $qr_src ); ?>" alt="Scan to Review QR" />
			</div>
			
			<div style="font-size:1.5rem; color:#facc15; margin-bottom:1rem;">★★★★★</div>
			<div style="font-size:0.9rem; color:#64748b; margin-bottom:2rem;">
				<?php echo esc_html( sprintf( 'Current Rating: %s based on %s+ store reviews.', $rating, $rating > 4.5 ? '500' : '100' ) ); ?>
			</div>
			
			<span style="font-size:0.8rem; color:#94a3b8; display:block;"><?php echo esc_html( $address ); ?></span>
			
			<button class="btn-print" onclick="window.print()">Print A4 Poster</button>
		</div>

	<?php elseif ( 'stand' === $layout ) : ?>
		<div class="print-container layout-stand">
			<h1 style="font-size:1.75rem;">Love Your Outfit?</h1>
			<p style="font-size:0.95rem;">Please share your experience on Google! Scan the QR code at the desk.</p>
			
			<div class="qr-box" style="width:180px; height:180px;">
				<img src="<?php echo esc_url( $qr_src ); ?>" alt="Scan to Review QR" style="width:100%; height:auto;" />
			</div>
			
			<div style="font-size:1.25rem; color:#facc15; margin-bottom:0.75rem;">★★★★★</div>
			
			<button class="btn-print" onclick="window.print()">Print Stand Card</button>
		</div>

	<?php elseif ( 'card' === $layout ) : ?>
		<div class="print-container layout-card">
			<h1>Thank You!</h1>
			<p style="font-size:0.85rem; margin-bottom:1rem;">We hope you loved shopping at <?php echo esc_html( $store_name ); ?>. Scan to review your tailoring fit or apparel purchases:</p>
			
			<div class="qr-box" style="width:140px; height:140px; margin-bottom:1rem;">
				<img src="<?php echo esc_url( $qr_src ); ?>" alt="Scan to Review QR" style="width:100%; height:auto;" />
			</div>
			
			<button class="btn-print" onclick="window.print()">Print Insert Card</button>
		</div>

	<?php else : ?>
		<div class="print-container">
			<h1>AME QR Poster Templates</h1>
			<p>Select a layout template size below to print and showcase in-store QR code review stands.</p>
			<ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:1rem; align-items:center;">
				<li><a href="?layout=a4" style="color:#1e1b4b; font-weight:700;">1. Printable A4 Poster (Fitting Room Wall)</a></li>
				<li><a href="?layout=stand" style="color:#1e1b4b; font-weight:700;">2. Billing Desk Counter Stand (4x6")</a></li>
				<li><a href="?layout=card" style="color:#1e1b4b; font-weight:700;">3. Bag Insert Thank-You Cards (3x4")</a></li>
			</ul>
		</div>
	<?php endif; ?>

</body>
</html>
