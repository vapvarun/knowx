/**
 * Theme behaviour that CSS cannot do: table cell labels, scrolling tables and the back-to-top button.
 * No jQuery; menus live in navigation.ts.
 */
(function () {
	'use strict';

	const data = window.knowxData || {};

	/**
	 * Copies each column heading onto its cells so tables can stack on small screens.
	 */
	function initTables() {
		document.querySelectorAll('table').forEach((table) => {
			const heads = Array.from(table.querySelectorAll('thead th')).map(
				(th) => th.textContent.trim()
			);

			table.querySelectorAll('tbody tr').forEach((row) => {
				Array.from(row.children).forEach((cell, index) => {
					if (heads[index]) {
						cell.setAttribute('data-attr', heads[index]);
					}
				});
			});
		});

		// A table that scrolls sideways (phones) takes keyboard focus so it can be scrolled with the arrow keys.
		if ('ResizeObserver' in window) {
			const observer = new window.ResizeObserver((entries) => {
				entries.forEach(({ target }) => {
					if (target.scrollWidth > target.clientWidth) {
						target.setAttribute('tabindex', '0');
					} else {
						target.removeAttribute('tabindex');
					}
				});
			});
			document
				.querySelectorAll('.wp-block-table')
				.forEach((figure) => observer.observe(figure));
		}
	}

	/**
	 * Adds a back-to-top button that appears after scrolling down.
	 */
	function initScrollTop() {
		if (!data.enableScrollup) {
			return;
		}

		const button = document.createElement('a');
		button.id = 'scrollUp';
		button.href = '#page';
		button.setAttribute('aria-label', data.scrollTopLabel || 'Back to top');
		button.innerHTML =
			'<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 15l6-6 6 6"/></svg>';
		document.body.appendChild(button);

		const reduceMotion = window.matchMedia(
			'(prefers-reduced-motion: reduce)'
		);
		let ticking = false;

		const update = () => {
			button.classList.toggle('is-visible', window.scrollY > 300);
			ticking = false;
		};

		window.addEventListener(
			'scroll',
			() => {
				if (!ticking) {
					ticking = true;
					window.requestAnimationFrame(update);
				}
			},
			{ passive: true }
		);

		button.addEventListener('click', (event) => {
			event.preventDefault();
			window.scrollTo({
				top: 0,
				behavior: reduceMotion.matches ? 'auto' : 'smooth',
			});
		});

		update();
	}

	initTables();
	initScrollTop();
})();
