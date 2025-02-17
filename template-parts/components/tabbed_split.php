<?php 
// Allow for passed variables, as well as ACF values
$tab_overline = get_sub_field('tabbed_split_overline');
$tab_text = get_sub_field('tabbed_split_text');
$tabs = get_sub_field('tabbed_split_tabs');
?>

<div class="cont-xs tabs-container margin-t-100 margin-b-20">
	
	
	<?php if ($tab_overline || $tab_text): ?>
		<div class="tabs-intro text-center margin-b-30">
			<?php if ($tab_overline): ?>
			<p class="margin-b-15"><?php echo esc_html($tab_overline); ?></p>
			<?php endif; ?>
			<?php if ($tab_text): ?>
			<p class="fs-500 fw-bold"><?php echo esc_html($tab_text); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
    <div class="tabs">
        <?php if ($tabs): ?>
            <?php foreach ($tabs as $index => $tab): ?>
                <?php 
                    $tab_id = sanitize_title($tab['tabbed_split_tab_title']);
                    $icon = $tab['tabbed_split_tab_icon'];
                    $is_active = $index === 0 ? 'active' : ''; // First tab is active
                ?>
                <div class="tab <?php echo esc_attr($is_active); ?>" data-tab="<?php echo esc_attr($tab_id); ?>">
                    <?php if ($icon): ?>
                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($tab['tabbed_split_tab_title']); ?>" />
                    <?php endif; ?>
                    <p><?php echo esc_html($tab['tabbed_split_tab_title']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="content-container light-grey-bg">
    <?php if ($tabs): ?>
        <?php foreach ($tabs as $index => $tab): ?>
            <?php 
                $tab_id = sanitize_title($tab['tabbed_split_tab_title']);
                $title = $tab['tabbed_split_content_title'];
                $text = $tab['tabbed_split_content_text'];
                $button = $tab['tabbed_split_button']; // ACF Link field
                $image = $tab['tabbed_split_image'];

                // Extract 'large' size image URL from Image Array, with fallback
                $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                $is_active = $index === 0 ? 'active' : ''; // First content is active
            ?>
            <div class="tab-content <?php echo esc_attr($is_active); ?>" id="<?php echo esc_attr($tab_id); ?>">
                <div class="half-half">
                    <div class="half">
                        <div class="all-content padding-t-b-100">
                            <div class="top-content">
                                <?php if ($title): ?>
                                    <p class="fs-400 fw-bold margin-b-15"><?php echo esc_html($title); ?></p>
                                <?php endif; ?>
                                <?php if ($text): ?>
                                    <div class="fs-300 grey-text margin-b-70"><?php echo wp_kses_post($text); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php if ($button): ?>
                                <a href="<?php echo esc_url($button['url']); ?>" class="hl arrow" target="<?php echo esc_attr($button['target']); ?>">
                                    <?php echo esc_html($button['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="half image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", () => {
			const tabs = document.querySelectorAll(".tab");
			const tabContents = document.querySelectorAll(".tab-content");

			tabs.forEach(tab => {
				tab.addEventListener("click", () => {
					// Remove active class from all tabs and contents
					tabs.forEach(t => t.classList.remove("active"));
					tabContents.forEach(c => c.classList.remove("active"));

					// Add active class to clicked tab and corresponding content
					tab.classList.add("active");
					document.getElementById(tab.dataset.tab).classList.add("active");
				});
			});
		});
	</script>