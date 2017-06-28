<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post types
 */
function ptv_register_post_types() {

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

	$phone_channel_labels = array(
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

	$phone_channel_args = array(
		'label'               => __( 'Phone Channel', 'ptv-for-wordpress' ),
		'labels'              => $phone_channel_labels,
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

	register_post_type( 'ptv-phone', $phone_channel_args );

	$pritable_form_channel_labels = array(
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

	$pritable_form_channel_args = array(
		'label'               => __( 'Printable Form Channel', 'ptv-for-wordpress' ),
		'labels'              => $pritable_form_channel_labels,
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

	register_post_type( 'ptv-printable-form', $pritable_form_channel_args );

	$service_location_channel_labels = array(
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

	$service_location_channel_args = array(
		'label'               => __( 'Service Location Channel', 'ptv-for-wordpress' ),
		'labels'              => $service_location_channel_labels,
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

	register_post_type( 'ptv-service-location', $service_location_channel_args );

	$webpage_channel_labels = array(
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

	$webpage_channel_args = array(
		'label'               => __( 'Webpage Channel', 'ptv-for-wordpress' ),
		'labels'              => $webpage_channel_labels,
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

	register_post_type( 'ptv-web-page', $webpage_channel_args );

}

add_action( 'init', 'ptv_register_post_types' );

/**
 * Register taxonomies
 */
function ptv_register_taxonomies() {

	$organization_class_labels = array(
		'name'                       => _x( 'Service classes', 'Taxonomy General Name', 'ptv-for-wordpress' ),
		'singular_name'              => _x( 'Service class', 'Taxonomy Singular Name', 'ptv-for-wordpress' ),
		'menu_name'                  => __( 'Service classes', 'ptv-for-wordpress' ),
		'all_items'                  => __( 'All Service classes', 'ptv-for-wordpress' ),
		'parent_item'                => __( 'Parent Service class', 'ptv-for-wordpress' ),
		'parent_item_colon'          => __( 'Parent Service class:', 'ptv-for-wordpress' ),
		'new_item_name'              => __( 'New Service class Name', 'ptv-for-wordpress' ),
		'add_new_item'               => __( 'Add New Service class', 'ptv-for-wordpress' ),
		'edit_item'                  => __( 'Edit Service class', 'ptv-for-wordpress' ),
		'update_item'                => __( 'Update Service class', 'ptv-for-wordpress' ),
		'view_item'                  => __( 'View Service class', 'ptv-for-wordpress' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ptv-for-wordpress' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ptv-for-wordpress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ptv-for-wordpress' ),
		'popular_items'              => __( 'Popular Service classes', 'ptv-for-wordpress' ),
		'search_items'               => __( 'Search Service classes', 'ptv-for-wordpress' ),
		'not_found'                  => __( 'Not Found', 'ptv-for-wordpress' ),
		'no_terms'                   => __( 'No items', 'ptv-for-wordpress' ),
		'items_list'                 => __( 'Service classes list', 'ptv-for-wordpress' ),
		'items_list_navigation'      => __( 'Service classes list navigation', 'ptv-for-wordpress' ),
	);

	$organization_class_args = array(
		'labels'            => $organization_class_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_menu'      => 'ptv-settings',
	);

	register_taxonomy( 'ptv-service-classes', 'ptv-service', $organization_class_args );

	$ontology_term_labels = array(
		'name'                       => _x( 'Ontology terms', 'Taxonomy General Name', 'ptv-for-wordpress' ),
		'singular_name'              => _x( 'Ontology term', 'Taxonomy Singular Name', 'ptv-for-wordpress' ),
		'menu_name'                  => __( 'Ontology terms', 'ptv-for-wordpress' ),
		'all_items'                  => __( 'All Ontology terms', 'ptv-for-wordpress' ),
		'parent_item'                => __( 'Parent Ontology term', 'ptv-for-wordpress' ),
		'parent_item_colon'          => __( 'Parent Ontology term:', 'ptv-for-wordpress' ),
		'new_item_name'              => __( 'New Ontology term Name', 'ptv-for-wordpress' ),
		'add_new_item'               => __( 'Add New Ontology term', 'ptv-for-wordpress' ),
		'edit_item'                  => __( 'Edit Ontology term', 'ptv-for-wordpress' ),
		'update_item'                => __( 'Update Ontology term', 'ptv-for-wordpress' ),
		'view_item'                  => __( 'View Ontology term', 'ptv-for-wordpress' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ptv-for-wordpress' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ptv-for-wordpress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ptv-for-wordpress' ),
		'popular_items'              => __( 'Popular Ontology terms', 'ptv-for-wordpress' ),
		'search_items'               => __( 'Search Ontology terms', 'ptv-for-wordpress' ),
		'not_found'                  => __( 'Not Found', 'ptv-for-wordpress' ),
		'no_terms'                   => __( 'No items', 'ptv-for-wordpress' ),
		'items_list'                 => __( 'Ontology terms list', 'ptv-for-wordpress' ),
		'items_list_navigation'      => __( 'Ontology terms list navigation', 'ptv-for-wordpress' ),
	);

	$ontology_term_args = array(
		'labels'            => $ontology_term_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_menu'      => 'ptv-settings',
	);

	register_taxonomy( 'ptv-ontology-terms', 'ptv-service', $ontology_term_args );

	$target_group_labels = array(
		'name'                       => _x( 'Target groups', 'Taxonomy General Name', 'ptv-for-wordpress' ),
		'singular_name'              => _x( 'Target group', 'Taxonomy Singular Name', 'ptv-for-wordpress' ),
		'menu_name'                  => __( 'Target groups', 'ptv-for-wordpress' ),
		'all_items'                  => __( 'All Target groups', 'ptv-for-wordpress' ),
		'parent_item'                => __( 'Parent Target group', 'ptv-for-wordpress' ),
		'parent_item_colon'          => __( 'Parent Target group:', 'ptv-for-wordpress' ),
		'new_item_name'              => __( 'New Target group Name', 'ptv-for-wordpress' ),
		'add_new_item'               => __( 'Add New Target group', 'ptv-for-wordpress' ),
		'edit_item'                  => __( 'Edit Target group', 'ptv-for-wordpress' ),
		'update_item'                => __( 'Update Target group', 'ptv-for-wordpress' ),
		'view_item'                  => __( 'View Target group', 'ptv-for-wordpress' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ptv-for-wordpress' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ptv-for-wordpress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ptv-for-wordpress' ),
		'popular_items'              => __( 'Popular Target groups', 'ptv-for-wordpress' ),
		'search_items'               => __( 'Search Target groups', 'ptv-for-wordpress' ),
		'not_found'                  => __( 'Not Found', 'ptv-for-wordpress' ),
		'no_terms'                   => __( 'No items', 'ptv-for-wordpress' ),
		'items_list'                 => __( 'Target groups list', 'ptv-for-wordpress' ),
		'items_list_navigation'      => __( 'Target groups list navigation', 'ptv-for-wordpress' ),
	);

	$target_group_args = array(
		'labels'            => $target_group_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_menu'      => 'ptv-settings',
	);

	register_taxonomy( 'ptv-target-groups', 'ptv-service', $target_group_args );

	$life_event_labels = array(
		'name'                       => _x( 'Life Events', 'Taxonomy General Name', 'ptv-for-wordpress' ),
		'singular_name'              => _x( 'Life Event', 'Taxonomy Singular Name', 'ptv-for-wordpress' ),
		'menu_name'                  => __( 'Life Events', 'ptv-for-wordpress' ),
		'all_items'                  => __( 'All Life Events', 'ptv-for-wordpress' ),
		'parent_item'                => __( 'Parent Life Event', 'ptv-for-wordpress' ),
		'parent_item_colon'          => __( 'Parent Life Event:', 'ptv-for-wordpress' ),
		'new_item_name'              => __( 'New Life Event Name', 'ptv-for-wordpress' ),
		'add_new_item'               => __( 'Add New Life Event', 'ptv-for-wordpress' ),
		'edit_item'                  => __( 'Edit Life Event', 'ptv-for-wordpress' ),
		'update_item'                => __( 'Update Life Event', 'ptv-for-wordpress' ),
		'view_item'                  => __( 'View Life Event', 'ptv-for-wordpress' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ptv-for-wordpress' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ptv-for-wordpress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ptv-for-wordpress' ),
		'popular_items'              => __( 'Popular Life Events', 'ptv-for-wordpress' ),
		'search_items'               => __( 'Search Life Events', 'ptv-for-wordpress' ),
		'not_found'                  => __( 'Not Found', 'ptv-for-wordpress' ),
		'no_terms'                   => __( 'No items', 'ptv-for-wordpress' ),
		'items_list'                 => __( 'Life Events list', 'ptv-for-wordpress' ),
		'items_list_navigation'      => __( 'Life Events list navigation', 'ptv-for-wordpress' ),
	);

	$life_event_args = array(
		'labels'            => $life_event_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_menu'      => 'ptv-settings',
	);

	register_taxonomy( 'ptv-life-events', 'ptv-service', $life_event_args );

	$life_event_labels = array(
		'name'                       => _x( 'Industrial classes', 'Taxonomy General Name', 'ptv-for-wordpress' ),
		'singular_name'              => _x( 'Industrial class', 'Taxonomy Singular Name', 'ptv-for-wordpress' ),
		'menu_name'                  => __( 'Industrial classes', 'ptv-for-wordpress' ),
		'all_items'                  => __( 'All Industrial classes', 'ptv-for-wordpress' ),
		'parent_item'                => __( 'Parent Industrial class', 'ptv-for-wordpress' ),
		'parent_item_colon'          => __( 'Parent Industrial class:', 'ptv-for-wordpress' ),
		'new_item_name'              => __( 'New Industrial class Name', 'ptv-for-wordpress' ),
		'add_new_item'               => __( 'Add New Industrial class', 'ptv-for-wordpress' ),
		'edit_item'                  => __( 'Edit Industrial class', 'ptv-for-wordpress' ),
		'update_item'                => __( 'Update Industrial class', 'ptv-for-wordpress' ),
		'view_item'                  => __( 'View Industrial class', 'ptv-for-wordpress' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ptv-for-wordpress' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ptv-for-wordpress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ptv-for-wordpress' ),
		'popular_items'              => __( 'Popular Industrial classes', 'ptv-for-wordpress' ),
		'search_items'               => __( 'Search Industrial classes', 'ptv-for-wordpress' ),
		'not_found'                  => __( 'Not Found', 'ptv-for-wordpress' ),
		'no_terms'                   => __( 'No items', 'ptv-for-wordpress' ),
		'items_list'                 => __( 'Industrial classes list', 'ptv-for-wordpress' ),
		'items_list_navigation'      => __( 'Industrial classes list navigation', 'ptv-for-wordpress' ),
	);

	$life_event_args = array(
		'labels'            => $life_event_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_menu'      => 'ptv-settings',
	);

	register_taxonomy( 'ptv-industrial-classes', 'ptv-service', $life_event_args );

}

add_action( 'init', 'ptv_register_taxonomies' );
