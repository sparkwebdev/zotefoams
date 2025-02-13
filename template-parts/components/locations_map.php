<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('locations_map_title');
$subtitle = get_sub_field('locations_map_subtitle');
$locations = get_sub_field('locations_map_locations');
$map_image = get_sub_field('locations_map_image');

// Extract 'large' size image URL from Image Array, with fallback
$map_image_url = $map_image ? $map_image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
?>

<div class="locations-map half-half padding-t-b-100">
    <div class="cont-m">
        <div class="map-intro margin-b-40">
            <?php if ($title): ?>
                <p class="fw-semibold fs-600 white-text"><?php echo esc_html($title); ?></p>
            <?php endif; ?>
            <?php if ($subtitle): ?>
                <p class="fw-semibold fs-600 blue-text"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="map-container">
            <?php if ($locations): ?>
                <?php foreach ($locations as $location): ?>
                    <?php 
                        $class = sanitize_title($location['locations_map_class']);
                        $description = $location['locations_map_description'];
                    ?>
                    <div class="location <?php echo esc_attr($class); ?>" onclick="locationClicked(this)">
                        <?php if ($description): ?>
                            <div class="popup">
                                <p><?php echo wp_kses_post($description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <img class="map" src="<?php echo esc_url($map_image_url); ?>" />
        </div>
    </div>
</div>


<script type="text/javascript">

	function locationClicked(sender) {
		console.log(sender);
	}

</script>


<style type="text/css">

	.locations-map {
		background-color: #041B40;
	}
	
	.map-intro {max-width:700px}

		.locations-map .title-container {
			position: relative;
			margin-left: auto;
			margin-right: auto;
		}

			.locations-map .title-container h2 {
				color: #fff;
				font-size: 1.5em;
				font-weight: 500;
			}

			.locations-map .title-container h3 {
				color: #3B82F6;
				font-size: 1.5em;
				font-weight: 600;
				max-width: 500px;
				line-height: 32px;
			}

		.locations-map .map-container {
			position: relative;
			margin-left: auto;
			margin-right: auto;
		}

			.locations-map .map-container .map {
				max-width: 100%;
				display: block;
				width: 100%;
				filter: brightness(0) saturate(100%) invert(26%) sepia(23%) saturate(954%) hue-rotate(179deg) brightness(90%) contrast(87%);
			}

			.locations-map .map-container .location {
				position: absolute;
				width: 0.4vw;
				height: 0.4vw;
				max-width: 8px;
				max-height: 8px;
				border-radius: 50%;
				background-color: #fff;
				display: -webkit-box;
				display: flex;
				-webkit-box-pack: center; 
				justify-content: center;
				-webkit-box-align: center;
				align-items: center;
				z-index: 1;
			}

			.locations-map .map-container .location::after {
				content: '';
				width: 3vw;
				height: 3vw;
				border-radius: 50%;
				border: solid 1px #fff;
				position: absolute;
				max-width: 60px;
				max-height: 60px;
				opacity: 0.5;
			}

			.locations-map .map-container .location:hover::after {
				border: solid 10px #fff;
			}

			.locations-map .map-container .location::after:hover {
				border: solid 10px #fff;
			}

				.locations-map .map-container .location .popup{
					display: none;
					position: absolute;
					top: 20px;
					left: calc(50% - 110px);
					width: 220px;
					padding: 10px 20px;
					border: solid 1px #ffffff55;
					backdrop-filter: blur(10px);
					color: #fff;
					background: #ffffff11;
				}

				.locations-map .map-container .location.kentucky {
					top: 34.5%;
					left: 19.5%;
				}

				.locations-map .map-container .location.massachusetts {
					top: 32%;
					left: 22%;
				}

				.locations-map .map-container .location.croydon {
					top: 43%;
					left: 46.5%;
				}

				.locations-map .map-container .location.europe1 {
					top: 43%;
					left: 49.5%;
				}

				.locations-map .map-container .location.europe2 {
					top: 44%;
					left: 52%;
				}

				.locations-map .map-container .location.india {
					top: 60%;
					left: 66%;
				}

				.locations-map .map-container .location.asia1 {
					top: 51%;
					left: 76.5%;
				}

				.locations-map .map-container .location.asia2 {
					top: 54%;
					left: 77%;
				}

				.locations-map .map-container .location.asia3 {
					top: 50%;
					left: 78.5%;
				}
</style>