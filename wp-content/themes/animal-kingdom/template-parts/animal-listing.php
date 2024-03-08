<?php
/**
 * Templpate part for animal listing.
 */

$animal_id   = get_the_ID();
$animal_url  = get_permalink( $animal_id );
$animal_name = get_the_title( $animal_id );
$animal_img  = get_the_post_thumbnail_url( $animal_id, 'large' );
?>
<a href="<?php echo esc_url( $animal_url ); ?>" class="category-listing-card-item">
	<div class="category-listing-card-img-wrapper">
		<?php
		if ( $animal_img ) {
			?>
				<img class="img-cover" src="<?php echo esc_url( $animal_img ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $animal_id ) ); ?>">
			<?php
		} else {
			$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
			?>
				<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
			<?php
		}
		?>
	</div>
	<h6 class="category-listing-card-content"><?php echo esc_html( $animal_name ); ?></h6>
</a>
<?php
