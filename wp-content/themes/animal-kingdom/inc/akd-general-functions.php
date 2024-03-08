<?php
/**
 * Theme general functions.
 *
 * @version 1.0.0
 *
 * @package akd
 */


/**
 * Function for image alt.
 *
 * @param post_id $post_id ID of a post.
 *
 * @version 1.0.0
 */
function akd_get_img_alt( $post_id ) {
	$post_img_id = get_post_thumbnail_id( $post_id );
	return ( ! empty( get_post_meta( $post_img_id, '_wp_attachment_image_alt', true ) ) ) ? get_post_meta( $post_img_id, '_wp_attachment_image_alt', true ) : get_the_title( $post_id );
}

/**
 * Indicates whether the IP address passed in as the parameter is valid or not.
 *
 * @version 1.0.0
 *
 * @param ip $ip IP Address.
 *
 * @return [boolean]
 */
function validate_ip( $ip ) {
	if ( filter_var(
		$ip,
		FILTER_VALIDATE_IP,
		FILTER_FLAG_IPV4 |
		FILTER_FLAG_IPV6 |
		FILTER_FLAG_NO_PRIV_RANGE |
		FILTER_FLAG_NO_RES_RANGE
	) === false ) {
		return false;
	}
	return true;
}

/**
 * Get IP Address.
 *
 * @version 1.0.0
 */
function akd_get_ip_address() {
	// check for shared internet/ISP IP.
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && validate_ip( sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
	}
	// check for IPs passing through proxies.
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		// check if multiple ips exist in var.
		$iplist = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
		foreach ( $iplist as $ip ) {
			if ( validate_ip( $ip ) ) {
				return $ip;
			}
		}
	}
	if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED'] ) && validate_ip( $_SERVER['HTTP_X_FORWARDED'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) );
	}
	if ( isset( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) && validate_ip( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) );
	}
	if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_FORWARDED_FOR'] ) && validate_ip( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) );
	}
	if ( isset( $_SERVER['HTTP_FORWARDED'] ) && ! empty( $_SERVER['HTTP_FORWARDED'] ) && validate_ip( $_SERVER['HTTP_FORWARDED'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) );
	}
	// return unreliable ip since all else failed.
	return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); //phpcs:ignore
}

/**
 * Set view count in the postmeta for the respective post.
 *
 * @param [integer] $post_id  $post_id ID of single post.
 *
 * @version 1.0.0
 */
function akd_set_view( $post_id ) {
	$stored_ip_addresses = get_post_meta( $post_id, 'view_ip', true );
	if ( $stored_ip_addresses ) {
		if ( count( $stored_ip_addresses ) ) {
			$current_ip = akd_get_ip_address();
			if ( ! in_array( $current_ip, $stored_ip_addresses ) ) {
				$meta_key = 'entry_views';
				if ( empty( get_post_meta( $post_id, $meta_key, true ) ) ) {
					$new_viewed_count = 1;
				} else {
					$view_post_meta   = get_post_meta( $post_id, $meta_key, true );
					$new_viewed_count = $view_post_meta + 1;
				}
				update_post_meta( $post_id, $meta_key, $new_viewed_count );
				$stored_ip_addresses[] = $current_ip;
				update_post_meta( $post_id, 'view_ip', $stored_ip_addresses );
			}
		}
	} else {
		$meta_key = 'entry_views';
		if ( empty( get_post_meta( $post_id, $meta_key, true ) ) ) {
			$new_viewed_count = 1;
		} else {
			$view_post_meta   = get_post_meta( $post_id, $meta_key, true );
			$new_viewed_count = $view_post_meta + 1;
		}
		update_post_meta( $post_id, $meta_key, $new_viewed_count );
		$ip_arr[] = akd_get_ip_address();
		update_post_meta( $post_id, 'view_ip', $ip_arr );
	}
}

/**
 * Ajax callback to get animals filter by category(Mega menu).
 *
 * @version 1.0.0
 */
function akd_filter_animals_by_category() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$category_id    = isset( $_POST['category_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$term      = get_term( $category_id, 'animal-categories' );
		$term_slug = $term->slug;
		if ( $term instanceof WP_Term && ! is_wp_error( $term ) ) {
			$term_name = $term->name;
			$term_url  = get_term_link( $term );
		}

		$args = [
			'post_type'      => 'animals',
			'posts_per_page' => 14,
			'meta_query'     => [
				[
					'key'     => 'show_on_animal_menu',
					'value'   => true,
					'compare' => '=',
					'type'    => 'BOOLEAN',
				],
			],
			'tax_query'      => [
				[
					'taxonomy' => 'animal-categories',
					'field'    => 'term_id',
					'terms'    => $category_id,
				],
			],
			'orderby'        => 'title',
			'order'          => 'ASC',
		];

		$filtered_posts = new WP_Query( $args );

		// If the query has posts, return the post details.
		ob_start();
		if ( $filtered_posts->have_posts() ) {
			while ( $filtered_posts->have_posts() ) {
				$filtered_posts->the_post();

				$post_id    = get_the_ID();
				$post_title = get_the_title();
				$post_img   = get_the_post_thumbnail_url( $post_id, 'large' );
				$permalink  = get_permalink();
				?>
					<li>
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php
							if ( $post_img ) {
								?>
									<img class="img-cover" src="<?php echo esc_url( $post_img ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $post_id ) ); ?>">
								<?php
							} else {
								$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
								?>
									<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
								<?php
							}
							?>
							<span><?php echo esc_html( $post_title ); ?></span>
						</a>
					</li>
				<?php
			}
			?>
				<li class="seeall-cat">
					<a class="seeall-category" data-cat-id="<?php echo esc_attr( $term_slug ); ?>" href="#"><?php echo esc_html( 'See All ' . $term_name ); ?></a>
				</li>
			<?php
			$html = ob_get_clean();
			ob_end_clean();
			wp_reset_postdata();
		}
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
	wp_die();
}
add_action( 'wp_ajax_akd_filter_animals_by_category', 'akd_filter_animals_by_category' );
add_action( 'wp_ajax_nopriv_akd_filter_animals_by_category', 'akd_filter_animals_by_category' );

/**
 * Ajax callback to load more animal blogs.
 *
 * @version 1.0.0
 */
function akd_load_more_animal_blogs() {
	$security_nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$blog_paged       = isset( $_POST['blog_paged'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_paged'] ) ) : '';
	$blog_per_page    = isset( $_POST['blog_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_per_page'] ) ) : '';
	$filter_sort_type = isset( $_POST['filter_sort_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_sort_type'] ) ) : '';
	$filter_cat_value = isset( $_POST['filter_cat_value'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_cat_value'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$blog_args = [
			'posts_per_page' => $blog_per_page,
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'paged'          => $blog_paged,
		];

		// Add args depending on sorting type.
		if ( $filter_sort_type == 'popular' ) {
			$blog_args['tag'] = 'popular';
		} elseif ( $filter_sort_type == 'favorites' ) {
			$blog_args['tag'] = 'favorites';
		} elseif ( $filter_sort_type == 'highlights' ) {
			$blog_args['tag'] = 'highlights';
		}

		// Add category id depends on filter.
		if ( $filter_cat_value !== 0 || ! empty( $filter_cat_value ) ) {
			$blog_args['category_name'] = $filter_cat_value;
		}
		$blog_query = new WP_Query( $blog_args );
		if ( $blog_query->have_posts() ) {
			ob_start();
			while ( $blog_query->have_posts() ) {
				$blog_query->the_post();

				get_template_part( 'template-parts/blog', 'listing' );
			}
		}
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
	wp_die();
}
add_action( 'wp_ajax_akd_load_more_animal_blogs', 'akd_load_more_animal_blogs' );
add_action( 'wp_ajax_nopriv_akd_load_more_animal_blogs', 'akd_load_more_animal_blogs' );

/**
 * Ajax callback to filter animal blogs.
 *
 * @version 1.0.0
 */
function akd_get_blogs_by_filter() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$blog_per_page  = isset( $_POST['blog_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_per_page'] ) ) : '';
	$sort_type      = isset( $_POST['sort_type'] ) ? sanitize_text_field( wp_unslash( $_POST['sort_type'] ) ) : '';
	$cat_value      = isset( $_POST['cat_value'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_value'] ) ) : '';
	$search_value   = isset( $_POST['search_value'] ) ? sanitize_text_field( wp_unslash( $_POST['search_value'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$blog_args = [
			'posts_per_page' => $blog_per_page,
			'post_status'    => 'publish',
			'post_type'      => 'post',
		];

		// Add args depending on sorting type.
		if ( $sort_type == 'popular' ) {
			$blog_args['tag'] = 'popular';
		} elseif ( $sort_type == 'favorites' ) {
			$blog_args['tag'] = 'favorites';
		} elseif ( $sort_type == 'highlights' ) {
			$blog_args['tag'] = 'highlights';
		}

		// Add category id depends on filter.
		if ( $cat_value !== 0 ) {
			$blog_args['category_name'] = $cat_value;
		}

		// If search keywords are found.
		if ( ! empty( $search_value ) ) {
			$blog_args['s'] = $search_value;
		}
		$blog_query  = new WP_Query( $blog_args );
		$total_blogs = $blog_query->found_posts;

		if ( $blog_query->have_posts() ) {
			ob_start();
			while ( $blog_query->have_posts() ) {
				$blog_query->the_post();

				get_template_part( 'template-parts/blog', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No Data Found' ); ?></h3>
			<?php
		}
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
		$response = [
			'html'       => $html,
			'totalblogs' => $total_blogs,
		];
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
	wp_die();
}
add_action( 'wp_ajax_akd_get_blogs_by_filter', 'akd_get_blogs_by_filter' );
add_action( 'wp_ajax_nopriv_akd_get_blogs_by_filter', 'akd_get_blogs_by_filter' );

/**
 * Ajax callback to submit contact form inquiry.
 *
 * @version 1.0.0
 */
function akd_submit_inquiry_details() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$name           = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email          = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
	$phone_number   = isset( $_POST['phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['phone_number'] ) ) : '';
	$reason         = isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '';
	$message        = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$errors = []; // Array to store validation errors.

		// Validate name.
		if ( strlen( $name ) < 1 ) {
			$errors['name'] = 'Please enter your name.';
		} elseif ( strlen( $name ) < 2 ) {
			$errors['name'] = 'Name must be at least 2 characters long.';
		}

		// Validate email.
		if ( strlen( $email ) < 1 ) {
			$errors['email'] = 'Please enter your email.';
		} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$errors['email'] = 'Please enter a valid email address.';
		}

		// Validate phone number.
		if ( strlen( $phone_number ) < 1 ) {
			$errors['phone_number'] = 'Please enter a phone number.';
		}

		// Validate reason.
		if ( strlen( $reason ) < 1 || $reason == 0 ) {
			$errors['reason'] = 'Please select a reason.';
		}

		// Validate message.
		if ( strlen( $message ) < 1 ) {
			$errors['message'] = 'Please enter a message.';
		} elseif ( strlen( $message ) < 2 ) {
			$errors['message'] = 'Message must be at least 2 characters long.';
		}

		if ( ! empty( $errors ) ) {
			// Send validation errors as JSON response.
			wp_send_json_error(
				[
					'message' => 'Validation errors',
					'errors'  => $errors,
				]
			);
			wp_die();
		}

		// Send inquiry details to the admin.
		$inquiry_email_id    = ! empty( get_field( 'inquiry_email_id', 'option' ) ) ? get_field( 'inquiry_email_id', 'option' ) : '';
		$multiple_recipients = [
			$inquiry_email_id,
		];

		$subj = "Inquiry - Contact Us - $name";
		$body = "
			Name: $name
			Email: $email
			Phone Number: $phone_number
			Reason: $reason
			Message: $message
		";
		wp_mail( $multiple_recipients, $subj, $body );

		// Prepare the response data.
		$response = [
			'response' => 'Sent successfully.',
		];

		// Send the JSON-encoded response with success status.
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_submit_inquiry_details', 'akd_submit_inquiry_details' );
add_action( 'wp_ajax_nopriv_akd_submit_inquiry_details', 'akd_submit_inquiry_details' );

/**
 * Ajax callback to loadmore related blogs.
 *
 * @version 1.0.0
 */
function akd_load_more_related_blogs() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$post_id        = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
	$blog_per_page  = isset( $_POST['blog_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_per_page'] ) ) : '';
	$blog_paged     = isset( $_POST['blog_paged'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_paged'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$category_ids       = wp_get_post_categories( $post_id );
		$related_blog_args  = [
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'category__in'   => $category_ids,
			'post__not_in'   => [ $post_id ],
			'posts_per_page' => $blog_per_page,
			'paged'          => $blog_paged,
		];
		$related_blog_query = new WP_Query( $related_blog_args );

		if ( $related_blog_query->have_posts() ) {
			ob_start();
			while ( $related_blog_query->have_posts() ) {
				$related_blog_query->the_post();

				get_template_part( 'template-parts/blog', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No Data Found' ); ?></h3>
			<?php
		}
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_load_more_related_blogs', 'akd_load_more_related_blogs' );
add_action( 'wp_ajax_nopriv_akd_load_more_related_blogs', 'akd_load_more_related_blogs' );

/**
 * Ajax callback to filter animals by category.
 *
 * @version 1.0.0
 */
function akd_get_animals_by_category() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$category_id    = isset( $_POST['category_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_id'] ) ) : '';
	$per_page       = isset( $_POST['per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['per_page'] ) ) : '';
	$search_term    = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';
	$sort_value     = isset( $_POST['sort_value'] ) ? sanitize_text_field( wp_unslash( $_POST['sort_value'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$category_name = get_term( $category_id )->name;
		// Animals from their category.
		$category_args = [
			'post_type'      => 'animals',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'tax_query'      => [
				[
					'taxonomy' => 'animal-categories',
					'field'    => 'term_id',
					'terms'    => $category_id,
				],
			],
		];

		// If search keywords are found.
		if ( ! empty( $search_term ) ) {
			$category_args['s'] = $search_term;
		}

		// For sorting.
		if ( isset( $sort_value ) && $sort_value == 'ASC' ) {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'ASC';
		} elseif ( isset( $sort_value ) && $sort_value == 'DESC' ) {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'DESC';
		} else {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'ASC';
		}

		$get_animal_category = new WP_Query( $category_args );
		$total_animals       = $get_animal_category->found_posts;

		if ( $get_animal_category->have_posts() ) {
			ob_start();
			while ( $get_animal_category->have_posts() ) {
				$get_animal_category->the_post();
				get_template_part( 'template-parts/animal', 'listing' );
			}
			wp_reset_postdata();
		} else {
			?>
				<h3><?php echo esc_html( 'No Data Found' ); ?></h3>
			<?php
		}
		$html = ob_get_clean();
		ob_end_clean();

		// Favorite animals.
		$favorite_args    = [
			'post_type'      => 'animals',
			'posts_per_page' => 4,
			'tax_query'      => [
				'relation' => 'AND',
				[
					'taxonomy' => 'animal-categories',
					'field'    => 'term_id',
					'terms'    => $category_id,
				],
				[
					'taxonomy' => 'animal-tags',
					'field'    => 'slug',
					'terms'    => 'favorites',
				],
			],
		];
		$favorite_animals = new WP_Query( $favorite_args );
		if ( $favorite_animals->have_posts() ) {
			ob_start();
			?>
				<h2 class="title-curve black"><?php echo esc_html( 'Favorite ' . $category_name ); ?></h2>
				<div class="farm-animal-list-wrapper">
					<?php
					while ( $favorite_animals->have_posts() ) {
						$favorite_animals->the_post();
						get_template_part( 'template-parts/favorite', 'animal' );
					}
					?>
				</div>
			<?php
			wp_reset_postdata();
		}
		$fav_content = ob_get_clean();
		ob_end_clean();

		$response = [
			'html'          => $html,
			'fav_content'   => $fav_content,
			'total_animals' => $total_animals,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_get_animals_by_category', 'akd_get_animals_by_category' );
add_action( 'wp_ajax_nopriv_akd_get_animals_by_category', 'akd_get_animals_by_category' );

/**
 * Ajax callback to loadmore animals based on category.
 *
 * @version 1.0.0
 */
function akd_load_more_animals_by_category() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$category_id    = isset( $_POST['category_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_id'] ) ) : '';
	$per_page       = isset( $_POST['per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['per_page'] ) ) : '';
	$paged          = isset( $_POST['paged'] ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : '';
	$search_term    = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';
	$sort_value     = isset( $_POST['sort_value'] ) ? sanitize_text_field( wp_unslash( $_POST['sort_value'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$category_args = [
			'post_type'      => 'animals',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged,
			'tax_query'      => [
				[
					'taxonomy' => 'animal-categories',
					'field'    => 'term_id',
					'terms'    => $category_id,
				],
			],
		];

		// For sorting.
		if ( isset( $sort_value ) && $sort_value == 'ASC' ) {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'ASC';
		} elseif ( isset( $sort_value ) && $sort_value == 'DESC' ) {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'DESC';
		} else {
			$category_args['orderby'] = 'title';
			$category_args['order']   = 'ASC';
		}

		// For search value.
		if ( ! empty( $search_term ) ) {
			$category_args['s'] = $search_term;
		}

		$get_animal_category = new WP_Query( $category_args );

		if ( $get_animal_category->have_posts() ) {
			ob_start();
			while ( $get_animal_category->have_posts() ) {
				$get_animal_category->the_post();

				get_template_part( 'template-parts/animal', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No Data Found' ); ?></h3>
			<?php
		}
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_load_more_animals_by_category', 'akd_load_more_animals_by_category' );
add_action( 'wp_ajax_nopriv_akd_load_more_animals_by_category', 'akd_load_more_animals_by_category' );

/**
 * Ajax callback to load more search results.
 *
 * @version 1.0.0
 */
function akd_load_more_search_results() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$search_value   = isset( $_POST['search_value'] ) ? sanitize_text_field( wp_unslash( $_POST['search_value'] ) ) : '';
	$start_index    = isset( $_POST['start_index'] ) ? sanitize_text_field( wp_unslash( $_POST['start_index'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$search_value_array = unserialize( $search_value );
		$end_index          = min( $start_index + 6 - 1, count( $search_value_array ) - 1 );
		ob_start();
		for ( $i = $start_index; $i <= $end_index;$i++ ) {
			$post_id    = $search_value_array[ $i ];
			$post_url   = get_permalink( $post_id );
			$post_title = get_the_title( $post_id );
			$post_image = get_the_post_thumbnail_url( $post_id, 'large' );
			?>
				<a href="<?php echo esc_url( $post_url ); ?>" class="list">
					<div class="img-content">
						<?php
						if ( $post_image ) {
							?>
								<img src="<?php echo esc_url( $post_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $post_id ) ); ?>" />
							<?php
						} else {
							$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
							?>
								<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
							<?php
						}
						?>
					</div>
					<div class="content">
						<span>
							<h4><?php echo esc_html( $post_title ); ?></h4>
						</span>
					</div>
				</div>
			<?php
		}

		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_load_more_search_results', 'akd_load_more_search_results' );
add_action( 'wp_ajax_nopriv_akd_load_more_search_results', 'akd_load_more_search_results' );

/**
 * Ajax callback to load more search results.
 *
 * @version 1.0.0
 */
function akd_load_more_animal_species() {
	$security_nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$species_id          = isset( $_POST['species_id'] ) ? sanitize_text_field( wp_unslash( $_POST['species_id'] ) ) : '';
	$species_start_index = isset( $_POST['species_start_index'] ) ? sanitize_text_field( wp_unslash( $_POST['species_start_index'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$species_id_arr = unserialize( $species_id );
		$end_index      = min( $species_start_index + 6 - 1, count( $species_id_arr ) - 1 );

		ob_start();
		for ( $i = $species_start_index; $i <= $end_index;$i++ ) {
			$species_id      = $species_id_arr[ $i ];
			$species_name    = get_the_title( $species_id );
			$species_image   = get_the_post_thumbnail_url( $species_id, 'large' );
			$species_url     = get_the_permalink( $species_id );
			$species_content = get_post_field( 'post_content', $species_id );
			$trim_content    = wp_trim_words( $species_content, 40, '...' );
			$species_terms   = get_the_terms( $species_id, 'species-categories' );
			?>
				<div class="type-of-cat-item">
					<div class="type-of-cat-img-wrapper">
						<?php
						if ( ! empty( $species_image ) ) {
							?>
								<img class="img-cover" src="<?php echo esc_url( $species_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $species_id ) ); ?>">
							<?php
						} else {
							$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
							?>
								<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
							<?php
						}
						?>
					</div>
					<div class="type-of-cat-content-wrapper">
						<h4><?php echo esc_html( $species_name ); ?></h4>
						<p><?php echo esc_html( $trim_content ); ?></p>
						<div class="type-of-cat-tags">
							<?php
							if ( ! empty( $species_terms ) ) {
								foreach ( $species_terms as $term ) {
									?>
										<span><?php echo esc_html( $term->name ); ?></span>
									<?php
								}
							}
							?>
						</div>
						<a href="<?php echo esc_url( $species_url ); ?>" class="btn btn-transparent-white"><?php echo esc_html( 'Read More' ); ?></a>
					</div>
				</div>
			<?php
		}

		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_akd_load_more_animal_species', 'akd_load_more_animal_species' );
add_action( 'wp_ajax_nopriv_akd_load_more_animal_species', 'akd_load_more_animal_species' );

/**
 * Function to get exact match from search value.
 *
 * @param   [type] $search search value.
 * @param   [type] $wp_query wp query.
 */
function custom_exact_search( $search, $wp_query ) {
	global $wpdb;

	if ( empty( $search ) ) {
		return $search; // No search term, do nothing.
	}
	// Get the search term and escape it.
	$search_term = $wpdb->esc_like( $wp_query->get( 's' ) );

	// Check for exact matches.
	$exact_match_query = $wpdb->prepare(
		"
        SELECT ID
        FROM {$wpdb->posts}
        WHERE post_type IN ('animals', 'species', 'post')
        AND post_status = 'publish'
        AND post_title = %s
    ",
		$search_term
	);

	$exact_match_result = $wpdb->get_col( $exact_match_query );

	if ( ! empty( $exact_match_result ) ) {
		// If there is an exact match, return it first.
		return ' AND ' . $wpdb->posts . '.ID IN (' . implode( ',', $exact_match_result ) . ')';
	}

	// If no exact match, proceed with the partial match logic.
	$search = $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . $search_term . '%' );

	return $search;
}
