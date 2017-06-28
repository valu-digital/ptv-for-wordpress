<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Service_In_Controller
 */
abstract class PTV_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type;

	/**
	 * @var $settings array
	 */
	protected $settings;

	/**
	 * @var WP_Error
	 */
	protected $errors;

	/**
	 * PTV_In_Controller constructor.
	 *
	 * @param PTV_Api|null $api
	 */
	function __construct( PTV_Api $api = null ) {

		if ( null === $api ) {
			$api = new PTV_Api();
		}

		$this->api      = $api;
		$this->settings = ptv_get_settings();
		$this->errors   = new WP_Error();

	}

	/**
	 * Create or update PTV item on save_post hook.
	 *
	 * @param $post_id
	 */
	function save( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check that page is published
		if ( 'publish' !== get_post_status( $post_id ) ) {
			return;
		}

		if ( get_post_type( $post_id ) !== $this->post_type ) {
			return;
		}

		if ( ! function_exists( 'carbon_get_post_meta' ) ) {
			return;
		}

		$lang      = $this->get_post_language( $post_id );
		$unique_id = $this->get_common_post_meta( $post_id, '_ptv_unique_id' );

		if ( ! $unique_id and $lang === $this->settings['primary_language'] ) {
			$this->create( $post_id );
		} elseif ( $unique_id ) {
			$this->update( $post_id );
		} else {
			$this->errors->add( 'ptv-translation-error', __( 'New items can only be created in the primary language. Please attach translation to an item created in the primary language.', 'ptv-for-wordpress' ) );
		}

		if ( $this->errors->get_error_messages() ) {
			set_transient( sprintf( 'ptv_error_messages_%d_%d', get_current_user_id(), $post_id ), $this->errors, 60 );
		}

	}

	/**
	 * Abstract function that every IN API Controller must implement.
	 *
	 * @param null $post_id
	 */
	abstract protected function create( $post_id = null );

	/**
	 * Abstract function that every IN API Controller must implement.
	 *
	 * @param null $post_id
	 */
	abstract protected function update( $post_id = null );

	/**
	 * Get languages.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_languages( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (array) carbon_get_post_meta( $post_id, 'ptv_languages' );

	}

	/**
	 * Get common id for translations.
	 *
	 * @param null $post_id
	 *
	 * @return mixed|null
	 */
	function get_translation_group_id( $post_id = null ) {

		if ( ! $post_id ) {
			return false;
		}

		$ptv_id = $this->get_common_post_meta( $post_id, '_ptv_id' );

		return $ptv_id;

	}

	/**
	 * Get post meta or item  primary language.
	 *
	 * @param null $post_id
	 * @param null $key
	 *
	 * @return bool|mixed
	 */
	public function get_common_post_meta( $post_id = null, $key = null ) {

		if ( ! $post_id || ! $key ) {
			return false;
		}

		$meta_value = get_post_meta( $post_id, $key, true );

		if ( function_exists( 'pll_get_post_translations' ) ) {
			$post_translations = pll_get_post_translations( $post_id );

			if ( isset( $post_translations[ $this->settings['primary_language'] ] ) ) {
				$meta_value = get_post_meta( $post_translations[ $this->settings['primary_language'] ], $key, true );
			}
		}

		return $meta_value;
	}

	/**
	 * Get post language.
	 *
	 * @param $post_id
	 *
	 * @return bool|string
	 */
	public function get_post_language( $post_id ) {
		$post_language = ( function_exists( 'pll_get_post_language' ) ) ? pll_get_post_language( $post_id ) : (string) $this->settings['primary_language'];

		return $post_language;
	}

	/**
	 * Get post translations.
	 *
	 * @param $post_id
	 *
	 * @return array|bool
	 */
	public function get_post_translations( $post_id ) {

		if ( ! $post_id ) {
			return false;
		}

		$post_language = $this->get_post_language( $post_id );

		$post_translations = array( $post_language => $post_id );

		if ( function_exists( 'pll_get_post_translations' ) ) {
			$post_translations = pll_get_post_translations( $post_id );
		}

		return $post_translations;
	}

	/**
	 * Prepare localized list item for API request.
	 *
	 * @param $data
	 * @param $lang
	 *
	 * @return null|PTV_Localized_List_Item
	 */
	function prepare_localized_list_item( $data, $lang ) {

		if ( ! $data || ! is_array( $data ) || ! $lang ) {
			return null;
		}

		if ( ! isset( $data['type'] ) || ! isset( $data['value'] ) ) {
			return null;
		}

		$item = new PTV_Localized_List_Item();
		$item->set_type( $data['type'] )->set_value( $data['value'] )->set_language( $lang );

		return $item;

	}

	/**
	 * Prepare language item for API request.
	 *
	 * @param $value
	 * @param $lang
	 *
	 * @return null|PTV_Language_Item
	 */
	public function prepare_language_item( $value, $lang ) {

		if ( ! $value || ! $lang ) {
			return null;
		}

		$language_item = new PTV_Language_Item();

		$language_item
			->set_value( $value )
			->set_language( $lang );

		return $language_item;
	}

	/**
	 * Sync unique ids to translations.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_translation_group_ids( $post_id, $object ) {

		if ( ! $post_id ) {
			return false;
		}

		if ( ! $object ) {
			return false;
		}

		$object_id         = $object->get_id();
		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $translation_post_id ) {
			$unique_id = sprintf( '%s-%s', $object_id, $lang );
			update_post_meta( $translation_post_id, '_ptv_unique_id', $unique_id );
			update_post_meta( $translation_post_id, '_ptv_id', $object_id );
		}

		return true;

	}

	/**
	 * Get publishing status.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function get_publishing_status( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$status = carbon_get_post_meta( $post_id, 'ptv_publishing_status' );

		return $status;
	}

	/**
	 * Get area type.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_area_type( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return get_post_meta( $post_id, '_ptv_area_type', true );

	}

	/**
	 * Get areas.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_areas( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$areas = carbon_get_post_meta( $post_id, 'ptv_areas' );

		if ( empty( $areas ) || ! is_array( $areas ) ) {
			return null;
		}

		foreach ( $areas as $area ) {

			$area_object = new PTV_Area_In();

			$area_object
				->set_type( $area['type'] )
				->set_area_codes( $area['municipalities'] );

			$result[] = $area_object;

		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Sync areas.
	 *
	 * @param $post_id
	 * @param $updated_object
	 *
	 * @return bool
	 */
	public function sync_areas( $post_id, $updated_object ) {

		if ( ! $post_id || ! $updated_object ) {
			return false;
		}

		$areas        = array();
		$area_objects = $updated_object->get_areas();

		if ( ! empty( $area_objects ) ) {

			foreach ( $area_objects as $area_object ) {

				$area           = array();
				$municipalities = array();
				$area['type']   = $area_object->get_type();

				$municipality_objects = $area_object->get_municipalities();

				if ( ! empty( $municipality_objects ) ) {

					foreach ( $municipality_objects as $municipality_object ) {
						$municipalities[] = $municipality_object->get_code();
					}

					$area['municipalities'] = $municipalities;
				}
				$areas[] = $area;
			}
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			carbon_set_post_meta( $id, 'ptv_area_type', $updated_object->get_area_type() );

			if ( ! empty( $areas ) ) {
				carbon_set_post_meta( $id, 'ptv_areas', $areas );
			}
		}

		return true;

	}

	/**
	 * Sync languages.
	 *
	 * @param $post_id
	 * @param $updated_object
	 *
	 * @return bool
	 */
	public function sync_languages( $post_id, $updated_object ) {

		if ( ! $post_id || ! $updated_object ) {
			return false;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			if ( ! empty( $updated_object->get_languages() ) ) {
				carbon_set_post_meta( $id, 'ptv_languages', $updated_object->get_languages() );
			}
		}

		return true;

	}

}