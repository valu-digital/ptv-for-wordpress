<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/helpers/class-ptv-taxonomy-helper.php' );

use Carbon_Fields\Toolset\Key_Toolset;

class PTV_Post_Type_Helper {

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

	/**
	 * Converts PTV data to the Carbon Fields compatible meta fields.
	 *
	 * @param $data
	 * @param string $lang
	 * @param null $parent
	 * @param null $current_index
	 *
	 * @return array|string
	 */
	public static function prepare( $data, $lang = 'fi', $parent = null, $current_index = null ) {

		// Use Carbon fiels key toolset to generate keys.
		$key_tools = new Key_Toolset();

		// Carbon fields has some reserved property names.
		$reserved_properties = array(
			'value',
		);

		if ( is_bool( $data ) ) {
			$integer = ( false === $data ) ? 0 : 1;

			return intval( $integer );
		} elseif ( is_scalar( $data ) || null === $data ) {
			return sanitize_text_field( $data );
		} elseif ( $data instanceof \DateTime ) {
			return $data->format( 'Y-m-d' );
		} elseif ( is_array( $data ) ) {
			foreach ( $data as $property => $value ) {
				$data[ $property ] = self::prepare( $value, $lang );
			}

			return $data;
		} elseif ( is_object( $data ) ) {

			$values = array();

			foreach ( $data::types() as $property => $type ) {
				$getter = $data::getters()[ $property ];
				if ( $data->$getter() !== null ) {

					// Add underscore if property name is reserved for carbon fields
					if ( in_array( $property, $reserved_properties, true ) ) {
						$property = sprintf( '_%s', $property );
					}

					if ( null === $parent ) {
						$key                  = '_ptv_' . $property;
						$full_hierarchy       = array( ltrim( $key, '_' ) );
						$full_hierarchy_index = array();
					} else {

						$parent_key     = $key_tools->parse_storage_key( $parent );
						$full_hierarchy = array_merge( $parent_key['full_hierarchy'], array( $property ) );

						if ( ! empty( $parent_key['hierarchy'] ) ) {
							array_push( $parent_key['hierarchy_index'], 0 );
						}

						array_push( $parent_key['hierarchy_index'], strval( $current_index ) );

						$full_hierarchy_index = $parent_key['hierarchy_index'];

						$key = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, 0, 'value' );

					}

					if ( strcasecmp( substr( $type, - 2 ), '[]' ) === 0 ) {

						$index = 0;

						$language_index = array(
							'fi' => 0,
							'en' => 0,
							'sv' => 0,
						);

						foreach ( $data->$getter() as $subdata ) {

							switch ( $type ) {

								case 'string[]' :
									foreach ( $data->$getter() as $item_index => $value ) {
										$key            = $key_tools->get_storage_key( false, array( 'ptv_' . $property ), array(), $item_index, 'value' );
										$values[ $key ] = $value;
									}
									break;

								// Language items.
								case 'PTV_Language_Item[]' :
									if ( ( $subdata->get_language() === $lang ) ) {
										$values[ $key ] = self::prepare( $subdata->get_value(), $lang );
									}
									break;

								// Localized list items.
								case 'PTV_Localized_List_Item[]':
									if ( ( $subdata->get_language() === $lang ) ) {

										if ( 'channel_urls' === $property ) {
											$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $language_index[ $lang ], 'value' );
											$values[ $parent_key ] = '_';
											$values                = array_merge( $values, self::prepare( $subdata, $lang, $key, $language_index[ $lang ] ) );
											$language_index[ $lang ] ++;
										} else {
											$values[ ptv_to_snake_case( sprintf( '%s_%s', $key, $subdata->get_type() ) ) ] = self::prepare( $subdata->get_value(), $lang );
										}
									}
									break;

								// Taxonomies.
								case 'PTV_Finto_Item[]':
									$values['taxonomies'][ ptv_to_taxomomy_name( $key ) ] = self::prepare( $data->$getter(), $lang );
									break;

								// Localized items.
								case 'PTV_Web_Page[]' :
								case 'PTV_Web_Page_With_Order_Number[]' :
								case 'PTV_Attachment[]' :
								case 'PTV_Attachment_With_Type[]' :
								case 'PTV_Email[]' :
								case 'PTV_Phone[]' :
								case 'PTV_Phone_Simple[]' :
								case 'PTV_Phone_With_Type[]' :
								case 'PTV_Phone_Channel_Phone[]' :
									if ( ( $subdata->get_language() === $lang ) ) {

										$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $language_index[ $lang ], 'value' );
										$values[ $parent_key ] = '_';

										$values = array_merge( $values, self::prepare( $subdata, $lang, $key, $language_index[ $lang ] ) );
										$language_index[ $lang ] ++;
									}
									break;

								// Localized items.
								case 'PTV_Service_Organization[]' :

									if ( ( 'VoucherServices' === $subdata->get_provision_type() ) ) {

										$additional_informations = $subdata->get_additional_information();

										if ( ! $additional_informations or ! is_array( $additional_informations ) ) {
											continue;
										}

										$has_current_language = false;

										foreach ( $additional_informations as $additional_information ) {
											if ( $lang === $additional_information->get_language() ) {
												$has_current_language = true;
												break;
											}
										}

										if ( ! $has_current_language ) {
											continue;
										}

										$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $language_index[ $lang ], 'value' );
										$values[ $parent_key ] = '_';

										$values = array_merge( $values, self::prepare( $subdata, $lang, $key, $language_index[ $lang ] ) );
										$language_index[ $lang ] ++;

									} else {

										$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $language_index[ $lang ], 'value' );
										$values[ $parent_key ] = '_';

										$values = array_merge( $values, self::prepare( $subdata, $lang, $key, $language_index[ $lang ] ) );
										$language_index[ $lang ] ++;

									}
									break;

								// Service channels. Special case.
								case 'PTV_Service_Service_Channel[]' :

									$key            = sprintf( '%s_-_service_channel_id_%s', '_ptv_' . $property, $index );
									$values[ $key ] = self::prepare( $subdata->get_service_channel_id(), $lang );
									break;

								// Localized items.
								case 'PTV_Municipality[]' :
									$key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $index, 'value' );
									$values[ $key ] = self::prepare( $subdata->get_code(), $lang );
									break;

								default:
									if ( is_object( $subdata ) ) {

										$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, $index, 'value' );
										$values[ $parent_key ] = '_';

										$values = array_merge( $values, self::prepare( $subdata, $lang, $key, $index ) );
									}
							}

							$index ++;
						}
					} elseif ( 'PTV_Municipality' === $type ) {
						$municipality   = $data->$getter();
						$values[ $key ] = self::prepare( $municipality->get_code(), $lang );
					} elseif ( 'PTV_Address_With_Coordinates' === $type ) {
						$parent_key            = $key_tools->get_storage_key( false, $full_hierarchy, $full_hierarchy_index, 0, 'value' );
						$values[ $parent_key ] = '_';
						$values                = array_merge( $values, self::prepare( $data->$getter(), $lang, $key, 0 ) );
					} else {
						$values[ $key ] = self::prepare( $data->$getter(), $lang );
					}
				}
			}

			return (array) $values;
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

		// Cleat previous fields before updating.
		$this->clear_post_meta_fields( $local_id );

		foreach ( $meta_fields as $key => $value ) {
			if ( 0 === strpos( $key, '_ptv_' ) ) {
				update_post_meta( $local_id, $key, $value );
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

		if ( empty( $term['_ptv_uri'] ) || empty( $term['_ptv_name'] ) ) {
			return false;
		}

		$local_term_id = $this->taxonomy_helper->get_term_id_by_uri( $term['_ptv_uri'], $taxonomy, $lang );

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

					if ( empty( $term['_ptv_uri'] ) || empty( $term['_ptv_name'] ) ) {
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