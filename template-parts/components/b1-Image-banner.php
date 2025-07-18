<?php if (have_rows('page_header_image')): ?>
	<div class="image-banner swiper swiper-image">
		<div class="swiper-wrapper">

			<?php while (have_rows('page_header_image')): the_row();
				$image_id = get_sub_field('image');
				$title = get_sub_field('title') ?: get_the_title();
				$text = get_sub_field('text') ?: '';
				$caption = get_sub_field('caption') ?: '';
				$link = get_sub_field('link');
				$image_url = wp_get_attachment_image_url($image_id, 'large') ?: get_template_directory_uri() . '/images/placeholder.png';
			?>
				<div class="swiper-slide image-cover"
					style="background-image:url(<?php echo esc_url($image_url); ?>);"
					data-title="<?php echo esc_attr($caption); ?>"
					role="img"
					aria-label="<?php echo esc_attr($caption ?: $title); ?>">

					<div class="swiper-inner padding-50 white-text">
						<div class="title-button">
							<h1 class="fw-extrabold fs-900 uppercase margin-b-30 animate__animated" style="opacity:0;">
								<?php echo esc_html($title); ?>
							</h1>
							<?php if (!empty($link)): ?>
								<a href="<?php echo esc_url($link['url']); ?>"
									class="btn white outline arrow animate__animated"
									target="<?php echo esc_attr($link['target'] ?: '_self'); ?>"
									style="opacity:0;">
									<?php echo esc_html($link['title']); ?>
								</a>
							<?php endif; ?>
						</div>

						<?php if (is_page('Sustainability')): ?>
							<div class="sustainability-stats data-points-items">
								<p class="sustainability-stats__heading fw-bold fs-500 uppercase">
									<?php echo esc_html($text); ?>
								</p>

								<?php if (have_rows('sustainability_stats')): ?>
									<div class="sustainability-stats__items">
										<?php while (have_rows('sustainability_stats')): the_row();
											$stat_icon = get_sub_field('sustainability_stat_icon');
											$stat_number = get_sub_field('sustainability_stat_big_number') ?: 0;
											$stat_suffix = get_sub_field('sustainability_stat_suffix') ?: '';
											$stat_text = get_sub_field('sustainability_stat_text') ?: '';
										?>
											<div class="sustainability-stats__stat">
												<?php if (!empty($stat_icon)): ?>
													<img src="<?php echo esc_url($stat_icon['url']); ?>" alt="<?php echo esc_attr($stat_text); ?>" loading="lazy" />
												<?php endif; ?>
												<div>
													<p class="fs-800 fw-bold animate__animated animate__delay-1s value margin-b-10"
														data-to="<?php echo esc_attr($stat_number); ?>"
														data-suffix="<?php echo esc_attr($stat_suffix); ?>"
														data-prefix=""
														data-animation="">
														0
													</p>
													<p><?php echo esc_html($stat_text); ?></p>
												</div>
											</div>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php else: ?>
							<p class="fw-bold fs-600 uppercase animate__animated" style="opacity:0;">
								<?php echo esc_html($text); ?>
							</p>
						<?php endif; ?>
					</div>
					<div class="overlay"></div>
				</div>
			<?php endwhile; ?>
		</div>

		<?php if (count(get_field('page_header_image')) > 1): ?>
			<div class="swiper-button-next swiper-button-next-image white-text">
				<p class="fw-regular fs-100 uppercase margin-r-15">
					NEXT: <span class="fw-bold"></span>
				</p>
				<div class="circle-container">
					<svg class="circle-svg" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
						<circle class="circle-background" cx="25" cy="25" r="23"></circle>
						<circle class="circle-progress circle-progress-image" cx="25" cy="25" r="23"></circle>
					</svg>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>