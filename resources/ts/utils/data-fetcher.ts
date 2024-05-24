import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

const loadData = async (
	url: string,
	lang: string,
	page: number = 1,
	perPage: number = 100
) => {
	const response: any = await apiFetch( {
		path: addQueryArgs( url, {
			page,
			per_page: perPage,
			lang,
		} ),
		parse: false,
	} );

	let data = await response.json();

	const totalPages = parseInt(
		response.headers.get( 'X-WP-TotalPages' ) ?? '1'
	);

	if ( page < totalPages ) {
		data = [ ...data, ...( await loadData( url, lang, ++page ) ) ];
	}

	return data;
};

export default loadData;
