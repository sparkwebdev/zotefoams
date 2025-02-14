
	<?php
	$pageHeaderText = get_field('page_header_text');
	if( $pageHeaderText ): ?>
		<div class="text-banner margin-t-70">
			<div class="cont-m margin-b-70">
				<h1 class="uppercase grey-text fs-800 fw-extrabold">
					<?php echo esc_html( $pageHeaderText['title'] ); ?>
				</h1>
				<h2 class="uppercase black-text fs-800 fw-extrabold">
					<?php echo esc_html( $pageHeaderText['subtitle'] ); ?>
				</h2>
			</div>
		</div>
	<?php endif; ?>