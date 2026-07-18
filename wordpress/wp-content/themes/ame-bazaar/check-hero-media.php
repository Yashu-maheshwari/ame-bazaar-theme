<?php
if ( ! defined( 'ABSPATH' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

$options = array(
	'ame_bazaar_media_hero_desktop',
	'ame_bazaar_media_hero_mobile',
	'ame_bazaar_media_hero_summer_video',
	'ame_bazaar_media_hero_summer_video_mobile',
	'ame_bazaar_media_hero_summer_poster',
	'ame_bazaar_media_hero_festive',
	'ame_bazaar_media_hero_festive_mobile',
	'ame_bazaar_media_hero_festive_video',
	'ame_bazaar_media_hero_festive_video_mobile',
	'ame_bazaar_media_hero_festive_poster',
	'ame_bazaar_media_hero_winter',
	'ame_bazaar_media_hero_winter_mobile',
	'ame_bazaar_media_hero_winter_video',
	'ame_bazaar_media_hero_winter_video_mobile',
	'ame_bazaar_media_hero_winter_poster',
);

echo "=== HERO OPTIONS AUDIT ===\n";
foreach ( $options as $opt ) {
	$val = get_option( $opt );
	echo "$opt: ";
	if ( $val ) {
		$url = wp_get_attachment_url( $val );
		$is_video = wp_attachment_is( 'video', $val );
		echo "ID ($val) | URL ($url) | Is Video: " . ($is_video ? 'Yes' : 'No') . "\n";
	} else {
		echo "EMPTY\n";
	}
}

echo "\n=== ALL MEDIA ATTACHMENTS ===\n";
$query = new WP_Query( array(
	'post_type'      => 'attachment',
	'post_status'    => 'inherit',
	'posts_per_page' => 100,
) );
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		$id = get_the_ID();
		$slug = get_post_field( 'post_name', $id );
		$mime = get_post_mime_type( $id );
		$url = wp_get_attachment_url( $id );
		echo "ID: $id | Slug: $slug | Mime: $mime | URL: $url\n";
	}
	wp_reset_postdata();
} else {
	echo "No attachments found!\n";
}

// Check how many slides are resolved in php
$collection_data = array(
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_desktop' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_summer_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_summer_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_summer_poster' ),
	),
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_festive' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_festive_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_festive_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_festive_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_festive_poster' ),
	),
	array(
		'desktop_id'       => (int) get_option( 'ame_bazaar_media_hero_winter' ),
		'mobile_id'        => (int) get_option( 'ame_bazaar_media_hero_winter_mobile' ),
		'video_id'         => (int) get_option( 'ame_bazaar_media_hero_winter_video' ),
		'video_mobile_id'  => (int) get_option( 'ame_bazaar_media_hero_winter_video_mobile' ),
		'poster_id'        => (int) get_option( 'ame_bazaar_media_hero_winter_poster' ),
	),
);

$slides = array_values( array_filter( $collection_data, function( $s ) {
	return $s['desktop_id'] > 0 || $s['video_id'] > 0;
} ) );

echo "Resolved slides count: " . count( $slides ) . "\n";
foreach ( $slides as $i => $s ) {
	echo "Slide $i: desktop_id(" . $s['desktop_id'] . ") video_id(" . $s['video_id'] . ") poster_id(" . $s['poster_id'] . ")\n";
}
