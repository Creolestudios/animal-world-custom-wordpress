<?php
/**
 * Load JS and CSS files of theme.
 *
 * @version 1.0.0
 *
 * @package akd
 */

/**
 * Load needed js and css files of theme.
 *
 * @version 1.0.0
 */
function akd_enqueue_scripts_styles() {

	$animal_list     = get_page_by_title( 'Animals' );
	$animal_list_url = get_permalink( $animal_list->ID );

	// Load CSS files.
	wp_enqueue_style( 'slick-min', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css' );
	wp_enqueue_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css' );
	wp_enqueue_style( 'all-style', AKD_THEME_URI . '/dist/assets/css/app.min.css', [], '1.0.0' );

	// Load JS files.
	wp_enqueue_script( 'global-script', AKD_THEME_URI . '/assets/js/akd-global.js', [ 'jquery' ], '1.0.0', false );
	wp_localize_script(
		'global-script',
		'globalScript',
		[
			'home_url'        => home_url( '/' ),
			'animal_list_url' => $animal_list_url,
			'ajax_url'        => admin_url( 'admin-ajax.php' ),
			'nonce'           => wp_create_nonce( 'auth-token' ),
		]
	);
	if ( is_page_template( 'template/template-contactus.php' ) ) {
		wp_enqueue_script( 'contact-form-script', AKD_THEME_URI . '/assets/js/akd-contact-form.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'contact-form-script',
			'contactScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_page_template( 'template/template-animal-category.php' ) ) {
		wp_enqueue_script( 'animal-cat-script', AKD_THEME_URI . '/assets/js/akd-animal-category-list.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'animal-cat-script',
			'animalCatScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_single() ) {
		wp_enqueue_script( 'blog-detail-script', AKD_THEME_URI . '/assets/js/akd-blog-detail.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'blog-detail-script',
			'blogDetailScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_search() ) {
		wp_enqueue_script( 'search-script', AKD_THEME_URI . '/assets/js/akd-search.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'search-script',
			'searchScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	wp_enqueue_script( 'jquery-validator-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js', [], '1.0.0', false );
	wp_enqueue_script( 'slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', [ 'jquery' ], '1.0.0', false );
	wp_enqueue_script( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', [ 'jquery' ], '1.0.0', false );
	wp_enqueue_script( 'custom-js', AKD_THEME_URI . '/dist/assets/js/custom.js', [ 'jquery' ], '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'akd_enqueue_scripts_styles' );
