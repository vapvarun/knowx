/**
 * File customizer.js.
 *
 * Theme Customizer preview enhancements.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

interface BindCallback<T> {
	(to: T): void;
}

interface CustomizeValue<T> {
	bind: (callback: BindCallback<T>) => void;
}

interface WPCustomize {
	<T>(id: string, callback: (value: CustomizeValue<T>) => void): void;
}

// Extend Window interface properly
declare global {
	interface Window {
		wp: { customize: WPCustomize };
	}
}

// This export makes the file a module and allows declare global to work
export {};

// Helper functions
function setTextContent(selector: string, text: string): void {
	const elements = document.querySelectorAll(selector);
	elements.forEach((element) => {
		element.textContent = text;
	});
}

// Site title and description.
window.wp.customize<string>('blogname', function (value) {
	value.bind(function (to) {
		setTextContent('.site-title a', to);
	});
});

window.wp.customize<string>('blogdescription', function (value) {
	value.bind(function (to) {
		setTextContent('.site-description', to);
	});
});
