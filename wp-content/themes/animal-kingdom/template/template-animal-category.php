<?php
/**
 * Template Name: Animal Category Page
 *
 * Custom template for the animal category page.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();

// Get all the animal-category terms.
$category_terms = get_terms(
	[
		'taxonomy'   => 'animal-categories',
		'hide_empty' => true,
	]
);
$first_cat_id   = $category_terms[0]->term_id;
$first_cat_name = $category_terms[0]->name;

if ( isset( $_GET['category'] ) ) {
	$term_slug      = $_GET['category'];
	$term           = get_term_by( 'slug', $term_slug, 'animal-categories' );
	$first_cat_id   = $term->term_id;
	$first_cat_name = $term->name;
}
?>
<main>
	<div class="category-main-container">
		
		<?php get_template_part( 'template-parts/page', 'introduction' ); ?>
	
		<div class="category-page-wrapper">
			<div class="container">
				<div class="search-wrapper">
					<input type="text" placeholder="Search" id="animal-category-search">
				</div>
				<div class="category-tab-wrapper">
					<ul class="category-tab">
						<?php
						// Display animal-category terms.
						if ( ! empty( $category_terms ) ) {
							foreach ( $category_terms as $cat_term ) {
								$term_id      = $cat_term->term_id;
								$term_name    = $cat_term->name;
								$active_class = $term_id == $first_cat_id ? 'active' : '';
								?>
									<li class="category-tab-item animal-category-list <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>">
										<?php echo esc_html( $term_name ); ?>
									</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
				<div class="sorting-wrapper">
					<div class="sorting-btn" data-sort="DESC"><?php echo esc_html( 'SORT Z TO A' ); ?></div>
				</div>
				<div class="category-listing-wrapper">
					<?php
					$category_args       = [
						'post_type'      => 'animals',
						'post_status'    => 'publish',
						'posts_per_page' => 12,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => [
							[
								'taxonomy' => 'animal-categories',
								'field'    => 'term_id',
								'terms'    => $first_cat_id,
							],
						],
					];
					$get_animal_category = new WP_Query( $category_args );
					$total_animals       = $get_animal_category->found_posts;

					if ( $get_animal_category->have_posts() ) {
						?>
							<div class="category-listing-card-wrapper" data-animal-track="<?php echo esc_attr( $total_animals ); ?>">
								<?php
								while ( $get_animal_category->have_posts() ) {
									$get_animal_category->the_post();
									get_template_part( 'template-parts/animal', 'listing' );
								}
								wp_reset_postdata();
								?>
							</div>
						<?php
					} else {
						?>
							<div class="category-listing-card-wrapper">
								<h3><?php echo esc_html( 'No Data Found' ); ?></h3>
							</div>
						<?php
					}
					?>
					<div class="btn-wrapper">
						<a class="btn btn-brown load-more-animal-category" href="#">
							<?php echo esc_html( 'Load More' ); ?>
						</a>
					</div>
					<div class="loader animal-category-loader" style="display:none">
						<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
					</div>
				</div>
				<div class="loader animal-search-loader" style="display:none">
					<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
				</div>

				<!-- Favorite animals -->
				<div class="favorite-farm-animal-wrapper">
					<?php
					$favorite_args    = [
						'post_type'      => 'animals',
						'posts_per_page' => 4,
						'tax_query'      => [
							'relation' => 'AND',
							[
								'taxonomy' => 'animal-categories',
								'field'    => 'term_id',
								'terms'    => $first_cat_id,
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
						?>
							<h2 class="title-curve black"><?php echo esc_html( 'Favorite ' . $first_cat_name ); ?></h2>
							<div class="farm-animal-list-wrapper">
								<?php
								while ( $favorite_animals->have_posts() ) {
									$favorite_animals->the_post();
									get_template_part( 'template-parts/favorite', 'animal' );
								}
								?>
							</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	
		<?php
		// Explore wildlife stories.
		$stories_args  = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
		];
		$stories_query = new WP_Query( $stories_args );
		$blog_page     = get_page_by_title( 'Blogs' );
		$blog_page_url = get_permalink( $blog_page->ID );
		if ( $stories_query->have_posts() ) {
			?>
				<div class="explore-wild-life-wrapper">
					<div class="container">
						<h2 class="title-curve white center"><?php echo esc_html( 'Explore Wildlife & Nature Stories' ); ?></h2>
					</div>
					<div class="explore-wild-life-slider slider">
						<?php
						while ( $stories_query->have_posts() ) {
							$stories_query->the_post();
							$blog_id          = get_the_ID();
							$blog_url         = get_permalink( $blog_id );
							$blog_name        = get_the_title( $blog_id );
							$blog_img         = get_the_post_thumbnail_url( $blog_id, 'large' );
							$blog_author_id   = get_post_field( 'post_author', $blog_id );
							$blog_author_name = get_the_author_meta( 'display_name', $blog_author_id );
							$read_time        = ! empty( get_field( 'read_time', $blog_id ) ) ? get_field( 'read_time', $blog_id ) : '5';
							?>
								<div class="explore-wild-life-slider-item">
									<div class="explore-wild-life-slider-item-img-wrapper">
										<img class="img-cover" src="<?php echo esc_url( $blog_img ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $blog_id ) ); ?>">
									</div>
									<div class="explore-wild-life-slider-item-content">
										<a href="<?php echo esc_url( $blog_url ); ?>">
											<h6><?php echo esc_html( $blog_name ); ?></h6>
										</a>
										<div class="explore-wild-life-slider-item-author-details">
											<span><?php echo esc_html( $read_time . ' min read |' ); ?></span>
											<span><?php echo esc_html( 'By ' ); ?><strong><?php echo esc_html( $blog_author_name ); ?></strong></span>
										</div>
									</div>
								</div>
							<?php
						}
						wp_reset_postdata();
						?>
					</div>
					<a class="btn btn-white" href="<?php echo esc_url( $blog_page_url ); ?>">
						<?php echo esc_html( 'Read More' ); ?>
					</a>
				</div>
			<?php
		}
		?>
	</div>
</main>
<?php

get_footer();
