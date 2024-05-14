import submitForm from '../form/form-handler';
import isReducedMotion from '../common/reduced-motion-detection';

const FILTER_SELECTOR = '.c-filter-checkbox';

document.querySelectorAll( FILTER_SELECTOR ).forEach( ( filterControl ) => {
	if ( isReducedMotion() ) {
		return;
	}

	const checkboxes = filterControl.querySelectorAll< HTMLInputElement >(
		'input[type=checkbox]'
	);

	checkboxes.forEach( ( checkbox ) => {
		checkbox.addEventListener( 'change', submitForm );
	} );
} );
