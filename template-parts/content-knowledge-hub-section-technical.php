


<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if (get_the_content()) { ?>
    <div class="cont-m margin-b-70">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
    <?php } ?>
	
    <?php
        $child_pages = get_pages(array(
            'child_of' => get_the_ID(),
            'sort_column' => 'menu_order',
            'sort_order' => 'ASC'
        ));

        $document_group_names = [];

        foreach ($child_pages as $child) {
            $document_groups = get_field('document_group', $child->ID);

            if ($document_groups) {
                foreach ($document_groups as $group) {
                    if (!empty($group['document_group_name'])) {
                        $group_name = is_array($group['document_group_name']) ? implode(', ', $group['document_group_name']) : $group['document_group_name'];
                        $document_group_names[$group_name] = $group_name;
                    }
                }
            }
        } 
    ?>

    <?php if (!empty($document_group_names)) : ?>
    <nav class="cont-m margin-t-70 margin-b-70">
        <div class="section-list" data-component="section-list">
            <?php if (!empty($document_group_names) && count($document_group_names) > 1) : ?>
            <div class="file-list__dropdown">
                <button id="filter-toggle" class="file-list__dropdown-button hl arrow">
                    Filter
                </button>
                <div id="filter-options" class="filter-toggle__options hidden">
                    <?php foreach ($document_group_names as $label) : ?>
                        <label class="filter-toggle__label">
                            <input type="checkbox" value="<?php echo esc_attr($label); ?>" class="filter-options__checkbox">
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Show All Button -->
            <button id="section-list-show-all" class="file-list__show-all hidden">Reset Filters</button>
            <?php endif; ?>
        <?php // Now display the structure in rows
        echo '<div class="articles articles--grid-alt margin-t-30">';
        foreach ($document_group_names as $group_name) {
            foreach ($child_pages as $child) { ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('section-list__item'); ?> data-clickable-url="<?php echo get_the_permalink($child->ID); ?>" data-gallery-label="<?php echo esc_html($group_name); ?>">
                <div class="articles__content padding-30">
                    <?php 
                        echo '<div class="tags margin-b-20"><span class="tag">' . esc_html($group_name) . '</span></div>';
                        echo '<h3 class="fs-400 fw-semibold margin-b-20">' . esc_html(get_the_title($child->ID)) . '</h3>';
                        echo '<p class="articles__cta"><a href="'.get_the_permalink($child->ID).'" class="hl arrow">View Information Sheets</a></p>';
                    ?>
                </div>
            </article>
            <?php }
        }
        echo '</div>'; ?>
        </div>
    </nav>
    <?php endif; ?>

    <?php
        $document_groups = get_field('document_group');
        if ($document_groups) {
            $fallback_title = get_the_title();
            $documents_array = [];
            $group_names = []; // To store unique group names (only if they have files)

            foreach ($document_groups as $group) {
                $group_name = !empty($group['document_group_name']) ? $group['document_group_name'] : $fallback_title;
                
                // Check if "document_group_outer" exists
                if (!empty($group['document_group_outer'])) {
                    $outer = $group['document_group_outer'];

                    // Main document gallery inside "document_group_outer"
                    $documents = $outer['document_group_documents'] ?? [];
                    if (is_array($documents) && count($documents) > 0) {
                        if (!in_array($group_name, $group_names)) {
                            $group_names[] = $group_name;
                        }
                        foreach ($documents as $document) {
                            $file_date = isset($document['date']) ? strtotime($document['date']) : 0;

                            $documents_array[] = (object) [
                                'group_name' => $group_name, 
                                'file' => $document,
                                'date' => $file_date
                            ];
                        }
                    }

                    // Check for sub-groups inside "document_group_outer"
                    if (!empty($outer['document_sub_groups']) && is_array($outer['document_sub_groups'])) {
                        foreach ($outer['document_sub_groups'] as $sub_group) {
                            $sub_group_name = !empty($sub_group['document_sub_group_name']) ? $sub_group['document_sub_group_name'] : $group_name;

                            $sub_documents = $sub_group['document_sub_group_documents'] ?? [];
                            if (is_array($sub_documents) && count($sub_documents) > 0) {
                                if (!in_array($sub_group_name, $group_names)) {
                                    $group_names[] = $sub_group_name;
                                }
                                foreach ($sub_documents as $document) {
                                    $file_date = isset($document['date']) ? strtotime($document['date']) : 0;

                                    $documents_array[] = (object) [
                                        'group_name' => $sub_group_name, 
                                        'file' => $document,
                                        'date' => $file_date
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            // Sort documents by date (newest first)
            usort($documents_array, function ($a, $b) {
                return $b->date - $a->date;
            });
        }
    ?>
    <div class="cont-m margin-b-100">
    <?php if (!empty($documents_array)) : ?>
        <div class="file-list" data-component="file-list">
            <?php if (!empty($group_names) && count($group_names) > 1) : ?>
                <div class="file-list__dropdown">
                    <button id="filter-toggle" class="file-list__dropdown-button hl arrow">
                        Filter
                    </button>
                    <div id="filter-options" class="filter-toggle__options hidden">
                        <?php foreach ($group_names as $label) : ?>
                            <label class="filter-toggle__label">
                                <input type="checkbox" value="<?php echo esc_attr($label); ?>" class="filter-options__checkbox">
                                <?php echo esc_html($label); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button id="file-list-show-all" class="file-list__show-all hidden">Show All</button>
                </div>
            <?php endif; ?>
            <table>
                <thead class="screen-reader-text">
                    <tr>
                        <th scope="col">Icon</th>
                        <th scope="col">Gallery Label</th>
                        <th scope="col">Title</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents_array as $document) : 
                        $file = $document->file;
                    ?>
                        <tr class="file-list__item" data-gallery-label="<?php echo esc_html($document->group_name); ?>" data-clickable-url="<?php echo esc_url($file['url']); ?>">
                            <td class="file-list__item-icon"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-03.svg" alt="" class="icon"></td>
                            <td class="file-list__item-group"><?php echo esc_html($document->group_name); ?></td>
                            <td class="file-list__item-title"><?php echo esc_html(str_replace('_', ' ', $file['title'] ?? $file['filename'])); ?></td>
                            <td class="file-list__item-action">
                                <a href="<?php echo esc_url($file['url']); ?>" class="hl download" download>View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <!-- <p><?php echo esc_html__('Sorry, no items currently available.', 'your-text-domain'); ?></p> -->
    <?php endif; ?>
    </div>

</section><!-- #post-<?php the_ID(); ?> -->

