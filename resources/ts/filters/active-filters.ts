import { resetRelatedInput } from './filters-handler';
import settings from '../utils/settings';
import buildActiveFilterLabel from './active-filter-label';

const ACTIVE_FILTERS_SECTION_SELECTOR = '.c-active-search-filters';
const ACTIVE_FILTERS_LIST_SELECTOR = '.c-active-search-filters__list';
const ACTIVE_FILTER_CLASS = 'c-active-search-filters__item';
const ACTIVE_FILTER_SELECTOR = `.${ ACTIVE_FILTER_CLASS }`;

const activeFiltersSection = document.querySelector< HTMLElement >(
	ACTIVE_FILTERS_SECTION_SELECTOR
);
const activeFiltersList = document.querySelector< HTMLElement >(
	ACTIVE_FILTERS_LIST_SELECTOR
);

const activeFilters = (): NodeListOf< HTMLElement > => {
	return document.querySelectorAll< HTMLElement >( ACTIVE_FILTER_SELECTOR );
};

const maybeHideFiltersSection = () => {
	if ( activeFilters().length !== 0 ) {
		return;
	}

	activeFiltersSection?.classList.add( 'hidden' );
};

const filterEventListener = ( e: MouseEvent ) => {
	e.preventDefault();
	const filter = e.target as HTMLElement;
	resetRelatedInput( filter.textContent?.trim() || '' );
	filter.remove();
	maybeHideFiltersSection();
};

activeFilters().forEach( ( filter ) => {
	filter.addEventListener( 'click', filterEventListener );
} );

const addActiveFilter = ( label: string ) => {
	const filter = document.createElement( 'a' );
	filter.insertAdjacentHTML(
		'beforeend',
		`
			${ settings.icon_close }
			${ label }
		`
	);
	filter.className = ACTIVE_FILTER_CLASS;
	filter.addEventListener( 'click', filterEventListener );
	activeFiltersList?.insertAdjacentElement( 'beforeend', filter );

	activeFiltersSection?.classList.remove( 'hidden' );
};

const removeActiveFilter = ( label: string ) => {
	activeFilters().forEach( ( filter ) => {
		if ( filter.textContent?.trim() === label ) {
			filter.remove();
		}
	} );

	maybeHideFiltersSection();
};

export const toggleActiveFilter = (
	filterControl: HTMLElement,
	checkbox: HTMLInputElement
) => {
	const label = buildActiveFilterLabel( filterControl, checkbox );

	if ( checkbox.checked ) {
		addActiveFilter( label );
		return;
	}

	removeActiveFilter( label );
};

export const toggleSingleActiveFilter = ( key: string, value: string ) => {
	let needCreateNewFilter = true;

	activeFilters().forEach( ( filter ) => {
		const content = ( filter.textContent?.trim() || '' )
			.split( ':' )
			.map( ( item ) => item.trim() );
		const [ filterKey ] = content;

		if ( filterKey !== key ) {
			return;
		}

		const [ , filterValue ] = content;

		if ( value !== filterValue ) {
			filter.remove();
			maybeHideFiltersSection();

			return;
		}

		needCreateNewFilter = false;
	} );

	if ( value && needCreateNewFilter ) {
		addActiveFilter( `${ key }: ${ value }` );
	}
};

export const clearActiveFilters = () => {
	activeFilters().forEach( ( filter ) => {
		filter.dispatchEvent( new Event( 'click' ) );
	} );
	maybeHideFiltersSection();
};
