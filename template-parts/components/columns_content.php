<?php
$overline   = get_sub_field('columns_content_overline');
$intro      = get_sub_field('columns_content_intro');
$image      = zotefoams_get_sub_field_safe('columns_content_image', [], 'array');
$column_one = get_sub_field('columns_content_column_one');
$column_two = get_sub_field('columns_content_column_two');

$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'columns-content',
    'theme'     => 'none',
    'spacing'   => 'padding-t-b-100',
    'container' => '',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">

	<?php if ($overline || $intro): ?>
		<?php
		$content = '';
		if ($overline) {
			$content .= '<p class="margin-b-20">' . wp_kses_post($overline) . '</p>';
		}
		if ($intro) {
			$content .= '<h3 class="fs-600 grey-text fw-semibold">' . wp_kses_post($intro) . '</h3>';
		}
		echo zotefoams_render_content_block($content, [
			'spacing' => 'margin-b-70'
		]);
		?>
    <?php endif; ?>

	<div class="columns-content__wrapper cont-m">
		<?php if ($image): ?>
			<div class="columns-content__image">
				<?php
				echo Zotefoams_Image_Helper::render_image($image, [
					'alt' => $image['alt'] ?? '',
					'size' => 'medium'
				]);
				?>
			</div>
		<?php endif; ?>

		<?php if ($column_one): ?>
			<div class="columns-content__column">
				<?php echo wp_kses_post($column_one); ?>
			</div>
		<?php endif; ?>

		<?php if ($column_two): ?>
			<div class="columns-content__column">
				<?php echo wp_kses_post($column_two); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
