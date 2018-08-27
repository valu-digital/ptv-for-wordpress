<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Carbon_Fields\Container' ) ) {
	return false;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Register custom fields
 */
class PTV_Custom_Fields {

	/**
	 * @var array
	 */
	private $options;

	/**
	 * Class instance.
	 *
	 * @var PTV_Custom_Fields
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_Custom_Fields
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_Custom_Fields constructor.
	 */
	private function __construct() {

		if ( ! isset( $this->options ) ) {
			$this->options = $this->get_default_options();
		}

		$this->register_fields();
	}

	/**
	 * Set options.
	 *
	 * @param $options
	 */
	function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * Get options.
	 *
	 * @return mixed
	 */
	function get_options() {
		return $this->options;
	}

	/**
	 * Get default options.
	 *
	 * @return array
	 */
	public function get_default_options() {

		return array(

			'weekdays' => array(
				''          => '',
				'Monday'    => __( 'Monday', 'ptv-for-wordpress' ),
				'Tuesday'   => __( 'Tuesday', 'ptv-for-wordpress' ),
				'Wednesday' => __( 'Wednesday', 'ptv-for-wordpress' ),
				'Thursday'  => __( 'Thursday', 'ptv-for-wordpress' ),
				'Friday'    => __( 'Friday', 'ptv-for-wordpress' ),
				'Saturday'  => __( 'Saturday', 'ptv-for-wordpress' ),
				'Sunday'    => __( 'Sunday', 'ptv-for-wordpress' ),
			),

			'signature_quantity' => array(
				'1'  => 1,
				'2'  => 2,
				'3'  => 3,
				'4'  => 4,
				'5'  => 5,
				'6'  => 6,
				'7'  => 7,
				'8'  => 8,
				'9'  => 9,
				'10' => 10,
			),

			'service_hour_types' => array(
				'Standard'  => __( 'Standard', 'ptv-for-wordpress' ),
				'Special'   => __( 'Special', 'ptv-for-wordpress' ),
				'Exception' => __( 'Exception', 'ptv-for-wordpress' ),
			),

			'organization_types' => array(
				'Municipality'         => __( 'Municipality', 'ptv-for-wordpress' ),
				'Company'              => __( 'Company', 'ptv-for-wordpress' ),
				'State'                => __( 'State', 'ptv-for-wordpress' ),
				'RegionalOrganization' => __( 'RegionalOrganization', 'ptv-for-wordpress' ),
				'Organization'         => __( 'Organization', 'ptv-for-wordpress' ),
			),

			'post_statuses' => array(
				'Published' => __( 'Published', 'ptv-for-wordpress' ),
				'Draft'     => __( 'Draft', 'ptv-for-wordpress' ),
				'Deleted'   => __( 'Archived', 'ptv-for-wordpress' ),
			),

			'organization_primary_name' => array(
				'Name'          => __( 'Name', 'ptv-for-wordpress' ),
				'AlternateName' => __( 'AlternateName', 'ptv-for-wordpress' ),
			),

			'attachment_types' => array(
				'Attachment' => __( 'Attachment', 'ptv-for-wordpress' ),
				'Form'       => __( 'Form', 'ptv-for-wordpress' ),
			),

			'phone_number_types' => array(
				'Phone' => __( 'Phone', 'ptv-for-wordpress' ),
				'Sms'   => __( 'Sms', 'ptv-for-wordpress' ),
				'Fax'   => __( 'Fax', 'ptv-for-wordpress' ),
			),

			'document_types' => array(
				'PDF'   => __( 'pdf', 'ptv-for-wordpress' ),
				'DOC'   => __( 'doc/docx', 'ptv-for-wordpress' ),
				'Excel' => __( 'xls/xlsx', 'ptv-for-wordpress' ),
				'rtf'   => __( 'rtf', 'ptv-for-wordpress' ),
				'odt'   => __( 'odt', 'ptv-for-wordpress' ),
			),

			'area_type' => array(
				'WholeCountry'                   => __( 'Whole country', 'ptv-for-wordpress' ),
				'WholeCountryExceptAlandIslands' => __( 'Whole country except Aland Islands', 'ptv-for-wordpress' ),
				'AreaType'                       => __( 'Restricted area', 'ptv-for-wordpress' ),
			),

			'area_types'           => array(
				'Municipality'    => __( 'Municipality', 'ptv-for-wordpress' ),
				'Province'        => __( 'Province', 'ptv-for-wordpress' ),
				'BusinessRegions' => __( 'Business Regions', 'ptv-for-wordpress' ),
				'HospitalRegions' => __( 'Hospital Regions', 'ptv-for-wordpress' ),
			),
			'complex_field_labels' => array(
				'plural_name'   => __( 'items', 'ptv-for-wordpress' ),
				'singular_name' => __( 'new', 'ptv-for-wordpress' ),
			),
		);
	}

	/**
	 * Register fields.
	 */
	function register_fields() {

		$this->register_organization_fields();
		$this->register_service_fields();
		$this->register_echannel_fields();
		$this->register_phone_channel_fields();
		$this->register_web_page_fields();
		$this->register_printable_form_fields();
		$this->register_service_location_fields();

	}

	/**
	 * Register organization fields.
	 */
	function register_organization_fields() {

		Container::make( 'post_meta', 'ptv-organization', __( 'Organization', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-organization' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Organization name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_alternate_name', __( 'Alternate name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null ),
				         Field::make( 'select', 'ptv_display_name_type', __( 'Display name', 'ptv-for-wordpress' ) )
				              ->add_options(
					              $this->options['organization_primary_name']
				              )
				              ->set_default_value( null ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_business_code', __( 'Business code', 'ptv-for-wordpress' ) )
				              ->set_default_value( null ),
				         Field::make( 'complex', 'ptv_email_addresses', __( 'Email addresses', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Phone numbers', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone numbers', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish Service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'is_finnish_service_number',
								                   'value'   => 1,
								                   'compare' => '!=',
							                   ),
						                   )
					                   ),
					              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
						                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
						                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_width( 50 ),
					              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $this->options['phone_number_types'] ),

				              ) )
			         )
		         )
		         ->add_tab( __( 'Electronic invoicings', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_electronic_invoicings', __( 'Electronic invoicings', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', 'operator_code', __( 'Operator code', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 ),
					              Field::make( 'text', 'electronic_invoicing_address', __( 'Electronic invoicing address', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
				              ) )
			         )
		         )
		         ->add_tab( __( 'Web pages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_web_pages', __( 'Web pages', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'text', 'url', __( 'URL address', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 500 )
						                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', '_value', __( 'Web Page Name', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' ),
					              )
				              )
				              ->set_layout( 'tabbed-horizontal' ),
			         )
		         )
		         ->add_tab( __( 'Addresses', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_addresses', __( 'Addresses', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Location' => __( 'Visiting address', 'ptv-for-wordpress' ),
							                   'Postal'   => __( 'Mailing address', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'select', 'sub_type', __( 'Subtype', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Single'        => __( 'Single', 'ptv-for-wordpress' ),
							                   'Street'        => __( 'Street', 'ptv-for-wordpress' ),
							                   'PostOfficeBox' => __( 'Post office box', 'ptv-for-wordpress' ),
							                   'Abroad'        => __( 'Abroad', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'complex', 'street_address', __( 'Street address', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'street', __( 'Street', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'street_number', __( 'Street number', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'post_office', __( 'Post office', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => array( 'Street', 'Single' ),
									                   'compare' => 'IN',
								                   ),
							                   )
						                   ),
						              Field::make( 'complex', 'post_office_box_address', __( 'Post office box', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'post_office_box', __( 'Post office box', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'PostOfficeBox',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
						              Field::make( 'textarea', 'location_abroad', __( 'Location abroad', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'Abroad',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
					              )
				              )
				              ->help_text( __( 'Addresses are common to all translations. Add equal number of addresses to each translation.', 'ptv-for-wordpress' ) )
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'select', 'ptv_organization_type', __( 'Organization type', 'ptv-for-wordpress' ) )
				              ->add_options(
					              $this->options['organization_types']
				              ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['post_statuses'] )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );
	}

	/**
	 * Register service fields.
	 */
	public function register_service_fields() {
		Container::make( 'post_meta', 'ptv-service', __( 'Service', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-service' )
		         ->add_tab( __( 'General Information', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_statutory_service_general_description_id', __( 'General description', 'ptv-for-wordpress' ) )
				              ->set_width( 100 )
				              ->set_default_value( null )
				              ->set_options('ptv_get_general_descriptions'),
				         Field::make( 'text', 'ptv_name', __( 'Service name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_alternate_name', __( 'Service alternate name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'select', 'ptv_type', __( 'Service type', 'ptv-for-wordpress' ) )
				              ->add_options( array(
					              'Service'                    => __( 'Service', 'ptv-for-wordpress' ),
					              'PermissionAndObligation'    => __( 'Permission and obligation', 'ptv-for-wordpress' ),
					              'ProfessionalQualifications' => __( 'Professional qualification', 'ptv-for-wordpress' ),
				              ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_requirements', __( 'Requirements', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_user_instruction', __( 'User instructions', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_dead_line_additional_info', __( 'Dead line additional info', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_type',
							              'value'   => 'Service',
							              'compare' => '!=',
						              ),
					              )
				              ),
				         Field::make( 'textarea', 'ptv_validity_time_additional_info', __( 'Validity time additional info', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_type',
							              'value'   => 'Service',
							              'compare' => '!=',
						              ),
					              )
				              ),
				         Field::make( 'textarea', 'ptv_processing_time_additional_info', __( 'Processing time additional info', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_type',
							              'value'   => 'Service',
							              'compare' => '!=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Service Charge Details', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
				              ->add_options( array(
					              'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
					              'Free'    => __( 'Free', 'ptv-for-wordpress' ),
				              ) )
				              ->set_required( true ),
				         Field::make( 'textarea', 'ptv_charge_type_additional_info', __( 'Charge type additional info', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'ptv_searchable_set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Legislation', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_legislation', __( 'Laws', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'text', 'name', __( 'Name', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' )
						                   ->set_required( true ),
						              Field::make( 'text', 'web_page', __( 'Web Page', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' )
						                   ->set_required( true ),
					              )
				              )
				              ->set_layout( 'tabbed-horizontal' )
				              ->help_text( __( 'Removing legislation element affects other language versions. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Service Channels', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_channels', __( 'Service channel IDs', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'service_channel_id', __( 'Service channel ID', 'ptv-for-wordpress' ) )
						                   ->add_options( 'ptv_get_service_channels' ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Organizations', 'ptv-for-wordpress' ), array(
			         Field::make( 'complex', 'ptv_organizations', __( 'Organizations', 'ptv-for-wordpress' ) )
			              ->setup_labels( $this->options['complex_field_labels'] )
			              ->add_fields(
				              array(
					              Field::make( 'select', 'role_type', __( 'Organization role', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
							                   'Responsible'      => __( 'Responsible', 'ptv-for-wordpress' ),
							                   'Producer'         => __( 'Producer', 'ptv-for-wordpress' ),
							                   'OtherResponsible' => __( 'Other responsible', 'ptv-for-wordpress' ),
						                   )
					                   )
					                   ->set_required( true ),
					              Field::make( 'select', 'provision_type', __( 'Provision Type', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
							                   'SelfProduced'     => __( 'Self produced services', 'ptv-for-wordpress' ),
							                   'PurchaseServices' => __( 'Purchase services', 'ptv-for-wordpress' ),
							                   'Other'            => __( 'Other', 'ptv-for-wordpress' ),
						                   )
					                   )
					                   ->set_required( true ),
					              Field::make( 'select', 'organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
					                   ->add_options( 'ptv_get_organizations' )
					                   ->set_default_value( ptv_get_organization_id() )
					                   ->set_required( true ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
					                   ->set_default_value( '' )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				              )
			              )
			              ->set_required( true )
			              ->help_text( __( 'Add at least one service producer and one responsible organization. ', 'ptv-for-wordpress' ) ),
		         ) )
		         ->add_tab( __( 'Service vouchers', 'ptv-for-wordpress' ), array(
			         Field::make( 'checkbox', 'ptv_service_vouchers_in_use', __( 'Service vouchers in use?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),

			         Field::make( 'complex', 'ptv_service_vouchers', __( 'Service vouchers', 'ptv-for-wordpress' ) )
			              ->setup_labels( $this->options['complex_field_labels'] )
			              ->add_fields(
				              array(
					              Field::make( 'text', '_value', __( 'Value', 'ptv-for-wordpress' ) ),
					              Field::make( 'text', 'url', __( 'Url', 'ptv-for-wordpress' ) ),
					              Field::make( 'textarea', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
					                   ->set_attribute( 'maxLength', 2500 )
					                   ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				              )
			              )
			              ->set_required( true )
			              ->set_conditional_logic(
				              array(
					              array(
						              'field'   => 'ptv_service_vouchers_in_use',
						              'value'   => 1,
						              'compare' => '=',
					              ),
				              )
			              ),
		         ) )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
			         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
			              ->set_attribute( 'readOnly', 1 ),
			         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
			              ->set_attribute( 'readOnly', 1 ),
			         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
			              ->add_options( 'ptv_get_organizations' )
			              ->set_default_value( ptv_get_organization_id() )
			              ->set_required( true ),
			         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
			              ->add_options( $this->options['post_statuses'] )
			              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         Field::make( 'select', 'ptv_funding_type', __( 'Funding type', 'ptv-for-wordpress' ) )
			              ->add_options( array(
				              'PubliclyFunded' => __( 'Publicly funded', 'ptv-for-wordpress' ),
				              'MarketFunded'   => __( 'Market funded', 'ptv-for-wordpress' ),
			              ) ),
		         ) );
	}


	/**
	 * Register echannel fields.
	 */
	public function register_echannel_fields() {

		Container::make( 'post_meta', 'ptv-echannel', __( 'Echannel', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-echannel' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Service channel name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_urls', __( 'Url', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Signatures and authentication', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_requires_authentication', __( 'Requires authentication', 'ptv-for-wordpress' ) )
				              ->add_options( array(
					              0 => __( 'No', 'ptv-for-wordpress' ),
					              1 => __( 'Yes', 'ptv-for-wordpress' ),
				              ) ),
				         Field::make( 'select', 'ptv_requires_signature', __( 'Requires signature', 'ptv-for-wordpress' ) )
				              ->add_options( array(
					              0 => __( 'No', 'ptv-for-wordpress' ),
					              1 => __( 'Yes', 'ptv-for-wordpress' ),
				              ) )
				              ->set_width( 50 ),
				         Field::make( 'select', 'ptv_signature_quantity', __( 'Signature quantity', 'ptv-for-wordpress' ) )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_requires_signature',
							              'value'   => 1,
							              'compare' => '=',
						              ),
					              )
				              )
				              ->add_options( $this->options['signature_quantity'] ),

			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Support', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_support_phones', __( 'Support phones', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
						              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'is_finnish_service_number',
									                   'value'   => 1,
									                   'compare' => '!=',
								                   ),
							                   )
						                   )
						                   ->set_default_value( null )
						                   ->set_required( true ),
						              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_required( true ),
						              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),

						              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
							                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
							                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
						                   ) )
						                   ->set_width( 50 ),
						              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'service_charge_type',
									                   'value'   => 'Other',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_width( 50 )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 150 )
						                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              )
				              ),
				         Field::make( 'complex', 'ptv_support_emails', __( 'Support emails', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) )
			         )
		         )
		         ->add_tab( __( 'Attachments', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_attachments', __( 'Attachments', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', 'name', __( 'Name', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'text', 'url', __( 'Url', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_required( true )
					                   ->set_attribute( 'maxLength', 500 )
					                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
					              Field::make( 'text', 'description', __( 'Description', 'ptv-for-wordpress' ) )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
					                   ->set_options(
						                   $this->options['attachment_types']
					                   )
					                   ->set_width( 50 ),
				              ) ),
			         )
		         )
		         ->add_tab( __( 'Service hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service hours', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $this->options['service_hour_types'] ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid from', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid to', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening hours', 'ptv-for-wordpress' ) )
					                   ->setup_labels( $this->options['complex_field_labels'] )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] ),
							                   Field::make( 'select', 'day_to', __( 'Day to', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_storage_format( 'H:i:s' )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) ),
							                   Field::make( 'time', 'to', __( 'Time To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) )
							                        ->set_storage_format( 'H:i:s' ),
							                   Field::make( 'select', 'is_extra', __( 'Is extra?', 'ptv-for-wordpress' ) )
							                        ->add_options( array(
								                        0 => __( 'No', 'ptv-for-wordpress' ),
								                        1 => __( 'Yes', 'ptv-for-wordpress' ),
							                        ) )
							                        ->set_width( 20 )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Standard',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
						                   )
					                   ),
					              Field::make( 'select', 'is_closed', __( 'Is closed', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'service_hour_type',
								                   'value'   => 'Exception',
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
				              ) )
				              ->set_layout( 'tabbed-horizontal' ),
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'EChannel' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['post_statuses'] )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );
	}

	/**
	 * Register phone channel fields.
	 */
	public function register_phone_channel_fields() {

		Container::make( 'post_meta', 'ptv-phone', __( 'Phone Channel', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-phone' )
		         ->add_tab( __( 'General Information', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Service channel name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'complex', 'ptv_support_emails', __( 'Support emails', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) ),
				         Field::make( 'text', 'ptv_urls', __( 'Url', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Phone Number', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone numbers', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'is_finnish_service_number',
								                   'value'   => 1,
								                   'compare' => '!=',
							                   ),
						                   )
					                   )
					                   ->set_required( true ),
					              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 )
					                   ->set_required( true ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
						                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
						                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_width( 50 ),
					              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $this->options['phone_number_types'] ),

				              ) )
				              ->set_required( true )

			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'ptv_searchable_set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Service hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service hours', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $this->options['service_hour_types'] ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid from', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid to', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening hours', 'ptv-for-wordpress' ) )
					                   ->setup_labels( $this->options['complex_field_labels'] )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] ),
							                   Field::make( 'select', 'day_to', __( 'Day to', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_storage_format( 'H:i:s' )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) ),
							                   Field::make( 'time', 'to', __( 'Time To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) )
							                        ->set_storage_format( 'H:i:s' ),
							                   Field::make( 'select', 'is_extra', __( 'Is extra?', 'ptv-for-wordpress' ) )
							                        ->add_options( array(
								                        0 => __( 'No', 'ptv-for-wordpress' ),
								                        1 => __( 'Yes', 'ptv-for-wordpress' ),
							                        ) )
							                        ->set_width( 20 )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Standard',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
						                   )
					                   ),
					              Field::make( 'select', 'is_closed', __( 'Is closed', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'service_hour_type',
								                   'value'   => 'Exception',
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
				              ) )
				              ->set_layout( 'tabbed-horizontal' ),
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
			         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
			              ->set_attribute( 'readOnly', 1 ),
			         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
			              ->set_attribute( 'readOnly', 1 ),
			         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
			              ->add_options( 'ptv_get_organizations' )
			              ->set_default_value( ptv_get_organization_id() )
			              ->set_required( true ),
			         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
			              ->add_options( $this->options['post_statuses'] )
			              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
			              ->set_default_value(
				              'Phone'
			              ),
		         ) );
	}

	/**
	 * Register web page fields.
	 */
	function register_web_page_fields() {

		Container::make( 'post_meta', 'ptv-web-page', __( 'Web page', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-web-page' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Service channel name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_urls', __( 'Url', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Support', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_support_phones', __( 'Support phones', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
						              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'is_finnish_service_number',
									                   'value'   => 1,
									                   'compare' => '!=',
								                   ),
							                   )
						                   )
						                   ->set_default_value( null )
						                   ->set_required( true ),
						              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_required( true ),
						              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
						              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
							                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
							                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
						                   ) )
						                   ->set_width( 50 ),
						              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'service_charge_type',
									                   'value'   => 'Other',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_width( 50 )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 150 )
						                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              )
				              ),
				         Field::make( 'complex', 'ptv_support_emails', __( 'Support emails', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) )
			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'ptv_searchable_set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'WebPage' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['post_statuses'] )
				              ->help_text( __( 'Changing publishing status affects all translations.', 'ptv-for-wordpress' ) ),
			         )
		         );
	}

	/**
	 * Register printable form fields.
	 */
	public function register_printable_form_fields() {
		Container::make( 'post_meta', 'ptv-printable-form', __( 'Printable Form', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-printable-form' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Form Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_form_identifier', __( 'Form Identifier', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Files', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_channel_urls', __( 'Document details', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'select', 'type', __( 'Document Type', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->add_options( $this->options['document_types'] )
					                   ->set_required( true ),
					              Field::make( 'text', '_value', __( 'Url', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_required( true )
					                   ->set_attribute( 'maxLength', 500 )
					                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
				              ) )
				              ->set_required( true )
			         )

		         )
		         ->add_tab( __( 'Delivery address', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_form_receiver', __( 'Form receiver', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'complex', 'ptv_delivery_address', __( 'Delivery addresses', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'select', 'sub_type', __( 'Subtype', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Street'        => __( 'Street', 'ptv-for-wordpress' ),
							                   'PostOfficeBox' => __( 'Post office box', 'ptv-for-wordpress' ),
							                   'NoAddress'     => __( 'No Address', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'complex', 'street_address', __( 'Street address', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'street', __( 'Street', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'street_number', __( 'Street number', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'post_office', __( 'Post office', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => array( 'Street', 'Single' ),
									                   'compare' => 'IN',
								                   ),
							                   )
						                   ),
						              Field::make( 'complex', 'post_office_box_address', __( 'Post office box', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'post_office_box', __( 'Post office box', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'PostOfficeBox',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
						              Field::make( 'textarea', 'delivery_address_in_text', __( 'Delivery address in text', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'NoAddress',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
					              )
				              )
				              ->help_text( __( 'Addresses are common to all translations. Add equal number of addresses to each translation.', 'ptv-for-wordpress' ) )
				              ->set_max( 1 )
			         )
		         )
		         ->add_tab( __( 'Support', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_support_phones', __( 'Support phones', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
						              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'is_finnish_service_number',
									                   'value'   => 1,
									                   'compare' => '!=',
								                   ),
							                   )
						                   )
						                   ->set_default_value( null )
						                   ->set_required( true ),
						              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_required( true ),
						              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
						              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
							                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
							                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
						                   ) )
						                   ->set_width( 50 ),
						              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'service_charge_type',
									                   'value'   => 'Other',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_width( 50 )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 150 )
						                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              )
				              ),
				         Field::make( 'complex', 'ptv_support_emails', __( 'Support emails', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) )
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Attachments', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_attachments', __( 'Attachments', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', 'name', __( 'Name', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'text', 'url', __( 'Url', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 500 )
					                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) )
					                   ->set_required( true ),
					              Field::make( 'text', 'description', __( 'Description', 'ptv-for-wordpress' ) )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				              ) )
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'PrintableForm' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['post_statuses'] )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );
	}

	/**
	 * Register service location fields.
	 */
	public function register_service_location_fields() {
		Container::make( 'post_meta', 'ptv-service-location', __( 'Service Location Channel', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-service-location' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_name', __( 'Service channel name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_short_description', __( 'Short description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Addresses', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_addresses', __( 'Addresses', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Location' => __( 'Visiting address', 'ptv-for-wordpress' ),
							                   'Postal'   => __( 'Mailing address', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'select', 'sub_type', __( 'Subtype', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Single'        => __( 'Single', 'ptv-for-wordpress' ),
							                   'Street'        => __( 'Street', 'ptv-for-wordpress' ),
							                   'PostOfficeBox' => __( 'Post office box', 'ptv-for-wordpress' ),
							                   'Abroad'        => __( 'Abroad', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'complex', 'street_address', __( 'Street address', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'street', __( 'Street', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'street_number', __( 'Street number', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'post_office', __( 'Post office', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
								                   Field::make( 'select', 'municipality', __( 'Municipality', 'ptv-for-wordpress' ) )
								                        ->add_options(
									                        ptv_get_municipalities()
								                        )
								                        ->set_conditional_logic(
									                        array(
										                        array(
											                        'field'   => 'parent.type',
											                        'value'   => 'Location',
											                        'compare' => '=',
										                        ),
									                        )
								                        ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => array( 'Street', 'Single' ),
									                   'compare' => 'IN',
								                   ),
							                   )
						                   ),
						              Field::make( 'complex', 'post_office_box_address', __( 'Post office box', 'ptv-for-wordpress' ) )
						                   ->setup_labels( $this->options['complex_field_labels'] )
						                   ->add_fields(
							                   array(
								                   Field::make( 'text', 'post_office_box', __( 'Post office box', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'postal_code', __( 'Postal code', 'ptv-for-wordpress' ) )->set_required( true ),
								                   Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
							                   )
						                   )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'PostOfficeBox',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
						              Field::make( 'textarea', 'location_abroad', __( 'Location abroad', 'ptv-for-wordpress' ) )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'sub_type',
									                   'value'   => 'Abroad',
									                   'compare' => '=',
								                   ),
							                   )
						                   ),
					              )
				              )
				              ->set_required( true )
				              ->help_text( __( 'Addresses are common to all translations. Visiting address is required.', 'ptv-for-wordpress' ) )
			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'ptv_searchable_set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['area_type'] )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $this->options['area_types'] ),
						              Field::make( 'set', 'municipality', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Municipality',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'province', __( 'Select provinces', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_provinces()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'Province',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'business_regions', __( 'Select business regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_business_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'BusinessRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
						              Field::make( 'set', 'hospital_regions', __( 'Select hospital regions', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_hospital_regions()
						                   )
						                   ->limit_options( 5 )
						                   ->set_conditional_logic(
							                   array(
								                   array(
									                   'field'   => 'type',
									                   'value'   => 'HospitalRegions',
									                   'compare' => '=',
								                   ),
							                   )
						                   )
						                   ->set_required( true ),
					              )
				              )
				              ->set_required( true )
				              ->set_conditional_logic(
					              array(
						              array(
							              'field'   => 'ptv_area_type',
							              'value'   => 'AreaType',
							              'compare' => '=',
						              ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Service hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service hours', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $this->options['service_hour_types'] ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid from', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid to', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_storage_format( get_option( 'date_format' ) )
					                   ->set_input_format( get_option( 'date_format' ), get_option( 'date_format' ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening hours', 'ptv-for-wordpress' ) )
					                   ->setup_labels( $this->options['complex_field_labels'] )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] ),
							                   Field::make( 'select', 'day_to', __( 'Day to', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $this->options['weekdays'] )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time from', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_storage_format( 'H:i:s' )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) ),
							                   Field::make( 'time', 'to', __( 'Time To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->set_input_format( 'H:i:s', 'H:i:ss' )
							                        ->set_picker_options( array( 'time_24hr' => true ) )
							                        ->set_storage_format( 'H:i:s' ),
							                   Field::make( 'select', 'is_extra', __( 'Is extra?', 'ptv-for-wordpress' ) )
							                        ->add_options( array(
								                        0 => __( 'No', 'ptv-for-wordpress' ),
								                        1 => __( 'Yes', 'ptv-for-wordpress' ),
							                        ) )
							                        ->set_width( 20 )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Standard',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
						                   )
					                   ),
					              Field::make( 'select', 'is_closed', __( 'Is closed', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'service_hour_type',
								                   'value'   => 'Exception',
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
				              ) )
				              ->set_layout( 'tabbed-horizontal' ),
			         )
		         )
		         ->add_tab( __( 'Phones and faxes', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone numbers', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'is_finnish_service_number',
								                   'value'   => 1,
								                   'compare' => '!=',
							                   ),
						                   )
					                   )
					                   ->set_required( true ),
					              Field::make( 'text', 'number', __( 'Number', 'ptv-for-wordpress' ) )
					                   ->set_width( 25 )
					                   ->set_required( true ),
					              Field::make( 'text', 'additional_information', __( 'Additional information', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'service_charge_type', __( 'Service charge type', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
						                   'Free'    => __( 'Free', 'ptv-for-wordpress' ),
						                   'Other'   => __( 'Other', 'ptv-for-wordpress' ),
					                   ) )
					                   ->set_width( 50 ),
					              Field::make( 'text', 'charge_description', __( 'Service charge details', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $this->options['phone_number_types'] ),

				              ) )
			         )
		         )
		         ->add_tab( __( 'Contact', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_web_pages', __( 'Web pages', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields(
					              array(
						              Field::make( 'text', 'url', __( 'URL address', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 500 )
						                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', '_value', __( 'Web Page Name', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' ),
					              )
				              )
				              ->set_layout( 'tabbed-horizontal' ),
				         Field::make( 'complex', 'ptv_emails', __( 'Emails', 'ptv-for-wordpress' ) )
				              ->setup_labels( $this->options['complex_field_labels'] )
				              ->add_fields( array(
					              Field::make( 'text', '_value', __( 'Email', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				              ) )
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'text', 'ptv_modified', __( 'Modified', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'ServiceLocation' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $this->options['post_statuses'] )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );
	}

}

PTV_Custom_Fields::get_instance();
