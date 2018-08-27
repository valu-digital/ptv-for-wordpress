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

		// Set delete all channel urls.
		if ( ! $request->get_channel_urls() && method_exists( $request, 'set_delete_all_channel_urls' ) ) {
			$request->set_delete_all_channel_urls( true );
		}

		// Set form identifier.
		$request->set_form_identifier( $this->get_form_identifier( $post_id ) );

		// Set delete all form identifiers.
		if ( ! $request->get_form_identifier() && method_exists( $request, 'set_delete_all_form_identifiers' ) ) {
			$request->set_delete_all_form_identifiers( true );
		}

		// Set form receiver.
		$request->set_form_receiver( $this->get_form_receiver( $post_id ) );

		// Set delete all form receivers.
		if ( ! $request->get_form_receiver() && method_exists( $request, '$this->set_delete_all_form_receivers()' ) ) {
			$request->set_delete_all_form_receivers( true );
		}

		// Set organization id.
		$request->set_organization_id( $this->get_organization_id( $post_id ) );

		// Set support phones.
		$request->set_support_phones( $this->get_support_phones( $post_id ) );

		// Set delete all support phones.
		if ( ! $request->get_support_phones() && method_exists( $request, 'set_delete_all_support_phones' ) ) {
			$request->set_delete_all_support_phones( true );
		}

		// Set support emails.
		$request->set_support_emails( $this->get_support_emails( $post_id ) );

		// Set delete all support emails.
		if ( ! $request->get_support_emails() && method_exists( $request, 'set_delete_all_support_emails' ) ) {
			$request->set_delete_all_support_emails( true );
		}

		// Set delivery address.
		$request->set_delivery_address( $this->get_delivery_address( $post_id ) );

		// Set delete delivery address.
		if ( ! $request->get_delivery_address() && method_exists( $request, 'set_delete_delivery_address' ) ) {
			$request->set_delete_delivery_address( true );
		}

		// Set attachments.
		$request->set_attachments( $this->get_attachments( $post_id ) );

		// Set delete all attachments.
		if ( ! $request->get_attachments() && method_exists( $request, 'set_delete_all_attachments' ) ) {
			$request->set_delete_all_attachments( true );
		}

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

		$channel_url_objects = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$channel_urls = carbon_get_post_meta( $id, 'ptv_channel_urls' );

			if ( ! $channel_urls or ! is_array( $channel_urls ) ) {
				continue;
			}

			$data = array();

			foreach ( $channel_urls as $channel_url ) {

				if ( isset( $channel_url['type'] ) && isset( $channel_url['_value'] ) ) {
					$data['type']          = $channel_url['type'];
					$data['value']         = $channel_url['_value'];
					$channel_url_objects[] = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $channel_url_objects ) ) {
			return null;
		}

		return $channel_url_objects;

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

		$attachment_objects = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$attachments = carbon_get_post_meta( $id, 'ptv_attachments' );

			if ( ! $attachments || ! is_array( $attachments ) ) {
				continue;
			}

			foreach ( $attachments as $attachment ) {

				if ( isset( $attachment['url'] ) ) {

					$attachment_object = new PTV_Attachment();
					$attachment_object->set_language( sanitize_text_field( $lang ) );
					$attachment_object->set_name( sanitize_text_field( $attachment['name'] ) );
					$attachment_object->set_description( sanitize_text_field( $attachment['description'] ) );
					$attachment_object->set_url( esc_url( $attachment['url'] ) );

					$attachment_objects[] = $attachment_object;
				}
			}
		}

		if ( empty( $attachment_objects ) ) {
			return null;
		}

		return $attachment_objects;

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

		$merged_result     = array();
		$post_language     = $this->get_post_language( $post_id );
		$post_translations = $this->get_post_translations( $post_id );

		$all_addresses = array();

		foreach ( $post_translations as $lang => $id ) {

			$localized_addresses = carbon_get_post_meta( $id, 'ptv_delivery_address' );

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

			$address_object = new PTV_Address_Delivery_In();

			$address_object->set_sub_type( $address['sub_type'] );

			switch ( $address['sub_type'] ) {

				case 'Single':
				case 'Street':

					foreach ( $address['street_address'] as $street_address_index => $street_address ) {

						$street_address_object = new PTV_Street_Address_In();
						$street_address_object->set_street_number( $street_address['street_number'] );
						$street_address_object->set_postal_code( $street_address['postal_code'] );

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

				case 'NoAddress':

					if ( ! empty( $address['delivery_address_in_text'] ) ) {

						$localized_delivery_address_in_text = [];

						foreach ( $post_translations as $lang => $id ) {

							if ( isset( $all_addresses[ $lang ][ $address_index ]['delivery_address_in_text'] ) && ! empty( $all_addresses[ $lang ][ $address_index ]['delivery_address_in_text'] ) ) {
								$localized_delivery_address_in_text[] = $this->prepare_language_item( $all_addresses[ $lang ][ $address_index ]['delivery_address_in_text'], $lang );
							}
						}

						$address_object->set_delivery_address_in_text( $localized_delivery_address_in_text );
					}

					break;
			}


			$merged_result[] = $address_object;

		}

		$result = $merged_result[0];

		if ( empty( $result ) ) {
			return null;
		}

		return $result;
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

		$form_identifier = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_form_identifier' );

			if ( $value ) {
				$form_identifier[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $form_identifier ) ) {
			return null;
		}

		return $form_identifier;

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

		$form_receiver = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_form_receiver' );

			if ( $value ) {
				$form_receiver[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $form_receiver ) ) {
			return null;
		}

		return $form_receiver;

	}

	/**
	 * Sync delivery address.
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

		$post_translations        = $this->get_post_translations( $post_id );
		$updated_delivery_address = $updated_channel->get_delivery_address();

		foreach ( $post_translations as $lang => $id ) {

			$delivery_address = $this->get_serializer()->serialize_delivery_address( $updated_delivery_address, $lang );

			if ( ! empty( $delivery_address ) ) {
				$this->update_post_meta( $id, $delivery_address );
			} else {
				$this->update_post_meta( $id, array( '_ptv_delivery_address' => '' ) );
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

		// Sync modified time.
		$sync_modified = $this->sync_modified( $post_id, $object );

		if ( ! $sync_modified ) {
			$this->errors->add( 'ptv-modified-time-sync-error', __( 'Item was saved to the PTV, but synchronization of modified time to translations failed.', 'ptv-for-wordpress' ) );
		}

	}

}