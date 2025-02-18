
<?php 
	// Allow for passed variables, as well as ACF values
	$title = get_sub_field('markets_list_title');
	$button = get_sub_field('markets_list_button'); // ACF Link field
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
		<?php if ($items): ?>
			<div class="markets-list">
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