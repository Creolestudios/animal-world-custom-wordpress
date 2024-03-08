<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @version 1.0.0
 *
 * @package akd
 */

get_header();

// Blog details.
$post_id            = get_the_ID();
$featured_img_url   = get_the_post_thumbnail_url();
$post_title         = get_the_title();
$author_name        = get_the_author_meta( 'display_name', get_post_field( 'post_author' ) );
$read_time          = ! empty( get_field( 'read_time' ) ) ? get_field( 'read_time' ) : '5';
$blog_sidebar_image = ! empty( get_field( 'blog_sidebar_image' ) ) ? get_field( 'blog_sidebar_image' ) : '';
$post_tags          = get_the_tags();

// Query for favorite blogs.
$favorite_blog_args  = [
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'post_type'      => 'post',
	'tag'            => 'favorites',
	'post__not_in'   => [ $post_id ],
];
$favorite_blog_query = new WP_Query( $favorite_blog_args );
$blog_page           = get_page_by_title( 'Blogs' );
$blog_page_url       = get_permalink( $blog_page->ID );

// Query for related blogs.
$post_categories     = get_the_category( $post_id );
$category_ids        = wp_get_post_categories( $post_id );
$related_blog_args   = [
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'post_type'      => 'post',
	'category__in'   => $category_ids,
	'post__not_in'   => [ $post_id ],
];
$related_blog_query  = new WP_Query( $related_blog_args );
$related_total_posts = $related_blog_query->found_posts;
?>
<main>
	<div class="blog-detail-container blog-container">
		<div class="blog-detail-banner">
			<?php
			if ( $featured_img_url ) {
				?>
					<img class="modal-popup" src="<?php echo esc_url( $featured_img_url ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $post_id ) ); ?>" />
				<?php
			} else {
				$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
				?>
					<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
				<?php
			}
			?>
			<div class="content">
				<h2 class="text-white"><?php echo esc_html( $post_title ); ?></h2>
				<ul>
					<li class="text-white"><?php echo esc_html( $read_time . ' min read' ); ?></li>
					<li class="text-white"><?php echo esc_html( 'By ' ); ?><strong><?php echo esc_html( $author_name ); ?></strong></li>
				</ul>
			</div>
		</div>

		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>
		
		<!-- Blog details -->
		<div class="details-content">
			<div class="container">
				<div class="content">
					<div class="left">
						<?php the_content(); ?>
					</div>
					<div class="right">
						<?php
						if ( ! empty( $blog_sidebar_image ) ) {
							?>
								<img class="modal-popup" src="<?php echo esc_url( $blog_sidebar_image ); ?>" alt="detail-img" />
							<?php
						}
						?>
						<div class="tags-wrapper">
							<h5><?php echo esc_html( 'Tags:' ); ?></h5>
							<div class="tags">
								<?php
								foreach ( $post_tags as $tag ) {
									?>
										<div class="tag text-white"><?php echo esc_html( $tag->name ); ?></div>
									<?php
								}
								?>
							</div>
						</div>
						
						<?php echo do_shortcode( '[ads_section_layout layout="vertical-banner" class="horizontal"]' ); ?>

						<div class="social-share-wrapper">
							<h6><?php echo esc_html( 'Share the Post' ); ?></h6>
							<div class="social-share">
								<?php echo do_shortcode( '[Sassy_Social_Share]' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Favorites blogs  -->
		<?php
		if ( $favorite_blog_query->have_posts() ) {
			?>
				<div class="favourate-list-wrapper">
					<div class="container">
						<h2 class="text-white"><?php echo esc_html( 'Favorites' ); ?></h2>
						<div class="blog-list">
							<?php
							while ( $favorite_blog_query->have_posts() ) {
								$favorite_blog_query->the_post();
								get_template_part( 'template-parts/blog', 'listing' );
							}
							?>
						</div>
						<a class="btn btn-white" href="<?php echo esc_url( $blog_page_url . '?sort=favorites' ); ?>"><?php echo esc_html( 'See all Favorites' ); ?></a>
					</div>
				</div>
			<?php
			wp_reset_postdata();
		}
		?>

		<!-- Relates blogs  -->
		<?php
		if ( $related_blog_query->have_posts() ) {
			?>
				<div class="favourate-list-wrapper related-list-wrapper" data-related-blogs="<?php echo esc_attr( $related_total_posts ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
					<div class="container">
						<h2 class="text-white"><?php echo esc_html( 'Related Stories' ); ?></h2>
						<div class="blog-list related-blog-list">
							<?php
							while ( $related_blog_query->have_posts() ) {
								$related_blog_query->the_post();
								get_template_part( 'template-parts/blog', 'listing' );
							}
							?>
						</div>
						<a class="btn btn-white load-more-related-blogs" href="#"><?php echo esc_html( 'Read More' ); ?></a>
						<div class="loader related-blogs-loader" style="display:none">
							<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
						</div>
					</div>
				</div>
			<?php
			wp_reset_postdata();
		}
		?>
	</div>
</main>
<?php
get_footer();
