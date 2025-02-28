<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('data_points_title');
$items = get_sub_field('data_points_items');

// Function to determine the number of decimal places
function getDecimalPlaces($number) {
		if (strpos($number, '.') !== false) {
				return strlen(substr(strrchr($number, "."), 1)); // Count decimals after the dot
		}
		return 0; // No decimal places if it's a whole number
}
?>

<div class="data-points half-half light-grey-bg padding-t-b-70 theme-light">
    
    <?php if ($title): ?>
        <h3><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    
    <div class="data-points-items cont-m">
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <?php 
                    $icon = $item['data_points_icon'];
                    $value = $item['data_points_value'];
                    $prefix = $item['data_points_prefix'];
                    $suffix = $item['data_points_suffix'];
                    $label = $item['data_points_label'];

                    // Default values for data attributes
                    $data_prefix = !empty($prefix) ? "data-prefix='{$prefix}'" : '';
                    $data_suffix = !empty($suffix) ? "data-suffix='{$suffix}'" : '';
                    $value = (string) $value; 
                    $data_decimals = !empty($value) ? "data-decimals='" . getDecimalPlaces($value) . "'" : '';
                    $data_to = !empty($value) ? "data-to='{$value}'" : '';

                ?>
                <div class="data-points-item">
                    <?php if ($icon): ?>
                        <img class="" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($label); ?>" />
                    <?php endif; ?>
                    <p class="animate__animated animate__delay-1s value" <?php echo $data_to . ' ' . $data_prefix . ' ' . $data_suffix . ' ' . $data_decimals; ?>>0</p>
                    <?php if ($label): ?>
                        <label class="sub-title"><?php echo esc_html($label); ?></label>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



<script type="text/javascript">

	// Performance optimized animation for counting up numbers
	document.addEventListener('DOMContentLoaded', function () {		
		
		function animateValue(obj, start, end, duration, prefix, suffix, decimals) {
			let startTimestamp = null;
			const step = (timestamp) => {
				if (!startTimestamp) startTimestamp = timestamp;
				const progress = Math.min((timestamp - startTimestamp) / duration, 1);
				let value = (progress * (end - start) + start);
				
				// Format value with the correct decimals and remove trailing zeros
				let formattedValue = value.toFixed(decimals).replace(/\.?0+$/, '');
				
				// Apply the formatted value
				obj.innerHTML = (prefix ?? '') + formattedValue + (suffix ?? '');
				
				if (progress < 1) {
					window.requestAnimationFrame(step);
				}
			};
			window.requestAnimationFrame(step);
		}
	
		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const values = entry.target.getElementsByClassName('value');
					
					for (let i = 0; i < values.length; i++) {
						// Prevent re-animation
						if (values[i].dataset.animated) continue;
	
						const prefix = values[i].dataset.prefix ?? '';
						const suffix = values[i].dataset.suffix ?? '';
						const duration = parseInt(values[i].dataset.duration) || 1100;
						const decimals = parseInt(values[i].dataset.decimals) || 0;
						const to = parseFloat(values[i].dataset.to) || 0;
	
						values[i].classList.add('animate__pulse');
						animateValue(values[i], 0, to, duration, prefix, suffix, decimals);
						
						// Mark as animated
						values[i].dataset.animated = "true";
					}
				} else {
					// Allow re-animation if needed by removing flag
					entry.target.querySelectorAll('.value').forEach(el => el.removeAttribute('data-animated'));
				}
			});
		}, { threshold: 0.5 }); // Adjust threshold to trigger animations better
	
		const target = document.querySelector('.data-points');
		if (target) {
			observer.observe(target);
		}
	});
	
</script>


<style type="text/css">
	/* 17 Data points
	--------------------------------------------- */

	.data-points h3 {
		text-align: center;
		font-size: 1.5em;
		font-weight: 600;
		margin-bottom: 40px;
	}

	.data-points .data-points-items {
		display: flex;
	}

	.data-points .data-points-items .spacer {
		flex: 1 1 0;
	}

	.data-points .data-points-items .data-points-item {
		flex: 2 1 0;
		text-align: center;
	}

	.data-points .data-points-items .data-points-item img {
		width: 24px;
	}

	.data-points .data-points-items .data-points-item .value {
		font-size: 3em;
		font-weight: bold;
		display: block;
	}

	.data-points .data-points-items .data-points-item .sub-title {
		font-size: 0.8em;
		color: #707070;
		display: block;
		margin-top: -10px;
	}
</style>