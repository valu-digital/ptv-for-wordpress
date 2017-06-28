<?php
/**
 * PTV for WordPress
 *
 * @package     PTV for WordPress
 * @author      Valu Digital Oy
 * @copyright   2017 Valu Digital Oy
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: PTV for WordPress
 * Plugin URI:  https://www.valu.fi
 * Description: PTV WordPress Integration
 * Version:     0.8.1
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

		// Load dependencies.
		require_once( PTV_FOR_WORDPRESS_DIR . '/vendor/autoload.php' );

		// Show admin notice, if dependencies are missing.
		if ( ! $this->check_dependencies() ) {
			add_action( 'admin_notices', array( $this, 'show_missing_dependencies_notice' ) );

			return;
		}

		// Load general dependencies.
		$this->includes();

		if ( 'out' === PTV_FOR_WORDPRESS_MODE ) {
			// Out.
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-out-module.php' );

		} else {
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-in-module.php' );
		}
	}

	/**
	 * Load common dependencies
	 */
	function includes() {

		// Add admin menu page.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// Load custom fields.
		add_action( 'after_setup_theme', array( $this, 'load_custom_fields' ) );

		// Register post types and taxonomies.
		add_action( 'init', array( $this, 'register_post_types_and_taxonomies' ), 9 );

		require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-api.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/core/functions.php' );
		require_once( PTV_FOR_WORDPRESS_DIR . '/core/polylang.php' );

	}

	/**
	 * Add admin menu
	 */
	function add_admin_menu() {
		require_once( PTV_FOR_WORDPRESS_DIR . '/core/class-ptv-admin-page.php' );
	}

	/**
	 * Register post types and taxonomies
	 */
	function register_post_types_and_taxonomies() {
		require_once( PTV_FOR_WORDPRESS_DIR . '/core/post-types.php' );
	}

	/**
	 * Load custom functions and fields
	 */
	function load_custom_fields() {

		Carbon_Fields::boot();
		require_once( PTV_FOR_WORDPRESS_DIR . '/core/custom-fields.php' );

	}

	/**
	 * Load plugin textdomain
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'ptv-for-wordpress', false, basename( dirname( __FILE__ ) ) . '/languages' );
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


