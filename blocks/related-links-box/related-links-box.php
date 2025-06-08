<?php

/**
 * related-links-box Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$title = !empty(get_field('title')) ? get_field('title') : 'Related Links and Documents';
$links = get_field('links');

// Support custom "anchor" values.
$anchor = '';
if (! empty($block['anchor'])) {
    $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'related-links-box';
if (! empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (! empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

?>

<aside <?php echo esc_attr($anchor); ?> class="<?php echo esc_attr($class_name); ?>">
    <?php if (!empty($title)) : ?>
        <h3 class="related-links-box__title"><?php echo wp_kses_post($title); ?></h3>
    <?php endif; ?>
    <?php if ($links) : ?>
        <nav class="related-links-box__links">
            <ul>
                <?php foreach ($links as $link) : ?>
                    <?php
                    if ($link['link']): // ✅ Correctly use the repeater's "link" sub-field
                        $link_url = $link['link']['url'];
                        $link_title = $link['link']['title'];
                        $link_target = $link['link']['target'] ? $link['link']['target'] : '_self';
                        $is_file = wp_check_filetype($link_url)['ext'] ? true : false;

                    ?>
                        <li>
                            <a class="related-links-box__link <?php echo $is_file ? 'related-links-box__link-download' : 'related-links-box__link-arrow'; ?> " href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                                <?php echo esc_html($link_title); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</aside>