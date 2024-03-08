<?php
/**
 * Template for animal-category mega menu.
 */

$page_name        = ! empty( get_field( 'page_name', get_the_ID() ) ) ? get_field( 'page_name', get_the_ID() ) : '';
$background_color = ! empty( get_field( 'background_color', get_the_ID() ) ) ? get_field( 'background_color', get_the_ID() ) : '';
?>

<div class="banner-inner-page <?php echo esc_attr( $background_color ); ?>">
	<div class="banner-inner-title"><?php echo esc_html( $page_name ); ?></div>
</div>
