<?php
/**
 * Custom Single Product layout template override.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ame-single-product-container', $product ); ?>>
	<div class="ame-bazaar-container">
		
		<!-- Custom Breadcrumbs -->
		<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>

		<div class="ame-single-product-layout-grid">
			
			<!-- Left Column: Gallery Slider/Zoom -->
			<div class="ame-product-gallery-column">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>

			<!-- Right Column: Product Info Summary -->
			<div class="summary entry-summary ame-product-summary-column">
				
				<?php
				// Retrieve product metadata
				$post_id = $product->get_id();
				$brand   = get_post_meta( $post_id, '_ame_brand', true );
				$sku     = $product->get_sku();
				$fabric  = get_post_meta( $post_id, '_ame_fabric', true );
				$material = get_post_meta( $post_id, '_ame_material', true );
				$gsm     = get_post_meta( $post_id, '_ame_gsm', true );
				$fabric_wt = get_post_meta( $post_id, '_ame_fabric_weight', true );
				$pattern = get_post_meta( $post_id, '_ame_pattern', true );
				$fit     = get_post_meta( $post_id, '_ame_fit', true );
				$sleeve  = get_post_meta( $post_id, '_ame_sleeve_type', true );
				$neck    = get_post_meta( $post_id, '_ame_neck_type', true );
				$closure = get_post_meta( $post_id, '_ame_closure', true );
				$occasion= get_post_meta( $post_id, '_ame_occasion', true );
				$season  = get_post_meta( $post_id, '_ame_season', true );
				$origin  = get_post_meta( $post_id, '_ame_country_of_origin', true );
				$mfr     = get_post_meta( $post_id, '_ame_manufacturer', true );
				$wash    = get_post_meta( $post_id, '_ame_wash_instructions', true );
				$care    = get_post_meta( $post_id, '_ame_care_instructions', true );
				$size_chart = get_post_meta( $post_id, '_ame_size_chart', true );
				$alteration = get_post_meta( $post_id, '_ame_alteration_available', true );
				$local_avail = get_post_meta( $post_id, '_ame_local_availability', true );
				$kirari_stock = get_post_meta( $post_id, '_ame_kirari_stock', true );

				// Mappings definitions
				$label_mappings = array(
					'_ame_fabric' => array(
						'pure-cotton'   => 'Pure Cotton',
						'mulmul-cotton' => 'Pure Mulmul Cotton',
						'silk'          => 'Silk (Banarasi/Raw)',
						'rayon'         => 'Soft Rayon',
						'georgette'     => 'Georgette',
						'cotton-blend'  => 'Cotton Blend',
						'wool'          => 'Pure Wool / Cashmere',
						'synthetic'     => 'Polyester / Synthetic',
						'denim'         => 'Denim',
					),
					'_ame_pattern' => array(
						'solid'       => 'Solid / Plain',
						'printed'     => 'Printed',
						'embroidered' => 'Embroidered',
						'checked'     => 'Checked',
						'striped'     => 'Striped',
						'woven'       => 'Self-Woven / Zari Border',
						'designer'    => 'Designer Embellished',
					),
					'_ame_fit' => array(
						'regular'   => 'Regular Fit',
						'slim'      => 'Slim Fit',
						'loose'     => 'Loose / Comfort Fit',
						'semi-slim' => 'Semi-Slim Fit',
						'tailored'  => 'Custom Tailored Fit',
					),
					'_ame_sleeve_type' => array(
						'full'           => 'Full Sleeve',
						'half'           => 'Half Sleeve',
						'sleeveless'     => 'Sleeveless',
						'three-quarter'  => '3/4 Sleeve',
						'short'          => 'Short Sleeve',
						'not-applicable' => 'Not Applicable',
					),
					'_ame_neck_type' => array(
						'collar'         => 'Shirt Collar',
						'mandarin'       => 'Mandarin / Nehru Collar',
						'round'          => 'Round Neck',
						'v-neck'         => 'V-Neck',
						'boat'           => 'Boat Neck',
						'cowl'           => 'Cowl Neck',
						'not-applicable' => 'Not Applicable',
					),
					'_ame_occasion' => array(
						'casual'   => 'Casual Daily',
						'formal'   => 'Office Formal',
						'wedding'  => 'Wedding / Ceremony',
						'festival' => 'Festive Shopping',
						'party'    => 'Party Wear',
						'school'   => 'School Wear',
					),
					'_ame_season' => array(
						'all-season' => 'All Seasons',
						'summer'     => 'Summer Wear (Mulmul Cotton)',
						'winter'     => 'Winter Layers',
						'monsoon'    => 'Monsoon Wear',
					),
				);

				$get_label = function( $key, $value ) use ( $label_mappings ) {
					if ( isset( $label_mappings[ $key ][ $value ] ) ) {
						return $label_mappings[ $key ][ $value ];
					}
					return $value;
				};

				// Badges row
				$is_on_sale = $product->is_on_sale();
				$is_featured = $product->is_featured();
				$stock_status = $product->get_stock_status();
				
				echo '<div class="ame-single-product-badges" style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.75rem;">';
				if ( $is_on_sale ) {
					echo '<span class="ame-badge-sale">' . esc_html__( 'Sale', 'ame-bazaar' ) . '</span>';
				}
				if ( $is_featured ) {
					echo '<span class="ame-badge-bestseller">' . esc_html__( 'Best Seller', 'ame-bazaar' ) . '</span>';
				}
				if ( 'outofstock' === $stock_status ) {
					echo '<span class="ame-badge-outofstock">' . esc_html__( 'Out of Stock', 'ame-bazaar' ) . '</span>';
				} else {
					echo '<span class="ame-badge-new">' . esc_html__( 'In Stock', 'ame-bazaar' ) . '</span>';
				}
				if ( ! empty( $kirari_stock ) && intval( $kirari_stock ) > 0 ) {
					if ( intval( $kirari_stock ) <= 5 ) {
						echo '<span class="ame-badge-limited" style="background:#fee2e2; color:#ef4444;">' . sprintf( __( 'Only %d left at Mubarakpur Road!', 'ame-bazaar' ), intval( $kirari_stock ) ) . '</span>';
					} else {
						echo '<span class="ame-badge-new" style="background:#f0fdf4; color:#16a34a;">' . sprintf( __( 'Kirari Outlet Stock: %d', 'ame-bazaar' ), intval( $kirari_stock ) ) . '</span>';
					}
				}
				echo '</div>';
				
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 15
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 */
				do_action( 'woocommerce_single_product_summary' );

				// Highlights box from metadata
				$highlights = array();
				if ( $brand ) $highlights[] = "Brand: " . esc_html( $brand );
				if ( $fabric ) $highlights[] = "Fabric: " . esc_html( $get_label( '_ame_fabric', $fabric ) );
				if ( $pattern ) $highlights[] = "Pattern: " . esc_html( $get_label( '_ame_pattern', $pattern ) );
				if ( $fit ) $highlights[] = "Fit: " . esc_html( $get_label( '_ame_fit', $fit ) );
				if ( $sleeve && $sleeve !== 'not-applicable' ) $highlights[] = "Sleeves: " . esc_html( $get_label( '_ame_sleeve_type', $sleeve ) );
				if ( $neck && $neck !== 'not-applicable' ) $highlights[] = "Neck: " . esc_html( $get_label( '_ame_neck_type', $neck ) );

				if ( ! empty( $highlights ) ) {
					?>
					<div class="ame-product-highlights-box" style="margin: 1.5rem 0; padding: 1.25rem; border-left: 4px solid var(--ame-color-gold, #ca8a04); background: #fdfdfa; border-radius: 0 var(--ame-radius-sm, 8px) var(--ame-radius-sm, 8px) 0; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));">
						<h4 style="margin: 0 0 0.5rem 0; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ame-color-navy);"><?php esc_html_e( 'Product Highlights', 'ame-bazaar' ); ?></h4>
						<ul style="margin: 0; padding-left: 1.2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem 1rem; font-size: 0.85rem; color: var(--ame-color-slate); line-height: 1.4;">
							<?php foreach ( $highlights as $highlight ) : ?>
								<li><?php echo $highlight; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php
				}

				// Product Availability & Service Card
				?>
				<div class="ame-availability-service-card" style="margin-top: 1.5rem; padding: 1.25rem; background: #fafaf9; border: 1.5px solid var(--ame-color-border, #dbe2ea); border-radius: var(--ame-radius-sm, 8px); box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));">
					<h4 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; font-weight: 800; color: var(--ame-color-navy); display: flex; align-items: center; gap: 0.4rem; text-transform: uppercase; letter-spacing: 0.02em;">
						<svg class="ame-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; color: var(--ame-color-gold-dark, #916c02);"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
						<span>Availability & Services</span>
					</h4>
					<ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.85rem; color: var(--ame-color-slate);">
						<li style="display: flex; gap: 0.5rem; align-items: flex-start; line-height: 1.4;">
							<strong style="color: var(--ame-color-navy); min-width: 110px;">In-Store:</strong> 
							<span>
								<?php 
								if ( 'in-store-only' === $local_avail ) {
									echo '<span style="color:#ca8a04; font-weight:700;">In-Store Purchase Only</span> (Trial Rooms Available at Kirari Store)';
								} else {
									echo '<span style="color:#16a34a; font-weight:700;">In-Stock</span> (Available Online & Mubarakpur Road Store)';
								}
								?>
							</span>
						</li>
						<li style="display: flex; gap: 0.5rem; align-items: flex-start; line-height: 1.4;">
							<strong style="color: var(--ame-color-navy); min-width: 110px;">Alteration:</strong> 
							<span>
								<?php 
								if ( 'yes' === $alteration || '1' === $alteration ) {
									echo '<span style="color:#16a34a; font-weight:700;">Available (30-Minute In-Store Alteration)</span>';
								} else {
									echo 'Standard sizes (fittings/alterations can be requested in-store)';
								}
								?>
							</span>
						</li>
						<li style="display: flex; gap: 0.5rem; align-items: flex-start; line-height: 1.4;">
							<strong style="color: var(--ame-color-navy); min-width: 110px;">Store Pickup:</strong> 
							<span>Ready in 2 Hours at Mubarakpur Road, Kirari, Delhi.</span>
						</li>
						<li style="display: flex; gap: 0.5rem; align-items: flex-start; line-height: 1.4;">
							<strong style="color: var(--ame-color-navy); min-width: 110px;">Exchange policy:</strong> 
							<span>Hassle-free 7-day exchange options at Kirari outlet.</span>
						</li>
						<li style="display: flex; gap: 0.5rem; align-items: flex-start; line-height: 1.4;">
							<strong style="color: var(--ame-color-navy); min-width: 110px;">Local Trust:</strong> 
							<span>Sourced directly by Apparel Maheshwari Enterprises—a trusted family store in Delhi.</span>
						</li>
					</ul>
				</div>
				<?php
				?>
			</div>
		</div>

		<!-- Advanced Product Experience Section: Specs, Sizing, Guarantees, FAQs -->
		<div class="ame-single-product-details-expansion" style="margin-top: 3.5rem; clear: both; border-top: 1px solid var(--ame-color-border, #dbe2ea); padding-top: 2.5rem;">
			<div class="ame-specs-guarantees-grid" style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 3.5rem; align-items: start;">
				
				<!-- Left side: Specs Table -->
				<div>
					<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 0.4rem; display: inline-block;">Garment Specifications</h3>
					
					<?php
					$specs = array(
						'Brand'             => $brand ? $brand : 'AME Bazaar',
						'SKU'               => $sku ? $sku : 'AME-' . $product->get_id(),
						'Category'          => wc_get_product_category_list( $product->get_id(), ', ' ),
						'Fabric Type'       => $fabric ? $get_label( '_ame_fabric', $fabric ) : '',
						'Material Details'  => $material ? $material : '',
						'GSM weight'        => $gsm ? $gsm . ' GSM' : '',
						'Fabric Weight'     => $gsm ? '' : ($fabric_wt ? $fabric_wt : ''),
						'Pattern Style'     => $pattern ? $get_label( '_ame_pattern', $pattern ) : '',
						'Fit Style'         => $fit ? $get_label( '_ame_fit', $fit ) : '',
						'Sleeve Type'       => ($sleeve && $sleeve !== 'not-applicable') ? $get_label( '_ame_sleeve_type', $sleeve ) : '',
						'Neck Type'         => ($neck && $neck !== 'not-applicable') ? $get_label( '_ame_neck_type', $neck ) : '',
						'Closure'           => $closure ? $get_label( '_ame_closure', $closure ) : '',
						'Occasion'          => $occasion ? $get_label( '_ame_occasion', $occasion ) : '',
						'Seasonality'       => $season ? $get_label( '_ame_season', $season ) : '',
						'Country of Origin' => $origin ? $origin : 'India',
						'Manufacturer'      => $mfr ? $mfr : 'Apparel Maheshwari Enterprises',
						'Wash Instructions' => $wash ? $wash : '',
						'Care Instructions' => $care ? $care : '',
					);

					$specs = array_filter( $specs );
					?>
					<table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; margin-top: 0.5rem;" class="ame-specs-table">
						<tbody>
							<?php 
							$row_index = 0;
							foreach ( $specs as $label => $val ) : 
								$bg = ($row_index % 2 === 0) ? '#fdfdfa' : 'transparent';
								$row_index++;
							?>
								<tr style="border-bottom: 1px solid #f1f5f9; background: <?php echo $bg; ?>;">
									<td style="padding: 0.75rem 1rem; font-weight: 700; color: var(--ame-color-navy); width: 35%;"><?php echo esc_html( $label ); ?></td>
									<td style="padding: 0.75rem 1rem; color: var(--ame-color-slate);"><?php echo wp_kses_post( $val ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				
				<!-- Right side: Size Guide & Trust Blocks -->
				<div style="display: flex; flex-direction: column; gap: 2rem;">
					<?php if ( $size_chart ) : ?>
						<div>
							<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 0.4rem; display: inline-block;">Size Chart & Guide</h3>
							<div style="background: #fafaf9; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 1.5rem; margin-top: 0.5rem;" class="ame-size-guide-box">
								<div style="white-space: pre-wrap; font-family: monospace; font-size: 0.85rem; color: #334155; max-height: 250px; overflow-y: auto; line-height: 1.5;"><?php echo esc_html( $size_chart ); ?></div>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Reusable Trust Section -->
					<div>
						<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 0.4rem; display: inline-block;">Store Guarantees</h3>
						<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.5rem;">
							<!-- Quality Checked -->
							<div style="padding: 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));" class="ame-card ame-hover-lift">
								<svg class="ame-icon" style="color: #16a34a; width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
								<span style="font-size: 0.8rem; font-weight: 800; color: var(--ame-color-navy);">Quality Checked</span>
							</div>
							<!-- Easy Exchange -->
							<div style="padding: 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));" class="ame-card ame-hover-lift">
								<svg class="ame-icon" style="color: #2563eb; width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path></svg>
								<span style="font-size: 0.8rem; font-weight: 800; color: var(--ame-color-navy);">Easy Exchanges</span>
							</div>
							<!-- Tailoring Available -->
							<div style="padding: 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));" class="ame-card ame-hover-lift">
								<svg class="ame-icon" style="color: var(--ame-color-gold-dark, #916c02); width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
								<span style="font-size: 0.8rem; font-weight: 800; color: var(--ame-color-navy);"><?php echo 'yes' === $alteration || '1' === $alteration ? '30-Min Alterations' : 'Custom Fit Option'; ?></span>
							</div>
							<!-- Store Pickup -->
							<div style="padding: 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));" class="ame-card ame-hover-lift">
								<svg class="ame-icon" style="color: #ca8a04; width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
								<span style="font-size: 0.8rem; font-weight: 800; color: var(--ame-color-navy);">2-Hour Store Pickup</span>
							</div>
						</div>
						<div style="margin-top: 1.25rem; padding: 1.25rem; background: var(--ame-color-cream, #f8f7f4); border: 1px solid var(--ame-color-border, #dbe2ea); border-radius: 8px; font-size: 0.8rem; color: var(--ame-color-slate); line-height: 1.5;" class="ame-card">
							<strong>Local Family Store Guarantee:</strong> Apparel Maheshwari Enterprises has proudly served Delhi families from our Mubarakpur Road, Kirari outlet for years. We source directly from weavers to ensure authentic, skin-safe garments. For size verification or customization, connect via <strong>WhatsApp Support</strong>.
						</div>
					</div>
				</div>
			</div>

			<!-- Dynamic Metadata-driven Product FAQ Section -->
			<?php
			$faq_items = array();

			// 1. Fabric
			if ( $fabric || $material ) {
				$fab_desc = $fabric ? $get_label( '_ame_fabric', $fabric ) : '';
				if ( $material ) {
					$fab_desc .= $fab_desc ? ' (' . $material . ')' : $material;
				}
				$wt_desc = $gsm ? ' (GSM: ' . $gsm . ')' : '';
				$faq_items[] = array(
					'question' => sprintf( __( 'What fabric or material is this %s made of?', 'ame-bazaar' ), strtolower( get_the_title() ) ),
					'answer'   => sprintf( __( 'This garment is crafted from premium %s%s. It is designed to be highly breathable and comfortable for local Delhi weather.', 'ame-bazaar' ), $fab_desc, $wt_desc ),
				);
			}

			// 2. Alteration / Fitting
			if ( $alteration ) {
				$faq_items[] = array(
					'question' => __( 'Is custom tailoring or alteration available for this garment?', 'ame-bazaar' ),
					'answer'   => 'yes' === $alteration || '1' === $alteration 
						? __( 'Yes! We provide on-site custom fitting and hem alterations within 30 minutes at our Mubarakpur Road outlet in Kirari, Delhi.', 'ame-bazaar' )
						: __( 'Standard sizes are available. You can visit our Kirari outlet for fitting consultations with our master tailors.', 'ame-bazaar' ),
				);
			}

			// 3. Care & Washing
			if ( $care || $wash ) {
				$care_text = $care ? $care : '';
				$wash_text = $wash ? $wash : '';
				$sep = ($care_text && $wash_text) ? ' | ' : '';
				$faq_items[] = array(
					'question' => __( 'How should I wash and care for this product?', 'ame-bazaar' ),
					'answer'   => sprintf( __( 'Recommended care: %s%s%s. Proper care ensures the fabric maintains its color and texture for years.', 'ame-bazaar' ), $care_text, $sep, $wash_text ),
				);
			}

			// 4. Origin & Manufacturer
			if ( $mfr || $origin ) {
				$faq_items[] = array(
					'question' => __( 'Where is this garment manufactured?', 'ame-bazaar' ),
					'answer'   => sprintf( __( 'This premium apparel is manufactured by %s. Country of origin: %s.', 'ame-bazaar' ), $mfr ? $mfr : 'Apparel Maheshwari Enterprises', $origin ? $origin : 'India' ),
				);
			}

			// 5. Occasion & Season
			if ( $occasion || $season ) {
				$occ_lbl = $occasion ? $get_label( '_ame_occasion', $occasion ) : '';
				$sea_lbl = $season ? $get_label( '_ame_season', $season ) : '';
				$parts = array();
				if ( $occ_lbl ) $parts[] = sprintf( __( 'designed for %s', 'ame-bazaar' ), strtolower( $occ_lbl ) );
				if ( $sea_lbl ) $parts[] = sprintf( __( 'perfect for %s', 'ame-bazaar' ), strtolower( $sea_lbl ) );
				$faq_items[] = array(
					'question' => __( 'What season and occasion is this garment suitable for?', 'ame-bazaar' ),
					'answer'   => sprintf( __( 'This item is %s. It makes an excellent addition to your seasonal ethnic wardrobe.', 'ame-bazaar' ), implode( ' and ', $parts ) ),
				);
			}

			if ( ! empty( $faq_items ) ) {
				?>
				<div class="ame-product-faq-section" style="margin-top: 3.5rem; padding-top: 2.5rem; border-top: 1px solid var(--ame-color-border, #dbe2ea);">
					<h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ame-color-navy); margin-top: 0; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--ame-color-gold, #ca8a04); padding-bottom: 0.4rem; display: inline-block;">Product FAQs</h3>
					<div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
						<?php foreach ( $faq_items as $index => $item ) : ?>
							<details class="ame-faq-accordion-item" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; padding: 1rem; cursor: pointer; box-shadow: var(--ame-shadow-sm, 0 1px 2px rgba(0,0,0,0.05));">
								<summary style="font-weight: 700; color: var(--ame-color-navy); list-style: none; display: flex; justify-content: space-between; align-items: center; outline: none; margin: 0;">
									<span style="font-size: 0.95rem;"><?php echo esc_html( $item['question'] ); ?></span>
									<span style="font-size: 1.2rem; font-weight: 400; color: var(--ame-color-gold-dark, #916c02); transition: transform 0.2s;" class="ame-faq-arrow">▼</span>
								</summary>
								<p style="margin: 0.75rem 0 0 0; font-size: 0.9rem; color: var(--ame-color-slate); line-height: 1.6; border-top: 1px solid #f1f5f9; padding-top: 0.75rem;"><?php echo esc_html( $item['answer'] ); ?></p>
							</details>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>

		<!-- Single Product Tabs, Related Products, and Recently Viewed -->
		<div class="ame-single-product-bottom-wrap" style="margin-top: 4rem; clear: both;">
			<?php
			/**
			 * Hook: woocommerce_after_single_product_summary.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 * @hooked ame_bazaar_render_recently_viewed_single - 25
			 */
			do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>

	</div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
