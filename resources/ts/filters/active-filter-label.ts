const ACCORDION_ITEM_TITLE_SELECTOR = '.c-accordion-item__title';
const ADVANCED_FILTER_SELECTOR = '.c-advanced-filter-item';
const FILTER_DROPDOWN_SELECTOR = '.c-filter-dropdown';
const FILTER_DROPDOWN_LABEL_SELECTOR = '.c-filter-dropdown__label';

const buildActiveFilterLabel = (
	filterControl: HTMLElement,
	checkbox: HTMLInputElement
) => {
	const labelText = filterControl
		.querySelector( 'span' )
		?.textContent?.trim();

	let filter = checkbox.closest( ADVANCED_FILTER_SELECTOR );
	let filterLabel;

	if ( filter ) {
		filterLabel = filter
			.querySelector( ACCORDION_ITEM_TITLE_SELECTOR )
			?.textContent?.trim();

		return `${ filterLabel }: ${ labelText }`;
	}

	filter = checkbox.closest( FILTER_DROPDOWN_SELECTOR );
	filterLabel = filter
		?.querySelector( FILTER_DROPDOWN_LABEL_SELECTOR )
		?.textContent?.trim();

	return `${ filterLabel }: ${ labelText }`;
};

export default buildActiveFilterLabel;
