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

/**
 * Sync fields that are common for all languages.
 *
 * @param $post_type
 * @param $post
 */
function ptv_sync_common_fields( $post_type, $post ) {

	if ( 'post-new.php' == $GLOBALS['pagenow'] && isset( $_GET['from_post'], $_GET['new_lang'] ) && in_array( $post->post_type, ptv_get_post_types(), true ) && pll_is_translated_post_type( $post->post_type ) ) {
		$settings = ptv_get_settings();

		// Capability check already done in post-new.php
		$from_post_id = (int) $_GET['from_post'];
		$lang         = PLL()->model->get_language( $_GET['new_lang'] );

		if ( ! $from_post_id || ! $lang ) {
			return;
		}

		$post_translations = pll_get_post_translations( $from_post_id );

		if ( ! isset( $post_translations[ $settings['primary_language'] ] ) ) {
			return;
		}

		$primary_language_from_post_id = (int) $post_translations[ $settings['primary_language'] ];

		if ( ! $primary_language_from_post_id ) {
			return;
		}

		$fields_to_sync = array(
			'ptv_service_hours',
			'ptv_areas',
			'ptv_area_type',
			'ptv_languages',
			'ptv_service_channels',
			'ptv_organizations',
			'ptv_addresses',
			'ptv_service_charge_type',
			'ptv_delivery_address',
			'ptv_requires_signature',
			'ptv_requires_authentication',
			'ptv_signature_quantity',
		);

		$custom_fields = get_post_custom( $primary_language_from_post_id );

		foreach ( $custom_fields as $key => $value ) {

			$sync = false;

			foreach ( $fields_to_sync as $field ) {

				if ( stripos( $key, $field ) !== false ) {
					$sync = true;
					break;
				}
			}

			if ( $sync && $value ) {
				update_post_meta( $post->ID, $key, reset( $value ) );
			}
		}

		// Copy taxonomies to the new post.
		ptv_copy_taxonomies( $from_post_id, $post->ID, $lang->slug );
	}
}

add_action( 'add_meta_boxes', 'ptv_sync_common_fields', PHP_INT_MAX, 2 );

/**
 * Copy or synchronize terms
 *
 * @param int $from id of the post from which we copy informations
 * @param int $to id of the post to which we paste informations
 * @param string $lang language slug
 */
function ptv_copy_taxonomies( $from, $to, $lang ) {

	$taxonomy_helper = new PTV_Taxonomy_Helper();

	// Get taxonomies to sync for this post type
	$taxonomies = array_intersect( get_post_taxonomies( $from ), ptv_get_taxonomies() );

	// Copy terms
	foreach ( $taxonomies as $tax ) {
		$terms = get_the_terms( $from, $tax );

		// Translated taxonomy
		if ( pll_is_translated_taxonomy( $tax ) ) {
			$newterms = array();
			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {

					$term_uri = get_term_meta( $term->term_id, 'uri', true );

					if ( ! $term_uri ) {
						continue;
					}

					$translated_term_id = $taxonomy_helper->get_term_id_by_uri( $term_uri, $tax, $lang );

					if ( $translated_term_id ) {
						$newterms[] = (int) $translated_term_id;
					}
				}
			}

			if ( ! empty( $newterms ) ) {
				wp_set_object_terms( $to, $newterms, $tax ); // replace terms in translation
			}
		}
	}
}

/**
 * Load custom fields also on auto-drafts.
 *
 * @param $storage_array
 * @param $datastore
 * @param $storage_key_patterns
 *
 * @return array|null|object
 */
function ptv_init_custom_fields_on_autodraft( $storage_array, $datastore, $storage_key_patterns ) {

	global $wpdb, $post;

	if ( in_array( $post->post_type, ptv_get_post_types() ) ) {

		$key_toolset = \Carbon_Fields\Carbon_Fields::resolve( 'key_toolset' );

		$storage_key_comparisons = $key_toolset->storage_key_patterns_to_sql( '`meta_key`', $storage_key_patterns );

		if ( ! $datastore->get_object_id() && isset( $post->ID ) && ! empty( $post->ID ) ) {
			$datastore->set_object_id( $post->ID );

			$storage_array = $wpdb->get_results( '
			SELECT `meta_key` AS `key`, `meta_value` AS `value`
			FROM ' . $datastore->get_table_name() . '
			WHERE `' . $datastore->get_table_field_name() . '` = ' . intval( $datastore->get_object_id() ) . '
				AND ' . $storage_key_comparisons . '
			ORDER BY `meta_key` ASC
		' );

		}
	}

	return $storage_array;
}

add_filter( 'carbon_fields_datastore_storage_array', 'ptv_init_custom_fields_on_autodraft', PHP_INT_MAX, 3 );




