<?php
/**
 * Template to show favorite animals.
 */

$animal_id      = get_the_ID();
$animal_url     = get_permalink( $animal_id );
$animal_name    = get_the_title( $animal_id );
$animal_img     = get_the_post_thumbnail_url( $animal_id, 'large' );
$animal_content = get_post_field( 'post_content', $post_id );
$trim_content   = wp_trim_words( $animal_content, 20, '...' );
?>

<div class="farm-animal-list-item">
	<div class="farm-animal-list-item-img-wrapper">
		<img class="img-cover" src="<?php echo esc_url( $animal_img ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $animal_id ) ); ?>">
	</div>
	<div class="farm-animal-list-content-wrapper">
		<h5><?php echo esc_html( $animal_name ); ?></h5>
		<p><?php echo esc_html( $trim_content ); ?></p>
		<a href="<?php echo esc_url( $animal_url ); ?>"><?php echo esc_html( 'Learn More' ); ?></a>
	</div>
</div>
