<?php 
	// Allow for passed variables, as well as ACF values
	$title = get_sub_field('markets_list_title');
	$button = get_sub_field('markets_list_button'); // ACF Link field
	$showAll = get_sub_field('markets_list_show_all');
	$marketPageID = zf_get_page_id_by_title('Markets');
	$ourBrandsID = zf_get_page_id_by_title('Our Brands'); // ID of the top-level Brands page
	$items = get_sub_field('markets_list_markets');

	$current_page_id = get_the_ID();
	$is_brand_page = false;

	// Check if the current page is a child or grandchild of the 'Our Brands' page
	$ancestors = get_post_ancestors($current_page_id);
	if (in_array($ourBrandsID, $ancestors) || $current_page_id == $ourBrandsID) {
			$is_brand_page = true;
	}

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
    if ($is_brand_page && $showAll) :
        // Get the current brand's ID
        $current_brand_id = get_the_ID();

        // Query all child and grandchild pages of the Markets page
        $markets_query = new WP_Query(array(
            'post_type'      => 'page',
            'post_parent'    => $marketPageID,
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'associated_brands',
                    'value'   => '"' . $current_brand_id . '"', // Exact match inside serialized array
                    'compare' => 'LIKE',
                ),
            ),
        ));

        if ($markets_query->have_posts()) : ?>
            <div class="markets-list">
                <?php while ($markets_query->have_posts()) : $markets_query->the_post(); ?>
                    <div class="market-box white-bg text-center">
                        <h3 class="fs-600 fw-medium"><?php the_title(); ?></h3>

                        <?php 
                        $related_brands = get_field('associated_brands', get_the_ID());
                        if ($related_brands): ?>
                            <ul class="brands margin-b-20">
                                <?php foreach ($related_brands as $brand): ?>
                                    <li>
                                        <p class="grey-text"><?php echo get_the_title($brand); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                        <a href="<?php the_permalink(); ?>" class="hl arrow">Read more</a>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php 
            wp_reset_postdata();
        endif;
    
    elseif ($showAll) :
        // Original logic to show all child pages of the Markets page
        $child_pages = get_pages(array(
            'child_of'     => $marketPageID,
            'sort_column'  => 'menu_order',
            'sort_order'   => 'ASC'
        ));

        if (count($child_pages) > 0) : ?>
            <div class="markets-list">
                <?php foreach ($child_pages as $child) {
                    $id = $child->ID; ?>

                    <div class="market-box white-bg text-center">
                        <h3 class="fs-600 fw-medium"><?php echo get_the_title($id); ?></h3>

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
                        
                        <a href="<?php echo get_the_permalink($id); ?>" class="hl arrow">Read more</a>
                    </div>
                <?php } ?>
            </div>
        <?php endif;
    
    elseif ($items) : ?>
        <div class="markets-list">
            <?php foreach ($items as $item): 
                $title = $item['markets_list_name'] ?? '';
                $brands = $item['markets_list_brands'] ?? '';
                $link = $item['markets_list_link'] ?? '';
            ?>

                <div class="market-box white-bg text-center">
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
                        <a href="<?php echo esc_url($link_url); ?>" class="hl arrow"<?php echo $link_target; ?>>
                            <?php echo esc_html($link_title); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>	
