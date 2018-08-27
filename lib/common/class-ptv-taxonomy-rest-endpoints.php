<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Taxonomy_REST_Endpoints
 */
class PTV_Taxonomy_REST_Endpoints {

	/**
	 * @var PTV_Taxonomy_Controller
	 */
	protected $taxonomy_controller;

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

		$this->taxonomy_controller = new PTV_Taxonomy_Controller();

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}

	/**
	 * Register routes.
	 */
	public function register_routes() {

		register_rest_route( 'ptv/v1', 'taxonomies', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'get_taxonomies' ),
			'permission_callback' => array( $this, 'permission_callback' ),
		) );

	}

	/**
	 * Get taxonomies.
	 */
	public function get_taxonomies( WP_REST_Request $request ) {

		$this->taxonomy_controller->fetch();

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

PTV_Taxonomy_REST_Endpoints::get_instance();
