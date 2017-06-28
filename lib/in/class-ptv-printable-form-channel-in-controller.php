<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-channel-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
class PTV_Printable_Form_Channel_In_Controller extends PTV_Service_Channel_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-printable-form';

	/**
	 * Create a new printable form channel.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_Printable_Form_Channel_In();

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
		$new_channel = $this->api->get_service_channel_api()->create_printable_form( $request );

		if ( is_wp_error( $new_channel ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_channel->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_channel );


	}

	/**
	 * Update a printable form channel.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_Printable_Form_Channel_In_Base();

		$id = $this->get_translation_group_id( $post_id );

		// Set ID.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $request->valid() ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		// Create a new channel.
		$updated_channel = $this->api->get_service_channel_api()->update_printable_form_by_id( $id, $request );

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
	 * @param $request PTV_Printable_Form_Channel_In|PTV_Printable_Form_Channel_In_Base
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

		// Set channel urls.
		$request->set_channel_urls( $this->get_channel_urls( $post_id ) );

		// Set form identifier.
		$request->set_form_identifier( $this->get_form_identifier( $post_id ) );

		// Set form receiver.
		$request->set_form_receiver( $this->get_form_receiver( $post_id ) );

		// Set organization id.
		$request->set_organization_id( $this->get_organization_id( $post_id ) );

		// Set support phones.
		$request->set_support_phones( $this->get_support_phones( $post_id ) );

		// Set support emails.
		$request->set_support_emails( $this->get_support_emails( $post_id ) );

		// Delete all previous addresses.
		$request->set_delete_delivery_address( true );

		// Set delivery address.
		$request->set_delivery_address( $this->get_delivery_address( $post_id ) );

		// Set attachments.
		$request->set_attachments( $this->get_attachments( $post_id ) );

		return $request;

	}

	/**
	 * Get channel urls.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_channel_urls( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$channel_urls = carbon_get_post_meta( $id, 'ptv_channel_urls' );

			if ( ! $channel_urls or ! is_array( $channel_urls ) ) {
				continue;
			}

			$data = array();

			foreach ( $channel_urls as $channel_url ) {

				if ( isset( $channel_url['type'] ) && isset( $channel_url['_value'] ) ) {
					$data['type']  = $channel_url['type'];
					$data['value'] = $channel_url['_value'];
					$result[]      = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get attachments.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_attachments( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$attachments = carbon_get_post_meta( $id, 'ptv_attachments' );

			if ( ! $attachments || ! is_array( $attachments ) ) {
				return null;
			}

			foreach ( $attachments as $attachment ) {

				if ( isset( $attachment['url'] ) ) {

					$attachment = new PTV_Attachment( $attachment );
					$attachment->set_language( $lang );
					$result[] = $attachment;
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get delivery address.
	 *
	 * @param $post_id
	 *
	 * @return null
	 */
	public function get_delivery_address( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}


		$result            = array();
		$post_translations = $this->get_post_translations( $post_id );

		$addresses = carbon_get_post_meta( $post_id, 'ptv_delivery_address' );

		if ( ! $addresses ) {
			$addresses = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_delivery_address' );
		}

		if ( ! $addresses || ! is_array( $addresses ) ) {
			return null;
		}

		foreach ( $addresses as $address ) {
			if ( isset( $address['postal_code'] ) ) {
				$result[] = new PTV_Address_In( $address );
			}
		}

		$first_result = $result[0];

		$street_address          = array();
		$additional_informations = array();
		$post_office_box         = array();

		foreach ( $post_translations as $lang => $id ) {

			$localized_addresses = carbon_get_post_meta( $id, 'ptv_delivery_address' );

			if ( ! $localized_addresses ) {
				$localized_addresses = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_delivery_address' );
			}

			if ( ! $localized_addresses || ! is_array( $localized_addresses ) ) {
				return null;
			}

			foreach ( $localized_addresses as $address ) {

				if ( isset( $address['additional_informations'] ) && ! empty( $address['additional_informations'] ) ) {
					$additional_informations[] = $this->prepare_language_item( $address['additional_informations'], $lang );
				}

				if ( isset( $address['street_address'] ) && ! empty( $address['street_address'] ) ) {
					$street_address[] = $this->prepare_language_item( $address['street_address'], $lang );
				}

				if ( isset( $address['post_office_box'] ) && ! empty( $address['post_office_box'] ) ) {
					$post_office_box[] = $this->prepare_language_item( $address['post_office_box'], $lang );
				}
			}
		}

		if ( ! empty( $street_address ) ) {
			$first_result->set_street_address( $street_address );
		}

		if ( ! empty( $additional_informations ) ) {
			$first_result->set_additional_informations( $additional_informations );
		}

		if ( ! empty( $post_office_box ) ) {
			$first_result->set_post_office_box( $post_office_box );
		}

		return $first_result;

	}

	/**
	 * Get form identifier.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_form_identifier( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_form_identifier' );

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
	 * Get form receiver.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_form_receiver( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_form_receiver' );

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
	 * Sync fields that are common for all languages.
	 *
	 * @param $post_id
	 * @param $updated_channel
	 *
	 * @return bool
	 */
	public function sync_delivery_address( $post_id, $updated_channel ) {

		if ( ! $post_id || ! $updated_channel ) {
			return false;
		}

		$post_translations = $this->get_post_translations( $post_id );
		$updated_address   = $updated_channel->get_delivery_address();

		foreach ( $post_translations as $lang => $id ) {

			$address = array();

			if ( ! empty( $updated_address ) ) {

				$address['type']                    = '_';
				$address['postal_code']             = $updated_address->get_postal_code();
				$address['street_address']          = ptv_get_localized_value( $updated_address->get_street_address(), $lang );
				$address['post_office_box']         = ptv_get_localized_value( $updated_address->get_post_office_box(), $lang );
				$address['street_number']           = $updated_address->get_street_number();
				$address['additional_informations'] = ptv_get_localized_value( $updated_address->get_additional_informations(), $lang );

				if ( ! empty( $address ) ) {
					carbon_set_post_meta( $id, 'ptv_delivery_address', array( 0 => $address ) );
				}
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

		// Sync delivery address.
		$sync_delivery_address = $this->sync_delivery_address( $post_id, $object );

		if ( ! $sync_delivery_address ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of delivery address to translations failed.', 'ptv-for-wordpress' ) );
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