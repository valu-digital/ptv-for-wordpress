<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-channel-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
class PTV_Phone_Channel_In_Controller extends PTV_Service_Channel_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-phone';

	/**
	 * Create a new phone channel.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_Phone_Channel_In();

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
		$new_channel = $this->api->get_service_channel_api()->create_phone( $request );

		if ( is_wp_error( $new_channel ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_channel->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_channel );

	}

	/**
	 * Update a phone channel.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_Phone_Channel_In_Base();

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
		$updated_channel = $this->api->get_service_channel_api()->update_phone_by_id( $id, $request );

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
	 * @param $request PTV_Phone_Channel_In|PTV_Phone_Channel_In_Base
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

		// Set service hours.
		$request->set_service_hours( $this->get_service_hours( $post_id ) );

		// Set languages.
		$request->set_languages( $this->get_languages( $post_id ) );

		// Set support emails.
		$request->set_support_emails( $this->get_support_emails( $post_id ) );

		// Set urls.
		$request->set_urls( $this->get_urls( $post_id ) );

		// Set phone numbers.
		$request->set_phone_numbers( $this->get_phone_numbers( $post_id ) );

		// Set organization id.
		$request->set_organization_id( $this->get_organization_id( $post_id ) );

		return $request;

	}

	/**
	 * Get phone numbers.
	 *
	 * @param $post_id
	 *
	 * @return null
	 */
	public function get_phone_numbers( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$phones = carbon_get_post_meta( $id, 'ptv_phone_numbers' );

			if ( ! $phones || ! is_array( $phones ) ) {
				return null;
			}

			foreach ( $phones as $phone ) {

				if ( isset( $phone['number'] ) ) {

					$phone = new PTV_Phone_Channel_Phone( $phone );
					$phone->set_language( $lang );

					if ( true === $phone['is_finnish_service_number'] ) {
						$phone->set_prefix_number( null );
					}

					$result[] = $phone;
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

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
