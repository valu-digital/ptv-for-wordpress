<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Background_Process
 */
class PTV_Background_Process extends WP_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'ptv_background_process';

	/**
	 * Task to perform for every item in the queue.
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 */
	protected function task( $item ) {

		if ( ! isset( $item['id'] ) || ! isset( $item['type'] ) ) {
			return false;
		}

		$post_type_helper = new PTV_Post_Type_Helper();
		$api              = new PTV_Api();
		$settings         = ptv_get_settings();
		$translations     = array();
		$serializer       = '';

		switch ( $item['type'] ) {
			case 'Service':
				$item_data  = $api->get_service_api()->get_service_by_id( $item['id'] );
				$serializer = new PTV_Service_Serializer();
				break;
			case 'Organization':
				$item_data  = $api->get_organization_api()->get_organization_by_id( $item['id'] );
				$serializer = new PTV_Organization_Serializer();
				break;
			case 'EChannel':
				$item_data  = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				$serializer = new PTV_EChannel_Serializer();
				break;
			case 'Phone':
				$item_data  = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				$serializer = new PTV_Phone_Serializer();
				break;
			case 'ServiceLocation':
				$item_data  = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				$serializer = new PTV_Service_Location_Serializer();
				break;
			case 'PrintableForm':
				$item_data  = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				$serializer = new PTV_Printable_Form_Serializer();
				break;
			case 'WebPage':
				$item_data  = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				$serializer = new PTV_Web_Page_Serializer();
				break;
			default:
				$item_data = new WP_Error( 'invalid-item-type', __( 'Item type is invalid' ) );
		}

		// Set serializer based on item type.
		$post_type_helper->set_serializer( $serializer );

		if ( is_wp_error( $item_data ) ) {
			return false;
		}

		foreach ( array( 'fi', 'sv', 'en' ) as $lang ) {

			$prepared_post = $post_type_helper->serialize( $item_data, $lang );

			$post_title = ( isset( $prepared_post['_ptv_name'] ) ) ? $prepared_post['_ptv_name'] : '';

			// Post type.
			$post_type                           = ptv_to_post_type_name( get_class( $item_data ) );
			$prepared_post['post']['post_type']  = $post_type;
			$prepared_post['post']['post_title'] = $post_title;

			// Post status.
			$post_status                          = ( isset( $prepared_post['_ptv_publishing_status'] ) and 'Published' === $prepared_post['_ptv_publishing_status'] ) ? 'publish' : 'draft';
			$prepared_post['post']['post_status'] = $post_status;
			$prepared_post['lang']                = $lang;

			// Check responsible organization.
			if ( 'ptv-service' === $post_type ) {

				$responsible_organizations = array_filter( $prepared_post['_ptv_organizations'], function ( $organization ) use ( $settings ) {

					if (
						isset( $organization['role_type'] )
						&& 'Responsible' === $organization['role_type']
						&& isset( $organization['organization_id'] )
						&& $settings['organization_id'] === $organization['organization_id']
					) {
						return true;
					}

					return false;

				} );

				if ( ! $responsible_organizations ) {
					break;
				}
			}

			if ( isset( $post_title ) and ! empty( $post_title ) ) {

				error_log( $post_title );

				$local_id = $post_type_helper->update( $prepared_post );

				if ( $local_id ) {
					$translations[ $lang ] = $local_id;
				}
			}
		}

		// Save translation relations
		$post_type_helper->save_post_translations( $translations );

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		error_log( 'PTV SERVICE IMPORT COMPLETE' );
		parent::complete();
	}

}