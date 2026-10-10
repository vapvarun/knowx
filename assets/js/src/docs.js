/**
 * Docs enhancements: copy buttons on code blocks, copyable heading links and the open tree on wide screens.
 * Everything here is progressive; the pages work without it.
 */
(() => {
	const strings = window.knowxDocs || {};
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
			}
		});
		pre.append(button);
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
		const wide = window.matchMedia('(min-width: 1100px)');
		const sync = () => {
			tree.open = wide.matches;
		};
		sync();
		wide.addEventListener('change', sync);
	}
})();
