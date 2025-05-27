<?php
$overline = get_sub_field('icon_columns_intro_overline');
$intro    = get_sub_field('icon_columns_intro');
$columns  = get_sub_field('icon_columns_columns');

$is_sustainability = is_page('Sustainability');
$wrapper_class = 'icon-columns';

if (!empty($columns)) {
    $count = count($columns);
	$wrapper_class .= ($overline || $intro) ? ' theme-none' : ' light-grey-bg theme-light';
    $wrapper_class .= ($count == 2 || $count == 4) ? ' icon-columns--half' : '';
	// $wrapper_class .= ($count === 5) ? ' icon-columns--centered' : '';

    // Check if ALL columns have empty 'text' field
    $all_columns_without_text = true;
    foreach ($columns as $column) {
        if (!empty($column['icon_columns_text'])) {
            $all_columns_without_text = false;
            break;
        }
    }

    if ($all_columns_without_text) {
        $wrapper_class .= ' icon-columns--centered';
    }
}
?>

<div class="<?php echo esc_attr($wrapper_class); ?> padding-t-b-100">

	<?php if ($overline || $intro): ?>
	<div class="text-block cont-m margin-b-70">
		<?php if ($overline): ?>
			<p class="margin-b-20"><?php echo wp_kses_post($overline); ?></p>
		<?php endif; ?>
		<?php if ($intro): ?>
			<h3 class="fs-600 grey-text fw-semibold"><?php echo wp_kses_post($intro); ?></h3>
		<?php endif; ?>
	</div>
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
						<img class="margin-b-15" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($title); ?>" />
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