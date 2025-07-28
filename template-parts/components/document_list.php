<?php
// Get field data using safe helper functions
$title             = zotefoams_get_sub_field_safe('document_list_title', '', 'string');
$button            = zotefoams_get_sub_field_safe('document_list_button', [], 'url');
$behaviour         = zotefoams_get_sub_field_safe('document_list_behaviour', '', 'string'); // 'latest', 'pick', or 'manual'
$pick_hub          = zotefoams_get_sub_field_safe('document_list_pick_hub', 0, 'int');
$pick_documents    = zotefoams_get_sub_field_safe('document_list_pick_documents', [], 'array');
$manual_documents  = zotefoams_get_sub_field_safe('document_list_documents', [], 'array');
$pick_count        = zotefoams_get_sub_field_safe('document_list_pick_count', 0, 'int');

$documents_array = [];

// 🔹 Helper: Category info with fallback icon
function get_category_data($category_id, $fallback_title = 'Uncategorized')
{
    $name = $fallback_title;
    $image_url = get_template_directory_uri() . '/images/icon-01.svg';

    if ($category_id) {
        $term = get_term($category_id);
        if ($term && !is_wp_error($term)) {
            $name = $term->name;
        }

        $image_id = get_field('category_image', 'category_' . $category_id);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
        }
    }

    return [
        'name'  => $name,
        'image' => $image_url,
    ];
}

// 🔹 Helper: Create document object
function create_document_entry($file, $category_data, $category_id, $all_brands = false, $brands = [])
{
    return (object) [
        'file' => $file,
        'category_label' => $category_data['name'],
        'category_id' => $category_id,
        'category_image' => $category_data['image'],
        'all_brands' => $all_brands,
        'associated_brands' => $brands,
        'associated_brands_label' => array_map('get_the_title', $brands),
    ];
}

// 📌 latest behaviour
if ($behaviour === 'latest') {
    $args = [
        'post_type' => 'knowledge-hub',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    if ($pick_hub) {
        $hub = get_post($pick_hub);
        $args += ($hub && $hub->post_parent == 0)
            ? ['post_parent__in' => [$pick_hub]]
            : ['p' => $pick_hub];
    }

    $hubs = get_posts($args);
    foreach ($hubs as $hub) {
        $documents = get_field('documents_list', $hub->ID);
        if ($documents) {
            foreach ($documents as $doc) {
                if (!empty($doc['file'])) {
                    $cat_id = $doc['category'] ?? '';
                    $cat_data = get_category_data($cat_id, get_the_title($hub->ID));
                    $documents_array[] = create_document_entry(
                        $doc['file'],
                        $cat_data,
                        $cat_id,
                        $doc['all_brands'] ?? false,
                        $doc['associated_brands'] ?? []
                    );
                }
            }
        }
    }

    if ($pick_count && count($documents_array) > $pick_count) {
        $documents_array = array_slice($documents_array, 0, $pick_count);
    }
}

// 📌 pick behaviour
elseif ($behaviour === 'pick' && !empty($pick_documents)) {
    $selected_ids = array_filter(array_map(fn($doc) => $doc['document_list_pick'], $pick_documents));
    $hubs = get_posts([
        'post_type' => 'knowledge-hub',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    foreach ($hubs as $hub) {
        $docs = get_field('documents_list', $hub->ID);
        if ($docs) {
            foreach ($docs as $doc) {
                $file_id = $doc['file']['ID'] ?? null;
                if ($file_id && in_array($file_id, $selected_ids)) {
                    $cat_id = $doc['category'] ?? '';
                    $cat_data = get_category_data($cat_id, get_the_title($hub->ID));
                    $documents_array[] = create_document_entry(
                        $doc['file'],
                        $cat_data,
                        $cat_id,
                        $doc['all_brands'] ?? false,
                        $doc['associated_brands'] ?? []
                    );
                }
            }
        }
    }
}

// 📌 manual behaviour
elseif ($behaviour === 'manual' && !empty($manual_documents)) {
    foreach ($manual_documents as $doc) {
        $cat_id = $doc['document_list_category'] ?? '';
        $cat_data = get_category_data($cat_id, '');
        $documents_array[] = create_document_entry(
            [
                'url' => $doc['document_list_link']['url'] ?? '',
                'title' => $doc['document_list_doc_title'] ?? '',
                'filename' => $doc['document_list_doc_title'] ?? '',
            ],
            $cat_data,
            $cat_id
        );
    }
}

// Generate classes to match original structure exactly
$wrapper_classes = 'doc-list-outer cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">
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
                            <td class="file-list__item-group"><?php echo esc_html($document->category_label); ?></td>
                            <td class="file-list__item-title"><?php echo esc_html($title); ?></td>
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
