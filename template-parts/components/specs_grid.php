<?php
$title   = zotefoams_get_sub_field_safe('specs_grid_title', '', 'string');
$items   = zotefoams_get_sub_field_safe('specs_grid_items', [], 'array');
$download_file = zotefoams_get_sub_field_safe('specs_grid_download_link', [], 'array');
$variant = zotefoams_get_sub_field_safe('specs_grid_variant', false, 'bool');

$wrapper_classes = $variant
    ? 'specs-grid black-bg white-text theme-dark'
    : 'specs-grid white-bg theme-light';
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>">
    <div class="cont-m padding-t-b-70">

        <?php if ($title) : ?>
            <h2 class="fs-500 fw-semibold margin-b-40"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($items) : ?>
            <ul class="specs-grid__items margin-t-20 margin-b-70">
                <?php foreach ($items as $item) :
                    $name  = $item['specs_grid_item_name'] ?? '';
                    $value = $item['specs_grid_item_value'] ?? '';
                    if (!$name && !$value) continue;
                ?>
                    <li class="specs-grid__item">
                        <span class="fs-200 grey-text"><?php echo esc_html($name); ?></span>
                        <span class="screen-reader-text">: </span>
                        <span class="fw-bold fs-400"><?php echo esc_html($value); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (!empty($download_file['url'])) : ?>
            <div class="margin-t-40">
                <a href="<?php echo esc_url($download_file['url']); ?>"
                   class="hl download"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php echo esc_html($download_file['title'] ?? 'Download'); ?>
                    <span class="screen-reader-text"> <?php esc_html_e('(opens in new tab)', 'zotefoams'); ?></span>
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>
