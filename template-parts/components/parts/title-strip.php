<?php
// Get template variables, prioritizing passed values (and falling back to ACF fields)
$title = get_query_var('title');
$link = get_query_var('link');

if ($title || $link) { ?>
    <div class="title-strip margin-b-30">
        <?php if ($title) : ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>

        <?php if ($link) :
            $link_url = $link['url'] ?? '#';
            $link_title = $link['title'] ?? 'Read More';
            $link_target = $link['target'] ?? '_self';
        ?>
            <a href="<?php echo esc_url($link_url); ?>" class="btn black outline" target="<?php echo esc_attr($link_target); ?>">
                <?php echo esc_html($link_title); ?>
            </a>
        <?php endif; ?>
    </div>
<?php } ?>
