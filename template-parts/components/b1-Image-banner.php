
	<?php
	if( have_rows('page_header_image') ): ?>
		<div class="image-banner swiper swiper-image">
			<div class="swiper-wrapper">

			<?php while( have_rows('page_header_image') ) : the_row();

				$imageId = get_sub_field('image');
				$title = get_sub_field('title');
				$text = get_sub_field('text');
				$caption = get_sub_field('caption');
				$link = get_sub_field('link');
				
				?>

				<div class="swiper-slide image-cover" style="background-image:url(<?php echo wp_get_attachment_image_url( $imageId, 'full' ); ?>)" data-title="<?php echo esc_attr( $caption ); ?>">
					<div class="swiper-inner padding-50 white-text">
						<div class="title-button">
							<h1 class="fw-extrabold fs-900 uppercase margin-b-30"><?php echo $title; ?></h1>
							<?php if( get_sub_field('link') ): ?>
							<a href="<?php echo $link['url']; ?>" <?php echo !empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '"' : '' ?> class="btn white outline arrow"><?php echo $link['title']; ?></a>
							<?php endif; ?>
						</div>
						<p class="fw-bold fs-600 uppercase"><?php echo $text; ?></p>
					</div>
					<div class="overlay"></div>
				</div>

			<?php endwhile; ?>
			</div>
			
			<?php if ( count(get_field('page_header_image')) > 1 ): ?>
			<div class="swiper-button-next swiper-button-next-image white-text">
				<p class="fw-regular fs-100 uppercase margin-r-15">NEXT: <span class="fw-bold"></span></p>
				<div class="circle-container">
					<svg class="circle-svg" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
						<circle class="circle-background" cx="25" cy="25" r="23"></circle>
						<circle class="circle-progress circle-progress-image" cx="25" cy="25" r="23"></circle>
					</svg>
				</div>
			</div>
			<?php endif; ?>
			
		</div>

	<?php endif; ?>