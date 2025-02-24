
<?php 
	// Allow for passed variables, as well as ACF values
	$title = get_sub_field('markets_list_title');
	$button = get_sub_field('markets_list_button'); // ACF Link field
	$showAll = get_sub_field('markets_list_show_all');
	$marketPageID = get_sub_field('markets_list_page_id');
	$items = get_sub_field('markets_list_markets');
?>
	<div class="light-grey-bg padding-t-b-70">

		<div class="cont-m">
			<div class="title-strip margin-b-30">
				<?php if ($title): ?>
						<h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
				<?php endif; ?>
				<?php if ($button): ?>
						<a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target'] ?? '_self'); ?>">
								<?php echo esc_html($button['title']); ?>
						</a>
				<?php endif; ?>
			</div>
		</div>

		<?php 
		if ($showAll) :
				$market_page_id = !empty($marketPageID) ? $marketPageID : zf_get_page_id_by_title('Markets');
				$child_pages = get_pages(array(
				'child_of' => $market_page_id,
				'sort_column' => 'menu_order',
				'sort_order' => 'ASC'
			));
			if (count($child_pages) > 0) { ?>
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
		<?php } ?>

		<?php elseif ($items) : ?>
			<dv cl ass="markets-list">
				<?php foreach ($items as $item): 
					$title = $item['markets_list_name'] ?? '';
					$brands = $item['markets_list_brands'] ?? '';
					$link = $item['markets_list_link'] ?? '';
				?>

					<div class="market-box white-bg text-center">
						<?php if ($title): ?>
								<h3 class="fs-600 fw-medium margin-b-20"><?php echo esc_html($title); ?></h3>
						<?php endif; ?>
						<?php 
						if( $brands ): ?>
							<ul class="brands margin-b-20">
								<?php foreach( $brands as $brand ): 
									$name = $brand['market_brand_name'] ?? '';
									if ($name):
									?>
										<li>
											<p class="grey-text"><?php echo $name; ?></p>
										</li>
								<?php 
									endif;
								endforeach; ?>
							</ul>
						<?php endif ?>
						<?php if ($link): 
							$link_url = $link['url'] ?? '#';
							$link_title = $link['title'] ?? 'Read More';
							$link_target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
						?>
							<a href="<?php echo esc_url($link_url); ?>" class="hl arrow"<?php echo $link_target; ?>>
									<?php echo esc_html($link_title) ?? 'Read more'; ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>	   
