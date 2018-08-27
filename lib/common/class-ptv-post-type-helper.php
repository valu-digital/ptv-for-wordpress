<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-taxonomy-helper.php' );

class PTV_Post_Type_Helper {

	/**
	 * @var
	 */
	private $serializer;

	/**
	 * @var PTV_Taxonomy_Helper
	 */
	protected $taxonomy_helper;

	/**
	 * PTV_Post_Type_Helper constructor.
	 *
	 * @param PTV_Taxonomy_Helper|null $taxonomy_helper
	 */
	function __construct( PTV_Taxonomy_Helper $taxonomy_helper = null ) {

		if ( null === $taxonomy_helper ) {
			$taxonomy_helper = new PTV_Taxonomy_Helper();
		}

		$this->taxonomy_helper = $taxonomy_helper;

	}

	function set_serializer( $serializer ) {
		$this->serializer = $serializer;
	}

	/**
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	public function serialize( $data, $lang = 'fi' ) {

		if ( ! $this->serializer ) {
			error_log( 'no serializer defined' );

			return false;
		}

		if ( is_object( $data ) ) {

			$values = [];
			foreach ( array_keys( $data::types() ) as $property ) {

				$getter = $data::getters()[ $property ];
				if ( $data->$getter() !== null ) {

					$serialize_method = array( $this->serializer, sprintf( 'serialize_%s', $property ) );

					if ( true === is_callable( $serialize_method, false, $callable_name ) ) {

						$value = call_user_func_array( $serialize_method, array( $data->$getter(), $lang ) );

						if ( ! empty( $value ) && is_array( $value ) ) {
							$values = array_merge_recursive( $values, $value );
						}
					} else {
						error_log( sprintf( 'No serializer defined for %s', $property ) );
					}
				}
			}

			return $values;
		}

	}

	/**
	 * Update or insert a post.
	 *
	 * @param $prepared_post
	 *
	 * @return bool|int|WP_Error
	 */
	function update( $prepared_post ) {

		$unique_id = sprintf( '%s-%s', $prepared_post['_ptv_id'], $prepared_post['lang'] );

		// Get channel local id.
		$local_id = $this->get_local_id( $unique_id );

		// Update if local id exists.
		if ( $local_id ) {
			$prepared_post['post']['ID'] = $local_id;
			wp_update_post( $prepared_post['post'] );
		} else {
			$local_id = wp_insert_post( $prepared_post['post'] );
		}

		if ( ! $local_id ) {
			return false;
		}

		// Save unique id.
		update_post_meta( $local_id, '_ptv_unique_id', $unique_id );

		// Set post language if polylang is enabled
		if ( function_exists( 'pll_set_post_language' ) ) {
			pll_set_post_language( $local_id, $prepared_post['lang'] );
		}

		// Save post meta fields
		$this->save_post_meta_fields( $local_id, $prepared_post );

		// Save post taxonomies
		$this->save_post_taxonomies( $local_id, $prepared_post, $prepared_post['lang'] );

		return $local_id;


	}

	/**
	 * Get local ID by PTV unique id.
	 *
	 * @param string $ptv_unique_id
	 *
	 * @return bool|int
	 */
	public function get_local_id( $ptv_unique_id = '' ) {

		if ( ! $ptv_unique_id ) {
			return false;
		}

		global $wpdb;

		$local_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key ='_ptv_unique_id' AND meta_value = %s", $ptv_unique_id ) );

		if ( ! $local_id ) {
			return false;
		}

		return (int) $local_id;
	}


	/**
	 * Save post meta fields.
	 *
	 * @param string $local_id
	 * @param array $meta_fields
	 *
	 * @return bool
	 */
	public function save_post_meta_fields( $local_id = null, $meta_fields = array() ) {

		if ( ! $local_id ) {
			return false;
		}

		if ( ! is_array( $meta_fields ) || empty( $meta_fields ) ) {
			return false;
		}


		unset( $meta_fields['post'] );

		// Cleat previous fields before updating.
		$this->clear_post_meta_fields( $local_id );

		foreach ( $meta_fields as $key => $value ) {
			if ( 0 === strpos( $key, '_ptv_' ) ) {
				carbon_set_post_meta( $local_id, ltrim( $key, '_' ), $value, ptv_get_container_id( $local_id ) );
			}
		}

		return true;
	}


	/**
	 * Clear all PTV related post meta fields except unique id.
	 *
	 * @param null $local_id
	 *
	 * @return bool|false|int
	 */
	public function clear_post_meta_fields( $local_id = null ) {

		if ( ! $local_id ) {
			return false;
		}

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '%s' AND post_id ='%d' AND meta_key != '_ptv_unique_id'", $wpdb->esc_like( '_ptv_' ) . '%', $local_id ) );

		wp_cache_delete( $local_id, 'post_meta' );

		return $result;

	}

	/**
	 * Insert or update a taxonomy term.
	 *
	 * @param $term
	 * @param $taxonomy
	 * @param string $lang
	 *
	 * @return array|bool|int|WP_Error
	 */
	public function process_term( $term, $taxonomy, $lang ) {

		if ( empty( $term['uri'] ) || empty( $term['name'] ) ) {
			return false;
		}

		$local_term_id = $this->taxonomy_helper->get_term_id_by_uri( $term['uri'], $taxonomy, $lang );

		if ( $local_term_id and ! is_wp_error( $local_term_id ) ) {
			$local_term_id = $this->taxonomy_helper->update_term( $local_term_id, $term, $taxonomy, $lang );
		} else {
			$local_term_id = $this->taxonomy_helper->insert_term( $term, $taxonomy, $lang );
		}

		return $local_term_id;

	}

	/**
	 * Save post taxonimies.
	 *
	 * @param $local_id
	 * @param $prepared_post
	 * @param $lang
	 *
	 * @return bool
	 */
	public function save_post_taxonomies( $local_id, $prepared_post, $lang ) {

		if ( ! isset( $prepared_post['taxonomies'] ) || empty( $prepared_post['taxonomies'] ) || ! is_array( $prepared_post['taxonomies'] ) ) {
			return false;
		}

		foreach ( $prepared_post['taxonomies'] as $taxonomy => $terms ) {

			$object_terms = array();


			if ( $terms ) {
				foreach ( $terms as $term ) {


					if ( empty( $term['uri'] ) || empty( $term['name'] ) ) {
						continue;
					}

					$local_term_id = $this->process_term( $term, $taxonomy, $lang );

					if ( $local_term_id and ! is_wp_error( $local_term_id ) ) {
						$object_terms[] = $local_term_id;
					}
				}

				wp_set_object_terms( $local_id, array_map( 'intval', $object_terms ), $taxonomy, false );

			}
		}

		return true;

	}

	/**
	 * Save post translations.
	 *
	 * @param array $translations
	 *
	 * @return bool
	 */
	public function save_post_translations( $translations = array() ) {

		if ( empty( $translations ) ) {
			return false;
		}

		if ( function_exists( 'pll_save_post_translations' ) ) {
			pll_save_post_translations( $translations );
		}

		return true;

	}

}
