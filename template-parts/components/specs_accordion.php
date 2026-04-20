<?php
$title            = zotefoams_get_sub_field_safe('specs_accordion_title', '', 'string');
$items            = zotefoams_get_sub_field_safe('specs_accordion_items', [], 'array');
$use_default_cta  = zotefoams_get_sub_field_safe('specs_accordion_use_default_cta', true, 'bool');
$cta              = zotefoams_get_sub_field_safe('specs_accordion_cta', [], 'url');
$variant          = zotefoams_get_sub_field_safe('specs_accordion_variant', false, 'bool');

$theme_classes = $variant
    ? ' dark-grey-bg white-text theme-dark'
    : ' white-bg theme-none';
?>

<div class="accordion padding-t-b-100<?php echo esc_attr($theme_classes); ?>">
    <div class="cont-m">

        <?php if ($title) : ?>
        <div class="text-block">
            <div class="text-block__inner">
                <h2 class="fs-500 fw-semibold margin-b-40"><?php echo esc_html($title); ?></h2>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <div class="accordion-items">
                <?php foreach ($items as $item) :
                    $section_title = $item['specs_accordion_item_title'] ?? '';
                    $rows          = $item['specs_accordion_item_rows'] ?? [];
                    if (!$section_title || !$rows) continue;
                    // Build columns; suppress any column with no header and no row data
                    $all_columns = [
                        ['header' => $item['specs_accordion_col1_header'] ?? '', 'key' => 'specs_accordion_row_col1'],
                        ['header' => $item['specs_accordion_col2_header'] ?? '', 'key' => 'specs_accordion_row_col2'],
                        ['header' => $item['specs_accordion_col3_header'] ?? '', 'key' => 'specs_accordion_row_col3'],
                        ['header' => $item['specs_accordion_col4_header'] ?? '', 'key' => 'specs_accordion_row_col4'],
                        ['header' => $item['specs_accordion_col5_header'] ?? '', 'key' => 'specs_accordion_row_col5'],
                        ['header' => $item['specs_accordion_col6_header'] ?? '', 'key' => 'specs_accordion_row_col6'],
                    ];
                    $columns = array_values(array_filter($all_columns, function($col) use ($rows) {
                        if ($col['header'] !== '') return true;
                        foreach ($rows as $row) {
                            if (!empty($row[$col['key']])) return true;
                        }
                        return false;
                    }));
                    $has_headers = (bool) array_filter($columns, fn($c) => $c['header'] !== '');
                ?>
                    <div class="accordion-item">
                        <button class="accordion-header fs-400 fw-semibold" data-js="accordion-header" aria-expanded="false">
                            <?php echo esc_html($section_title); ?>
                            <span class="toggle-icon" aria-hidden="true" data-js="toggle-icon">+</span>
                        </button>

                        <?php if ($rows) : ?>
                            <div class="accordion-content">
                                <div class="margin-b-40">
                                    <table class="data-table data-table--collapsible">
                                        <caption class="screen-reader-text"><?php echo esc_html($section_title); ?></caption>
                                        <?php if ($has_headers) : ?>
                                            <thead class="uppercase">
                                                <tr>
                                                    <?php foreach ($columns as $i => $col) : ?>
                                                        <?php if ($col['header']) : ?>
                                                            <th scope="col" class="fs-100 fw-normal"><?php echo esc_html($col['header']); ?></th>
                                                        <?php else : ?>
                                                            <th scope="col" aria-label="<?php echo esc_attr('Column ' . ($i + 1)); ?>"></th>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                        <?php endif; ?>
                                        <tbody>
                                            <?php foreach ($rows as $row) :
                                                $cells = array_map(fn($c) => $row[$c['key']] ?? '', $columns);
                                                if (!array_filter($cells)) continue;
                                            ?>
                                                <tr>
                                                    <?php foreach ($columns as $i => $col) :
                                                        $cell_value = $row[$col['key']] ?? '';
                                                        $label = $col['header'] ?: 'Column ' . ($i + 1);
                                                    ?>
                                                        <?php if ($i === 0) : ?>
                                                            <th scope="row" class="fw-regular" data-label="<?php echo esc_attr($label); ?>"><?php echo esc_html($cell_value); ?></th>
                                                        <?php else : ?>
                                                            <td data-label="<?php echo esc_attr($label); ?>"><?php echo esc_html($cell_value); ?></td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($use_default_cta) : ?>
            <div class="margin-t-40">
                <a href="#contact-forms-item-2" class="btn blue btn--chevron-down">
                    Request a Quote
                </a>
            </div>
        <?php elseif (!empty($cta['url'])) : ?>
            <div class="margin-t-40">
                <?php echo Zotefoams_Button_Helper::render($cta, ['style' => 'primary']); ?>
            </div>
        <?php endif; ?>

    </div>
</div>
