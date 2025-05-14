<?php

/**
 * quote-box Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load ACF fields with fallbacks
$quote       = get_field('quote') ?: 'Your quote here...';
$author      = get_field('author');
$author_role = get_field('role');
$image       = get_field('image');

// Build attribution block
$quote_attribution = '';
if ($author) {
    $quote_attribution .= '<footer class="quote-box__attribution">';
    $quote_attribution .= '<cite class="quote-box__author">' . esc_html($author) . '</cite>';

    if ($author_role) {
        $quote_attribution .= '<span class="quote-box__role">' . esc_html($author_role) . '</span>';
    }

    $quote_attribution .= '</footer>';
}

// Handle anchor and class names
$anchor = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : '';

$class_name = 'quote-box';
$class_name .= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
$class_name .= !empty($block['align']) ? ' align' . esc_attr($block['align']) : '';
?>

<aside <?php echo $anchor; ?>class="<?php echo $class_name; ?>">
    <?php if ($image): ?>
        <div class="quote-box__col">
            <figure class="quote-box__image">
                <?php
                echo wp_get_attachment_image(
                    $image['ID'],
                    'thumbnail-square',
                    false,
                    ['class' => 'quote-box__img', 'alt' => esc_attr($author ?: 'Quote image')]
                );
                ?>
            </figure>
        </div>
    <?php endif; ?>

    <div class="quote-box__col">
        <blockquote class="quote-box__blockquote">
            <?php echo esc_html($quote); ?>

            <?php if ($quote_attribution): ?>
                <?php echo wp_kses_post($quote_attribution); ?>
            <?php endif; ?>
        </blockquote>
    </div>
</aside>