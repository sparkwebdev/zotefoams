<?php wp_enqueue_style('component-zoteiq-cta', get_template_directory_uri() . '/template-parts/components/zoteiq_cta/style.css', [], '1.0.0'); ?>

<div class="cont-m padding-t-b-30 component-zoteiq-cta">
    <?php zoteiq_link(get_sub_field('question')); ?>
</div>