<?php
/**
 * Template for explore wild life section.
 */

$selected_post_ids = get_field( 'explore_wildlife_nature_stories', get_the_ID() );
$blog_page         = get_page_by_title( 'Blogs' );
$blog_page_url     = get_permalink( $blog_page->ID );

if ( ! empty( $selected_post_ids ) ) {
	?>
		<div class="explore-wild-life-wrapper">
			<div class="container">
				<h2 class="title-curve white"><?php echo esc_html( 'Explore Wildlife & Nature Stories' ); ?></h2>
			</div>
			<div class="explore-wild-life-slider slider">
				<?php
				foreach ( $selected_post_ids as $post_id ) {
					$post_title  = get_the_title( $post_id );
					$post_image  = get_the_post_thumbnail_url( $post_id, 'large' );
					$post_url    = get_permalink( $post_id );
					$author_name = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
					$read_time   = ! empty( get_field( 'read_time', $post_id ) ) ? get_field( 'read_time', $post_id ) : '5';
					?>
						<div class="explore-wild-life-slider-item">
							<div class="explore-wild-life-slider-item-img-wrapper">
								<?php
								if ( ! empty( $post_image ) ) {
									?>
										<img class="img-cover" src="<?php echo esc_url( $post_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $post_id ) ); ?>" >
									<?php
								} else {
									$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
									?>
										<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail" >
									<?php
								}
								?>
							</div>
							<div class="explore-wild-life-slider-item-content">
								<a href="<?php echo esc_url( $post_url ); ?>">
									<h6><?php echo esc_html( $post_title ); ?></h6>
								</a>
								<div class="explore-wild-life-slider-item-author-details">
									<span><?php echo esc_html( $read_time . ' min read |' ); ?> </span>
									<span><?php echo esc_html( 'By' ); ?> <strong><?php echo esc_html( $author_name ); ?></strong></span>
								</div>
							</div>
						</div>
					<?php
				}
				?>
			</div>
			<a class="btn btn-white" href="<?php echo esc_url( $blog_page_url ); ?>">
				<?php echo esc_html( 'Discover More' ); ?>
			</a>
		</div>
	<?php
}
