/**
 * Customizer helpers: colour palettes for the Colors section, and collapsible font groups in Typography.
 *
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

	/**
	 * Typography: one control per text style, each a long list of inputs. Each group opens from its title, the first one is open.
	 */
	api.bind( 'ready', () => {
		api.section( 'site_title_typography_section', ( section ) => {
			section.container.on( 'expanded', () => {
				const controls = Array.from( section.contentContainer[ 0 ].querySelectorAll( '.customize-control-knowx-typography' ) );
				controls.forEach( ( control, index ) => {
					const title = control.querySelector( '.customize-control-title' );
					if ( ! title || control.dataset.knowxFold ) {
						return;
					}
					control.dataset.knowxFold = '1';
					title.setAttribute( 'role', 'button' );
					title.setAttribute( 'tabindex', '0' );
					const set = ( open ) => {
						control.classList.toggle( 'knowx-folded', ! open );
						title.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
					};
					title.addEventListener( 'click', () => set( control.classList.contains( 'knowx-folded' ) ) );
					title.addEventListener( 'keydown', ( event ) => {
						if ( 'Enter' === event.key || ' ' === event.key ) {
							event.preventDefault();
							set( control.classList.contains( 'knowx-folded' ) );
						}
					} );
					set( 0 === index );
				} );
			} );
		} );
	} );

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

			// The colour picker keeps its last swatch when a setting is emptied from outside it (a palette sets
			// Link Color to "use the theme shade"), so clear the swatch to match the empty field.
			ids.forEach( ( id ) => api( id, ( setting ) => setting.bind( ( value ) => {
				if ( ! value ) {
					api.control( id, ( control ) => control.container.find( '.wp-color-result' ).css( 'background-color', '' ) );
				}
			} ) ) );
			refresh();
		} );
	} );
}( wp.customize ) );
