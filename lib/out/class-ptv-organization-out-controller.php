<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-out-controller.php' );

/**
 * Class PTV_Organization_Out_Controller
 */
class PTV_Organization_Out_Controller extends PTV_Out_Controller {

	/**
	 * Get organization defined in the settings.
	 *
	 * @return array|WP_Error
	 */
	function get_items() {

		return array( array( 'type' => 'Organization', 'id' => $this->settings['organization_id'] ) );

	}

}