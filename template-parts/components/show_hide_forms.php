<?php 
// Allow for passed variables, as well as ACF values
$title = get_field('show_hide_forms_title', 'option');
$intro = get_field('show_hide_forms_intro', 'option'); // ACF Link field
$items = get_field('show_hide_forms_items', 'option');
?>

	<div class="show-hide-forms cont-m padding-t-100 margin-b-100 theme-none">

		<div class="intro">
        	<?php if ($title): ?>
				<h3 class="fs-500 fw-600 margin-b-20"><?php echo esc_html($title); ?></h3>
        	<?php endif; ?>
        	<?php if ($intro): ?>
				<p><?php echo esc_html($intro); ?></p>
        	<?php endif; ?>
		</div>

		<?php if ($items): ?>
			<div class="accordion-items">
				<?php foreach ($items as $item): ?>
					<?php 
						$formID = $item['show_hide_forms_form'] ?? '';
					?>
					<div class="accordion-item">
						<button class="accordion-header fs-400 fw-semibold">
							<?php echo get_the_title($formID); ?>
							<span class="toggle-icon">+</span>
						</button>
						<div class="accordion-content">
							<?php echo do_shortcode('[wpforms id="'.$formID.'" title="false"]'); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
        <?php endif; ?>

	</div>

	<style type="text/css">
		.show-hide-forms {
			display:flex;
			gap:50px
		}
		.show-hide-forms > div {
			flex:1
		}
	</style>