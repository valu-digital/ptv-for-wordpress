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
function ptv_register_custom_fields() {

	$ptv_weekdays = array(
		''          => '',
		'Monday'    => __( 'Monday', 'ptv-for-wordpress' ),
		'Tuesday'   => __( 'Tuesday', 'ptv-for-wordpress' ),
		'Wednesday' => __( 'Wednesday', 'ptv-for-wordpress' ),
		'Thursday'  => __( 'Thursday', 'ptv-for-wordpress' ),
		'Friday'    => __( 'Friday', 'ptv-for-wordpress' ),
		'Saturday'  => __( 'Saturday', 'ptv-for-wordpress' ),
		'Sunday'    => __( 'Sunday', 'ptv-for-wordpress' ),
	);

	$ptv_signature_quantity = array(
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
	);

	$ptv_service_hour_types = array(
		'Standard'  => __( 'Standard', 'ptv-for-wordpress' ),
		'Special'   => __( 'Special', 'ptv-for-wordpress' ),
		'Exception' => __( 'Exception', 'ptv-for-wordpress' ),
	);

	$ptv_organization_types = array(
		'State'                => __( 'State', 'ptv-for-wordpress' ),
		'Municipality'         => __( 'Municipality', 'ptv-for-wordpress' ),
		'RegionalOrganization' => __( 'RegionalOrganization', 'ptv-for-wordpress' ),
		'Organization'         => __( 'Organization', 'ptv-for-wordpress' ),
		'Company'              => __( 'Company', 'ptv-for-wordpress' ),
	);

	$ptv_post_statuses = array(
		'Published' => __( 'Published', 'ptv-for-wordpress' ),
		'Draft'     => __( 'Draft', 'ptv-for-wordpress' ),
		'Deleted'   => __( 'Archived', 'ptv-for-wordpress' ),
	);

	$ptv_organization_primary_name = array(
		'Name'          => __( 'Name', 'ptv-for-wordpress' ),
		'AlternateName' => __( 'AlternateName', 'ptv-for-wordpress' ),
	);

	$ptv_attachment_types = array(
		'Attachment' => __( 'Attachment', 'ptv-for-wordpress' ),
		'Form'       => __( 'Form', 'ptv-for-wordpress' ),
	);

	$ptv_phone_number_types = array(
		'Phone' => __( 'Phone', 'ptv-for-wordpress' ),
		'Sms'   => __( 'Sms', 'ptv-for-wordpress' ),
		'Fax'   => __( 'Fax', 'ptv-for-wordpress' ),
	);

	$ptv_document_types = array(
		'PDF'   => __( 'pdf', 'ptv-for-wordpress' ),
		'DOC'   => __( 'doc/docx', 'ptv-for-wordpress' ),
		'Excel' => __( 'xls/xlsx', 'ptv-for-wordpress' ),
		'rtf'   => __( 'rtf', 'ptv-for-wordpress' ),
		'odt'   => __( 'odt', 'ptv-for-wordpress' ),
	);

	$ptv_area_type = array(
		'WholeCountry'                   => __( 'Whole country', 'ptv-for-wordpress' ),
		'WholeCountryExceptAlandIslands' => __( 'Whole country except Aland Islands', 'ptv-for-wordpress' ),
		'AreaType'                       => __( 'Restricted area', 'ptv-for-wordpress' ),
	);

	$ptv_area_types = array(
		'Municipality' => __( 'Municipality', 'ptv-for-wordpress' ),
	);


	$carbon_fields_organization =
		Container::make( 'post_meta', __( 'Organization', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-organization' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_organization_names_name', __( 'Service Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_organization_names_alternate_name', __( 'Alternate Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null ),
				         Field::make( 'select', 'ptv_display_name_type', __( 'Display Name', 'ptv-for-wordpress' ) )
				              ->add_options(
					              $ptv_organization_primary_name
				              )
				              ->set_default_value( null ),
				         Field::make( 'textarea', 'ptv_organization_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_business_code', __( 'Business code', 'ptv-for-wordpress' ) )
				              ->set_default_value( null ),
			         )
		         )
		         ->add_tab( __( 'Phone Numbers', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone Numbers', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix Number', 'ptv-for-wordpress' ) )
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
					              Field::make( 'select', 'service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
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
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $ptv_phone_number_types ),

				              ) )
			         )
		         )
		         ->add_tab( __( 'Web Pages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_web_pages', __( 'Web Pages', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'text', 'url', __( 'URL address', 'ptv-for-wordpress' ) )
						                   ->set_width( 50 )
						                   ->set_default_value( '' )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 500 )
						                   ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
					              )
				              )
				              ->set_layout( 'tabbed-horizontal' ),
			         )
		         )
		         ->add_tab( __( 'Addresses', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_addresses', __( 'Addresses', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Postal'   => __( 'Mailing Address', 'ptv-for-wordpress' ),
							                   'Visiting' => __( 'Visiting Address', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'text', 'street_address', __( 'Street Address', 'ptv-for-wordpress' ) )
						                   ->set_width( 30 )
						                   ->set_required( true ),
						              Field::make( 'text', 'street_number', __( 'Street Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 ),
						              Field::make( 'text', 'postal_code', __( 'Postal Code', 'ptv-for-wordpress' ) )
						                   ->set_width( 20 )
						                   ->set_required( true ),
						              Field::make( 'text', 'post_office_box', __( 'Post Office Box', 'ptv-for-wordpress' ) )
						                   ->set_width( 15 ),
						              Field::make( 'text', 'country', __( 'Country', 'ptv-for-wordpress' ) )
						                   ->set_width( 10 ),
						              Field::make( 'textarea', 'additional_informations', __( 'Additional Informations', 'ptv-for-wordpress' ) ),
					              )
				              )
				              ->help_text( __( 'Addresses are common to all translations. Add equal number of addresses to each translation.', 'ptv-for-wordpress' ) )
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_type', __( 'Organization type', 'ptv-for-wordpress' ) )
				              ->add_options(
					              $ptv_organization_types
				              ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_post_statuses )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );

	$carbon_fields_service =
		Container::make( 'post_meta', __( 'Service', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-service' )
		         ->add_tab( __( 'General Information', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_names_name', __( 'Service Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_names_alternate_name', __( 'Service Alternate Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_requirements', __( 'Requirements', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_descriptions_service_user_instruction', __( 'User Instructions', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Service Charge Details', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
				              ->add_options( array(
					              'Charged' => __( 'Charged', 'ptv-for-wordpress' ),
					              'Free'    => __( 'Free', 'ptv-for-wordpress' ),
				              ) )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_area_type )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $ptv_area_types ),
						              Field::make( 'set', 'municipalities', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 ),
					              )
				              )
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
		         ->add_tab( __( 'Service Channels', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_channels', __( 'Service Channel IDs', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'service_channel_id', __( 'Service Channel ID', 'ptv-for-wordpress' ) )
						                   ->add_options( 'ptv_get_service_channels' ),
					              )
				              ),
			         )
		         )
		         ->add_tab( __( 'Organizations', 'ptv-for-wordpress' ), array(
			         Field::make( 'complex', 'ptv_organizations', __( 'Organizations', 'ptv-for-wordpress' ) )
			              ->add_fields(
				              array(
					              Field::make( 'select', 'role_type', __( 'Organization Role', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
							                   'Responsible' => __( 'Responsible', 'ptv-for-wordpress' ),
							                   'Producer'    => __( 'Producer', 'ptv-for-wordpress' ),
						                   )
					                   ),
					              Field::make( 'select', 'provision_type', __( 'Provision Type', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
							                   'SelfProduced'     => __( 'Self Produced Services', 'ptv-for-wordpress' ),
							                   'VoucherServices'  => __( 'Voucher Services', 'ptv-for-wordpress' ),
							                   'PurchaseServices' => __( 'Purchase Services', 'ptv-for-wordpress' ),
							                   'Other'            => __( 'Other', 'ptv-for-wordpress' ),
						                   )
					                   ),
					              Field::make( 'select', 'organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
					                   ->add_options(
						                   'ptv_get_organizations'
					                   )
					                   ->set_default_value( ptv_get_organization_id() ),
					              Field::make( 'text', 'additional_information', __( 'Additional Information', 'ptv-for-wordpress' ) )
					                   ->set_default_value( '' )
					                   ->set_attribute( 'maxLength', 150 )
					                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				              )
			              )
			              ->set_required( true ),
		         ) )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
			         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
			              ->set_attribute( 'readOnly', 1 ),
			         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
			              ->add_options( $ptv_post_statuses )
			              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         Field::make( 'select', 'ptv_type', __( 'Service type', 'ptv-for-wordpress' ) )
			              ->add_options( array(
				              'Service'                 => __( 'Service', 'ptv-for-wordpress' ),
				              'PermissionAndObligation' => __( 'Permission and Obligation', 'ptv-for-wordpress' ),
			              ) ),
		         ) );


	$carbon_fields_echannel =
		Container::make( 'post_meta', 'Echannel' )
		         ->where( 'post_type', '=', 'ptv-echannel' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_channel_names_name', __( 'Service Channel Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_channel_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
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
					              '' => __( '- Select -', 'ptv-for-wordpress' ),
					              0  => __( 'No', 'ptv-for-wordpress' ),
					              1  => __( 'Yes', 'ptv-for-wordpress' ),
				              ) )
				              ->set_required( true ),
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
				              ->add_options( $ptv_signature_quantity ),

			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_area_type )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $ptv_area_types ),
						              Field::make( 'set', 'municipalities', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 ),
					              )
				              )
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
				         Field::make( 'complex', 'ptv_support_phones', __( 'Support Phones', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
						              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
						              Field::make( 'text', 'prefix_number', __( 'Prefix Number', 'ptv-for-wordpress' ) )
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
						              Field::make( 'select', 'service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
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
				         Field::make( 'text', 'ptv_support_emails', __( 'Email', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Attachments', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_attachments', __( 'Attachments', 'ptv-for-wordpress' ) )
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
						                   $ptv_attachment_types
					                   )
					                   ->set_width( 50 ),
				              ) ),
			         )
		         )
		         ->add_tab( __( 'Service Hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service Hours', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service Hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $ptv_service_hour_types ),
					              Field::make( 'text', 'additional_information', __( 'Additional Information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid From', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid To', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening Hours', 'ptv-for-wordpress' ) )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day From', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays ),
							                   Field::make( 'select', 'day_to', __( 'Day To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time From', 'ptv-for-wordpress' ) )
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
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'EChannel' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_post_statuses )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );

	$carbon_fields_phone_channel =
		Container::make( 'post_meta', __( 'Phone Channel', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-phone' )
		         ->add_tab( __( 'General Information', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_channel_names_name', __( 'Service Channel Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_channel_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_support_emails', __( 'Support Email', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_urls', __( 'Url', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 500 )
				              ->help_text( __( 'Max length 500 characters. ', 'ptv-for-wordpress' ) . __( 'Please start URL address with http:// or https://.', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Phone Number', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone Numbers', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix Number', 'ptv-for-wordpress' ) )
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
					                   ->set_width( 25 )
					                   ->set_required( true ),
					              Field::make( 'select', 'service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
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
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $ptv_phone_number_types ),

				              ) )
				              ->set_required( true )

			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_area_type )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $ptv_area_types ),
						              Field::make( 'set', 'municipalities', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 ),
					              )
				              )
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
		         ->add_tab( __( 'Service Hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service Hours', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service Hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $ptv_service_hour_types ),
					              Field::make( 'text', 'additional_information', __( 'Additional Information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid From', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid To', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening Hours', 'ptv-for-wordpress' ) )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day From', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays ),
							                   Field::make( 'select', 'day_to', __( 'Day To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time From', 'ptv-for-wordpress' ) )
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
			         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
			              ->add_options( $ptv_post_statuses )
			              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
			              ->set_default_value(
				              'Phone'
			              ),
		         ) );

	$carbon_fields_service_location =
		Container::make( 'post_meta', __( 'Service Location Channel', 'ptv-for-wordpress' ) )
		         ->where( 'post_type', '=', 'ptv-service-location' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_channel_names_name', __( 'Service Channel Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_alternate_name', __( 'Service Alternate Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_channel_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Addresses', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_addresses', __( 'Addresses', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( array(
							                   'Postal'   => __( 'Mailing Address', 'ptv-for-wordpress' ),
							                   'Visiting' => __( 'Visiting Address', 'ptv-for-wordpress' ),
						                   ) ),
						              Field::make( 'text', 'street_address', __( 'Street Address', 'ptv-for-wordpress' ) )
						                   ->set_width( 30 )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 100 )
						                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', 'street_number', __( 'Street Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_attribute( 'maxLength', 30 )
						                   ->help_text( __( 'Max length 30 characters. ', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', 'postal_code', __( 'Postal Code', 'ptv-for-wordpress' ) )
						                   ->set_width( 20 )
						                   ->set_required( true ),
						              Field::make( 'text', 'country', __( 'Country', 'ptv-for-wordpress' ) )
						                   ->set_width( 10 ),
						              Field::make( 'textarea', 'additional_informations', __( 'Additional Informations', 'ptv-for-wordpress' ) )
						                   ->set_required( true )
						                   ->set_attribute( 'maxLength', 150 )
						                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              )
				              )
				              ->help_text( __( 'Addresses are common to all translations. Add equal number of addresses to each translation.', 'ptv-for-wordpress' ) )
			         )
		         )
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_area_type )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $ptv_area_types ),
						              Field::make( 'set', 'municipalities', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 ),
					              )
				              )
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
		         ->add_tab( __( 'Service Hours', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_service_hours', __( 'Service Hours', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'select', 'service_hour_type', __( 'Service Hour type', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->add_options( $ptv_service_hour_types ),
					              Field::make( 'text', 'additional_information', __( 'Additional Information', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_attribute( 'maxLength', 100 )
					                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
					              Field::make( 'select', 'valid_for_now', __( 'Valid for now', 'ptv-for-wordpress' ) )
					                   ->add_options( array(
						                   0 => __( 'No', 'ptv-for-wordpress' ),
						                   1 => __( 'Yes', 'ptv-for-wordpress' ),
					                   ) ),
					              Field::make( 'date', 'valid_from', __( 'Valid From', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'date', 'valid_to', __( 'Valid To', 'ptv-for-wordpress' ) )
					                   ->set_width( 40 )
					                   ->set_input_format( 'd.m.Y', 'd.m.Y' )
					                   ->set_conditional_logic(
						                   array(
							                   array(
								                   'field'   => 'valid_for_now',
								                   'value'   => 0,
								                   'compare' => '=',
							                   ),
						                   )
					                   ),
					              Field::make( 'complex', 'opening_hour', __( 'Opening Hours', 'ptv-for-wordpress' ) )
					                   ->add_fields(
						                   array(
							                   Field::make( 'select', 'day_from', __( 'Day From', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays ),
							                   Field::make( 'select', 'day_to', __( 'Day To', 'ptv-for-wordpress' ) )
							                        ->set_width( 20 )
							                        ->add_options( $ptv_weekdays )
							                        ->set_conditional_logic(
								                        array(
									                        array(
										                        'field'   => 'parent.service_hour_type',
										                        'value'   => 'Special',
										                        'compare' => '=',
									                        ),
								                        )
							                        ),
							                   Field::make( 'time', 'from', __( 'Time From', 'ptv-for-wordpress' ) )
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
				         Field::make( 'complex', 'ptv_phone_numbers', __( 'Phone Numbers', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
					              Field::make( 'text', 'prefix_number', __( 'Prefix Number', 'ptv-for-wordpress' ) )
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
					                   ->set_width( 25 )
					                   ->set_required( true ),
					              Field::make( 'select', 'service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
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
					              Field::make( 'select', 'type', __( 'Channel Type', 'ptv-for-wordpress' ) )
					                   ->add_options( $ptv_phone_number_types ),

				              ) )
			         )
		         )
		         ->add_tab( __( 'Contact', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_web_pages', __( 'Web Pages', 'ptv-for-wordpress' ) )
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
				         Field::make( 'text', 'ptv_emails', __( 'Emails', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'ServiceLocation' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_post_statuses )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );


	$carbon_fields_printable_form =
		Container::make( 'post_meta', 'Printable Form' )
		         ->where( 'post_type', '=', 'ptv-printable-form' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_channel_names_name', __( 'Form Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_form_identifier', __( 'Form Identifier', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_channel_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 2500 )
				              ->help_text( __( 'Max length 2500 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Files', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_channel_urls', __( 'Document details', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
					              Field::make( 'select', 'type', __( 'Document Type', 'ptv-for-wordpress' ) )
					                   ->set_width( 50 )
					                   ->add_options( $ptv_document_types )
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
		         ->add_tab( __( 'Delivery Address', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_form_receiver', __( 'Form Receiver', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'complex', 'ptv_delivery_address', __( 'Delivery Address', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
						              Field::make( 'text', 'street_address', __( 'Street Address', 'ptv-for-wordpress' ) )
						                   ->set_width( 30 )
						                   ->set_attribute( 'maxLength', 100 )
						                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', 'street_number', __( 'Street Number', 'ptv-for-wordpress' ) )
						                   ->set_width( 25 )
						                   ->set_attribute( 'maxLength', 30 )
						                   ->help_text( __( 'Max length 30 characters. ', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', 'post_office_box', __( 'Post Office Box', 'ptv-for-wordpress' ) )
						                   ->set_width( 15 )
						                   ->set_attribute( 'maxLength', 100 )
						                   ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
						              Field::make( 'text', 'postal_code', __( 'Postal Code', 'ptv-for-wordpress' ) )
						                   ->set_width( 20 )
						                   ->set_required( true ),
						              Field::make( 'textarea', 'additional_informations', __( 'Delivery informations', 'ptv-for-wordpress' ) )
						                   ->set_attribute( 'maxLength', 150 )
						                   ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
					              )
				              )
				              ->set_max( 1 )
			         )
		         )
		         ->add_tab( __( 'Support', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'complex', 'ptv_support_phones', __( 'Support Phones', 'ptv-for-wordpress' ) )
				              ->add_fields( array(
						              Field::make( 'checkbox', 'is_finnish_service_number', __( 'Is Finnish service number?', 'ptv-for-wordpress' ) )->set_option_value( '1' ),
						              Field::make( 'text', 'prefix_number', __( 'Prefix Number', 'ptv-for-wordpress' ) )
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
						              Field::make( 'select', 'service_charge_type', __( 'Service Charge Type', 'ptv-for-wordpress' ) )
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
				         Field::make( 'text', 'ptv_support_emails', __( 'Email', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
			         )
		         )
		         ->add_tab( __( 'Areas', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'select', 'ptv_area_type', __( 'Area type', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_area_type )
				              ->set_required( true ),
				         Field::make( 'complex', 'ptv_areas', __( 'Areas', 'ptv-for-wordpress' ) )
				              ->add_fields(
					              array(
						              Field::make( 'select', 'type', __( 'Type', 'ptv-for-wordpress' ) )
						                   ->add_options( $ptv_area_types ),
						              Field::make( 'set', 'municipalities', __( 'Select municipalities', 'ptv-for-wordpress' ) )
						                   ->add_options(
							                   ptv_get_municipalities()
						                   )
						                   ->limit_options( 5 ),
					              )
				              )
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
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'PrintableForm' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_post_statuses )
				              ->help_text( __( 'Changing publishing status affects all language versions. After publishing status can be set only as archived.', 'ptv-for-wordpress' ) ),
			         )
		         );

	$carbon_fields_webpage =
		Container::make( 'post_meta', 'Web Page' )
		         ->where( 'post_type', '=', 'ptv-web-page' )
		         ->add_tab( __( 'General', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'text', 'ptv_service_channel_names_name', __( 'Service Channel Name', 'ptv-for-wordpress' ) )
				              ->set_width( 50 )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 100 )
				              ->help_text( __( 'Max length 100 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'text', 'ptv_service_channel_descriptions_short_description', __( 'Short Description', 'ptv-for-wordpress' ) )
				              ->set_default_value( null )
				              ->set_required( true )
				              ->set_attribute( 'maxLength', 150 )
				              ->help_text( __( 'Max length 150 characters. ', 'ptv-for-wordpress' ) ),
				         Field::make( 'textarea', 'ptv_service_channel_descriptions_description', __( 'Description', 'ptv-for-wordpress' ) )
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
		         ->add_tab( __( 'Languages', 'ptv-for-wordpress' ),
			         array(
				         Field::make( 'set', 'ptv_languages', __( 'Service is available in following languages', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_languages' )
				              ->set_required( true ),
			         )
		         )
		         ->add_tab( __( 'Meta', 'ptv-for-wordpress' ), array(
				         Field::make( 'text', 'ptv_id', __( 'PTV ID', 'ptv-for-wordpress' ) )
				              ->set_attribute( 'readOnly', 1 ),
				         Field::make( 'select', 'ptv_organization_id', __( 'Organization', 'ptv-for-wordpress' ) )
				              ->add_options( 'ptv_get_organizations' )
				              ->set_default_value( ptv_get_organization_id() )
				              ->set_required( true ),
				         Field::make( 'hidden', 'ptv_service_channel_type', __( 'Service channel type', 'ptv-for-wordpress' ) )
				              ->set_default_value( 'WebPage' ),
				         Field::make( 'select', 'ptv_publishing_status', __( 'Publishing status', 'ptv-for-wordpress' ) )
				              ->add_options( $ptv_post_statuses )
				              ->help_text( __( 'Changing publishing status affects all translations.', 'ptv-for-wordpress' ) ),
			         )
		         );

}

ptv_register_custom_fields();
