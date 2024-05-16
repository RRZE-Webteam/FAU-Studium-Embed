const DEGREE_PROGRAMS_HEADER_SELECTOR =
	'a.c-degree-programs-collection__header-item';

const headers = document.querySelectorAll< HTMLLinkElement >(
	DEGREE_PROGRAMS_HEADER_SELECTOR
);

const updateHeadersUrls = ( urlParams: string ) => {
	headers.forEach( ( header ) => {
		const formParams = new URLSearchParams( urlParams );
		const [ , params ] = header.href.split( '?' );

		if ( ! params || ! urlParams ) {
			return;
		}
		const [ path ] = header.href.split( '?' );
		const headerParams = new URLSearchParams( params );

		headerParams.forEach( ( value, key ) => {
			if ( [ 'order', 'order_by' ].includes( key ) ) {
				formParams.append( key, value );
			}
		} );

		header.href = `${ path }?${ formParams.toString() }`;
	} );
};

export default updateHeadersUrls;
