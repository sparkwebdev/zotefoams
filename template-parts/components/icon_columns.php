<?php 
// Allow for passed variables, as well as ACF values
$columns = get_sub_field('icon_columns_columns');
?>


<?php if (is_page('Sustainability')) : ?>

<div class="sustainability-icons black-text padding-t-b-100 cont-m theme-none">

	<div class="sustainability-intro grey-text">
		<p class="margin-b-20">Thriving in a lower carbon economy</p>
		<h3 class="fs-600 margin-b-70 grey-text">Four aspects of our business <strong>enable us to thrive in a lower carbon economy</strong> and <strong>support our customers in meeting their sustainability goals.</strong></h3>
	</div>
	
    <div class="sustainability-icons-inner">
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

<div class="waste-hierarchy text-center light-grey-bg padding-t-b-100 theme-light">

	<div class="waste-hierarchy-intro text-center">
		<h3 class="fs-600 margin-b-20">The Hierarchy of Waste</h3>
		<p class="fs-300 grey-text margin-b-70">Zotefoams put in place a Group-wide Waste Hierarchy Framework to support decision-making on waste management.</p>
	</div>
	
	<img src="<?php echo get_template_directory_uri(); ?>/images/waste-hierarchy.png" />

</div>

<div class="development-goals padding-t-b-100 cont-m theme-none">

	<div class="sustainability-intro grey-text">
		<p class="margin-b-20">Development Goals</p>
		<h3 class="fs-600 margin-b-70 grey-text">
			<strong>Zotefoams supports the United Nations Sustainable Development Goals and have aligned a number of our processes and activities to these.</strong>
		</h3>
	</div>
	
	<div class="development-goals-list">
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon7-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon6-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon5-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon4-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon3-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon2-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon1-min.png" />
		<img src="<?php echo get_template_directory_uri(); ?>/images/esg-icons/esg-icon8-min.png" />
	</div>

</div>

<?php else : ?>

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

<?php endif; ?>