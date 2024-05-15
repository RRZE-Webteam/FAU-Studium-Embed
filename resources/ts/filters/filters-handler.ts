import isReducedMotion from '../common/reduced-motion-detection';
import { clearInput, SEARCH_ACTIVE_FILTER_LABEL } from '../form/input-handler';
import { toggleActiveFilter } from './active-filters';
import updateFiltersCount from './filters-count';
import submitForm from '../form/form-handler';

const FILTER_SELECTOR = '.c-filter-checkbox';

const filters = document.querySelectorAll< HTMLElement >( FILTER_SELECTOR );

export const resetRelatedInput = ( label: string ) => {
	if ( ! label ) {
		return;
	}

	const content = label.split( ':' ).map( ( item ) => item.trim() );
	const [ filter ] = content;

	if ( filter === SEARCH_ACTIVE_FILTER_LABEL ) {
		clearInput();

		if ( isReducedMotion() ) {
			submitForm();
		}

		return;
	}

	const [ , filterValue ] = content;

	filters.forEach( ( filterControl ) => {
		const labelText = filterControl
			.querySelector( 'span' )
			?.textContent?.trim();

		if ( filterValue !== labelText ) {
			return;
		}

		const checkbox = filterControl.querySelector< HTMLInputElement >(
			'input[type=checkbox]'
		) as HTMLInputElement;

		checkbox.checked = false;
		checkbox.dispatchEvent( new Event( 'change' ) );

		if ( isReducedMotion() ) {
			submitForm();
		}
	} );
};

filters.forEach( ( filterControl ) => {
	const checkbox = filterControl.querySelector< HTMLInputElement >(
		'input[type=checkbox]'
	);
	checkbox?.addEventListener( 'change', ( e ) => {
		toggleActiveFilter( filterControl, checkbox );
		updateFiltersCount( checkbox );

		if ( isReducedMotion() ) {
			return;
		}

		submitForm();
	} );
} );
