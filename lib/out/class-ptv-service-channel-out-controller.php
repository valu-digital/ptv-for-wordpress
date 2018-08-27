<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-out-controller.php' );

/**
 * Class PTV_Service_Out_Controller
 */
class PTV_Service_Channel_Out_Controller extends PTV_Out_Controller {

	/**
	 * Get service channels of the organization defined in the plugin settings.
	 *
	 * @return array|WP_Error
	 */
	function get_items() {

		$result = array();

		$types = array(
			'EChannel',
			'Phone',
			'ServiceLocation',
			'PrintableForm',
			'WebPage',
		);

		foreach ( $types as $type ) {

			// Get organization.
			$service_channels = $this->api->get_service_channel_api()->get_service_channels_for_organization_by_type( $this->settings['organization_id'], $type );

			if ( is_wp_error( $service_channels ) ) {
				return $service_channels;
			}

			foreach ( $service_channels->get_item_list() as $service_channel ) {
				$result[ $service_channel->get_id() ] = array(
					'id'   => $service_channel->get_id(),
					'type' => $type,
				);
			}
		}

		return $result;

	}

}