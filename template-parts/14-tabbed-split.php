
    <div class="cont-xs tabs-container margin-b-30">
        <div class="tabs">
            <div class="tab active" data-tab="early-careers">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-14.svg" />
				<p class="">Early Careers</p>
            </div>
            <div class="tab" data-tab="operations">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-12.svg" />
				<p>Operations</p>
            </div>
            <div class="tab" data-tab="head-office">
                <img src="<?php echo get_template_directory_uri(); ?>/images/icon-13.svg" />
				<p>Head Office</p>
            </div>
        </div>
    </div>

	<div class="content-container light-grey-bg">
		<div class="tab-content active" id="early-careers">
			<div class="half-half">
				<div class="half">
					<div class="all-content padding-100">
						<div class="top-content">
							<p class="fs-400 fw-bold margin-b-15">Early Careers</p>
							<p class="fs-300 grey-text">Experience life at Zotefoams during an exciting two-year graduate programme with structured rotations across the business departments. The placement offers practical experience and an opportunity to develop essential professional skills. At the end of the programme, you will be encouraged to apply for any suitable vacancies.</p>
						</div>
						<a href="#" class="hl arrow">Apply For Our Graduate Scheme</a>
					</div>
				</div>
				<div class="half image-cover" style="background-image:url(http://zotefoams.local/wp-content/themes/zotefoams/images/475033.jpg)">
				</div>
			</div>
		</div>
		<div class="tab-content" id="operations">
			<div class="half-half">
				<div class="half">
					<div class="all-content padding-100">
						<div class="top-content">
							<p class="fs-400 fw-bold margin-b-15">Operations</p>
							<p class="fs-300 grey-text">Experience life at Zotefoams during an exciting two-year graduate programme with structured rotations across the business departments. The placement offers practical experience and an opportunity to develop essential professional skills. At the end of the programme, you will be encouraged to apply for any suitable vacancies.</p>
						</div>
						<a href="#" class="hl arrow">Apply For Our Graduate Scheme</a>
					</div>
				</div>
				<div class="half image-cover" style="background-image:url(http://zotefoams.local/wp-content/themes/zotefoams/images/18346858.jpg)">
				</div>
			</div>
		</div>
		<div class="tab-content" id="head-office">
			<div class="half-half">
				<div class="half">
					<div class="all-content padding-100">
						<div class="top-content">
							<p class="fs-400 fw-bold margin-b-15">Head Office</p>
							<p class="fs-300 grey-text">Experience life at Zotefoams during an exciting two-year graduate programme with structured rotations across the business departments. The placement offers practical experience and an opportunity to develop essential professional skills. At the end of the programme, you will be encouraged to apply for any suitable vacancies.</p>
						</div>
						<a href="#" class="hl arrow">Apply For Our Graduate Scheme</a>
					</div>
				</div>
				<div class="half image-cover" style="background-image:url(http://zotefoams.local/wp-content/themes/zotefoams/images/2148908800.jpg)">
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", () => {
			const tabs = document.querySelectorAll(".tab");
			const tabContents = document.querySelectorAll(".tab-content");

			tabs.forEach(tab => {
				tab.addEventListener("click", () => {
					// Remove active class from all tabs and contents
					tabs.forEach(t => t.classList.remove("active"));
					tabContents.forEach(c => c.classList.remove("active"));

					// Add active class to clicked tab and corresponding content
					tab.classList.add("active");
					document.getElementById(tab.dataset.tab).classList.add("active");
				});
			});
		});
	</script>