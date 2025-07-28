<?php
// Get field data using safe helper functions
$title  = zotefoams_get_sub_field_safe('show_hide_title', '', 'string');
$button = zotefoams_get_sub_field_safe('show_hide_button', [], 'url');
$items  = zotefoams_get_sub_field_safe('show_hide_items', [], 'array');
$unique_id  = zotefoams_get_sub_field_safe('unique_id', '', 'string');
$sanitized_unique_id = str_replace('.', '-', $unique_id);

// Generate classes to match original structure exactly
$wrapper_classes = 'accordion cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>" id="<?php echo esc_attr($sanitized_unique_id); ?>">

    <?php if ($title || $button) : ?>
        <div class="title-strip padding-b-30">
            <?php if ($title) : ?>
                <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <?php if ($button) : ?>
                <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                    <?php echo esc_html($button['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="accordion-items">
        <?php if ($items) :
            $index = 1;
            foreach ($items as $item) :
                $question = $item['show_hide_question'];
                $answer   = $item['show_hide_answer'];
                $item_id  = $sanitized_unique_id . '-item-' . $index;
        ?>
                <div class="accordion-item" id="<?php echo esc_attr($item_id); ?>">
                    <?php if ($question) : ?>
                        <button class="accordion-header fs-400 fw-semibold">
                            <?php echo esc_html($question); ?>
                            <span class="toggle-icon">+</span>
                        </button>
                    <?php endif; ?>

                    <?php if ($answer) : ?>
                        <div class="accordion-content">
                            <?php echo wp_kses_post($answer); ?>
                        </div>
                    <?php endif; ?>
                </div>
        <?php
                $index++;
            endforeach;
        endif; ?>
    </div>

</div>