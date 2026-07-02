<?php
/**
 * Template Name: Smart Customer Review Funnel
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
$review_url = ame_bazaar_get_business_setting( 'google_review_url', '#' );
$success    = false;

if ( isset( $_POST['ame_submit_feedback'] ) && check_admin_referer( 'ame_private_feedback_action', 'ame_feedback_nonce' ) ) {
	$rating   = isset( $_POST['rating_val'] ) ? (int) $_POST['rating_val'] : 3;
	$feedback = isset( $_POST['feedback_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['feedback_text'] ) ) : '';
	$name     = isset( $_POST['customer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_name'] ) ) : 'Guest';
	
	$logs = get_option( 'ame_bazaar_private_feedback', array() );
	$logs[] = array(
		'name'     => $name,
		'rating'   => $rating,
		'feedback' => $feedback,
		'date'     => current_time( 'mysql' )
	);
	update_option( 'ame_bazaar_private_feedback', $logs );
	$success = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( sprintf( 'Rate Your Experience - %s', $store_name ) ); ?></title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
			background: #f8fafc;
			color: #0f172a;
			margin: 0;
			padding: 2rem;
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			box-sizing: border-box;
		}
		.funnel-card {
			background: #fff;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			padding: 2.5rem;
			max-width: 480px;
			width: 100%;
			box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
			text-align: center;
			box-sizing: border-box;
		}
		h1 { font-size: 1.5rem; font-weight: 800; color: #1e1b4b; margin: 0 0 0.5rem 0; }
		p { font-size: 0.95rem; color: #64748b; margin: 0 0 2rem 0; line-height: 1.5; }
		.stars-container {
			display: flex;
			justify-content: center;
			gap: 0.75rem;
			margin-bottom: 2rem;
		}
		.star-btn {
			font-size: 2.5rem;
			color: #cbd5e1;
			background: none;
			border: none;
			cursor: pointer;
			padding: 0;
			transition: color 0.15s;
		}
		.star-btn:hover, .star-btn.active { color: #facc15; }
		.form-group { text-align: left; margin-bottom: 1.25rem; }
		label { font-size: 0.85rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.5rem; }
		input, textarea {
			width: 100%;
			border: 1px solid #cbd5e1;
			border-radius: 6px;
			padding: 0.75rem;
			font-size: 0.9rem;
			box-sizing: border-box;
		}
		.btn-submit {
			background: #1e1b4b;
			color: #fff;
			border: none;
			padding: 0.75rem 2rem;
			font-weight: 700;
			font-size: 0.9rem;
			border-radius: 6px;
			cursor: pointer;
			width: 100%;
		}
	</style>
</head>
<body>

	<div class="funnel-card">
		<?php if ( $success ) : ?>
			<h1 style="color:#10b981;">Thank You!</h1>
			<p>Your constructive feedback has been successfully shared with the store manager privately. We will use this to improve our service.</p>
		<?php else : ?>
			<h1>Rate Your Experience</h1>
			<p>We value your honest feedback to help us offer the best ethnic fashion apparel in Delhi.</p>
			
			<div class="stars-container" id="stars-wrap">
				<button class="star-btn" data-value="1" aria-label="1 Star">★</button>
				<button class="star-btn" data-value="2" aria-label="2 Stars">★</button>
				<button class="star-btn" data-value="3" aria-label="3 Stars">★</button>
				<button class="star-btn" data-value="4" aria-label="4 Stars">★</button>
				<button class="star-btn" data-value="5" aria-label="5 Stars">★</button>
			</div>

			<!-- Dynamic Private Feedback Form -->
			<form id="feedback-form" method="post" action="" style="display:none;">
				<?php wp_nonce_field( 'ame_private_feedback_action', 'ame_feedback_nonce' ); ?>
				<input type="hidden" name="rating_val" id="rating_input" value="3" />
				
				<div class="form-group">
					<label for="customer_name">Your Name</label>
					<input type="text" id="customer_name" name="customer_name" required placeholder="Enter your name" />
				</div>
				
				<div class="form-group">
					<label for="feedback_text">How can we improve?</label>
					<textarea id="feedback_text" name="feedback_text" rows="4" required placeholder="Tell us about your shopping experience..."></textarea>
				</div>
				
				<button type="submit" name="ame_submit_feedback" class="btn-submit">Submit Feedback</button>
			</form>
		<?php endif; ?>
	</div>

	<script>
		const stars = document.querySelectorAll('.star-btn');
		const form = document.getElementById('feedback-form');
		const ratingInput = document.getElementById('rating_input');
		const googleReviewUrl = "<?php echo esc_js( $review_url ); ?>";

		stars.forEach((star, index) => {
			star.addEventListener('click', () => {
				const value = parseInt(star.getAttribute('data-value'));
				
				// Highlight stars
				stars.forEach((s, idx) => {
					if (idx < value) {
						s.classList.add('active');
					} else {
						s.classList.remove('active');
					}
				});

				if (value >= 4) {
					// Redirect high ratings straight to Google Business review form
					window.location.href = googleReviewUrl;
				} else {
					// Low ratings show private feedback collector form
					ratingInput.value = value;
					form.style.display = 'block';
				}
			});
		});
	</script>

</body>
</html>
