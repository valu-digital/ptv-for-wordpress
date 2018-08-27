<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-channel-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
class PTV_EChannel_In_Controller extends PTV_Service_Channel_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-echannel';

	/**
	 * Create a new EChannel.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_EChannel_Channel_In();

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
		$new_channel = $this->api->get_service_channel_api()->create_echannel( $request );

		if ( is_wp_error( $new_channel ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_channel->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_channel );

	}

	/**
	 * Update the EChannel.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_EChannel_Channel_In_Base();

		$id = $this->get_translation_group_id( $post_id );

		// Set ID.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Create a new channel.
		$updated_channel = $this->api->get_service_channel_api()->update_echannel_by_id( $id, $request );

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
	 * @param $request PTV_EChannel_Channel_In_Base|PTV_EChannel_Channel_In
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

		// Set organization id.
		$request->set_organization_id( $this->get_organization_id( $post_id ) );

		// Set area type.
		$request->set_area_type( $this->get_area_type( $post_id ) );

		// Set areas.
		$request->set_areas( $this->get_areas( $post_id ) );

		// Set urls.
		$request->set_urls( $this->get_urls( $post_id ) );

		// Set requires signature.
		$request->set_requires_signature( $this->get_requires_signature( $post_id ) );

		// Set signature quantity.
		$request->set_signature_quantity( $this->get_signature_quantity( $post_id ) );

		// Set requires authentication.
		$request->set_requires_authentication( $this->get_requires_authentication( $post_id ) );

		// Set service hours.
		$request->set_service_hours( $this->get_service_hours( $post_id ) );

		// Set attachments.
		$request->set_attachments( $this->get_attachments( $post_id ) );

		// Set support phones.
		$request->set_support_phones( $this->get_support_phones( $post_id ) );

		// Set support emails.
		$request->set_support_emails( $this->get_support_emails( $post_id ) );

		return $request;

	}

	/**
	 * Get requires signature.
	 *
	 * @param $post_id
	 *
	 * @return bool|null
	 */
	public function get_requires_signature( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (bool) carbon_get_post_meta( $post_id, 'ptv_requires_signature' );

	}

	/**
	 * Get requires authentication.
	 *
	 * @param $post_id
	 *
	 * @return bool|null
	 */
	public function get_requires_authentication( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (bool) carbon_get_post_meta( $post_id, 'ptv_requires_authentication' );

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
					$attachment_object = new PTV_Attachment_With_Type();
					$attachment_object->set_language( sanitize_text_field( $lang ) );
					$attachment_object->set_type( sanitize_text_field( $attachment['type'] ) );
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
	 * Get signature quantity.
	 *
	 * @param $post_id
	 *
	 * @return int|null
	 */
	public function get_signature_quantity( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$signature_quantity = carbon_get_post_meta( $post_id, 'ptv_signature_quantity' );

		if ( empty( $signature_quantity ) ) {
			return 1;
		}

		return (int) $signature_quantity;
	}

	/**
	 * Sync authentication and signatures.
	 *
	 * @param $post_id
	 * @param $updated_channel
	 *
	 * @return bool
	 */
	public function sync_authentication_and_signatures( $post_id, $updated_channel ) {

		if ( ! $post_id || ! $updated_channel ) {
			return false;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			carbon_set_post_meta( $id, 'ptv_requires_authentication', intval( $updated_channel->get_requires_authentication() ) );
			carbon_set_post_meta( $id, 'ptv_requires_signature', intval( $updated_channel->get_requires_signature() ) );

			if ( ! empty( $updated_channel->get_signature_quantity() ) ) {
				carbon_set_post_meta( $id, 'ptv_signature_quantity', $updated_channel->get_signature_quantity() );
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

		// Sync authentication and signatures.
		$sync_authentication_and_signatures = $this->sync_authentication_and_signatures( $post_id, $object );

		if ( ! $sync_authentication_and_signatures ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of authentication and signature fields to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync service hours.
		$sync_service_hours = $this->sync_service_hours( $post_id, $object );

		if ( ! $sync_service_hours ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of service hours to translations failed.', 'ptv-for-wordpress' ) );
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