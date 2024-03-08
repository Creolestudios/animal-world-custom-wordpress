<?php
/**
 * Template Name: About Us Page
 *
 * Custom template for the about page.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();

$default_logo_url        = AKD_THEME_URI . '/dist/assets/images/Animalkingdomlogo.svg';
$default_founder_url     = AKD_THEME_URI . '/dist/assets/images/author-image.png';
$site_logo_url           = ! empty( get_field( 'site_logo', 'option' ) ) ? get_field( 'site_logo', 'option' ) : $default_logo_url;
$page_name               = ! empty( get_field( 'page_name' ) ) ? get_field( 'page_name' ) : 'About';
$background_color        = ! empty( get_field( 'background_color' ) ) ? get_field( 'background_color' ) : '';
$about_details           = ! empty( get_field( 'about_details' ) ) ? get_field( 'about_details' ) : '';
$mission_vision_title    = ! empty( get_field( 'mission_vision_title' ) ) ? get_field( 'mission_vision_title' ) : 'Mission & Vision';
$mission_vision_content  = ! empty( get_field( 'mission_vision_content' ) ) ? get_field( 'mission_vision_content' ) : '';
$about_founder_title     = ! empty( get_field( 'about_founder_title' ) ) ? get_field( 'about_founder_title' ) : 'About Founder';
$founder_image           = ! empty( get_field( 'founder_image' ) ) ? get_field( 'founder_image' ) : $default_founder_url;
$founder_description     = ! empty( get_field( 'founder_description' ) ) ? get_field( 'founder_description' ) : 'Dr. Phillip Imler became passionate about traveling at an early age while helping his father plan family vacations. He has been employed by or working with the travel industry for over 30 years with travel experiences happening for well over 40 years. He has explored over 70 countries across six continents, and he has had the opportunity to experience many “once-in-a-lifetime” type travel adventures. He is constantly exploring new destinations and new opportunities for adventures. “If I go, I want to make sure I do it right."';
$founder_name            = ! empty( get_field( 'founder_name' ) ) ? get_field( 'founder_name' ) : 'Dr. Phillip Imler';
$founder_designation     = ! empty( get_field( 'founder_designation' ) ) ? get_field( 'founder_designation' ) : 'Product Owner';
$explore_animals_content = ! empty( get_field( 'explore_animals_content' ) ) ? get_field( 'explore_animals_content' ) : '';
$inquiry_label           = ! empty( get_field( 'inquiry_label' ) ) ? get_field( 'inquiry_label' ) : 'Updates, Inquiries, and Proposals';
$inquiry_description     = ! empty( get_field( 'inquiry_description' ) ) ? get_field( 'inquiry_description' ) : "The world is home to an incredible diversity of animals, from the smallest insects to the largest whales. These animals are all unique and fascinating, and they play an important role in our planet's ecosystems . This article will explore some of the world's most amazing animals in more detail. We will learn about their unique features, behaviors, and habitats. We will also discuss the threats facing these animals and what we can do to help protect them.";
$inquiry_cta             = ! empty( get_field( 'inquiry_cta' ) ) ? get_field( 'inquiry_cta' ) : '';
?>
<main>
	<div class="aboutus-container">
	
		<?php get_template_part( 'template-parts/page', 'introduction' ); ?>

		<div class="container">
			<div class="aboutus-top-section">
				<div class="aboutus-top-left">
					<img class="img-cover" src="<?php echo esc_url( $site_logo_url ); ?>" alt="site logo" >
				</div>
				<div class="aboutus-top-right">
					<?php
					if ( ! empty( $about_details ) && isset( $about_details ) ) {
						?>
							<p><?php echo wp_kses_post( $about_details ); ?></p>
						<?php
					}
					?>
				</div>
			</div>
			<div class="mission-vision-main">
				<?php
				if ( $mission_vision_content ) {
					?>
						<h2 class="title-curve orange center"><?php echo esc_html( $mission_vision_title ); ?></h2>
						<div class="mission-vision-wrapper">
							<?php
							foreach ( $mission_vision_content as $content ) {
								?>
									<div class="mission-vision-item">
										<div class="mission-vision-content">
											<?php echo wp_kses_post( $content['content'] ); ?>
										</div>
									</div>
								<?php
							}
							?>
						</div>
					<?php
				}
				?>
			</div>
			<div class="about-founder favorite-farm-animal-wrapper">
				<h2 class="title-curve black center"><?php echo esc_html( $about_founder_title ); ?></h2>

				<div class="farm-animal-list-wrapper">
					<div class="farm-animal-list-item">
						<div class="farm-animal-list-item-img-wrapper">
							<img class="img-cover" src="<?php echo esc_url( $founder_image ); ?>" alt="author" >
						</div>
						<div class="farm-animal-list-content-wrapper">
							<p><?php echo wp_kses_post( $founder_description ); ?></p>
							<h5><?php echo esc_html( $founder_name ); ?></h5>
							<span><?php echo esc_html( $founder_designation ); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="most-amazing-animal-main">
				<div class="most-amazing-animal-wrapper">
					<?php
					if ( $explore_animals_content ) {
						foreach ( $explore_animals_content as $explore_cotent ) {
							?>
								<div class="most-amazing-animal">
									<div class="most-amazing-animal-content-wrapper">
										<div class="most-amazing-animal-content">
											<h2 class="title-curve black"><?php echo esc_html( $explore_cotent['title'] ); ?></h2>
											<p><?php echo wp_kses_post( $explore_cotent['description'] ); ?></p>
											<a class="btn btn-lightBrown" href="<?php echo esc_attr( $explore_cotent['button_cta']['url'] ); ?>">
												<?php echo esc_html( $explore_cotent['button_cta']['title'] ); ?>
											</a>
										</div>
									</div>
									<div class="most-amazing-animal-img-wrapper combined">
										<img class="img-cover" src="<?php echo esc_url( $explore_cotent['image'] ); ?>" alt="explore-img" >
									</div>
								</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<div class="contact-us-newsletter-wrapper">
			<div class="contact-us-newsletter">
				<h3 class="text-white"><?php echo esc_html( $inquiry_label ); ?></h3>
				<p class="text-white"><?php echo wp_kses_post( $inquiry_description ); ?></p>
				<a class="btn btn-white" href="<?php echo esc_attr( $inquiry_cta['url'] ); ?>"><?php echo esc_html( $inquiry_cta['title'] ); ?></a>
			</div>
		</div>
	</div>
</main>
<?php

get_footer();
