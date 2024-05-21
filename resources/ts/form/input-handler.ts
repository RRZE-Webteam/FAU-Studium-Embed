import { _x } from '@wordpress/i18n';

import isReducedMotion from '../common/reduced-motion-detection';
import submitForm from './form-handler';
import { toggleSingleActiveFilter } from '../filters/active-filters';

const INPUT_SELECTOR = '.c-degree-programs-sarchform__input';
const MIN_CHARACTERS = 3;
export const SEARCH_ACTIVE_FILTER_LABEL = _x(
	'Keyword',
	'frontoffice: degree-programs-overview',
	'fau-degree-program-output'
);

const input = document.querySelector< HTMLInputElement >( INPUT_SELECTOR );

const initLiveSearching = () => {
	const INPUT_DELAY = 1500;

	let timeout: ReturnType< typeof setTimeout > | null = null;

	input?.addEventListener( 'input', () => {
		if ( timeout ) {
			clearTimeout( timeout );
		}

		const inputValue = input.value.trim();

		if ( inputValue.length > MIN_CHARACTERS ) {
			timeout = setTimeout( () => {
				submitForm();
				timeout = null;
			}, INPUT_DELAY );
		}
	} );
};

if ( ! isReducedMotion() ) {
	initLiveSearching();
}

input?.addEventListener( 'blur', () => {
	const inputValue = input.value.trim();

	if ( inputValue.length > MIN_CHARACTERS ) {
		return;
	}

	submitForm();
} );
input?.addEventListener( 'search', () => {
	submitForm();
} );

export const toggleSearchActiveFilter = () => {
	if ( ! input ) {
		return;
	}

	const inputValue = input.value.trim();

	toggleSingleActiveFilter( SEARCH_ACTIVE_FILTER_LABEL, inputValue );
};

export const clearInput = () => {
	if ( ! input ) {
		return;
	}

	input.value = '';
	input.dispatchEvent( new Event( 'search' ) );
};
