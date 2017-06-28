<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
abstract class PTV_Service_Channel_In_Controller extends PTV_In_Controller {

	/**
	 * Get organization id.
	 *
	 * @param null $post_id
	 *
	 * @return null|string
	 */
	function get_organization_id( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		return (string) carbon_get_post_meta( $post_id, 'ptv_organization_id' );

	}

	/**
	 * Get service channel names.
	 *
	 * @param null $post_id
	 *
	 * @return array|null
	 */
	function get_service_channel_names( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'Name'          => 'ptv_service_channel_names_name',
				'AlternateName' => 'ptv_service_channel_names_alternate_name',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']  = $type;
					$data['value'] = carbon_get_post_meta( $id, $field_key );
					$result[]      = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get channel descriptions.
	 *
	 * @param null $post_id
	 *
	 * @return array|null
	 */
	function get_service_channel_descriptions( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'ShortDescription' => 'ptv_service_channel_descriptions_short_description',
				'Description'      => 'ptv_service_channel_descriptions_description',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']  = $type;
					$data['value'] = carbon_get_post_meta( $id, $field_key );
					$result[]      = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}


		return $result;

	}

	/**
	 * Get type.
	 *
	 * @param $post_id
	 *
	 * @return null|string
	 */
	public function get_type( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (string) get_post_meta( $post_id, '_ptv_type', true );

	}

	/**
	 * Get languages.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed|null
	 */
	public function get_languages( $post_id ) {

		if ( ! $post_id ) {
			return false;
		}

		$languages = carbon_get_post_meta( $post_id, 'ptv_languages' );

		if ( empty( $languages ) ) {
			return null;
		}

		return $languages;

	}

	/**
	 * Get urls.
	 *
	 * @param null $post_id
	 *
	 * @return array|null
	 */
	function get_urls( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_urls' );

			if ( $value ) {
				$result[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get support emails.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_support_emails( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$result = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_support_emails' );

			if ( $value ) {
				$result[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get support phones.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_support_phones( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$phones = carbon_get_post_meta( $id, 'ptv_support_phones' );

			if ( ! $phones || ! is_array( $phones ) ) {
				return null;
			}

			foreach ( $phones as $phone ) {

				if ( isset( $phone['number'] ) ) {

					$phone = new PTV_Phone( $phone );
					$phone->set_language( $lang );

					if ( true === $phone['is_finnish_service_number'] ) {
						$phone->set_prefix_number( null );
					}

					$result[] = $phone;
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get phone numbers.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_phone_numbers( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$phones = carbon_get_post_meta( $id, 'ptv_phone_numbers' );

			if ( ! $phones || ! is_array( $phones ) ) {
				return null;
			}

			foreach ( $phones as $phone ) {

				if ( isset( $phone['number'] ) ) {

					$phone = new PTV_Phone( $phone );
					$phone->set_language( $lang );

					if ( true === $phone['is_finnish_service_number'] ) {
						$phone->set_prefix_number( null );
					}

					$result[] = $phone;
				}
			}
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}

	/**
	 * Get service hours.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_service_hours( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$service_hour_objects = array();
		$merged_result        = array();
		$post_translations    = $this->get_post_translations( $post_id );

		$service_hours = carbon_get_post_meta( $post_id, 'ptv_service_hours' );

		// Fallback to the default language.
		if ( ! $service_hours ) {
			$service_hours = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_service_hours' );
		}

		if ( ! $service_hours || ! is_array( $service_hours ) ) {
			return null;
		}

		foreach ( $service_hours as $service_hour ) {

			$service_hour_object = new PTV_Service_Hour();

			$service_hour_object->set_service_hour_type( $service_hour['service_hour_type'] );

			if ( isset( $service_hour['valid_from'] ) && ! empty( $service_hour['valid_from'] ) ) {
				$service_hour_object->set_valid_from( new DateTime( $service_hour['valid_from'] ) );
			}

			if ( isset( $service_hour['valid_to'] ) && ! empty( $service_hour['valid_to'] ) ) {
				$service_hour_object->set_valid_from( new DateTime( $service_hour['valid_to'] ) );
			}

			$service_hour_object->set_is_closed( (bool) $service_hour['is_closed'] );
			$service_hour_object->set_valid_for_now( (bool) $service_hour['valid_for_now'] );

			$opening_hour_objects = array();

			if ( isset( $service_hour['opening_hour'] ) && ! empty( $service_hour['opening_hour'] ) ) {

				foreach ( $service_hour['opening_hour'] as $opening_hour ) {
					$opening_hour['is_extra'] = (bool) $opening_hour['is_extra'];
					$opening_hour_objects[]   = new PTV_Daily_Opening_Time( $opening_hour );
				}

				$service_hour_object->set_opening_hour( $opening_hour_objects );
			}

			$service_hour_objects[] = $service_hour_object;

		}

		foreach ( $service_hour_objects as $index => $service_hour_object ) {

			$additional_informations = array();

			foreach ( $post_translations as $lang => $id ) {

				$localized_service_hours = carbon_get_post_meta( $id, 'ptv_service_hours' );

				if ( ! $localized_service_hours ) {
					$localized_service_hours = carbon_get_post_meta( $post_translations[ $this->settings['primary_language'] ], 'ptv_service_hours' );
				}

				if ( ! $localized_service_hours || ! is_array( $localized_service_hours ) ) {
					return null;
				}

				if ( isset( $localized_service_hours[ $index ]['additional_information'] ) && ! empty( $localized_service_hours[ $index ]['additional_information'] ) ) {
					$additional_informations[] = $this->prepare_language_item( $localized_service_hours[ $index ]['additional_information'], $lang );
				}
			}

			if ( ! empty( $additional_informations ) ) {
				$service_hour_object->set_additional_information( $additional_informations );
			}

			$merged_result[] = $service_hour_object;

		}

		if ( empty( $merged_result ) ) {
			return null;
		}

		return $merged_result;

	}

	/**
	 * Sync service hours.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_service_hours( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$post_translations    = $this->get_post_translations( $post_id );
		$service_hour_objects = $object->get_service_hours();


		if ( empty( $service_hour_objects ) || ! is_array( $service_hour_objects ) ) {
			return false;
		}

		foreach ( $post_translations as $lang => $id ) {

			$service_hours = array();

			foreach ( $service_hour_objects as $service_hour_object ) {

				if ( ! empty( $service_hour_object ) ) {


					$service_hour = array();

					$service_hour['service_hour_type']      = $service_hour_object->get_service_hour_type();
					$service_hour['is_closed']              = (int) $service_hour_object->get_is_closed();
					$service_hour['valid_for_now']          = (int) $service_hour_object->get_valid_for_now();
					$service_hour['additional_information'] = ptv_get_localized_value( $service_hour_object->get_additional_information(), $lang );

					$valid_from = $service_hour_object->get_valid_from();
					$valid_to   = $service_hour_object->get_valid_to();

					if ( is_a( $valid_from, 'DateTime' ) ) {
						$service_hour['valid_from'] = $valid_from->format( 'Y-m-d' );
					}

					if ( is_a( $valid_to, 'DateTime' ) ) {
						$service_hour['valid_to'] = $valid_to->format( 'Y-m-d' );
					}

					$opening_hour_objects = $service_hour_object->get_opening_hour();
					$opening_hours        = array();

					if ( $opening_hour_objects ) {

						foreach ( $opening_hour_objects as $opening_hour_object ) {
							$opening_hour             = array();
							$opening_hour['day_from'] = $opening_hour_object->get_day_from();
							$opening_hour['day_to']   = $opening_hour_object->get_day_to();
							$opening_hour['from']     = $opening_hour_object->get_from();
							$opening_hour['to']       = $opening_hour_object->get_to();
							$opening_hour['is_extra'] = (int) $opening_hour_object->get_is_extra();

							$opening_hours[] = $opening_hour;
						}

						$service_hour['opening_hour'] = $opening_hours;
					}

					$service_hours[] = $service_hour;

				}
			}

			if ( ! empty( $service_hours ) ) {
				carbon_set_post_meta( $id, 'ptv_service_hours', $service_hours );
			}
		}

		return true;

	}

}
