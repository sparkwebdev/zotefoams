
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
							<a href="<?php echo $link['url']; ?>" <?php echo !empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '"' : '' ?> class="btn white outline arrow"><?php echo $link['title']; ?></a>
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


	<style type="text/css">

		/* b1 - Image banner
		--------------------------------------------- */
		.swiper-image {
			width: 100%;
			height: 72vh;
			position: relative;
			max-height: 900px;
			min-height: 700px;
		}
		.swiper-image .swiper-slide {
			justify-content: center;
			align-items: center;
			background-color: #ddd;
		}
		.swiper-image .swiper-inner {
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			height: calc(100% - 100px);
			position: relative;
			z-index: 1;
		}
		.swiper-image .swiper-inner h1 {
			max-width: 1100px;
		}
		.swiper-image .swiper-inner p {
			max-width: 750px;
		}
		.swiper-image .swiper-slide .overlay {
			background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
		}

		/* Next Button Styles */
		.swiper-image .swiper-button-next-image {
			position: absolute;
			bottom: 50px;
			right: 50px;
			z-index: 2;
			display: flex;
			align-items: center;
			transition: opacity 0.3s;
			top: auto;
			width: auto;
			height: auto;
			margin: auto;
			cursor: auto;
			justify-content: unset;
			color: #fff;
		}

		.swiper-image .swiper-button-next:after, .swiper-rtl .swiper-button-prev:after {
			content: '';
		}

		.swiper-image .swiper-button-next-image:hover {
			cursor: pointer;
			opacity: 0.8;
		}

		/* Circle Container */
		.circle-container {
			width: 50px;
			height: 50px;
			display: flex;
			justify-content: center;
			align-items: center;
			pointer-events: none;
			z-index: 1;
		}
		.circle-svg {
			transform: rotate(-90deg);
			width: 100%;
			height: 100%;
		}
		.circle-background {
			fill: none;
			stroke: #ddd;
			stroke-width: 2;
		}
		.circle-progress {
			fill: none;
			stroke: #007bff;
			stroke-width: 2;
			stroke-dasharray: 144.513;
			stroke-dashoffset: 144.513;
			transition: stroke-dashoffset 3s linear;
		}
		.swiper-slide-active .circle-progress {
			stroke-dashoffset: 0;
		}
		.swiper-slide-next .circle-progress,
		.swiper-slide-prev .circle-progress {
			stroke-dashoffset: 144.513;
		}

	</style>




