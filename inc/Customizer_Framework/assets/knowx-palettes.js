/**
 * Colour palettes for the Colors section.
 *
 * Adds a row of palette cards at the top of the section. Choosing one fills the colour controls below it,
 * and each of them can still be changed by hand afterwards. "Default" puts every colour back to the theme's own.
 * The palettes come from PHP (window.knowxPalettes), so a child theme can add its own with the `knowx_color_palettes` filter.
 */
( function ( api ) {
	'use strict';

	const data = window.knowxPalettes || {};
	const palettes = data.palettes || {};
	const ids = data.ids || [];
	const text = data.strings || {};

	if ( ! Object.keys( palettes ).length ) {
		return;
	}

	const norm = ( value ) => String( value || '' ).toLowerCase();

	function defaultOf( id ) {
		return ( data.defaults || {} )[ id ] || '';
	}

	function apply( colors ) {
		ids.forEach( ( id ) => {
			const setting = api( id );
			if ( setting ) {
				setting.set( colors ? colors[ id ] || '' : defaultOf( id ) );
			}
		} );
	}

	function matches( colors ) {
		return ids.every( ( id ) => {
			const setting = api( id );
			const want = colors ? colors[ id ] || '' : defaultOf( id );
			return setting && norm( setting.get() ) === norm( want );
		} );
	}

	function card( label, colors, preview ) {
		const button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'knowx-palette';
		button.setAttribute( 'aria-pressed', 'false' );

		const swatches = document.createElement( 'span' );
		swatches.className = 'knowx-palette-swatches';
		preview.forEach( ( color ) => {
			const dot = document.createElement( 'span' );
			dot.style.background = color;
			swatches.appendChild( dot );
		} );

		const name = document.createElement( 'span' );
		name.className = 'knowx-palette-name';
		name.textContent = label;

		button.append( swatches, name );
		button.addEventListener( 'click', () => apply( colors ) );

		return { button, colors };
	}

	api.bind( 'ready', () => {
		api.section( 'site_skin_section', ( section ) => {
			const wrap = document.createElement( 'div' );
			wrap.className = 'knowx-palettes';

			const title = document.createElement( 'p' );
			title.className = 'knowx-palettes-title';
			title.textContent = text.title || 'Palettes';
			const hint = document.createElement( 'p' );
			hint.className = 'description';
			hint.textContent = text.hint || '';

			const row = document.createElement( 'div' );
			row.className = 'knowx-palette-row';

			const cards = [ card( text.reset || 'Default', null, [ '#03a9f4', '#f7f7f9', '#ffffff', '#333333' ] ) ];
			Object.keys( palettes ).forEach( ( slug ) => {
				const c = palettes[ slug ].colors;
				cards.push( card( palettes[ slug ].label, c, [ c.site_primary_color, c.background_color, c.site_surface_color, c.site_heading_color ] ) );
			} );
			cards.forEach( ( c ) => row.appendChild( c.button ) );

			wrap.append( title, hint, row );
			const content = section.contentContainer[ 0 ] || section.container[ 0 ];
			const list = content.querySelector( '.customize-section-title' );
			if ( list && list.nextSibling ) {
				list.parentNode.insertBefore( wrap, list.nextSibling );
			} else {
				content.prepend( wrap );
			}

			const refresh = () => cards.forEach( ( c ) => c.button.setAttribute( 'aria-pressed', matches( c.colors ) ? 'true' : 'false' ) );
			ids.forEach( ( id ) => api( id, ( setting ) => setting.bind( refresh ) ) );
			refresh();
		} );
	} );
}( wp.customize ) );
