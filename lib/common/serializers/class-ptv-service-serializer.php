<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/serializers/class-ptv-serializer.php' );

/**
 * Class PTV_Organization_Out_Controller
 */
class PTV_Service_Serializer extends PTV_Serializer {

	/**
	 * Serialize requirements field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_requirements( $data, $lang = 'fi' ) {

		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		return array( '_ptv_requirements' => $this->serialize_localized_list_item_value( $data, $lang ) );
	}

	/**
	 * Serialize vouchers in use field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_vouchers_in_use( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_service_vouchers_in_use' => intval( $data ) );

	}

	/**
	 * Serialize statutory service general description_id field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_statutory_service_general_description_id( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return false;
		}

		return array( '_ptv_statutory_service_general_description_id' => sanitize_text_field( $data ) );

	}

	/**
	 * Serialize vouchers field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_vouchers( $data, $lang = 'fi' ) {

		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			if ( $lang === $item->get_language() ) {

				$single_service_voucher                           = [];
				$single_service_voucher['_value']                 = sanitize_text_field( $item->get_value() );
				$single_service_voucher['url']                    = esc_url( $item->get_url() );
				$single_service_voucher['additional_information'] = sanitize_textarea_field( $item->get_additional_information() );

				$values[] = $single_service_voucher;
			}
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_service_vouchers' => $values );

	}

	/**
	 * Serialize organizations field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_organizations( $data, $lang = 'fi' ) {

		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			$single_organization                   = [];
			$single_organization['provision_type'] = sanitize_text_field( $item->get_provision_type() );
			$single_organization['role_type']      = sanitize_text_field( $item->get_role_type() );

			if ( $item->get_additional_information() ) {
				$single_organization['additional_information'] = $this->serialize_localized_list_item_value( $item->get_additional_information(), $lang );
			}

			$organization = $item->get_organization();

			if ( $organization ) {
				$single_organization['organization_id'] = sanitize_text_field( $organization->get_id() );
			}

			$values[] = $single_organization;

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_organizations' => $values );

	}

	/**
	 * Serialize service channels field.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_channels( $data, $lang = 'fi' ) {

		if ( ! $data || ! is_array( $data ) ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			$single_service_channel                       = [];
			$single_service_channel['service_channel_id'] = sanitize_text_field( $item->get_service_channel()->get_id() );

			$values[] = $single_service_channel;

		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_service_channels' => $values );

	}

	/**
	 * Serialize legistraltion field.
	 *
	 * @param $data array
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_legislation( $data, $lang = 'fi' ) {

		if ( ! $data ) {
			return array( '_ptv_legislation' => array() );
		}

		$values = [];

		foreach ( $data as $item ) {

			foreach ( $item['names'] as $index => $name ) {

				if ( $lang === $name->get_language() ) {

					$single_law             = [];
					$single_law['name']     = sanitize_text_field( $name->get_value() );
					$single_law['web_page'] = esc_url( $item->get_web_pages()[ $index ]['url'] );

					$values[] = $single_law;

				}
			}
		}

		if ( ! $values ) {
			return false;
		}

		return array( '_ptv_legislation' => $values );

	}


	/**
	 * Serialize life events taxonomy term.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_life_events( $data, $lang = 'fi' ) {

		return $this->serialize_taxonomy_term( 'life_events', $data, $lang );

	}

	/**
	 * Serialize target group taxonomy term.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_target_groups( $data, $lang = 'fi' ) {

		return $this->serialize_taxonomy_term( 'target_groups', $data, $lang );

	}

	/**
	 * Serialize service class term.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_service_classes( $data, $lang = 'fi' ) {

		return $this->serialize_taxonomy_term( 'service_classes', $data, $lang );

	}

	/**
	 * Serialize ontology taxonomy term.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_ontology_terms( $data, $lang = 'fi' ) {

		return $this->serialize_taxonomy_term( 'ontology_terms', $data, $lang );

	}

	/**
	 * Serialize industrial class term.
	 *
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_industrial_classes( $data, $lang = 'fi' ) {

		return $this->serialize_taxonomy_term( 'industrial_classes', $data, $lang );

	}

	/**
	 * Serialize taxonomy term.
	 *
	 * @param $taxonomy
	 * @param $data
	 * @param string $lang
	 *
	 * @return array|bool
	 */
	function serialize_taxonomy_term( $taxonomy, $data, $lang = 'fi' ) {

		if ( ! $taxonomy || ! $data || ! is_array( $data ) ) {
			return false;
		}

		$values = [];

		foreach ( $data as $item ) {

			$single_target_group               = [];
			$single_target_group['name']       = $this->serialize_localized_list_item_value( $item->get_name(), $lang );
			$single_target_group['uri']        = esc_url( $item->get_uri() );
			$single_target_group['parent_uri'] = esc_url( $item->get_parent_uri() );

			$values[] = $single_target_group;

		}

		if ( ! $values ) {
			return false;
		}

		return array(
			'taxonomies' => array(
				ptv_to_taxomomy_name( $taxonomy ) => $values,
			),
		);

	}


}