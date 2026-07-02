<?php
/**
 * Helper functions.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_asset_uri( $relative_path ) {
	return trailingslashit( AME_BAZAAR_URI ) . ltrim( $relative_path, '/' );
}

function ame_bazaar_asset_path( $relative_path ) {
	return trailingslashit( AME_BAZAAR_PATH ) . ltrim( $relative_path, '/' );
}

function ame_bazaar_get_custom_logo_url() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( $custom_logo_id ) {
		$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );

		if ( ! empty( $logo[0] ) ) {
			return $logo[0];
		}
	}

	return '';
}

function ame_bazaar_get_brand_name() {
	$brand = get_bloginfo( 'name' );

	return $brand ? $brand : 'AME Bazaar';
}

/**
 * Get the central Entity Registry.
 *
 * @return array
 */
function ame_bazaar_get_entity_registry() {
	return array(
		'store'       => array(
			'label'       => __( 'Clothing Store Overview', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => true,
		),
		'mens_wear'   => array(
			'label'       => __( 'Men\'s Wear Department', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => true,
		),
		'womens_wear' => array(
			'label'       => __( 'Women\'s Wear Department', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => true,
		),
		'kids_wear'   => array(
			'label'       => __( 'Kids\' Wear Department', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => false,
		),
		'sarees'      => array(
			'label'       => __( 'Saree Shop Department', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => true,
		),
		'accessories' => array(
			'label'       => __( 'Accessories Department', 'ame-bazaar' ),
			'schema_type' => 'ClothingStore',
			'has_tailor'  => false,
		),
		'tailoring'   => array(
			'label'       => __( 'Tailoring & Alterations', 'ame-bazaar' ),
			'schema_type' => 'Service',
			'has_tailor'  => true,
		),
	);
}

/**
 * Register post meta keys for local entity pages.
 */
function ame_bazaar_register_local_entity_meta() {
	register_post_meta( 'page', 'ame_local_entity_type', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', 'ame_local_buying_guide', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', 'ame_local_seasonal_recommendations', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', 'ame_local_faqs', array(
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'q' => array( 'type' => 'string' ),
						'a' => array( 'type' => 'string' ),
					),
				),
			),
		),
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	// Blog & Pillar Entity Metadata
	register_post_meta( 'post', 'ame_last_reviewed_date', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
	register_post_meta( 'page', 'ame_last_reviewed_date', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_author_title', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
	register_post_meta( 'page', 'ame_author_title', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_factual_summary', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
	register_post_meta( 'page', 'ame_factual_summary', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_key_takeaways', array(
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		),
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
	register_post_meta( 'page', 'ame_key_takeaways', array(
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		),
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_associated_fabric', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_associated_occasion', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_associated_season', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_associated_pillar', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
	register_post_meta( 'page', 'ame_associated_pillar', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	// FAQ Meta for Posts
	register_post_meta( 'post', 'ame_local_faqs', array(
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'q' => array( 'type' => 'string' ),
						'a' => array( 'type' => 'string' ),
					),
				),
			),
		),
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'ame_bazaar_register_local_entity_meta' );

/**
 * Generate semantic HTML navigation links for registered local entities.
 *
 * @return string HTML output.
 */
function ame_bazaar_get_local_entity_links_html() {
	$registry = ame_bazaar_get_entity_registry();
	$current_id = get_the_ID();

	// Query pages with local entity type meta key
	$query = new WP_Query( array(
		'post_type'      => 'page',
		'posts_per_page' => 20,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => 'ame_local_entity_type',
				'compare' => 'EXISTS',
			),
		),
	) );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$html = '<ul class="ame-local-entity-nav-list">';
	while ( $query->have_posts() ) {
		$query->the_post();
		$entity_key = get_post_meta( get_the_ID(), 'ame_local_entity_type', true );

		// Skip if not in the registry
		if ( ! isset( $registry[ $entity_key ] ) ) {
			continue;
		}

		$is_current = ( get_the_ID() === $current_id );
		$active_class = $is_current ? ' class="current-menu-item"' : '';
		$aria_current = $is_current ? ' aria-current="page"' : '';

		$html .= sprintf(
			'<li%s><a href="%s"%s>%s</a></li>',
			$active_class,
			esc_url( get_permalink() ),
			$aria_current,
			esc_html( get_the_title() )
		);
	}
	wp_reset_postdata();
	$html .= '</ul>';

	return $html;
}

/**
 * Calculate estimated reading time for content.
 *
 * @param string $content The text content.
 * @return int Minutes count.
 */
function ame_bazaar_calculate_reading_time( $content ) {
	$words_per_minute = 200;
	$word_count       = str_word_count( wp_strip_all_tags( $content ) );
	$minutes          = ceil( $word_count / $words_per_minute );

	return max( 1, $minutes );
}

/**
 * Filter post content to automatically add ID anchors to H2 headings.
 *
 * @param string $content The content string.
 * @return string Filtered content.
 */
function ame_bazaar_toc_content_filter( $content ) {
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	$content = preg_replace_callback( '/<h2(.*?)>(.*?)<\/h2>/i', function( $matches ) {
		$attrs = $matches[1];
		$title = $matches[2];

		if ( stripos( $attrs, 'id=' ) !== false ) {
			return $matches[0];
		}

		$id = sanitize_title( wp_strip_all_tags( $title ) );

		return sprintf( '<h2 id="%s"%s>%s</h2>', esc_attr( $id ), $attrs, $title );
	}, $content );

	return $content;
}
add_filter( 'the_content', 'ame_bazaar_toc_content_filter', 9 );

/**
 * Generate a semantic HTML Table of Contents for post content.
 *
 * @param string $content The content string.
 * @return string HTML output.
 */
function ame_bazaar_generate_toc_html( $content ) {
	preg_match_all( '/<h2.*?>(.*?)<\/h2>/i', $content, $matches );
	if ( empty( $matches[1] ) ) {
		return '';
	}

	$html  = '<nav class="ame-toc-nav" aria-label="' . esc_attr__( 'Table of contents', 'ame-bazaar' ) . '">';
	$html .= '<h3 class="ame-toc-title">' . esc_html__( 'Table of Contents', 'ame-bazaar' ) . '</h3>';
	$html .= '<ul class="ame-toc-list">';
	foreach ( $matches[1] as $title ) {
		$clean_title = wp_strip_all_tags( $title );
		$id          = sanitize_title( $clean_title );
		$html       .= sprintf( '<li><a href="#%s">%s</a></li>', esc_attr( $id ), esc_html( $clean_title ) );
	}
	$html .= '</ul>';
	$html .= '</nav>';

	return $html;
}

/**
 * Register post meta keys for the editorial workflow.
 */
function ame_bazaar_register_editorial_meta() {
	register_post_meta( 'post', 'ame_editorial_status', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'default'       => 'draft',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_fact_verified_by', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_human_reviewed_by', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_ai_generated', array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'boolean',
		'default'       => false,
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'post', 'ame_editorial_checklist', array(
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		),
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'ame_bazaar_register_editorial_meta' );

/**
 * Calculate post content and metadata completeness score.
 *
 * @param int $post_id The post ID.
 * @return int Score out of 100.
 */
function ame_bazaar_calculate_completeness_score( $post_id ) {
	$score = 0;

	// Critical Content Fields (Max 30)
	if ( ! empty( get_post_meta( $post_id, 'ame_factual_summary', true ) ) ) {
		$score += 15;
	}
	$takeaways = get_post_meta( $post_id, 'ame_key_takeaways', true );
	if ( is_array( $takeaways ) && ! empty( $takeaways ) ) {
		$score += 15;
	}

	// Entity Classification Fields (Max 40)
	if ( ! empty( get_post_meta( $post_id, 'ame_associated_pillar', true ) ) ) {
		$score += 10;
	}
	if ( ! empty( get_post_meta( $post_id, 'ame_associated_fabric', true ) ) ) {
		$score += 10;
	}
	if ( ! empty( get_post_meta( $post_id, 'ame_associated_occasion', true ) ) ) {
		$score += 10;
	}
	if ( ! empty( get_post_meta( $post_id, 'ame_associated_season', true ) ) ) {
		$score += 10;
	}

	// Quality and Review Fields (Max 30)
	$faqs = get_post_meta( $post_id, 'ame_local_faqs', true );
	if ( is_array( $faqs ) && ! empty( $faqs ) ) {
		$score += 15;
	}
	if ( ! empty( get_post_meta( $post_id, 'ame_last_reviewed_date', true ) ) ) {
		$score += 15;
	}

	return $score;
}

/**
 * Validate post publishing readiness.
 *
 * @param int $post_id The post ID.
 * @return array Checklist status and readiness flag.
 */
function ame_bazaar_validate_publishing_readiness( $post_id ) {
	$score = ame_bazaar_calculate_completeness_score( $post_id );
	$status = get_post_meta( $post_id, 'ame_editorial_status', true ) ?: 'draft';
	
	$warnings = array();

	if ( empty( get_post_meta( $post_id, 'ame_factual_summary', true ) ) ) {
		$warnings[] = __( 'Missing Factual Summary', 'ame-bazaar' );
	}
	if ( empty( get_post_meta( $post_id, 'ame_associated_pillar', true ) ) ) {
		$warnings[] = __( 'Missing Associated Pillar Page', 'ame-bazaar' );
	}
	if ( 'fact_verified' !== $status && 'seo_reviewed' !== $status && 'published' !== $status ) {
		$warnings[] = __( 'Editorial workflow status is not verified or reviewed', 'ame-bazaar' );
	}

	$is_ready = ( $score >= 70 && empty( $warnings ) );

	return array(
		'score'    => $score,
		'warnings' => $warnings,
		'is_ready' => $is_ready,
	);
}

/**
 * Add custom columns to post admin listing.
 */
function ame_bazaar_add_editorial_columns( $columns ) {
	$columns['ame_status']       = __( 'Editorial Status', 'ame-bazaar' );
	$columns['ame_completeness'] = __( 'Completeness', 'ame-bazaar' );
	$columns['ame_readiness']    = __( 'Readiness', 'ame-bazaar' );
	return $columns;
}
add_filter( 'manage_post_posts_columns', 'ame_bazaar_add_editorial_columns' );

/**
 * Render custom columns in post admin listing.
 */
function ame_bazaar_render_editorial_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'ame_status':
			$status = get_post_meta( $post_id, 'ame_editorial_status', true ) ?: 'draft';
			$labels = array(
				'draft'         => __( 'Draft', 'ame-bazaar' ),
				'ai_draft'      => __( 'AI Draft', 'ame-bazaar' ),
				'human_edited'  => __( 'Human Edited', 'ame-bazaar' ),
				'fact_verified' => __( 'Fact Verified', 'ame-bazaar' ),
				'seo_reviewed'  => __( 'SEO Reviewed', 'ame-bazaar' ),
				'published'     => __( 'Published', 'ame-bazaar' ),
				'annual_review' => __( 'Annual Review', 'ame-bazaar' ),
			);
			echo esc_html( isset( $labels[ $status ] ) ? $labels[ $status ] : $status );
			break;

		case 'ame_completeness':
			$score = ame_bazaar_calculate_completeness_score( $post_id );
			printf( '%d%%', $score );
			break;

		case 'ame_readiness':
			$validation = ame_bazaar_validate_publishing_readiness( $post_id );
			if ( $validation['is_ready'] ) {
				echo '<span style="color: #059669; font-weight: 700;">✓ Ready</span>';
			} else {
				echo '<span style="color: #dc2626; font-weight: 700;">✗ Incomplete</span>';
			}
			break;
	}
}
add_action( 'manage_post_posts_custom_column', 'ame_bazaar_render_editorial_columns', 10, 2 );

/**
 * Add custom Editorial Meta Box in post editing dashboard.
 */
function ame_bazaar_add_editorial_meta_box() {
	add_meta_box(
		'ame_editorial_box',
		__( 'AME Editorial Production Hub', 'ame-bazaar' ),
		'ame_bazaar_render_editorial_meta_box',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ame_bazaar_add_editorial_meta_box' );

/**
 * Render Editorial Meta Box output.
 */
function ame_bazaar_render_editorial_meta_box( $post ) {
	// Security check nonce
	wp_nonce_field( 'ame_editorial_save', 'ame_editorial_nonce' );

	$status   = get_post_meta( $post->ID, 'ame_editorial_status', true ) ?: 'draft';
	$verified = get_post_meta( $post->ID, 'ame_fact_verified_by', true );
	$reviewed = get_post_meta( $post->ID, 'ame_human_reviewed_by', true );
	$is_ai    = get_post_meta( $post->ID, 'ame_ai_generated', true );

	$validation = ame_bazaar_validate_publishing_readiness( $post->ID );
	?>
	<div class="ame-editorial-metabox-wrapper" style="font-family: sans-serif; font-size: 13px;">
		<!-- Completeness Bar -->
		<div style="margin-bottom: 15px;">
			<label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Completeness Score:', 'ame-bazaar' ); ?></label>
			<div style="background: #e2e8f0; border-radius: 4px; height: 12px; overflow: hidden; width: 100%;">
				<div style="background: <?php echo $validation['score'] >= 70 ? '#059669' : '#eab308'; ?>; width: <?php echo $validation['score']; ?>%; height: 100%;"></div>
			</div>
			<span style="font-size: 11px; color: #64748b; font-weight: bold;"><?php printf( '%d%% Complete', $validation['score'] ); ?></span>
		</div>

		<!-- Status Workflow -->
		<div style="margin-bottom: 12px;">
			<label for="ame_editorial_status" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Workflow State:', 'ame-bazaar' ); ?></label>
			<select name="ame_editorial_status" id="ame_editorial_status" class="postbox" style="width: 100%;">
				<option value="draft" <?php selected( $status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'ame-bazaar' ); ?></option>
				<option value="ai_draft" <?php selected( $status, 'ai_draft' ); ?>><?php esc_html_e( 'AI Draft', 'ame-bazaar' ); ?></option>
				<option value="human_edited" <?php selected( $status, 'human_edited' ); ?>><?php esc_html_e( 'Human Edited', 'ame-bazaar' ); ?></option>
				<option value="fact_verified" <?php selected( $status, 'fact_verified' ); ?>><?php esc_html_e( 'Fact Verified', 'ame-bazaar' ); ?></option>
				<option value="seo_reviewed" <?php selected( $status, 'seo_reviewed' ); ?>><?php esc_html_e( 'SEO Reviewed', 'ame-bazaar' ); ?></option>
				<option value="published" <?php selected( $status, 'published' ); ?>><?php esc_html_e( 'Published', 'ame-bazaar' ); ?></option>
				<option value="annual_review" <?php selected( $status, 'annual_review' ); ?>><?php esc_html_e( 'Annual Review', 'ame-bazaar' ); ?></option>
			</select>
		</div>

		<!-- Metadata inputs -->
		<div style="margin-bottom: 12px;">
			<label for="ame_fact_verified_by" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Fact Verified By:', 'ame-bazaar' ); ?></label>
			<input type="text" name="ame_fact_verified_by" id="ame_fact_verified_by" value="<?php echo esc_attr( $verified ); ?>" style="width: 100%;" />
		</div>

		<div style="margin-bottom: 12px;">
			<label for="ame_human_reviewed_by" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Human Reviewed By:', 'ame-bazaar' ); ?></label>
			<input type="text" name="ame_human_reviewed_by" id="ame_human_reviewed_by" value="<?php echo esc_attr( $reviewed ); ?>" style="width: 100%;" />
		</div>

		<div style="margin-bottom: 15px;">
			<input type="checkbox" name="ame_ai_generated" id="ame_ai_generated" value="1" <?php checked( $is_ai, true ); ?> />
			<label for="ame_ai_generated" style="font-weight: bold;"><?php esc_html_e( 'Uses AI Draft?', 'ame-bazaar' ); ?></label>
		</div>

		<!-- Warnings / Checklist -->
		<div>
			<label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Readiness Status:', 'ame-bazaar' ); ?></label>
			<?php if ( $validation['is_ready'] ) : ?>
				<div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; padding: 8px; color: #065f46; font-weight: bold;">
					<?php esc_html_e( 'Ready for Publish', 'ame-bazaar' ); ?>
				</div>
			<?php else : ?>
				<div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 4px; padding: 8px; color: #991b1b;">
					<span style="font-weight: bold; display: block; margin-bottom: 3px;"><?php esc_html_e( 'Missing Requirements:', 'ame-bazaar' ); ?></span>
					<ul style="margin: 0; padding-left: 15px; font-size: 11px;">
						<?php foreach ( $validation['warnings'] as $warn ) : ?>
							<li><?php echo esc_html( $warn ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Save Editorial Meta Box values.
 */
function ame_bazaar_save_editorial_meta_box( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['ame_editorial_nonce'] ) || ! wp_verify_nonce( $_POST['ame_editorial_nonce'], 'ame_editorial_save' ) ) {
		return;
	}

	// Verify auto-save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save fields
	if ( isset( $_POST['ame_editorial_status'] ) ) {
		update_post_meta( $post_id, 'ame_editorial_status', sanitize_text_field( $_POST['ame_editorial_status'] ) );
	}
	if ( isset( $_POST['ame_fact_verified_by'] ) ) {
		update_post_meta( $post_id, 'ame_fact_verified_by', sanitize_text_field( $_POST['ame_fact_verified_by'] ) );
	}
	if ( isset( $_POST['ame_human_reviewed_by'] ) ) {
		update_post_meta( $post_id, 'ame_human_reviewed_by', sanitize_text_field( $_POST['ame_human_reviewed_by'] ) );
	}
	
	$is_ai = isset( $_POST['ame_ai_generated'] ) ? true : false;
	update_post_meta( $post_id, 'ame_ai_generated', $is_ai );
}
add_action( 'save_post', 'ame_bazaar_save_editorial_meta_box' );



