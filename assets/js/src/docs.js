/**
 * Docs enhancements: copy buttons on code blocks, copyable heading links and the open tree on wide screens.
 * Everything here is progressive; the pages work without it.
 */
(() => {
	const strings = window.knowxDocs || {};
	const { _n, sprintf } = window.wp.i18n;
	const live = document.createElement('div');
	live.className = 'screen-reader-text';
	live.setAttribute('role', 'status');
	document.body.append(live);

	const announce = (text) => {
		live.textContent = '';
		setTimeout(() => {
			live.textContent = text;
		}, 50);
	};

	const copy = async (text, done) => {
		try {
			await window.navigator.clipboard.writeText(text);
			announce(done);
			return true;
		} catch (e) {
			// No Clipboard API (a page that is not served over HTTPS) or permission denied.
			announce(strings.copyFailed);
			return false;
		}
	};

	// Code blocks.
	document.querySelectorAll('.entry-content pre').forEach((pre) => {
		pre.classList.add('knowx-code');
		const button = document.createElement('button');
		button.type = 'button';
		button.className = 'knowx-copy';
		button.textContent = strings.copy;
		button.addEventListener('click', async () => {
			if (
				await copy(
					pre.querySelector('code')?.innerText ?? pre.innerText,
					strings.copied
				)
			) {
				button.textContent = strings.copied;
				setTimeout(() => {
					button.textContent = strings.copy;
				}, 1800);
			} else {
				button.textContent = strings.copyFailed;
				setTimeout(() => {
					button.textContent = strings.copy;
				}, 1800);
			}
		});
		pre.append(button);
	});

	// A breadcrumb trail that scrolls on phones starts at its end, so the current page is in view.
	document.querySelectorAll('.knowx-trail-items').forEach((trail) => {
		if (trail.scrollWidth > trail.clientWidth) {
			const rtl = document.documentElement.dir === 'rtl';
			trail.scrollLeft = rtl ? -trail.scrollWidth : trail.scrollWidth;
		}
	});

	// Heading links copy their address and keep the browser's normal jump.
	document.querySelectorAll('.knowx-anchor').forEach((anchor) => {
		anchor.addEventListener('click', () => {
			copy(anchor.href, strings.linkCopied);
		});
	});

	// Tree beside the article on wide screens.
	const tree = document.querySelector('.knowx-tree-panel');
	if (tree && !window.CSS.supports('selector(::details-content)')) {
		const wide = window.matchMedia('(min-width: 900px)');
		const sync = () => {
			tree.open = wide.matches;
		};
		sync();
		wide.addEventListener('change', sync);
	}

	// Instant search overlay.
	const dialog = document.querySelector('.knowx-search-dialog');
	if (dialog && typeof dialog.showModal === 'function') {
		const input = dialog.querySelector('input[type="search"]');
		const list = dialog.querySelector('.knowx-search-dialog-results');
		const status = dialog.querySelector('.knowx-search-status');
		const scope = dialog.querySelector('.knowx-search-scope input');
		const decode = (html) =>
			new window.DOMParser().parseFromString(html, 'text/html').body
				.textContent;
		let timer;
		let request;

		const run = async () => {
			const term = input.value.trim();
			if (request) {
				request.abort();
			}
			list.replaceChildren();
			if (term.length < 2) {
				status.textContent = '';
				return;
			}
			const url = new URL(dialog.dataset.endpoint);
			url.searchParams.set('search', term);
			url.searchParams.set('per_page', '8');
			url.searchParams.set('_fields', 'id,link,title');
			if (scope?.checked && dialog.dataset.categories) {
				url.searchParams.set('categories', dialog.dataset.categories);
			}
			request = new window.AbortController();
			status.textContent = strings.searching;
			try {
				const response = await window.fetch(url, {
					signal: request.signal,
				});
				const posts = await response.json();
				if (!Array.isArray(posts)) {
					throw new Error('bad response');
				}
				posts.forEach((post) => {
					const link = document.createElement('a');
					link.href = post.link;
					link.textContent = decode(post.title.rendered);
					const item = document.createElement('li');
					item.append(link);
					list.append(item);
				});
				if (!posts.length) {
					status.textContent = strings.none;
				} else {
					status.textContent = sprintf(
						/* translators: %d: number of results. */
						_n(
							'%d result. Use the arrow keys to move through it.',
							'%d results. Use the arrow keys to move through them.',
							posts.length,
							'knowx'
						),
						posts.length
					);
				}
			} catch (e) {
				if (e.name !== 'AbortError') {
					status.textContent = strings.error;
				}
			}
		};

		const open = (event) => {
			event?.preventDefault();
			if (!dialog.open) {
				dialog.showModal();
			}
			input.select();
		};

		document
			.querySelectorAll('.knowx-search-trigger')
			.forEach((trigger) => trigger.addEventListener('click', open));
		dialog
			.querySelector('.knowx-search-close')
			.addEventListener('click', () => dialog.close());
		dialog.addEventListener('click', (event) => {
			if (event.target === dialog) {
				dialog.close();
			}
		});
		input.addEventListener('input', () => {
			clearTimeout(timer);
			timer = setTimeout(run, 200);
		});
		scope?.addEventListener('change', run);

		// Arrow keys move through the results; Enter on the input still runs the normal search.
		dialog.addEventListener('keydown', (event) => {
			if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
				return;
			}
			const links = [...list.querySelectorAll('a')];
			if (!links.length) {
				return;
			}
			event.preventDefault();
			const at = links.indexOf(dialog.ownerDocument.activeElement);
			const next =
				event.key === 'ArrowDown'
					? (at + 1) % links.length
					: (at - 1 + links.length) % links.length;
			(at === 0 && event.key === 'ArrowUp' ? input : links[next]).focus();
		});

		document.addEventListener('keydown', (event) => {
			if (
				(event.metaKey || event.ctrlKey) &&
				event.key.toLowerCase() === 'k'
			) {
				open(event);
			}
		});
	}

	// Header Products menu: Escape and a click elsewhere close it, like the mobile drawer.
	const switcher = document.querySelector('details.knowx-switcher');
	if (switcher) {
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && switcher.open) {
				switcher.open = false;
				switcher.querySelector('summary')?.focus();
			}
		});
		document.addEventListener('click', (event) => {
			if (switcher.open && !switcher.contains(event.target)) {
				switcher.open = false;
			}
		});
	}
})();
