<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('document_list_title');
$button = get_sub_field('document_list_button'); // ACF Link field
$documents = get_sub_field('document_list_documents');
?>

<div class="doc-list-outer cont-m margin-b-100">
    
	<h2 style="color:red">* TO BE REPLACED *</h2>
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
    
    <div class="doc-list">
        <?php if ($documents): ?>
            <?php foreach ($documents as $document): ?>
                <?php 
                    $icon = $document['document_list_icon'];
                    $category = $document['document_list_category'];
                    $title = $document['document_list_doc_title'];
                    $link = $document['document_list_link']; // ACF Link field
                ?>
                <div class="doc-row">
                    <div class="doc-cat">
                        <?php if ($icon): ?>
                            <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($category); ?>" class="icon" />
                        <?php endif; ?>
                        <?php if ($category): ?>
                            <p class="fs-100 blue-text"><?php echo esc_html($category); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($title): ?>
                        <p class="fs-400 fw-semibold"><?php echo esc_html($title); ?></p>
                    <?php endif; ?>
                    <?php if ($link): ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="hl download" target="<?php echo esc_attr($link['target']); ?>">
                            <?php echo esc_html($link['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
</div>
