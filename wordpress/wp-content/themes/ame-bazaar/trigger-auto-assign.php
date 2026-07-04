<?php
/**
 * Temp Trigger for AME Bazaar Auto Media Mappings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

if ( function_exists( 'ame_bazaar_auto_assign_media_mappings' ) ) {
	ame_bazaar_auto_assign_media_mappings();
}

$fields = array(
	'ame_bazaar_media_primary_logo',
	'ame_bazaar_media_white_logo',
	'ame_bazaar_media_sticky_logo',
	'ame_bazaar_media_favicon',
	'ame_bazaar_media_hero_desktop',
	'ame_bazaar_media_hero_mobile',
	'ame_bazaar_media_men',
	'ame_bazaar_media_women',
	'ame_bazaar_media_kids',
	'ame_bazaar_media_accessories',
	'ame_bazaar_media_tailoring',
	'ame_bazaar_media_visit_store',
	'ame_bazaar_media_about',
	'ame_bazaar_media_google_reviews',
	'ame_bazaar_media_instagram',
	'ame_bazaar_media_footer_bg',
	'ame_bazaar_media_empty_state',
	'ame_bazaar_media_404_illustration'
);

$results = array();
foreach ( $fields as $field ) {
	$results[ $field ] = get_option( $field );
}

echo json_encode( $results, JSON_PRETTY_PRINT );
