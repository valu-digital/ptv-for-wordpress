<?php
/**
 * PTV for WordPress
 *
 * @package     PTV for WordPress
 * @author      Valu Digital Oy
 * @copyright   2018 Valu Digital Oy
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: PTV for WordPress
 * Plugin URI:  https://www.valu.fi
 * Description: PTV WordPress Integration
 * Version:     0.9.8
 * Author:      Valu Digital Oy
 * Author URI:  https://www.valu.fi
 * Text Domain: ptv-for-wordpress
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Copyright (C) 2017  Valu Digital  wordpress@valu.fi
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'PTV_FOR_WORDPRESS_DIR' ) ) {
	define( 'PTV_FOR_WORDPRESS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PTV_FOR_WORDPRESS_URL' ) ) {
	define( 'PTV_FOR_WORDPRESS_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PTV_FOR_WORDPRESS_MODE' ) ) {
	define( 'PTV_FOR_WORDPRESS_MODE', 'out' );
}

use Carbon_Fields\Carbon_Fields;

/**
 * Class PTV_For_WordPress
 */
final class PTV_For_WordPress {

	/**
	 * Plugin instance.
	 *
	 * @var PTV_For_WordPress
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return PTV_For_WordPress
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_For_WordPress constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 0 );
	}

	/**
	 * Load plugin.
	 */
	function plugins_loaded() {

		// Show admin notice, if dependencies are missing.
		if ( ! $this->check_dependencies() ) {
			add_action( 'admin_notices', array( $this, 'show_missing_dependencies_notice' ) );

			return;
		}

		// Load dependencies.
		require_once( PTV_FOR_WORDPRESS_DIR . '/vendor/autoload.php' );

		// Setup hooks
		$this->setup_hooks();

		// Load general dependencies.
		$this->load();

	}

	/**
	 * Load plugin textdomain.
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'ptv-for-wordpress', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {

		// Add admin menu page.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// Load custom fields.
		add_action( 'after_setup_theme', array( $this, 'load_custom_fields' ) );

		// Register post types and taxonomies.
		add_action( 'init', array( $this, 'register_post_types_and_taxonomies' ), 9 );

		// Enqueue scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Add admin menu.
	 */
	function add_admin_menu() {
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-admin-page.php' );
	}

	/**
	 * Load custom functions and fields.
	 */
	function load_custom_fields() {

		Carbon_Fields::boot();
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/carbon-fields.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-custom-fields.php' );

	}

	/**
	 * Register post types and taxonomies.
	 */
	function register_post_types_and_taxonomies() {

		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-post-types.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-taxonomies.php' );

	}

	/**
	 * Load common dependencies.
	 */
	function load() {

		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/functions.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-api.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/polylang.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-common-module.php' );

		if ( 'out' === PTV_FOR_WORDPRESS_MODE ) {
			// Out.
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-out-module.php' );

		} else {
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-in-module.php' );
		}

	}

	/**
	 * Check plugin dependencies
	 */
	function check_dependencies() {

		if ( ! defined( 'PTV_API_USER' ) || ! defined( 'PTV_API_SECRET' ) || ! defined( 'PTV_API_URL' ) || ! defined( 'PTV_API_TOKEN_URL' ) || ! defined( 'PTV_FOR_WORDPRESS_REST_TOKEN' ) ) {
			return false;
		}

		if ( ! function_exists( 'PLL' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Enqueue scripts.
	 */
	function enqueue_scripts( $hook ) {

		if ( ! in_array( $hook, array( 'edit.php', 'post-new.php', 'post.php' ), true ) ) {
			return;
		}

		global $post;

		wp_enqueue_script( 'ptv-for-wordpress-admin', PTV_FOR_WORDPRESS_URL . 'assets/js/ptv-for-wordpress-admin.js', array( 'jquery' ), '', true );


		$post_type = ( isset( $post->post_type ) ) ? $post->post_type : '';

		wp_localize_script( 'ptv-for-wordpress-admin', 'ptv', array(
			'postType' => $post_type,
			'errors'   => array(
				'addressMissing'                  => __( 'Visiting address is required value.', 'ptv-for-wordpress' ),
				'onlyOneDeliveryAddressIsAllowed' => __( 'Only one delivery address is allowed.', 'ptv-for-wordpress' ),
				'visitingAddressWithWrongSubType' => __( 'Subtype of visiting address must be single.', 'ptv-for-wordpress' ),
			),
		) );

	}


	/**
	 * Admin notice for missing dependencies.
	 */
	function show_missing_dependencies_notice() {
		?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'Define API constants in the wp-config.php and enable Polylang plugin.', 'ptv-for-wordpress' ); ?></p>
		</div>
		<?php
	}

}

/**
 * @return PTV_For_WordPress
 */
function ptv() {
	return PTV_For_WordPress::get_instance();
}

ptv();


