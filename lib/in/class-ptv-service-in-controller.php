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
			$this->errors->add( 'ptv-invalid-properties', __( 'Request contains invalid properties.', 'ptv-for-wordpress' ) );

			return;
		}

		// Create new service.
		$new_service = $this->api->get_service_api()->create_service( $request );

		if ( is_wp_error( $new_service ) ) {
			$this->errors->add( 'ptv-creation-failed', __( 'Failed to create the item to the PTV.', 'ptv-for-wordpress' ), $new_service->get_error_data() );

			return;
		}

		// Sync fields.
		$this->sync( $post_id, $new_service );

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

		$this->sync( $post_id, $updated_service );

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

		// Set languages.
		$request->set_languages( $this->get_languages( $post_id ) );

		// Set target groups.
		$request->set_target_groups( $this->get_target_groups( $post_id ) );

		// Set ontology terms.
		$request->set_ontology_terms( $this->get_ontology_terms( $post_id ) );

		// Set life events.
		$request->set_life_events( $this->get_life_events( $post_id ) );

		// Set service classes.
		$request->set_service_classes( $this->get_service_classes( $post_id ) );

		// Set ontology terms.
		$request->set_industrial_classes( $this->get_industrial_classes( $post_id ) );

		// Set service organizations.
		$request->set_service_organizations( $this->get_service_organizations( $post_id ) );

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
				'Name'          => 'ptv_service_names_name',
				'AlternateName' => 'ptv_service_names_alternate_name',
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
				'ShortDescription'             => 'ptv_service_descriptions_short_description',
				'Description'                  => 'ptv_service_descriptions_description',
				'ServiceUserInstruction'       => 'ptv_service_descriptions_service_user_instruction',
				'ProcessingTimeAdditionalInfo' => 'ptv_service_descriptions_processing_time_additional_info',
				'DeadLineAdditionalInfo'       => 'ptv_service_descriptions_dead_line_additional_info',
				'ChargeTypeAdditionalInfo'     => 'ptv_service_descriptions_charge_type_additional_info',
				'ValidityTimeAdditionalInfo'   => 'ptv_service_descriptions_validity_time_additional_info',
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

		$result = carbon_get_post_meta( $post_id, 'ptv_type' );

		if ( empty( $result ) ) {
			return null;
		}

		return (string) $result;

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

		$result = array();

		$post_translations = $this->get_post_translations( $post_id );

		foreach ( $post_translations as $lang => $id ) {
			$data['type']  = 'Requirements';
			$data['value'] = carbon_get_post_meta( $id, 'ptv_requirements' );
			$result[]      = $this->prepare_localized_list_item( $data, $lang );
		}

		if ( empty( $result ) ) {
			return null;
		}

		return $result;

	}


	/**
	 * Get service charge type.
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function get_service_charge_type( $post_id ) {

		$result = carbon_get_post_meta( $post_id, 'ptv_service_charge_type' );

		if ( empty( $result ) ) {
			return null;
		}

		return (string) $result;

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
	 * Get service organizations.
	 *
	 * @param $post_id
	 *
	 * @return mixed|null
	 */
	public function get_service_organizations( $post_id ) {

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

		foreach ( $organizations as $organization ) {
			$key                          = md5( $organization['organization_id'] . $organization['role_type'] . $organization['provision_type'] );
			$organization_objects[ $key ] = new PTV_Service_Organization( $organization );

		}

		foreach ( $organization_objects as $index => $organization_object ) {

			$localized_additional_information = array();

			foreach ( $post_translations as $lang => $id ) {

				$localized_organizations = carbon_get_post_meta( $id, 'ptv_organizations' );

				if ( ! $localized_organizations || ! is_array( $localized_organizations ) ) {
					return null;
				}


				$localized_organizations_with_key = array();

				foreach ( $localized_organizations as $localized_organization ) {
					$key                                      = md5( $localized_organization['organization_id'] . $localized_organization['role_type'] . $localized_organization['provision_type'] );
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

		return $this->get_taxonomy_terms( $post_id, 'ptv-ontology-terms' );

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

				$ptv_id = get_term_meta( $term, '_ptv_uri', true );

				if ( $ptv_id ) {
					$term_uris[] = $ptv_id;
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
			->set_url( $data['url'] )
			->set_value( $data['value'] )
			->set_language( $lang );

		return $web_page;
	}

	/**
	 * Sync service and channel relations.
	 *
	 * @param $post_id
	 *
	 * @return bool|PTV_Service
	 */
	public function sync_service_and_channel_relations( $post_id ) {

		if ( ! $post_id ) {
			return false;
		}

		$id = $this->get_translation_group_id( $post_id );

		$service_channels = carbon_get_post_meta( $post_id, 'ptv_service_channels' );

		if ( $service_channels ) {

			$service_channel_objects = array();
			foreach ( $service_channels as $service_channel ) {

				$service_channel_object = new PTV_Service_Service_Channel_In_Base();
				$service_channel_object->set_service_channel_id( $service_channel['service_channel_id'] );

				$service_channel_objects[] = $service_channel_object;

			}

			if ( $service_channel_objects ) {
				$service_and_channel_relations = new PTV_Service_And_Channel_Relation_In_Base();
				$service_and_channel_relations->set_channel_relations( $service_channel_objects );

				$sync_relations = $this->api->get_service_api()->update_service_and_channel_by_service_id( $id, $service_and_channel_relations );

				if ( is_wp_error( $sync_relations ) ) {
					return false;
				}
			}
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

		if ( empty( $organization_objects ) || ! is_array( $organization_objects ) ) {
			return false;
		}

		$lang = $this->get_post_language( $post_id );

		$organizations = array();

		foreach ( $organization_objects as $organization_object ) {

			if ( ! empty( $organization_object ) ) {

				$additional_informations = $organization_object->get_additional_information();

				$has_current_language = false;

				if ( empty( $additional_informations ) || ! is_array( $additional_informations ) ) {
					return false;
				}

				foreach ( $additional_informations as $additional_information ) {
					if ( $lang === $additional_information->get_language() ) {
						$has_current_language = true;
						break;
					}
				}

				if ( $has_current_language ) {
					$organization                           = array();
					$organization['organization_id']        = $organization_object->get_organization_id();
					$organization['role_type']              = $organization_object->get_role_type();
					$organization['provision_type']         = $organization_object->get_provision_type();
					$organization['additional_information'] = ptv_get_localized_value( $organization_object->get_additional_information(), $lang );

					if ( ! empty( $organization ) ) {
						$organizations[] = $organization;
					}
				}
			}
		}

		if ( ! empty( $organizations ) ) {
			carbon_set_post_meta( $post_id, 'ptv_organizations', $organizations );
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
			carbon_set_post_meta( $id, 'ptv_service_charge_type', $object->get_service_charge_type() );
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

		// Sync service channel relations.
		$sync_relations = $this->sync_service_and_channel_relations( $post_id );

		if ( ! $sync_relations ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of service and channel relations to translations failed.', 'ptv-for-wordpress' ) );
		}

		// Sync organizations.
		$sync_organizations = $this->sync_organizations( $post_id, $object );

		if ( ! $sync_organizations ) {
			$this->errors->add( 'ptv-relations-sync-error', __( 'Item was saved to the PTV, but synchronization of organizations to translations failed.', 'ptv-for-wordpress' ) );
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

	}

}

