<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : 
			$title = single_cat_title('', false);
			$cat_more_link_label = zotefoams_map_cat_label($title);
			$layout = ($title == 'Case Studies' || $title == 'Videos') ? "grid" : "list";
		?>

		<header class="text-block cont-m margin-t-70 margin-b-100">
			<h1 class="uppercase grey-text fs-800 fw-extrabold"><?php echo get_the_title(get_option('page_for_posts', true)); ?></h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold"><?php echo ($title == "News" ? 'Latest ' : '') . $title; ?></h2>
		</header>
		
		<hr class="separator" />

		<section class="cont-m margin-t-70">
			<div class="articles articles--<?php echo $layout; ?> margin-b-70">
				<?php
					while ( have_posts() ) :
						the_post();
						$cat_more_link = esc_url( get_the_permalink());
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), ($layout == "list") ? 'thumbnail-square' : 'thumbnail');
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/images/placeholder-' . (($layout == "list") ? 'thumbnail-square' : 'thumbnail') . '.png';
						}
						if ($layout == "list") { ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php
									echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
								?>
								<div class="articles__content">
									<?php
										echo '<div class="margin-b-20 grey-text">';
										zotefoams_posted_on();
										echo '</div>';
										the_title( '<h3 class="fs-400 fw-semibold margin-b-20">', '</h3>' );
										echo '<div class="articles__footer">';
											if (get_the_excerpt()) {
												the_excerpt();
											}
											echo '<p class="articles__cta"><a href="' . $cat_more_link . '" class="btn black outline">' . $cat_more_link_label . '</a></p>';
										echo '</div>';
									?>
								</div>
							</article><!-- #post-<?php the_ID(); ?> -->
						<?php } else { ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class($title == 'Case Studies' ? 'light-grey-bg' : ''); ?>>
								<?php
									if ($title == 'Videos') {
										$first_video_url = get_first_youtube_url( get_the_ID() );
										if ( $first_video_url ) :
											$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(),  'thumbnail');
											if (!$thumbnail_url) {
												$youtube_cover_image = youtube_cover_image( $first_video_url );
											}
											 ?>
											<div class="articles__block-embed-youtube" style="background-image:url(<?php echo $youtube_cover_image; ?>)">
												<a href="<?php echo $first_video_url; ?>" class="video-link open-video-overlay" rel="noopener noreferrer">
													<img src="/wp-content/themes/zotefoams/images/youtube-play.svg" />
												</a>
											</div>
										<?php else :
											echo '<img src="' . get_template_directory_uri() . '/images/placeholder-thumbnail.png' . '" alt="" class="margin-b-20">';
									endif;
									}
								?>
								<div class="articles__content">
								<?php
									the_title( '<h3 class="fs-400 fw-semibold margin-b-20">', '</h3>' );

									if (get_the_excerpt()) {
										echo '<div class="margin-b-20 grey-text">';
										the_excerpt();
										echo '</div>';
									}
									if ($title == 'Videos') {
										if (isset($first_video_url)) {
											echo '<p class="articles__cta margin-b-40"><a href="' . $first_video_url . '" class="open-video-overlay hl arrow" rel="noopener noreferrer">' . $cat_more_link_label . '</a></p>';
										} else {
											echo '<p class="articles__cta margin-b-40">No video found.</p>';
										}
									} else {
										echo '<p class="articles__cta"><a href="' . $cat_more_link . '" class="hl arrow">' . $cat_more_link_label . '</a></p>';
									}
								?>
								</div>
								<?php
								if ($title == 'Case Studies') {
									echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
								}
								?>
							</article><!-- #post-<?php the_ID(); ?> -->
							
						<?php } ?>
				
				<?php
				endwhile; 
					echo '<div class="pagination cont-m margin-t-70 margin-b-70">';
					get_template_part( 'template-parts/pagination' );
					echo '</div>';
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
		</section>
	</main>

	<?php if ($title == 'Videos') { ?>
	<!-- Video Overlay Structure -->
	<div id="video-overlay" style="display:none;">
		<div id="overlay-content">
			<button id="close-video">Close</button>
			<iframe id="video-iframe" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
	<?php } ?>

<?php
get_footer();
