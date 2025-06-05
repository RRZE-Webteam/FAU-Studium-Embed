import { form } from '../form/form-handler';
import { clearActiveFilters } from './active-filters';

const CLEAR_FILTERS_SELECTOR = '.c-active-search-filters__clear-all-button';

const clearFilters = form?.querySelector< HTMLElement >(
	CLEAR_FILTERS_SELECTOR
);
clearFilters?.addEventListener( 'click', ( e ) => {
	e.preventDefault();
	clearActiveFilters();
} );
