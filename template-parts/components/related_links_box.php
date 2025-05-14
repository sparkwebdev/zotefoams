<?php
$title = get_sub_field( 'related_links_box_title' );
$links = get_sub_field( 'related_links_box_links' );
?>

<aside class="cont-xs related-links-box padding-t-70 margin-b-100 theme-none">
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
						$is_file     = $link_url && wp_check_filetype( $link_url )['ext'];
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
</aside>
