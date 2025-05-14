<?php
// Setup
$block_title       = get_sub_field('document_list_title');
$block_button      = get_sub_field('document_list_button');
$behaviour         = get_sub_field('document_list_behaviour');
$pick_hub          = get_sub_field('document_list_pick_hub');
$pick_documents    = get_sub_field('document_list_pick_documents');
$manual_documents  = get_sub_field('document_list_documents');
$pick_count        = get_sub_field('document_list_pick_count');

$documents_array = [];

// Helper to get category image or fallback
function get_category_icon_url($cat_id)
{
    $image_id = get_field('category_image', 'category_' . $cat_id);
    return $image_id
        ? wp_get_attachment_image_url($image_id, 'thumbnail')
        : get_template_directory_uri() . '/images/icon-01.svg';
}

// 📌 'latest'
if ($behaviour === 'latest') {
    $args = ['post_type' => 'knowledge-hub', 'posts_per_page' => -1, 'post_status' => 'publish'];

    if ($pick_hub) {
        $hub_post = get_post($pick_hub);
        $args += $hub_post->post_parent === 0
            ? ['post_parent__in' => [$pick_hub]]
            : ['p' => $pick_hub];
    }

    foreach (get_posts($args) as $hub) {
        $docs = get_field('documents_list', $hub->ID);
        foreach ((array) $docs as $doc) {
            if (!empty($doc['file'])) {
                $cat_id = $doc['category'] ?? '';
                $documents_array[] = (object)[
                    'file'       => $doc['file'],
                    'category_label' => $cat_id ? get_term($cat_id)->name ?? get_the_title($hub->ID) : get_the_title($hub->ID),
                    'category_id'    => $cat_id,
                    'category_image' => get_category_icon_url($cat_id),
                    'all_brands'     => $doc['all_brands'] ?? false,
                    'associated_brands_label' => array_map('get_the_title', $doc['associated_brands'] ?? [])
                ];
            }
        }
    }

    if ($pick_count) {
        $documents_array = array_slice($documents_array, 0, (int)$pick_count);
    }
}

// 📌 'pick'
elseif ($behaviour === 'pick' && !empty($pick_documents)) {
    $wanted_ids = array_map(fn($doc) => $doc['document_list_pick']['ID'] ?? null, $pick_documents);
    $hubs = get_posts(['post_type' => 'knowledge-hub', 'posts_per_page' => -1, 'post_status' => 'publish']);

    foreach ($hubs as $hub) {
        foreach ((array) get_field('documents_list', $hub->ID) as $doc) {
            $file_id = $doc['file']['ID'] ?? null;
            if ($file_id && in_array($file_id, $wanted_ids, true)) {
                $cat_id = $doc['category'] ?? '';
                $documents_array[] = (object)[
                    'file'       => $doc['file'],
                    'category_label' => $cat_id ? get_term($cat_id)->name ?? get_the_title($hub->ID) : get_the_title($hub->ID),
                    'category_id'    => $cat_id,
                    'category_image' => get_category_icon_url($cat_id),
                    'all_brands'     => $doc['all_brands'] ?? false,
                    'associated_brands_label' => array_map('get_the_title', $doc['associated_brands'] ?? [])
                ];
            }
        }
    }
}

// 📌 'manual'
elseif ($behaviour === 'manual' && !empty($manual_documents)) {
    foreach ($manual_documents as $doc) {
        $cat_id = $doc['document_list_category'] ?? '';
        $documents_array[] = (object)[
            'file' => [
                'url'      => $doc['document_list_link']['url'] ?? '',
                'title'    => $doc['document_list_doc_title'] ?? '',
                'filename' => $doc['document_list_doc_title'] ?? ''
            ],
            'category_label' => $cat_id ? get_term($cat_id)->name ?? '' : '',
            'category_id'    => $cat_id,
            'category_image' => get_category_icon_url($cat_id),
            'all_brands'     => false,
            'associated_brands_label' => []
        ];
    }
}
?>

<div class="doc-list-outer cont-m padding-t-100 padding-b-100 theme-none">
    <div class="title-strip margin-b-30">
        <?php if ($block_title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($block_title); ?></h3>
        <?php endif; ?>
        <?php if ($block_button): ?>
            <a href="<?php echo esc_url($block_button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($block_button['target']); ?>">
                <?php echo esc_html($block_button['title']); ?>
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
                    <?php foreach ($documents_array as $doc):
                        $file = $doc->file;
                        $title = $file['title'] ?? $file['filename'] ?? 'Untitled';
                    ?>
                        <tr class="file-list__item"
                            data-category="<?php echo esc_attr($doc->category_id); ?>"
                            data-clickable-url="<?php echo esc_url($file['url']); ?>">

                            <td class="file-list__item-icon">
                                <img src="<?php echo esc_url($doc->category_image); ?>" alt="<?php echo esc_attr($doc->category_label); ?>" class="icon">
                            </td>
                            <td class="file-list__item-group"><?php echo esc_html($doc->category_label); ?></td>
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