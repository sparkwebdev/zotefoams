<?php 
// Allow for passed variables, as well as ACF values
$columns = get_sub_field('icon_columns_columns');
?>

<div class="icon-columns light-grey-bg black-text padding-t-b-100 theme-light">
    <div class="icon-columns-inner cont-m">
        <?php if ($columns): ?>
            <?php foreach ($columns as $column): ?>
                <?php 
                    $icon = $column['icon_columns_icon'];
                    $title = $column['icon_columns_title'];
                    $text = $column['icon_columns_text'];
                ?>
                <div class="comp-08-item">
                    <?php if ($icon): ?>
                        <img class="margin-b-15" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($title); ?>" />
                    <?php endif; ?>
                    <?php if ($title): ?>
                        <p class="fs-400 fw-bold margin-b-20"><?php echo esc_html($title); ?></p>
                    <?php endif; ?>
                    <?php if ($text): ?>
                        <p class="grey-text"><?php echo wp_kses_post($text); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
