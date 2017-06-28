<?php

/**
 * Get PTV Settings.
 *
 * @return array
 */
function ptv_get_settings() {
	return (array) get_option( 'ptv_settings', array( 'primary_language' => 'fi' ) );
}

/**
 * Get organization id.
 *
 * @return null
 */
function ptv_get_organization_id() {

	$settings = ptv_get_settings();

	if ( isset( $settings['organization_id'] ) && ! empty( $settings['organization_id'] ) ) {
		return sanitize_text_field( $settings['organization_id'] );
	}

	return null;
}

/**
 * Get languages from the json file
 *
 * @param string $lang
 *
 * @return array
 */
function ptv_get_languages( $lang = null ) {

	if ( ! $lang ) {
		$settings = ptv_get_settings();
		$lang     = $settings['primary_language'];
	}

	$languages        = json_decode( file_get_contents( PTV_FOR_WORDPRESS_DIR . '/resources/language-codes.json' ) );
	$language_options = array();

	if ( isset( $languages ) and is_array( $languages ) ) {
		foreach ( $languages as $language_object ) {
			$language_options[ $language_object->code ] = $language_object->$lang;
		}
	}

	return $language_options;

}

/**
 * Get municipalities from the json file
 *
 * @param string $lang
 *
 * @return array
 */
function ptv_get_municipalities( $lang = null ) {

	if ( ! $lang ) {
		$settings = ptv_get_settings();
		$lang     = $settings['primary_language'];
	}

	$municipalities       = json_decode( file_get_contents( PTV_FOR_WORDPRESS_DIR . '/resources/municipalities.json' ) );
	$municipality_options = array();

	if ( isset( $municipalities ) and is_array( $municipalities ) ) {
		foreach ( $municipalities as $municipality_object ) {

			foreach ( $municipality_object->names as $name ) {
				if ( $lang === $name->language ) {
					$municipality_options[ $municipality_object->municipalityCode ] = $name->name;
				}
				break;
			}
		}
	}

	return $municipality_options;

}

/**
 * Get post types.
 *
 * @return array
 */
function ptv_get_post_types() {
	return array(
		'ptv-service',
		'ptv-organization',
		'ptv-echannel',
		'ptv-phone',
		'ptv-printable-form',
		'ptv-service-location',
		'ptv-web-page',
	);
}

/**
 * Get taxonomies.
 *
 * @return array
 */
function ptv_get_taxonomies() {
	return array(
		'ptv-service-classes',
		'ptv-ontology-terms',
		'ptv-target-groups',
		'ptv-life-events',
		'ptv-industrial-classes',
	);
}

/**
 * Get service channel post types.
 *
 * @return array
 */
function ptv_get_service_channel_post_types() {
	return array(
		'ptv-echannel',
		'ptv-phone',
		'ptv-printable-form',
		'ptv-service-location',
		'ptv-web-page',
	);
}

/**
 * Get service channels
 *
 * @return array
 */
function ptv_get_service_channels() {

	$service_channels = new WP_Query(
		array(
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'post_type'      => ptv_get_service_channel_post_types(),
		)
	);

	$result = array();

	if ( $service_channels->have_posts() ) :

		$result[] = __( 'Select one', 'ptv-for-wordpress' );

		while ( $service_channels->have_posts() ) : $service_channels->the_post();

			$ptv_id = get_post_meta( get_the_ID(), '_ptv_id', true );

			if ( ! empty( $ptv_id ) ) {
				$result[ get_post_meta( get_the_ID(), '_ptv_id', true ) ] = get_the_title();
			}

		endwhile;
		wp_reset_postdata();
	endif;

	return $result;

}

/**
 * @return array|mixed|PTV_Guid_Page
 */
function ptv_get_organizations() {

	$organization_cache = get_transient( 'ptv_organizations' );

	if ( $organization_cache ) {
		return $organization_cache;
	}

	$api = new PTV_Api();

	// Get organization.
	$organizations = $api->get_organization_api()->get_organizations();

	if ( is_wp_error( $organizations ) ) {
		return $organizations;
	}

	$organization_array = array(
		'' => __( 'Select one', 'ptv-for-wordpress' ),
	);

	foreach ( $organizations->get_item_list() as $organization ) {
		$organization_array[ $organization->get_id() ] = $organization->get_name();
	}

	set_transient( 'ptv_organizations', $organization_array, 60 * 60 );

	return $organization_array;

}

/**
 * Convert string to snake case.
 *
 * @param $string
 *
 * @return string
 */
function ptv_to_snake_case( $string ) {
	$string = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1_$2', $string ) );

	return $string;
}

/**
 * Convert string to a meta key
 *
 * @param $string
 *
 * @return string
 */
function ptv_to_meta_key( $string ) {
	return sprintf( '_ptv_%s', ptv_to_snake_case( $string ) );
}

/**
 * Convert sring to a post type name.
 *
 * @param $string
 *
 * @return string
 */
function ptv_to_post_type_name( $string ) {
	return str_replace( '-channel', '', ptv_to_taxomomy_name( ptv_to_snake_case( $string ) ) );
}

/**
 * Convert string to a taxonomy name.
 *
 * @param $string
 *
 * @return string
 */
function ptv_to_taxomomy_name( $string ) {

	return str_replace( '_', '-', ltrim( $string, '_' ) );

}

/**
 * Get localized value of the item.
 *
 * @param $localized_items
 * @param $lang
 * @param null $type
 *
 * @return string
 */
function ptv_get_localized_value( $localized_items, $lang, $type = null ) {

	if ( is_array( $localized_items ) ) {
		foreach ( $localized_items as $localized_item ) {
			if ( ( $lang === $localized_item->get_language() ) && ( ! $type || ( $type === $localized_item->get_type() ) ) ) {
				return $localized_item->get_value();
			}
		}
	}

	return '';
}
