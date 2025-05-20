<?php
$overline = get_sub_field('icon_columns_intro_overline');
$intro    = get_sub_field('icon_columns_intro');
$columns  = get_sub_field('icon_columns_columns');

$is_sustainability = is_page('Sustainability');
$wrapper_class = $is_sustainability ? 'sustainability-icons black-text' : 'icon-columns light-grey-bg black-text';
$theme_class = $is_sustainability ? 'theme-none' : 'theme-light';
$columns_wrapper_class = $is_sustainability ? 'sustainability-icons-inner' : 'icon-columns__inner';
?>

<div class="<?php echo esc_attr($wrapper_class); ?> padding-t-b-100 <?php echo esc_attr($theme_class); ?>">

	<?php if ($is_sustainability): ?>
	<div class="sustainability-intro grey-text cont-m">
		<div class="sustainability-intro-inner">
			<?php if ($overline): ?>
				<p class="margin-b-20"><?php echo wp_kses_post($overline); ?></p>
			<?php endif; ?>
			<?php if ($intro): ?>
				<h3 class="fs-600 margin-b-70 grey-text"><?php echo wp_kses_post($intro); ?></h3>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="<?php echo esc_attr($columns_wrapper_class); ?> cont-m">
		<?php if ($columns): ?>
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
		<?php endif; ?>
	</div>
</div>

<?php if ($is_sustainability): ?>
	<?php include locate_template('/template-parts/components/waste-hierarchy.php', false, false); ?>
	<?php include locate_template('/template-parts/components/development-goals.php', false, false); ?>
<?php endif; ?>