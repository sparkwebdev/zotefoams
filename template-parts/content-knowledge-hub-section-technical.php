<?php
// Define your brands array. It can now be empty.
$brands = array();

// Query the child pages of the current page using the 'knowledge-hub' post type.
$child_pages = get_pages(array(
    'child_of'    => get_the_ID(),
    'sort_column' => 'menu_order',
    'sort_order'  => 'ASC',
    'post_type'   => 'knowledge-hub'
));
?>

<nav class="cont-m padding-t-b-100 theme-none">
    <div data-component="section-list">
        <?php if (!empty($brands) && count($brands) > 1) : ?>
            <div class="file-list__dropdown">
                <button id="filter-toggle" class="file-list__dropdown-button hl arrow">
                    Filter
                </button>
                <div id="filter-options" class="filter-toggle__options hidden">
                    <?php foreach ($brands as $brand_filter) : ?>
                        <label class="filter-toggle__label">
                            <input type="checkbox" value="<?php echo esc_attr($brand_filter); ?>" class="filter-options__checkbox">
                            <?php echo esc_html($brand_filter); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button id="section-list-show-all" class="file-list__show-all hidden">Reset Filters</button>
        <?php endif; ?>

        <div class="section-list">
            <div class="articles articles--grid-alt">
                <?php if (!empty($brands)) : ?>
                    <?php
                    // If there are brands, loop through each brand and then each child page.
                    foreach ($brands as $brand) :
                        foreach ($child_pages as $child) :
                            $child_title = $child->post_title;
                            $brand_id = zotefoams_get_page_id_by_title($brand);
                            $child_url = esc_url(get_the_permalink($child->ID)) . '?brand=' . $brand_id;
                    ?>
                            <article id="post-<?php echo esc_attr($child->ID); ?>" <?php post_class('section-list__item', $child->ID); ?> data-brand="<?php echo esc_attr($brand); ?>" data-gallery-label="<?php echo esc_attr($brand); ?>" data-clickable-url="<?php echo $child_url; ?>">
                                <div class="articles__content padding-30">
                                    <div class="tags margin-b-20">
                                        <span class="tag"><?php echo esc_html($brand); ?></span>
                                    </div>
                                    <h3 class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($child_title); ?></h3>
                                    <p class="articles__cta">
                                        <a href="<?php echo $child_url; ?>" class="hl arrow read-more">View Information Sheets</a>
                                    </p>
                                </div>
                            </article>
                    <?php
                        endforeach;
                    endforeach;
                    ?>
                <?php else : ?>
                    <?php
                    // If there are no brands, simply loop through child pages without any brand filter or tags.
                    foreach ($child_pages as $child) :
                        $child_title = $child->post_title;
                        $child_url = esc_url(get_the_permalink($child->ID));
                    ?>
                        <article id="post-<?php echo esc_attr($child->ID); ?>" <?php post_class('section-list__item', $child->ID); ?> data-clickable-url="<?php echo $child_url; ?>">
                            <div class="articles__content padding-30">
                                <h3 class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($child_title); ?></h3>
                                <p class="articles__cta">
                                    <a href="<?php echo $child_url; ?>" class="hl arrow read-more">View Information Sheets</a>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>