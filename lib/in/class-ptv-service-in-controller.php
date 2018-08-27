<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-in-controller.php' );

/**
 * Class PTV_Service_In_Controller
 */
class PTV_Service_In_Controller extends PTV_In_Controller {

	/**
	 * @var $post_type string
	 */
	protected $post_type = 'ptv-service';

	/**
	 * Create a new service.
	 *
	 * @param null $post_id
	 */
	function create( $post_id = null ) {

		if ( ! $post_id ) {
			return;
		}

		$request = new PTV_Service_In();

		// Set source id.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $request->valid() ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ), $request->list_invalid_properties() );

			return;
		}

		// Create new service.
		$new_service = $this->api->get_service_api()->create_service( $request );

		if ( is_wp_error( $new_service ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_service->get_error_data() );

			return;
		}

		// Sync other fields before connections.
		$this->sync( $post_id, $new_service );

		// Update service and service channel connections.
		$update_connections = $this->update_connections( $post_id );

		if ( ! $update_connections ) {
			$this->errors->add( 'ptv-connections-update-failed', __( 'Item was saved to the PTV, but update of service and channel connections failed.', 'ptv-for-wordpress' ) );
		}

	}

	/**
	 * Update a service.
	 *
	 * @param null $post_id
	 */
	function update( $post_id = null ) {

		$request = new PTV_Service_In_Base();

		$id = $this->get_translation_group_id( $post_id );

		// Set ID.
		$request->set_source_id( $post_id );

		// Set general fields.
		$request = $this->set_general_fields( $post_id, $request );

		// Validate request.
		if ( ! $request->valid() ) {
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		$updated_service = $this->api->get_service_api()->update_service_by_id( $id, $request );

		if ( is_wp_error( $updated_service ) ) {
			$this->errors->add( 'ptv-update-failed', __( 'Failed to update the item to the PTV.', 'ptv-for-wordpress' ), $updated_service->get_error_data() );

			return;
		}

		// Sync other fields before connections.
		$this->sync( $post_id, $updated_service );

		// Update service and service channel connections.
		$update_connections = $this->update_connections( $post_id );

		if ( ! $update_connections ) {
			$this->errors->add( 'ptv-connections-update-failed', __( 'Item was saved to the PTV, but update of service and channel connections failed.', 'ptv-for-wordpress' ) );
		}

	}


	/**
	 * Set request values for general fields.
	 *
	 * @param $post_id
	 * @param $request PTV_Service_In|PTV_Service_In_Base
	 *
	 * @return null
	 */
	public function set_general_fields( $post_id, $request ) {

		if ( ! $post_id && ! $request ) {
			return null;
		}

		// Set type.
		$request->set_type( $this->get_type( $post_id ) );

		// Set general description id.
		$request->set_statutory_service_general_description_id( $this->get_statutory_service_general_description_id( $post_id ) );

		// Set status.
		$request->set_publishing_status( $this->get_publishing_status( $post_id ) );

		// Set service names.
		$request->set_service_names( $this->get_service_names( $post_id ) );

		// Set service descriptions.
		$request->set_service_descriptions( $this->get_service_descriptions( $post_id ) );

		// Set service charge type.
		$request->set_service_charge_type( $this->get_service_charge_type( $post_id ) );

		// Set requirements.
		$request->set_requirements( $this->get_requirements( $post_id ) );

		// Set area type.
		$request->set_area_type( $this->get_area_type( $post_id ) );

		// Set areas.
		$request->set_areas( $this->get_areas( $post_id ) );

		// Set legislation.
		$request->set_legislation( $this->get_legislation( $post_id ) );

		if ( ! $request->get_legislation() && method_exists( $request, 'set_delete_all_laws' ) ) {
			$request->set_delete_all_laws( true );
		}

		// Set languages.
		$request->set_languages( $this->get_languages( $post_id ) );

		// Set funding type.
		$request->set_funding_type( $this->get_funding_type( $post_id ) );

		// Set main responsible organization.
		$request->set_main_responsible_organization( $this->get_main_responsible_organization( $post_id ) );

		// Set main responsible organizations.
		$request->set_other_responsible_organizations( $this->get_other_responsible_organizations( $post_id ) );

		// Set service producers.
		$request->set_service_producers( $this->get_service_producers( $post_id ) );

		// Set service vouchers in use.
		$request->set_service_vouchers_in_use( $this->get_service_vouchers_in_use( $post_id ) );

		// Set service vouchers.
		$request->set_service_vouchers( $this->get_service_vouchers( $post_id ) );

		// Set target groups.
		$request->set_target_groups( $this->get_target_groups( $post_id ) );

		// Set ontology terms.
		$request->set_ontology_terms( $this->get_ontology_terms( $post_id ) );

		// Set life events.
		$request->set_life_events( $this->get_life_events( $post_id ) );

		// Delete all life events if none is set.
		if ( ! $request->get_life_events() && method_exists( $request, 'set_delete_all_life_events' ) ) {
			$request->set_delete_all_life_events( true );
		}

		// Set service classes.
		$request->set_service_classes( $this->get_service_classes( $post_id ) );

		// Set ontology terms.
		$request->set_industrial_classes( $this->get_industrial_classes( $post_id ) );

		// Delete all industrial classes if none is set.
		if ( ! $request->get_industrial_classes() && method_exists( $request, 'set_delete_all_industrial_classes' ) ) {
			$request->set_delete_all_industrial_classes( true );
		}

		return $request;

	}

	/**
	 * Get service names.
	 *
	 * @param null $post_id
	 *
	 * @return array|null
	 */
	function get_service_names( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$names = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'Name'          => 'ptv_name',
				'AlternateName' => 'ptv_alternate_name',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']  = $type;
					$data['value'] = carbon_get_post_meta( $id, $field_key );
					$names[]       = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		return $names;

	}

	/**
	 * Get service descriptions.
	 *
	 * @param null $post_id
	 *
	 * @return null
	 */
	function get_service_descriptions( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		$post_translations = $this->get_post_translations( $post_id );

		$descriptions = array();

		foreach ( $post_translations as $lang => $id ) {

			$fields = array(
				'ShortDescription'             => 'ptv_short_description',
				'Description'                  => 'ptv_description',
				'ServiceUserInstruction'       => 'ptv_service_user_instruction',
				'ProcessingTimeAdditionalInfo' => 'ptv_processing_time_additional_info',
				'DeadLineAdditionalInfo'       => 'ptv_dead_line_additional_info',
				'ChargeTypeAdditionalInfo'     => 'ptv_charge_type_additional_info',
				'ValidityTimeAdditionalInfo'   => 'ptv_validity_time_additional_info',
			);

			$data = array();

			foreach ( $fields as $type => $field_key ) {
				if ( carbon_get_post_meta( $id, $field_key ) ) {
					$data['type']   = $type;
					$data['value']  = carbon_get_post_meta( $id, $field_key );
					$descriptions[] = $this->prepare_localized_list_item( $data, $lang );
				}
			}
		}

		return $descriptions;

	}

	/**
	 * Get type.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_type( $post_id ) {

		$type = carbon_get_post_meta( $post_id, 'ptv_type' );

		if ( empty( $type ) ) {
			return null;
		}

		return sanitize_text_field( $type );

	}

	/**
	 * Get general description id.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_statutory_service_general_description_id( $post_id ) {

		$general_description_id = carbon_get_post_meta( $post_id, 'ptv_statutory_service_general_description_id' );

		if ( empty( $general_description_id ) ) {
			return null;
		}

		return sanitize_text_field( $general_description_id );

	}

	/**
	 * Get requirements.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_requirements( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$requirements = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {
			$data['type']  = 'Requirements';
			$data['value'] = carbon_get_post_meta( $id, 'ptv_requirements' );

			if ( $data['value'] ) {
				$requirements[] = $this->prepare_localized_list_item( $data, $lang );
			}
		}

		if ( empty( $requirements ) ) {
			return null;
		}

		return $requirements;

	}


	/**
	 * Get service charge type.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_service_charge_type( $post_id ) {

		$service_charge_type = carbon_get_post_meta( $post_id, 'ptv_service_charge_type' );

		if ( empty( $service_charge_type ) ) {
			return null;
		}

		return sanitize_text_field( $service_charge_type );

	}

	/**
	 * Get languages.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_languages( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (array) carbon_get_post_meta( $post_id, 'ptv_languages' );

	}

	/**
	 * Get funding type.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function get_funding_type( $post_id = null ) {

		if ( ! $post_id ) {
			return null;
		}

		return sanitize_text_field( carbon_get_post_meta( $post_id, 'ptv_funding_type' ) );

	}

	/**
	 * Get service vouchers in use.
	 *
	 * @param $post_id
	 *
	 * @return bool|null
	 */
	public function get_service_vouchers_in_use( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return (bool) carbon_get_post_meta( $post_id, 'ptv_service_vouchers_in_use' );

	}

	/**
	 * Get service producers.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_service_producers( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$organization_objects = array();
		$merged_result        = array();
		$post_translations    = $this->get_post_translations( $post_id );

		$organizations = carbon_get_post_meta( $post_id, 'ptv_organizations' );

		if ( ! $organizations || ! is_array( $organizations ) ) {
			return null;
		}

		foreach ( $organizations as $index => $organization ) {

			if ( 'Producer' !== $organization['role_type'] ) {
				continue;
			}

			$key = md5( $index . $organization['organization_id'] . $organization['role_type'] . $organization['provision_type'] );

			$organization_object = new PTV_Service_Producer_In();

			$organization_object
				->set_order_number( (int) $index )
				->set_provision_type( sanitize_text_field( $organization['provision_type'] ) );

			if ( $organization['organization_id'] ) {
				$organization_object->set_organizations( array( sanitize_text_field( $organization['organization_id'] ) ) );
			}

			$organization_objects[ $key ] = $organization_object;

		}

		foreach ( $organization_objects as $index => $organization_object ) {

			$localized_additional_information = array();

			foreach ( $post_translations as $lang => $id ) {

				$localized_organizations = carbon_get_post_meta( $id, 'ptv_organizations' );

				if ( ! $localized_organizations || ! is_array( $localized_organizations ) ) {
					continue;
				}

				$localized_organizations_with_key = array();

				foreach ( $localized_organizations as $localized_index => $localized_organization ) {
					$key                                      = md5( $localized_index . $localized_organization['organization_id'] . $localized_organization['role_type'] . $localized_organization['provision_type'] );
					$localized_organizations_with_key[ $key ] = $localized_organization;

				}

				if ( isset( $localized_organizations_with_key[ $index ]['additional_information'] ) && ! empty( $localized_organizations_with_key[ $index ]['additional_information'] ) ) {
					$localized_additional_information[] = $this->prepare_language_item( $localized_organizations_with_key[ $index ]['additional_information'], $lang );
				}
			}


			if ( ! empty( $localized_additional_information ) ) {
				$organization_object->set_additional_information( $localized_additional_information );
			}

			$merged_result[] = $organization_object;

		}

		if ( empty( $merged_result ) ) {
			return null;
		}

		return $merged_result;

	}


	/**
	 * Get main responsible organization.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_main_responsible_organization( $post_id ) {

		$post_language     = $this->get_post_language( $post_id );
		$post_translations = $this->get_post_translations( $post_id );

		$all_main_responsible_organizations = array();

		foreach ( $post_translations as $lang => $id ) {

			$localized_responsible_organizations = carbon_get_post_meta( $id, 'ptv_organizations' );

			if ( $localized_responsible_organizations ) {
				$all_main_responsible_organizations[ $lang ] = $localized_responsible_organizations;
			}
		}

		$responsible_organizations = $all_main_responsible_organizations[ $post_language ];

		// Fallback to the default language.
		if ( ! $responsible_organizations ) {
			$responsible_organizations = $all_main_responsible_organizations[ $this->settings['primary_language'] ];
		}

		if ( ! $responsible_organizations || ! is_array( $responsible_organizations ) ) {
			return null;
		}

		$value = '';

		foreach ( $responsible_organizations as $index => $responsible_organization ) {

			if ( 'Responsible' !== $responsible_organization['role_type'] ) {
				continue;
			}

			if ( $responsible_organization['organization_id'] ) {
				$value = $responsible_organization['organization_id'];
				break;
			}
		}

		if ( ! $value ) {
			return null;
		}

		return $value;

	}

	/**
	 * Get other responsible organizations.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_other_responsible_organizations( $post_id ) {

		$post_language     = $this->get_post_language( $post_id );
		$post_translations = $this->get_post_translations( $post_id );

		$all_other_responsible_organizations = array();

		foreach ( $post_translations as $lang => $id ) {

			$localized_other_responsible_organizations = carbon_get_post_meta( $id, 'ptv_organizations' );

			if ( $localized_other_responsible_organizations ) {
				$all_other_responsible_organizations[ $lang ] = $localized_other_responsible_organizations;
			}
		}

		$other_responsible_organizations = $all_other_responsible_organizations[ $post_language ];

		// Fallback to the default language.
		if ( ! $other_responsible_organizations ) {
			$other_responsible_organizations = $all_other_responsible_organizations[ $this->settings['primary_language'] ];
		}

		if ( ! $other_responsible_organizations || ! is_array( $other_responsible_organizations ) ) {
			return null;
		}

		$values = [];

		foreach ( $other_responsible_organizations as $index => $other_responsible_organization ) {

			if ( 'OtherResponsible' !== $other_responsible_organization['role_type'] ) {
				continue;
			}

			if ( $other_responsible_organization['organization_id'] ) {
				$values[] = $other_responsible_organization['organization_id'];
				break;
			}
		}

		if ( ! $values ) {
			return null;
		}

		return $values;

	}

	/**
	 * Get service vouchers.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_service_vouchers( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$lang = $this->get_post_language( $post_id );

		$service_voucher_objects = array();

		$service_vouchers = carbon_get_post_meta( $post_id, 'ptv_service_vouchers' );

		if ( empty( $service_vouchers ) || ! is_array( $service_vouchers ) ) {
			return null;
		}

		foreach ( $service_vouchers as $i => $service_voucher ) {

			$service_voucher_object = new PTV_Service_Voucher();

			$service_voucher_object
				->set_language( sanitize_text_field( $lang ) )
				->set_order_number( $i )
				->set_url( esc_url( $service_voucher['url'] ) )
				->set_value( sanitize_text_field( $service_voucher['_value'] ) )
				->set_additional_information( sanitize_text_field( $service_voucher['additional_information'] ) );

			$service_voucher_objects[] = $service_voucher_object;

		}

		if ( empty( $service_voucher_objects ) ) {
			return null;
		}

		return $service_voucher_objects;

	}

	/**
	 * Get legislation.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_legislation( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$law_objects            = [];
		$post_translations = $this->get_post_translations( $post_id );
		$post_language     = $this->get_post_language( $post_id );

		$names     = [];
		$web_pages = [];
		$all_laws  = [];

		foreach ( $post_translations as $lang => $translation_post_id ) {

			$localized_laws = carbon_get_post_meta( $translation_post_id, 'ptv_legislation' );

			if ( ! $localized_laws ) {
				continue;
			}

			$all_laws[ $lang ] = $localized_laws;

		}

		if ( ! $all_laws ) {
			return null;
		}

		$laws = $all_laws[ $post_language ];

		foreach ( $laws as $law_index => $law ) {

			if ( ! $law['name'] || ! $law['web_page'] ) {
				continue;
			}

			foreach ( $post_translations as $lang => $id ) {

				if ( isset( $all_laws[ $lang ][ $law_index ]['name'] ) ) {
					$names[ $law_index ][]     = $this->prepare_language_item( sanitize_text_field( $all_laws[ $lang ][ $law_index ]['name'] ), sanitize_text_field( $lang ) );
					$web_page_object           = new PTV_Web_Page();
					$web_pages[ $law_index ][] = $web_page_object->set_url( esc_url( $all_laws[ $lang ][ $law_index ]['web_page'] ) )->set_language( sanitize_text_field( $lang ) );
				}
			}


			if ( isset( $names[ $law_index ] ) && isset( $web_pages[ $law_index ] ) ) {
				$law_object = new PTV_Law();
				$law_object
					->set_names( $names[ $law_index ] )
					->set_web_pages( $web_pages[ $law_index ] );

				$law_objects[] = $law_object;
			}
		}

		if ( empty( $law_objects ) ) {
			return null;
		}

		return $law_objects;

	}

	/**
	 * Get target groups.
	 *
	 * @param $post_id
	 *
	 * @return array|bool|null
	 */
	public function get_target_groups( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return $this->get_taxonomy_terms( $post_id, 'ptv-target-groups' );

	}

	/**
	 * Get ontology terms.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_ontology_terms( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		$terms = $this->get_taxonomy_terms( $post_id, 'ptv-ontology-terms' );

		if ( $terms ) {
			return $terms;
		}

		// Default fallback.
		return array( 'http://www.yso.fi/onto/koko/p36438' );

	}

	/**
	 * @param $post_id
	 *
	 * @return array|bool|null
	 */
	public function get_service_classes( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return $this->get_taxonomy_terms( $post_id, 'ptv-service-classes' );

	}

	/**
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_life_events( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return $this->get_taxonomy_terms( $post_id, 'ptv-life-events' );

	}

	/**
	 * Get industrial classes.
	 *
	 * @param $post_id
	 *
	 * @return array|null
	 */
	public function get_industrial_classes( $post_id ) {

		if ( ! $post_id ) {
			return null;
		}

		return $this->get_taxonomy_terms( $post_id, 'ptv-industrial-classes' );

	}

	/**
	 * Get taxonomy terms for post.
	 *
	 * @param null $post_id
	 * @param null $taxonomy
	 *
	 * @return array|null
	 */
	public function get_taxonomy_terms( $post_id = null, $taxonomy = null ) {

		if ( ! $post_id || ! $taxonomy ) {
			return null;
		}

		$term_uris = array();

		$terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );

		if ( is_wp_error( $terms ) ) {
			return null;
		}

		if ( $terms ) {
			foreach ( $terms as $term ) {

				$ptv_id = get_term_meta( $term, 'uri', true );

				if ( $ptv_id ) {
					$term_uris[] = sanitize_text_field( $ptv_id );
				}
			}
		}

		if ( ! $term_uris ) {
			return null;
		}

		return $term_uris;
	}

	/**
	 * Prepare web page for request.
	 *
	 * @param $data
	 * @param $lang
	 *
	 * @return null|PTV_Web_Page
	 */
	public function prepare_web_page( $data, $lang ) {

		if ( ! $data || ! is_array( $data ) || ! $lang ) {
			return null;
		}

		if ( ! isset( $data['url'] ) ) {
			return null;
		}

		$web_page = new PTV_Web_Page();

		$web_page
			->set_url( esc_url( $data['url'] ) )
			->set_value( $data['value'] )
			->set_language( sanitize_text_field( $lang ) );

		return $web_page;
	}

	/**
	 * Update service and channel connections.
	 *
	 * @param $post_id
	 *
	 * @return bool|PTV_Service
	 */
	public function update_connections( $post_id ) {

		if ( ! $post_id ) {
			return false;
		}

		$id = $this->get_translation_group_id( $post_id );

		$service_channels = carbon_get_post_meta( $post_id, 'ptv_service_channels' );

		$service_and_channel_relations = new PTV_Service_And_Channel_Relation_In_Base();

		if ( $service_channels ) {

			$service_channel_objects = array();
			foreach ( $service_channels as $service_channel ) {

				$service_channel_object = new PTV_Service_Service_Channel_In_Base();
				$service_channel_object->set_service_channel_id( sanitize_text_field( $service_channel['service_channel_id'] ) );

				$service_channel_objects[] = $service_channel_object;

			}

			if ( $service_channel_objects ) {
				$service_and_channel_relations->set_channel_relations( $service_channel_objects );

			}
		} else {
			$service_and_channel_relations->set_delete_all_channel_relations( true );
		}

		$updated_connections = $this->api->get_connection_api()->update_connection_by_service_id( $id, $service_and_channel_relations );

		if ( is_wp_error( $updated_connections ) ) {
			return false;
		}

		// Sync connections.
		$sync_connections = $this->sync_connections( $post_id, $updated_connections );

		if ( ! $sync_connections ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of service and channel connections to translations failed.', 'ptv-for-wordpress' ) );
		}

		return true;
	}

	/**
	 * Sync connections.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	function sync_connections( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$service_channel_objects = $object->get_service_channels();

		// Return true if nothing to sync.
		if ( empty( $service_channel_objects ) ) {
			return true;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$service_channels = $this->get_serializer()->serialize_service_channels( $service_channel_objects, $lang );

			$this->update_post_meta( $id, $service_channels );

		}

		return true;

	}

	/**
	 * Sync organizations.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_organizations( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$organization_objects = $object->get_organizations();

		// Return true if nothing to sync.
		if ( empty( $organization_objects ) || ! is_array( $organization_objects ) ) {
			return true;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$organizations = $this->get_serializer()->serialize_organizations( $organization_objects, $lang );

			if ( ! empty( $organizations ) ) {
				$this->update_post_meta( $id, $organizations );
			}
		}

		return true;

	}

	/**
	 * Sync legislation.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_legislation( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$legislation_objects = $object->get_legislation();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {

			$legislation = $this->get_serializer()->serialize_legislation( $legislation_objects, $lang );

			if ( ! empty( $legislation ) ) {
				$this->update_post_meta( $id, $legislation );
			}
		}

		return true;

	}

	/**
	 * Sync taxonomies.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_taxonominies( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$post_type_helper = new PTV_Post_Type_Helper();

		$all_post_taxonomies = array();

		$taxonomy_names = array(
			'service_classes'    => 'ptv-service-classes',
			'ontology_terms'     => 'ptv-ontology-terms',
			'target_groups'      => 'ptv-target-groups',
			'life_events'        => 'ptv-life-events',
			'industrial_classes' => 'ptv-industrial-classes',
		);

		foreach ( $taxonomy_names as $ptv_name => $local_name ) {

			$getter = sprintf( 'get_%s', $ptv_name );

			$taxonomy_objects = $object->$getter();

			if ( empty( $taxonomy_objects ) || ! is_array( $taxonomy_objects ) ) {
				continue;
			}

			$post_translations = $this->get_post_translations( $post_id );

			foreach ( $post_translations as $lang => $id ) {

				if ( ! isset( $all_post_taxonomies[ $id ] ) ) {
					$all_post_taxonomies[ $id ] = array();
				}

				$field_serializer = sprintf( 'serialize_%s', $ptv_name );

				$all_post_taxonomies[ $id ] = array_merge_recursive( $all_post_taxonomies[ $id ], $this->get_serializer()->$field_serializer( $taxonomy_objects, $lang ) );

			}
		}


		if ( $all_post_taxonomies ) {
			foreach ( $all_post_taxonomies as $id => $post_taxonomies ) {
				$post_type_helper->save_post_taxonomies( $id, $post_taxonomies, $this->get_post_language( $id ) );
			}
		}

		return true;

	}

	/**
	 * Sync service charge type.
	 *
	 * @param $post_id
	 * @param $object
	 *
	 * @return bool
	 */
	public function sync_service_charge_type( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			return false;
		}

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {
			carbon_set_post_meta( $id, 'ptv_service_charge_type', sanitize_text_field( $object->get_service_charge_type() ) );
		}

		return true;

	}

	/**
	 * Synchronize fields that are common for all translations.
	 *
	 * @param $post_id
	 * @param $object
	 */
	public function sync( $post_id, $object ) {

		if ( ! $post_id || ! $object ) {
			$this->errors->add( 'ptv-invalid-argument-error', __( 'Post id or object missing.', 'ptv-for-wordpress' ) );

			return;
		}

		// Sync unique ids.
		$sync_unique_ids = $this->sync_translation_group_ids( $post_id, $object );

		if ( ! $sync_unique_ids ) {
			$this->errors->add( 'ptv-unique-id-sync-error', __( 'Item was saved to the PTV, but synchronization of unique ids to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync organizations.
		$sync_organizations = $this->sync_organizations( $post_id, $object );

		if ( ! $sync_organizations ) {
			$this->errors->add( 'ptv-organizations-sync-error', __( 'Item was saved to the PTV, but synchronization of organizations to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync service classes.
		$sync_taxonominies = $this->sync_taxonominies( $post_id, $object );

		if ( ! $sync_taxonominies ) {
			$this->errors->add( 'ptv-taxonomies-sync-error', __( 'Item was saved to the PTV, but synchronization of taxonomies to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync service charge type.
		$sync_service_charge_type = $this->sync_service_charge_type( $post_id, $object );

		if ( ! $sync_service_charge_type ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of service charge type to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync languages.
		$sync_languages = $this->sync_languages( $post_id, $object );

		if ( ! $sync_languages ) {
			$this->errors->add( 'ptv-languages-sync-error', __( 'Item was saved to the PTV, but synchronization of languages to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync areas.
		$sync_areas = $this->sync_areas( $post_id, $object );

		if ( ! $sync_areas ) {
			$this->errors->add( 'ptv-area-sync-error', __( 'Item was saved to the PTV, but synchronization of areas to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync legislation.
		$sync_legislation = $this->sync_legislation( $post_id, $object );

		if ( ! $sync_legislation ) {
			$this->errors->add( 'ptv-legislation-sync-error', __( 'Item was saved to the PTV, but synchronization of legislation to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync modified time.
		$sync_modified = $this->sync_modified( $post_id, $object );

		if ( ! $sync_modified ) {
			$this->errors->add( 'ptv-modified-time-sync-error', __( 'Item was saved to the PTV, but synchronization of modified time to translations failed.', 'ptv-for-wordpress' ) );
		}

	}

}

