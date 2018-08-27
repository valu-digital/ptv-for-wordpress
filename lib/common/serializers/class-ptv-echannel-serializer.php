<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );

/**
 * Class PTV_Echannel_Serializer
 */
class PTV_EChannel_Serializer extends PTV_Serializer {

	/**
	 * Serialize signature quantity field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_signature_quantity( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_signature_quantity' => intval( $data ) );

	}

	/**
	 * Serialize requires signature field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_requires_signature( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_requires_signature' => intval( $data ) );

	}

	/**
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_requires_authentication( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_requires_authentication' => intval( $data ) );

	}

}