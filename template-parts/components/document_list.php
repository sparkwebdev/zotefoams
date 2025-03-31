<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('document_list_title');
$button = get_sub_field('document_list_button'); // ACF Link field
$behaviour = get_sub_field('document_list_behaviour'); // latest, pick, manual
$pick_hub = get_sub_field('document_list_pick_hub'); // Selected Knowledge Hub (optional)
$pick_documents = get_sub_field('document_list_pick_documents'); // Picked documents (if 'pick' selected)
$manual_documents = get_sub_field('document_list_documents'); // Manual documents (if 'manual' selected)
$pick_count = get_sub_field('document_list_pick_count'); // Number of items to display

$documents_array = [];

// 📌 Query for 'latest' behaviour (Include selected Hub + its Children)
if ($behaviour === 'latest') {
    $args = [
        'post_type'      => 'knowledge-hub',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];

    if ($pick_hub) {
        // Get post object for the selected hub
        $selected_hub = get_post($pick_hub);

        if ($selected_hub->post_parent == 0) {
            // 🔹 The selected hub is a top-level hub → Include its children
            $args['post_parent__in'] = [$pick_hub];
        } else {
            // 🔹 The selected hub is a child hub → Only query this specific hub
            $args['p'] = $pick_hub;
        }
    }

    // Fetch hubs based on adjusted query
    $knowledge_hubs = get_posts($args);

    // Loop through each Knowledge Hub and extract documents
    foreach ($knowledge_hubs as $hub) {
        $documents = get_field('documents_list', $hub->ID);
        if ($documents) {
            foreach ($documents as $document) {
                if (!empty($document['file'])) {
                    // 🔹 Fetch category name (or fallback to hub title)
                    $category_id = $document['category'] ?? '';
                    $category_name = $category_id ? get_term($category_id)->name ?? '' : get_the_title($hub->ID);

                    // 🔹 Fetch category icon (fallback to `icon-01.svg` if not found)
                    $category_image_id = $category_id ? get_field('category_image', 'category_' . $category_id) : '';
                    $category_image_url = $category_image_id 
                        ? wp_get_attachment_image_url($category_image_id, 'thumbnail') 
                        : get_template_directory_uri() . '/images/icon-01.svg';

                    $documents_array[] = (object) [
                        'file' => $document['file'],
                        'category_label' => $category_name,
                        'category_id' => $category_id,
                        'category_image' => $category_image_url,
                        'all_brands' => $document['all_brands'] ?? false,
                        'associated_brands' => $document['associated_brands'] ?? [],
                        'associated_brands_label' => array_map('get_the_title', $document['associated_brands'] ?? [])
                    ];
                }
            }
        }
    }

    // Limit the number of documents if needed
    if ($pick_count && count($documents_array) > $pick_count) {
        $documents_array = array_slice($documents_array, 0, $pick_count);
    }
}


// 📌 Query for 'pick' behaviour (selected documents)
elseif ($behaviour === 'pick' && !empty($pick_documents)) {
    $selected_document_ids = array_map(fn($doc) => $doc['document_list_pick'], $pick_documents);

    // Fetch all Knowledge Hubs that might contain these documents
    $args = [
        'post_type'      => 'knowledge-hub',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];
    $knowledge_hubs = get_posts($args);

    // Check each Knowledge Hub for the selected documents
    foreach ($knowledge_hubs as $hub) {
        $documents_list = get_field('documents_list', $hub->ID);
        
        if (!empty($documents_list)) {
            foreach ($documents_list as $document) {
                if (!empty($document['file']['ID']) && in_array($document['file']['ID'], $selected_document_ids)) {
                    
                    // 🔹 Fetch category name
                    $category_id = $document['category'] ?? '';
                    $category_name = get_term($category_id)->name ?? get_the_title($hub->ID); // Fallback to Hub title
                    
                    // 🔹 Fetch category icon (from 'category_image' field)
                    $category_image_id = get_field('category_image', 'category_' . $category_id);
                    $category_image_url = $category_image_id ? wp_get_attachment_image_url($category_image_id, 'thumbnail') : get_template_directory_uri() . '/images/icon-01.svg';

                    $documents_array[] = (object) [
                        'file' => $document['file'],
                        'category_label' => $category_name,
                        'category_id' => $category_id,
                        'category_image' => $category_image_url,
                        'all_brands' => $document['all_brands'] ?? false,
                        'associated_brands' => $document['associated_brands'] ?? [],
                        'associated_brands_label' => array_map('get_the_title', $document['associated_brands'] ?? [])
                    ];
                }
            }
        }
    }
}





// 📌 Query for 'manual' behaviour (Use ACF manual repeater)
elseif ($behaviour === 'manual' && !empty($manual_documents)) {
    foreach ($manual_documents as $document) {
        // 🔹 Fetch category name (or fallback to empty string)
        $category_id = $document['document_list_category'] ?? '';
        $category_name = $category_id ? get_term($category_id)->name ?? '' : '';

        // 🔹 Fetch category icon (fallback to placeholder.svg)
        $category_image_id = $category_id ? get_field('category_image', 'category_' . $category_id) : '';
        $category_image_url = $category_image_id ? wp_get_attachment_image_url($category_image_id, 'thumbnail') : get_template_directory_uri() . '/images/placeholder.svg';

        $documents_array[] = (object) [
            'file' => [
                'url' => $document['document_list_link']['url'] ?? '',
                'title' => $document['document_list_doc_title'] ?? '',
                'filename' => $document['document_list_doc_title'] ?? ''
            ],
            'category_label' => $category_name,
            'category_id' => $category_id,
            'category_image' => $category_image_url,
            'all_brands' => false,
            'associated_brands' => [],
            'associated_brands_label' => []
        ];
    }
}
?>

<div class="doc-list-outer cont-m padding-t-100 padding-b-100 theme-none">
    
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
    
    <?php if (!empty($documents_array)): ?>
        <div class="file-list">
            <table>
                <thead class="screen-reader-text">
                    <tr>
                        <th scope="col">Icon</th>
                        <th scope="col">Category</th>
                        <th scope="col">Title</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents_array as $document): 
                        $file = $document->file;
                        $title = !empty($file['title']) ? $file['title'] : $file['filename'];
                        ?>
                        <tr class="file-list__item"
                            data-category="<?php echo esc_attr($document->category_id); ?>"
                            data-clickable-url="<?php echo esc_url($file['url']); ?>">
                            
                            <td class="file-list__item-icon">
                                <img src="<?php echo esc_url($document->category_image); ?>" alt="<?php echo esc_attr($document->category_label); ?>" class="icon">
                            </td>
                            <td class="file-list__item-group">
                                <?php echo esc_html($document->category_label); ?>
                            </td>
                            <td class="file-list__item-title">
                                <?php echo esc_html($title); ?>
                            </td>
                            <td class="file-list__item-action">
                                <a href="<?php echo esc_url($file['url']); ?>" class="hl download" target="_blank" aria-label="View <?php echo esc_attr($title); ?>">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Sorry, no items currently available.</p>
    <?php endif; ?>
</div>
