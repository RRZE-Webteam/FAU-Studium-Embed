import type { ShortcodeAttributes } from './types';

export const PREFIX_INCLUDE = 'include.';
export const PREFIX_EXCLUDE = 'exclude.';

const handleFormData = ( data: object ): ShortcodeAttributes => {
	const attrs = {
		display: 'degree-program',
		id: 0,
		lang: 'de',
		format: 'full',
		include: [],
		exclude: [],
	} as ShortcodeAttributes;

	Object.entries( data ).forEach( ( [ key, value ] ) => {
		if ( key.startsWith( PREFIX_INCLUDE ) ) {
			if ( value === true ) {
				attrs.include.push( key.replace( PREFIX_INCLUDE, '' ) );
			}
			return;
		}

		if ( key.startsWith( PREFIX_EXCLUDE ) ) {
			if ( value === true ) {
				attrs.exclude.push( key.replace( PREFIX_EXCLUDE, '' ) );
			}
			return;
		}

		if ( ! ( key in attrs ) ) {
			return;
		}

		attrs[ key ] = value;
	} );

	return attrs;
};

export const buildShortcode = ( data ): string => {
	const attrs = handleFormData( data );
	const output = [
		`display="${ attrs.display }"`,
		`id="${ attrs.id }"`,
		`lang="${ attrs.lang }"`,
		`format="${ attrs.format }"`,
	];

	if ( attrs.id === 0 ) {
		throw new Error( 'Select a degree program.' );
	}

	// Include/Exclude parameters are ignored when format is short
	if ( attrs.format !== 'short' ) {
		if ( attrs.include.length > 0 ) {
			output.push( `include="${ attrs.include.join( ',' ) }"` );
		}

		// Exclude parameters are ignored when include parameters are set
		if ( attrs.include.length === 0 && attrs.exclude.length > 0 ) {
			output.push( `exclude="${ attrs.exclude.join( ',' ) }"` );
		}
	}

	return `[fau-studium ${ output.join( ' ' ) }]`;
};
