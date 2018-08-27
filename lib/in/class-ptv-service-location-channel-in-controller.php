<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-channel-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
class PTV_Service_Location_Channel_In_Controller extends PTV_Service_Channel_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-service-location';

	/**
	 * Create a new service location.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_Service_Location_Channel_In();

		// Set source id.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $request->valid() ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		// Create a new channel.
		$new_channel = $this->api->get_service_channel_api()->create_service_location( $request );

		if ( is_wp_error( $new_channel ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_channel->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_channel );

	}

	/**
	 * Update a service location.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_Service_Location_Channel_In_Base();

		$id = $this->get_translation_group_id( $post_id );

		// Set ID.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $request->valid() ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		// Create a new channel.
		$updated_channel = $this->api->get_service_channel_api()->update_service_location_by_id( $id, $request );

		if ( is_wp_error( $updated_channel ) ) {
			$this->errors->add( 'ptv-update-failed', __( 'Failed to update the item to the PTV.', 'ptv-for-wordpress' ), $updated_channel->get_error_data() );

			return;
		}

		$this->sync( $post_id, $updated_channel );

	}

	/**
	 * Set request values for general fields.
	 *
	 * @param $post_id
	 * @param $request PTV_Service_Location_Channel_In|PTV_Service_Location_Channel_In_Base
	 *
	 * @return null
	 */
	public function set_general_fields( $post_id, $request ) {

		if ( ! $post_id && ! $request ) {
			return null;
		}

		// Set status.
		$request->set_publishing_status( $this->get_publishing_status( $post_id ) );

		// Set service names.
		$request->set_service_channel_names( $this->get_service_channel_names( $post_id ) );

		// Set service descriptions.
		$request->set_service_channel_descriptions( $this->get_service_channel_descriptions( $post_id ) );

		// Set area type.
		$request->set_area_type( $this->get_area_type( $post_id ) );

		// Set areas.
		if ( 'AreaType' === $request->get_area_type() ) {
			$request->set_areas( $this->get_areas( $post_id ) );
		}

		// Set addresses.
		$request->set_addresses( $this->get_addresses( $post_id ) );

		// Set languages.
		$request->set_languages( $this->get_languages( $post_id ) );

		// Set service hours.
		$request->set_service_hours( $this->get_service_hours( $post_id ) );

		// Set phone numbers.
		$request->set_phone_numbers( $this->get_phone_numbers( $post_id ) );

		// Set fax numbers.
		$request->set_fax_numbers( $this->get_fax_numbers( $post_id ) );

		// Set phone numbers.
		$request->set_web_pages( $this->get_web_pages( $post_id ) );

		// Set emails.
		$request->set_emails( $this->get_emails( $post_id ) );

		// Set organization id.
		$request->set_organization_id( $this->get_organization_id( $post_id ) );


		return $request;

	}

	/**
	 * Get addresses.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_addresses( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$merged_result     = array();
		$post_language     = $this->get_post_language( $post_id );
		$post_translations = $this->get_post_translations( $post_id );

		$all_addresses = array();

		foreach ( $post_translations as $lang => $id ) {

			$localized_addresses = carbon_get_post_meta( $id, 'ptv_addresses', ptv_get_container_id( $post_id ) );

			if ( $localized_addresses ) {
				$all_addresses[ $lang ] = $localized_addresses;
			}
		}

		$addresses = $all_addresses[ $post_language ];

		// Fallback to the default language.
		if ( ! $addresses ) {
			$addresses = $addresses[ $this->settings['primary_language'] ];
		}

		if ( ! $addresses || ! is_array( $addresses ) ) {
			return null;
		}

		foreach ( $addresses as $address_index => $address ) {

			$address_object = new PTV_Address_With_Moving_In();

			$address_object->set_type( $address['type'] );
			$address_object->set_sub_type( $address['sub_type'] );

			switch ( $address['sub_type'] ) {

				case 'Single':
				case 'Street':

					foreach ( $address['street_address'] as $street_address_index => $street_address ) {

						$street_address_object = new PTV_Street_Address_With_Coordinates_In();
						$street_address_object->set_street_number( $street_address['street_number'] );
						$street_address_object->set_postal_code( $street_address['postal_code'] );

						if ( $street_address['municipality'] ) {
							$street_address_object->set_municipality( $street_address['municipality'] );
						}

						$localized_streets                = array();
						$localized_additional_information = array();

						foreach ( $post_translations as $lang => $id ) {

							if ( isset( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['additional_information'] ) && ! empty( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['additional_information'] ) ) {
								$localized_additional_information[] = $this->prepare_language_item( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['additional_information'], $lang );
							}

							if ( isset( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['street'] ) && ! empty( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['street'] ) ) {
								$localized_streets[] = $this->prepare_language_item( $all_addresses[ $lang ][ $address_index ]['street_address'][ $street_address_index ]['street'], $lang );
							}
						}

						if ( ! empty( $localized_streets ) ) {
							$street_address_object->set_street( $localized_streets );
						}

						if ( ! empty( $localized_additional_information ) ) {
							$street_address_object->set_additional_information( $localized_additional_information );
						}

						if ( ! empty( $street_address_object ) ) {
							$address_object->set_street_address( $street_address_object );
						}

						break;

					}

					break;

				case 'PostOfficeBox':

					foreach ( $address['post_office_box_address'] as $post_office_box_address_index => $post_office_box_address ) {

						$post_office_box_address_object = new PTV_Post_Office_Box_In();
						$post_office_box_address_object->set_postal_code( $post_office_box_address['postal_code'] );

						$localized_streets                = array();
						$localized_additional_information = array();

						foreach ( $post_translations as $lang => $id ) {

							if ( isset( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['additional_information'] ) && ! empty( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['additional_information'] ) ) {
								$localized_additional_information[] = $this->prepare_language_item( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['additional_information'], $lang );
							}

							if ( isset( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['post_office_box'] ) && ! empty( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['post_office_box'] ) ) {
								$localized_streets[] = $this->prepare_language_item( $all_addresses[ $lang ][ $address_index ]['post_office_box_address'][ $post_office_box_address_index ]['post_office_box'], $lang );
							}
						}

						if ( ! empty( $localized_streets ) ) {
							$post_office_box_address_object->set_post_office_box( $localized_streets );
						}

						if ( ! empty( $localized_additional_information ) ) {
							$post_office_box_address_object->set_additional_information( $localized_additional_information );
						}


						if ( ! empty( $post_office_box_address_object ) ) {
							$address_object->set_post_office_box_address( $post_office_box_address_object );
						}

						break;

					}

					break;

				case 'Abroad':

					if ( ! empty( $address['location_abroad'] ) ) {
						$address_object->set_location_abroad( array( $this->prepare_language_item( $address['location_abroad'], $lang ) ) );
					}

					break;

			}

			$merged_result[] = $address_object;

		}

		if ( empty( $merged_result ) ) {
			return null;
		}

		return $merged_result;

	}

	/**
	 * Get emails.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_emails( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$email_objects = array();

		foreach ( $post_translations as $lang => $id ) {

			$values = carbon_get_post_meta( $id, 'ptv_emails' );

			foreach ( $values as $value ) {

				if ( $value ) {
					$email_objects[] = $this->prepare_language_item( $value['_value'], $lang );
				}
			}
		}

		if ( empty( $email_objects ) ) {
			return null;
		}

		return $email_objects;

	}

	/**
	 * Sync addresses.
	 *
	 * @param $post_id
	 * @param $updated_channel
	 *
	 * @return bool
	 */
	public function sync_addresses( $post_id, $updated_channel ) {


		if ( ! $post_id || ! $updated_channel ) {
			return false;
		}

		$post_translations = $this->get_post_translations( $post_id );
		$updated_addresses = $updated_channel->get_addresses();

		if ( empty( $updated_addresses ) || ! is_array( $updated_addresses ) ) {
			return false;
		}

		foreach ( $post_translations as $lang => $id ) {

			$addresses = $this->get_serializer()->serialize_addresses( $updated_addresses, $lang );

			if ( ! empty( $addresses ) ) {
				$this->update_post_meta( $id, $addresses );
			}
		}

		return true;

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

		// Sync addresses.
		$sync_addresses = $this->sync_addresses( $post_id, $object );

		if ( ! $sync_addresses ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of addresses to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync service hours.
		$sync_service_hours = $this->sync_service_hours( $post_id, $object );

		if ( ! $sync_service_hours ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of service hours to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync languages.
		$sync_languages = $this->sync_languages( $post_id, $object );

		if ( ! $sync_languages ) {
			$this->errors->add( 'ptv-languages-sync-error', __( 'Item was saved to the PTV, but synchronization of languages to translations failed.', 'ptv-for-wordpress' ) );
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

}

