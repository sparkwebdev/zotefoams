<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('box_columns_title');
$button = get_sub_field('box_columns_button'); // ACF Link field
$cases = get_sub_field('box_columns_cases');
?>

<div class="box-columns cont-m padding-t-b-100">
    
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

    <div class="box-items">
        <?php if ($cases): ?>
            <?php foreach ($cases as $case): ?>
                <?php 
                    $title = $item['box_columns_item_title'] ?? '';
                    $description = $item['box_columns_item_description'] ?? '';
                    $button = $item['box_columns_item_button'] ?? '';
                    $image = $item['box_columns_item_image'] ?? null;
                    
                    // Extract 'large' size image URL with fallback
                    $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <div>
                            <?php if ($title): ?>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if ($description): ?>
                                <p class="margin-b-20 grey-text"><?php echo wp_kses_post($description); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($button): ?>
                            <a href="<?php echo esc_url($button['url']); ?>" class="hl arrow" target="<?php echo esc_attr($button['target']); ?>">
                                <?php echo esc_html($button['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
