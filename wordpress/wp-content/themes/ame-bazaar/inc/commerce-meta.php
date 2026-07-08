<?php
/**
 * Register Product Knowledge & Merchant meta fields for WooCommerce products.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom product metadata metaboxes.
 */
function ame_bazaar_add_product_meta_boxes() {
	add_meta_box(
		'ame_bazaar_product_knowledge_box',
		__( 'AI Commerce & Product Knowledge Layer', 'ame-bazaar' ),
		'ame_bazaar_render_product_knowledge_metabox',
		'product',
		'normal',
		'high'
	);

	add_meta_box(
		'ame_bazaar_page_guide_box',
		__( 'Shopping Guide Configurations', 'ame-bazaar' ),
		'ame_bazaar_render_page_guide_metabox',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'ame_bazaar_add_product_meta_boxes' );

/**
 * Render Product Knowledge Metabox.
 */
function ame_bazaar_render_product_knowledge_metabox( $post ) {
	wp_nonce_field( 'ame_bazaar_product_save_meta', 'ame_product_nonce' );

	$facts = get_post_meta( $post->ID, '_ame_product_facts', true ) ?: '';
	$material = get_post_meta( $post->ID, '_ame_product_material', true ) ?: '';
	$care = get_post_meta( $post->ID, '_ame_product_care', true ) ?: '';
	$size_fit = get_post_meta( $post->ID, '_ame_product_size_fit', true ) ?: '';
	$occasion = get_post_meta( $post->ID, '_ame_product_occasion', true ) ?: '';
	$season = get_post_meta( $post->ID, '_ame_product_season', true ) ?: '';
	$selected_articles = get_post_meta( $post->ID, '_ame_product_related_knowledge', true ) ?: array();

	// Query knowledge articles for the relationship picker
	$articles_query = new WP_Query( array(
		'post_type'      => 'knowledge',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );
	$knowledge_options = array();
	if ( $articles_query->have_posts() ) {
		while ( $articles_query->have_posts() ) {
			$articles_query->the_post();
			$knowledge_options[ get_the_ID() ] = get_the_title();
		}
		wp_reset_postdata();
	}
	?>
	<div class="ame-meta-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
		<div>
			<p>
				<label for="ame_product_facts"><strong><?php esc_html_e( 'Product Quick Facts (One per line)', 'ame-bazaar' ); ?></strong></label><br />
				<textarea name="ame_product_facts" id="ame_product_facts" style="width: 100%; height: 80px;" placeholder="e.g. 100% Cotton, Breathable fabric, Tailored fit"><?php echo esc_textarea( $facts ); ?></textarea>
			</p>
			<p>
				<label for="ame_product_material"><strong><?php esc_html_e( 'Fabric & Material Composition', 'ame-bazaar' ); ?></strong></label><br />
				<input type="text" name="ame_product_material" id="ame_product_material" value="<?php echo esc_attr( $material ); ?>" style="width: 100%;" placeholder="e.g. Premium Cotton & Linen Blend" />
			</p>
			<p>
				<label for="ame_product_care"><strong><?php esc_html_e( 'Care & Washing Instructions', 'ame-bazaar' ); ?></strong></label><br />
				<input type="text" name="ame_product_care" id="ame_product_care" value="<?php echo esc_attr( $care ); ?>" style="width: 100%;" placeholder="e.g. Machine wash cold, dry in shade" />
			</p>
		</div>
		<div>
			<p>
				<label for="ame_product_size_fit"><strong><?php esc_html_e( 'Size & Fit Guide details', 'ame-bazaar' ); ?></strong></label><br />
				<input type="text" name="ame_product_size_fit" id="ame_product_size_fit" value="<?php echo esc_attr( $size_fit ); ?>" style="width: 100%;" placeholder="e.g. Standard Indian sizing, slim fit layout" />
			</p>
			<p>
				<label for="ame_product_occasion"><strong><?php esc_html_e( 'Occasion / Suitability', 'ame-bazaar' ); ?></strong></label><br />
				<input type="text" name="ame_product_occasion" id="ame_product_occasion" value="<?php echo esc_attr( $occasion ); ?>" style="width: 100%;" placeholder="e.g. Wedding, Festival, Casual wear" />
			</p>
			<p>
				<label for="ame_product_season"><strong><?php esc_html_e( 'Recommended Season', 'ame-bazaar' ); ?></strong></label><br />
				<input type="text" name="ame_product_season" id="ame_product_season" value="<?php echo esc_attr( $season ); ?>" style="width: 100%;" placeholder="e.g. Summer, All-season, Winter" />
			</p>
		</div>
	</div>
	
	<p style="margin-top: 20px;">
		<label for="ame_product_related_knowledge"><strong><?php esc_html_e( 'Link Related Knowledge Hub Guides', 'ame-bazaar' ); ?></strong></label><br />
		<select name="ame_product_related_knowledge[]" id="ame_product_related_knowledge" multiple="multiple" style="width: 100%; height: 100px;">
			<?php foreach ( $knowledge_options as $id => $title ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php echo in_array( $id, $selected_articles ) ? 'selected="selected"' : ''; ?>>
					<?php echo esc_html( $title ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php
}

/**
 * Render Page Guide Metabox.
 */
function ame_bazaar_render_page_guide_metabox( $post ) {
	wp_nonce_field( 'ame_bazaar_page_guide_save_meta', 'ame_page_guide_nonce' );

	$selected_cat = get_post_meta( $post->ID, '_ame_guide_wc_cat', true ) ?: '';
	$checklist = get_post_meta( $post->ID, '_ame_guide_checklist', true ) ?: '';

	// Query product categories
	$categories = array();
	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		) );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[ $term->slug ] = $term->name;
			}
		}
	}
	?>
	<p>
		<label for="ame_guide_wc_cat"><strong><?php esc_html_e( 'Linked Product Category', 'ame-bazaar' ); ?></strong></label><br />
		<select name="ame_guide_wc_cat" id="ame_guide_wc_cat" style="width: 100%;">
			<option value=""><?php esc_html_e( '-- Select Category --', 'ame-bazaar' ); ?></option>
			<?php foreach ( $categories as $slug => $name ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $slug, $selected_cat ); ?>>
					<?php echo esc_html( $name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="ame_guide_checklist"><strong><?php esc_html_e( 'Interactive Checklist (One item per line)', 'ame-bazaar' ); ?></strong></label><br />
		<textarea name="ame_guide_checklist" id="ame_guide_checklist" style="width: 100%; height: 100px;" placeholder="e.g. Verify shoulder size&#10;Check sleeve length"><?php echo esc_textarea( $checklist ); ?></textarea>
	</p>
	<?php
}

/**
 * Save product and page metadata.
 */
function ame_bazaar_save_product_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 1. Save Product Meta
	if ( isset( $_POST['ame_product_nonce'] ) && wp_verify_nonce( $_POST['ame_product_nonce'], 'ame_bazaar_product_save_meta' ) ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'_ame_product_facts'    => 'ame_product_facts',
			'_ame_product_material' => 'ame_product_material',
			'_ame_product_care'     => 'ame_product_care',
			'_ame_product_size_fit' => 'ame_product_size_fit',
			'_ame_product_occasion' => 'ame_product_occasion',
			'_ame_product_season'   => 'ame_product_season',
		);

		foreach ( $fields as $meta_key => $post_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				if ( '_ame_product_facts' === $meta_key ) {
					update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $post_key ] ) );
				} else {
					update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $post_key ] ) );
				}
			}
		}

		$related_articles = isset( $_POST['ame_product_related_knowledge'] ) ? array_map( 'intval', $_POST['ame_product_related_knowledge'] ) : array();
		update_post_meta( $post_id, '_ame_product_related_knowledge', $related_articles );
	}

	// 2. Save Page Guide Meta
	if ( isset( $_POST['ame_page_guide_nonce'] ) && wp_verify_nonce( $_POST['ame_page_guide_nonce'], 'ame_bazaar_page_guide_save_meta' ) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['ame_guide_wc_cat'] ) ) {
			update_post_meta( $post_id, '_ame_guide_wc_cat', sanitize_text_field( $_POST['ame_guide_wc_cat'] ) );
		}
		if ( isset( $_POST['ame_guide_checklist'] ) ) {
			update_post_meta( $post_id, '_ame_guide_checklist', sanitize_textarea_field( $_POST['ame_guide_checklist'] ) );
		}
	}
}
add_action( 'save_post', 'ame_bazaar_save_product_meta' );
