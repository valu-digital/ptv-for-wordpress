<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enable translations for post types.
 *
 * @param $post_types
 * @param $is_settings
 *
 * @return mixed
 */
function ptv_add_post_types_to_polylang( $post_types, $is_settings ) {

	if ( $is_settings ) {
		$post_types = array_diff( $post_types, ptv_get_post_types() );
	} else {
		$post_types = array_merge( $post_types, ptv_get_post_types() );
	}

	return $post_types;
}

add_filter( 'pll_get_post_types', 'ptv_add_post_types_to_polylang', 10, 2 );

/**
 * Enable translations for taxonomies.
 *
 * @param $taxonomies
 * @param $is_settings
 *
 * @return array
 */
function ptv_add_taxonomies_to_polylang( $taxonomies, $is_settings ) {

	if ( $is_settings ) {
		$taxonomies = array_diff( $taxonomies, ptv_get_taxonomies() );
	} else {
		$taxonomies = array_merge( $taxonomies, ptv_get_taxonomies() );
	}

	return $taxonomies;
}

add_filter( 'pll_get_taxonomies', 'ptv_add_taxonomies_to_polylang', 10, 2 );
