<?php
// Get field data using safe helper functions
$overline = get_sub_field('icon_columns_intro_overline'); // Keep HTML intact
$intro    = get_sub_field('icon_columns_intro'); // Keep HTML intact
$columns  = zotefoams_get_sub_field_safe('icon_columns_columns', [], 'array');

$is_sustainability = is_page('Sustainability');

// Build component-specific classes
$component_classes = 'icon-columns';
$theme = ($overline || $intro) ? 'none' : 'light';

if (!empty($columns)) {
    $count = count($columns);
    $component_classes .= ($count == 2 || $count == 4) ? ' icon-columns--half' : '';

    // Check if ALL columns have empty 'text' field
    $all_columns_without_text = true;
    foreach ($columns as $column) {
        if (!empty($column['icon_columns_text'])) {
            $all_columns_without_text = false;
            break;
        }
    }

    if ($all_columns_without_text) {
        $component_classes .= ' icon-columns--centered';
    }
}

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => $component_classes,
    'theme'     => $theme,
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
		
	<?php if ($columns): ?>
		<div class="icon-columns__items cont-m">
			<?php foreach ($columns as $column):
				$icon  = $column['icon_columns_icon'];
				$title = $column['icon_columns_title'];
				$text  = $column['icon_columns_text'];
			?>
				<div>
					<?php if ($icon): ?>
						<?php echo Zotefoams_Image_Helper::render_image($icon, [
							'alt' => $title,
							'size' => 'large'
						]); ?>
					<?php endif; ?>
					<?php if ($title): ?>
						<p class="fs-400 fw-bold margin-b-20"><?php echo esc_html($title); ?></p>
					<?php endif; ?>
					<?php if ($text): ?>
						<p class="grey-text"><?php echo wp_kses_post($text); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php if ($is_sustainability): ?>
	<?php include locate_template('/template-parts/components/waste-hierarchy.php', false, false); ?>
	<?php include locate_template('/template-parts/components/development-goals.php', false, false); ?>
<?php endif; ?>