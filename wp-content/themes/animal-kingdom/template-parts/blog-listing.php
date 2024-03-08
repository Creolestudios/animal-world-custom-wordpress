<?php
/**
 * Template part for blog listing.
 * .
 */

$post_id          = get_the_ID();
$post_url         = get_permalink();
$post_title       = get_the_title();
$post_image       = get_the_post_thumbnail_url( $post_id, 'large' );
$post_author_id   = get_post_field( 'post_author', $post_id );
$post_author_name = get_the_author_meta( 'display_name', $post_author_id );
$read_time        = ! empty( get_field( 'read_time', $post_id ) ) ? get_field( 'read_time', $post_id ) : '5';
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
		<ul>
			<li><?php echo esc_html( $read_time . ' min read' ); ?></li>
			<li><?php echo esc_html( 'By ' ); ?><strong><?php echo esc_html( $post_author_name ); ?></strong></li>
		</ul>
	</div>
</a>
