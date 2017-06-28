<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV In module
 */
class PTV_REST_Endpoints {

	/**
	 * @var PTV_Organization_Out_Controller
	 */
	protected $organization_controller;

	/**
	 * @var PTV_Service_Out_Controller
	 */
	protected $service_controller;

	/**
	 * @var PTV_Service_Channel_Out_Controller
	 */
	protected $service_channel_controller;

	/**
	 * Class instance.
	 *
	 * @var PTV_REST_Endpoints
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_REST_Endpoints
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_REST_Endpoints constructor.
	 */
	private function __construct() {

		$this->service_controller         = new PTV_Service_Out_Controller();
		$this->service_channel_controller = new PTV_Service_Channel_Out_Controller();
		$this->organization_controller    = new PTV_Organization_Out_Controller();

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}

	/**
	 * Register routes.
	 */
	public function register_routes() {

		register_rest_route( 'ptv/v1', 'services', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'get_services' ),
			'permission_callback' => array( $this, 'permission_callback' ),
		) );

		register_rest_route( 'ptv/v1', 'service-channels', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'get_service_channels' ),
			'permission_callback' => array( $this, 'permission_callback' ),

		) );

		register_rest_route( 'ptv/v1', 'organizations', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'get_organizations' ),
			'permission_callback' => array( $this, 'permission_callback' ),
		) );

		register_rest_route( 'ptv/v1', 'all', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'get_all' ),
			'permission_callback' => array( $this, 'permission_callback' ),
		) );

	}

	/**
	 * Get services.
	 */
	public function get_services( WP_REST_Request $request ) {

		$this->service_controller->fetch();

		return new WP_REST_Response( array( 'success' => true ), 200 );

	}

	/**
	 * Get service channels.
	 */
	public function get_service_channels( WP_REST_Request $request ) {

		$this->service_channel_controller->fetch();

		return new WP_REST_Response( array( 'success' => true ), 200 );


	}

	/**
	 * Get organizations.
	 */
	public function get_organizations( WP_REST_Request $request ) {

		$this->organization_controller->fetch();

		return new WP_REST_Response( array( 'success' => true ), 200 );

	}

	/**
	 * Get all item types at once.
	 */
	public function get_all( WP_REST_Request $request ) {

		$args = array(
			'timeout'  => 0.01,
			'blocking' => false,
			'body'     => array(
				'token' => PTV_FOR_WORDPRESS_REST_TOKEN,
			),
		);

		wp_remote_post( rest_url( '/ptv/v1/services' ), $args );
		wp_remote_post( rest_url( '/ptv/v1/organizations' ), $args );
		wp_remote_post( rest_url( '/ptv/v1/service-channels' ), $args );

		return new WP_REST_Response( array( 'success' => true ), 200 );

	}

	/**
	 * Permission check.
	 */
	public function permission_callback( WP_REST_Request $request ) {

		$token = $request->get_param( 'token' );

		if ( ! isset( $token ) ) {
			return false;
		}

		if ( ! defined( 'PTV_FOR_WORDPRESS_REST_TOKEN' ) || empty( PTV_FOR_WORDPRESS_REST_TOKEN ) ) {
			return false;
		}

		if ( PTV_FOR_WORDPRESS_REST_TOKEN === $token ) {
			return true;
		}

		return false;
	}

}

PTV_REST_Endpoints::get_instance();
