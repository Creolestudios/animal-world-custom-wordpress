<?php
/**
 * Template for animal-category mega menu.
 */

// Get all the animal-category terms.
$category_terms = get_terms(
	[
		'taxonomy'   => 'animal-categories',
		'hide_empty' => true,
	]
);
$first_cat_id   = $category_terms[0]->term_id;
?>

<div class="menu-popup-wrapper">
	<div class="menu-popup-category-wrapper">
		<ul>
			<?php
			// Display animal-category terms.
			if ( ! empty( $category_terms ) ) {
				foreach ( $category_terms as $cat_term ) {
					$term_id       = $cat_term->term_id;
					$term_name     = $cat_term->name;
					$active_class  = $term_id === $first_cat_id ? 'active' : '';
					$category_page = get_field( 'category_page', 'animal-categories_' . $term_id ) ? get_field( 'category_page', 'animal-categories_' . $term_id ) : '#';
					?>
						<a href="<?php echo esc_url( $category_page ); ?>">
							<li class="animal-category <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>">
								<?php echo esc_attr( $term_name ); ?>
							</li>
						</a>
					<?php
				}
			}
			?>
		</ul>
	</div>
	<div class="megamenu-category-item-wrapper">
		<?php
		if ( ! empty( $category_terms ) ) {
			foreach ( $category_terms as $cat_term ) {
				$term_id      = $cat_term->term_id;
				$term_name    = $cat_term->name;
				$term_slug    = $cat_term->slug;
				$active_class = $term_id === $first_cat_id ? 'flex' : 'none';

				$args = [
					'post_type'      => 'animals',
					'posts_per_page' => 14,
					'meta_query'     => [
						[
							'key'     => 'show_on_animal_menu',
							'value'   => true,
							'compare' => '=',
							'type'    => 'BOOLEAN',
						],
					],
					'tax_query'      => [
						[
							'taxonomy' => 'animal-categories',
							'field'    => 'term_id',
							'terms'    => $term_id,
						],
					],
					'orderby'        => 'title',
					'order'          => 'ASC',
				];

				$filtered_posts = new WP_Query( $args );
				if ( $filtered_posts->have_posts() ) {
					?>
					<ul class="megamenu-category-item" data-term-id="<?php echo esc_attr( $term_id ); ?>" style="display:<?php echo $active_class; ?>">
						<?php
						while ( $filtered_posts->have_posts() ) {
							$filtered_posts->the_post();

							$post_id    = get_the_ID();
							$post_title = get_the_title();
							$post_img   = get_the_post_thumbnail_url( $post_id, 'large' );
							$permalink  = get_permalink();
							?>
							<li>
								<a href="<?php echo esc_url( $permalink ); ?>">
									<?php
									if ( $post_img ) {
										?>
											<img class="img-cover" src="<?php echo esc_url( $post_img ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $post_id ) ); ?>">
										<?php
									} else {
										$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
										?>
											<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
										<?php
									}
									?>
									<span><?php echo esc_html( $post_title ); ?></span>
								</a>
							</li>
							<?php
						}
						?>
						<li class="seeall-cat">
							<a class="seeall-category" data-cat-id="<?php echo esc_attr( $term_slug ); ?>" href="#"><?php echo esc_html( 'See All ' . $term_name ); ?></a>
						</li>
					</ul>
					<?php
					wp_reset_postdata();
				}
			}
		}
		?>
	</div>
	<div class="loader loader-animal-menu" style="display:none">
		<img src="<?php echo esc_attr( AKD_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
	</div>
</div>
