<?php
/**
 * Theme search template.
 *
 * @package akd
 * @version 1.0.0
 */
get_header();

$search_term = get_search_query();
?>

<main>
	<div class="blog-container">
		<div class="banner-inner-page">
			<div class="banner-inner-title"><?php echo esc_html( 'Search Results for: ' . $search_term ); ?></div>
		</div>

		<div class="blog-list-wrapper">
			<div class="container">
				<?php
				// Search query for animals.
				add_filter( 'posts_search', 'custom_exact_search', 10, 2 );
				$exact_search_args  = [
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'post_type'      => [ 'species', 'animals', 'post' ],
					's'              => $search_term,
					'fields'         => 'ids',
				];
				$exact_search_query = new WP_Query( $exact_search_args );
				$exact_search_id    = $exact_search_query->posts;
				remove_filter( 'posts_search', 'custom_exact_search', 10 );

				$animal_args   = [
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'post_type'      => [ 'species', 'animals', 'post' ],
					's'              => $search_term,
					'fields'         => 'ids',
				];
				$animal_query  = new WP_Query( $animal_args );
				$total_animals = $animal_query->posts;

				$total_search_results = array_unique( array_merge( $exact_search_id, $total_animals ) );

				if ( ! empty( $total_search_results ) ) {
					?>
						<div class="blog-list akd-search-listing" data-value="<?php echo esc_attr( serialize( $total_search_results ) ); ?>" data-blog-track="<?php echo esc_attr( count( $total_search_results ) ); ?>" data-index="6">
							<?php
							$search_index = 0;
							foreach ( $total_search_results as $result ) {
								if ( $search_index < 6 ) {
									$post_id    = $result;
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
										</a>
									<?php
								}
								$search_index++;
							}
							?>
						</div>
					<?php
				} else {
					?>
						<div class="blog-list akd-search-listing" data-blog-track="<?php echo esc_attr( $count_found_animals + $total_found_blogs ); ?>">
							<h3><?php echo esc_html( 'No Data Found.' ); ?></h3>
						</div>
					<?php
				}
				wp_reset_postdata();
				?>
				<a class="btn load-more-search" href="#"><?php echo esc_html( 'Load More' ); ?></a>
				<div class="loader search-result-loader" style="display:none">
					<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
