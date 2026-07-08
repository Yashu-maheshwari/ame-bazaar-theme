<?php
/**
 * Custom Post Type registration and Metaboxes for Knowledge Hub.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPT knowledge.
 */
function ame_bazaar_register_knowledge_cpt() {
	$labels = array(
		'name'               => _x( 'Knowledge Hub', 'post type general name', 'ame-bazaar' ),
		'singular_name'      => _x( 'Knowledge Article', 'post type singular name', 'ame-bazaar' ),
		'menu_name'          => _x( 'Knowledge Hub', 'admin menu', 'ame-bazaar' ),
		'name_admin_bar'     => _x( 'Knowledge Article', 'add new on admin bar', 'ame-bazaar' ),
		'add_new'            => _x( 'Add New', 'knowledge', 'ame-bazaar' ),
		'add_new_item'       => __( 'Add New Knowledge Article', 'ame-bazaar' ),
		'new_item'           => __( 'New Knowledge Article', 'ame-bazaar' ),
		'edit_item'          => __( 'Edit Knowledge Article', 'ame-bazaar' ),
		'view_item'          => __( 'View Knowledge Article', 'ame-bazaar' ),
		'all_items'          => __( 'All Articles', 'ame-bazaar' ),
		'search_items'       => __( 'Search Articles', 'ame-bazaar' ),
		'not_found'          => __( 'No articles found.', 'ame-bazaar' ),
		'not_found_in_trash' => __( 'No articles found in Trash.', 'ame-bazaar' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'knowledge', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'knowledge',
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-book-alt',
		'show_in_rest'       => true, // Enable Block Editor (Gutenberg)
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'knowledge', $args );
}
add_action( 'init', 'ame_bazaar_register_knowledge_cpt' );

/**
 * Register CPT Metaboxes
 */
function ame_bazaar_add_knowledge_metaboxes() {
	add_meta_box(
		'ame_bazaar_knowledge_faq_box',
		__( 'Structured FAQ (AI Retrieval & FAQPage Schema)', 'ame-bazaar' ),
		'ame_bazaar_render_faq_metabox',
		'knowledge',
		'normal',
		'high'
	);
	
	add_meta_box(
		'ame_bazaar_knowledge_relation_box',
		__( 'Relationships & WooCommerce Integration', 'ame-bazaar' ),
		'ame_bazaar_render_relation_metabox',
		'knowledge',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'ame_bazaar_add_knowledge_metaboxes' );

/**
 * Render FAQ Metabox
 */
function ame_bazaar_render_faq_metabox( $post ) {
	wp_nonce_field( 'ame_bazaar_knowledge_save_meta', 'ame_knowledge_nonce' );
	$faqs = get_post_meta( $post->ID, '_ame_knowledge_faqs', true );
	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}
	?>
	<div id="ame-faq-repeater">
		<table class="wp-list-table widefat fixed striped" style="margin-bottom: 10px;">
			<thead>
				<tr>
					<th style="width: 40%;"><?php esc_html_e( 'Question', 'ame-bazaar' ); ?></th>
					<th><?php esc_html_e( 'Answer', 'ame-bazaar' ); ?></th>
					<th style="width: 80px;"><?php esc_html_e( 'Actions', 'ame-bazaar' ); ?></th>
				</tr>
			</thead>
			<tbody id="ame-faq-tbody">
				<?php if ( empty( $faqs ) ) : ?>
					<tr class="faq-row">
						<td><input type="text" name="faq_questions[]" value="" style="width: 100%;" /></td>
						<td><textarea name="faq_answers[]" style="width: 100%; height: 60px;"></textarea></td>
						<td><button type="button" class="button remove-row"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $faqs as $faq ) : ?>
						<tr class="faq-row">
							<td><input type="text" name="faq_questions[]" value="<?php echo esc_attr( $faq['question'] ); ?>" style="width: 100%;" /></td>
							<td><textarea name="faq_answers[]" style="width: 100%; height: 60px;"><?php echo esc_textarea( $faq['answer'] ); ?></textarea></td>
							<td><button type="button" class="button remove-row"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<button type="button" id="add-faq-row" class="button button-primary"><?php esc_html_e( 'Add FAQ', 'ame-bazaar' ); ?></button>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const tbody = document.getElementById('ame-faq-tbody');
			const addBtn = document.getElementById('add-faq-row');
			
			if (addBtn && tbody) {
				addBtn.addEventListener('click', function() {
					const newRow = document.createElement('tr');
					newRow.className = 'faq-row';
					newRow.innerHTML = `
						<td><input type="text" name="faq_questions[]" value="" style="width: 100%;" /></td>
						<td><textarea name="faq_answers[]" style="width: 100%; height: 60px;"></textarea></td>
						<td><button type="button" class="button remove-row">Remove</button></td>
					`;
					tbody.appendChild(newRow);
				});
				
				tbody.addEventListener('click', function(e) {
					if (e.target && e.target.classList.contains('remove-row')) {
						const row = e.target.closest('tr');
						if (tbody.querySelectorAll('.faq-row').length > 1) {
							row.remove();
						} else {
							row.querySelector('input').value = '';
							row.querySelector('textarea').value = '';
						}
					}
				});
			}
		});
	</script>
	<?php
}

/**
 * Render Relation Metabox
 */
function ame_bazaar_render_relation_metabox( $post ) {
	$selected_cats = get_post_meta( $post->ID, '_ame_knowledge_wc_cats', true ) ?: array();
	$product_ids = get_post_meta( $post->ID, '_ame_knowledge_wc_products', true ) ?: '';
	$show_location = get_post_meta( $post->ID, '_ame_knowledge_show_location', true ) ?: 'no';
	$show_services = get_post_meta( $post->ID, '_ame_knowledge_show_services', true ) ?: 'no';

	// Get WooCommerce categories
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
		<label for="ame_knowledge_wc_cats"><strong><?php esc_html_e( 'Related Product Categories', 'ame-bazaar' ); ?></strong></label><br />
		<select name="ame_knowledge_wc_cats[]" id="ame_knowledge_wc_cats" multiple="multiple" style="width: 100%; height: 100px;">
			<?php foreach ( $categories as $slug => $name ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php echo in_array( $slug, $selected_cats, true ) ? 'selected="selected"' : ''; ?>>
					<?php echo esc_html( $name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="ame_knowledge_wc_products"><strong><?php esc_html_e( 'Featured Product IDs (Comma separated)', 'ame-bazaar' ); ?></strong></label><br />
		<input type="text" name="ame_knowledge_wc_products" id="ame_knowledge_wc_products" value="<?php echo esc_attr( $product_ids ); ?>" style="width: 100%;" placeholder="e.g. 101, 102, 103" />
	</p>
	<p>
		<label>
			<input type="checkbox" name="ame_knowledge_show_location" value="yes" <?php checked( $show_location, 'yes' ); ?> />
			<strong><?php esc_html_e( 'Show Location Authority block', 'ame-bazaar' ); ?></strong>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="ame_knowledge_show_services" value="yes" <?php checked( $show_services, 'yes' ); ?> />
			<strong><?php esc_html_e( 'Show Store Services block', 'ame-bazaar' ); ?></strong>
		</label>
	</p>
	<?php
}

/**
 * Save CPT meta data.
 */
function ame_bazaar_save_knowledge_meta( $post_id ) {
	if ( ! isset( $_POST['ame_knowledge_nonce'] ) || ! wp_verify_nonce( $_POST['ame_knowledge_nonce'], 'ame_bazaar_knowledge_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save FAQ
	$faqs = array();
	if ( isset( $_POST['faq_questions'] ) && isset( $_POST['faq_answers'] ) ) {
		$questions = $_POST['faq_questions'];
		$answers   = $_POST['faq_answers'];
		
		for ( $i = 0; $i < count( $questions ); $i++ ) {
			$q = sanitize_text_field( $questions[ $i ] );
			$a = sanitize_textarea_field( $answers[ $i ] );
			if ( ! empty( $q ) || ! empty( $a ) ) {
				$faqs[] = array(
					'question' => $q,
					'answer'   => $a,
				);
			}
		}
	}
	update_post_meta( $post_id, '_ame_knowledge_faqs', $faqs );

	// Save WC Categories
	$wc_cats = isset( $_POST['ame_knowledge_wc_cats'] ) ? array_map( 'sanitize_text_field', $_POST['ame_knowledge_wc_cats'] ) : array();
	update_post_meta( $post_id, '_ame_knowledge_wc_cats', $wc_cats );

	// Save WC Products
	$wc_products = isset( $_POST['ame_knowledge_wc_products'] ) ? sanitize_text_field( $_POST['ame_knowledge_wc_products'] ) : '';
	update_post_meta( $post_id, '_ame_knowledge_wc_products', $wc_products );

	// Save Checkboxes
	$show_location = isset( $_POST['ame_knowledge_show_location'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_ame_knowledge_show_location', $show_location );

	$show_services = isset( $_POST['ame_knowledge_show_services'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_ame_knowledge_show_services', $show_services );
}
add_action( 'save_post', 'ame_bazaar_save_knowledge_meta' );

/**
 * Programmatically create skeleton knowledge pages if they don't exist.
 */
function ame_bazaar_create_default_knowledge_posts() {
	$default_posts = array(
		'about-ame-bazaar' => 'About AME Bazaar',
		'business-facts'   => 'Business Facts',
		'store-services'   => 'Store Services',
		'fashion-guides'   => 'Fashion Guides',
	);
	foreach ( $default_posts as $slug => $title ) {
		$existing = get_page_by_path( $slug, OBJECT, 'knowledge' );
		if ( ! $existing ) {
			wp_insert_post( array(
				'post_name'   => $slug,
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => 'knowledge',
				'post_content'=> 'Default skeleton content for ' . $title,
			) );
		}
	}
}
add_action( 'init', 'ame_bazaar_create_default_knowledge_posts', 20 );
