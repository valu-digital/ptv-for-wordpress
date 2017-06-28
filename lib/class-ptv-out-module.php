<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV In module
 */
class PTV_Out_Module {

	/**
	 * Class instance.
	 *
	 * @var PTV_Out_Module
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_Out_Module
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_Out_Module constructor.
	 */
	private function __construct() {

		$settings = ptv_get_settings();

		if ( ! isset( $settings['organization_id'] ) ) {
			return false;
		}

		$this->includes();
	}

	/**
	 * Load module specific files.
	 */
	public function includes() {
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-service-out-controller.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-service-channel-out-controller.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-organization-out-controller.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-rest-endpoints.php' );
	}

}

PTV_Out_Module::get_instance();
