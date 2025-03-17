<?php
/**
 * highlight-box Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$title            = !empty(get_field( 'title' )) ? get_field( 'title' ) : 'Your title here...';
$copy             = !empty(get_field( 'copy' )) ? get_field( 'copy' ) : 'Your copy here...';
$link             = get_field( 'link' );
$image            = get_field( 'image' );

if ( $title ) {
    $title = '<h3 class="highlight-box__title">' . $title . '</h3>';
}
if ( $copy ) {
    $copy = '<p class="highlight-box__copy">' . $copy . '</p>';
}

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'highlight-box';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}


?>

<aside <?php echo esc_attr( $anchor ); ?>class="<?php echo esc_attr( $class_name ); ?>">
    <div class="highlight-box__col">
        <div class="highlight-box__content">
            <?php if ( !empty( $title ) ) : ?>
                <?php echo wp_kses_post( $title ); ?>
            <?php endif; ?>
            <?php if ( !empty( $copy ) ) : ?>
                <?php echo wp_kses_post( $copy ); ?>
            <?php endif; ?>
            <?php if ( !empty( $link['url'] ) ) : 
                $file_info = wp_check_filetype($link['url']);
                $is_file = !empty($file_info['ext']);
            ?>
                <p class="margin-t-30"><a 
                    href="<?php echo esc_url( $link['url'] ); ?>" 
                    <?php echo !empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '"' : ''; ?> 
                    class="highlight-box__link <?php echo $is_file ?? 'highlight-box__link-download'; ?> ">
                    <?php echo !empty( $link['title'] ) ? esc_html( $link['title'] ) : 'Read more'; ?>
				</a></p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ( $image ) : ?>
        <div class="highlight-box__col">
            <figure class="highlight-box__image">
                <?php echo wp_get_attachment_image( $image['ID'], 'medium', '', array( 'class' => 'highlight-box__img' ) ); ?>
            </figure>
        </div>
    <?php endif; ?>
</aside>