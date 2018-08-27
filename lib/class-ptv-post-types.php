<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PTV_Post_Types {

	/**
	 * Class instance.
	 *
	 * @var PTV_Post_Types
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_Post_Types
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_Post_Types constructor.
	 */
	private function __construct() {

		$this->register_post_types();
	}

	/**
	 * Register post types.
	 */
	function register_post_types() {

		$this->register_organization_post_type();
		$this->register_service_post_type();
		$this->register_echannel_post_type();
		$this->register_phone_post_type();
		$this->register_printable_form_post_type();
		$this->register_service_location_post_type();
		$this->register_web_page_post_type();

	}

	/**
	 * Register organization post type.
	 */
	public function register_organization_post_type() {

		$organization_labels = array(
			'name'                  => _x( 'Organizations', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Organization', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Organizations', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Organization', 'ptv-for-wordpress' ),
			'archives'              => __( 'Organization Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Organization Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Organization:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Organizations', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Organization', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Organization', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Organization', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Organization', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Organization', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Organizations', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Organization', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Organization', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Organizations list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Organizations list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$organization_args = array(
			'label'               => __( 'Organization', 'ptv-for-wordpress' ),
			'labels'              => $organization_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 20,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-organization', $organization_args );


	}

	/**
	 * Register web page post type.
	 */
	public function register_web_page_post_type() {

		$web_page_labels = array(
			'name'                  => _x( 'Webpage Channels', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Webpage Channel', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Webpage Channels', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Webpage Channel', 'ptv-for-wordpress' ),
			'archives'              => __( 'Webpage Channel Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Webpage Channel Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Webpage Channel:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Webpage Channels', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Webpage Channel', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Webpage Channel', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Webpage Channel', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Webpage Channel', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Webpage Channel', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Webpage Channels', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Webpage Channel', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Webpage Channel', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Webpage Channels list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Webpage Channels list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$web_page_args = array(
			'label'               => __( 'Webpage Channel', 'ptv-for-wordpress' ),
			'labels'              => $web_page_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 80,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-web-page', $web_page_args );
	}

	/**
	 * Register service location post type.
	 */
	public function register_service_location_post_type() {

		$service_location_labels = array(
			'name'                  => _x( 'Service Location Channels', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Service Location Channel', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Service Location Channels', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Service Location Channel', 'ptv-for-wordpress' ),
			'archives'              => __( 'Service Location Channel Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Service Location Channel Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Service Location Channel:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Service Location Channels', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Service Location Channel', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Service Location Channel', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Service Location Channel', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Service Location Channel', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Service Location Channel', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Service Location Channels', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Service Location Channel', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Service Location Channel', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Service Location Channels list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Service Location Channels list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$service_location_args = array(
			'label'               => __( 'Service Location Channel', 'ptv-for-wordpress' ),
			'labels'              => $service_location_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 70,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-service-location', $service_location_args );
	}

	/**
	 * Register printable form post type.
	 */
	public function register_printable_form_post_type() {

		$printable_form_labels = array(
			'name'                  => _x( 'Printable Form Channels', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Printable Form Channel', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Printable Form Channels', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Printable Form Channel', 'ptv-for-wordpress' ),
			'archives'              => __( 'Printable Form Channel Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Printable Form Channel Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Printable Form Channel:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Printable Form Channels', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Printable Form Channel', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Printable Form Channel', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Printable Form Channel', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Printable Form Channel', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Printable Form Channel', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Printable Form Channels', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Printable Form Channel', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Printable Form Channel', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Printable Form Channels list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Printable Form Channels list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$printable_form_args = array(
			'label'               => __( 'Printable Form Channel', 'ptv-for-wordpress' ),
			'labels'              => $printable_form_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 60,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-printable-form', $printable_form_args );

	}

	/**
	 * Register phone channel.
	 */
	public function register_phone_post_type() {
		$phone_labels = array(
			'name'                  => _x( 'Phone Channels', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Phone Channel', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Phone Channels', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Phone Channel', 'ptv-for-wordpress' ),
			'archives'              => __( 'Phone Channel Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Phone Channel Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Phone Channel:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Phone Channels', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Phone Channel', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Phone Channel', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Phone Channel', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Phone Channel', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Phone Channel', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Phone Channels', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Phone Channel', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Phone Channel', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Phone Channels list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Phone Channels list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$phone_args = array(
			'label'               => __( 'Phone Channel', 'ptv-for-wordpress' ),
			'labels'              => $phone_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 50,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-phone', $phone_args );
	}

	public function register_echannel_post_type() {

		$echannel_labels = array(
			'name'                  => _x( 'Electronic Channels', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Electronic Channel', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Electronic Channels', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Electronic Channel', 'ptv-for-wordpress' ),
			'archives'              => __( 'Electronic Channel Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Electronic Channel Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Electronic Channel:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Electronic Channels', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Electronic Channel', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Electronic Channel', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Electronic Channel', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Electronic Channel', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Electronic Channel', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Electronic Channels', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Electronic Channel', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into Electronic Channel', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Electronic Channels list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Electronic Channels list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$echannel_args = array(
			'label'               => __( 'Electronic Channel', 'ptv-for-wordpress' ),
			'labels'              => $echannel_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 40,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-echannel', $echannel_args );

	}

	public function register_service_post_type() {

		$service_labels = array(
			'name'                  => _x( 'Services', 'Post Type General Name', 'ptv-for-wordpress' ),
			'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'ptv-for-wordpress' ),
			'menu_name'             => __( 'Services', 'ptv-for-wordpress' ),
			'name_admin_bar'        => __( 'Service', 'ptv-for-wordpress' ),
			'archives'              => __( 'Service Archives', 'ptv-for-wordpress' ),
			'attributes'            => __( 'Service Attributes', 'ptv-for-wordpress' ),
			'parent_item_colon'     => __( 'Parent Service:', 'ptv-for-wordpress' ),
			'all_items'             => __( 'Services', 'ptv-for-wordpress' ),
			'add_new_item'          => __( 'Add New Service', 'ptv-for-wordpress' ),
			'add_new'               => __( 'Add New', 'ptv-for-wordpress' ),
			'new_item'              => __( 'New Service', 'ptv-for-wordpress' ),
			'edit_item'             => __( 'Edit Service', 'ptv-for-wordpress' ),
			'update_item'           => __( 'Update Service', 'ptv-for-wordpress' ),
			'view_item'             => __( 'View Service', 'ptv-for-wordpress' ),
			'view_items'            => __( 'View Services', 'ptv-for-wordpress' ),
			'search_items'          => __( 'Search Service', 'ptv-for-wordpress' ),
			'not_found'             => __( 'Not found', 'ptv-for-wordpress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'ptv-for-wordpress' ),
			'featured_image'        => __( 'Featured Image', 'ptv-for-wordpress' ),
			'set_featured_image'    => __( 'Set featured image', 'ptv-for-wordpress' ),
			'remove_featured_image' => __( 'Remove featured image', 'ptv-for-wordpress' ),
			'use_featured_image'    => __( 'Use as featured image', 'ptv-for-wordpress' ),
			'insert_into_item'      => __( 'Insert into service', 'ptv-for-wordpress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'ptv-for-wordpress' ),
			'items_list'            => __( 'Services list', 'ptv-for-wordpress' ),
			'items_list_navigation' => __( 'Services list navigation', 'ptv-for-wordpress' ),
			'filter_items_list'     => __( 'Filter services list', 'ptv-for-wordpress' ),
		);

		$service_args = array(
			'label'               => __( 'Service', 'ptv-for-wordpress' ),
			'labels'              => $service_labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 10,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_menu'        => 'ptv-menu',
		);

		register_post_type( 'ptv-service', $service_args );
	}

}


add_action( 'init', function () {
	PTV_Post_Types::get_instance();
} );
