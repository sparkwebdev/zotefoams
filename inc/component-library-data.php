<?php
/**
 * Component Library — dummy data definitions.
 *
 * Each function returns an array of field-name => value pairs that will be
 * injected into $GLOBALS['zotefoams_preview_fields'] before including the
 * real component template.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper: build a fake ACF image array.
 */
function zotefoams_cl_image($w = 800, $h = 600, $label = 'Preview', $size = 'large')
{
    $url = "https://placehold.co/{$w}x{$h}/e0e0e0/666?text=" . urlencode($label);
    return [
        'url'    => $url,
        'alt'    => $label,
        'sizes'  => [
            'thumbnail'        => "https://placehold.co/150x150/e0e0e0/666?text=" . urlencode($label),
            'thumbnail-square' => "https://placehold.co/335x335/e0e0e0/666?text=" . urlencode($label),
            'medium'           => "https://placehold.co/400x300/e0e0e0/666?text=" . urlencode($label),
            'large'            => $url,
            'thumbnail-product' => "https://placehold.co/400x400/e0e0e0/666?text=" . urlencode($label),
        ],
    ];
}

/**
 * Helper: build a fake ACF link array.
 */
function zotefoams_cl_link($title = 'Learn More', $url = '#')
{
    return [
        'url'    => $url,
        'title'  => $title,
        'target' => '',
    ];
}

/**
 * Master registry: component name => callback(s).
 * Each entry can be a single callback or an array of [label => callback] for variants.
 */
function zotefoams_component_library_registry()
{
    return [
        // --- Text ---
        'text_block' => [
            'label' => 'Text Block',
            'group' => 'Text',
            'data'  => 'zotefoams_cl_data_text_block',
        ],
        'split_text' => [
            'label' => 'Split Text',
            'group' => 'Text',
            'data'  => 'zotefoams_cl_data_split_text',
        ],
        'columns_content' => [
            'label' => 'Columns Content',
            'group' => 'Text',
            'data'  => 'zotefoams_cl_data_columns_content',
        ],
        'related_links_box' => [
            'label' => 'Related Links Box',
            'group' => 'Text',
            'data'  => 'zotefoams_cl_data_related_links_box',
        ],

        // --- Media ---
        'image_left_right' => [
            'label' => 'Image Left / Right',
            'group' => 'Media',
            'data'  => 'zotefoams_cl_data_image_left_right',
        ],
        'split_video_one' => [
            'label'    => 'Split Video One',
            'group'    => 'Media',
            'data'     => 'zotefoams_cl_data_split_video_one',
            'variants' => [
                'Variant' => 'zotefoams_cl_data_split_video_one_variant',
            ],
        ],
        'split_video_two' => [
            'label' => 'Split Video Two',
            'group' => 'Media',
            'data'  => 'zotefoams_cl_data_split_video_two',
        ],
        'highlight_panel' => [
            'label' => 'Highlight Panel',
            'group' => 'Media',
            'data'  => 'zotefoams_cl_data_highlight_panel',
        ],
        'text_banner_split' => [
            'label' => 'Text Banner Split',
            'group' => 'Media',
            'data'  => 'zotefoams_cl_data_text_banner_split',
        ],

        // --- Columns & Grids ---
        'icon_columns' => [
            'label'    => 'Icon Columns',
            'group'    => 'Columns & Grids',
            'data'     => 'zotefoams_cl_data_icon_columns',
            'variants' => [
                'Variant' => 'zotefoams_cl_data_icon_columns_variant',
            ],
        ],
        'icons_grid' => [
            'label' => 'Icons Grid',
            'group' => 'Columns & Grids',
            'data'  => 'zotefoams_cl_data_icons_grid',
        ],
        'box_columns' => [
            'label' => 'Box Columns (Manual)',
            'group' => 'Columns & Grids',
            'data'  => 'zotefoams_cl_data_box_columns',
        ],
        'small_box_columns' => [
            'label' => 'Small Box Columns',
            'group' => 'Columns & Grids',
            'data'  => 'zotefoams_cl_data_small_box_columns',
        ],
        'data_points' => [
            'label' => 'Data Points',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_data_points',
        ],
        'data_map' => [
            'label' => 'Data Map',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_data_map',
        ],

        // --- Carousels ---
        'dual_carousel' => [
            'label' => 'Dual Carousel',
            'group' => 'Carousels',
            'data'  => 'zotefoams_cl_data_dual_carousel',
        ],
        'split_carousel' => [
            'label' => 'Split Carousel',
            'group' => 'Carousels',
            'data'  => 'zotefoams_cl_data_split_carousel',
        ],
        'multi_item_carousel' => [
            'label'    => 'Multi Item Carousel (Manual)',
            'group'    => 'Carousels',
            'data'     => 'zotefoams_cl_data_multi_item_carousel',
            'variants' => [
                'Dark Variant' => 'zotefoams_cl_data_multi_item_carousel_variant',
            ],
        ],
        'calendar_carousel' => [
            'label' => 'Calendar Carousel',
            'group' => 'Carousels',
            'data'  => 'zotefoams_cl_data_calendar_carousel',
        ],
        'step_slider' => [
            'label' => 'Step Slider',
            'group' => 'Carousels',
            'data'  => 'zotefoams_cl_data_step_slider',
        ],

        // --- Navigation ---
        'panel_switcher' => [
            'label' => 'Panel Switcher',
            'group' => 'Tabs & Panels',
            'data'  => 'zotefoams_cl_data_panel_switcher',
        ],
        'tabbed_split' => [
            'label' => 'Tabbed Split',
            'group' => 'Tabs & Panels',
            'data'  => 'zotefoams_cl_data_tabbed_split',
        ],
        'show_hide' => [
            'label' => 'Show / Hide (Accordion)',
            'group' => 'Tabs & Panels',
            'data'  => 'zotefoams_cl_data_show_hide',
        ],

        // --- Data ---
        'news_feed' => [
            'label' => 'News Feed (Manual)',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_news_feed',
        ],
        'document_list' => [
            'label' => 'Document List (Manual)',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_document_list',
        ],
        'financial_documents_picker' => [
            'label' => 'Financial Documents Picker',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_financial_documents_picker',
        ],
        'markets_list' => [
            'label' => 'Markets List (Manual)',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_markets_list',
        ],
        'locations_map' => [
            'label' => 'Locations Map',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_locations_map',
        ],
        'interactive_image' => [
            'label' => 'Interactive Image',
            'group' => 'Data',
            'data'  => 'zotefoams_cl_data_interactive_image',
        ],

        // --- Misc ---
        'waste-hierarchy' => [
            'label' => 'Waste Hierarchy',
            'group' => 'Misc',
            'data'  => null, // No fields needed — static component
        ],
        'bir_widgets' => [
            'label' => 'BIR Widgets',
            'group' => 'Misc',
            'data'  => 'zotefoams_cl_data_bir_widgets',
        ],
        'show_hide_forms' => [
            'label' => 'Show / Hide Forms',
            'group' => 'Misc',
            'data'  => 'zotefoams_cl_data_show_hide_forms',
        ],
        'cta_picker' => [
            'label' => 'CTA Picker',
            'group' => 'Columns & Grids',
            'data'  => 'zotefoams_cl_data_cta_picker',
        ],
    ];
}

// ---------------------------------------------------------------------------
// Data callbacks
// ---------------------------------------------------------------------------

function zotefoams_cl_data_text_block()
{
    return [
        'text_block_overline' => 'Our Purpose',
        'text_block_text'     => '<p>Zotefoams is a world leader in cellular material technology. We design and manufacture lightweight foams for a wide range of markets and applications, from aerospace to sport.</p>',
    ];
}

function zotefoams_cl_data_split_text()
{
    return [
        'split_text_title' => '<strong>Pushing the boundaries</strong> of cellular material science',
        'split_text_text'  => '<p>Our unique manufacturing process uses pure nitrogen gas to expand our foams, resulting in products with finer, more uniform cell structures than conventionally made foams. This gives our products superior performance characteristics.</p><p>We serve industries around the globe, providing innovative solutions that meet the most demanding requirements.</p>',
    ];
}

function zotefoams_cl_data_columns_content()
{
    return [
        'columns_content_overline'   => 'About Zotefoams',
        'columns_content_intro'      => 'Our commitment to innovation drives everything we do',
        'columns_content_image'      => zotefoams_cl_image(600, 400, 'Content Image'),
        'columns_content_column_one' => '<h4>Research & Development</h4><p>Our dedicated R&D team works continuously to develop new materials and improve existing products. We invest significantly in research to maintain our position at the forefront of cellular material technology.</p>',
        'columns_content_column_two' => '<h4>Sustainability</h4><p>We are committed to sustainable practices throughout our operations. Our manufacturing processes are designed to minimise waste and energy consumption while producing products that contribute to a more sustainable future.</p>',
    ];
}

function zotefoams_cl_data_related_links_box()
{
    return [
        'related_links_box_title' => 'Related Resources',
        'related_links_box_links' => [
            ['related_links_box_link' => zotefoams_cl_link('Technical Data Sheets', '#')],
            ['related_links_box_link' => zotefoams_cl_link('Product Brochures', '#')],
            ['related_links_box_link' => zotefoams_cl_link('Contact Our Team', '#')],
        ],
    ];
}

function zotefoams_cl_data_image_left_right()
{
    return [
        'image_left_right_position'  => false,
        'image_left_right_image'     => zotefoams_cl_image(800, 600, 'Image Left/Right'),
        'image_left_right_heading'   => 'Innovative Solutions',
        'image_left_right_text'      => '<p>Zotefoams provides industry-leading foam solutions for a wide range of applications. Our products are designed to meet the highest standards of quality and performance.</p>',
        'image_left_right_icon_list' => [
            [
                'image_left_right_icon'      => zotefoams_cl_image(80, 80, 'Icon 1'),
                'image_left_right_icon_text' => '<p>Lightweight & durable construction</p>',
            ],
            [
                'image_left_right_icon'      => zotefoams_cl_image(80, 80, 'Icon 2'),
                'image_left_right_icon_text' => '<p>Environmentally responsible</p>',
            ],
        ],
    ];
}

function zotefoams_cl_data_split_video_one()
{
    return [
        'split_video_one_image'      => zotefoams_cl_image(800, 500, 'Video Thumbnail'),
        'split_video_one_video_url'  => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'split_video_one_title'      => 'Discover Our Process',
        'split_video_one_text'       => '<p>See how Zotefoams manufactures its world-class cellular materials using unique nitrogen expansion technology.</p>',
        'split_video_one_extra_text' => '<p>Our process uses pure nitrogen gas, resulting in foams with finer, more uniform cell structures.</p>',
        'split_video_one_link'       => zotefoams_cl_link('View All Videos', '#'),
        'split_video_one_variant'    => false,
    ];
}

function zotefoams_cl_data_split_video_one_variant()
{
    $data = zotefoams_cl_data_split_video_one();
    $data['split_video_one_variant'] = true;
    $data['split_video_one_title'] = 'Our Manufacturing Process';
    return $data;
}

function zotefoams_cl_data_split_video_two()
{
    return [
        'split_video_two_image'     => zotefoams_cl_image(800, 500, 'Video BG'),
        'split_video_two_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'split_video_two_title'     => 'Technology in Action',
        'split_video_two_text'      => '<p>Watch how our materials are used across industries to create lighter, safer, and more efficient products for the modern world.</p>',
    ];
}

function zotefoams_cl_data_highlight_panel()
{
    return [
        'highlight_panel_background_image'    => zotefoams_cl_image(1400, 600, 'Highlight BG'),
        'highlight_panel_lead_text'           => 'Leading the future of <strong>cellular material technology</strong>',
        'highlight_panel_sub_image'           => zotefoams_cl_image(120, 120, 'Badge'),
        'highlight_panel_sub_title'           => 'ISO 14001 Certified',
        'highlight_panel_sub_title_secondary' => 'Environmental Management',
    ];
}

function zotefoams_cl_data_text_banner_split()
{
    return [
        'text_banner_split_image' => zotefoams_cl_image(800, 600, 'Banner Image'),
        'text_banner_split_title' => 'About Zotefoams',
        'text_banner_split_text'  => '<p>World leader in cellular material technology, manufacturing lightweight foams for diverse markets and applications.</p>',
        'text_banner_split_link'  => zotefoams_cl_link('Explore Our Story', '#'),
    ];
}

function zotefoams_cl_data_interactive_image()
{
    return [
        'interactive_image_title'          => 'Product Cross-Section',
        'interactive_image_subtitle'       => 'Explore the layers',
        'interactive_image_output_numbers' => true,
        'interactive_image_light_theme'    => false,
        'interactive_image_bg'             => zotefoams_cl_image(1000, 600, 'Interactive BG'),
        'interactive_image_points'         => [
            ['point_content' => 'Outer Shell<br>Provides structural integrity', 'from_top' => '25', 'from_left' => '30'],
            ['point_content' => 'Foam Core<br>Lightweight insulation layer', 'from_top' => '50', 'from_left' => '55'],
            ['point_content' => 'Inner Lining<br>Smooth finish for comfort', 'from_top' => '70', 'from_left' => '75'],
        ],
    ];
}

function zotefoams_cl_data_icon_columns()
{
    return [
        'icon_columns_intro_overline' => 'Why Zotefoams',
        'icon_columns_intro'          => 'Key benefits of our cellular materials',
        'icon_columns_variant'        => false,
        'icon_columns_columns'        => [
            [
                'icon_columns_icon'  => zotefoams_cl_image(120, 120, 'Lightweight'),
                'icon_columns_title' => 'Lightweight',
                'icon_columns_text'  => '<p>Our foams are among the lightest available, reducing overall product weight significantly.</p>',
            ],
            [
                'icon_columns_icon'  => zotefoams_cl_image(120, 120, 'Durable'),
                'icon_columns_title' => 'Durable',
                'icon_columns_text'  => '<p>Exceptional resistance to chemicals, moisture and temperature extremes.</p>',
            ],
            [
                'icon_columns_icon'  => zotefoams_cl_image(120, 120, 'Sustainable'),
                'icon_columns_title' => 'Sustainable',
                'icon_columns_text'  => '<p>Manufactured using a unique nitrogen expansion process with minimal environmental impact.</p>',
            ],
        ],
    ];
}

function zotefoams_cl_data_icon_columns_variant()
{
    $data = zotefoams_cl_data_icon_columns();
    $data['icon_columns_variant'] = true;
    $data['icon_columns_intro_overline'] = 'Our Approach';
    $data['icon_columns_intro'] = 'Innovation at every stage';
    return $data;
}

function zotefoams_cl_data_box_columns()
{
    return [
        'box_columns_title'     => 'Explore Our Products',
        'box_columns_button'    => zotefoams_cl_link('View All', '#'),
        'box_columns_behaviour' => 'manual',
        'box_columns_parent_id' => 0,
        'box_columns_items'     => [
            [
                'box_columns_item_image'       => zotefoams_cl_image(400, 300, 'AZOTE'),
                'box_columns_item_title'       => 'AZOTE Polyolefin Foams',
                'box_columns_item_description' => '<p>High-performance cross-linked polyolefin foams.</p>',
                'box_columns_item_button'      => zotefoams_cl_link('Learn More', '#'),
            ],
            [
                'box_columns_item_image'       => zotefoams_cl_image(400, 300, 'ZOTEK'),
                'box_columns_item_title'       => 'ZOTEK Technical Foams',
                'box_columns_item_description' => '<p>Engineering foams for demanding applications.</p>',
                'box_columns_item_button'      => zotefoams_cl_link('Learn More', '#'),
            ],
            [
                'box_columns_item_image'       => zotefoams_cl_image(400, 300, 'T-FIT'),
                'box_columns_item_title'       => 'T-FIT Insulation',
                'box_columns_item_description' => '<p>Clean environment insulation solutions.</p>',
                'box_columns_item_button'      => zotefoams_cl_link('Learn More', '#'),
            ],
        ],
    ];
}

function zotefoams_cl_data_small_box_columns()
{
    return [
        'small_box_columns_title'  => 'Our Brands',
        'small_box_columns_button' => zotefoams_cl_link('See All Brands', '#'),
        'small_box_columns_items'  => [
            [
                'small_box_columns_item_image'       => zotefoams_cl_image(335, 335, 'Brand A'),
                'small_box_columns_item_title'       => 'AZOTE',
                'small_box_columns_item_description' => 'Cross-linked polyolefin foams',
                'small_box_columns_item_link'        => zotefoams_cl_link('Explore', '#'),
            ],
            [
                'small_box_columns_item_image'       => zotefoams_cl_image(335, 335, 'Brand B'),
                'small_box_columns_item_title'       => 'ZOTEK',
                'small_box_columns_item_description' => 'High-performance engineering foams',
                'small_box_columns_item_link'        => zotefoams_cl_link('Explore', '#'),
            ],
            [
                'small_box_columns_item_image'       => zotefoams_cl_image(335, 335, 'Brand C'),
                'small_box_columns_item_title'       => 'T-FIT',
                'small_box_columns_item_description' => 'Clean environment insulation',
                'small_box_columns_item_link'        => zotefoams_cl_link('Explore', '#'),
            ],
        ],
    ];
}

function zotefoams_cl_data_icons_grid()
{
    return [
        'icons_grid_overline' => 'Our Capabilities',
        'icons_grid_intro'    => 'What sets us apart from the competition',
        'icons_grid_items'    => [
            [
                'icon_image'        => zotefoams_cl_image(100, 100, 'R&D'),
                'title'             => 'Research & Development',
                'text'              => 'Continuous investment in new material science and product development.',
                'background_image'  => zotefoams_cl_image(400, 300, 'R&D BG'),
            ],
            [
                'icon_image'        => zotefoams_cl_image(100, 100, 'Quality'),
                'title'             => 'Quality Assurance',
                'text'              => 'Rigorous testing and quality control at every stage of production.',
                'background_image'  => zotefoams_cl_image(400, 300, 'QA BG'),
            ],
            [
                'icon_image'        => zotefoams_cl_image(100, 100, 'Global'),
                'title'             => 'Global Reach',
                'text'              => 'Serving customers across the world with local expertise and support.',
                'background_image'  => zotefoams_cl_image(400, 300, 'Global BG'),
            ],
            [
                'icon_image'        => zotefoams_cl_image(100, 100, 'Green'),
                'title'             => 'Sustainability',
                'text'              => 'Committed to reducing our environmental footprint across all operations.',
                'background_image'  => zotefoams_cl_image(400, 300, 'Green BG'),
            ],
        ],
    ];
}

function zotefoams_cl_data_data_points()
{
    return [
        'data_points_title' => 'Key Figures',
        'data_points_items' => [
            [
                'data_points_icon'   => zotefoams_cl_image(80, 80, 'Revenue'),
                'data_points_value'  => '89.2',
                'data_points_prefix' => '£',
                'data_points_suffix' => 'm',
                'data_points_label'  => 'Revenue',
            ],
            [
                'data_points_icon'   => zotefoams_cl_image(80, 80, 'Employees'),
                'data_points_value'  => '500',
                'data_points_prefix' => '',
                'data_points_suffix' => '+',
                'data_points_label'  => 'Employees Worldwide',
            ],
            [
                'data_points_icon'   => zotefoams_cl_image(80, 80, 'Countries'),
                'data_points_value'  => '40',
                'data_points_prefix' => '',
                'data_points_suffix' => '+',
                'data_points_label'  => 'Countries Served',
            ],
        ],
    ];
}

function zotefoams_cl_data_data_map()
{
    return [
        'data_map_bg'           => zotefoams_cl_image(1400, 800, 'Map BG'),
        'data_map_map_image'    => zotefoams_cl_image(600, 400, 'World Map'),
        'data_map_stat_1_value' => '3',
        'data_map_stat_1_text'  => 'Manufacturing Sites',
        'data_map_stat_2_value' => '40+',
        'data_map_stat_2_text'  => 'Countries Served',
        'data_map_stat_3_value' => '500+',
        'data_map_stat_3_text'  => 'Employees Worldwide',
    ];
}

function zotefoams_cl_data_dual_carousel()
{
    $slides = [];
    for ($i = 1; $i <= 3; $i++) {
        $slides[] = [
            'dual_carousel_category' => 'Category ' . $i,
            'dual_carousel_title'    => 'Slide Title ' . $i,
            'dual_carousel_image'    => zotefoams_cl_image(600, 400, "Slide {$i}"),
            'dual_carousel_text'     => '<p>Description for slide ' . $i . '. Our innovative foam solutions deliver outstanding performance.</p>',
            'dual_carousel_button'   => zotefoams_cl_link('Read More', '#'),
            'dual_carousel_bg_image' => zotefoams_cl_image(1200, 600, "BG {$i}"),
        ];
    }
    return [
        'dual_carousel_slides' => $slides,
    ];
}

function zotefoams_cl_data_split_carousel()
{
    $slides = [];
    for ($i = 1; $i <= 3; $i++) {
        $slides[] = [
            'split_carousel_category' => 'Innovation',
            'split_carousel_title'    => 'Product Feature ' . $i,
            'split_carousel_text'     => '<p>Discover our latest foam technology advancement number ' . $i . '.</p>',
            'split_carousel_button'   => zotefoams_cl_link('Explore', '#'),
            'split_carousel_image'    => zotefoams_cl_image(800, 600, "Split {$i}"),
        ];
    }
    return [
        'split_carousel_slides' => $slides,
    ];
}

function zotefoams_cl_data_multi_item_carousel()
{
    $slides = [];
    for ($i = 1; $i <= 5; $i++) {
        $slides[] = [
            'multi_item_carousel_slide_title'  => 'Product ' . $i,
            'multi_item_carousel_slide_text'   => '<p>High-performance cellular material for demanding applications.</p>',
            'multi_item_carousel_slide_button' => zotefoams_cl_link('View Details', '#'),
            'multi_item_carousel_slide_image'  => zotefoams_cl_image(400, 400, "Item {$i}"),
        ];
    }
    return [
        'multi_item_carousel_title'     => 'Our Products',
        'multi_item_carousel_behaviour' => 'manual',
        'multi_item_carousel_parent_id' => 0,
        'multi_item_carousel_slides'    => $slides,
        'multi_item_carousel_variant'   => false,
    ];
}

function zotefoams_cl_data_multi_item_carousel_variant()
{
    $data = zotefoams_cl_data_multi_item_carousel();
    $data['multi_item_carousel_variant'] = true;
    $data['multi_item_carousel_title'] = 'Featured Products';
    return $data;
}

function zotefoams_cl_data_calendar_carousel()
{
    return [
        'calendar_carousel_title'  => 'Upcoming Events',
        'calendar_carousel_note'   => 'Dates are subject to change.',
        'calendar_carousel_events' => [
            ['calendar_carousel_date' => '15', 'calendar_carousel_month_year' => 'Mar 2026', 'calendar_carousel_description' => 'Foam Expo Europe — Stuttgart, Germany'],
            ['calendar_carousel_date' => '22', 'calendar_carousel_month_year' => 'Apr 2026', 'calendar_carousel_description' => 'Composites Engineering Show — Birmingham, UK'],
            ['calendar_carousel_date' => '08', 'calendar_carousel_month_year' => 'Jun 2026', 'calendar_carousel_description' => 'IPACK-IMA — Milan, Italy'],
            ['calendar_carousel_date' => '14', 'calendar_carousel_month_year' => 'Sep 2026', 'calendar_carousel_description' => 'Aircraft Interiors Expo — Hamburg, Germany'],
        ],
    ];
}

function zotefoams_cl_data_step_slider()
{
    return [
        'step_slider_overline_title' => 'Our Process',
        'step_slider_slides'         => [
            [
                'step_slider_slide_image'    => zotefoams_cl_image(800, 500, 'Step 1'),
                'step_slider_slide_overline' => 'Step 01',
                'step_slider_slide_title'    => 'Raw Material Preparation',
                'step_slider_slide_text'     => '<p>High-quality polymer resins are carefully selected and prepared for the expansion process.</p>',
            ],
            [
                'step_slider_slide_image'    => zotefoams_cl_image(800, 500, 'Step 2'),
                'step_slider_slide_overline' => 'Step 02',
                'step_slider_slide_title'    => 'Nitrogen Saturation',
                'step_slider_slide_text'     => '<p>Blanks are placed in high-pressure autoclaves and saturated with pure nitrogen gas.</p>',
            ],
            [
                'step_slider_slide_image'    => zotefoams_cl_image(800, 500, 'Step 3'),
                'step_slider_slide_overline' => 'Step 03',
                'step_slider_slide_title'    => 'Expansion & Finishing',
                'step_slider_slide_text'     => '<p>The nitrogen-saturated blanks are expanded using heat, creating the final foam structure.</p>',
            ],
        ],
    ];
}

function zotefoams_cl_data_panel_switcher()
{
    return [
        'panel_switcher_overline' => 'Our product families',
        'panel_switcher_intro'    => 'Three world-class foam brands under one roof',
        'panel_switcher_panels'   => [
            [
                'panel_switcher_title' => 'AZOTE Foams',
                'panel_switcher_icon'  => zotefoams_cl_image(80, 80, 'AZOTE'),
                'panel_switcher_text'  => '<p>Cross-linked polyolefin foams manufactured using our unique high-pressure nitrogen process. AZOTE foams offer outstanding consistency, purity and performance.</p>',
            ],
            [
                'panel_switcher_title' => 'ZOTEK Foams',
                'panel_switcher_icon'  => zotefoams_cl_image(80, 80, 'ZOTEK'),
                'panel_switcher_text'  => '<p>High-performance engineering foams including ZOTEK F fluoropolymer and ZOTEK N nylon foams for the most demanding applications in aerospace, automotive and more.</p>',
            ],
            [
                'panel_switcher_title' => 'T-FIT Insulation',
                'panel_switcher_icon'  => zotefoams_cl_image(80, 80, 'T-FIT'),
                'panel_switcher_text'  => '<p>Clean environment insulation products designed for pharmaceutical, biotechnology and semiconductor manufacturing facilities.</p>',
            ],
        ],
    ];
}

function zotefoams_cl_data_tabbed_split()
{
    return [
        'tabbed_split_overline' => 'Applications',
        'tabbed_split_text'     => 'How our foams are used across industries',
        'tabbed_split_tabs'     => [
            [
                'tabbed_split_tab_title'    => 'Aerospace',
                'tabbed_split_tab_icon'     => zotefoams_cl_image(60, 60, 'Aero'),
                'tabbed_split_content_title' => 'Aviation & Space',
                'tabbed_split_content_text' => '<p>Our lightweight foams meet the stringent requirements of the aerospace industry, offering flame retardancy, low smoke and toxicity, and excellent mechanical properties.</p>',
                'tabbed_split_button'       => zotefoams_cl_link('View Aerospace Solutions', '#'),
                'tabbed_split_image'        => zotefoams_cl_image(600, 400, 'Aerospace'),
            ],
            [
                'tabbed_split_tab_title'    => 'Automotive',
                'tabbed_split_tab_icon'     => zotefoams_cl_image(60, 60, 'Auto'),
                'tabbed_split_content_title' => 'Vehicle Applications',
                'tabbed_split_content_text' => '<p>From interior comfort to structural components, our foams help automotive manufacturers reduce weight and improve safety.</p>',
                'tabbed_split_button'       => zotefoams_cl_link('View Automotive Solutions', '#'),
                'tabbed_split_image'        => zotefoams_cl_image(600, 400, 'Automotive'),
            ],
            [
                'tabbed_split_tab_title'    => 'Packaging',
                'tabbed_split_tab_icon'     => zotefoams_cl_image(60, 60, 'Pack'),
                'tabbed_split_content_title' => 'Protective Packaging',
                'tabbed_split_content_text' => '<p>Reliable protection for sensitive goods during transit. Our foams provide excellent shock absorption and surface protection.</p>',
                'tabbed_split_button'       => zotefoams_cl_link('View Packaging Solutions', '#'),
                'tabbed_split_image'        => zotefoams_cl_image(600, 400, 'Packaging'),
            ],
        ],
    ];
}

function zotefoams_cl_data_show_hide()
{
    return [
        'show_hide_title'  => 'Frequently Asked Questions',
        'show_hide_button' => zotefoams_cl_link('Contact Us', '#'),
        'unique_id'        => 'cl-faq',
        'show_hide_items'  => [
            [
                'show_hide_question' => 'What makes Zotefoams unique?',
                'show_hide_answer'   => '<p>Our patented nitrogen expansion process creates foams with finer, more uniform cell structures than conventionally made foams, resulting in superior performance characteristics.</p>',
            ],
            [
                'show_hide_question' => 'What industries do you serve?',
                'show_hide_answer'   => '<p>We serve a wide range of industries including aerospace, automotive, construction, healthcare, packaging, sport &amp; leisure, and more.</p>',
            ],
            [
                'show_hide_question' => 'Where are your products manufactured?',
                'show_hide_answer'   => '<p>We have manufacturing facilities in the UK (Croydon), USA (Walton, Kentucky) and Poland (Brzeg). Our products are distributed worldwide.</p>',
            ],
        ],
    ];
}

function zotefoams_cl_data_news_feed()
{
    return [
        'news_feed_title'     => 'Latest News',
        'news_feed_button'    => zotefoams_cl_link('View All News', '#'),
        'news_feed_behaviour' => 'manual',
        'news_feed_post_ids'  => [],
        'news_feed_items'     => [
            [
                'news_feed_image'    => zotefoams_cl_image(400, 300, 'News 1'),
                'news_feed_category' => 'Company News',
                'news_feed_title'    => 'Zotefoams Reports Strong Q4 Results',
                'news_feed_link'     => zotefoams_cl_link('Read More', '#'),
            ],
            [
                'news_feed_image'    => zotefoams_cl_image(400, 300, 'News 2'),
                'news_feed_category' => 'Product Launch',
                'news_feed_title'    => 'New ZOTEK F42 Foam for Aerospace Applications',
                'news_feed_link'     => zotefoams_cl_link('Read More', '#'),
            ],
            [
                'news_feed_image'    => zotefoams_cl_image(400, 300, 'News 3'),
                'news_feed_category' => 'Sustainability',
                'news_feed_title'    => 'Carbon Reduction Targets Achieved Ahead of Schedule',
                'news_feed_link'     => zotefoams_cl_link('Read More', '#'),
            ],
        ],
    ];
}

function zotefoams_cl_data_document_list()
{
    return [
        'document_list_title'      => 'Technical Documents',
        'document_list_button'     => zotefoams_cl_link('Browse All', '#'),
        'document_list_behaviour'  => 'manual',
        'document_list_pick_hub'   => 0,
        'document_list_pick_documents' => [],
        'document_list_pick_count' => 0,
        'document_list_documents'  => [
            [
                'document_list_category'  => '',
                'document_list_link'      => zotefoams_cl_link('AZOTE Product Data Sheet', '#'),
                'document_list_doc_title' => 'AZOTE Product Data Sheet',
            ],
            [
                'document_list_category'  => '',
                'document_list_link'      => zotefoams_cl_link('ZOTEK F Technical Guide', '#'),
                'document_list_doc_title' => 'ZOTEK F Technical Guide',
            ],
            [
                'document_list_category'  => '',
                'document_list_link'      => zotefoams_cl_link('T-FIT Installation Manual', '#'),
                'document_list_doc_title' => 'T-FIT Installation Manual',
            ],
        ],
    ];
}

function zotefoams_cl_data_financial_documents_picker()
{
    return [
        'financial_documents_picker_title' => 'Financial Reports',
        'financial_documents_picker_text'  => '<p>Download our latest annual reports and financial statements.</p>',
        'documents_by_year'                => [
            [
                'year'      => '2025',
                'documents' => [
                    ['document_link' => zotefoams_cl_link('Annual Report 2025', '#')],
                    ['document_link' => zotefoams_cl_link('Half-Year Results 2025', '#')],
                ],
            ],
            [
                'year'      => '2024',
                'documents' => [
                    ['document_link' => zotefoams_cl_link('Annual Report 2024', '#')],
                    ['document_link' => zotefoams_cl_link('Half-Year Results 2024', '#')],
                ],
            ],
        ],
    ];
}

function zotefoams_cl_data_markets_list()
{
    return [
        'markets_list_title'     => 'Markets We Serve',
        'markets_list_button'    => zotefoams_cl_link('View All Markets', '#'),
        'markets_list_behaviour' => 'manual',
        'markets_list_markets'   => [
            [
                'markets_list_name'   => 'Aerospace',
                'markets_list_brands' => [
                    ['market_brand_name' => 'ZOTEK'],
                    ['market_brand_name' => 'AZOTE'],
                ],
                'markets_list_link'   => zotefoams_cl_link('Explore', '#'),
                'markets_list_image'  => zotefoams_cl_image(600, 400, 'Aerospace'),
            ],
            [
                'markets_list_name'   => 'Automotive',
                'markets_list_brands' => [
                    ['market_brand_name' => 'AZOTE'],
                ],
                'markets_list_link'   => zotefoams_cl_link('Explore', '#'),
                'markets_list_image'  => zotefoams_cl_image(600, 400, 'Automotive'),
            ],
            [
                'markets_list_name'   => 'Construction',
                'markets_list_brands' => [
                    ['market_brand_name' => 'T-FIT'],
                    ['market_brand_name' => 'AZOTE'],
                ],
                'markets_list_link'   => zotefoams_cl_link('Explore', '#'),
                'markets_list_image'  => zotefoams_cl_image(600, 400, 'Construction'),
            ],
        ],
        'markets_list_ids' => [],
    ];
}

function zotefoams_cl_data_locations_map()
{
    return [
        'locations_map_title'     => 'Our Locations',
        'locations_map_subtitle'  => 'Around the world',
        'locations_map_image'     => zotefoams_cl_image(1000, 500, 'World Map'),
        'locations_map_locations' => [
            ['locations_map_description' => '<strong>Croydon, UK</strong><br>Global headquarters & manufacturing', 'from_top' => '35', 'from_left' => '47'],
            ['locations_map_description' => '<strong>Walton, KY, USA</strong><br>North American manufacturing', 'from_top' => '40', 'from_left' => '25'],
            ['locations_map_description' => '<strong>Brzeg, Poland</strong><br>European manufacturing', 'from_top' => '32', 'from_left' => '52'],
        ],
    ];
}

function zotefoams_cl_data_bir_widgets()
{
    return [
        'brighter_ir_widget' => 'share-price-chart',
    ];
}

function zotefoams_cl_data_show_hide_forms()
{
    return [
        'show_hide_forms_title' => 'Contact Forms',
        'show_hide_forms_intro' => '<p>Select a form below to get in touch with the relevant team.</p>',
        'show_hide_forms_items' => [],
    ];
}

function zotefoams_cl_data_cta_picker()
{
    return [
        'cta_picker_title'        => 'Featured Pages',
        'cta_picker_link'         => zotefoams_cl_link('View All', '#'),
        'cta_picker_content_type' => 'page',
        'cta_picker_show_latest'  => true,
        'cta_picker_count'        => 3,
        'cta_picker_layout'       => 'Grid',
    ];
}
