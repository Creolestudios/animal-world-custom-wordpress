<?php
/**
 * Template for slider banner section.
 */

$selected_post_ids = get_field( 'show_post_on_slider_banner', get_the_ID() );

if ( ! empty( $selected_post_ids ) ) {
	?>
		<div class="home-banner-slider-wrapper">
			<div class="home-banner-slider slider">
				<?php
				foreach ( $selected_post_ids as $post_id ) {
					$post_title = get_the_title( $post_id );
					$post_image = get_the_post_thumbnail_url( $post_id );
					$post_url   = get_permalink( $post_id );
					$blog_video = ! empty( get_field( 'blog_video', $post_id ) ) ? get_field( 'blog_video', $post_id ) : $post_image;
					?>
						<div class="home-banner-slider-item">
							<div class="home-banner-slider-img">
								<?php
								if ( $blog_video ) {
									?>
										<img class="img-cover" src="<?php echo esc_url( $blog_video ); ?>" alt="banner-img" >
									<?php
								} else {
									$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
									?>
										<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
									<?php
								}
								?>
							</div>
							<div class="sider-content-box-wrapper">
								<h1 class="sider-content-title">
									<?php echo esc_html( $post_title ); ?>
								</h1>
								<a class="btn btn-transparent-white" href="<?php echo esc_url( $post_url ); ?>">
									<?php echo esc_html( 'Explore Now' ); ?>
								</a>
							</div>
						</div>
					<?php
				}
				?>
			</div>
		</div>
	<?php
}
