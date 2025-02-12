<?php
/**
 * quote-box Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$quote             = !empty(get_field( 'quote' )) ? get_field( 'quote' ) : 'Your quote here...';
$author            = get_field( 'author' );
$author_role       = get_field( 'role' );
$image             = get_field( 'image' );
$quote_attribution = '';

if ( $author ) {
    $quote_attribution .= '<footer class="quote-box__attribution">';
    $quote_attribution .= '<cite class="quote-box__author">' . $author . '</cite>';

    if ( $author_role ) {
        $quote_attribution .= '<span class="quote-box__role">' . $author_role . '</span>';
    }

    $quote_attribution .= '</footer><!-- .quote-box__attribution -->';
}

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'quote-box';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}

?>

<aside <?php echo esc_attr( $anchor ); ?>class="<?php echo esc_attr( $class_name ); ?>">
    <?php if ( $image ) : ?>
        <div class="quote-box__col">
            <figure class="quote-box__image">
                <?php echo wp_get_attachment_image( $image['ID'], 'full', '', array( 'class' => 'quote-box__img' ) ); ?>
            </figure>
        </div>
    <?php endif; ?>
    <div class="quote-box__col">
        <blockquote class="quote-box__blockquote">
            <?php echo esc_html( $quote ); ?>

            <?php if ( !empty( $quote_attribution ) ) : ?>
                <?php echo wp_kses_post( $quote_attribution ); ?>
            <?php endif; ?>
        </blockquote>
    </div>
</aside>