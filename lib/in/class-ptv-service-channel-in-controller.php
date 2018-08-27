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

		$organization_id = carbon_get_post_meta( $post_id, 'ptv_organization_id' );

		if ( ! $organization_id ) {
			$organization_id = ptv_get_organization_id();
		}

		if ( ! $organization_id ) {
			return null;
		}

		return sanitize_text_field( $organization_id );

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

		$service_channel_names = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'Name'          => 'ptv_name',
				'AlternateName' => 'ptv_alternate_name',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']            = $type;
					$data['value']           = carbon_get_post_meta( $id, $field_key );
					$service_channel_names[] = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $service_channel_names ) ) {
			return null;
		}

		return $service_channel_names;

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

		$service_channel_descriptions = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'ShortDescription' => 'ptv_short_description',
				'Description'      => 'ptv_description',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']                   = $type;
					$data['value']                  = carbon_get_post_meta( $id, $field_key );
					$service_channel_descriptions[] = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		if ( empty( $service_channel_descriptions ) ) {
			return null;
		}


		return $service_channel_descriptions;

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

		$urls = array();

		foreach ( $post_translations as $lang => $id ) {

			$value = carbon_get_post_meta( $id, 'ptv_urls' );

			if ( $value ) {
				$urls[] = $this->prepare_language_item( $value, $lang );
			}
		}

		if ( empty( $urls ) ) {
			return null;
		}

		return $urls;

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

		$support_emails = array();

		foreach ( $post_translations as $lang => $id ) {

			$values = carbon_get_post_meta( $id, 'ptv_support_emails' );

			foreach ( $values as $value ) {

				if ( $value ) {
					$support_emails[] = $this->prepare_language_item( $value['_value'], $lang );
				}
			}
		}

		if ( empty( $support_emails ) ) {
			return null;
		}

		return $support_emails;

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

		$phone_objects = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$phones = carbon_get_post_meta( $id, 'ptv_support_phones' );

			if ( ! $phones || ! is_array( $phones ) ) {
				continue;
			}

			foreach ( $phones as $phone ) {

				if ( isset( $phone['number'] ) ) {

					foreach ( $phones as $phone ) {

						$phone_object = new PTV_Phone();
						$phone_object->set_language( sanitize_text_field( $lang ) );
						$phone_object->set_additional_information( sanitize_text_field( $phone['additional_information'] ) );
						$phone_object->set_service_charge_type( sanitize_text_field( $phone['service_charge_type'] ) );
						$phone_object->set_charge_description( sanitize_text_field( $phone['charge_description'] ) );
						$phone_object->set_is_finnish_service_number( (bool) $phone['is_finnish_service_number'] );
						$phone_object->set_prefix_number( sanitize_text_field( $phone['prefix_number'] ) );
						$phone_object->set_number( sanitize_text_field( $phone['number'] ) );

						if ( true === $phone['is_finnish_service_number'] ) {
							$phone_object->set_prefix_number( null );
						}
					}

					$phone_objects[] = $phone_object;
				}
			}
		}

		if ( empty( $phone_objects ) ) {
			return null;
		}

		return $phone_objects;

	}

	/**
	 * Get web pages.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_web_pages( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$web_page_objects = array();

		$web_pages = carbon_get_post_meta( $post_id, 'ptv_web_pages' );

		if ( ! $web_pages || ! is_array( $web_pages ) ) {
			return null;
		}

		$lang = $this->get_post_language( $post_id );

		$i = 0;

		foreach ( $web_pages as $web_page ) {

			if ( isset( $web_page['url'] ) ) {


				$web_page_object = new PTV_Web_Page_With_Order_Number();
				$web_page_object->set_language( sanitize_text_field( $lang ) );
				$web_page_object->set_order_number( $i );
				$web_page_object->set_url( esc_url( $web_page['url'] ) );

				if ( isset( $web_page['_value'] ) && ! empty( $web_page['_value'] ) ) {
					$web_page_object->set_value( sanitize_text_field( $web_page['_value'] ) );
				} else {
					$web_page_object->set_value( $web_page->get_url() );
				}

				$web_page_objects[] = $web_page_object;

				$i ++;
			}
		}

		if ( empty( $web_page_objects ) ) {
			return null;
		}

		return $web_page_objects;

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

		$phone_objects = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$phones = carbon_get_post_meta( $id, 'ptv_phone_numbers' );

			if ( ! $phones || ! is_array( $phones ) ) {
				return null;
			}

			foreach ( $phones as $phone ) {

				if ( isset( $phone['number'] ) && 'Phone' === $phone['type'] ) {

					$phone_object = new PTV_Phone();
					$phone_object->set_language( sanitize_text_field( $lang ) );
					$phone_object->set_additional_information( sanitize_text_field( $phone['additional_information'] ) );
					$phone_object->set_service_charge_type( sanitize_text_field( $phone['service_charge_type'] ) );
					$phone_object->set_charge_description( sanitize_text_field( $phone['charge_description'] ) );
					$phone_object->set_is_finnish_service_number( (bool) $phone['is_finnish_service_number'] );
					$phone_object->set_prefix_number( sanitize_text_field( $phone['prefix_number'] ) );
					$phone_object->set_number( sanitize_text_field( $phone['number'] ) );

					if ( true === $phone['is_finnish_service_number'] ) {
						$phone_object->set_prefix_number( null );
					}

					$phone_objects[] = $phone_object;
				}
			}
		}

		if ( empty( $phone_objects ) ) {
			return null;
		}

		return $phone_objects;

	}

	/**
	 * Get phone numbers.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_fax_numbers( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$fax_objects = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$faxes = carbon_get_post_meta( $id, 'ptv_phone_numbers' );

			if ( ! $faxes || ! is_array( $faxes ) ) {
				return null;
			}

			foreach ( $faxes as $fax ) {

				if ( isset( $fax['number'] ) && 'Fax' === $fax['type'] ) {

					$fax_object = new PTV_Phone_Simple();
					$fax_object->set_language( sanitize_text_field( $lang ) );
					$fax_object->set_is_finnish_service_number( (bool) $fax['is_finnish_service_number'] );
					$fax_object->set_prefix_number( sanitize_text_field( $fax['prefix_number'] ) );
					$fax_object->set_number( sanitize_text_field( $fax['number'] ) );

					if ( true === $fax['is_finnish_service_number'] ) {
						$fax_object->set_prefix_number( null );
					}

					$fax_objects[] = $fax_object;
				}
			}
		}

		if ( empty( $fax_objects ) ) {
			return null;
		}

		return $fax_objects;

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
				$service_hour_object->set_valid_to( new DateTime( $service_hour['valid_to'] ) );
			}

			$service_hour_object->set_is_closed( (bool) $service_hour['is_closed'] );
			$service_hour_object->set_valid_for_now( (bool) $service_hour['valid_for_now'] );

			$opening_hour_objects = array();

			if ( isset( $service_hour['opening_hour'] ) && ! empty( $service_hour['opening_hour'] ) ) {

				foreach ( $service_hour['opening_hour'] as $opening_hour ) {
					$opening_hour['is_extra'] = (int) $opening_hour['is_extra'];
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
					continue;
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

		$service_hour_objects = $object->get_service_hours();

		// Return true if nothing to sync.
		if ( empty( $service_hour_objects ) || ! is_array( $service_hour_objects ) ) {
			return true;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$service_hours = $this->get_serializer()->serialize_service_hours( $service_hour_objects, $lang );

			if ( ! empty( $service_hours ) ) {
				$this->update_post_meta( $id, $service_hours );
			}
		}

		return true;

	}

}
