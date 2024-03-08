<?php
/**
 * The template for displaying detail page of animals.
 *
 * @version 1.0.0
 * @package akd
 */

get_header();

$animal_id          = get_the_ID();
$animal_name        = get_the_title( $animal_id );
$feature_image      = get_the_post_thumbnail_url( $animal_id );
$animal_gallery     = get_field( 'animal_gallery', $animal_id );
$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
$animal_faqs        = get_field( 'faqs_animals', $animal_id );
$sources            = get_field( 'sources', $animal_id );
$species_details    = get_field( 'species_details', $animal_id );
?>
<main>
	<div class="animaldetail-container">
		<div class="banner-inner-page green">
			<div class="banner-inner-title"><?php the_title(); ?></div>
		</div>
		<div class="animal-detail-banner-slider-wrapper">
			<?php
			if ( ! empty( $animal_gallery ) ) {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						foreach ( $animal_gallery as $image ) {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover modal-popup" src="<?php echo esc_url( $image ); ?>" alt="animal-gallery">
								</div>
							<?php
						}
						?>
					</div>
				<?php
			} else {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						if ( ! empty( $feature_image ) ) {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover modal-popup" src="<?php echo esc_url( $feature_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $animal_id ) ); ?>">
								</div>
							<?php
						} else {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="thumbnail-image">
								</div>
							<?php
						}
						?>
					</div>
				<?php
			}
			?>
		</div>

		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>

		<div class="container">
			<div class="about-animal-detail-wrapper">
				<div class="about-animal-detail">
					<h2 class="title-curve black"><?php echo esc_html( 'About ' . $animal_name ); ?></h2>
					<div class="animal-content">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="about-animal-detail-sidebar">
					<?php echo do_shortcode( '[ads_section_layout layout="vertical-banner" class="vertical"]' ); ?>
				</div>
			</div>
		</div>

		<?php
		// Animal species.
		if ( ! empty( $species_details ) ) {
			?>
				<div class="types-of-cat">
					<div class="container">
						<h2 class="title-curve white"><?php echo esc_html( 'Types of ' . $animal_name ); ?></h2>
						<div class="type-of-cat-item-wrapper" data-species-start="6" data-species-track="<?php echo esc_attr( count( $species_details ) ); ?>" data-species_id="<?php echo esc_attr( serialize( $species_details ) ); ?>">
							<?php
							foreach ( $species_details as $detail ) {
								$species_name    = $detail['title'];
								$species_image   = $detail['image'];
								$species_content = $detail['description'];
								$species_id      = $detail['select_species_page'][0];
								?>
									<div class="type-of-cat-item">
										<div class="type-of-cat-img-wrapper">
											<?php
											if ( ! empty( $species_image ) ) {
												?>
													<div class="jagged modal-popup green" style="background-image: url(<?php echo esc_url( $species_image ); ?>)"> </div>

													<!-- <img class="img-cover modal-popup" src="<?php echo esc_url( $species_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $species_id ) ); ?>"> -->
												<?php
											} else {
												$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
												?>
													<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
												<?php
											}
											?>
										</div>
										<div class="type-of-cat-content-wrapper">
											<h4><?php echo esc_html( $species_name ); ?></h4>
											<p><?php echo esc_html( $species_content ); ?></p>
											<?php
											if ( ! empty( $species_id ) ) {
												$species_url = get_permalink( $species_id );
												?>
													<a href="<?php echo esc_url( $species_url ); ?>" class="btn btn-transparent-white"><?php echo esc_html( 'Read More' ); ?></a>
												<?php
											}
											?>
										</div>
									</div>
								<?php
							}
							?>
						</div>
						<!-- <div class="btn-wrapper">
							<a href="#" class="btn btn-white load-more-species"><?php // echo esc_html( 'Load More' ); ?></a>
						</div> -->
						<!-- <div class="loader animal-species-loader" style="display:none">
							<img src="<?php // echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
						</div> -->
					</div>
				</div>
			<?php
		}
		?>

		<?php
		// Animal FAQ's.
		if ( ! empty( $animal_faqs ) ) {
			?>
				<div class="faq-wrapper">
					<div class="container">
						<h2 class="title-curve brown"><?php echo esc_html( 'FAQ’s' ); ?></h2>
						<div class="faqs">
							<?php
							$faq_index = 1;
							foreach ( $animal_faqs as $faq ) {
								$question = $faq['question'];
								$answer   = $faq['answer'];
								$active   = $faq_index == 1 ? 'active' : '';
								?>
									<div class="faq <?php echo esc_attr( $active ); ?>">
										<div class="header">
											<h3><?php echo esc_html( $faq_index . '. ' . $question ); ?></h3>
											<div class="arrow"></div>
										</div>
										<div class="content">
											<p><?php echo wp_kses_post( $answer ); ?></p>
										</div>
									</div>
								<?php
								$faq_index++;
							}
							?>
						</div>
					</div>
				</div>
			<?php
		}

		// Content sources.
		if ( ! empty( $sources ) ) {
			?>
				<div class="source-wrapper">
					<div class="container">
						<h6><?php echo esc_html( 'Sources' ); ?></h6>
						<ul>
							<?php
							foreach ( $sources as $detail ) {
								?>
									<li><?php echo esc_html( $detail['detail'] ); ?></li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php
		}
		?>
	</div>
</main>
<?php
get_footer();
