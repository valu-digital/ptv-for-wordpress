<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );

/**
 * Class PTV_Organization_Serializer
 */
class PTV_Organization_Serializer extends PTV_Serializer {

	/**
	 * Serialize organization id field.
	 *
	 * @param $oid
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_oid( $oid, $lang = 'fi' ) {

		if ( ! $oid ) {
			return false;
		}

		return array( '_ptv_oid' => $this->serialize_text_field( $oid, $lang = 'fi' ) );

	}

	/**
	 * Serialize display name field.
	 *
	 * @param $display_name_types
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_display_name_type( $display_name_types, $lang = 'fi' ) {

		if ( ! $display_name_types || ! is_array( $display_name_types ) ) {
			return false;
		}

		$value = '';

		foreach ( $display_name_types as $display_name_type ) {

			if ( $lang === $display_name_type->get_language() ) {
				$value = sanitize_text_field( $display_name_type->get_type() );
				break;
			}
		}

		if ( ! $value ) {
			return false;
		}

		return array( '_ptv_display_name_type' => $value );

	}

	/**
	 * Serialize organization type field.
	 *
	 * @param $organization_type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_organization_type( $organization_type, $lang = 'fi' ) {

		if ( ! $organization_type ) {
			return false;
		}

		return array( '_ptv_organization_type' => $this->serialize_text_field( $organization_type, $lang = 'fi' ) );

	}

	/**
	 * Serialize business code field.
	 *
	 * @param $business_code
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_business_code( $business_code, $lang = 'fi' ) {

		if ( ! $business_code ) {
			return false;
		}

		return array( '_ptv_business_code' => $this->serialize_text_field( $business_code, $lang = 'fi' ) );

	}

	/**
	 * Serialize organization names field.
	 *
	 * @param $organization_names
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_organization_names( $organization_names, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $organization_names, $lang );
	}

	/**
	 * Serialize organization descriptions.
	 *
	 * @param $organization_descriptions
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_organization_descriptions( $organization_descriptions, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $organization_descriptions, $lang );
	}

	/**
	 * Serialize email addresses field.
	 *
	 * @param $email_addresses array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_email_addresses( $email_addresses, $lang = 'fi' ) {

		if ( ! $email_addresses || ! is_array( $email_addresses ) ) {
			return false;
		}

		$values = [];

		foreach ( $email_addresses as $email_address ) {

			if ( $lang === $email_address->get_language() ) {

				$single_email           = [];
				$single_email['_value'] = sanitize_text_field( $email_address->get_value() );

				$values[] = $single_email;

			}

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_email_addresses' => $values );

	}

	/**
	 * Serialize electronic invoicings field.
	 *
	 * @param $electronic_invoicings array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_electronic_invoicings( $electronic_invoicings, $lang = 'fi' ) {

		if ( ! $electronic_invoicings || ! is_array( $electronic_invoicings ) ) {
			return false;
		}

		$values = [];

		foreach ( $electronic_invoicings as $electronic_invoicing ) {

			$single_electronic_invoicing                                 = [];
			$single_electronic_invoicing['operator_code']                = sanitize_text_field( $electronic_invoicing->get_operator_code() );
			$single_electronic_invoicing['electronic_invoicing_address'] = sanitize_text_field( $electronic_invoicing->get_electronic_invoicing_address() );
			$single_electronic_invoicing['additional_information']       = $this->serialize_localized_list_item_value( $electronic_invoicing->get_additional_information(), $lang );

			$values[] = $single_electronic_invoicing;

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_electronic_invoincings' => $values );

	}

}
