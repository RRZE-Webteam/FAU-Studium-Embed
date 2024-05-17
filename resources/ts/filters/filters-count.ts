const FILTER_DROPDOWN_SELECTOR = '.c-filter-dropdown';
const FILTER_DROPDOWN_LABEL_SELECTOR = '.c-filter-dropdown__label';
const FILTER_COUNT_CLASS = 'c-filter-dropdown__count';
const FILTER_COUNT_SELECTOR = `.${ FILTER_COUNT_CLASS }`;

const updateFiltersCount = ( element: HTMLInputElement ) => {
	const filter = element.closest< HTMLElement >( FILTER_DROPDOWN_SELECTOR );
	const filterLabel = filter?.querySelector< HTMLElement >(
		FILTER_DROPDOWN_LABEL_SELECTOR
	);

	if ( ! filter || ! filterLabel ) {
		return;
	}

	let count = filter.querySelector< HTMLElement >( FILTER_COUNT_SELECTOR );

	if ( ! count ) {
		count = document.createElement( 'span' );
		count.className = FILTER_COUNT_CLASS;
		filterLabel.insertAdjacentElement( 'afterend', count );
	}

	let value = Number( count.textContent?.trim() || '0' );
	value += element.checked ? 1 : -1;

	if ( value < 1 ) {
		count.remove();
		return;
	}

	count.textContent = String( value );
};

export default updateFiltersCount;
