<?php
/**
 * Template Name: Privacy Policy
 *
 * Custom template for the Privacy Policy.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();
?>
<main>
	<div class="policy-container">
		<?php get_template_part( 'template-parts/page', 'introduction' ); ?>
		<div class="container">
			<?php
			$policy_repeater = get_field( 'policy', get_the_ID() );
			if ( ! empty( $policy_repeater ) ) {
				?>
					<div class="policy-inner">
						<div class="left">
							<ul class="tab-list">
								<?php
								$counter = 1;
								foreach ( $policy_repeater as $policy ) {
									$policy_label  = $policy['policy_label'];
									$active_policy = $counter == 1 ? 'active' : '';
									?>
										<li class="<?php echo esc_attr( $active_policy ); ?>">
										<a href="#<?php echo esc_attr( sanitize_title( $policy_label ) ); ?>">
											<label>
												<span><?php echo esc_html( $policy_label ); ?></span>
												<i></i>
											</label>
										</a>
										</li>
									<?php
									$counter++;
								}
								?>
							</ul>
							<?php echo do_shortcode( '[ads_section_layout layout="vertical-banner" class="vertical"]' ); ?>
						</div>
						<div class="right">
							<div class="tab-contents">
								<?php
								foreach ( $policy_repeater as $policy ) {
									$policy_label = $policy['policy_label'];
									?>
										<div id="<?php echo esc_attr( sanitize_title( $policy_label ) ); ?>" class="tab-content active">
											<h2><?php echo esc_html( $policy_label ); ?></h2>
											<?php
											foreach ( $policy['policy_description'] as $description ) {
												?>
													<p><?php echo $description['description']; ?></p>
												<?php
											}
											?>
										</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php
			}
			?>

			<?php
			// FAQ's section.
			$faqs_repeater = get_field( 'faqs', get_the_ID() );
			if ( ! empty( $faqs_repeater ) ) {
				?>
					<div class="faq-wrapper">
						<h2><?php echo esc_html( 'FAQ’s' ); ?></h2>
						<div class="faqs">
							<?php
							$index = 1;
							foreach ( $faqs_repeater as $faq ) {
								$question = $faq['question'];
								$answer   = $faq['answer'];
								$active   = $index == 1 ? 'active' : '';
								?>
									<div class="faq <?php echo esc_attr( $active ); ?>">
										<div class="header">
											<h3><?php echo esc_html( $index . '. ' . $question ); ?></h3>
											<div class="arrow"></div>
										</div>
										<div class="content">
											<p><?php echo esc_html( $answer ); ?></p>
										</div>
									</div>
								<?php
								$index++;
							}
							?>
						</div>
					</div>
				<?php
			}
			?>
		</div>
	</div>
</main>
<?php
get_footer();
