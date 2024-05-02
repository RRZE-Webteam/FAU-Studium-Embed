const TOGGLES_SELECTOR =
	'.search-filter__output_modes .search-filter__output-mode-option';
const TOGGLE_ACTIVE_CLASSNAME = '-active';
const COLLECTION_SELECTOR = '.c-degree-programs-collection';
const LIST_VIEW_CLASSNAME = '-list';
const TILES_VIEW_CLASSNAME = '-tiles';

type OutputMode = 'list' | 'tiles';

const updateUrl = ( outputMode: OutputMode ) => {
	const url = new URL( window.location.href );
	url.searchParams.set( 'output', outputMode );
	window.history.pushState( { outputMode }, '', url );
};

const switchOutputMode = ( outputMode: OutputMode ) => {
	const collectionElements = document.querySelectorAll( COLLECTION_SELECTOR );

	if ( ! collectionElements.length ) {
		return;
	}

	document
		.querySelectorAll( TOGGLES_SELECTOR )
		.forEach( ( toggleElement ) => {
			const element = toggleElement as HTMLAnchorElement;
			element.classList.remove( TOGGLE_ACTIVE_CLASSNAME );

			if ( element.dataset.mode === outputMode ) {
				element.classList.add( TOGGLE_ACTIVE_CLASSNAME );
			}
		} );

	// Update view mode in all collection elements in case multiple overview outputs exist.
	collectionElements.forEach( ( collectionElement ) => {
		collectionElement.classList.remove(
			LIST_VIEW_CLASSNAME,
			TILES_VIEW_CLASSNAME
		);
		collectionElement.classList.add(
			outputMode === 'list' ? LIST_VIEW_CLASSNAME : TILES_VIEW_CLASSNAME
		);
	} );
};

document
	.querySelectorAll< HTMLAnchorElement >( TOGGLES_SELECTOR )
	.forEach( ( element ) => {
		element.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			const outputMode = element.dataset.mode as OutputMode | undefined;

			if ( ! outputMode ) {
				return;
			}

			switchOutputMode( outputMode );
			updateUrl( outputMode );
		} );
	} );

window.addEventListener( 'popstate', ( event ) => {
	const { outputMode } = event.state;

	if ( ! outputMode ) {
		return;
	}

	switchOutputMode( outputMode );
} );
