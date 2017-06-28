<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PTV_Taxonomy_Helper {

	/**
	 * @var $meta_key string
	 */
	protected $meta_key;

	/**
	 * PTV_Taxonomy_Helper constructor.
	 */
	function __construct() {

		$this->meta_key = '_ptv_unique_id';

	}

	/**
	 * Get taxonomy term by uri.
	 *
	 * @param $uri
	 * @param $taxonomy
	 * @param string $lang
	 *
	 * @return array|bool|int|WP_Error
	 */
	function get_term_id_by_uri( $uri, $taxonomy, $lang ) {

		if ( empty( $taxonomy ) || empty( $uri ) || empty( $lang ) ) {
			return new WP_Error( 'term-insert-error', 'Taxonomy, uri || language missing' );
		}

		$uri = strval( sprintf( '%s-%s', $uri, $lang ) );

		$args = array(
			'taxonomy'   => esc_html( $taxonomy ),
			'hide_empty' => false,
			'lang'       => '',
			'meta_query' => array(
				array(
					'key'   => $this->meta_key,
					'value' => $uri,
					'type'  => 'CHAR',
				),
			),
			'fields'     => 'ids',
		);

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		if ( count( $terms ) > 1 ) {
			return new WP_Error( 'ptv-get-term-error', sprintf( 'Found multiple terms with meta_key %s and meta_value: %s ', $this->meta_key, $uri ) );
		}

		if ( ! empty( $terms ) ) {
			return $terms[0];
		}

		return false;

	}

	/**
	 * Insert a taxonomy term.
	 *
	 * @param $term
	 * @param $taxonomy
	 * @param string $lang
	 *
	 * @return array|bool|int|WP_Error
	 */
	function insert_term( $term, $taxonomy, $lang ) {

		$args = array();

		if ( empty( $term ) || empty( $taxonomy ) || empty( $lang ) ) {
			return false;
		}

		if ( empty( $term['_ptv_uri'] ) || empty( $term['_ptv_name'] ) ) {
			return false;
		}

		if ( ! empty( $term['_ptv_parent_uri'] ) ) {

			$parent_id = $this->get_term_id_by_uri( strval( $term['_ptv_parent_uri'] ), $taxonomy, $lang );

			if ( is_wp_error( $parent_id ) ) {
				return $parent_id;
			}

			$args['parent'] = $parent_id;
		}

		$local_term = wp_insert_term( $term['_ptv_name'], $taxonomy, $args );

		if ( is_wp_error( $local_term ) ) {
			return false;
		}

		if ( function_exists( 'pll_set_term_language' ) ) {
			pll_set_term_language( $local_term['term_id'], $lang );
		}

		update_term_meta( $local_term['term_id'], $this->meta_key, strval( sprintf( '%s-%s', $term['_ptv_uri'], $lang ) ) );
		update_term_meta( $local_term['term_id'], '_ptv_uri', $term['_ptv_uri'] );

		if ( isset( $term['_ptv_parent_uri'] ) && ! empty( $term['_ptv_parent_uri'] ) ) {
			update_term_meta( $local_term['term_id'], '_ptv_parent_uri', $term['_ptv_parent_uri'] );
		}

		return $local_term['term_id'];

	}

	/**
	 * Update a taxonomy term.
	 *
	 * @param $term_id
	 * @param $term
	 * @param $taxonomy
	 * @param $lang
	 *
	 * @return array|bool|int|WP_Error
	 */
	function update_term( $term_id, $term, $taxonomy, $lang ) {

		$args = array();

		if ( empty( $term_id ) || empty( $taxonomy ) || empty( $term ) || empty( $lang ) ) {
			return false;
		}

		if ( empty( $term['_ptv_uri'] ) || empty( $term['_ptv_name'] ) ) {
			return false;
		}

		if ( ! empty( $term['_ptv_parent_uri'] ) ) {

			$parent_id = $this->get_term_id_by_uri( strval( $term['_ptv_parent_uri'] ), $taxonomy, $lang );

			if ( is_wp_error( $parent_id ) ) {
				return false;
			}

			$args['parent'] = $parent_id;

		}

		if ( ! empty( $term['_ptv_name'] ) ) {
			$args['_ptv_name'] = $term['_ptv_name'];
		}

		$local_term = wp_update_term( $term_id, $taxonomy, $args );

		if ( is_wp_error( $local_term ) ) {
			return $local_term;
		}

		if ( function_exists( 'pll_set_term_language' ) ) {
			pll_set_term_language( $local_term['term_id'], $lang );
		}

		return $local_term['term_id'];

	}


}