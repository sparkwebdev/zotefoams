<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('show_hide_title');
$button = get_sub_field('show_hide_button'); // ACF Link field
$items = get_sub_field('show_hide_items');
?>

<div class="accordion cont-m margin-t-100 margin-b-100 theme-none">
    
    <div class="title-strip margin-b-30">
        <?php if ($title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <?php if ($button): ?>
            <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                <?php echo esc_html($button['title']); ?>
            </a>
        <?php endif; ?>
    </div>
    
    <div class="accordion-items">
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <?php 
                    $question = $item['show_hide_question'];
                    $answer = $item['show_hide_answer'];
                ?>
                <div class="accordion-item">
                    <?php if ($question): ?>
                        <button class="accordion-header fs-400 fw-semibold">
                            <?php echo esc_html($question); ?>
                            <span class="toggle-icon">+</span>
                        </button>
                    <?php endif; ?>
                    <?php if ($answer): ?>
                        <div class="accordion-content">
                            <?php echo wp_kses_post($answer); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
</div>
