<?php
// Get field data using safe helper functions
$overline_title = zotefoams_get_sub_field_safe('step_slider_overline_title', '', 'string');
$slides         = zotefoams_get_sub_field_safe('step_slider_slides', [], 'array');

// Generate classes to match original structure exactly
$wrapper_classes = 'step-slider theme-dark';
?>

<?php if ($slides) : ?>
<div class="<?php echo $wrapper_classes; ?>">
	<?php if ($overline_title) : ?>
		<h2 class="step_slider_slide_overline fs-200 fw-regular"><?php echo esc_html($overline_title); ?></h2>
	<?php endif; ?>

		<?php foreach ($slides as $slide) :
			$image = $slide['step_slider_slide_image'];
			$overline = $slide['step_slider_slide_overline'];
			$title = $slide['step_slider_slide_title'];
			$text  = $slide['step_slider_slide_text'];
			$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'step-slider');
		?>
			<div class="step-slider__slide black-bg white-text">
				<div class="step-slider__cols">
					<img class="step-slider__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />

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
