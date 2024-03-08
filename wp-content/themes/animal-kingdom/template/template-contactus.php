<?php
/**
 * Template Name: Contact Us Page
 *
 * Custom template for the Contact Us page.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();

$default_cover_img        = AKD_THEME_URI . '/dist/assets/images/contatc-us-img.png';
$social_media_information = get_field( 'social_information', 'option' );
$contact_us_image         = ! empty( get_field( 'contact_us_image', 'option' ) ) ? get_field( 'contact_us_image', 'option' ) : $default_cover_img;
$contact_details          = ! empty( get_field( 'contact_details', 'option', false ) ) ? get_field( 'contact_details', 'option', false ) : '';
?>
<main>
	<div class="contactus-container">
	
		<?php get_template_part( 'template-parts/page', 'introduction' ); ?>

		<div class="container">
			<h2 class="title-curve black"><?php echo esc_html( 'Contact Form' ); ?></h2>
			<div class="contact-form-wrapper">
				<div class="contact-left">
					<form class="inquiry-form">
						<div class="form-group">
							<label for="name"><?php echo esc_html( 'Name' ); ?></label>
							<input type="text" class="form-field inquiry-name" name="name" placeholder="Enter your full name" />
							<span class="error" id="name-error"></span>
						</div>
						<div class="form-group">
							<label for="email"><?php echo esc_html( 'Email Address' ); ?></label>
							<input type="text" class="form-field inquiry-email" name="email" placeholder="Enter your email address" />
						</div>
						<div class="form-group">
							<label for="phone"><?php echo esc_html( 'Phone Number' ); ?></label>
							<div class="phone-number-wrapper">
								<div class="phone-number-left">
									<input type="text" class="inquiry-country" placeholder="+91" />
								</div>
								<div class="phone-number-right">
									<input type="number" class="inquiry-phone" placeholder="Enter your phone number" min="0" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="reason"><?php echo esc_html( 'Reason' ); ?></label>
							<div class="select-dropdown">
								<div data-value="0" class="select-dropdown__button inquiry-reason">
									<span><?php echo esc_html( 'Select Reason' ); ?></span>
									<i class="arrow"></i>
								</div>
								<ul class="select-dropdown__list">
									<li data-value="Content Inquiry" class="select-dropdown__list-item"><?php echo esc_html( 'Content Inquiry' ); ?></li>
									<li data-value="Partnership Collaboration" class="select-dropdown__list-item"><?php echo esc_html( 'Partnership Collaboration' ); ?></li>
									<li data-value="News & Media" class="select-dropdown__list-item"><?php echo esc_html( 'News & Media' ); ?></li>
									<li data-value="Other" class="select-dropdown__list-item"><?php echo esc_html( 'Other' ); ?></li>
								</ul>
							</div>
						</div>
						<div class="form-group">
							<label for="message"><?php echo esc_html( 'Message' ); ?></label>
							<textarea class="inquiry-message" name="message" cols="30" rows="10" placeholder="Type your message here "></textarea>
						</div>
						<div class="contact-submit">
							<input class="btn btn-lightBrown submit-inquiry" type="submit" value="submit">
							<span id="submit-error" class="error"></span>
							<span class="success-response"></span>
						</div>
						<div class="loader contact-form-loader" style="display:none">
							<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
						</div>
					</form>
				</div>
				<div class="contact-right">
					<img class="img-cover" src="<?php echo esc_url( $contact_us_image ); ?>" alt="contact-us-img">
				</div>
			</div>

			<div class="contact-footer">
				<h2 class="title-curve black"><?php echo esc_html( 'Contact Details' ); ?></h2>
				<div class="contact-footer-address">
					<?php echo $contact_details; ?>
				</div>
				<div class="social-link-wrapper">
					<?php
					if ( $social_media_information ) {
						?>
							<ul class="social-links">
								<?php
								foreach ( $social_media_information as $detail ) {
									$social_media_img = $detail['icon'];
									$social_media_url = $detail['link'];
									?>
										<li>
											<a href="<?php echo esc_attr( $social_media_url ); ?>">
												<img class="img-cover" src="<?php echo esc_url( $social_media_img ); ?>" alt="">
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
		</div>
	</div>
</main>
<?php

get_footer();
