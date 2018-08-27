<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );


/**
 * Class PTV_Service_Location_Serializer
 */
class PTV_Service_Location_Serializer extends PTV_Serializer {

	/**
	 * @param $data array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_emails( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			if ( $lang === $item->get_language() ) {

				$single_email           = [];
				$single_email['_value'] = sanitize_text_field( $item->get_value() );

				$values[] = $single_email;

			}

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_emails' => $values );

	}

}