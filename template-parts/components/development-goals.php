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
?>

<div class="development-goals padding-t-b-100 theme-none">
	<div class="sustainability-intro grey-text cont-m">
		<div class="sustainability-intro-inner">
			<p class="margin-b-20">Development Goals</p>
			<h3 class="fs-600 margin-b-70 grey-text">
				<strong>
					Zotefoams supports the United Nations Sustainable Development Goals and have aligned a number of our processes and activities to these.
				</strong>
			</h3>
		</div>
	</div>

	<div class="development-goals-list cont-m">
		<?php foreach ($icon_filenames as $filename): ?>
			<img
				src="<?php echo esc_url($icons_base_url . $filename); ?>"
				alt="UN Sustainable Development Goal Icon" />
		<?php endforeach; ?>
	</div>
</div>