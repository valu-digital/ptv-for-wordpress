<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PTV_Admin_Page {

	private $page_slug = 'ptv-menu';

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * PTV_Admin_Page constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 9999 );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add plugin page
	 */
	public function add_plugin_page() {

		add_menu_page(
			'PTV',
			'PTV',
			'administrator',
			$this->page_slug,
			array( $this, 'create_menu_item' )
		);

		add_submenu_page( $this->page_slug, __( 'PTV settings', 'ptv-for-wordpress' ), __( 'PTV settings', 'ptv-for-wordpress' ), 'administrator', 'ptv-settings', array( $this, 'create_admin_page' ) );

	}

	function create_menu_item() {
		return false;
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = ptv_get_settings();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'PTV Settings', 'ptv-for-wordpress' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'ptv_settings_group' );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {

		register_setting(
			'ptv_settings_group', // Option group
			'ptv_settings', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'ptv_general_settings_section', // ID
			__( 'General settings', 'ptv-for-wordpress' ), // Title
			array( $this, 'general_settings_section_callback' ), // Callback
			$this->page_slug // Page
		);

		add_settings_field(
			'organization_id', // ID
			__( 'Organization ID', 'ptv-for-wordpress' ), // Title
			array( $this, 'organization_id_callback' ), // Callback
			$this->page_slug, // Page
			'ptv_general_settings_section' // Section
		);

		add_settings_field(
			'primary_language', // ID
			__( 'Primary language', 'ptv-for-wordpress' ), // Title
			array( $this, 'primary_language_callback' ), // Callback
			$this->page_slug, // Page
			'ptv_general_settings_section' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if ( isset( $input['organization_id'] ) ) {
			$new_input['organization_id'] = sanitize_text_field( $input['organization_id'] );
		}

		if ( isset( $input['primary_language'] ) ) {
			$new_input['primary_language'] = sanitize_text_field( $input['primary_language'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function general_settings_section_callback() {
		return false;
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function organization_id_callback() {
		printf(
			'<input type="text" id="organization_id" name="ptv_settings[organization_id]" value="%s" />',
			isset( $this->options['organization_id'] ) ? esc_attr( $this->options['organization_id'] ) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function primary_language_callback() {
		?>
		<select name='ptv_settings[primary_language]'>
			<option value='fi' <?php selected( $this->options['primary_language'], 'fi' ); ?>><?php esc_html_e( 'Finnish', 'ptv-for-wordpress' ); ?></option>
			<option value='sv' <?php selected( $this->options['primary_language'], 'sv' ); ?>><?php esc_html_e( 'Swedish', 'ptv-for-wordpress' ); ?></option>
			<option value='en' <?php selected( $this->options['primary_language'], 'en' ); ?>><?php esc_html_e( 'English', 'ptv-for-wordpress' ); ?></option>
		</select>

		<?php
	}
}

if ( is_admin() ) {
	$ptv_admin_page = new PTV_Admin_Page();
}