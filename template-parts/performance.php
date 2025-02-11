<div class="performance half-half cont-m padding-t-b-70">
	<h3>Our 2024 Performance Overview</h3>
	<div class="performance-items">
		<div class="spacer"></div>
		<div class="performance-item">
			<img class="" src="<?php echo get_template_directory_uri(); ?>/images/icon-17.svg" />
			<p class="animate__animated animate__delay-1s value" data-to=127 data-prefix="£" data-suffix="m">0</p>
			<label class="sub-title">Group Revenue</label>
		</div>
		<div class="performance-item">
			<img class="" src="<?php echo get_template_directory_uri(); ?>/images/icon-18.svg" />
			<p class="animate__animated animate__delay-1s value" data-to=30 data-suffix="%">0</p>
			<label class="sub-title">Sustainability Growth</label>
		</div>
		<div class="performance-item">
			<img class="" src="<?php echo get_template_directory_uri(); ?>/images/icon-19.svg" />
			<p class="animate__animated animate__delay-1s value" data-to=15.1 data-decimals=1 data-prefix="£" data-suffix="m">0</p>
			<label class="sub-title">Operating Profit</label>
		</div>
		<div class="performance-item">
			<img class="" src="<?php echo get_template_directory_uri(); ?>/images/icon-20.svg" />
			<p class="animate__animated animate__delay-1s value" data-decimals=1 data-to=10.3 data-suffix="%">0</p>
			<label class="sub-title">Return On Capital Employed</label>
		</div>
		<div class="spacer"></div>
	</div>
</div>


<script type="text/javascript">

	// Performance
	document.addEventListener('DOMContentLoaded', function () {		
		
		function animateValue(obj, start, end, duration, prefix, suffix, decimals) {
  			let startTimestamp = null;
  			const step = (timestamp) => {
    			if (!startTimestamp) startTimestamp = timestamp;
    			const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    			obj.innerHTML = (prefix ?? '') + (progress * (end - start) + start).toFixed(decimals) + (suffix ?? '');
    			if (progress < 1) {
      				window.requestAnimationFrame(step);
    			}
  			};
  			window.requestAnimationFrame(step);
		}

		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {

				const values = entry.target.getElementsByClassName('value');

				for (let i = 0; i < values.length; i++) {

					if (entry.isIntersecting) {
						
						const prefix = values[i].dataset.prefix ?? '';
						const suffix = values[i].dataset.suffix ?? '';
						const duration = values[i].dataset.duration ?? 1100;
						const decimals = values[i].dataset.decimals ?? 0;
						const to = values[i].dataset.to ?? 0;

						values[i].classList.add('animate__pulse');
						animateValue(values[i], 0, to, duration, prefix, suffix, decimals);
					}
					else {
						values[i].classList.remove('animate__pulse');
					}
				}
			});
		});
		observer.observe(document.querySelector('.performance'));
	});
</script>


<style type="text/css">
	/* Performance
	--------------------------------------------- */

	.performance {
		background-color: #F8F8F8;
	}

		.performance h3 {
			text-align: center;
			font-size: 1.5em;
			font-weight: 600;
			margin-bottom: 40px;
		}

		.performance .performance-items {
			display: flex;
		}

			.performance .performance-items .spacer {
				flex: 1 1 0;
			}

			.performance .performance-items .performance-item {
				flex: 2 1 0;
				text-align: center;
			}

				.performance .performance-items .performance-item img {
					width: 24px;
				}

				.performance .performance-items .performance-item .value {
					font-size: 3em;
					font-weight: bold;
					display: block;
				}

				.performance .performance-items .performance-item .sub-title {
					font-size: 0.8em;
					color: #707070;
					display: block;
					margin-top: -10px;
				}
</style>