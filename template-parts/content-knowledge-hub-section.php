<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="text-banner cont-m margin-t-70 margin-b-100">
        <h1 class="uppercase grey-text fs-800 fw-extrabold"><?php echo get_the_title(wp_get_post_parent_id(get_the_ID())); ?></h1>
        <h2 class="uppercase black-text fs-800 fw-extrabold"><?php echo get_the_title(); ?></h2>
    </header>

    <hr class="separator margin-b-70" />

    <div class="text-block cont-m margin-b-70">
        <div class="text-block-inner">
            <p class="margin-b-20 grey-text"><?php echo get_the_title(); ?></p>
            <p class="grey-text fs-600 fw-semibold">Aenean quis lorem tempus sodales ipsum ac maximus mauris.
                <b>Morbi elementum nec dolor id porttitor.</b></p>
        </div>
    </div>

    <?php if (get_the_content()) { ?>
    <div class="cont-m margin-b-70">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
    <?php } ?>

	<?php
        $document_groups = get_field('document_group');
        if ($document_groups) {
            $fallback_title = get_the_title(); 
            $documents_array = [];
            $group_names = []; // To store unique group names (only if they have files)
            foreach ($document_groups as $group) {
                $group_name = !empty($group['document_group_name']) ? $group['document_group_name'] : $page_title;
                $documents = $group['document_group_documents'];
                if (is_array($documents) && count($documents) > 0) {
                    if (!in_array($group_name, $group_names)) {
                        $group_names[] = $group_name;
                    }
                    foreach ($documents as $document) {
                        $file_date = isset($document['date']) ? strtotime($document['date']) : 0;

                        if (str_contains($group_name, 'Certificates')) {
                            $icon = '02';
                        } elseif (str_contains($group_name, 'Safety')) {
                            $icon = '04';
                        } else {
                            $icon = '01';
                        }

                        $documents_array[] = (object) [
                            'group_name' => $group_name, 
                            'file' => $document,
                            'date' => $file_date // Store file date for sorting - may want to sort a different way?
                        ];
                    }
                }
            }
            // Sort documents by date (newest first)
            usort($documents_array, function ($a, $b) {
                return $b->date - $a->date; // Sort by timestamp (descending)
            });
        }
    ?>

    <!-- Filter and Select All -->
    <div class="cont-m margin-b-100">
    <?php if (!empty($documents_array)) : 
        // get_template_part( 'template-parts/documents-filter-list' );
        ?>
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
            </div>
            <!-- Show All Button -->
            <button id="file-list-show-all" class="file-list__show-all hidden">Reset Filters</button>
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
                            <td class="file-list__item-icon"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-<?php echo $icon; ?>.svg" alt="" class="icon"></td>
                            <td class="file-list__item-group"><?php echo esc_html($document->group_name); ?></td>
                            <td class="file-list__item-title"><?php echo esc_html(str_replace('_', ' ', $file['title'] ?? $file['filename'])); ?></td>
                            <td class="file-list__item-action">
                                <!-- <a href="<?php echo esc_url($file['url']); ?>" target="_blank">View</a> -->
                                <a href="<?php echo esc_url($file['url']); ?>" class="hl download" download>View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <p><?php echo esc_html__('Sorry, no items currently available.', 'your-text-domain'); ?></p>
    <?php endif; ?>
    </div>

</section><!-- #post-<?php the_ID(); ?> -->