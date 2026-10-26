<?php wp_enqueue_style('component-table', get_template_directory_uri() . '/template-parts/components/table/style.css', [], '1.0.0'); ?>

<div class="component-table cont-m">
    <h2 class="component-table__caption">
        <?php the_sub_field('caption'); ?>
    </h2>

    <table class="component-table__table">
        <thead class="component-table__table__head">
            <tr class="component-table__table__row">
                <?php foreach (get_sub_field('columns') as $column): ?>
                    <th class="component-table__table__cell component-table__table__cell--header">
                        <?php echo esc_html($column['title']); ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody class="component-table__table__body">
            <?php foreach (get_sub_field('rows') as $row): ?>
                <tr class="component-table__table__row">
                    <?php foreach ($row['cells'] as $cell): ?>
                        <td class="component-table__table__cell component-table__table__cell--<?php echo esc_attr($cell['acf_fc_layout']); ?>">
                            <div class="component-table__table__cell__contents">

                                <?php if ("text" === $cell['acf_fc_layout']): ?>

                                    <?php echo wp_kses_post($cell['body']); ?>
                                    <?php zotefoams_link($cell['cta']); ?>

                                <?php elseif ("image" === $cell['acf_fc_layout']): ?>

                                    <?php echo wp_get_attachment_image($cell['image'], 'medium') ?>

                                <?php elseif ("chips" === $cell['acf_fc_layout']): ?>

                                    <ul>
                                        <?php foreach (explode(",", $cell['items']) as $chip): ?>
                                            <li>
                                                <?php echo esc_attr(trim($chip)); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php endif; ?>

                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>