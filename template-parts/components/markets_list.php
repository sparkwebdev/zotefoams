<?php
// Get field data using safe helper functions
$title          = zotefoams_get_sub_field_safe('markets_list_title', '', 'string');
$button         = zotefoams_get_sub_field_safe('markets_list_button', [], 'url');
$behaviour      = zotefoams_get_sub_field_safe('markets_list_behaviour', '', 'string');
$marketPageID   = zotefoams_get_page_id_by_title('Markets') ?: zotefoams_get_page_id_by_title('Industries');
$manual_items   = zotefoams_get_sub_field_safe('markets_list_markets', [], 'array');
$selected_pages = zotefoams_get_sub_field_safe('markets_list_ids', [], 'array');

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => '',
    'theme'     => 'light',
    'spacing'   => 'padding-t-b-70',
    'container' => '',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="cont-m">
        <div class="title-strip margin-b-30">
            <?php if ($title) : ?>
                <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>
            <?php if ($button) : ?>
                <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target'] ?? '_self'); ?>">
                    <?php echo esc_html($button['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($behaviour === 'all') :
        $child_pages = get_pages([
            'child_of'    => $marketPageID,
            'sort_column' => 'menu_order',
            'sort_order'  => 'ASC',
        ]);

        if ($child_pages) : ?>
            <div class="markets-list">
                <?php foreach ($child_pages as $child) :
                    $id = $child->ID;
                    $page_title = get_the_title($id);
                    $page_link = get_permalink($id);
                    $image_url = Zotefoams_Image_Helper::get_image_url(get_post_thumbnail_id($id), 'large', 'large');
                    $brands = get_field('associated_brands', $id);
                ?>
                    <div class="market-box padding-50 white-bg text-center" data-clickable-url="<?php echo esc_url($page_link); ?>">
                        <h3 class="fs-600 fw-medium margin-b-20"><?php echo esc_html($page_title); ?></h3>

                        <?php if ($brands) : ?>
                            <ul class="brands margin-b-20">
                                <?php foreach ($brands as $brand) : ?>
                                    <li>
                                        <p class="grey-text"><?php echo esc_html(get_the_title($brand)); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <a href="<?php echo esc_url($page_link); ?>" class="hl arrow read-more">Read more</a>
                        <div class="market-image" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php elseif ($behaviour === 'pick' && $selected_pages) : ?>
        <div class="markets-list">
            <?php foreach ($selected_pages as $id) :
                $page_title = get_the_title($id);
                $page_link = get_permalink($id);
                $image_url = Zotefoams_Image_Helper::get_image_url(get_post_thumbnail_id($id), 'large', 'large');
                $brands = get_field('associated_brands', $id);
            ?>
                <div class="market-box padding-50 white-bg text-center" data-clickable-url="<?php echo esc_url($page_link); ?>">
                    <h3 class="fs-600 fw-medium margin-b-20"><?php echo esc_html($page_title); ?></h3>

                    <?php if ($brands) : ?>
                        <ul class="brands margin-b-20">
                            <?php foreach ($brands as $brand) : ?>
                                <li>
                                    <p class="grey-text"><?php echo esc_html(get_the_title($brand)); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <a href="<?php echo esc_url($page_link); ?>" class="hl arrow read-more">Read more</a>
                    <div class="market-image" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($behaviour === 'manual' && $manual_items) : ?>
        <div class="markets-list">
            <?php foreach ($manual_items as $item) :
                $item_title = $item['markets_list_name'] ?? '';
                $brands     = $item['markets_list_brands'] ?? [];
                $link       = $item['markets_list_link'] ?? [];
                $image      = $item['markets_list_image'] ?? null;
                $image_url  = Zotefoams_Image_Helper::get_image_url($image, 'large', 'large');
                $link_url   = $link['url'] ?? '#';
                $link_title = $link['title'] ?? 'Read more';
                $link_target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
            ?>
                <div class="market-box padding-50 white-bg text-center" data-clickable-url="<?php echo esc_url($link_url); ?>">
                    <?php if ($item_title) : ?>
                        <h3 class="fs-600 fw-medium"><?php echo esc_html($item_title); ?></h3>
                    <?php endif; ?>

                    <?php if ($brands) : ?>
                        <ul class="brands margin-b-20">
                            <?php foreach ($brands as $brand) :
                                $brand_name = $brand['market_brand_name'] ?? '';
                                if ($brand_name) :
                            ?>
                                    <li>
                                        <p class="grey-text"><?php echo esc_html($brand_name); ?></p>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($link_url) : ?>
                        <a href="<?php echo esc_url($link_url); ?>" class="hl arrow read-more" <?php echo $link_target; ?>>
                            <?php echo esc_html($link_title); ?>
                        </a>
                    <?php endif; ?>

                    <div class="market-image" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>