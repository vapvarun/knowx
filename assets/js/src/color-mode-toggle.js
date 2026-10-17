/**
 * Colour mode toggle: cycles light, dark and automatic, and remembers the choice.
 * The mode is applied before first paint by an inline script in the head.
 */
(function () {
	'use strict';

	const KEY = 'knowx-color-mode';
	const ORDER = ['light', 'dark', 'auto'];
	const root = document.documentElement;
	const button = document.querySelector('.color-mode-toggle');

	if (!button) {
		return;
	}

	const labels = JSON.parse(button.dataset.labels || '{}');
	const current = () =>
		ORDER.includes(root.dataset.colorMode)
			? root.dataset.colorMode
			: 'light';
	const render = () => {
		const label = labels[current()] || '';
		button.setAttribute('aria-label', label);
		button.setAttribute('title', label);
	};

	button.addEventListener('click', () => {
		const next = ORDER[(ORDER.indexOf(current()) + 1) % ORDER.length];
		root.setAttribute('data-color-mode', next);
		try {
			window.localStorage.setItem(KEY, next);
		} catch (error) {
			// Storage can be blocked; the choice then lasts for this page only.
		}
		render();
	});

	render();
})();
