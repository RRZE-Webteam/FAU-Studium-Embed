import { _x } from '@wordpress/i18n';

import DegreeProgram, { DegreeProgramApiData } from './degree-program';

const DEGREE_PROGRAMS_SECTION_SELECTOR = '.c-degree-programs-search';
const DEGREE_PROGRAMS_OVERVIEW_SELECTOR = '.c-degree-programs-collection';
const SINGLE_PROGRAM_PREVIEW_SELECTOR = '.c-degree-program-preview';
const NO_SEARCH_RESULT_SELECTOR = '.c-no-search-results';

const degreeProgramsSection = document.querySelector< HTMLElement >(
	DEGREE_PROGRAMS_SECTION_SELECTOR
);
const degreeProgramsOverview =
	degreeProgramsSection?.querySelector< HTMLElement >(
		DEGREE_PROGRAMS_OVERVIEW_SELECTOR
	);

const isLocaleSwitched =
	degreeProgramsSection?.getAttribute( 'data-locale-switched' ) || '';

export const currentLanguage =
	degreeProgramsSection?.getAttribute( 'lang' )?.substring( 0, 2 ) || 'de';

const renderPrograms = ( programs: DegreeProgram[] ) => {
	const output = programs
		.map( ( program ) => program.render( isLocaleSwitched ) )
		.join( '' );
	degreeProgramsOverview?.insertAdjacentHTML( 'beforeend', output );
};

const renderNoResults = () => {
	const output = `
		<p class="c-no-search-results">
			${ _x(
				'No degree programs found',
				'frontoffice: Search results',
				'fau-degree-program-output'
			) }
		</p>`;
	degreeProgramsSection?.insertAdjacentHTML( 'beforeend', output );
};

const showNoResults = () => {
	const noResults = degreeProgramsSection?.querySelector(
		NO_SEARCH_RESULT_SELECTOR
	) as HTMLElement;

	if ( ! noResults ) {
		renderNoResults();

		return;
	}

	noResults.classList.remove( 'hidden' );
};

const hideNoResults = () => {
	const noResults = degreeProgramsSection?.querySelector(
		NO_SEARCH_RESULT_SELECTOR
	) as HTMLElement;

	if ( ! noResults ) {
		return;
	}

	noResults.classList.add( 'hidden' );
};

export const updateDegreeProgramOverviewDataset = (
	dataset: Record< string, any >
) => {
	if ( ! ( degreeProgramsOverview instanceof HTMLElement ) ) {
		return;
	}

	Object.entries( dataset ).forEach( ( [ property, value ] ) => {
		degreeProgramsOverview.dataset[ property ] = value;
	} );
};

export default ( data: DegreeProgramApiData[] ) => {
	degreeProgramsOverview?.setAttribute( 'aria-busy', 'true' );
	degreeProgramsOverview
		?.querySelectorAll( SINGLE_PROGRAM_PREVIEW_SELECTOR )
		?.forEach( ( element ) => element.remove() );

	const programs: DegreeProgram[] = data.map( ( programData ) =>
		DegreeProgram.createDegreeProgram( programData )
	);

	if ( ! programs.length ) {
		showNoResults();
		degreeProgramsOverview?.setAttribute( 'aria-busy', 'false' );
		return;
	}

	hideNoResults();
	renderPrograms( programs );
	degreeProgramsOverview?.setAttribute( 'aria-busy', 'false' );
};
