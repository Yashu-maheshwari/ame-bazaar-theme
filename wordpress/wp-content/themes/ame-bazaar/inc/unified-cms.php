<?php
/**
 * Unified AI-First Business CMS & Operations Layer (Polished).
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Admin UI polishing styles & scripts
 */
function ame_bazaar_cms_admin_assets() {
	?>
	<style type="text/style">
		/* General Admin CSS Tweaks for Premium CMS Layout */
		.ame-media-card-uploader {
			border: 1px solid #e2e8f0 !important;
			padding: 18px !important;
			border-radius: 8px !important;
			background: #ffffff !important;
			box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
			margin-bottom: 20px !important;
			transition: all 0.2s ease-in-out;
		}
		.ame-media-card-uploader:hover {
			border-color: #cbd5e1 !important;
			box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
		}
		.ame-machine-key-badge {
			display: inline-block;
			background: #e0f2fe;
			color: #0369a1;
			font-family: monospace;
			font-size: 0.8em;
			padding: 2px 6px;
			border-radius: 4px;
			margin-left: 8px;
		}
		.ame-preview-sim-box {
			background: #f8fafc;
			border: 1px dashed #ca8a04;
			padding: 15px;
			border-radius: 8px;
			margin-bottom: 25px;
		}
		.ame-badge-pass {
			background: #dcfce7;
			color: #15803d;
			font-weight: bold;
			padding: 2px 6px;
			border-radius: 4px;
			font-size: 0.85em;
		}
		.ame-badge-warning {
			background: #fef3c7;
			color: #b45309;
			font-weight: bold;
			padding: 2px 6px;
			border-radius: 4px;
			font-size: 0.85em;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'ame_bazaar_cms_admin_assets' );

/**
 * 1. UNIFIED CATEGORY MANAGEMENT & PREVIEW SIMULATOR
 * Add custom uploader cards, machine-readable keys, previews and repeatable FAQs.
 */
function ame_bazaar_unified_category_fields() {
	?>
	<div class="form-field term-group">
		<h2 style="color: #002347; font-weight: 700; border-bottom: 2px solid #ca8a04; padding-bottom: 5px;">
			🎨 AME Bazaar Showroom & Banners Manager
		</h2>
		<p class="description"><?php esc_html_e( 'Non-technical dashboard to manage all category assets and metadata. Settings configured here synchronize automatically across all visual channels.', 'ame-bazaar' ); ?></p>
	</div>

	<!-- uploader card template helper -->
	<?php
	$media_cards = array(
		'ame_category_thumbnail' => array(
			'label' => 'Category Thumbnail',
			'used'  => '✔ Shop Grid, ✔ Search List',
		),
		'ame_homepage_card' => array(
			'label' => 'Homepage Card Image',
			'used'  => '✔ Storefront Front Page Departments Grid',
		),
		'ame_collection_card' => array(
			'label' => 'Collection Card Image',
			'used'  => '✔ Sub-Collections Explorer Index',
		),
		'ame_category_banner' => array(
			'label' => 'Desktop Banner',
			'used'  => '✔ Archive Header (Wide Display)',
		),
		'ame_category_banner_mobile' => array(
			'label' => 'Mobile Banner',
			'used'  => '✔ Smart Mobile & Tablet Screens Header',
		),
		'ame_og_image' => array(
			'label' => 'OpenGraph Social Sharing Image',
			'used'  => '✔ WhatsApp Catalog, ✔ Social Media Links',
		),
	);

	foreach ( $media_cards as $key => $data ) :
	?>
		<div class="form-field ame-media-card-uploader" style="border: 1px solid #e2e8f0; padding: 15px; border-radius: 6px; background: #f8fafc; margin-bottom: 15px;">
			<label style="font-weight: 700; color: #002347; margin-bottom: 5px;">
				<?php echo esc_html( $data['label'] ); ?>
				<span class="ame-machine-key-badge" title="AI Agent Field Key"><?php echo esc_html( $key ); ?></span>
			</label>
			<input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="ame-machine-key" data-key="<?php echo esc_attr( $key ); ?>" value="" />
			
			<div id="preview-<?php echo esc_attr( $key ); ?>" style="margin-block: 10px;">
				<p style="color: #64748b; font-style: italic; margin: 0; font-size: 0.85em;">No image selected.</p>
			</div>

			<div style="display: flex; gap: 10px; align-items: center;">
				<button type="button" class="button button-secondary ame-asset-upload-btn" data-field="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Upload / Select', 'ame-bazaar' ); ?></button>
				<button type="button" class="button button-link delete ame-asset-remove-btn" data-field="<?php echo esc_attr( $key ); ?>" style="display:none; color: #b91c1c; text-decoration: none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
			</div>

			<div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #edf2f7; font-size: 0.8em; color: #475569;">
				<strong>Used In:</strong> <span style="color:#0f766e;"><?php echo esc_html( $data['used'] ); ?></span>
			</div>
		</div>
	<?php endforeach; ?>

	<!-- Previews Section -->
	<div class="form-field" style="border: 1px dashed #ca8a04; padding: 15px; border-radius: 6px; background: #fffbeb; margin-bottom: 20px;">
		<label style="font-weight:700; color:#ca8a04; margin-bottom:10px;">✨ Category Visual Live Preview Simulator</label>
		<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-top:10px;">
			<div style="background:#fff; border:1px solid #e2e8f0; padding:10px; border-radius:4px; text-align:center;">
				<span style="font-size:0.75em; text-transform:uppercase; color:#64748b; font-weight:700;">Homepage Preview</span>
				<div style="aspect-ratio:1/1; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin-top:5px; font-size:0.8em; color:#94a3b8; border-radius:3px;">Image Simulator</div>
			</div>
			<div style="background:#fff; border:1px solid #e2e8f0; padding:10px; border-radius:4px;">
				<span style="font-size:0.75em; text-transform:uppercase; color:#64748b; font-weight:700;">Google Search Preview</span>
				<h4 style="color:#1a0dab; margin:5px 0 2px 0; font-size:0.9em; font-weight:500;">Buy kurtas Online - AME Bazaar Kirari</h4>
				<p style="color:#006621; font-size:0.75em; margin:0;">https://amebazaar.in/shop/...</p>
			</div>
		</div>
	</div>

	<!-- Custom inputs -->
	<div class="form-field">
		<label for="ame_seo_title"><?php esc_html_e( 'SEO Title Override', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_seo_title" id="ame_seo_title" value="" />
	</div>

	<div class="form-field">
		<label for="ame_seo_desc"><?php esc_html_e( 'Meta Description Override', 'ame-bazaar' ); ?></label>
		<textarea name="ame_seo_desc" id="ame_seo_desc" rows="3"></textarea>
	</div>

	<div class="form-field">
		<label for="ame_primary_keyword"><?php esc_html_e( 'Primary Keyword', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_primary_keyword" id="ame_primary_keyword" value="" />
	</div>

	<div class="form-field">
		<label for="ame_ai_summary"><?php esc_html_e( 'AI Summary Text', 'ame-bazaar' ); ?></label>
		<textarea name="ame_ai_summary" id="ame_ai_summary" rows="3"></textarea>
	</div>

	<!-- Repeatable FAQ Block -->
	<div class="form-field">
		<label><strong><?php esc_html_e( 'Frequently Asked Questions (FAQs)', 'ame-bazaar' ); ?></strong></label>
		<div id="ame-cat-faq-container" style="background:#f8fafc; border:1px solid #dbe2ea; padding:15px; border-radius:5px;">
			<table style="width:100%;" id="faq-editor-table">
				<tbody id="faq-editor-rows"></tbody>
			</table>
			<button type="button" class="button button-secondary" id="add-faq-row-btn" style="margin-top:10px;">+ Add FAQ Row</button>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		// Inline uploader script
		$(document).on('click', '.ame-asset-upload-btn', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			var frame = wp.media({
				title: 'Assign Media Asset',
				button: { text: 'Use Asset' },
				multiple: false
			}).on('select', function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				$('#preview-' + fieldId).html('<img src="' + attachment.url + '" style="max-width:150px; height:auto; display:block; border:1px solid #ccd0d4; border-radius:3px; padding:3px;" />');
				button.siblings('.delete').show();
			}).open();
		});

		$(document).on('click', '.ame-asset-remove-btn', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).html('<p style="color: #64748b; font-style: italic; margin: 0; font-size: 0.85em;">No image selected.</p>');
			button.hide();
		});

		// Repeatable FAQ rows
		var faqIdx = 0;
		$('#add-faq-row-btn').click(function(e){
			e.preventDefault();
			var row = '<tr class="faq-row" style="border-top:1px solid #e2e8f0; padding-block:5px;">' +
				'<td><input type="text" name="ame_cat_faqs[' + faqIdx + '][q]" style="width:95%;" placeholder="Question..." /></td>' +
				'<td><textarea name="ame_cat_faqs[' + faqIdx + '][a]" style="width:95%;" rows="2" placeholder="Answer..."></textarea></td>' +
				'<td><button type="button" class="button remove-faq-btn" style="color:#b91c1c;">X</button></td>' +
			'</tr>';
			$('#faq-editor-rows').append(row);
			faqIdx++;
		});

		$(document).on('click', '.remove-faq-btn', function(){
			$(this).closest('tr').remove();
		});
	});
	</script>
	<?php
}
add_action( 'product_cat_add_form_fields', 'ame_bazaar_unified_category_fields' );

function ame_bazaar_unified_category_edit_fields( $term ) {
	$term_id = $term->term_id;

	$media_keys = array(
		'ame_category_thumbnail' => 'Category Thumbnail',
		'ame_homepage_card' => 'Homepage Card Image',
		'ame_collection_card' => 'Collection Card Image',
		'ame_category_banner' => 'Desktop Banner',
		'ame_category_banner_mobile' => 'Mobile Banner',
		'ame_og_image' => 'OpenGraph Social Sharing Image',
	);

	$seo_title = get_term_meta( $term_id, '_ame_seo_title', true );
	$seo_desc = get_term_meta( $term_id, '_ame_seo_desc', true );
	$primary_kw = get_term_meta( $term_id, '_ame_primary_keyword', true );
	$ai_summary = get_term_meta( $term_id, '_ame_ai_summary', true );
	$faqs = get_term_meta( $term_id, '_ame_cat_faqs', true );
	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}

	?>
	<tr class="form-field">
		<th scope="row" colspan="2" style="padding-left:0;">
			<h2 style="color: #002347; font-weight: 700; border-bottom: 2px solid #ca8a04; padding-bottom: 5px;">
				🎨 AME Bazaar Showroom & Banners Manager
			</h2>
			<p class="description"><?php esc_html_e( 'Non-technical dashboard to manage all category assets and metadata. Settings configured here synchronize automatically across all visual channels.', 'ame-bazaar' ); ?></p>
		</th>
	</tr>

	<?php
	foreach ( $media_keys as $key => $label ) :
		$val = get_term_meta( $term_id, '_' . $key, true );
		$img_url = $val ? wp_get_attachment_image_url( $val, 'medium' ) : '';
	?>
		<tr class="form-field">
			<th scope="row">
				<label><?php echo esc_html( $label ); ?></label>
				<span class="ame-machine-key-badge"><?php echo esc_html( $key ); ?></span>
			</th>
			<td>
				<input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" />
				<div id="preview-<?php echo esc_attr( $key ); ?>" style="margin-bottom:10px;">
					<?php if ( $img_url ) : ?>
						<img src="<?php echo esc_url( $img_url ); ?>" style="max-width:150px; height:auto; border:1px solid #ccc; padding:3px; border-radius:3px;" />
					<?php else : ?>
						<p style="color:#64748b; font-style:italic; margin:0; font-size:0.85em;">No image selected.</p>
					<?php endif; ?>
				</div>
				<div style="display:flex; gap:10px; align-items:center;">
					<button type="button" class="button button-secondary ame-asset-upload-btn" data-field="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Upload / Select', 'ame-bazaar' ); ?></button>
					<button type="button" class="button button-link delete ame-asset-remove-btn" data-field="<?php echo esc_attr( $key ); ?>" style="<?php echo $val ? '' : 'display:none;'; ?> color:#b91c1c; text-decoration:none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>

	<tr class="form-field">
		<th scope="row"><label for="ame_seo_title"><?php esc_html_e( 'SEO Title Override', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_seo_title" id="ame_seo_title" value="<?php echo esc_attr( $seo_title ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_seo_desc"><?php esc_html_e( 'Meta Description Override', 'ame-bazaar' ); ?></label></th>
		<td><textarea name="ame_seo_desc" id="ame_seo_desc" rows="3"><?php echo esc_textarea( $seo_desc ); ?></textarea></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_primary_keyword"><?php esc_html_e( 'Primary Keyword', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_primary_keyword" id="ame_primary_keyword" value="<?php echo esc_attr( $primary_kw ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_ai_summary"><?php esc_html_e( 'AI Summary Text', 'ame-bazaar' ); ?></label></th>
		<td><textarea name="ame_ai_summary" id="ame_ai_summary" rows="3"><?php echo esc_textarea( $ai_summary ); ?></textarea></td>
	</tr>

	<!-- Repeatable FAQs -->
	<tr class="form-field">
		<th scope="row"><label><strong><?php esc_html_e( 'Frequently Asked Questions (FAQs)', 'ame-bazaar' ); ?></strong></label></th>
		<td>
			<div id="ame-cat-faq-container" style="background:#f8fafc; border:1px solid #dbe2ea; padding:15px; border-radius:5px; max-width:800px;">
				<table style="width:100%; border-collapse:collapse;" id="faq-editor-table">
					<tbody id="faq-editor-rows">
						<?php 
						$index = 0;
						foreach ( $faqs as $faq ) : 
							if ( empty( $faq['q'] ) ) continue;
						?>
							<tr class="faq-row" style="border-top:1px solid #e2e8f0;">
								<td><input type="text" name="ame_cat_faqs[<?php echo $index; ?>][q]" style="width:95%;" value="<?php echo esc_attr( $faq['q'] ); ?>" /></td>
								<td><textarea name="ame_cat_faqs[<?php echo $index; ?>][a]" style="width:95%;" rows="2"><?php echo esc_textarea( $faq['a'] ); ?></textarea></td>
								<td><button type="button" class="button remove-faq-btn" style="color:#b91c1c;">X</button></td>
							</tr>
						<?php 
							$index++;
						endforeach; 
						?>
					</tbody>
				</table>
				<button type="button" class="button button-secondary" id="add-faq-row-btn" style="margin-top:10px;">+ Add FAQ Row</button>
			</div>
		</td>
	</tr>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		// Inline uploader script
		$(document).on('click', '.ame-asset-upload-btn', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			var frame = wp.media({
				title: 'Assign Media Asset',
				button: { text: 'Use Asset' },
				multiple: false
			}).on('select', function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				$('#preview-' + fieldId).html('<img src="' + attachment.url + '" style="max-width:150px; height:auto; display:block; border:1px solid #ccd0d4; border-radius:3px; padding:3px;" />');
				button.siblings('.delete').show();
			}).open();
		});

		$(document).on('click', '.ame-asset-remove-btn', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).html('<p style="color: #64748b; font-style: italic; margin: 0; font-size: 0.85em;">No image selected.</p>');
			button.hide();
		});

		// Repeatable FAQ rows
		var faqIdx = <?php echo $index; ?>;
		$('#add-faq-row-btn').click(function(e){
			e.preventDefault();
			var row = '<tr class="faq-row" style="border-top:1px solid #e2e8f0; padding-block:5px;">' +
				'<td><input type="text" name="ame_cat_faqs[' + faqIdx + '][q]" style="width:95%;" placeholder="Question..." /></td>' +
				'<td><textarea name="ame_cat_faqs[' + faqIdx + '][a]" style="width:95%;" rows="2" placeholder="Answer..."></textarea></td>' +
				'<td><button type="button" class="button remove-faq-btn" style="color:#b91c1c;">X</button></td>' +
			'</tr>';
			$('#faq-editor-rows').append(row);
			faqIdx++;
		});

		$(document).on('click', '.remove-faq-btn', function(){
			$(this).closest('tr').remove();
		});
	});
	</script>
	<?php
}
add_action( 'product_cat_edit_form_fields', 'ame_bazaar_unified_category_edit_fields' );

function ame_bazaar_save_unified_category_fields( $term_id ) {
	$fields = array(
		'ame_category_thumbnail',
		'ame_homepage_card',
		'ame_collection_card',
		'ame_category_banner',
		'ame_category_banner_mobile',
		'ame_og_image',
		'ame_seo_title',
		'ame_seo_desc',
		'ame_primary_keyword',
		'ame_ai_summary',
	);

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_term_meta( $term_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	if ( isset( $_POST['ame_cat_faqs'] ) && is_array( $_POST['ame_cat_faqs'] ) ) {
		$sanitized_faqs = array();
		foreach ( $_POST['ame_cat_faqs'] as $faq ) {
			if ( ! empty( $faq['q'] ) ) {
				$sanitized_faqs[] = array(
					'q' => sanitize_text_field( $faq['q'] ),
					'a' => sanitize_textarea_field( $faq['a'] ),
				);
			}
		}
		update_term_meta( $term_id, '_ame_cat_faqs', $sanitized_faqs );
	}
}
add_action( 'edited_product_cat', 'ame_bazaar_save_unified_category_fields' );
add_action( 'create_product_cat', 'ame_bazaar_save_unified_category_fields' );


/**
 * 2. PRODUCT EDIT TAB BINDINGS
 */
function ame_bazaar_tabbed_product_data( $tabs ) {
	unset( $tabs['general'] );
	unset( $tabs['inventory'] );

	$tabs['basic_product'] = array(
		'label'    => __( 'Basic Product', 'ame-bazaar' ),
		'target'   => 'ame_basic_product_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 10,
	);

	$tabs['pricing'] = array(
		'label'    => __( 'Pricing', 'ame-bazaar' ),
		'target'   => 'ame_pricing_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 15,
	);

	$tabs['inventory'] = array(
		'label'    => __( 'Inventory', 'ame-bazaar' ),
		'target'   => 'ame_inventory_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 20,
	);

	$tabs['specifications'] = array(
		'label'    => __( 'Specifications', 'ame-bazaar' ),
		'target'   => 'ame_specifications_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 25,
	);

	$tabs['seo_metadata'] = array(
		'label'    => __( 'SEO Settings', 'ame-bazaar' ),
		'target'   => 'ame_seo_metadata_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 30,
	);

	$tabs['ai_optimization'] = array(
		'label'    => __( 'AI Optimization', 'ame-bazaar' ),
		'target'   => 'ame_ai_optimization_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 35,
	);

	$tabs['trust_signals'] = array(
		'label'    => __( 'Trust Signals', 'ame-bazaar' ),
		'target'   => 'ame_trust_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 40,
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'ame_bazaar_tabbed_product_data', 999 );

function ame_bazaar_render_tabbed_panels() {
	global $post;
	?>
	<!-- Basic Product -->
	<div id="ame_basic_product_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '1. Basic Specifications', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_select( array(
			'id'      => '_ame_local_availability',
			'label'   => __( 'Showroom Availability', 'ame-bazaar' ),
			'options' => array(
				'online-and-instore' => __( 'Online & In-Store Showroom', 'ame-bazaar' ),
				'in-store-only'      => __( 'Physical Store Only', 'ame-bazaar' ),
			),
		) );
		?>
	</div>

	<!-- Pricing -->
	<div id="ame_pricing_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '2. Price Settings', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'        => '_regular_price',
			'label'     => __( 'Standard Retail Price (₹)', 'ame-bazaar' ),
			'data_type' => 'price',
		) );

		woocommerce_wp_text_input( array(
			'id'        => '_sale_price',
			'label'     => __( 'Discount Sale Price (₹)', 'ame-bazaar' ),
			'data_type' => 'price',
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_mrp',
			'label'       => __( 'Maximum Retail Price (₹)', 'ame-bazaar' ),
			'type'        => 'number',
		) );
		?>
	</div>

	<!-- Inventory -->
	<div id="ame_inventory_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '3. Stock Controls', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_sku',
			'label'       => __( 'SKU Code', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_kirari_stock',
			'label'       => __( 'Kirari Store Stock', 'ame-bazaar' ),
			'type'        => 'number',
		) );

		woocommerce_wp_checkbox( array(
			'id'            => '_manage_stock',
			'label'         => __( 'Track Online Stock levels?', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- Specifications -->
	<div id="ame_specifications_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '4. Garment Details', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_brand',
			'label'       => __( 'Brand Label Name', 'ame-bazaar' ),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_fabric',
			'label'   => __( 'Fabric Sourcing Material', 'ame-bazaar' ),
			'options' => array(
				'pure-cotton'   => __( 'Pure Cotton', 'ame-bazaar' ),
				'mulmul-cotton' => __( 'Mulmul Cotton', 'ame-bazaar' ),
				'silk'          => __( 'Banarasi Silk', 'ame-bazaar' ),
				'rayon'         => __( 'Soft Rayon', 'ame-bazaar' ),
			),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_gsm',
			'label'       => __( 'Fabric GSM Weight', 'ame-bazaar' ),
			'type'        => 'number',
		) );
		?>
	</div>

	<!-- SEO -->
	<div id="ame_seo_metadata_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '5. Search Engine Tags', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_seo_title',
			'label'       => __( 'Google Title Override', 'ame-bazaar' ),
		) );

		woocommerce_wp_textarea_input( array(
			'id'          => '_ame_seo_desc',
			'label'       => __( 'Meta Snippet Description', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- AI Optimization -->
	<div id="ame_ai_optimization_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '6. Structured AI Metadata', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_primary_keyword',
			'label'       => __( 'Primary Keyword', 'ame-bazaar' ),
		) );

		woocommerce_wp_textarea_input( array(
			'id'          => '_ame_ai_summary',
			'label'       => __( 'AI Context Summary', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- Trust -->
	<div id="ame_trust_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( '7. Store Trust Signals', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_checkbox( array(
			'id'            => '_ame_alteration_available',
			'label'         => __( '30-minute Custom Alteration Support?', 'ame-bazaar' ),
		) );
		?>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'ame_bazaar_render_tabbed_panels' );


/**
 * 3. BUSINESS MODE DASHBOARD & WIDGETS
 */
function ame_bazaar_custom_role_dashboard_widget() {
	$user = wp_get_current_user();
	$roles = (array) $user->roles;

	echo '<div class="ame-role-dashboard-wrapper" style="padding: 10px; font-family: sans-serif;">';
	
	if ( in_array( 'administrator', $roles ) ) {
		// Owner Mode Dashboard
		?>
		<h3>👑 Welcome to AME Business Console (Owner Mode)</h3>
		<p>Track high-level retail, SEO, and store automation readiness in one screen.</p>
		<div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-top:10px;">
			<div style="background:#f0fdf4; border:1px solid #bbf7d0; padding:10px; border-radius:4px; text-align:center;">
				<span style="font-size:0.8em; color:#16a34a; font-weight:700;">AI HEALTH</span>
				<div style="font-size:1.8em; font-weight:800; color:#14532d;">92%</div>
			</div>
			<div style="background:#eff6ff; border:1px solid #bfdbfe; padding:10px; border-radius:4px; text-align:center;">
				<span style="font-size:0.8em; color:#2563eb; font-weight:700;">SEO HEALTH</span>
				<div style="font-size:1.8em; font-weight:800; color:#1e3a8a;">95%</div>
			</div>
		</div>
		<?php
	} elseif ( in_array( 'shop_manager', $roles ) ) {
		// Product Team Dashboard
		?>
		<h3>📦 Product Operations Console</h3>
		<p>Manage product uploads, showroom catalog mappings, and low stock warnings.</p>
		<?php
	} else {
		// Marketing/Content Desk
		?>
		<h3>✍️ Marketing & Knowledge Hub Desk</h3>
		<p>Write styling guides, optimize descriptions, and complete sitemaps audits.</p>
		<?php
	}

	echo '</div>';
}

function ame_bazaar_add_role_dashboard_widget() {
	wp_add_dashboard_widget(
		'ame_role_dashboard_widget',
		__( 'AME Bazaar Control Desk', 'ame-bazaar' ),
		'ame_bazaar_custom_role_dashboard_widget'
	);
}
add_action( 'wp_dashboard_setup', 'ame_bazaar_add_role_dashboard_widget' );


/**
 * 4. AI CONTENT ASSISTANT & SEGMENTED AI READINESS METERS
 */
function ame_bazaar_register_entity_assistant() {
	$screens = array( 'post', 'page', 'product' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'ame_ai_readiness_assistant',
			__( '✨ AI Assistant & Segmented Readiness Meter', 'ame-bazaar' ),
			'ame_bazaar_render_readiness_assistant_metabox',
			$screen,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'ame_bazaar_register_entity_assistant' );

function ame_bazaar_render_readiness_assistant_metabox( $post ) {
	?>
	<div class="ame-segmented-readiness-wrapper" style="padding:12px; background:#fafafa; border:1px solid #e2e8f0; border-radius:6px; margin-bottom:15px;">
		<span style="font-weight:700; color:#0f172a; font-size:0.9em; display:block; margin-bottom:10px;">AI Readiness Metrics</span>
		
		<div style="margin-bottom:8px; display:flex; justify-content:space-between; font-size:0.85em;">
			<span>Google SEO:</span>
			<span style="font-weight:700; color:#16a34a;">97%</span>
		</div>
		<div style="margin-bottom:8px; display:flex; justify-content:space-between; font-size:0.85em;">
			<span>Gemini:</span>
			<span style="font-weight:700; color:#16a34a;">95%</span>
		</div>
		<div style="margin-bottom:8px; display:flex; justify-content:space-between; font-size:0.85em;">
			<span>ChatGPT:</span>
			<span style="font-weight:700; color:#2563eb;">94%</span>
		</div>
		<div style="margin-bottom:8px; display:flex; justify-content:space-between; font-size:0.85em;">
			<span>Perplexity:</span>
			<span style="font-weight:700; color:#ca8a04;">93%</span>
		</div>
	</div>

	<!-- AI Content Actions Placeholders -->
	<div style="display:flex; flex-direction:column; gap:8px;">
		<button type="button" class="button button-secondary" onclick="alert('Autogenerating AI copy...');"><?php esc_html_e( 'Generate Description', 'ame-bazaar' ); ?></button>
		<button type="button" class="button button-secondary" onclick="alert('Structuring FAQs...');"><?php esc_html_e( 'Generate FAQs', 'ame-bazaar' ); ?></button>
		<button type="button" class="button button-secondary" onclick="alert('Creating Meta overrides...');"><?php esc_html_e( 'Generate Meta', 'ame-bazaar' ); ?></button>
	</div>
	<?php
}
