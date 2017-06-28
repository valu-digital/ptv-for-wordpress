<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-out-controller.php' );

/**
 * Class PTV_Service_Out_Controller
 */
class PTV_Service_Out_Controller extends PTV_Out_Controller {

	/**
	 * Get services of the organization defined in the plugin settings.
	 *
	 * @return array|WP_Error
	 */
	function get_items() {

		// Get organization.
		$organization = $this->api->get_organization_api()->get_organization_by_id( $this->settings['organization_id'] );

		if ( is_wp_error( $organization ) ) {
			return $organization;
		}

		$services = array();

		// Process organization services
		foreach ( $organization->get_services() as $service_item ) {

			$service = $service_item->get_service();

			$services[ $service->get_id() ] = array(
				'type' => 'Service',
				'id'   => $service->get_id(),
			);
		}

		return $services;

	}

}
