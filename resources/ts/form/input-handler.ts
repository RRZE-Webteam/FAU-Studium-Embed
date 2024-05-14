import submitForm from './form-handler';
import isReducedMotion from '../common/reduced-motion-detection';

const init = () => {
	const INPUT_SELECTOR = '.c-degree-programs-sarchform__input';
	const INPUT_DELAY = 1500;
	const MIN_CHARACTERS = 3;

	const input = document.querySelector( INPUT_SELECTOR ) as HTMLInputElement;

	let timeout: ReturnType< typeof setTimeout > | null = null;

	input?.addEventListener( 'input', () => {
		if ( timeout ) {
			clearTimeout( timeout );
		}

		const inputValue = input.value.trim();

		if ( inputValue.length > MIN_CHARACTERS ) {
			timeout = setTimeout( () => {
				submitForm();
				timeout = null;
			}, INPUT_DELAY );
		}
	} );
};

if ( ! isReducedMotion() ) {
	init();
}
