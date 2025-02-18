
	<div class="data-map padding-t-b-100" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/data-map-bg.jpg)">
		<div class="cont-m">
			<div class="stats">
				<div class="stat stat-1">
					<img src="<?php echo get_template_directory_uri(); ?>/images/data-map.png)" />
					<p class="fs-700 fw-semibold margin-b-10">120</p>
					<p class="fs-200 fw-semibold">Graduate positions in 9 global locations</p>
				</div>
				<div class="stat stat-2">
					<p class="fs-700 fw-semibold margin-b-10">£15.1m</p>
					<p class="fs-200 fw-semibold">Operating profit</p>
				</div>
				<div class="stat stat-3">
					<p class="fs-700 fw-semibold margin-b-10">75%</p>
					<p class="fs-200 fw-semibold">Employee retention</p>
				</div>
			</div>
		</div>
	</div>

	<style type="text/css">
		.stat {
			background:rgba(255,255,255,0.9);
			padding:30px
		}
		.stats {
			display:flex;
			width:50%;
			gap:20px;
			flex-wrap:wrap
		}
		.stat-1 {
			width:100%
		}
		.stat-2, .stat-3 {
			width:calc(50% - 10px)
		}
	</style>