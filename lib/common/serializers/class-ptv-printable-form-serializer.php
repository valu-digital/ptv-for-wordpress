<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );

/**
 * Class PTV_Printable_Form_Serializer
 */
class PTV_Printable_Form_Serializer extends PTV_Serializer {

	/**
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_form_identifier( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_form_identifier' => $this->serialize_localized_list_item_value( $data, $lang = 'fi' ) );

	}

	/**
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_form_receiver( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_form_receiver' => $this->serialize_localized_list_item_value( $data, $lang = 'fi' ) );

	}

	/**
	 * @param $data array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_channel_urls( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			if ( $lang === $item->get_language() ) {

				$single_channel_url           = [];
				$single_channel_url['_value'] = sanitize_text_field( $item->get_value() );
				$single_channel_url['type']   = sanitize_text_field( $item->get_type() );

				$values[] = $single_channel_url;

			}

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_channel_urls' => $values );

	}

	/**
	 * @param $data array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_delivery_address( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$address = [];

		$address['sub_type'] = $data->get_sub_type();

		if ( $data->get_street_address() ) {

			$street_address = array( $this->serialize_street_address( $data->get_street_address(), $lang ) );

			if ( $street_address ) {
				$address['street_address'] = $street_address;
			}
		}

		if ( $data->get_post_office_box_address() ) {
			$post_office_box_address = array( $this->serialize_post_office_box_address( $data->get_post_office_box_address(), $lang ) );

			if ( $post_office_box_address ) {
				$address['post_office_box_address'] = $post_office_box_address;
			}
		}

		if ( $data->get_delivery_address_in_text() ) {
			$address['delivery_address_in_text'] = $this->serialize_localized_list_item_value( $data->get_delivery_address_in_text(), $lang );
		}

		return array( '_ptv_delivery_address' => array( $address ) );

	}
}