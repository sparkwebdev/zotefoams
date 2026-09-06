(() => {
	const blocks = [
		...document.querySelectorAll(".component-applications-slider"),
	];

	blocks.map((block) => {
		const chips = [
			...document.querySelectorAll("[data-applications-slider--chip]"),
		];
		const cards = [
			...document.querySelectorAll("[data-applications-slider--card]"),
		];

		chips.forEach((chip) => {
			const application = chip.dataset["applicationsSlider-Chip"].trim();

			const enable = () => {
				chips.forEach(
					(c) => (c.ariaPressed = c === chip ? "true" : "false"),
				);

				cards.forEach((card) => {
					const applications = card.dataset["applicationsSlider-Card"]
						.split(",")
						.map((app) => app.trim());

					if (!application) {
						card.hidden = false;
						return;
					}

					const hasApplication = applications.some(
						(app) => app === application,
					);
					card.hidden = !hasApplication;
				});
			};

			chip.addEventListener("click", enable);
		});
	});
})();
