<?php
$title = get_sub_field('related_links_box_title');
$links = get_sub_field('related_links_box_links');
?>

<aside class="cont-xs related-links-box margin-t-70 margin-b-70 theme-none">
    <?php if ( !empty( $title ) ) : ?>
        <h3 class="related-links-box__title"><?php echo wp_kses_post( $title ); ?></h3>
    <?php endif; ?>

    <?php if ( $links ) : ?>
        <nav class="related-links-box__links">
            <ul>
                <?php foreach ( $links as $link ) : ?>
                    <?php 
                    if( $link['related_links_box_link'] ):
                        $link_url = $link['related_links_box_link']['url'];
                        $link_title = $link['related_links_box_link']['title'];
                        $link_target = $link['related_links_box_link']['target'] ? $link['related_links_box_link']['target'] : '_self'; 
                        $is_file = wp_check_filetype($link_url)['ext'] ? true : false;
                    ?>
                        <li>
                            <a class="related-links-box__link <?php echo $is_file ? 'related-links-box__link-download' : 'related-links-box__link-arrow'; ?>" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                                <?php echo esc_html( $link_title ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</aside>
