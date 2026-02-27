<?php
// Get field data using safe helper functions
$title = get_sub_field('related_links_box_title'); // Keep HTML intact
$links = zotefoams_get_sub_field_safe('related_links_box_links', [], 'array');

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'related-links-box',
    'theme'     => 'none',
    'spacing'   => 'margin-t-0 margin-b-0 padding-t-b-100',
    'container' => '',
]);
?>

<aside class="<?php echo $wrapper_classes; ?>">
	<div class="cont-xs">
		<?php if ( ! empty( $title ) ) : ?>
			<h3 class="related-links-box__title"><?php echo wp_kses_post( $title ); ?></h3>
		<?php endif; ?>

		<?php if ( $links ) : ?>
			<nav class="related-links-box__links">
				<ul>
					<?php foreach ( $links as $link ) : 
						$link_data = $link['related_links_box_link'] ?? null;

						if ( $link_data ) :
							$link_url    = $link_data['url'] ?? '';
							$link_title  = $link_data['title'] ?? '';
							$link_target = $link_data['target'] ?? '_self';
							$is_file     = Zotefoams_Image_Helper::is_file_url($link_url);
							$link_class  = $is_file ? 'related-links-box__link-download' : 'related-links-box__link-arrow';
					?>
						<li>
							<a 
								class="related-links-box__link <?php echo esc_attr( $link_class ); ?>"
								href="<?php echo esc_url( $link_url ); ?>"
								target="<?php echo esc_attr( $link_target ); ?>">
								<?php echo esc_html( $link_title ); ?>
							</a>
						</li>
					<?php endif; endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
</aside>
