<?php
$title        = get_sub_field('multi_item_carousel_title');
$behaviour    = get_sub_field('multi_item_carousel_behaviour'); // 'pick', 'children', or 'manual'
$page_id      = get_sub_field('multi_item_carousel_parent_id');
$manual_slides = get_sub_field('multi_item_carousel_slides');
$isVariant    = get_sub_field('multi_item_carousel_variant');
$wrapperClass = $isVariant ? 'multi-item-carousel multi-item-carousel--variant' : 'multi-item-carousel';
$slideClass   = $isVariant ? 'swiper-slide black-bg white-text' : 'swiper-slide';
$btnClass     = $isVariant ? 'btn white outline' : 'btn black outline';

?>

<div class="multi-item-carousel-container padding-t-b-100 theme-none">
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
					$page_ids = get_sub_field('multi_item_carousel_page_ids') ?: [];

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
					$children = get_pages([
						'parent'      => $page_id,
						'sort_column' => 'menu_order',
						'sort_order'  => 'ASC',
					]);

					foreach ($children as $child) {
						$title   = get_the_title($child->ID);
						$link    = get_permalink($child->ID);
						$excerpt = get_the_excerpt($child->ID);
						$image   = get_the_post_thumbnail_url($child->ID, 'thumbnail-product');
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
						$image   = $slide['multi_item_carousel_slide_image']['sizes']['thumbnail-product'] ?? '';

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
								<a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($btnClass); ?>" target="<?php echo esc_attr($button['target'] ?? '_self'); ?>">
									<?php echo esc_html($button['title'] ?? 'Read More'); ?>
								</a>
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