<?php
$icon_filenames = [
	'esg-icon7-min.png',
	'esg-icon6-min.png',
	'esg-icon5-min.png',
	'esg-icon4-min.png',
	'esg-icon3-min.png',
	'esg-icon2-min.png',
	'esg-icon1-min.png',
	'esg-icon8-min.png',
];

$icons_base_url = get_template_directory_uri() . '/images/esg-icons/';

// Generate classes to match original structure exactly
$wrapper_classes = 'development-goals padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">

	<div class="text-block cont-m margin-b-70">
		<div class="text-block__inner">
			<?php if ($overline): ?>
				<p class="margin-b-20 grey-text">Development Goals</p>
			<?php endif; ?>
			<?php if ($intro): ?>
				<h3 class="fs-600 grey-text fw-semibold"><strong>
					Zotefoams supports the United Nations Sustainable Development Goals and have aligned a number of our processes and activities to these.
				</strong></h3>
			<?php endif; ?>
		</div>
	</div>

	<div class="development-goals__list cont-m">
		<?php foreach ($icon_filenames as $filename): ?>
			<img
				src="<?php echo esc_url($icons_base_url . $filename); ?>"
				alt="UN Sustainable Development Goal Icon" />
		<?php endforeach; ?>
	</div>
</div>