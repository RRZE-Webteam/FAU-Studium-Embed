const DEGREE_PROGRAMS_HEADER_SELECTOR =
	'a.c-degree-programs-collection__header-item';
export const ORDER_PARAMS = [ 'order', 'order_by' ];

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
			if ( ORDER_PARAMS.includes( key ) ) {
				formParams.set( key, value );
			}
		} );

		header.href = `${ path }?${ formParams.toString() }`;
	} );
};

export default updateHeadersUrls;
