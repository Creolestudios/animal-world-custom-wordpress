<?php
/**
 * The template for displaying detail page of species.
 *
 * @version 1.0.0
 * @package akd
 */
get_header();

$species_id             = get_the_ID();
$species_name           = get_the_title( $species_id );
$animal_slider_images   = get_field( 'animal_slider_images', $species_id );
$feature_image          = get_the_post_thumbnail_url( $species_id );
$no_image_thumbnail     = get_field( 'default_post_thumbnail_image', 'option' );
$animal_measurement     = get_field( 'animal_measurement', $species_id );
$species_terms          = get_the_terms( $species_id, 'species-categories' );
$threatened_level       = get_field( 'threatened_level', $species_id );
$sources                = get_field( 'sources', $species_id );
$animal_popup_gallery   = get_field( 'animal_popup_gallery', $species_id );
$animal_characteristics = get_field( 'animal_characteristics', $species_id );
$faqs_species           = get_field( 'faqs_species', $species_id );
$related_species        = get_field( 'related_species', $species_id );

$related_species_args = [
	'post_type'  => 'animals',
	'meta_query' => [
		[
			'key'     => 'select_species',
			'value'   => $species_id,
			'compare' => 'LIKE',
		],
	],
	'fields'     => 'ids',
];
$related_species_ids  = get_posts( $related_species_args );
$select_species       = get_field( 'select_species', $related_species_ids[0] );
?>
<main>
	<div class="animaldetail-container">
		<div class="banner-inner-page green">
			<div class="banner-inner-title"><?php echo esc_html( $species_name ); ?></div>
		</div>
		<div class="animal-detail-banner-slider-wrapper">
			<?php
			if ( ! empty( $animal_slider_images ) ) {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						foreach ( $animal_slider_images as $image_url ) {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover modal-popup" src="<?php echo esc_url( $image_url ); ?>" alt="species-image">
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
									<img class="img-cover modal-popup" src="<?php echo esc_url( $feature_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $species_id ) ); ?>">
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
			<div class="animal-measurement-detail">
				<?php
				if ( ! empty( $animal_measurement ) ) {
					foreach ( $animal_measurement as $characteristic ) {
						$label  = $characteristic['label'];
						$detail = $characteristic['detail'];
						?>
							<div class="animal-measurement-detail-wrapper">
								<div class="animal-measurement-item"><?php echo esc_html( $detail ); ?></div>
								<span class="animal-measurement-label"><?php echo esc_html( $label ); ?></span>
							</div>
						<?php
					}
				}
				?>
		   </div>
		</div>
		<div class="species-about-detail">
			<div class="container">
				<div class="species-about-detail-left">
					<h2 class="title-curve white"><?php echo esc_html( 'About' ); ?></h2>
					<?php
					if ( ! empty( $species_terms ) ) {
						?>
							<div class="type-of-cat-tags">
								<?php
								foreach ( $species_terms as $term ) {
									?>
										<span><?php echo esc_html( '#' . $term->name ); ?></span>
									<?php
								}
								?>
							</div>
						<?php
					}
					?>
					<p><?php the_content(); ?></p>
					<div class="threatened-wrapper">
						<span><?php echo esc_html( 'Threatened:' ); ?></span>
						<div class="threatened-list">
							<?php
							$color_classes = [
								'red'    => 'Extinct',
								'maroon' => 'Critically Endangered',
								'orange' => 'Endangered',
								'skin'   => 'Vulnerable',
								'sand'   => 'Near Threatened',
								'blue'   => 'Least Concern',
							];
							foreach ( $color_classes as $key => $value ) {
								$is_active = $key == $threatened_level ? 'active' : '';
								?>
									<div class="threatened-color tooltip <?php echo esc_attr( $key . ' ' . $is_active ); ?>">
										<span class="tooltiptext"><?php echo esc_html( $value ); ?></span>
									</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="species-about-detail-right">
					<div class="modal-popup jagged brown" style="background-image: url(<?php echo esc_url( $feature_image ); ?>)"> </div>
					<!-- <img class="img-cover modal-popup" src="<?php echo esc_url( $feature_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $species_id ) ); ?>"> -->
				</div>
			</div>
		</div>
		<?php
		if ( ! empty( $animal_characteristics ) ) {
			?>
				<div class="species-characteristics-detail">
					<div class="container">
						<div class="species-characteristics-left">
							<section class="tabs-wrapper">
								<div class="tabs-container">
									<div class="tabs-block">
										<div class="tabs">
											<?php
											$tab_index = 1;
											foreach ( $animal_characteristics as $detail ) {
												$characteristic        = $detail['characteristic'];
												$characteristic_detail = $detail['characteristic_detail'];
												$animal_image          = $detail['animal_image'];
												$features              = $detail['features'];
												$tab_check             = $tab_index == 1 ? 'checked="checked"' : '';
												?>
													<input type="radio" name="tabs" id="tab<?php echo esc_attr( $tab_index ); ?>" <?php echo esc_attr( $tab_check ); ?>/>
													<label for="tab<?php echo esc_attr( $tab_index ); ?>"><?php echo esc_html( $characteristic ); ?></label>
													<div class="tab">
														<div class="species-characteristic-tab-left">
															<h2 class="title-curve black-light"><?php echo esc_html( $characteristic ); ?></h2>
															<p><?php echo wp_kses_post( $characteristic_detail ); ?></p>
															<?php
															if ( ! empty( $features ) ) {
																?>
																<div class="characteristics-details">
																	<?php
																	foreach ( $features as $feature ) {
																		$feature_name  = $feature['feature_name'];
																		$feature_image = $feature['feature_image'];
																		?>
																			<div class="characteristics-detail-item">
																				<img class="img-cover" src="<?php echo esc_url( $feature_image ); ?>" alt="feature-image">
																				<span><?php echo esc_html( $feature_name ); ?></span>
																			</div>
																		<?php
																	}
																	?>
																</div>
																<?php
															}
															?>
														</div>
														<?php
														if ( ! empty( $animal_image ) ) {
															?>
																<div class="species-characteristic-tab-right">
																	<div class="jagged modal-popup" style="background-image: url(<?php echo esc_url( $animal_image ); ?>)"> </div>
																	<!-- <img class="img-cover modal-popup" src="<?php echo esc_url( $animal_image ); ?>" alt="animal-image"> -->
																</div>
															<?php
														}
														?>
													</div>
												<?php
												$tab_index++;
											}
											?>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="species-characteristics-right">
							<div class="add-banner vertical">
								<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="advertisement banner">
								<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="advertisement banner">
							</div>
						</div>
					</div>
				</div>
			<?php
		}
		?>
		<?php
		if ( ! empty( $animal_popup_gallery ) ) {
			?>
				<div class="see-all-picture-wrapper">
					<div class="container">
						<h2 class="title-curve white"><?php echo esc_html( $species_name . ' Pictures' ); ?></h2>
						<div class="see-all-picture-item-wrapper img-gallery-magnific">
							<?php
							foreach ( $animal_popup_gallery as $key => $image ) {
								?>
									<div class="magnific-img">
										<a class="image-popup-vertical-fit" href="<?php echo esc_url( $image ); ?>" title="">
											<div class="see-all-picture-item">
												<div class="jagged green" style="background-image: url(<?php echo esc_url( $image ); ?>)"> </div>
												<!-- <img class="img-cover" src="<?php echo esc_url( $image ); ?>" alt="gallery-image"> -->
											</div>
										</a>
									</div>
									<?php
							}
							?>
						</div>
						<div class="btn-wrapper">
							<a href="#" class="btn btn-white see-all-pictures"><?php echo esc_html( 'See All Pictures' ); ?></a>
						</div>
					</div>
				</div>
			<?php
		}
		?>

		<?php
		// Species FAQ's.
		if ( ! empty( $faqs_species ) ) {
			?>
				<div class="faq-wrapper">
					<div class="container">
						<h2 class="title-curve brown"><?php echo esc_html( 'FAQ’s' ); ?></h2>
						<div class="faqs">
							<?php
							$faq_index = 1;
							foreach ( $faqs_species as $faq ) {
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
		?>

		<?php
		if ( ! empty( $related_species ) ) {
			?>
				<div class="explore-the-diversity">
					<div class="container">
						<h2 class="title-curve black"><?php echo esc_html( 'Related Family Species' ); ?></h2>
						<div class="explore-the-diversity-card-wrapper">
							<?php
							foreach ( $related_species as $related_species_id ) {
								if ( $related_species_id != $species_id ) {
									$related_species_name  = get_the_title( $related_species_id );
									$related_species_image = get_the_post_thumbnail_url( $related_species_id, 'large' );
									$related_species_url   = get_the_permalink( $related_species_id );
									?>
										<a href="<?php echo esc_url( $related_species_url ); ?>" class="explore-the-diversity-card-item">
											<div class="explore-the-diversity-card-img-wrapper">
												<img class="img-cover" src="<?php echo esc_url( $related_species_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $species_id ) ); ?>">
											</div>
											<h5 class="explore-the-diversity-card-content white"><?php echo esc_html( $related_species_name ); ?></h5>
										</a>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			<?php
		}
		?>

		<?php
		if ( ! empty( $sources ) ) {
			?>
				<div class="source-wrapper">
					<div class="container">
						<h6><?php echo esc_html( 'Sources' ); ?></h6>
						<ul>
							<?php
							foreach ( $sources as $source ) {
								?>
									<li><?php echo esc_html( $source['detail'] ); ?></li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php
		}
		?>

		<?php
		if ( ! empty( $animal_popup_gallery ) ) {
			?>
				<div class="animal-gallery-popup-main" style="display:none">
					<div class="animal-gallery-popup-wrapper">
						<div>
							<a href="javascript:void(0)" class="close"></a>
							<div class="slider slider-for">
								<?php
								foreach ( $animal_popup_gallery as $image ) {
									?>
										<div><img src="<?php echo esc_url( $image ); ?>" alt="gallery-image"></div>
									<?php
								}
								?>
							</div>
							<div class="slider slider-nav">
								<?php
								foreach ( $animal_popup_gallery as $image ) {
									?>
										<div><img src="<?php echo esc_url( $image ); ?>" alt="gallery-image"></div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php
		}
		?>
	</div>
</main>
<?php
get_footer();
