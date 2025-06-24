<div class="post-navigation post-navigation--single margin-t-70 margin-b-70">
    <?php
    global $post;

    $parent_id = wp_get_post_parent_id($post->ID);

    // Get all sibling pages with same parent and 'page-article.php' template
    $siblings = get_pages([
        'child_of'     => $parent_id,
        'parent'       => $parent_id,
        'sort_column'  => 'post_date',
        'sort_order'   => 'asc',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'meta_key'     => '_wp_page_template',
        'meta_value'   => 'page-article.php',
    ]);

    // Find index of current page
    $current_index = -1;
    foreach ($siblings as $index => $sibling) {
        if ($sibling->ID === $post->ID) {
            $current_index = $index;
            break;
        }
    }

    $prev_page = ($current_index > 0) ? $siblings[$current_index - 1] : null;
    $next_page = ($current_index >= 0 && $current_index < count($siblings) - 1) ? $siblings[$current_index + 1] : null;
?>

    <?php if ($parent_id): ?>
        <div class="nav-back-to-list">
            <a href="<?php echo esc_url(get_permalink($parent_id)); ?>">
                <?php echo esc_html("« Back to '" . get_the_title($parent_id))."'"; ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($prev_page): ?>
        <div class="nav-previous">
            <a href="<?php echo esc_url(get_permalink($prev_page->ID)); ?>">
                <span class="nav-subtitle">« <?php esc_html_e('Previous', 'zotefoams'); ?></span>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($next_page): ?>
        <div class="nav-next">
            <a href="<?php echo esc_url(get_permalink($next_page->ID)); ?>">
                <span class="nav-subtitle"><?php esc_html_e('Next', 'zotefoams'); ?> »</span>
            </a>
        </div>
    <?php endif; ?>
</div>
