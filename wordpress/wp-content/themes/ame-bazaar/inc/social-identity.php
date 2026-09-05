<?php
/**
 * AME Bazaar official social identity signals.
 *
 * Keeps the existing connected JSON-LD graph intact while ensuring the
 * Organization and ClothingStore entities use the official social profiles.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ame_bazaar_official_social_profiles() {
	return array(
		'https://www.facebook.com/AMETTBAZAAR',
		'https://www.instagram.com/ame_bazaar/',
	);
}

function ame_bazaar_filter_organization_social_identity( $schema ) {
	$schema['name']   = 'AME Bazaar - Family Garment Store';
	$schema['url']    = home_url( '/' );
	$schema['sameAs'] = ame_bazaar_official_social_profiles();

	return $schema;
}
add_filter( 'ame_bazaar_organization_schema', 'ame_bazaar_filter_organization_social_identity', 20 );

function ame_bazaar_filter_clothing_store_social_identity( $schema ) {
	$schema['name']   = 'AME Bazaar - Family Garment Store';
	$schema['url']    = home_url( '/' );
	$schema['sameAs'] = ame_bazaar_official_social_profiles();

	return $schema;
}
add_filter( 'ame_bazaar_clothing_store_schema', 'ame_bazaar_filter_clothing_store_social_identity', 20 );
