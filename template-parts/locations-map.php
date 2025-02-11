<div class="locations-map half-half cont-m padding-t-b-30">
	<div class="title-container">
		<h2>Our Locations</h2>
		<h3>An international company with key operations across the world.</h3>
	</div>
	<div class="map-container">
  		<div class="location kentucky" onclick="locationClicked(this)">
			<div class="popup">
				<p>
					Zotefoams Inc.
					55 Precision Drive
					Walton
					KY, 41094
					USA
					Tel:
					+1 859 371 4025
					FREE: (800) 362-8358 (US Only)
					Fax: +1 859 371 4734
					Email:
					hello@zotefoams.com
				</p>
			</div>
		</div>
  		<div class="location massachusetts" onclick="locationClicked(this)"></div>
  		<div class="location croydon"></div>
  		<div class="location europe1"></div>
		<div class="location europe2"></div>
		<div class="location india"></div>
		<div class="location asia1"></div>
		<div class="location asia2"></div>
		<div class="location asia3"></div>
		<img class="map" src="<?php echo get_template_directory_uri(); ?>/images/map.svg" />
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

		.locations-map .title-container {
			position: relative;
			margin-left: auto;
			margin-right: auto;
			max-width: 1024px;
			margin-bottom: 40px;
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
			max-width: 1024px;
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