import updateDegreePrograms, {
	currentLanguage,
} from '../degree-program-overview/degree-program-overview';
import { toggleSearchActiveFilter } from './input-handler';
import loadData from '../utils/data-fetcher';
import { DegreeProgramApiData } from '../degree-program-overview/degree-program';
import updateHeadersUrls, { ORDER_PARAMS } from '../order/order-updater';
import { startLoader, stopLoader } from './loader';

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
	const formSearchParams = new URLSearchParams( formData as any );

	const currentSearchParams = new URLSearchParams( window.location.search );
	ORDER_PARAMS.forEach( ( param ) => {
		const value = currentSearchParams.get( param );
		if ( value ) {
			formSearchParams.append( param, value );
		}
	} );

	sendForm( `?${ formSearchParams.toString() }` );
};

let timeout: ReturnType< typeof setTimeout > | null = null;

const sendForm = ( urlSearchParams: string = '' ) => {
	startLoader();

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

		loadData(
			`/fau/v1/degree-program${ urlSearchParams }`,
			currentLanguage
		)
			.then( ( data: DegreeProgramApiData[] ) => {
				updateDegreePrograms( data );
			} )
			.finally( () => {
				stopLoader();
			} );

		timeout = null;
	}, 500 );
};

export default submitForm;
