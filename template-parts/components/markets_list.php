<?php 
	$market_page_id = 11;
	$child_pages = get_pages(array(
			'child_of' => $market_page_id,
			'sort_column' => 'menu_order',
			'sort_order' => 'ASC'
	));
	if (count($child_pages) > 0) { ?>
	<div class="light-grey-bg padding-t-b-70">

		<div class="cont-m">
			<div class="title-strip margin-b-30">
				<h2 class="fs-500 fw-600">Our Markets</h2>
				<?php if ($market_page_id !== get_the_ID()) { ?>
					<a href="<?php echo get_the_permalink( $market_page_id ); ?>" class="btn black outline">About Our Markets</a>	
				<?php } ?>
			</div>
		</div>

		<div class="markets-list">
		<?php foreach ($child_pages as $child) {
			$id = $child->ID; ?>

			<div class="market-box white-bg text-center">
				<h3 class="fs-600 fw-medium margin-b-20"><?php echo get_the_title( $id ); ?></h3>

				<?php 
				$related_brands = get_field('brands', $id);
				if( $related_brands ): ?>
					<ul class="brands margin-b-20">
						<?php foreach( $related_brands as $brand ): 
								setup_postdata($brand); ?>
								<li>
									<p class="grey-text"><?php echo get_the_title($brand); ?></p>
								</li>
						<?php endforeach; ?>
					</ul>
						<?php 
						wp_reset_postdata(); ?>
				<?php endif ?>
				<a href="<?php echo get_the_permalink( $id ); ?>" class="hl arrow">Read more</a>
			</div>
		<?php } ?>
		</div>
	</div>
<?php } ?>
