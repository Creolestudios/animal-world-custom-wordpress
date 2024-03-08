<?php
/**
 * General shortcodes of theme.
 *
 * @package akd
 */

/**
 * Shortcode to manage Ads section layout
 *
 * @version 1.0.0
 * @param attributes $atts Attributes of shortcode.
 */
function akd_ads_script_layout( $atts ) {
	ob_start();

	if ( 'horizontal-banner' === strtolower( $atts['layout'] ) ) {
		if ( get_field( 'horizontal_banner', 'options' ) ) {
			?>
				<div class="add-banner">
					<?php get_field( 'horizontal_banner', 'options' ); ?>
				</div>
			<?php
		} else {
			?>
				<div class="add-banner">
					<img src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="add-banner" />
				</div>
			<?php
		}
	}
	if ( 'vertical-banner' === strtolower( $atts['layout'] ) ) {
		if ( get_field( 'vertical_banner', 'options' ) ) {
			?>
				<div class="add-banner vertical">
					<?php get_field( 'vertical_banner', 'options' ); ?>
				</div>
			<?php
		} else {
			?>
				<div class="add-banner <?php echo esc_attr($atts['class']) ?>">
					<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/sidebar-ads.png' ); ?>" alt="detail-img" />
					<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/sidebar-ads.png' ); ?>" alt="detail-img" />
				</div>
			<?php
		}
	}

	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode( 'ads_section_layout', 'akd_ads_script_layout' );
