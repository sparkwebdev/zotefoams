<?php
// Get field data using safe helper functions
$title        = zotefoams_get_sub_field_safe('multi_item_carousel_title', '', 'string');
$behaviour    = zotefoams_get_sub_field_safe('multi_item_carousel_behaviour', '', 'string'); // 'pick', 'children', or 'manual'
$page_id      = zotefoams_get_sub_field_safe('multi_item_carousel_parent_id', 0, 'int');
$manual_slides = zotefoams_get_sub_field_safe('multi_item_carousel_slides', [], 'array');
$isVariant    = zotefoams_get_sub_field_safe('multi_item_carousel_variant', false, 'boolean');
$wrapperClass = $isVariant ? 'multi-item-carousel multi-item-carousel--variant' : 'multi-item-carousel';
$slideClass   = $isVariant ? 'swiper-slide black-bg white-text' : 'swiper-slide';
$btnClass     = $isVariant ? 'btn white outline' : 'btn black outline';

// Generate classes to match original structure exactly
$container_classes = 'multi-item-carousel-container padding-t-b-100 theme-none';

?>

<div class="<?php echo $container_classes; ?>">
	<div class="cont-m">

		<div class="title-strip margin-b-30">
			<?php if ($title): ?>
				<h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="multi-swiper-button-prev">
						<img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="multi-swiper-button-next">
						<img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
		</div>

		<div class="swiper <?php echo esc_attr($wrapperClass); ?>">
			<div class="swiper-wrapper">

				<?php
				if ($behaviour === 'pick') {
					$page_ids = zotefoams_get_sub_field_safe('multi_item_carousel_page_ids', [], 'array');

					foreach ($page_ids as $id) {
						$title   = get_the_title($id);
						$link    = get_permalink($id);
						$excerpt = get_the_excerpt($id);
						$image   = get_the_post_thumbnail_url($id, 'thumbnail-product');
				?>
						<div class="<?php echo esc_attr($slideClass); ?>">
							<h3 class="fs-600 fw-bold"><?php echo esc_html($title); ?></h3>
							<?php if ($excerpt): ?>
								<p><?php echo esc_html($excerpt); ?></p>
							<?php endif; ?>
							<?php if ($image): ?>
								<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
							<?php endif; ?>
							<a href="<?php echo esc_url($link); ?>" class="<?php echo esc_attr($btnClass); ?>">Read More</a>
						</div>
					<?php }
				} elseif ($behaviour === 'children') {
					$children = zotefoams_get_child_pages($page_id);

					foreach ($children as $child) {
						$title   = get_the_title($child->ID);
						$link    = get_permalink($child->ID);
						$excerpt = get_the_excerpt($child->ID);
						$image   = get_the_post_thumbnail_url($child->ID, 'thumbnail-product') ?: Zotefoams_Image_Helper::get_image_url(get_post_thumbnail_id($child->ID), 'thumbnail-product', 'thumbnail');
					?>
						<div class="<?php echo esc_attr($slideClass); ?>">
							<h3 class="fs-600 fw-bold"><?php echo esc_html($title); ?></h3>
							<?php if ($excerpt): ?>
								<p><?php echo esc_html($excerpt); ?></p>
							<?php endif; ?>
							<?php if ($image): ?>
								<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
							<?php endif; ?>
							<a href="<?php echo esc_url($link); ?>" class="<?php echo esc_attr($btnClass); ?>">Read More</a>
						</div>
					<?php }
				} elseif ($behaviour === 'manual' && $manual_slides) {
					foreach ($manual_slides as $slide) {
						$title   = $slide['multi_item_carousel_slide_title'] ?? '';
						$text    = $slide['multi_item_carousel_slide_text'] ?? '';
						$button  = $slide['multi_item_carousel_slide_button'] ?? [];
						$image   = Zotefoams_Image_Helper::get_image_url($slide['multi_item_carousel_slide_image'] ?? [], 'thumbnail-product', 'thumbnail', false);

						if (!$title && !$text && !$image && empty($button)) continue;
					?>
						<div class="<?php echo esc_attr($slideClass); ?>">
							<?php if ($title): ?>
								<h3 class="fs-600 fw-bold"><?php echo esc_html($title); ?></h3>
							<?php endif; ?>
							<?php if ($text): ?>
								<p><?php echo wp_kses_post($text); ?></p>
							<?php endif; ?>
							<?php if ($image): ?>
								<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
							<?php endif; ?>
							<?php if (!empty($button['url'])): ?>
								<?php echo zotefoams_render_link($button, [
									'class' => $btnClass,
									'text' => $button['title'] ?? 'Read More'
								]); ?>
							<?php endif; ?>
						</div>
				<?php }
				}
				?>
			</div>

			<div class="multi-swiper-scrollbar"></div>
		</div>
	</div>
</div>