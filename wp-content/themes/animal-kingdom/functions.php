<?php
/**
 * Theme functions and definitions
 *
 * @version 1.0.0
 *
 * @package akd
 */

/**
 * Define necessary constants.
 */
define( 'AKD_THEME_DIR', get_template_directory() );
define( 'AKD_THEME_URI', get_template_directory_uri() );

/* Loaded file for general functions. */
require AKD_THEME_DIR . '/inc/akd-general-functions.php';

/* Loaded file for ACF functions. */
require AKD_THEME_DIR . '/inc/akd-acf-functions.php';

/* Loaded file for to enqueue js css. */
require AKD_THEME_DIR . '/inc/akd-render-css-js.php';

/* Loaded file for theme setup functions. */
require AKD_THEME_DIR . '/inc/akd-setup-functions.php';

/* Loaded file for custom shortcodes. */
require AKD_THEME_DIR . '/inc/akd-custom-shortcodes.php';