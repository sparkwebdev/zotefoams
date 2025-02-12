
	<?php
	$pageHeaderText = get_field('page_header_text');
	if( $pageHeaderText ): ?>
		<div class="text-banner cont-m margin-t-70 margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php echo esc_html( $pageHeaderText['title'] ); ?>
			</h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold">
				<?php echo esc_html( $pageHeaderText['subtitle'] ); ?>
			</h2>
		</div>
	<?php endif; ?>