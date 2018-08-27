<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Api
 */
class PTV_Api {

	/**
	 * @var $api_user string
	 */
	protected $api_user;

	/**
	 * @var $api_secret string
	 */
	protected $api_secret;

	/**
	 * @var $api_url string
	 */
	protected $api_url;

	/**
	 * @var $api_token_url string
	 */
	protected $api_token_url;

	/**
	 * PTV_Api constructor.
	 */
	function __construct() {

		if ( defined( 'PTV_API_USER' ) ) {
			$this->api_user = PTV_API_USER;
		}

		if ( defined( 'PTV_API_SECRET' ) ) {
			$this->api_secret = PTV_API_SECRET;
		}

		if ( defined( 'PTV_API_URL' ) ) {
			$this->api_url = PTV_API_URL;
		}

		if ( defined( 'PTV_API_TOKEN_URL' ) ) {
			$this->api_token_url = PTV_API_TOKEN_URL;
		}

	}

	/**
	 * Get general description API.
	 *
	 * @return PTV_General_Description_Api
	 */
	function get_general_description_api() {
		return new PTV_General_Description_Api( self::get_client() );
	}

	/**
	 * Get organization API.
	 *
	 * @return PTV_Organization_Api
	 */
	function get_organization_api() {
		return new PTV_Organization_Api( self::get_client() );
	}

	/**
	 * Get service API.
	 *
	 * @return PTV_Service_Api
	 */
	function get_service_api() {
		return new PTV_Service_Api( self::get_client() );
	}

	/**
	 * Get service channel API.
	 *
	 * @return PTV_Service_Channel_Api
	 */
	function get_service_channel_api() {
		return new PTV_Service_Channel_Api( self::get_client() );
	}

	/**
	 * Get connection API.
	 *
	 * @return PTV_Connection_Api()
	 */
	function get_connection_api() {
		return new PTV_Connection_Api( self::get_client() );
	}

	/**
	 * Get access token
	 *
	 * @return bool|mixed
	 */
	function get_access_token() {

		$cached_token = get_transient( '_ptv_access_token' );

		if ( $cached_token ) {
			return $cached_token;
		}

		$organization_id = ptv_get_organization_id();

		if ( ! $organization_id ) {
			return false;
		}

		$args = array( 'headers' => array( 'Content-Type' => 'application/json' ) );

		$args['body'] = json_encode( array(
			'apiUserOrganization' => $organization_id,
			'username'            => $this->api_user,
			'password'            => $this->api_secret,
		) );

		$response = wp_remote_post( $this->api_token_url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! isset( $response_body->ptvToken ) || empty( $response_body->ptvToken ) ) {
			return false;
		}

		$token = $response_body->ptvToken;

		set_transient( '_ptv_access_token', $token, 60 * 60 * 12 );

		return $token;


	}

	/**
	 * Get configuration.
	 *
	 * @return mixed
	 */
	private function get_configuration() {

		$configuration = PTV_Api_Client_Configuration::get_default_configuration();
		$configuration->set_host( $this->api_url );
		$configuration->set_timeout( 60 );

		$token = $this->get_access_token();

		if ( $token ) {
			$configuration->set_access_token( $token );
		}

		return $configuration;
	}

	/**
	 * Get the API Client instance.
	 *
	 * @return PTV_Api_Client
	 */
	private function get_client() {
		return new PTV_Api_Client( self::get_configuration() );
	}

}
