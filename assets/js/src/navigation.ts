declare const knowxScreenReaderText: { [key: string]: string };

// Module-level variable to store navigation elements
let navElements: NodeListOf<HTMLElement>;

// Initiate the menus when the DOM loads.
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initNavigation);
} else {
	initNavigation();
}

function initNavigation(): void {
	initNavToggleSubmenus();
	initNavToggleSmall();
	watchForWindowSizeChanges();
	initDesktopSubmenuKeyboard();
}

/**
 * On wide screens a submenu opens when focus is inside it (CSS :focus-within), so keyboard users
 * can reach the child items with Tab. Escape closes it again and puts focus back on the parent link.
 *
 * @return {void}
 */
function initDesktopSubmenuKeyboard(): void {
	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || isMobileWidth()) {
			return;
		}
		const parentItem = (event.target as HTMLElement).closest<HTMLElement>(
			'.nav--toggle-sub li.menu-item-has-children'
		);
		if (!parentItem) {
			return;
		}
		parentItem.classList.add('knowx-dismissed');
		parentItem.querySelector<HTMLElement>(':scope > a')?.focus();
	});

	// The submenu may open again once focus or the pointer leaves the item.
	const reopen = (event: Event) => {
		document
			.querySelectorAll<HTMLElement>('.knowx-dismissed')
			.forEach((item) => {
				if (!item.contains(event.target as Node)) {
					item.classList.remove('knowx-dismissed');
				}
			});
	};
	document.addEventListener('focusin', reopen);
	document.addEventListener('mouseover', reopen);
}

/**
 * Initializes navigation menu toggle functionality for submenus.
 * This method selects all elements with the class 'nav--toggle-sub' and applies
 * the `initEachNavToggleSubmenu` function to each of those elements.
 *
 * @return {void} This function does not return a value.
 */
function initNavToggleSubmenus(): void {
	const navTOGGLE: NodeListOf<HTMLElement> =
		document.querySelectorAll('.nav--toggle-sub');

	if (!navTOGGLE.length) {
		return;
	}

	navTOGGLE.forEach((nav) => initEachNavToggleSubmenu(nav));
}

/**
 * Initializes each navigation toggle submenu within a given navigation element.
 *
 * @param {HTMLElement} nav - The navigation element containing submenus.
 * @return {void} This function does not return a value.
 */
function initEachNavToggleSubmenu(nav: HTMLElement): void {
	const SUBMENUS: NodeListOf<HTMLElement> = nav.querySelectorAll(
		'ul.sub-menu, ul.wp-block-navigation__submenu-container'
	);

	if (!SUBMENUS.length) {
		return;
	}

	const dropdownButton = getDropdownButton();

	SUBMENUS.forEach((submenu, index) =>
		processEachSubMenu(SUBMENUS, dropdownButton, index)
	);
}

/**
 * Handles the toggle events for submenus within a given parent menu item.
 *
 * @param {ParentNode} parentMenuItem - The parent menu item that contains the submenu to be toggled.
 * @return {void}
 */
function handleToggleSubMenuEvents(parentMenuItem: ParentNode): void {
	const FOCUS_ELEMENTS_SELECTOR =
		'ul.toggle-show > li > a, ul.toggle-show > li > button';
	const anchor = parentMenuItem.querySelector<HTMLAnchorElement>('a');
	anchor?.addEventListener('focus', (e) => {
		// Fix: Type guard for currentTarget and cast to HTMLElement
		if (e.currentTarget && e.currentTarget instanceof HTMLElement) {
			// Ensure we operate relative to the parent <li> element
			const parentLi = (e.currentTarget as HTMLElement).closest(
				'li'
			) as HTMLElement | null;
			if (parentLi && parentLi.parentElement) {
				const parentMenuItemsToggled: NodeListOf<HTMLElement> =
					parentLi.parentElement.querySelectorAll(
						'li.menu-item--toggled-on'
					);
				parentMenuItemsToggled.forEach((menuItem) =>
					toggleSubMenu(menuItem)
				);
			}
		}
	});

	parentMenuItem.addEventListener('keydown', (e) => {
		if (
			e instanceof KeyboardEvent &&
			e.key === 'Tab' &&
			shouldToggleSubMenu(e, FOCUS_ELEMENTS_SELECTOR)
		) {
			// Always resolve to the parent <li>, even if the target is inside a button
			const parentLi = (e.target as HTMLElement).closest(
				'li'
			) as HTMLElement | null;
			if (parentLi) {
				toggleSubMenu(parentLi);
			}
		}
	});
}

/**
 * Determines if the sub-menu should toggle based on the provided keyboard event and focus selector.
 *
 * @param {KeyboardEvent} e             - The keyboard event that triggers the check.
 * @param {string}        focusSelector - The CSS selector used to identify focusable elements within the sub-menu.
 * @return {boolean} Returns true if the sub-menu should toggle, otherwise false.
 */
function shouldToggleSubMenu(e: KeyboardEvent, focusSelector: string): boolean {
	const container = e.shiftKey
		? isFirstFocusableElement
		: isLastFocusableElement;
	// Fix: Use document.documentElement instead of document
	return container(
		document.documentElement,
		e.target as HTMLElement,
		focusSelector
	);
}

/**
 * Initializes the navigation toggle functionality for small navigation elements.
 * This method selects all elements with the class 'nav--toggle-small' and, if any are found,
 * stores them in a global variable and initializes the toggle functionality.
 *
 * @return {void}
 */
function initNavToggleSmall(): void {
	navElements = document.querySelectorAll<HTMLElement>('.nav--toggle-small');

	if (!navElements.length) {
		return;
	}

	initEachNavToggleSmall();
}

/**
 * Monitors the window for resize events and performs actions based on the window size.
 * Specifically, if the window width exceeds a specified breakpoint in em units, it triggers
 * the closure of all sub-menus.
 *
 * @return {void} No return value.
 */
function watchForWindowSizeChanges(): void {
	window.addEventListener('resize', () => {
		if (!isMobileWidth()) {
			closeAllSubMenus();
			setMobileMenuOpen(false);
		}
	});
}

/**
 * Helper to determine if we are at or below the mobile breakpoint (767px, same as the CSS).
 */
function isMobileWidth(): boolean {
	return window.matchMedia('(max-width: 767px)').matches;
}

/**
 * Processes each submenu by checking its parent element, possibly creating a dropdown,
 * attaches toggle button functionality and event listeners for handling submenu actions.
 *
 * @param {NodeListOf<HTMLElement>} SUBMENUS       - The list of submenu elements.
 * @param {HTMLElement}             dropdownButton - The button used to toggle the dropdown.
 * @param {number}                  index          - The index of the current submenu in the SUBMENUS list.
 * @return {void}
 */
function processEachSubMenu(
	SUBMENUS: NodeListOf<HTMLElement>,
	dropdownButton: HTMLElement,
	index: number
): void {
	const parentMenuItem = SUBMENUS[index].parentNode as HTMLElement;
	const isNavigationBlock = parentMenuItem.classList.contains(
		'wp-block-navigation-item'
	);
	let dropdown = parentMenuItem.querySelector<HTMLElement>('.dropdown');

	if (!dropdown && !isNavigationBlock) {
		dropdown = createDropdown(parentMenuItem, SUBMENUS, index);
		parentMenuItem.insertBefore(dropdown, SUBMENUS[index]);
	}

	if (!isNavigationBlock) {
		convertDropdownToToggleButton(dropdown!, dropdownButton);
	} else {
		parentMenuItem
			.querySelector<HTMLElement>('.wp-block-navigation-submenu__toggle')
			?.addEventListener('click', (e) => {
				// Ensure we pass the parent <li>
				const parentLi = (e.currentTarget as HTMLElement).closest(
					'li'
				) as HTMLElement | null;
				if (parentLi) {
					toggleSubMenu(parentLi);
				}
			});
	}

	const subMenuParentLink =
		parentMenuItem.querySelector<HTMLAnchorElement>(':scope > a');

	// Handle menu items with no link or "#" as href
	if (
		!subMenuParentLink ||
		(subMenuParentLink &&
			(subMenuParentLink.getAttribute('href') === '#' ||
				subMenuParentLink.getAttribute('href') === '' ||
				subMenuParentLink.getAttribute('href') === null))
	) {
		// If there is a link, add the click event to it
		if (subMenuParentLink) {
			subMenuParentLink.addEventListener('click', (e) => {
				e.preventDefault();
				// Ensure we pass the parent <li>
				const parentLi = (e.currentTarget as HTMLElement).closest(
					'li'
				) as HTMLElement | null;
				if (parentLi) {
					toggleSubMenu(parentLi);
				}
			});
		}

		// For items with no link, make the entire menu item clickable
		if (!subMenuParentLink) {
			parentMenuItem.style.cursor = 'pointer';
			parentMenuItem.addEventListener('click', (e) => {
				// A click on a link or a button is handled by that element (the dropdown button
				// has its own handler), so only the plain text of the item toggles here.
				if (!(e.target as HTMLElement).closest('a, button')) {
					toggleSubMenu(parentMenuItem);
				}
			});
		}
	} else if (subMenuParentLink) {
		// Parent has a valid link and also has children: on mobile, clicking the parent link should toggle
		subMenuParentLink.addEventListener('click', (e) => {
			if (isMobileWidth()) {
				e.preventDefault();
				const parentLi = (e.currentTarget as HTMLElement).closest(
					'li'
				) as HTMLElement | null;
				if (parentLi) {
					toggleSubMenu(parentLi);
				}
			}
		});
	}

	handleToggleSubMenuEvents(parentMenuItem);
	parentMenuItem.classList.add('menu-item--has-toggle');
}

/**
 * Creates a dropdown element and inserts it before a specified submenu item.
 *
 * @param {ParentNode}              parentMenuItem - The parent menu item where the dropdown will be added.
 * @param {NodeListOf<HTMLElement>} SUBMENUS       - A list of submenu elements under the parent menu item.
 * @param {number}                  index          - The index in the list of submenus where the dropdown will be inserted.
 * @return {HTMLElement} The created dropdown element.
 */
function createDropdown(
	parentMenuItem: ParentNode,
	SUBMENUS: NodeListOf<HTMLElement>,
	index: number
): HTMLElement {
	const dropdown = document.createElement('span');
	dropdown.classList.add('dropdown');
	const dropdownSymbol = document.createElement('i');
	dropdownSymbol.classList.add('dropdown-symbol');
	dropdown.appendChild(dropdownSymbol);
	parentMenuItem.insertBefore(dropdown, SUBMENUS[index]);
	return dropdown;
}

/**
 * Converts a dropdown menu into a toggle button.
 * The converted toggle button will display the dropdown's original inner HTML
 * and will be equipped with an event listener to toggle a sub-menu on click.
 *
 * @param {HTMLElement} dropdown       - The dropdown menu element to be converted.
 * @param {HTMLElement} dropdownButton - The template button element to replace the dropdown with.
 * @return {void}
 */
function convertDropdownToToggleButton(
	dropdown: HTMLElement,
	dropdownButton: HTMLElement
): void {
	const thisDropdownButton = dropdownButton.cloneNode(true) as HTMLElement;
	thisDropdownButton.innerHTML = dropdown.innerHTML;
	dropdown.parentNode!.replaceChild(thisDropdownButton, dropdown);
	thisDropdownButton.addEventListener('click', (e) => {
		// Ensure we pass the parent <li>
		const parentLi = (e.currentTarget as HTMLElement).closest(
			'li'
		) as HTMLElement | null;
		if (parentLi) {
			toggleSubMenu(parentLi);
		}
	});
}

/**
 * Initializes the navigation toggle for a given navigation element, setting up
 * aria attributes and click event listeners to handle the toggling of the navigation menu.
 *
 * @return {void} This function does not return a value.
 */
function initEachNavToggleSmall(): void {
	const menuToggles = document.querySelectorAll<HTMLElement>('.menu-toggle');

	menuToggles.forEach((menuToggle) => {
		menuToggle.setAttribute('aria-expanded', 'false');

		menuToggle.addEventListener('click', toggleMenuToggleState);
	});

	// Close the mobile drawer from its Close button, the dimmed overlay and the Escape key.
	document.addEventListener('click', (e) => {
		const target = e.target as HTMLElement;
		if (target.closest('.menu-close, .mobile-menu-close')) {
			setMobileMenuOpen(false);
			(menuToggles[0] as HTMLElement).focus();
		}
	});
	document.addEventListener('keydown', (e) => {
		if (
			e.key === 'Escape' &&
			document.body.classList.contains('mobile-menu-opened')
		) {
			setMobileMenuOpen(false);
			(menuToggles[0] as HTMLElement).focus();
		}
	});
}

/**
 * Opens or closes the mobile menu drawer and keeps every toggle, nav element and the page in sync.
 *
 * @param {boolean} open - Whether the drawer should be open.
 * @return {void}
 */
function setMobileMenuOpen(open: boolean): void {
	document.body.classList.toggle('mobile-menu-opened', open);

	document
		.querySelectorAll<HTMLElement>('.menu-toggle')
		.forEach((menuToggle) => {
			menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			menuToggle.classList.toggle('menu-toggle-open', open);
		});

	navElements?.forEach((navElement) => {
		navElement.classList.toggle('nav--toggled-on', open);
	});
}

function toggleMenuToggleState(e: Event) {
	const currentToggle = e.currentTarget as HTMLElement;

	setMobileMenuOpen(currentToggle.getAttribute('aria-expanded') === 'false');
}

/**
 * Toggles the sub-menu visibility and accessibility attributes for a given parent menu item.
 *
 * @param {HTMLElement} parentMenuItem - The parent menu item whose sub-menu is to be toggled.
 * @return {void}
 */
function toggleSubMenu(parentMenuItem: HTMLElement): void {
	const toggleButton = parentMenuItem.querySelector<HTMLElement>(
		'.dropdown-toggle, .wp-block-navigation-submenu__toggle'
	);

	// Fix: Add null check for toggleButton before assigning subMenu
	if (!toggleButton) {
		return;
	}

	// Assign subMenu only after null check
	const subMenu = parentMenuItem.querySelector<HTMLElement>('ul');

	const parentMenuItemToggled = parentMenuItem.classList.contains(
		'menu-item--toggled-on'
	);

	if (
		!toggleButton.classList.contains('wp-block-navigation-submenu__toggle')
	) {
		toggleButton.setAttribute(
			'aria-expanded',
			(!parentMenuItemToggled).toString()
		);
	}

	if (parentMenuItemToggled) {
		parentMenuItem.classList.remove('menu-item--toggled-on');
		subMenu!.classList.remove('toggle-show');
		toggleButton.setAttribute('aria-label', knowxScreenReaderText.expand);
	} else {
		parentMenuItem.classList.add('menu-item--toggled-on');
		subMenu!.classList.add('toggle-show');
		toggleButton.setAttribute('aria-label', knowxScreenReaderText.collapse);
	}
}

/**
 * Closes all submenu items by toggling them off.
 *
 * @return {void} No return value.
 */
function closeAllSubMenus(): void {
	const toggledMenuItems = document.querySelectorAll<HTMLElement>(
		'.menu-item--toggled-on'
	);
	toggledMenuItems.forEach((menuItem) => toggleSubMenu(menuItem));
}

/**
 * Creates and returns a dropdown toggle button element.
 *
 * The button element will have the 'dropdown-toggle' class,
 * an 'aria-expanded' attribute set to 'false', and
 * an 'aria-label' attribute with the text for expanding
 * the dropdown from the global knowxScreenReaderText object.
 *
 * @return {HTMLElement} The configured dropdown button element.
 */
function getDropdownButton(): HTMLElement {
	const dropdownButton = document.createElement('button');
	dropdownButton.classList.add('dropdown-toggle');
	dropdownButton.setAttribute('aria-expanded', 'false');
	dropdownButton.setAttribute('aria-label', knowxScreenReaderText.expand);
	return dropdownButton;
}

/**
 * Determines if the given element is the first focusable element within a specified container according to the provided focus selector.
 *
 * @param {HTMLElement} container     - The container element within which to search for focusable elements.
 * @param {HTMLElement} element       - The element to check if it is the first focusable element.
 * @param {string}      focusSelector - The selector used to identify focusable elements within the container.
 * @return {boolean} - Returns true if the element is the first focusable element in the container, otherwise returns false.
 */
function isFirstFocusableElement(
	container: HTMLElement,
	element: HTMLElement,
	focusSelector: string
): boolean {
	const focusableElements =
		container.querySelectorAll<HTMLElement>(focusSelector);
	return focusableElements.length > 0 && element === focusableElements[0];
}

/**
 * Checks if the given element is the last focusable element within a specified container.
 *
 * @param {HTMLElement} container     - The container within which to search for focusable elements.
 * @param {HTMLElement} element       - The element to check if it is the last focusable within the container.
 * @param {string}      focusSelector - The CSS selector string that identifies focusable elements.
 * @return {boolean} True if the element is the last focusable element within the container, otherwise false.
 */
function isLastFocusableElement(
	container: HTMLElement,
	element: HTMLElement,
	focusSelector: string
): boolean {
	const focusableElements =
		container.querySelectorAll<HTMLElement>(focusSelector);
	return (
		focusableElements.length > 0 &&
		element === focusableElements[focusableElements.length - 1]
	);
}
