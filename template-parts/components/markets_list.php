<?php 
	// Allow for passed variables, as well as ACF values
	$title = get_sub_field('markets_list_title');
	$button = get_sub_field('markets_list_button'); // ACF Link field
	$behaviour = get_sub_field('markets_list_behaviour'); // All / Pick / Manual
	$marketPageID = zotefoams_get_page_id_by_title('Markets');
	$manual_items = get_sub_field('markets_list_markets'); // Manual markets
	$selected_pages = get_sub_field('markets_list_ids'); // Selected pages if behaviour is "pick"
?>

<div class="light-grey-bg padding-t-b-70 theme-light">
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
    if ($behaviour === 'all') :
        // Show all child pages of the Markets page
        $child_pages = get_pages([
            'child_of'     => $marketPageID,
            'sort_column'  => 'menu_order',
            'sort_order'   => 'ASC'
        ]);

        if ($child_pages): ?>
            <div class="markets-list">
                <?php foreach ($child_pages as $child):
                    $id = $child->ID;
                    $featured_image = get_the_post_thumbnail_url($id, 'large') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png'; ?>
                    <div class="market-box white-bg text-center" data-clickable-url="<?php echo get_the_permalink($id); ?>">
                        <h3 class="fs-600 fw-medium margin-b-20"><?php echo get_the_title($id); ?></h3>

                        <?php 
                        $related_brands = get_field('associated_brands', $id);
                        if ($related_brands): ?>
                            <ul class="brands margin-b-20">
                                <?php foreach ($related_brands as $brand): ?>
                                    <li>
                                        <p class="grey-text"><?php echo get_the_title($brand); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_the_permalink($id); ?>" class="hl arrow read-more">Read more</a>

                        <div class="market-image" style="background-image:url('<?php echo esc_url($featured_image); ?>');"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    
    elseif ($behaviour === 'pick' && $selected_pages) : ?>
        <div class="markets-list">
            <?php foreach ($selected_pages as $page_id):
                $page_title = get_the_title($page_id);
                $page_link = get_permalink($page_id);
                $featured_image = get_the_post_thumbnail_url($page_id, 'large') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                $related_brands = get_field('associated_brands', $page_id);
                ?>
                <div class="market-box white-bg text-center" data-clickable-url="<?php echo esc_url($page_link); ?>">
                    <h3 class="fs-600 fw-medium margin-b-20"><?php echo esc_html($page_title); ?></h3>

                    <?php if ($related_brands): ?>
                        <ul class="brands margin-b-20">
                            <?php foreach ($related_brands as $brand): ?>
                                <li>
                                    <p class="grey-text"><?php echo get_the_title($brand); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <a href="<?php echo esc_url($page_link); ?>" class="hl arrow read-more">Read more</a>

                    <div class="market-image" style="background-image:url('<?php echo esc_url($featured_image); ?>');"></div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($behaviour === 'manual' && $manual_items) : ?>
        <div class="markets-list">
            <?php foreach ($manual_items as $item): 
                $title = $item['markets_list_name'] ?? '';
                $brands = $item['markets_list_brands'] ?? '';
                $link = $item['markets_list_link'] ?? '';
                $image = $item['markets_list_image'] ?? null;
                $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder-thumbnail.png';
								if ($link): 
										$link_url = $link['url'] ?? '#';
								endif;
            ?>

                <div class="market-box white-bg text-center" 
									<?php if (!empty($link_url)): ?> 
											data-clickable-url="<?php echo esc_url($link_url); ?>"
									<?php endif; ?>
								>
                    <?php if ($title): ?>
                        <h3 class="fs-600 fw-medium"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($brands): ?>
                        <ul class="brands margin-b-20">
                            <?php foreach ($brands as $brand): 
                                $name = $brand['market_brand_name'] ?? '';
                                if ($name): ?>
                                    <li>
                                        <p class="grey-text"><?php echo esc_html($name); ?></p>
                                    </li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($link): 
                        $link_url = $link['url'] ?? '#';
                        $link_title = $link['title'] ?? 'Read More';
                        $link_target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
                    ?>
                        <a href="<?php echo esc_url($link_url); ?>" class="hl arrow read-more"<?php echo $link_target; ?>>
                            <?php echo esc_html($link_title); ?>
                        </a>
                    <?php endif; ?>

                    <div class="market-image" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
