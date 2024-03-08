<?php
/**
 * ACF functions of theme.
 *
 * @version 1.0.0
 *
 * @package akd
 */

 /**
  * Set the directory for ACF Local JSON files.
  *
  * @param string $path The default path for saving ACF Local JSON files.
  * @return string The updated path for saving ACF Local JSON files.
  * @version 1.0.0
  */
function akd_acf_json_save_point( $path ) {
	// Update path.
	$path = AKD_THEME_DIR . '/acf-json';

	// Return the updated path.
	return $path;
}
add_filter( 'acf/settings/save_json', 'akd_acf_json_save_point' );

/**
 * Set the directory for loading ACF Local JSON files.
 *
 * @param array $paths An array of paths to search for ACF Local JSON files.
 * @return array The updated array of paths to search for ACF Local JSON files.
 * @version 1.0.0
 */
function akd_acf_json_load_point( $paths ) {
	// Remove the original path (optional).
	unset( $paths[0] );

	// Append the path.
	$paths[] = get_template_directory() . '/acf-json';

	// Return the updated array of paths.
	return $paths;
}
add_filter( 'acf/settings/load_json', 'akd_acf_json_load_point' );

/**
 * To create general option page.
 *
 * @version 1.0.0
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		[
			'page_title' => __( 'General Settings', 'akd' ),
			'menu_title' => __( 'General Settings', 'akd' ),
			'menu_slug'  => 'general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'icon_url'   => 'dashicons-admin-settings',
		]
	);
}
