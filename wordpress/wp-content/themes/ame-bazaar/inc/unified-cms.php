<?php
/**
 * Unified Business CMS, Media Manager, and AI-First Content Assistant.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. UNIFIED CATEGORY MANAGEMENT
 * Add custom fields (banners, SEO, FAQs, and AI metadata) to product categories.
 */
function ame_bazaar_cat_add_meta_fields() {
	?>
	<div class="form-field term-group">
		<h2><?php esc_html_e( 'AME Bazaar AI Showroom Settings', 'ame-bazaar' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Configure banners, FAQs, SEO and AI metadata directly. No copy-pasting URLs required.', 'ame-bazaar' ); ?></p>
	</div>

	<!-- Media Uploaders -->
	<div class="form-field">
		<label><?php esc_html_e( 'Desktop Banner', 'ame-bazaar' ); ?></label>
		<input type="hidden" name="ame_cat_desktop_banner_id" id="ame_cat_desktop_banner_id" value="" />
		<div id="ame_cat_desktop_banner_preview" style="margin-bottom:10px;"></div>
		<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_desktop_banner_id"><?php esc_html_e( 'Upload Desktop Banner', 'ame-bazaar' ); ?></button>
		<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_desktop_banner_id" style="display:none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
	</div>

	<div class="form-field">
		<label><?php esc_html_e( 'Mobile Banner', 'ame-bazaar' ); ?></label>
		<input type="hidden" name="ame_cat_mobile_banner_id" id="ame_cat_mobile_banner_id" value="" />
		<div id="ame_cat_mobile_preview" style="margin-bottom:10px;"></div>
		<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_mobile_banner_id"><?php esc_html_e( 'Upload Mobile Banner', 'ame-bazaar' ); ?></button>
		<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_mobile_banner_id" style="display:none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
	</div>

	<div class="form-field">
		<label><?php esc_html_e( 'Featured Collection Image', 'ame-bazaar' ); ?></label>
		<input type="hidden" name="ame_cat_featured_image_id" id="ame_cat_featured_image_id" value="" />
		<div id="ame_cat_featured_preview" style="margin-bottom:10px;"></div>
		<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_featured_image_id"><?php esc_html_e( 'Upload Featured Image', 'ame-bazaar' ); ?></button>
		<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_featured_image_id" style="display:none;"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
	</div>

	<!-- SEO & Metadata -->
	<div class="form-field">
		<label for="ame_cat_seo_title"><?php esc_html_e( 'SEO Title Override', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_seo_title" id="ame_cat_seo_title" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_seo_desc"><?php esc_html_e( 'Meta Description Override', 'ame-bazaar' ); ?></label>
		<textarea name="ame_cat_seo_desc" id="ame_cat_seo_desc" rows="3"></textarea>
	</div>

	<!-- Structured AI fields -->
	<div class="form-field">
		<label for="ame_cat_primary_keyword"><?php esc_html_e( 'Primary Keyword', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_primary_keyword" id="ame_cat_primary_keyword" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_secondary_keywords"><?php esc_html_e( 'Secondary Keywords', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_secondary_keywords" id="ame_cat_secondary_keywords" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_search_intent"><?php esc_html_e( 'Search Intent', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_search_intent" id="ame_cat_search_intent" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_target_audience"><?php esc_html_e( 'Target Audience', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_target_audience" id="ame_cat_target_audience" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_ai_summary"><?php esc_html_e( 'AI Summary', 'ame-bazaar' ); ?></label>
		<textarea name="ame_cat_ai_summary" id="ame_cat_ai_summary" rows="3"></textarea>
	</div>

	<div class="form-field">
		<label for="ame_cat_product_entity"><?php esc_html_e( 'Product Entity Type', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_product_entity" id="ame_cat_product_entity" value="" />
	</div>

	<div class="form-field">
		<label for="ame_cat_geo_target"><?php esc_html_e( 'GEO Target Location', 'ame-bazaar' ); ?></label>
		<input type="text" name="ame_cat_geo_target" id="ame_cat_geo_target" value="" />
	</div>

	<!-- Repeatable FAQs -->
	<div class="form-field">
		<label><strong><?php esc_html_e( 'Frequently Asked Questions (FAQs)', 'ame-bazaar' ); ?></strong></label>
		<div id="ame-cat-faq-container" style="background:#f8fafc; border:1px solid #dbe2ea; padding:10px; border-radius:5px;">
			<table id="ame-cat-faq-table" style="width:100%; border-collapse:collapse;">
				<thead>
					<tr>
						<th style="text-align:left; padding-bottom:5px;">Question</th>
						<th style="text-align:left; padding-bottom:5px;">Answer</th>
						<th style="width:50px;"></th>
					</tr>
				</thead>
				<tbody id="ame-cat-faq-rows">
				</tbody>
			</table>
			<button type="button" class="button button-secondary" id="ame-add-cat-faq-btn" style="margin-top:10px;">+ Add FAQ Row</button>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		// Inline uploader binding
		$(document).on('click', '.ame-cat-media-upload', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			var mediaUploader = wp.media({
				title: 'Select Category Media',
				button: { text: 'Assign Image' },
				multiple: false
			}).on('select', function(){
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				$('#preview-' + fieldId).remove();
				button.siblings('.delete').show();
				button.parent().find('div').html('<img id="preview-' + fieldId + '" src="' + attachment.url + '" style="max-width:120px; height:auto; display:block; border:1px solid #ccc; padding:3px; margin-top:5px;" />');
			}).open();
		});

		$(document).on('click', '.ame-cat-media-remove', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).remove();
			button.hide();
		});

		// Repeatable FAQs
		var faqIndex = 0;
		$('#ame-add-cat-faq-btn').click(function(e){
			e.preventDefault();
			var row = '<tr class="faq-row" style="border-top:1px solid #e2e8f0;">' +
				'<td><input type="text" name="ame_cat_faqs[' + faqIndex + '][q]" style="width:95%;" placeholder="Question..." /></td>' +
				'<td><textarea name="ame_cat_faqs[' + faqIndex + '][a]" style="width:95%;" rows="2" placeholder="Answer..."></textarea></td>' +
				'<td><button type="button" class="button remove-faq-row-btn" style="color:#b91c1c;">X</button></td>' +
			'</tr>';
			$('#ame-cat-faq-rows').append(row);
			faqIndex++;
		});

		$(document).on('click', '.remove-faq-row-btn', function(){
			$(this).closest('tr').remove();
		});
	});
	</script>
	<?php
}
add_action( 'product_cat_add_form_fields', 'ame_bazaar_cat_add_meta_fields' );

function ame_bazaar_cat_edit_meta_fields( $term ) {
	$term_id = $term->term_id;
	$desktop_id = get_term_meta( $term_id, '_ame_cat_desktop_banner_id', true );
	$mobile_id = get_term_meta( $term_id, '_ame_cat_mobile_banner_id', true );
	$featured_id = get_term_meta( $term_id, '_ame_cat_featured_image_id', true );

	$desktop_url = $desktop_id ? wp_get_attachment_image_url( $desktop_id, 'medium' ) : '';
	$mobile_url = $mobile_id ? wp_get_attachment_image_url( $mobile_id, 'medium' ) : '';
	$featured_url = $featured_id ? wp_get_attachment_image_url( $featured_id, 'medium' ) : '';

	$seo_title = get_term_meta( $term_id, '_ame_cat_seo_title', true );
	$seo_desc = get_term_meta( $term_id, '_ame_cat_seo_desc', true );
	$primary_kw = get_term_meta( $term_id, '_ame_cat_primary_keyword', true );
	$secondary_kw = get_term_meta( $term_id, '_ame_cat_secondary_keywords', true );
	$intent = get_term_meta( $term_id, '_ame_cat_search_intent', true );
	$audience = get_term_meta( $term_id, '_ame_cat_target_audience', true );
	$ai_summary = get_term_meta( $term_id, '_ame_cat_ai_summary', true );
	$entity_type = get_term_meta( $term_id, '_ame_cat_product_entity', true );
	$geo_target = get_term_meta( $term_id, '_ame_cat_geo_target', true );
	$faqs = get_term_meta( $term_id, '_ame_cat_faqs', true );
	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}
	?>
	<tr class="form-field">
		<th scope="row" colspan="2" style="padding-left:0;">
			<h2><?php esc_html_e( 'AME Bazaar AI Showroom Settings', 'ame-bazaar' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Configure banners, FAQs, SEO and AI metadata directly. No copy-pasting URLs required.', 'ame-bazaar' ); ?></p>
		</th>
	</tr>

	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'Desktop Banner', 'ame-bazaar' ); ?></label></th>
		<td>
			<input type="hidden" name="ame_cat_desktop_banner_id" id="ame_cat_desktop_banner_id" value="<?php echo esc_attr( $desktop_id ); ?>" />
			<div id="preview-ame_cat_desktop_banner_id" style="margin-bottom:10px;">
				<?php if ( $desktop_url ) : ?>
					<img src="<?php echo esc_url( $desktop_url ); ?>" style="max-width:120px; height:auto; border:1px solid #ccc; padding:3px;" />
				<?php endif; ?>
			</div>
			<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_desktop_banner_id"><?php esc_html_e( 'Upload Desktop Banner', 'ame-bazaar' ); ?></button>
			<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_desktop_banner_id" style="<?php echo $desktop_id ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'Mobile Banner', 'ame-bazaar' ); ?></label></th>
		<td>
			<input type="hidden" name="ame_cat_mobile_banner_id" id="ame_cat_mobile_banner_id" value="<?php echo esc_attr( $mobile_id ); ?>" />
			<div id="preview-ame_cat_mobile_banner_id" style="margin-bottom:10px;">
				<?php if ( $mobile_url ) : ?>
					<img src="<?php echo esc_url( $mobile_url ); ?>" style="max-width:120px; height:auto; border:1px solid #ccc; padding:3px;" />
				<?php endif; ?>
			</div>
			<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_mobile_banner_id"><?php esc_html_e( 'Upload Mobile Banner', 'ame-bazaar' ); ?></button>
			<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_mobile_banner_id" style="<?php echo $mobile_id ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'Featured Collection Image', 'ame-bazaar' ); ?></label></th>
		<td>
			<input type="hidden" name="ame_cat_featured_image_id" id="ame_cat_featured_image_id" value="<?php echo esc_attr( $featured_id ); ?>" />
			<div id="preview-ame_cat_featured_image_id" style="margin-bottom:10px;">
				<?php if ( $featured_url ) : ?>
					<img src="<?php echo esc_url( $featured_url ); ?>" style="max-width:120px; height:auto; border:1px solid #ccc; padding:3px;" />
				<?php endif; ?>
			</div>
			<button class="button button-secondary ame-cat-media-upload" data-field="ame_cat_featured_image_id"><?php esc_html_e( 'Upload Featured Image', 'ame-bazaar' ); ?></button>
			<button class="button button-link delete ame-cat-media-remove" data-field="ame_cat_featured_image_id" style="<?php echo $featured_id ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'ame-bazaar' ); ?></button>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_seo_title"><?php esc_html_e( 'SEO Title Override', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_seo_title" id="ame_cat_seo_title" value="<?php echo esc_attr( $seo_title ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_seo_desc"><?php esc_html_e( 'Meta Description Override', 'ame-bazaar' ); ?></label></th>
		<td><textarea name="ame_cat_seo_desc" id="ame_cat_seo_desc" rows="3"><?php echo esc_textarea( $seo_desc ); ?></textarea></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_primary_keyword"><?php esc_html_e( 'Primary Keyword', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_primary_keyword" id="ame_cat_primary_keyword" value="<?php echo esc_attr( $primary_kw ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_secondary_keywords"><?php esc_html_e( 'Secondary Keywords', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_secondary_keywords" id="ame_cat_secondary_keywords" value="<?php echo esc_attr( $secondary_kw ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_search_intent"><?php esc_html_e( 'Search Intent', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_search_intent" id="ame_cat_search_intent" value="<?php echo esc_attr( $intent ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_target_audience"><?php esc_html_e( 'Target Audience', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_target_audience" id="ame_cat_target_audience" value="<?php echo esc_attr( $audience ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_ai_summary"><?php esc_html_e( 'AI Summary', 'ame-bazaar' ); ?></label></th>
		<td><textarea name="ame_cat_ai_summary" id="ame_cat_ai_summary" rows="3"><?php echo esc_textarea( $ai_summary ); ?></textarea></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_product_entity"><?php esc_html_e( 'Product Entity Type', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_product_entity" id="ame_cat_product_entity" value="<?php echo esc_attr( $entity_type ); ?>" /></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="ame_cat_geo_target"><?php esc_html_e( 'GEO Target Location', 'ame-bazaar' ); ?></label></th>
		<td><input type="text" name="ame_cat_geo_target" id="ame_cat_geo_target" value="<?php echo esc_attr( $geo_target ); ?>" /></td>
	</tr>

	<!-- Repeatable FAQs -->
	<tr class="form-field">
		<th scope="row"><label><strong><?php esc_html_e( 'Frequently Asked Questions (FAQs)', 'ame-bazaar' ); ?></strong></label></th>
		<td>
			<div id="ame-cat-faq-container" style="background:#f8fafc; border:1px solid #dbe2ea; padding:10px; border-radius:5px; max-width:800px;">
				<table id="ame-cat-faq-table" style="width:100%; border-collapse:collapse;">
					<thead>
						<tr>
							<th style="text-align:left; padding-bottom:5px;">Question</th>
							<th style="text-align:left; padding-bottom:5px;">Answer</th>
							<th style="width:50px;"></th>
						</tr>
					</thead>
					<tbody id="ame-cat-faq-rows">
						<?php 
						$index = 0;
						foreach ( $faqs as $faq ) : 
							if ( empty( $faq['q'] ) ) continue;
						?>
							<tr class="faq-row" style="border-top:1px solid #e2e8f0;">
								<td><input type="text" name="ame_cat_faqs[<?php echo $index; ?>][q]" style="width:95%;" value="<?php echo esc_attr( $faq['q'] ); ?>" /></td>
								<td><textarea name="ame_cat_faqs[<?php echo $index; ?>][a]" style="width:95%;" rows="2"><?php echo esc_textarea( $faq['a'] ); ?></textarea></td>
								<td><button type="button" class="button remove-faq-row-btn" style="color:#b91c1c;">X</button></td>
							</tr>
						<?php 
							$index++;
						endforeach; 
						?>
					</tbody>
				</table>
				<button type="button" class="button button-secondary" id="ame-add-cat-faq-btn" style="margin-top:10px;">+ Add FAQ Row</button>
			</div>
		</td>
	</tr>

	<script type="text/javascript">
	jQuery(document).ready(function($){
		// Inline uploader binding
		$(document).on('click', '.ame-cat-media-upload', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			var mediaUploader = wp.media({
				title: 'Select Category Media',
				button: { text: 'Assign Image' },
				multiple: false
			}).on('select', function(){
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$('#' + fieldId).val(attachment.id);
				$('#preview-' + fieldId).html('<img src="' + attachment.url + '" style="max-width:120px; height:auto; border:1px solid #ccc; padding:3px;" />');
				button.siblings('.delete').show();
			}).open();
		});

		$(document).on('click', '.ame-cat-media-remove', function(e){
			e.preventDefault();
			var button = $(this);
			var fieldId = button.data('field');
			$('#' + fieldId).val('');
			$('#preview-' + fieldId).html('');
			button.hide();
		});

		// Repeatable FAQs
		var faqIndex = <?php echo $index; ?>;
		$('#ame-add-cat-faq-btn').click(function(e){
			e.preventDefault();
			var row = '<tr class="faq-row" style="border-top:1px solid #e2e8f0;">' +
				'<td><input type="text" name="ame_cat_faqs[' + faqIndex + '][q]" style="width:95%;" placeholder="Question..." /></td>' +
				'<td><textarea name="ame_cat_faqs[' + faqIndex + '][a]" style="width:95%;" rows="2" placeholder="Answer..."></textarea></td>' +
				'<td><button type="button" class="button remove-faq-row-btn" style="color:#b91c1c;">X</button></td>' +
			'</tr>';
			$('#ame-cat-faq-rows').append(row);
			faqIndex++;
		});

		$(document).on('click', '.remove-faq-row-btn', function(){
			$(this).closest('tr').remove();
		});
	});
	</script>
	<?php
}
add_action( 'product_cat_edit_form_fields', 'ame_bazaar_cat_edit_meta_fields' );

function ame_bazaar_save_cat_meta_fields( $term_id ) {
	$fields = array(
		'ame_cat_desktop_banner_id',
		'ame_cat_mobile_banner_id',
		'ame_cat_featured_image_id',
		'ame_cat_seo_title',
		'ame_cat_seo_desc',
		'ame_cat_primary_keyword',
		'ame_cat_secondary_keywords',
		'ame_cat_search_intent',
		'ame_cat_target_audience',
		'ame_cat_ai_summary',
		'ame_cat_product_entity',
		'ame_cat_geo_target',
	);

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_term_meta( $term_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	// Save Repeatable FAQs
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
	} else {
		delete_term_meta( $term_id, '_ame_cat_faqs' );
	}
}
add_action( 'edited_product_cat', 'ame_bazaar_save_cat_meta_fields' );
add_action( 'create_product_cat', 'ame_bazaar_save_cat_meta_fields' );


/**
 * 2. REORGANIZED PRODUCT DATA TABS
 * Split and list fields into clean, non-technical business layouts.
 */
function ame_bazaar_reorganize_product_tabs( $tabs ) {
	// Remove default general/inventory tabs to prevent overlaps or confusion
	unset( $tabs['general'] );
	unset( $tabs['inventory'] );

	$tabs['basic_product_specs'] = array(
		'label'    => __( 'Basic Product Specs', 'ame-bazaar' ),
		'target'   => 'ame_basic_product_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 10,
	);

	$tabs['pricing_specs'] = array(
		'label'    => __( 'Pricing & MRP', 'ame-bazaar' ),
		'target'   => 'ame_pricing_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 15,
	);

	$tabs['inventory_specs'] = array(
		'label'    => __( 'Inventory & Stocks', 'ame-bazaar' ),
		'target'   => 'ame_inventory_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 20,
	);

	$tabs['specifications_specs'] = array(
		'label'    => __( 'Garment Specifications', 'ame-bazaar' ),
		'target'   => 'ame_specifications_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 25,
	);

	$tabs['seo_specs'] = array(
		'label'    => __( 'SEO Metadata', 'ame-bazaar' ),
		'target'   => 'ame_seo_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 30,
	);

	$tabs['ai_optimization_specs'] = array(
		'label'    => __( 'AI Optimization', 'ame-bazaar' ),
		'target'   => 'ame_ai_optimization_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 35,
	);

	$tabs['trust_signals_specs'] = array(
		'label'    => __( 'Factual Trust Signals', 'ame-bazaar' ),
		'target'   => 'ame_trust_signals_specs_panel',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 40,
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'ame_bazaar_reorganize_product_tabs', 99 );

function ame_bazaar_render_custom_product_panels() {
	global $post;
	?>
	<!-- 1. Basic Product Specs Panel -->
	<div id="ame_basic_product_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Basic Details', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_select( array(
			'id'      => '_ame_local_availability',
			'label'   => __( 'Local Availability', 'ame-bazaar' ),
			'options' => array(
				'online-and-instore' => __( 'Online & In-Store', 'ame-bazaar' ),
				'in-store-only'      => __( 'In-Store Only', 'ame-bazaar' ),
			),
		) );
		?>
	</div>

	<!-- 2. Pricing & MRP Panel -->
	<div id="ame_pricing_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Pricing Settings', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'        => '_regular_price',
			'label'     => __( 'Regular Price (₹)', 'ame-bazaar' ),
			'data_type' => 'price',
		) );

		woocommerce_wp_text_input( array(
			'id'        => '_sale_price',
			'label'     => __( 'Sale Price (₹)', 'ame-bazaar' ),
			'data_type' => 'price',
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_mrp',
			'label'       => __( 'Tag MRP Value (₹)', 'ame-bazaar' ),
			'type'        => 'number',
			'description' => __( 'Maximum Retail Price for store tags.', 'ame-bazaar' ),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_price_segment',
			'label'   => __( 'Price Segment Class', 'ame-bazaar' ),
			'options' => array(
				'budget'    => __( 'Budget Friendly (Under ₹999)', 'ame-bazaar' ),
				'mid-range' => __( 'Value Range (₹1000 - ₹2499)', 'ame-bazaar' ),
				'premium'   => __( 'Mid-Premium (₹2500 - ₹4999)', 'ame-bazaar' ),
				'luxury'    => __( 'Luxury / Wedding (₹5000+)', 'ame-bazaar' ),
			),
		) );
		?>
	</div>

	<!-- 3. Inventory & Stocks Panel -->
	<div id="ame_inventory_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Stock & Physical Counter Inventory', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_sku',
			'label'       => __( 'SKU Code', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_kirari_stock',
			'label'       => __( 'Kirari Store Stock', 'ame-bazaar' ),
			'type'        => 'number',
			'description' => __( 'Actual count present at the Mubarakpur Road Outlet.', 'ame-bazaar' ),
		) );

		woocommerce_wp_checkbox( array(
			'id'            => '_manage_stock',
			'label'         => __( 'Manage Stock?', 'ame-bazaar' ),
			'description'   => __( 'Enable automated stock deduction.', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- 4. Garment Specifications Panel -->
	<div id="ame_specifications_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Fabric & Tailoring Specifications', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_brand',
			'label'       => __( 'Brand Name', 'ame-bazaar' ),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_fabric',
			'label'   => __( 'Fabric Material', 'ame-bazaar' ),
			'options' => array(
				'pure-cotton'   => __( 'Pure Cotton', 'ame-bazaar' ),
				'mulmul-cotton' => __( 'Pure Mulmul Cotton', 'ame-bazaar' ),
				'silk'          => __( 'Silk (Banarasi/Raw)', 'ame-bazaar' ),
				'rayon'         => __( 'Soft Rayon', 'ame-bazaar' ),
				'georgette'     => __( 'Georgette', 'ame-bazaar' ),
				'denim'         => __( 'Denim', 'ame-bazaar' ),
			),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_gsm',
			'label'       => __( 'GSM Value (Fabric Weight)', 'ame-bazaar' ),
			'type'        => 'number',
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_pattern',
			'label'   => __( 'Pattern Style', 'ame-bazaar' ),
			'options' => array(
				'solid'       => __( 'Solid / Plain', 'ame-bazaar' ),
				'printed'     => __( 'Printed', 'ame-bazaar' ),
				'embroidered' => __( 'Embroidered', 'ame-bazaar' ),
				'checked'     => __( 'Checked', 'ame-bazaar' ),
			),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_fit',
			'label'   => __( 'Fit Style', 'ame-bazaar' ),
			'options' => array(
				'regular'  => __( 'Regular Fit', 'ame-bazaar' ),
				'slim'     => __( 'Slim Fit', 'ame-bazaar' ),
				'tailored' => __( 'Custom Tailored Fit', 'ame-bazaar' ),
			),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_gender',
			'label'   => __( 'Target Gender', 'ame-bazaar' ),
			'options' => array(
				'men'    => __( 'Men\'s Wear', 'ame-bazaar' ),
				'women'  => __( 'Women\'s Wear', 'ame-bazaar' ),
				'kids'   => __( 'Kids Essentials', 'ame-bazaar' ),
			),
		) );
		?>
	</div>

	<!-- 5. SEO Metadata Panel -->
	<div id="ame_seo_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'SEO & Social Metas', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_seo_title',
			'label'       => __( 'SEO Title Override', 'ame-bazaar' ),
		) );

		woocommerce_wp_textarea_input( array(
			'id'          => '_ame_seo_desc',
			'label'       => __( 'Meta Description Override', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_canonical_url',
			'label'       => __( 'Canonical URL override', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- 6. AI Optimization Panel -->
	<div id="ame_ai_optimization_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Structured AI Metadata', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_text_input( array(
			'id'          => '_ame_primary_keyword',
			'label'       => __( 'Primary Keyword', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_secondary_keywords',
			'label'       => __( 'Secondary Keywords', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_search_intent',
			'label'       => __( 'Search Intent', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_target_audience',
			'label'       => __( 'Target Audience', 'ame-bazaar' ),
		) );

		woocommerce_wp_textarea_input( array(
			'id'          => '_ame_ai_summary',
			'label'       => __( 'AI Summary Text', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_product_entity',
			'label'       => __( 'Product Entity Type', 'ame-bazaar' ),
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_ame_geo_target',
			'label'       => __( 'GEO Target Locations', 'ame-bazaar' ),
		) );
		?>
	</div>

	<!-- 7. Factual Trust Signals Panel -->
	<div id="ame_trust_signals_specs_panel" class="panel woocommerce_options_panel">
		<h3 style="padding-left:12px; color:#002347;"><?php esc_html_e( 'Trust & Alterations Policies', 'ame-bazaar' ); ?></h3>
		<?php
		woocommerce_wp_checkbox( array(
			'id'            => '_ame_alteration_available',
			'label'         => __( 'On-site 30-min Alterations?', 'ame-bazaar' ),
		) );

		woocommerce_wp_select( array(
			'id'      => '_ame_whatsapp_ready',
			'label'   => __( 'WhatsApp Catalog Ready?', 'ame-bazaar' ),
			'options' => array(
				'yes' => __( 'Yes - Fully formatted template', 'ame-bazaar' ),
				'no'  => __( 'No - Catalog Only', 'ame-bazaar' ),
			),
		) );
		?>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'ame_bazaar_render_custom_product_panels' );


/**
 * 3. BLOG POSTS ONE-CLICK SETUP NOTICE
 */
function ame_bazaar_blog_posts_admin_notice() {
	if ( get_option( 'page_for_posts' ) ) {
		return;
	}

	$blog_page = get_page_by_path( 'blog' );
	if ( ! $blog_page ) {
		return;
	}

	$action_url = wp_nonce_url( admin_url( 'admin-post.php?action=ame_set_blog_archive_page' ), 'ame_blog_action_nonce' );
	?>
	<div class="notice notice-warning is-dismissible">
		<p>
			<strong><?php esc_html_e( 'AME Bazaar Blog Inactive:', 'ame-bazaar' ); ?></strong>
			<?php esc_html_e( 'The Blog Posts archive page has not been configured. Would you like to map your "Blog" page as the default archive loop?', 'ame-bazaar' ); ?>
			<a href="<?php echo esc_url( $action_url ); ?>" class="button button-primary" style="margin-left: 10px;"><?php esc_html_e( 'Set as Posts Page', 'ame-bazaar' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ame_bazaar_blog_posts_admin_notice' );

function ame_bazaar_set_blog_archive_page_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized user.' );
	}

	check_admin_referer( 'ame_blog_action_nonce' );

	$blog_page = get_page_by_path( 'blog' );
	if ( $blog_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $blog_page->ID );
	}

	wp_safe_redirect( admin_url( 'options-reading.php?settings-updated=true' ) );
	exit;
}
add_action( 'admin_post_ame_set_blog_archive_page', 'ame_bazaar_set_blog_archive_page_handler' );


/**
 * 4. CONTENT ASSISTANT, AI ENTITY BUILDER & INTERNAL LINKING METABOXES
 */
function ame_bazaar_register_cms_metaboxes() {
	$screens = array( 'post', 'page', 'product' );
	foreach ( $screens as $screen ) {
		// 4.1 Content Assistant
		add_meta_box(
			'ame_content_assistant_metabox',
			__( 'AME Content Assistant (Future n8n Integration)', 'ame-bazaar' ),
			'ame_bazaar_render_content_assistant_metabox',
			$screen,
			'side',
			'high'
		);

		// 4.2 AI Entity Builder
		add_meta_box(
			'ame_ai_entity_builder_metabox',
			__( 'AI Entity Builder (Knowledge Graph)', 'ame-bazaar' ),
			'ame_bazaar_render_ai_entity_builder_metabox',
			$screen,
			'normal',
			'high'
		);

		// 4.3 Internal Linking Assistant
		add_meta_box(
			'ame_internal_linking_metabox',
			__( 'Internal Linking Assistant', 'ame-bazaar' ),
			'ame_bazaar_render_internal_linking_metabox',
			$screen,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'ame_bazaar_register_cms_metaboxes' );

function ame_bazaar_render_content_assistant_metabox( $post ) {
	// Calculate Completeness Score
	$score = 0;
	$missing = array();

	if ( 'product' === $post->post_type ) {
		$mrp = get_post_meta( $post->ID, '_ame_mrp', true );
		$fabric = get_post_meta( $post->ID, '_ame_fabric', true );
		$seo_desc = get_post_meta( $post->ID, '_ame_seo_desc', true );
		$ai_summary = get_post_meta( $post->ID, '_ame_ai_summary', true );
		$faqs = get_post_meta( $post->ID, '_ame_cat_faqs', true );
		$alt = get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true );

		if ( $mrp ) $score += 20; else $missing[] = 'MRP Value';
		if ( $fabric ) $score += 20; else $missing[] = 'Fabric Specification';
		if ( $seo_desc ) $score += 20; else $missing[] = 'SEO Meta Description';
		if ( $ai_summary ) $score += 20; else $missing[] = 'AI Summary Text';
		if ( $alt ) $score += 20; else $missing[] = 'Image Alt Text';
	} else {
		// Default calculations for pages/posts
		$desc = $post->post_content;
		$seo_title = get_post_meta( $post->ID, '_ame_seo_title', true );
		$seo_desc = get_post_meta( $post->ID, '_ame_seo_desc', true );
		$entity_name = get_post_meta( $post->ID, '_ame_entity_name', true );
		$thumb = has_post_thumbnail( $post->ID );

		if ( $desc ) $score += 20; else $missing[] = 'Post Description';
		if ( $seo_title ) $score += 20; else $missing[] = 'SEO Title';
		if ( $seo_desc ) $score += 20; else $missing[] = 'SEO Meta Description';
		if ( $entity_name ) $score += 20; else $missing[] = 'AI Entity Details';
		if ( $thumb ) $score += 20; else $missing[] = 'Featured Image';
	}

	?>
	<div class="ame-score-card-wrapper" style="padding:10px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:5px; margin-bottom:15px; text-align:center;">
		<span style="font-size:0.85em; font-weight:700; color:#0369a1; text-transform:uppercase;">AI Readiness Score</span>
		<div style="font-size:2.5em; font-weight:800; color:#0c4a6e; margin-block:5px;"><?php echo $score; ?>%</div>
		
		<?php if ( ! empty( $missing ) ) : ?>
			<div style="text-align:left; font-size:0.85em; color:#0284c7; border-top:1px solid #bae6fd; padding-top:5px; margin-top:8px;">
				<strong>Missing:</strong>
				<ul style="margin:5px 0 0 15px; padding:0; list-style-type:disc;">
					<?php foreach ( $missing as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php else : ?>
			<div style="color:#15803d; font-size:0.85em; font-weight:700; margin-top:5px;">✓ Fully AI-Ready!</div>
		<?php endif; ?>
	</div>

	<div style="display:flex; flex-direction:column; gap:8px;">
		<button type="button" class="button button-secondary" onclick="alert('Triggering AI Description Generation (Ticket #020 n8n pipeline)...');"><?php esc_html_e( 'Generate Description', 'ame-bazaar' ); ?></button>
		<button type="button" class="button button-secondary" onclick="alert('Generating Meta Overrides...');"><?php esc_html_e( 'Generate Meta Description', 'ame-bazaar' ); ?></button>
		<button type="button" class="button button-secondary" onclick="alert('Generating FAQs...');"><?php esc_html_e( 'Generate FAQ', 'ame-bazaar' ); ?></button>
		<button type="button" class="button button-secondary" onclick="alert('Creating Alt tag ideas...');"><?php esc_html_e( 'Generate Alt Text', 'ame-bazaar' ); ?></button>
	</div>
	<?php
}

function ame_bazaar_render_ai_entity_builder_metabox( $post ) {
	wp_nonce_field( 'ame_ai_entity_save_nonce', 'ame_ai_entity_nonce' );

	$name = get_post_meta( $post->ID, '_ame_entity_name', true );
	$type = get_post_meta( $post->ID, '_ame_entity_type', true );
	$synonyms = get_post_meta( $post->ID, '_ame_entity_synonyms', true );
	$terms = get_post_meta( $post->ID, '_ame_entity_search_terms', true );
	$local = get_post_meta( $post->ID, '_ame_entity_local_relevance', true );
	$season = get_post_meta( $post->ID, '_ame_entity_season', true );
	$occasion = get_post_meta( $post->ID, '_ame_entity_occasion', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="ame_entity_name">Entity Name</label></th>
			<td><input type="text" id="ame_entity_name" name="ame_entity_name" value="<?php echo esc_attr( $name ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_type">Entity Type</label></th>
			<td><input type="text" id="ame_entity_type" name="ame_entity_type" value="<?php echo esc_attr( $type ); ?>" class="regular-text" placeholder="e.g. Apparel Product, Style, Guide" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_synonyms">Synonyms</label></th>
			<td><input type="text" id="ame_entity_synonyms" name="ame_entity_synonyms" value="<?php echo esc_attr( $synonyms ); ?>" class="large-text" placeholder="Comma separated, e.g. coat, blazer, suit coat" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_search_terms">Common Search Terms</label></th>
			<td><input type="text" id="ame_entity_search_terms" name="ame_entity_search_terms" value="<?php echo esc_attr( $terms ); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_local_relevance">Local Relevance</label></th>
			<td><input type="text" id="ame_entity_local_relevance" name="ame_entity_local_relevance" value="<?php echo esc_attr( $local ); ?>" class="regular-text" placeholder="e.g. Mubarakpur, Kirari, Nangloi, Delhi" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_season">Target Season</label></th>
			<td><input type="text" id="ame_entity_season" name="ame_entity_season" value="<?php echo esc_attr( $season ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="ame_entity_occasion">Target Occasion</label></th>
			<td><input type="text" id="ame_entity_occasion" name="ame_entity_occasion" value="<?php echo esc_attr( $occasion ); ?>" class="regular-text" /></td>
		</tr>
	</table>
	<?php
}

function ame_bazaar_render_internal_linking_metabox( $post ) {
	$products = get_post_meta( $post->ID, '_ame_link_products', true );
	$categories = get_post_meta( $post->ID, '_ame_link_categories', true );
	$articles = get_post_meta( $post->ID, '_ame_link_articles', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="ame_link_products">Recommended Products</label></th>
			<td>
				<input type="text" id="ame_link_products" name="ame_link_products" value="<?php echo esc_attr( $products ); ?>" class="large-text" placeholder="Comma separated IDs or names" />
				<p class="description">AI Link Assistant placeholder: Recommended retail products matching this entity.</p>
			</td>
		</tr>
		<tr>
			<th><label for="ame_link_categories">Recommended Categories</label></th>
			<td><input type="text" id="ame_link_categories" name="ame_link_categories" value="<?php echo esc_attr( $categories ); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th><label for="ame_link_articles">Recommended Articles</label></th>
			<td><input type="text" id="ame_link_articles" name="ame_link_articles" value="<?php echo esc_attr( $articles ); ?>" class="large-text" /></td>
		</tr>
	</table>
	<?php
}

function ame_bazaar_save_cms_metaboxes_data( $post_id ) {
	if ( ! isset( $_POST['ame_ai_entity_nonce'] ) || ! wp_verify_nonce( $_POST['ame_ai_entity_nonce'], 'ame_ai_entity_save_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$fields = array(
		'ame_entity_name' => '_ame_entity_name',
		'ame_entity_type' => '_ame_entity_type',
		'ame_entity_synonyms' => '_ame_entity_synonyms',
		'ame_entity_search_terms' => '_ame_entity_search_terms',
		'ame_entity_local_relevance' => '_ame_entity_local_relevance',
		'ame_entity_season' => '_ame_entity_season',
		'ame_entity_occasion' => '_ame_entity_occasion',
		'ame_link_products' => '_ame_link_products',
		'ame_link_categories' => '_ame_link_categories',
		'ame_link_articles' => '_ame_link_articles',
	);

	foreach ( $fields as $post_key => $meta_key ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
		}
	}
}
add_action( 'save_post', 'ame_bazaar_save_cms_metaboxes_data' );


/**
 * 5. CONTENT & MEDIA HEALTH DASHBOARD
 */
function ame_bazaar_register_health_dashboard() {
	add_submenu_page(
		'ame-store-dashboard',
		__( 'Store Content & Media Health', 'ame-bazaar' ),
		__( 'Content Health', 'ame-bazaar' ),
		'manage_options',
		'ame-content-health',
		'ame_bazaar_render_health_dashboard'
	);
}
add_action( 'admin_menu', 'ame_bazaar_register_health_dashboard' );

function ame_bazaar_render_health_dashboard() {
	global $wpdb;

	// Query health counts
	$total_prod = (int) $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type='product' AND post_status='publish'" );
	$no_image_prod = (int) $wpdb->get_var( "SELECT COUNT(posts.ID) FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} pm ON (posts.ID = pm.post_id AND pm.meta_key='_thumbnail_id') WHERE posts.post_type='product' AND posts.post_status='publish' AND pm.meta_value IS NULL" );
	
	// Large images query (>150kb)
	$large_images = 0;
	$no_alt_images = 0;
	$no_caption_images = 0;
	
	$attachments = $wpdb->get_results( "SELECT ID, post_excerpt FROM {$wpdb->posts} WHERE post_type='attachment'", ARRAY_A );
	foreach ( $attachments as $att ) {
		$alt = get_post_meta( $att['ID'], '_wp_attachment_image_alt', true );
		if ( empty( $alt ) ) {
			$no_alt_images++;
		}
		if ( empty( $att['post_excerpt'] ) ) {
			$no_caption_images++;
		}
		
		$file = get_attached_file( $att['ID'] );
		if ( $file && file_exists( $file ) ) {
			$size = filesize( $file );
			if ( $size > 153600 ) { // 150kb
				$large_images++;
			}
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AME Bazaar Store Content & Media Health Desk', 'ame-bazaar' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Locate missing meta descriptors, unoptimized images, or unassigned sitemaps options instantly.', 'ame-bazaar' ); ?></p>
		
		<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px; max-width:1000px;">
			<!-- Content Audit -->
			<div style="background:#fff; border:1px solid #ccd0d4; padding:20px; border-radius:5px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
				<h2 style="color:#0f172a; margin-top:0; border-bottom:2px solid #ca8a04; padding-bottom:5px;">1. Content Health</h2>
				<table class="form-table" style="margin-top:0;">
					<tr>
						<td>Products without Images:</td>
						<td style="color:#b91c1c; font-weight:700;"><?php echo $no_image_prod; ?> / <?php echo $total_prod; ?></td>
					</tr>
					<tr>
						<td>Products without Brand:</td>
						<td><?php echo (int) $wpdb->get_var( "SELECT COUNT(posts.ID) FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} pm ON (posts.ID = pm.post_id AND pm.meta_key='_ame_brand') WHERE posts.post_type='product' AND posts.post_status='publish' AND (pm.meta_value IS NULL OR pm.meta_value='')" ); ?></td>
					</tr>
					<tr>
						<td>Products without SEO overrides:</td>
						<td><?php echo (int) $wpdb->get_var( "SELECT COUNT(posts.ID) FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} pm ON (posts.ID = pm.post_id AND pm.meta_key='_ame_seo_desc') WHERE posts.post_type='product' AND posts.post_status='publish' AND (pm.meta_value IS NULL OR pm.meta_value='')" ); ?></td>
					</tr>
				</table>
			</div>

			<!-- Media Audit -->
			<div style="background:#fff; border:1px solid #ccd0d4; padding:20px; border-radius:5px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
				<h2 style="color:#0f172a; margin-top:0; border-bottom:2px solid #ca8a04; padding-bottom:5px;">2. Media Library Health</h2>
				<table class="form-table" style="margin-top:0;">
					<tr>
						<td>Images without ALT tags:</td>
						<td style="color:#ca8a04; font-weight:700;"><?php echo $no_alt_images; ?></td>
					</tr>
					<tr>
						<td>Images without Captions:</td>
						<td><?php echo $no_caption_images; ?></td>
					</tr>
					<tr>
						<td>Large Banners (>150 KB size):</td>
						<td style="color:#b91c1c; font-weight:700;"><?php echo $large_images; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php
}


/**
 * 6. ROLE-BASED DASHBOARD SIMPLIFICATION
 */
function ame_bazaar_simplify_staff_menus() {
	if ( current_user_can( 'manage_options' ) ) {
		return; // Keep all controls for Administrator
	}

	$user = wp_get_current_user();
	$roles = (array) $user->roles;

	// Check if user matches Content, Store Manager, or Marketing roles
	$restricted_roles = array( 'editor', 'shop_manager', 'author', 'contributor' );
	if ( array_intersect( $roles, $restricted_roles ) ) {
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'options-general.php' );
		remove_menu_page( 'plugins.php' );
		remove_menu_page( 'themes.php' );
	}
}
add_action( 'admin_menu', 'ame_bazaar_simplify_staff_menus', 999 );
