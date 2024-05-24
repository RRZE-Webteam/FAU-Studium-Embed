const LOADER_SELECTOR = '.loader';

let loader = document.querySelector< HTMLElement >( LOADER_SELECTOR );

const createLoader = (): HTMLElement => {
	const loaderHTML = `
        <div class="loader">
            <div class="loader-icon"></div>
        </div>
    `;
	document.body.insertAdjacentHTML( 'afterbegin', loaderHTML );

	return document.querySelector< HTMLElement >(
		LOADER_SELECTOR
	) as HTMLElement;
};

const getLoader = (): HTMLElement => {
	if ( ! loader ) {
		loader = createLoader();
	}
	return loader;
};

export const startLoader = () => {
	getLoader().classList.remove( 'hidden' );
};

export const stopLoader = () => {
	getLoader().classList.add( 'hidden' );
};
