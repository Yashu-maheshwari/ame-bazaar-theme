<?php
/**
 * AME Bazaar AI Fashion Advisor Backend Connector.
 * Implements provider abstractions, prompt builder, session memory, and product matching engines.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. AI Provider Interface
 */
interface Ame_Bazaar_AI_Provider_Interface {
	/**
	 * Send structured prompt with history to the provider.
	 *
	 * @param string $prompt
	 * @param array  $history
	 * @return string
	 */
	public function generate_response( $prompt, array $history );
}

/**
 * 2. Mock / Dry-Run Provider (Default fallback)
 */
class Ame_Bazaar_Mock_AI_Provider implements Ame_Bazaar_AI_Provider_Interface {
	public function generate_response( $prompt, array $history ) {
		// Scans local knowledge base FAQs for keyword matching
		$grouped_faqs = ame_bazaar_get_knowledge_base_faqs();
		$best_match   = '';
		$high_score   = 0;
		$clean_prompt = strtolower( $prompt );

		foreach ( $grouped_faqs as $group ) {
			foreach ( $group['faqs'] as $faq ) {
				$q = strtolower( $faq['q'] );
				if ( strpos( $q, $clean_prompt ) !== false ) {
					return $faq['a'];
				}
				$words = explode( ' ', $clean_prompt );
				$score = 0;
				foreach ( $words as $w ) {
					if ( strlen( $w ) > 3 && strpos( $q, $w ) !== false ) {
						$score += 3;
					}
				}
				if ( $score > $high_score ) {
					$high_score = $score;
					$best_match = $faq['a'];
				}
			}
		}

		if ( $best_match && $high_score > 0 ) {
			return $best_match;
		}

		return __( "I have received your style query. We specialize in premium family clothing, custom tailoring, and fits at our Mubarakpur Road showroom in Kirari. How can I guide your outfit search?", 'ame-bazaar' );
	}
}

/**
 * 3. Prompt Construction Engine
 */
class Ame_Bazaar_AI_Prompt_Builder {
	/**
	 * Build system prompt grounded with business identity.
	 *
	 * @param string $user_query
	 * @param array  $context
	 * @return string
	 */
	public static function build_grounded_prompt( $user_query, array $context = array() ) {
		$store_name = ame_bazaar_get_business_setting( 'store_name', 'AME Bazaar' );
		$address    = ame_bazaar_get_business_setting( 'address', 'Mubarakpur Road' );
		$city       = ame_bazaar_get_business_setting( 'city', 'Kirari' );
		$hours      = ame_bazaar_get_business_setting( 'hours', 'Mo-Su 09:00–22:00' );

		$system = "You are the official AI Fashion Advisor for {$store_name}, located at {$address}, {$city}, Delhi.\n";
		$system .= "Timings: {$hours}. Alterations: On-site 30-minute adjustments available.\n";
		$system .= "Strict Grounding Rule: Answer only based on family fashion, tailoring, fabrics, sizing fits, and custom dressmaking. Do not invent products or external reviews. If you do not know the answer, politely invite the customer to visit our showroom or contact us on WhatsApp.\n\n";
		
		if ( ! empty( $context ) ) {
			$system .= "Customer Preferences Context:\n";
			foreach ( $context as $k => $v ) {
				$system .= "- " . esc_attr( $k ) . ": " . esc_attr( $v ) . "\n";
			}
		}

		$system .= "\nUser Query: {$user_query}";
		return $system;
	}
}

/**
 * 4. Session Conversation Memory Structure
 */
class Ame_Bazaar_AI_Memory_Manager {
	private $session_key = 'ame_bazaar_ai_history';

	public function __construct() {
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}
	}

	public function add_message( $role, $content ) {
		if ( ! isset( $_SESSION[ $this->session_key ] ) ) {
			$_SESSION[ $this->session_key ] = array();
		}
		$_SESSION[ $this->session_key ][] = array(
			'role'      => sanitize_text_field( $role ),
			'content'   => sanitize_textarea_field( $content ),
			'timestamp' => time(),
		);

		// Limit history length to last 20 messages
		if ( count( $_SESSION[ $this->session_key ] ) > 20 ) {
			array_shift( $_SESSION[ $this->session_key ] );
		}
	}

	public function get_history() {
		return isset( $_SESSION[ $this->session_key ] ) ? $_SESSION[ $this->session_key ] : array();
	}

	public function clear_memory() {
		if ( isset( $_SESSION[ $this->session_key ] ) ) {
			unset( $_SESSION[ $this->session_key ] );
		}
	}
}

/**
 * 5. Reusable Product Knowledge Layer & Matcher
 */
class Ame_Bazaar_AI_Recommendation_Engine {
	/**
	 * Match WooCommerce catalog items based on semantic attributes.
	 *
	 * @param array $attributes
	 * @return array Array of matched WP_Post objects
	 */
	public static function query_matching_products( array $attributes ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return array();
		}

		$meta_query = array( 'relation' => 'AND' );

		// Fabric filter match
		if ( ! empty( $attributes['fabric'] ) ) {
			$meta_query[] = array(
				'key'     => '_ame_fabric',
				'value'   => sanitize_text_field( $attributes['fabric'] ),
				'compare' => 'LIKE',
			);
		}

		// Gender filter match
		if ( ! empty( $attributes['gender'] ) ) {
			$meta_query[] = array(
				'key'     => '_ame_gender',
				'value'   => sanitize_text_field( $attributes['gender'] ),
				'compare' => '=',
			);
		}

		// Pattern filter match
		if ( ! empty( $attributes['pattern'] ) ) {
			$meta_query[] = array(
				'key'     => '_ame_pattern',
				'value'   => sanitize_text_field( $attributes['pattern'] ),
				'compare' => 'LIKE',
			);
		}

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'meta_query'     => $meta_query,
		);

		$query = new WP_Query( $args );
		return $query->posts;
	}
}

/**
 * 6. AJAX API endpoint for front-end integration
 */
function ame_bazaar_ajax_advisor_chat_handler() {
	check_ajax_referer( 'ame_bazaar_advisor_nonce', 'nonce' );

	$query    = isset( $_POST['query'] ) ? sanitize_textarea_field( wp_unslash( $_POST['query'] ) ) : '';
	$gender   = isset( $_POST['gender'] ) ? sanitize_text_field( wp_unslash( $_POST['gender'] ) ) : 'unisex';
	$occasion = isset( $_POST['occasion'] ) ? sanitize_text_field( wp_unslash( $_POST['occasion'] ) ) : 'casual';
	$season   = isset( $_POST['season'] ) ? sanitize_text_field( wp_unslash( $_POST['season'] ) ) : 'summer';
	$budget   = isset( $_POST['budget'] ) ? sanitize_text_field( wp_unslash( $_POST['budget'] ) ) : 'all';

	if ( empty( $query ) ) {
		wp_send_json_error( array( 'message' => 'Empty style query.' ) );
	}

	$memory   = new Ame_Bazaar_AI_Memory_Manager();
	$history  = $memory->get_history();

	// Construct context block
	$context = array(
		'Gender'   => $gender,
		'Occasion' => $occasion,
		'Season'   => $season,
		'Budget'   => $budget,
	);

	$full_prompt = Ame_Bazaar_AI_Prompt_Builder::build_grounded_prompt( $query, $context );

	// Resolve active AI provider using filter hooks (Extensible architecture)
	$provider = apply_filters( 'ame_bazaar_ai_provider', new Ame_Bazaar_Mock_AI_Provider() );
	$response = $provider->generate_response( $full_prompt, $history );

	// Save history
	$memory->add_message( 'user', $query );
	$memory->add_message( 'assistant', $response );

	// Fetch matching products for recommendations
	$matched_products = Ame_Bazaar_AI_Recommendation_Engine::query_matching_products( array(
		'gender' => $gender,
		'fabric' => $season === 'summer' ? 'Cotton' : '',
	) );

	$product_recommendations = array();
	foreach ( $matched_products as $prod ) {
		$product_recommendations[] = array(
			'id'    => $prod->ID,
			'title' => $prod->post_title,
			'url'   => get_permalink( $prod->ID ),
			'price' => get_post_meta( $prod->ID, '_price', true ),
		);
	}

	wp_send_json_success( array(
		'reply'           => $response,
		'recommendations' => $product_recommendations,
	) );
}
add_action( 'wp_ajax_ame_bazaar_advisor_chat', 'ame_bazaar_ajax_advisor_chat_handler' );
add_action( 'wp_ajax_nopriv_ame_bazaar_advisor_chat', 'ame_bazaar_ajax_advisor_chat_handler' );
