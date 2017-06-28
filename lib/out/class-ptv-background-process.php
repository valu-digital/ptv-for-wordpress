<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/helpers/class-ptv-post-type-helper.php' );

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
		$translations     = array();

		switch ( $item['type'] ) {
			case 'Service':
				$item_data = $api->get_service_api()->get_service_by_id( $item['id'] );
				break;
			case 'Organization':
				$item_data = $api->get_organization_api()->get_organization_by_id( $item['id'] );
				break;
			case 'EChannel':
			case 'Phone':
			case 'ServiceLocation':
			case 'PrintableForm':
			case 'WebPage':
				$item_data = $api->get_service_channel_api()->get_service_channel_by_id( $item['id'] );
				break;
			default:
				$item_data = new WP_Error( 'invalid-item-type', __( 'Item type is invalid' ) );
		}

		if ( is_wp_error( $item_data ) ) {
			return false;
		}

		foreach ( array( 'fi', 'sv', 'en' ) as $lang ) {

			$prepared_post = $post_type_helper->prepare( $item_data, $lang );

			// Post type.
			$post_type                          = ptv_to_post_type_name( get_class( $item_data ) );
			$prepared_post['post']['post_type'] = $post_type;

			// Post title
			switch ( $post_type ) {
				case 'ptv-service':
					$post_title = ( isset( $prepared_post['_ptv_service_names_name'] ) ) ? $prepared_post['_ptv_service_names_name'] : '';
					break;
				case 'ptv-organization':
					$post_title = ( isset( $prepared_post['_ptv_organization_names_name'] ) ) ? $prepared_post['_ptv_organization_names_name'] : '';
					break;
				default :
					$post_title = ( isset( $prepared_post['_ptv_service_channel_names_name'] ) ) ? $prepared_post['_ptv_service_channel_names_name'] : '';
			}

			$prepared_post['post']['post_title'] = $post_title;

			// Post status.
			$post_status                          = ( isset( $prepared_post['_ptv_publishing_status'] ) and 'Published' === $prepared_post['_ptv_publishing_status'] ) ? 'publish' : 'draft';
			$prepared_post['post']['post_status'] = $post_status;
			$prepared_post['lang']                = $lang;

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
		parent::complete();
	}

}