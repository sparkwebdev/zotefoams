<?php
// Get field data using safe helper functions
$title     = zotefoams_get_sub_field_safe('show_hide_title', '', 'string');
$button    = zotefoams_get_sub_field_safe('show_hide_button', [], 'url');
$items     = zotefoams_get_sub_field_safe('show_hide_items', [], 'array');
$unique_id = zotefoams_get_sub_field_safe('unique_id', '', 'string');
$sanitized_unique_id = str_replace('.', '-', $unique_id);

// Generate classes to match original structure exactly
$wrapper_classes = 'accordion cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>" id="<?php echo esc_attr($sanitized_unique_id); ?>">

    <?php echo zotefoams_render_title_strip($title, $button, ['wrapper_class' => 'title-strip padding-b-30']); ?>

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