<?php
$pageHeaderText = get_field('page_header_text');
if( $pageHeaderText ): 
    $title = !empty($pageHeaderText['title']) ? $pageHeaderText['title'] : get_the_title();
    $subtitle = $pageHeaderText['subtitle'] ?? '';
?>
    <header class="text-banner margin-t-70">
        <div class="cont-m margin-b-70">
            <?php if ( !empty($title) ): ?>
                <h1 class="uppercase grey-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                    <?php echo esc_html( $title ); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ( !empty($subtitle) ): ?>
                <h2 class="uppercase black-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                    <?php echo esc_html( $subtitle ); ?>
                </h2>
            <?php endif; ?>
        </div>
    </header>
<?php endif; ?>
