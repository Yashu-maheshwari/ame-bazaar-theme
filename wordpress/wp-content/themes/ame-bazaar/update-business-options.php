<?php
/**
 * Temp script to update database options to match real Google Business Profile.
 */
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

header( 'Content-Type: application/json' );

update_option( 'ame_bazaar_phone', '+91 99535 69533' );
update_option( 'ame_bazaar_whatsapp', '+91 99535 69533' );
update_option( 'ame_bazaar_address', 'Mubarakpur Road, near Chappan Bhog, Kirari Suleman Nagar, Delhi – 110086' );
update_option( 'ame_bazaar_hours', 'Mo-Su 09:00–22:00' );
update_option( 'ame_bazaar_google_reviews_rating', '4.9' );
update_option( 'ame_bazaar_google_reviews_count', '524' );
update_option( 'ame_bazaar_google_review_url', 'https://search.google.com/local/writereview?placeid=ChIJTgAADinpDDkRTr27xpunNWM' );
update_option( 'ame_bazaar_maps_url', 'https://maps.google.com/?q=AME+Bazaar+Kirari+Delhi' );

// Clean options array
$updated = array(
	'phone'        => get_option( 'ame_bazaar_phone' ),
	'whatsapp'     => get_option( 'ame_bazaar_whatsapp' ),
	'address'      => get_option( 'ame_bazaar_address' ),
	'hours'        => get_option( 'ame_bazaar_hours' ),
	'rating'       => get_option( 'ame_bazaar_google_reviews_rating' ),
	'review_count' => get_option( 'ame_bazaar_google_reviews_count' )
);

// Clear LSCache
if ( has_action( 'litespeed_purge_all' ) ) {
	do_action( 'litespeed_purge_all' );
	$purged = 'Purged LSCache via hook';
} else {
	$purged = 'LSCache hook not found';
}

echo json_encode( array(
	'success' => true,
	'purged'  => $purged,
	'updated' => $updated
), JSON_PRETTY_PRINT );
