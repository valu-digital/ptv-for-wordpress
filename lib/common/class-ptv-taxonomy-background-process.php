<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PTV_Taxonomy_Background_Process extends WP_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'ptv_taxonomy_background_process';

	/**
	 * Task to perform for every item in the queue.
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 */
	protected function task( $item ) {

		error_log( $item['name'] );

		$post_type_helper = new PTV_Post_Type_Helper();

		$post_type_helper->process_term( $item, $item['taxonomy'], $item['language'] );

		return false;

	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		error_log( 'PTV TAXONOMY IMPORT COMPLETE' );
		parent::complete();
	}

}