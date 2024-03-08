<?php
/**
 * 404 template of theme
 *
 * @version 1.0.0
 * @package npx
 */

get_header();
?>

<div class="page-not-found-wrapper">
	<div class="container">
		<h2>404</h2>
		<p>Page Not Found !!</p>
		<a href="<?php echo esc_url( site_url() ); ?>" class="btn">Back Home</a>
	</div>
</div>

<?php
get_footer();
?>
