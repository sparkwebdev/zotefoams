<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('financial_documents_picker_title');
$text = get_sub_field('financial_documents_picker_text'); // ACF Link field
		
$documents_by_year = get_sub_field('documents_by_year'); // Get repeater field
?>

<?php if ($text): ?>
	<div class="financial-documents-picker cont-m light-grey-bg padding-50 theme-light">
		<div class="financial-documents-intro">
			<?php if ($title): ?>
				<h3 class="fw-semibold margin-b-20 fs-400"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
			<?php if ($text): ?>
				<?php echo wp_kses_post($text); ?>
			<?php endif; ?>
		</div>
		<div class="financial-documents">

			<?php if ($documents_by_year): ?>
				<?php $instance_id = uniqid('docs_'); // Generate a unique instance ID ?>

				<select class="yearSelect" id="<?php echo $instance_id; ?>_yearSelect">
					<?php foreach ($documents_by_year as $index => $year_data): ?>
						<option value="<?php echo esc_attr($year_data['year']); ?>" <?php echo $index === 0 ? 'selected' : ''; ?>>
							<?php echo esc_html($year_data['year']); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div class="documents-container" id="<?php echo $instance_id; ?>-documents-container">
					<?php foreach ($documents_by_year as $index => $year_data): ?>
						<ul class="document-list document-year" data-year="<?php echo esc_attr($year_data['year']); ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
							<?php if (!empty($year_data['documents']) && is_array($year_data['documents'])): ?>
								<?php foreach ($year_data['documents'] as $document): ?>
									<?php $link = $document['document_link']; ?>
									<li>
										<div class="">
											<img src="<?php echo get_template_directory_uri(); ?>/images/icon-21.svg" />
											<p class="fs-400"><?php echo esc_html($link['title']); ?></p>
										</div>
										<?php if ($link): ?>
											<a class="hl download" href="<?php echo esc_url($link['url']); ?>" target="_blank">
											View</a>
										<?php else: ?>
											No document available
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							<?php else: ?>
								<li>No documents available for this year</li>
							<?php endif; ?>
						</ul>
					<?php endforeach; ?>
				</div>

				<script>
					document.addEventListener("DOMContentLoaded", function () {
						const yearSelect = document.getElementById("<?php echo $instance_id; ?>_yearSelect");
						const documentLists = document.querySelectorAll("#<?php echo $instance_id; ?>-documents-container .document-year");

						function updateDocumentList(year) {
							documentLists.forEach(list => {
								list.style.display = list.getAttribute("data-year") === year ? "block" : "none";
							});
						}

						yearSelect.addEventListener("change", function () {
							updateDocumentList(this.value);
						});

						// Show the first year by default
						updateDocumentList(yearSelect.value);
					});
				</script>

			<?php endif; ?>

		</div>
	</div>
<?php else : ?>
	<div class="financial-documents-picker cont-m margin-b-100 theme-none">
		<div class="half">
			<?php if ($title): ?>
				<h3 class="fw-semibold margin-b-20 fs-400"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>

			<?php if ($documents_by_year): ?>
				<?php $instance_id = uniqid('docs_'); // Generate a unique instance ID ?>

				<select class="yearSelect" id="<?php echo $instance_id; ?>_yearSelect">
					<?php foreach ($documents_by_year as $index => $year_data): ?>
						<option value="<?php echo esc_attr($year_data['year']); ?>" <?php echo $index === 0 ? 'selected' : ''; ?>>
							<?php echo esc_html($year_data['year']); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div class="documents-container" id="<?php echo $instance_id; ?>-documents-container">
					<?php foreach ($documents_by_year as $index => $year_data): ?>
						<ul class="document-list document-year" data-year="<?php echo esc_attr($year_data['year']); ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
							<?php if (!empty($year_data['documents']) && is_array($year_data['documents'])): ?>
								<?php foreach ($year_data['documents'] as $document): ?>
									<?php $link = $document['document_link']; ?>
									<li>
										<div class="">
											<img src="<?php echo get_template_directory_uri(); ?>/images/icon-21.svg" />
											<p class="fs-400"><?php echo esc_html($link['title']); ?></p>
										</div>
										<?php if ($link): ?>
											<a class="hl download" href="<?php echo esc_url($link['url']); ?>" target="_blank">
											View</a>
										<?php else: ?>
											No document available
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							<?php else: ?>
								<li>No documents available for this year</li>
							<?php endif; ?>
						</ul>
					<?php endforeach; ?>
				</div>

				<script>
					document.addEventListener("DOMContentLoaded", function () {
						const yearSelect = document.getElementById("<?php echo $instance_id; ?>_yearSelect");
						const documentLists = document.querySelectorAll("#<?php echo $instance_id; ?>-documents-container .document-year");

						function updateDocumentList(year) {
							documentLists.forEach(list => {
								list.style.display = list.getAttribute("data-year") === year ? "block" : "none";
							});
						}

						yearSelect.addEventListener("change", function () {
							updateDocumentList(this.value);
						});

						// Show the first year by default
						updateDocumentList(yearSelect.value);
					});
				</script>

			<?php endif; ?>

		</div>
	</div>
<?php endif; ?>