import isReducedMotion from '../common/reduced-motion-detection';
import { clearInput, SEARCH_ACTIVE_FILTER_LABEL } from '../form/input-handler';
import { toggleActiveFilter } from './active-filters';
import updateFiltersCount from './filters-count';
import submitForm from '../form/form-handler';
import { updateDegreeProgramOverviewDataset } from '../degree-program-overview/degree-program-overview';

const FILTER_SELECTOR = '.c-filter-checkbox';
export const LANGUAGE_SKILLS_INPUT =
	'german-language-skills-for-international-students';
const EXTENDED_INPUT = 'extended';

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

let languageCertificateCheckedCheckboxes = 0;

filters.forEach( ( filterControl ) => {
	const checkbox = filterControl.querySelector< HTMLInputElement >(
		'input[type=checkbox]'
	);

	if (
		checkbox?.name.startsWith( LANGUAGE_SKILLS_INPUT ) &&
		checkbox?.checked
	) {
		languageCertificateCheckedCheckboxes++;
	}

	checkbox?.addEventListener( 'change', ( e ) => {
		if ( ! checkbox.name.startsWith( EXTENDED_INPUT ) ) {
			toggleActiveFilter( filterControl, checkbox );
			updateFiltersCount( checkbox );
		}

		if ( checkbox.name.startsWith( LANGUAGE_SKILLS_INPUT ) ) {
			languageCertificateCheckedCheckboxes += checkbox.checked ? 1 : -1;
			updateDegreeProgramOverviewDataset( {
				activeFilters:
					languageCertificateCheckedCheckboxes >= 1
						? LANGUAGE_SKILLS_INPUT
						: '',
			} );
		}

		if ( isReducedMotion() ) {
			return;
		}

		submitForm();
	} );
} );
