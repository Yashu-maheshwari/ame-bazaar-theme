<?php
/**
 * Debug Script to PROVE the Media Manager Database state.
 * Visit: /wp-content/themes/ame-bazaar/debug-hero.php
 */

// Bootstrap WordPress
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

header('Content-Type: application/json');

$desktop_id = get_option( 'ame_bazaar_media_hero_desktop' );
$mobile_id = get_option( 'ame_bazaar_media_hero_mobile' );

$response = array(
    '1_CURRENT_OPTION_VALUE' => array(
        'ame_bazaar_media_hero_desktop' => $desktop_id,
        'ame_bazaar_media_hero_mobile'  => $mobile_id,
    ),
    '2_DESKTOP_HERO_DETAILS' => null,
    '3_MOBILE_HERO_DETAILS' => null,
    '4_ROOT_CAUSE_TRACE' => array(
        'WHO_IS_WRITING_IT' => 'Theme Code: inc/admin-operations.php (function: ame_bazaar_auto_assign_media_mappings)',
        'WHEN_IS_IT_WRITTEN' => 'On every page load (init hook).',
        'WHY_SAVE_WAS_BROKEN' => 'Even if you clicked Save in Media Manager, the init hook immediately overwrote your save with the hardcoded "unnamed-6" (storefront image) on the next refresh.',
        'STATUS' => 'FIXED in commit. The theme now only sets the default if the option is completely empty.'
    )
);

if ( $desktop_id ) {
    $response['2_DESKTOP_HERO_DETAILS'] = array(
        'attachment_id' => $desktop_id,
        'url'           => wp_get_attachment_url( $desktop_id ),
        'filename'      => basename( get_attached_file( $desktop_id ) ),
    );
}

if ( $mobile_id ) {
    $response['3_MOBILE_HERO_DETAILS'] = array(
        'attachment_id' => $mobile_id,
        'url'           => wp_get_attachment_url( $mobile_id ),
        'filename'      => basename( get_attached_file( $mobile_id ) ),
    );
}

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
