<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Serializer
 */
abstract class PTV_Serializer {

	/**
	 * Serialize ID field.
	 *
	 * @param $id
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_id( $id, $lang = 'fi' ) {

		if ( ! $id ) {
			return false;
		}

		return array( '_ptv_id' => $this->serialize_text_field( $id, $lang = 'fi' ) );

	}

	/**
	 * Serialize organization ID field.
	 *
	 * @param $oid
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_organization_id( $oid, $lang = 'fi' ) {

		if ( ! $oid ) {
			return false;
		}

		return array( '_ptv_organization_id' => $this->serialize_text_field( $oid, $lang = 'fi' ) );

	}

	/**
	 * Serialize service channel type field.
	 *
	 * @param $type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_channel_type( $type, $lang = 'fi' ) {

		if ( ! $type ) {
			return false;
		}

		return array( '_ptv_service_channel_type' => $this->serialize_text_field( $type, $lang = 'fi' ) );

	}

	/**
	 * Serialize type field.
	 *
	 * @param $type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_type( $type, $lang = 'fi' ) {

		if ( ! $type ) {
			return false;
		}

		return array( '_ptv_type' => $this->serialize_text_field( $type, $lang = 'fi' ) );

	}

	/**
	 * Serialize funding type field.
	 *
	 * @param $funding_type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_funding_type( $funding_type, $lang = 'fi' ) {

		if ( ! $funding_type ) {
			return false;
		}

		return array( '_ptv_funding_type' => $this->serialize_text_field( $funding_type, $lang = 'fi' ) );

	}

	/**
	 * Serialize publishing status field.
	 *
	 * @param $publishing_status
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_publishing_status( $publishing_status, $lang = 'fi' ) {

		if ( ! $publishing_status ) {
			return false;
		}

		return array( '_ptv_publishing_status' => $this->serialize_text_field( $publishing_status, $lang = 'fi' ) );

	}

	/**
	 * Serialize languages field.
	 *
	 * @param $languages
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_languages( $languages, $lang = 'fi' ) {

		if ( ! $languages ) {
			return false;
		}

		return array( '_ptv_languages' => (array) $languages );

	}

	/**
	 * Serialize area type field.
	 *
	 * @param $area_type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_area_type( $area_type, $lang = 'fi' ) {

		if ( ! $area_type ) {
			return false;
		}

		return array( '_ptv_area_type' => $this->serialize_text_field( $area_type, $lang = 'fi' ) );

	}

	/**
	 * Serialize service channel names field.
	 *
	 * @param $service_channel_names
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_channel_names( $service_channel_names, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $service_channel_names, $lang );
	}

	/**
	 * Serialize service names.
	 *
	 * @param $service_names
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_names( $service_names, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $service_names, $lang );
	}

	/**
	 * Serialize service channel descriptions field.
	 *
	 * @param $service_channel_descriptions
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_channel_descriptions( $service_channel_descriptions, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $service_channel_descriptions, $lang );
	}

	/**
	 * Serialize service descriptions.
	 *
	 * @param $service_descriptions
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_descriptions( $service_descriptions, $lang = 'fi' ) {
		return $this->serialize_localized_value_with_type( $service_descriptions, $lang );
	}

	/**
	 * Serialize urls field.
	 *
	 * @param $urls
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_urls( $urls, $lang = 'fi' ) {

		if ( ! $urls ) {
			return false;
		}

		return array( '_ptv_urls' => $this->serialize_localized_list_item_value( $urls, $lang = 'fi' ) );

	}

	/**
	 * Serialize service hours field.
	 *
	 * @param $service_hours array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_hours( $service_hours, $lang = 'fi' ) {

		if ( ! $service_hours || ! is_array( $service_hours ) ) {
			return false;
		}

		$values = [];

		foreach ( $service_hours as $service_hour ) {

			$single_service_hour                      = [];
			$single_service_hour['service_hour_type'] = $service_hour->get_service_hour_type();

			if ( $service_hour->get_valid_from() ) {
				$single_service_hour['valid_from'] = $service_hour->get_valid_from()->format( get_option( 'date_format' ) );
			}

			if ( $service_hour->get_valid_to() ) {
				$single_service_hour['valid_to'] = $service_hour->get_valid_to()->format( get_option( 'date_format' ) );
			}

			$single_service_hour['is_closed']              = (int) $service_hour->get_is_closed();
			$single_service_hour['valid_for_now']          = (int) $service_hour->get_valid_for_now();
			$single_service_hour['service_hour_type']      = $service_hour->get_service_hour_type();
			$single_service_hour['additional_information'] = $this->serialize_localized_list_item_value( $service_hour->get_additional_information(), $lang );

			if ( $service_hour->get_opening_hour() ) {
				$single_service_hour['opening_hour'] = $this->serialize_opening_hour( $service_hour->get_opening_hour(), $lang );
			}

			$values[] = $single_service_hour;

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_service_hours' => $values );

	}

	/**
	 * Serialize phone numbers field.
	 *
	 * @param $phone_numbers array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_phone_numbers( $phone_numbers, $lang = 'fi' ) {

		if ( ! $phone_numbers || ! is_array( $phone_numbers ) ) {
			return false;
		}

		$values = [];

		foreach ( $phone_numbers as $phone_number ) {

			if ( $lang === $phone_number->get_language() ) {
				$single_phone = $this->serialize_single_phone_number( $phone_number, $lang );
				$values[]     = $single_phone;
			}
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_phone_numbers' => $values );

	}


	/**
	 * Serialize opening hour field.
	 *
	 * @param $opening_hours array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_opening_hour( $opening_hours, $lang = 'fi' ) {

		if ( ! $opening_hours || ! is_array( $opening_hours ) ) {
			return false;
		}

		$values = [];

		foreach ( $opening_hours as $opening_hour ) {

			$single_opening_hour             = [];
			$single_opening_hour['day_from'] = sanitize_text_field( $opening_hour->get_day_from() );
			$single_opening_hour['day_to']   = sanitize_text_field( $opening_hour->get_day_to() );
			$single_opening_hour['from']     = sanitize_text_field( $opening_hour->get_from() );
			$single_opening_hour['to']       = sanitize_text_field( $opening_hour->get_to() );
			$single_opening_hour['is_extra'] = (int) $opening_hour->get_is_extra();

			$values[] = $single_opening_hour;

		}

		if ( ! $values ) {
			return false;
		}

		// Sort values by weekday and start time
		usort( $values, function ( $a, $b ) {
			return strcmp(
				date( 'N H:i', strtotime( sprintf( '%s %s', $a['day_from'], $a['from'] ) ) ), date( 'N H:i', strtotime( sprintf( '%s %s', $b['day_from'], $b['from'] ) ) )
			);
		} );

		return $values;

	}

	/**
	 * Serialize single phone number.
	 *
	 * @param $phone_number PTV_Phone | PTV_Phone_With_Type
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_single_phone_number( $phone_number, $lang = 'fi' ) {

		if ( ! $phone_number ) {
			return false;
		}

		$phone = [];

		if ( is_a( $phone_number, 'PTV_Phone_With_Type' ) ) {
			$phone['type'] = sanitize_text_field( $phone_number->get_type() );
		}

		$phone['additional_information']    = sanitize_text_field( $phone_number->get_additional_information() );
		$phone['service_charge_type']       = sanitize_text_field( $phone_number->get_service_charge_type() );
		$phone['charge_description']        = sanitize_text_field( $phone_number->get_charge_description() );
		$phone['prefix_number']             = sanitize_text_field( $phone_number->get_prefix_number() );
		$phone['is_finnish_service_number'] = (int) $phone_number->get_is_finnish_service_number();
		$phone['number']                    = sanitize_text_field( $phone_number->get_number() );
		$phone['language']                  = sanitize_text_field( $phone_number->get_language() );

		return $phone;

	}

	/**
	 * Serialize support emails field.
	 *
	 * @param $support_emails array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_support_emails( $support_emails, $lang = 'fi' ) {

		if ( ! $support_emails || ! is_array( $support_emails ) ) {
			return false;
		}

		$values = [];

		foreach ( $support_emails as $support_email ) {

			if ( $lang === $support_email->get_language() ) {

				$single_email           = [];
				$single_email['_value'] = sanitize_text_field( $support_email->get_value() );

				$values[] = $single_email;

			}
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_support_emails' => $values );


	}

	/**
	 * Serialize addresses field.
	 *
	 * @param $addresses array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_addresses( $addresses, $lang = 'fi' ) {

		if ( ! $addresses || ! is_array( $addresses ) ) {
			return false;
		}

		$values = [];

		foreach ( $addresses as $address ) {

			$single_address = $this->serialize_single_address( $address, $lang );

			$values[] = $single_address;
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_addresses' => $values );

	}

	/**
	 * Serialize single address.
	 *
	 * @param $data PTV_Address|PTV_Address_With_Moving|PTV_Address_Delivery
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_single_address( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$address = [];

		// Delivery address has no type.
		if ( is_a( $data, 'PTV_Address_With_Moving' ) ) {
			$address['type'] = $data->get_type();
		}

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

		// Delivery address can not have location abroad.
		if ( is_a( $data, 'PTV_Address_With_Moving' ) ) {
			$address['location_abroad'] = $this->serialize_localized_list_item_value( $data->get_location_abroad(), $lang );
		}

		return $address;

	}

	/**
	 * Serialize street address field.
	 *
	 * @param PTV_Street_Address_With_Coordinates|PTV_Street_Address $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_street_address( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$street_address = [];

		$street_address['street']                 = $this->serialize_localized_list_item_value( $data->get_street(), $lang );
		$street_address['street_number']          = sanitize_text_field( $data->get_street_number() );
		$street_address['postal_code']            = sanitize_text_field( $data->get_postal_code() );
		$street_address['post_office']            = $this->serialize_localized_list_item_value( $data->get_post_office(), $lang );
		$street_address['additional_information'] = $this->serialize_localized_list_item_value( $data->get_additional_information(), $lang );

		$municipality = $data->get_municipality();

		if ( $municipality ) {
			$street_address['municipality'] = sanitize_text_field( $municipality->get_code() );
		}

		return $street_address;

	}

	/**
	 * Serialize post office box address field.
	 *
	 * @param  $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_post_office_box_address( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$post_office_box_address = [];

		$post_office_box_address['post_office_box']        = $this->serialize_localized_list_item_value( $data->get_post_office_box(), $lang );
		$post_office_box_address['postal_code']            = $data->get_postal_code();
		$post_office_box_address['municipality']           = $data->get_municipality();
		$post_office_box_address['additional_information'] = $this->serialize_localized_list_item_value( $data->get_additional_information(), $lang );

		return $post_office_box_address;

	}


	/**
	 * Serialize areas.
	 *
	 * @param $areas array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_areas( $areas, $lang = 'fi' ) {

		if ( ! $areas || ! is_array( $areas ) ) {
			return false;
		}

		$types = [];
		$codes = [];

		foreach ( $areas as $area ) {

			$filtered_type = ptv_to_snake_case( $area->get_type() );

			if ( ! isset( $codes[ $filtered_type ] ) ) {
				$codes[ $filtered_type ] = array();
				$types[ $filtered_type ] = $area->get_type();
			}

			if ( 'municipality' === $filtered_type ) {

				$municipality_codes = [];

				foreach ( $area->get_municipalities() as $municipality ) {
					$municipality_codes[] = $municipality->get_code();
				}

				$codes[ $filtered_type ] = $municipality_codes;

			} else {
				$codes[ $filtered_type ][] = sanitize_text_field( $area->get_code() );
			}
		}

		if ( ! $codes ) {
			return false;
		}

		$values = [];

		foreach ( $codes as $key => $value ) {
			$values[] = array(
				'type' => $types[ $key ],
				$key   => $value
			);
		}

		return array( '_ptv_areas' => $values );

	}

	/**
	 * Serialize support phones.
	 *
	 * @param $support_phones array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_support_phones( $support_phones, $lang = 'fi' ) {

		if ( ! $support_phones || ! is_array( $support_phones ) ) {
			return false;
		}

		$values = [];

		foreach ( $support_phones as $support_phone ) {

			if ( $lang === $support_phone->get_language() ) {
				$single_phone = $this->serialize_single_phone_number( $support_phone, $lang );
				$values[]     = $single_phone;
			}

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_support_phones' => $values );

	}

	/**
	 * Serialize attachments.
	 *
	 * @param $attachments array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_attachments( $attachments, $lang = 'fi' ) {

		if ( ! $attachments || ! is_array( $attachments ) ) {
			return false;
		}

		$values = [];

		foreach ( $attachments as $attachment ) {

			if ( $lang === $attachment->get_language() ) {

				$single_attachment = [];

				$single_attachment['type']        = sanitize_text_field( $attachment->get_type() );
				$single_attachment['name']        = sanitize_text_field( $attachment->get_name() );
				$single_attachment['description'] = sanitize_text_field( $attachment->get_description() );
				$single_attachment['url']         = esc_url( $attachment->get_url() );
				$single_attachment['language']    = sanitize_text_field( $attachment->get_language() );

				$values[] = $single_attachment;

			}

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_attachments' => $values );

	}

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

		$values = [];

		foreach ( $web_pages as $web_page ) {

			if ( $lang === $web_page->get_language() ) {
				$single_phone = $this->serialize_single_web_page( $web_page, $lang );
				$values[]     = $single_phone;
			}
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_web_pages' => $values );

	}

	/**
	 * Serialize single web page.
	 *
	 * @param $data PTV_Web_Page_With_Order_Number
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_single_web_page( PTV_Web_Page_With_Order_Number $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		$web_page                 = [];
		$web_page['order_number'] = $data->get_order_number();
		$web_page['_value']       = $data->get_value();
		$web_page['url']          = $data->get_url();
		$web_page['language']     = $data->get_language();

		return $web_page;

	}

	/**
	 * Serialize modified.
	 *
	 * @param $modified_time DateTime
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_modified( DateTime $modified_time, $lang = 'fi' ) {

		if ( ! $modified_time ) {
			return false;
		}

		return array( '_ptv_modified' => $modified_time->format( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ) ) );

	}

	/**
	 * Serialize localized list item value.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return bool
	 */
	function serialize_localized_list_item_value( $data, $lang = 'fi' ) {

		if ( ! is_array( $data ) ) {
			return false;
		}

		$value = '';

		foreach ( $data as $item ) {
			if ( ( $item->get_language() == $lang ) ) {
				$value = sanitize_textarea_field( $item->get_value() );
				break;
			}
		}

		return $value;

	}

	/**
	 * Serialize localized value.
	 *
	 * @param PTV_Localized_List_Item $data
	 * @param string $lang
	 *
	 * @return string
	 */
	function serialize_localized_value( PTV_Localized_List_Item $data, $lang = 'fi' ) {

		$value = '';

		if ( $data->get_language() == $lang ) {
			$value = sanitize_textarea_field( $data->get_value() );
		}

		return $value;

	}

	/**
	 * Serialize text field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return bool|string
	 */
	function serialize_text_field( $data, $lang = 'fi' ) {
		if ( ! $data ) {
			return false;
		}

		return sanitize_textarea_field( $data );

	}

	/**
	 * Serialize localized value with type.
	 *
	 * @param $data
	 * @param $lang
	 *
	 * @return array|bool
	 */
	public function serialize_localized_value_with_type( $data, $lang ) {

		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			$localized_value = $this->serialize_localized_value( $item, $lang );

			if ( $localized_value ) {

				$key = sprintf( '_ptv_%s', ptv_to_snake_case( $item->get_type() ) );

				$values[ $key ] = $this->serialize_localized_value( $item, $lang );
			}
		}

		if ( ! $values ) {
			return false;
		}

		return $values;
	}

}