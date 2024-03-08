<?php
/**
 * Template for did you know section.
 */

$selected_post_ids = get_field( 'did_you_know_section', get_the_ID() );

if ( ! empty( $selected_post_ids ) ) {
	?>
		<div class="did-you-know-slider-wrapper">
			<h2 class="did-you-know-title">
				<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/didyouknow-title.svg' ); ?>" alt="title" >
			</h2>
			<div class="did-you-know-slider slider">
				<?php
				foreach ( $selected_post_ids as $post_id ) {
					$post_title = get_the_title( $post_id );
					$post_image = get_the_post_thumbnail_url( $post_id, 'large' );
					$post_url   = get_permalink( $post_id );
					?>
						<div class="did-you-know-slider-item">
							<div class="did-you-know-slider-img-wrapper">
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
							<div class="did-you-know-slider-content">
								<h5>
									<?php echo esc_html( $post_title ); ?>
								</h5>
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
