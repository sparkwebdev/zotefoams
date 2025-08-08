<?php
// Get field data using safe helper functions
$pageHeaderText = get_field('page_header_text'); // ACF field group
if ($pageHeaderText):
    $title = !empty($pageHeaderText['title']) ? $pageHeaderText['title'] : get_the_title();
    $subtitle = $pageHeaderText['subtitle'] ?? '';
    
    // Generate classes to match original structure exactly
    $wrapper_classes = 'text-banner padding-t-b-70';
?>
    <header class="<?php echo $wrapper_classes; ?>" role="banner" aria-label="Page Header">
        <div class="cont-m">
            <?php if (!empty($title)): ?>
                <h1 class="uppercase grey-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                    <?php echo esc_html($title); ?>
                </h1>
            <?php endif; ?>

            <?php if (!empty($subtitle)): ?>
                <p class="uppercase black-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                    <?php echo esc_html($subtitle); ?>
                </p>
            <?php endif; ?>
        </div>
    </header>
<?php endif; ?>