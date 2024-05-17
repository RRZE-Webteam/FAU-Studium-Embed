import updateDegreePrograms, {
	currentLanguage,
} from '../degree-program-overview/degree-program-overview';
import { toggleSearchActiveFilter } from './input-handler';
import loadData from '../utils/data-fetcher';
import { DegreeProgramApiData } from '../degree-program-overview/degree-program';
import updateHeadersUrls from '../order/order-updater';

const DEGREE_PROGRAMS_FORM_SELECTOR = '.c-degree-programs-search form';

export const form = document.querySelector< HTMLElement >(
	DEGREE_PROGRAMS_FORM_SELECTOR
) as HTMLFormElement;

form?.addEventListener( 'submit', ( e ) => {
	e.preventDefault();
	submitForm();
} );

const submitForm = () => {
	const formData = new FormData( form );
	sendForm( `?${ new URLSearchParams( formData as any ).toString() }` );
};

let timeout: ReturnType< typeof setTimeout > | null = null;

const sendForm = ( urlSearchParams: string = '' ) => {
	if ( timeout ) {
		clearTimeout( timeout );
	}

	timeout = setTimeout( () => {
		toggleSearchActiveFilter();
		history.replaceState(
			null,
			'',
			urlSearchParams || window.location.pathname
		);

		updateHeadersUrls( urlSearchParams );

		try {
			loadData(
				`/fau/v1/degree-program${ urlSearchParams }`,
				currentLanguage
			).then( ( data: DegreeProgramApiData[] ) => {
				updateDegreePrograms( data );
			} );
		} catch ( error ) {
			updateDegreePrograms( [] );
		}
		timeout = null;
	}, 500 );
};

export default submitForm;
