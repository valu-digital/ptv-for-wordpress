<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV_Taxonomies
 */
class PTV_Taxonomies {

	/**
	 * Class instance.
	 *
	 * @var PTV_Taxonomies
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_Taxonomies
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_Taxonomies constructor.
	 */
	private function __construct() {

		$this->register_taxonomies();
	}

	/**
	 * Register taxonomies.
	 */
	function register_taxonomies() {

		$this->register_service_classes();
		$this->register_target_groups();
		$this->register_life_events();
		$this->register_industrial_classes();
		$this->register_ontology_terms();

	}

	/**
	 * Register service classes.
	 */
	public function register_service_classes() {
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
			'capabilities'      => array(
				'manage_terms' => 'do_not_allow',
				'edit_terms'   => 'do_not_allow',
				'delete_terms' => 'do_not_allow',
				'assign_terms' => 'edit_pages',
			)
		);

		register_taxonomy( 'ptv-service-classes', 'ptv-service', $organization_class_args );
	}

	/**
	 * Register ontology terms.
	 */
	public function register_ontology_terms() {
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
			'capabilities'      => array(
				'manage_terms' => 'do_not_allow',
				'edit_terms'   => 'do_not_allow',
				'delete_terms' => 'do_not_allow',
				'assign_terms' => 'edit_pages',
			)
		);

		register_taxonomy( 'ptv-ontology-terms', 'ptv-service', $ontology_term_args );
	}

	/**
	 * Register target groups.
	 */
	public function register_target_groups() {

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
			'capabilities'      => array(
				'manage_terms' => 'do_not_allow',
				'edit_terms'   => 'do_not_allow',
				'delete_terms' => 'do_not_allow',
				'assign_terms' => 'edit_pages',
			)
		);

		register_taxonomy( 'ptv-target-groups', 'ptv-service', $target_group_args );

	}

	/**
	 * Register industrial classes.
	 */
	public function register_industrial_classes() {

		$industrial_classes_labels = array(
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

		$industrial_class_args = array(
			'labels'            => $industrial_classes_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_menu'      => 'ptv-settings',
			'capabilities'      => array(
				'manage_terms' => 'do_not_allow',
				'edit_terms'   => 'do_not_allow',
				'delete_terms' => 'do_not_allow',
				'assign_terms' => 'edit_pages',
			)
		);

		register_taxonomy( 'ptv-industrial-classes', 'ptv-service', $industrial_class_args );

	}

	/**
	 * Register life events.
	 */
	public function register_life_events() {

		$labels = array(
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

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_menu'      => 'ptv-settings',
			'capabilities'      => array(
				'manage_terms' => 'do_not_allow',
				'edit_terms'   => 'do_not_allow',
				'delete_terms' => 'do_not_allow',
				'assign_terms' => 'edit_pages',
			)
		);

		register_taxonomy( 'ptv-life-events', 'ptv-service', $args );

	}

}

add_action( 'init', function () {
	PTV_Taxonomies::get_instance();
} );
