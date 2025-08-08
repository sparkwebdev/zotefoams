<?php
// Get field data using safe helper functions
$title              = zotefoams_get_sub_field_safe('financial_documents_picker_title', '', 'string');
$text               = get_sub_field('financial_documents_picker_text'); // Keep HTML intact
$documents_by_year  = zotefoams_get_sub_field_safe('documents_by_year', [], 'array');
$instance_id        = uniqid('docs_'); // Unique ID for JS bindings
$has_intro_content  = !empty($text);
$container_class    = $has_intro_content ? 'light-grey-bg padding-50 theme-light' : 'padding-b-100 theme-none';

// Generate classes to match original structure exactly
$wrapper_classes = 'financial-documents-picker cont-m ' . $container_class;
?>

<?php if ($documents_by_year) : ?>
	<div class="<?php echo esc_attr($wrapper_classes); ?>">
		<div>
			<?php if ($has_intro_content) : ?>
				<div class="financial-documents-intro">
					<?php if ($title) : ?>
						<h3 class="fw-semibold margin-b-20 fs-400"><?php echo esc_html($title); ?></h3>
					<?php endif; ?>
					<?php echo wp_kses_post($text); ?>
				</div>
			<?php else : ?>
				<?php if ($title) : ?>
					<h3 class="fw-semibold margin-b-20 fs-400"><?php echo esc_html($title); ?></h3>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div>
			<div class="financial-documents">
				<select class="yearSelect" id="<?php echo esc_attr($instance_id); ?>_yearSelect">
					<?php foreach ($documents_by_year as $index => $year_data) : ?>
						<option value="<?php echo esc_attr($year_data['year']); ?>" <?php selected($index, 0); ?>>
							<?php echo esc_html($year_data['year']); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div class="documents-container" id="<?php echo esc_attr($instance_id); ?>-documents-container">
					<?php foreach ($documents_by_year as $index => $year_data) :
						$year     = esc_attr($year_data['year']);
						$visible  = $index === 0 ? 'block' : 'none';
						$documents = $year_data['documents'] ?? [];
					?>
						<ul class="document-list document-year" data-year="<?php echo esc_attr($year); ?>" style="display: <?php echo esc_attr($visible); ?>;">
							<?php if (is_array($documents) && !empty($documents)) : ?>
								<?php foreach ($documents as $document) :
									$link = $document['document_link'] ?? null;
								?>
									<li>
										<div>
											<img src="<?php echo esc_url(get_template_directory_uri() . '/images/icon-21.svg'); ?>" alt="" />
											<p class="fs-400"><?php echo esc_html($link['title'] ?? 'Untitled Document'); ?></p>
										</div>
										<?php if ($link) : ?>
											<a class="hl download" href="<?php echo esc_url($link['url']); ?>" target="_blank">View</a>
										<?php else : ?>
											<span>No document available</span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							<?php else : ?>
								<li>No documents available for this year</li>
							<?php endif; ?>
						</ul>
					<?php endforeach; ?>
				</div>
			</div>

				
		</div>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				const yearSelect = document.getElementById("<?php echo esc_js($instance_id); ?>_yearSelect");
				const documentLists = document.querySelectorAll("#<?php echo esc_js($instance_id); ?>-documents-container .document-year");

				function updateDocumentList(year) {
					documentLists.forEach(list => {
						list.style.display = list.getAttribute("data-year") === year ? "block" : "none";
					});
				}

				yearSelect.addEventListener("change", function() {
					updateDocumentList(this.value);
				});

				updateDocumentList(yearSelect.value);
			});
		</script>
	</div>
<?php endif; ?>