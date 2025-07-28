<?php
// Get field data using safe helper functions
$section_title = zotefoams_get_sub_field_safe('small_box_columns_title', '', 'string');
$section_button = zotefoams_get_sub_field_safe('small_box_columns_button', [], 'url');
$items = zotefoams_get_sub_field_safe('small_box_columns_items', [], 'array');

// Generate classes to match original structure exactly
$wrapper_classes = 'small-box-columns padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="cont-m">

        <div class="title-strip margin-b-30">
            <?php if ($section_title) : ?>
                <h3 class="fs-500 fw-600"><?php echo esc_html($section_title); ?></h3>
            <?php endif; ?>

            <?php if ($section_button) : ?>
                <a href="<?php echo esc_url($section_button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($section_button['target']); ?>">
                    <?php echo esc_html($section_button['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="small-box-columns__items">
            <?php if ($items) : ?>
                <?php foreach ($items as $item) :
                    $image       = $item['small_box_columns_item_image'];
                    $item_title  = $item['small_box_columns_item_title'];
                    $description = $item['small_box_columns_item_description'];
                    $link        = $item['small_box_columns_item_link'];

                    $image_url = Zotefoams_Image_Helper::get_image_url($image, 'thumbnail-square', 'thumbnail-square');
                ?>
                    <div>
                        <?php echo Zotefoams_Image_Helper::render_image($image, [
                            'size'    => 'thumbnail-square',
                            'context' => 'thumbnail-square',
                            'class'   => 'small-box-columns__image margin-b-20',
                            'alt'     => $item_title,
                            'width'   => '335',
                            'height'  => '335'
                        ]); ?>

                        <?php if ($item_title) : ?>
                            <p class="fs-300 fw-semibold"><?php echo esc_html($item_title); ?></p>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <p class="margin-b-20 grey-text"><?php echo esc_html($description); ?></p>
                        <?php endif; ?>

                        <?php if ($link) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="hl arrow read-more" target="<?php echo esc_attr($link['target']); ?>">
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>