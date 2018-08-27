<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/libraries/class-wp-async-request.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/libraries/class-wp-background-process.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/out/class-ptv-background-process.php' );

/**
 * Class PTV_Out_Controller
 */
abstract class PTV_Out_Controller {

	/**
	 * @var PTV_Api
	 */
	protected $api;

	/**
	 * @var $settings mixed|void
	 */
	protected $settings;

	/**
	 * @var PTV_Background_Process
	 */
	protected $background_process;

	/**
	 * PTV_Out_Controller constructor.
	 */
	function __construct( PTV_Api $api = null ) {

		if ( null === $api ) {
			$api = new PTV_Api();
		}

		$this->api = $api;

		// Define organization ID
		$this->settings = ptv_get_settings();

		$this->background_process = new PTV_Background_Process();

	}

	/**
	 * Fetches the items and sets them to a background processing queue.
	 *
	 * @return bool
	 */
	function fetch() {

		$items = $this->get_items();

		if ( is_wp_error( $items ) ) {
			return false;
		}

		foreach ( $items as $item ) {
			$this->background_process->push_to_queue( $item );
		}

		$this->background_process->save()->dispatch();

	}

	/**
	 * @return array|WP_Error
	 */
	abstract protected function get_items();

}