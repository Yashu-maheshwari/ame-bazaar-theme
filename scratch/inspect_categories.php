<?php
require_once dirname(__DIR__) . '/wordpress/wp-load.php';

$depts = get_terms( array(
    'taxonomy'   => 'product_cat',
    'parent'     => 0,
    'hide_empty' => false,
) );

echo "TAXONOMY TERM META AUDIT:\n";
foreach ( $depts as $dept ) {
    $homepage_card_id = get_term_meta( $dept->term_id, '_ame_homepage_card', true );
    $category_banner_id = get_term_meta( $dept->term_id, '_ame_category_banner', true );
    echo "----------------------------------------\n";
    echo "Term ID: " . $dept->term_id . "\n";
    echo "Slug: " . $dept->slug . "\n";
    echo "Name: " . $dept->name . "\n";
    echo "_ame_homepage_card: " . var_export( $homepage_card_id, true ) . "\n";
    echo "_ame_category_banner: " . var_export( $category_banner_id, true ) . "\n";
    if ( $homepage_card_id ) {
        echo "Homepage Card Image URL: " . wp_get_attachment_url( $homepage_card_id ) . "\n";
    }
    if ( $category_banner_id ) {
        echo "Category Banner Image URL: " . wp_get_attachment_url( $category_banner_id ) . "\n";
    }
}
