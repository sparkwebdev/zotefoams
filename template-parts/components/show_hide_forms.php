<?php
$title = get_field('show_hide_forms_title', 'option');
$intro = get_field('show_hide_forms_intro', 'option');
$items = get_field('show_hide_forms_items', 'option');
?>

<div class="show-hide-forms accordion cont-m padding-t-b-100 theme-none">

	<div>
		<?php if ($title) : ?>
			<h3 class="fs-500 fw-600 margin-b-20"><?php echo esc_html($title); ?></h3>
		<?php endif; ?>

		<?php if ($intro) : ?>
			<p><?php echo esc_html($intro); ?></p>
		<?php endif; ?>
	</div>

	<?php if ($items) : ?>
		<div class="accordion-items">
			<?php foreach ($items as $item) :
				$form_id = isset($item['show_hide_forms_form']) ? (int) $item['show_hide_forms_form'] : 0;
				$form_title = $form_id ? get_the_title($form_id) : '';
			?>
				<?php if ($form_id && $form_title) : ?>
					<div class="accordion-item">
						<button class="accordion-header fs-400 fw-semibold">
							<?php echo esc_html($form_title); ?>
							<span class="toggle-icon">+</span>
						</button>
						<div class="accordion-content">
							<?php echo do_shortcode('[wpforms id="' . esc_attr($form_id) . '" title="false"]'); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</div>