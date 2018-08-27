<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( PTV_FOR_WORDPRESS_DIR . '/lib/libraries/class-wp-async-request.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/libraries/class-wp-background-process.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-taxonomy-background-process.php' );


/**
 * Class PTV_Taxonomy_Controller
 */
class PTV_Taxonomy_Controller {

	/**
	 * @var PTV_Background_Process
	 */
	protected $background_process;

	/**
	 * PTV_Taxonomy_Controller constructor.
	 */
	function __construct() {

		$this->background_process = new PTV_Taxonomy_Background_Process();

	}

	/**
	 * Fetches the items and sets them to a background processing queue.
	 *
	 * @return bool
	 */
	function fetch() {

		$items = $this->get_items();

		if ( is_wp_error( $items ) ) {
			return false;
		}

		foreach ( $items as $item ) {
			$this->background_process->push_to_queue( $item );
		}

		$this->background_process->save()->dispatch();

	}

	/**
	 * @return array|WP_Error
	 */
	function get_items() {

		$taxonomies           = get_transient( 'ptv_taxonomies' );
		$taxonomies_formatted = array();

		if ( empty( $taxonomies ) ) {
			$taxonomies = json_decode( file_get_contents( PTV_FOR_WORDPRESS_DIR . '/resources/taxonomies.json' ), true );
			foreach ( $taxonomies as $taxonomy => $termlist ) {
				foreach ( $termlist as $term ) {

					$term['taxonomy']  = $taxonomy;
					$taxonomies_formatted[] = $term;
				}
			}
			set_transient( 'ptv_taxonomies', $taxonomies_formatted, 60 * 60 * 12 );
			$taxonomies = $taxonomies_formatted;
		}

		return $taxonomies;

	}

}