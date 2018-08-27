<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-in-controller.php' );

/**
 * Class PTV_Organization_In_Controller
 */
class PTV_Organization_In_Controller extends PTV_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-organization';

	/**
	 * Create a new service.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_Organization_In();

		// Set source id.
		$request->set_source_id( $post_id );

		// Set parent organization ID.
		$request->set_parent_organization_id( $this->get_parent_organization_id() );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $this->valid( $request ) ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ), $request->list_invalid_properties() );

			return;
		}
		
		// Create new service.
		$new_organization = $this->api->get_organization_api()->create_organization( $request );

		if ( is_wp_error( $new_organization ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_organization->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_organization );

	}

	/**
	 * Update a service.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_Organization_In_Base();

		$id = $this->get_translation_group_id( $post_id );

		// Set ID.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $this->valid( $request ) ) {

			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		$update_organization = $this->api->get_organization_api()->update_organization_by_id( $id, $request );

		if ( is_wp_error( $update_organization ) ) {
			$this->errors->add( 'ptv-update-failed', __( 'Failed to update the item to the PTV.', 'ptv-for-wordpress' ), $update_organization->get_error_data() );

			return;
		}

		$this->sync( $post_id, $update_organization );

	}

	/**
	 * Get parent organization id.
	 */
	function get_parent_organization_id() {

		return ptv_get_organization_id();

	}


	/**
	 * Set request values for general fields.
	 *
	 * @param $post_id
	 * @param $request PTV_Organization_In|PTV_Organization_In_Base
	 *
	 * @return null
	 */
	public function set_general_fields( $post_id, $request ) {

		if ( ! $post_id && ! $request ) {
			return null;
		}

		// Set status.
		$request->set_publishing_status( $this->get_publishing_status( $post_id ) );

		// Set business code.
		$request->set_business_code( $this->get_business_code( $post_id ) );

		// Set display name type.
		$request->set_display_name_type( $this->get_display_name_type( $post_id ) );

		// Set organization type.
		$request->set_organization_type( $this->get_organization_type( $post_id ) );

		// Set organization names.
		$request->set_organization_names( $this->get_organization_names( $post_id ) );

		// Set organization descriptions.
		$request->set_organization_descriptions( $this->get_organization_descriptions( $post_id ) );

		// Set area type.
		$request->set_area_type( $this->get_area_type( $post_id ) );

		// Set areas.
		$request->set_areas( $this->get_areas( $post_id ) );

		return $request;

	}

	/**
	 * Get business code.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function get_business_code( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		return sanitize_text_field( carbon_get_post_meta( $post_id, 'ptv_business_code' ) );

	}

	/**
	 * Get organization type.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function get_organization_type( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		return sanitize_text_field( carbon_get_post_meta( $post_id, 'ptv_organization_type' ) );

	}

	/**
	 * Get display name type.
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	function get_display_name_type( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {


			$value = sanitize_text_field( carbon_get_post_meta( $id, 'ptv_display_name_type' ) );

			if ( $value ) {

				$name_type = new PTV_Name_Type_By_Language();
				$name_type
					->set_language( $lang )
					->set_type( $value );

				$result[] = $name_type;
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get organization names.
	 *
	 * @param null $post_id
	 *
	 * @return array|null
	 */
	function get_organization_names( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$names = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'Name'          => 'ptv_name',
				'AlternateName' => 'ptv_alternate_name',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']  = $type;
					$data['value'] = carbon_get_post_meta( $id, $field_key );
					$names[]       = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		return $names;

	}

	/**
	 * Get organization descriptions.
	 *
	 * @param null $post_id
	 *
	 * @return null
	 */
	function get_organization_descriptions( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$descriptions = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'ShortDescription' => 'ptv_short_description',
				'Description'      => 'ptv_description',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']   = $type;
					$data['value']  = carbon_get_post_meta( $id, $field_key );
					$descriptions[] = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		return $descriptions;

	}


	/**
	 * Synchronize fields that are common for all translations.
	 *
	 * @param $post_id
	 * @param $object
	 */
	public function sync( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			$this->errors->add( 'ptv-invalid-argument-error', __( 'Post id or object missing.', 'ptv-for-wordpress' ) );

			return;
		}

		// Sync unique ids.
		$sync_unique_ids = $this->sync_translation_group_ids( $post_id, $object );

		if ( ! $sync_unique_ids ) {
			$this->errors->add( 'ptv-unique-id-sync-error', __( 'Item was saved to the PTV, but synchronization of unique ids to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync areas.
		$sync_areas = $this->sync_areas( $post_id, $object );

		if ( ! $sync_areas ) {
			$this->errors->add( 'ptv-area-sync-error', __( 'Item was saved to the PTV, but synchronization of areas to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync modified time.
		$sync_modified = $this->sync_modified( $post_id, $object );

		if ( ! $sync_modified ) {
			$this->errors->add( 'ptv-modified-time-sync-error', __( 'Item was saved to the PTV, but synchronization of modified time to translations failed.', 'ptv-for-wordpress' ) );
		}

	}

	/**
	 * validate all the properties in the model
	 * return true if all passed
	 *
	 * @return bool True if all properties are valid
	 */
	public function valid( $request ) {

		if ( ! preg_match( '/^[A-Za-z0-9-.]*$/', $request->get_source_id() ) ) {
			return false;
		}
		if ( strlen( $request->get_oid() ) > 100 ) {
			return false;
		}
		if ( ! preg_match( '/^[A-Za-z0-9.-]*$/', $request->get_oid() ) ) {
			return false;
		}
		if ( ! is_null( $request->get_municipality() ) && ! preg_match( '/^[0-9]{1,3}$/', $request->get_municipality() ) ) {
			return false;
		}
		if ( ! preg_match( '/^[0-9]{7}-[0-9]{1}$/', $request->get_business_code() ) ) {
			return false;
		}
		if ( null === $request->get_publishing_status() ) {
			return false;
		}

		return true;
	}

}

