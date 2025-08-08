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

        <?php echo zotefoams_render_title_strip($section_title, $section_button); ?>

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
                            <?php echo zotefoams_render_link($link, [
                                'class' => 'hl arrow read-more'
                            ]); ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>