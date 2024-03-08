<?php
/**
 * Footer section of theme.
 *
 * @version 1.0.0
 *
 * @package akd
 */

$default_logo_url = AKD_THEME_URI . '/dist/assets/images/Animalkingdomlogo.svg';
$site_logo_url    = ! empty( get_field( 'site_logo', 'option' ) ) ? get_field( 'site_logo', 'option' ) : $default_logo_url;
?>
<div id="myModal" class="modal">
	<span class="close">&times;</span>
	<img class="modal-content" src="">
</div>
<footer>
	<div class="container">
		<div class="footer-logo-wrapper">
			<a href="<?php echo esc_url( home_url() ); ?>">
				<img class="img-cover" src="<?php echo esc_url( $site_logo_url ); ?>" alt="footer logo">
			</a>
		</div>
		<div class="footer-menu-link-wrapper">
			<ul class="footer-menu-links">
				<?php
				// Get the menu items for the "footer-menu" location.
				$footer_menu_items = wp_get_nav_menu_items( 'footer-menu' );
				if ( $footer_menu_items ) {
					foreach ( $footer_menu_items as $menu_item ) {
						?>
							<li>
								<a href="<?php echo esc_url( $menu_item->url ); ?>">
									<?php echo esc_html( $menu_item->title ); ?>
								</a>
							</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<div class="social-link-wrapper">
			<span><?php echo esc_html( 'Follow us for more' ); ?></span>

			<?php
			$social_media_information = get_field( 'social_media_information', 'option' );
			if ( $social_media_information ) {
				?>
					<ul class="social-links">
						<?php
						foreach ( $social_media_information as $detail ) {
							$social_media_img = $detail['icon'];
							$social_media_url = $detail['link'];
							?>
								<li>
									<a href="<?php echo esc_url( $social_media_url ); ?>">
										<img class="img-cover" src="<?php echo esc_url( $social_media_img ); ?>" alt="icon">
									</a>
								</li>
							<?php
						}
						?>
					</ul>
				<?php
			}
			?>
		</div>
	</div>
</footer>
</body>
</html>
