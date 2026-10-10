/**
 * Order field for articles in the block editor. WordPress shows Parent but not Order for posts,
 * and the docs tree and section lists sort by this number.
 */
(() => {
	const { registerPlugin } = window.wp.plugins;
	const { PluginDocumentSettingPanel } = window.wp.editor;
	const { NumberControl, __experimentalNumberControl } = window.wp.components;
	const { useSelect, useDispatch } = window.wp.data;
	const { createElement } = window.wp.element;
	const { __ } = window.wp.i18n;
	const Control = NumberControl || __experimentalNumberControl;

	const Panel = () => {
		const order = useSelect(
			(select) =>
				select('core/editor').getEditedPostAttribute('menu_order') ?? 0,
			[]
		);
		const { editPost } = useDispatch('core/editor');

		return createElement(
			PluginDocumentSettingPanel,
			{
				name: 'knowx-order',
				title: __('Order in section', 'knowx'),
			},
			createElement(Control, {
				__next40pxDefaultSize: true,
				label: __('Order', 'knowx'),
				help: __(
					'Lower numbers come first in the section tree and lists.',
					'knowx'
				),
				value: order,
				min: 0,
				onChange: (value) =>
					editPost({ menu_order: parseInt(value, 10) || 0 }),
			})
		);
	};

	registerPlugin('knowx-order', { render: Panel });
})();
