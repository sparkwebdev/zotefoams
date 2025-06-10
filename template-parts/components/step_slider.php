<?php
$overline_title = get_sub_field('step_slider_overline_title');
$slides         = get_sub_field('step_slider_slides');
?>

<?php if ($slides) : ?>
<div class="step-slider theme-dark">
	<?php if ($overline_title) : ?>
		<h2 class="step_slider_slide_overline fs-200 fw-regular"><?php echo esc_html($overline_title); ?></h2>
	<?php endif; ?>

		<?php foreach ($slides as $slide) :
			$image = $slide['step_slider_slide_image'];
			$overline = $slide['step_slider_slide_overline'];
			$title = $slide['step_slider_slide_title'];
			$text  = $slide['step_slider_slide_text'];
			$image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
		?>
			<div class="step-slider__slide black-bg white-text">
				<div class="step-slider__cols">
					<?php /*
					<div class="step-slider__image image-cover" style="background-image: url(<?php echo esc_url($image_url); ?>">
						<img class="step-slider__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
					</div> */ ?>

					<div class="step-slider__text">
						<?php if ($overline) : ?>
							<h2 class="step-slider__overline fs-200 fw-regular"><?php echo esc_html($overline); ?></h2>
						<?php endif; ?>
						<?php if ($title) : ?>
							<h3 class="step-slider__title fs-700 fw-medium"><?php echo esc_html($title); ?></h3>
						<?php endif; ?>

						<?php if ($text) : ?>
							<div class="step-slider__subtext fs-300">
								<?php echo wp_kses_post($text); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
</div>
<?php endif; ?>
