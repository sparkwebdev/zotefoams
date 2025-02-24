<?php
// Allow for passed variables, as well as ACF values
$title = get_sub_field('small_box_columns_title');
$button = get_sub_field('small_box_columns_button'); // ACF Link field
$items = get_sub_field('small_box_columns_items');
?>

<div class="small-box-columns padding-t-b-100 theme-none">
    <div class="cont-m">

        <div class="title-strip margin-b-30">
            <?php if ($title): ?>
                <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <?php if ($button): ?>
                <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                    <?php echo esc_html($button['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="small-box-items">
            <?php if ($items): ?>
                <?php foreach ($items as $item): ?>
                    <?php 
                        $image = $item['small_box_columns_item_image'];
                        $title = $item['small_box_columns_item_title'];
                        $description = $item['small_box_columns_item_description'];
                        $link = $item['small_box_columns_item_link']; // ACF Link field

                        // Extract 'thumbnail-square' size image URL with fallback
                        $image_url = $image ? $image['sizes']['thumbnail-square'] : get_template_directory_uri() . '/images/placeholder.png';
                    ?>
                    <div class="small-box-item">
                        <div class="small-box-image image-cover margin-b-20" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                        <div>
                            <?php if ($title): ?>
                                <p class="fs-300 fw-semibold"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if ($description): ?>
                                <p class="margin-b-20 grey-text"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($link): ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="hl arrow" target="<?php echo esc_attr($link['target']); ?>">
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
