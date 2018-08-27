<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );

/**
 * Class PTV_Phone_Serializer
 */
class PTV_Phone_Serializer extends PTV_Serializer {

	/**
	 * Serialize web pages field.
	 *
	 * @param $web_pages array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_web_pages( $web_pages, $lang = 'fi' ) {

		if ( ! $web_pages || ! is_array( $web_pages ) ) {
			return false;
		}

		$value = '';

		foreach ( $web_pages as $web_page ) {

			if ( $lang === $web_page->get_language() ) {
				$single_phone = $this->serialize_single_web_page( $web_page, $lang );

				if ( isset( $single_phone['url'] ) ) {
					$value = $single_phone['url'];
					break;
				}
			}
		}

		if ( ! $value ) {
			return false;
		}

		return array( '_ptv_urls' => $value );

	}


}