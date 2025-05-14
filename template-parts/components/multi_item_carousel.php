<?php
$title = get_sub_field('multi_item_carousel_title');
$behaviour = get_sub_field('multi_item_carousel_behaviour'); // Pick / Children / Manual
$page_id = get_sub_field('multi_item_carousel_parent_id');
$manual_slides = get_sub_field('multi_item_carousel_slides');
$page_ids = get_sub_field('multi_item_carousel_page_ids');

$slides = [];
$fallback_image = get_template_directory_uri() . '/images/placeholder.png';

if ($behaviour === 'pick' && $page_ids) {
	foreach ($page_ids as $id) {
		$slides[] = [
			'title' => get_the_title($id),
			'text' => get_the_excerpt($id),
			'image' => get_the_post_thumbnail_url($id, 'thumbnail-product') ?: $fallback_image,
			'link' => get_permalink($id),
		];
	}
} elseif ($behaviour === 'children' && $page_id) {
	$children = get_pages([
		'parent' => $page_id,
		'sort_column' => 'menu_order',
		'sort_order' => 'ASC',
	]);
	foreach ($children as $child) {
		$slides[] = [
			'title' => get_the_title($child->ID),
			'text' => get_the_excerpt($child->ID),
			'image' => get_the_post_thumbnail_url($child->ID, 'thumbnail-product') ?: $fallback_image,
			'link' => get_permalink($child->ID),
		];
	}
} elseif ($behaviour === 'manual' && $manual_slides) {
	foreach ($manual_slides as $item) {
		$image_url = $item['multi_item_carousel_slide_image']['sizes']['thumbnail-product'] ?? $fallback_image;
		$slides[] = [
			'title' => $item['multi_item_carousel_slide_title'] ?? '',
			'text' => $item['multi_item_carousel_slide_text'] ?? '',
			'image' => $image_url,
			'link' => $item['multi_item_carousel_slide_button']['url'] ?? '',
			'link_title' => $item['multi_item_carousel_slide_button']['title'] ?? 'Read More',
			'link_target' => $item['multi_item_carousel_slide_button']['target'] ?? '_self',
		];
	}
}
?>

<div class="multi-item-carousel-container padding-t-100 padding-b-100 theme-none">
	<div class="cont-m">
		<div class="title-strip margin-b-30">
			<?php if ($title): ?>
				<h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="multi-swiper-button-prev">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="multi-swiper-button-next">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
		</div>

		<div class="swiper multi-item-carousel">
			<div class="swiper-wrapper">
				<?php foreach ($slides as $slide): ?>
					<div class="swiper-slide">
						<?php if (!empty($slide['title'])): ?>
							<h3 class="fs-600 fw-bold"><?php echo esc_html($slide['title']); ?></h3>
						<?php endif; ?>
						<?php if (!empty($slide['text'])): ?>
							<p><?php echo wp_kses_post($slide['text']); ?></p>
						<?php endif; ?>
						<?php if (!empty($slide['image'])): ?>
							<img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title'] ?? ''); ?>">
						<?php endif; ?>
						<?php if (!empty($slide['link'])): ?>
							<a href="<?php echo esc_url($slide['link']); ?>" class="btn black outline" target="<?php echo esc_attr($slide['link_target'] ?? '_self'); ?>">
								<?php echo esc_html($slide['link_title'] ?? 'Read More'); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="multi-swiper-scrollbar"></div>
		</div>
	</div>
</div>