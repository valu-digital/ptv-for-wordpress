<?php

namespace PTV_Custom_Fields;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Field\Set_Field;
use Carbon_Fields\Field\Textarea_Field;

class PTV_Searchable_Set_Field extends Set_Field {

	public static function admin_enqueue_scripts() {
		$root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( PTV_FOR_WORDPRESS_DIR );

		# Enqueue JS
		wp_enqueue_script( 'carbon-field-searchable_set', $root_uri . '/assets/js/bundle.js', array( 'carbon-fields-boot' ) );

	}
}

Carbon_Fields::extend( PTV_Searchable_Set_Field::class, function ( $container ) {
	return new PTV_Searchable_Set_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
} );


class PTV_Markdown_Field extends Textarea_Field {
	public static function admin_enqueue_scripts() {
		$root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( PTV_FOR_WORDPRESS_DIR );

		# Enqueue JS
		wp_enqueue_script( 'carbon-field-searchable_set', $root_uri . '/assets/js/bundle.js', array( 'carbon-fields-boot' ) );

	}
}

Carbon_Fields::extend( PTV_Markdown_Field::class, function ( $container ) {
	return new PTV_Markdown_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );

} );
