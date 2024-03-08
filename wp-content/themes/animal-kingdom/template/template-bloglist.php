<?php
/**
 * Template Name: Blog List Page
 *
 * Custom template for the Blog List Page.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();

$categories = get_categories();
?>

<main>
	<div class="blog-container">

		<?php get_template_part( 'template-parts/page', 'introduction' ); ?>

		<div class="container">
			<div class="filter-group">
				<div class="search-bar">
					<div class="search">
						<input type="text" class="blog-list-search" name="search" placeholder="Search..." />
					</div>
				</div>
				<div class="category-wrap">
					<div class="category">
						<div class="select-dropdown">
							<div data-value="0" class="select-dropdown__button category_blog_filter">
								<span><?php echo esc_html( 'Select Category' ); ?></span> <i class="arrow"></i>
							</div>
							<ul class="select-dropdown__list">
								<?php
								foreach ( $categories as $category ) {
									$category_name = $category->name;
									$category_slug = $category->slug;
									?>
										<li data-value="<?php echo esc_attr( $category_slug ); ?>" class="select-dropdown__list-item blog_category_item"><?php echo esc_html( $category_name ); ?></li>
									<?php
								}
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="popular-wrap">
					<div class="popular">
						<div class="select-dropdown">
							<div data-value="0" class="select-dropdown__button sort_blog_filter">
								<span><?php echo esc_html( 'Recently added' ); ?></span> <i class="arrow"></i><i class="filter"></i>
							</div>
							<ul class="select-dropdown__list">
								<li data-value="recent" class="select-dropdown__list-item blog_sort_item"><?php echo esc_html( 'Recently added' ); ?></li>
								<li data-value="popular" class="select-dropdown__list-item blog_sort_item"><?php echo esc_html( 'Popular' ); ?></li>
								<li data-value="favorites" class="select-dropdown__list-item blog_sort_item"><?php echo esc_html( 'Favorites' ); ?></li>
								<li data-value="highlights" class="select-dropdown__list-item blog_sort_item"><?php echo esc_html( 'Highlights' ); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>

		<div class="blog-list-wrapper">
			<div class="container">
				<?php
				$blog_args   = [
					'posts_per_page' => 6,
					'post_status'    => 'publish',
					'post_type'      => 'post',
				];
				$blog_query  = new WP_Query( $blog_args );
				$total_posts = $blog_query->found_posts;
				if ( $blog_query->have_posts() ) {
					?>
					<div class="blog-list akd-blog-listing" data-blog-track="<?php echo esc_attr( $total_posts ); ?>">
						<?php
						while ( $blog_query->have_posts() ) {
							$blog_query->the_post();

							get_template_part( 'template-parts/blog', 'listing' );
						}
						?>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
				<a class="btn blog-list-read-more-btn" href="#"><?php echo esc_html( 'Read More' ); ?></a>
				<div class="loader blog-list-loader" style="display:none">
					<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
