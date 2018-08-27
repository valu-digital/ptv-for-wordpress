<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV Common module
 */
class PTV_Common_Module {

	/**
	 * Class instance.
	 *
	 * @var PTV_Common_Module
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_Common_Module
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_Common_Module constructor.
	 */
	private function __construct() {
		$this->includes();
	}

	/**
	 * Load module specific files.
	 */
	public function includes() {
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-post-type-helper.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-taxonomy-controller.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-taxonomy-rest-endpoints.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-service-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-organization-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-echannel-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-phone-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-service-location-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-printable-form-serializer.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-web-page-serializer.php' );
	}

}

PTV_Common_Module::get_instance();
