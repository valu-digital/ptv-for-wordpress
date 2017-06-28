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
		$request->set_areas( $this->get_areas( $post_id ) );

		// Set addresses.
		$request->set_addresses( $this->get_addresses( $post_id ) );

		// Set languages.
		$request->set_languages( $this->get_languages( $post_id ) );

		// Set service hours.
		$request->set_service_hours( $this->get_service_hours( $post_id ) );

		// Set phone numbers.
		$request->set_phone_numbers( $this->get_phone_numbers( $post_id ) );

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

		$address_objects   = array();
		$merged_result     = array();
		$post_translations = $this->get_post_translations( $post_id );

		$addresses = carbon_get_post_meta( $post_id, 'ptv_addresses' );

		// Fallback to the default language.
		if ( ! $addresses ) {
			$addresses = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_addresses' );
		}

		if ( ! $addresses || ! is_array( $addresses ) ) {
			return null;
		}

		foreach ( $addresses as $address ) {
			if ( isset( $address['postal_code'] ) ) {
				$address_objects[] = new PTV_Address_With_Type_In( $address );
			}
		}

		foreach ( $address_objects as $index => $address_object ) {

			$street_address          = array();
			$additional_informations = array();

			foreach ( $post_translations as $lang => $id ) {

				$localized_addresses = carbon_get_post_meta( $id, 'ptv_addresses' );

				if ( ! $localized_addresses ) {
					$localized_addresses = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_addresses' );
				}

				if ( ! $localized_addresses || ! is_array( $localized_addresses ) ) {
					return null;
				}

				if ( isset( $localized_addresses[ $index ]['additional_informations'] ) && ! empty( $localized_addresses[ $index ]['additional_informations'] ) ) {
					$additional_informations[] = $this->prepare_language_item( $localized_addresses[ $index ]['additional_informations'], $lang );
				}

				if ( isset( $localized_addresses[ $index ]['street_address'] ) && ! empty( $localized_addresses[ $index ]['street_address'] ) ) {
					$street_address[] = $this->prepare_language_item( $localized_addresses[ $index ]['street_address'], $lang );
				}
			}

			if ( ! empty( $street_address ) ) {
				$address_object->set_street_address( $street_address );
			}

			if ( ! empty( $additional_informations ) ) {
				$address_object->set_additional_informations( $additional_informations );
			}

			$merged_result[] = $address_object;

		}

		if ( empty( $merged_result ) ) {
			return null;
		}

		return $merged_result;

	}

	/**
	 * Get web pages.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_web_pages( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$web_pages = carbon_get_post_meta( $post_id, 'ptv_web_pages' );

		if ( ! $web_pages || ! is_array( $web_pages ) ) {
			return null;
		}

		$lang = $this->get_post_language( $post_id );

		$i = 0;

		foreach ( $web_pages as $web_page ) {

			if ( isset( $web_page['url'] ) ) {

				$web_page = new PTV_Web_Page_With_Order_Number( $web_page );
				$web_page->set_language( $lang );
				$web_page->set_order_number( $i );

				if ( isset( $web_page['_value'] ) ) {
					$web_page->set_value( $web_page['_value'] );
				}

				$result[] = $web_page;

				$i ++;
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

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

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_emails' );

			if ( $value ) {
				$result[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

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

			$addresses = array();

			foreach ( $updated_addresses as $updated_address ) {

				if ( ! empty( $updated_address ) ) {

					$address = array();

					$address['type']                    = $updated_address->get_type();
					$address['postal_code']             = $updated_address->get_postal_code();
					$address['street_address']          = ptv_get_localized_value( $updated_address->get_street_address(), $lang );
					$address['street_number']           = $updated_address->get_street_number();
					$address['additional_informations'] = ptv_get_localized_value( $updated_address->get_additional_informations(), $lang );

					$addresses[] = $address;

				}
			}

			if ( ! empty( $addresses ) ) {
				carbon_set_post_meta( $id, 'ptv_addresses', $addresses );
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

	}

}

