<?php
/**
 * Theme Setup functions.
 *
 * @version 1.0.0
 *
 * @package akd
 */

/**
 * Enable custom support in the theme.
 *
 * @version 1.0.0
 */
function akd_custom_theme_setup() {
	// Enable menu support.
	add_theme_support( 'menus' );
	// Enable feature image support.
	add_theme_support( 'post-thumbnails' );
	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'akd_custom_theme_setup' );

/**
 * Register custom menus in the theme.
 *
 * @version 1.0.0
 */
function akd_register_custom_menus() {
	register_nav_menus(
		[
			'header-menu'        => 'Header Menu',
			'header-mobile-menu' => 'Header Mobile Menu',
			'footer-menu'        => 'Footer Menu',
		]
	);
}
add_action( 'init', 'akd_register_custom_menus' );

/**
 * Allow SVG Support
 *
 * @param array $mimes Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function akd_allow_svg_support( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'akd_allow_svg_support' );

/**
 * Register a custom post type called "Animals".
 *
 * @version 1.0.0
 */
function akd_register_posttype_animals() {
	$animal_labels = [
		'name'                  => _x( 'Animals', 'Animals', 'akd' ),
		'singular_name'         => _x( 'Animal', 'Animal', 'akd' ),
		'menu_name'             => _x( 'Animals', 'Admin Menu text', 'akd' ),
		'name_admin_bar'        => _x( 'Animal', 'Add New on Toolbar', 'akd' ),
		'add_new'               => __( 'Add New', 'akd' ),
		'add_new_item'          => __( 'Add New Animal', 'akd' ),
		'new_item'              => __( 'New Animal', 'akd' ),
		'edit_item'             => __( 'Edit Animal', 'akd' ),
		'view_item'             => __( 'View Animal', 'akd' ),
		'all_items'             => __( 'All Animals', 'akd' ),
		'search_items'          => __( 'Search Animal', 'akd' ),
		'parent_item_colon'     => __( 'Parent Animal:', 'akd' ),
		'not_found'             => __( 'No Animal found.', 'akd' ),
		'not_found_in_trash'    => __( 'No Animal found in Trash.', 'akd' ),
		'featured_image'        => _x( 'Animal Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'akd' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'akd' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'akd' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'akd' ),
	];

	$animal_args = [
		'labels'             => $animal_labels,
		'has_archive'        => true,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-pets',
		'menu_position'      => 9,
		'rewrite'            => [ 'slug' => 'animals' ],
		'supports'           => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields' ],
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
	];

	register_post_type( 'animals', $animal_args );
}
add_action( 'init', 'akd_register_posttype_animals' );

/**
 * Register child post type for the animals.
 *
 * @version 1.0.0
 */
function akd_register_posttype_species() {

	$labels = [
		'name'          => 'Species',
		'singular_name' => 'Species',
	];

	$args = [
		'labels'             => $labels,
		'has_archive'        => true,
		'hierarchical'       => true,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'show_in_menu'       => 'edit.php?post_type=animals',
		'supports'           => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields' ],
		'rewrite'            => [ 'slug' => 'species' ],
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
	];

	register_post_type( 'species', $args );
}
add_action( 'init', 'akd_register_posttype_species' );

/**
 * Additional custom taxonomies.
 *
 * @version 1.0.0
 */
function akd_add_custom_taxonomies() {

	// Animal categories taxonomy.
	register_taxonomy(
		'animal-categories',
		'animals',
		[
			'label'             => __( 'Animal Categories' ),
			'rewrite'           => [ 'slug' => 'animal-categories' ],
			'hierarchical'      => true,
			'public'            => true,
			'has_archive'       => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		]
	);

	// Animal tags taxonomy.
	register_taxonomy(
		'animal-tags',
		'animals',
		[
			'label'             => __( 'Animal Tags' ),
			'rewrite'           => [ 'slug' => 'animal-tags' ],
			'hierarchical'      => true,
			'has_archive'       => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		]
	);

	// Species taxonomy.
	register_taxonomy(
		'species-categories',
		'species',
		[
			'label'             => __( 'Species Categories' ),
			'rewrite'           => [ 'slug' => 'species-categories' ],
			'hierarchical'      => true,
			'has_archive'       => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		]
	);
}
add_action( 'init', 'akd_add_custom_taxonomies' );

/**
 * Admin notice for species post type.
 * Provide button for navigating to the taxonomy screen.
 *
 * @version 1.0.0
 */
add_action(
	'load-edit.php',
	function () {

		$screen = get_current_screen();
		if ( 'edit-species' === $screen->id ) {
			add_action(
				'all_admin_notices',
				function () {
					$taxonomy  = 'species-categories';
					$post_type = 'species';
					$admin_url = admin_url( 'edit-tags.php' );

					$taxonomy_url = add_query_arg(
						[
							'taxonomy'  => $taxonomy,
							'post_type' => $post_type,
						],
						$admin_url
					);
					?>
						<div>
							<a href="<?php echo esc_url( $taxonomy_url ); ?>">
								<button class="button button-primary button-large">
									<?php echo esc_html( 'View All Species Categories' ); ?>
								</button>
							<a>
						</div>
					<?php
				}
			);
		}
	}
);
