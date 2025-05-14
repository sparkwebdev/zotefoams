<?php

/**
 * highlight-box Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values
$title     = get_field('title') ?: 'Your title here...';
$copy      = get_field('copy') ?: 'Your copy here...';
$link      = get_field('link');
$image     = get_field('image');

// Optional anchor support
$anchor = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : '';

// Class name with align and custom class
$class_name = 'highlight-box';
$class_name .= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
$class_name .= !empty($block['align']) ? ' align' . esc_attr($block['align']) : '';
?>

<aside <?php echo $anchor; ?>class="<?php echo $class_name; ?>">
    <div class="highlight-box__col">
        <div class="highlight-box__content">
            <?php if ($title): ?>
                <h3 class="highlight-box__title"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <?php if ($copy): ?>
                <p class="highlight-box__copy"><?php echo wp_kses_post($copy); ?></p>
            <?php endif; ?>

            <?php if (!empty($link['url'])):
                $is_file = wp_check_filetype($link['url'])['ext'] ?? false;
                $link_class = $is_file ? 'highlight-box__link-download' : 'highlight-box__link-arrow';
            ?>
                <p class="margin-t-30">
                    <a href="<?php echo esc_url($link['url']); ?>"
                        class="highlight-box__link <?php echo esc_attr($link_class); ?>"
                        <?php if (!empty($link['target'])) echo 'target="' . esc_attr($link['target']) . '"'; ?>>
                        <?php echo esc_html($link['title'] ?? 'Read more'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($image): ?>
        <div class="highlight-box__col">
            <figure class="highlight-box__image">
                <?php echo wp_get_attachment_image($image['ID'], 'medium', false, ['class' => 'highlight-box__img']); ?>
            </figure>
        </div>
    <?php endif; ?>
</aside>