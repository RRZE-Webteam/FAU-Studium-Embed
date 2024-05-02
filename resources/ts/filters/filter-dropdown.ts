const DROPDOWN_SELECTOR = '.fau-dropdown';
const DROPDOWN_TOGGLE_SELECTOR = '.fau-dropdown__toggle';
const DROPDOWN_CONTENT_SELECTOR = '.fau-dropdown__content';
const CLICKAWAY_WINDOW_WIDTH_THRESHOLD = 768; // close on click away only works on screen sizes above this value

const closeDropDown = ( dropdown: HTMLElement ) => {
	dropdown.setAttribute( 'aria-expanded', 'false' );
};

const toggleDropdown = ( dropdown: HTMLElement ) => {
	dropdown.setAttribute(
		'aria-expanded',
		dropdown.getAttribute( 'aria-expanded' ) === 'true' ? 'false' : 'true'
	);
};

const registerClickListeners = () => {
	document
		.querySelectorAll< HTMLElement >( DROPDOWN_SELECTOR )
		.forEach( ( dropdown ) => {
			const toggle = dropdown.querySelector( DROPDOWN_TOGGLE_SELECTOR );
			const content = dropdown.querySelector( DROPDOWN_CONTENT_SELECTOR );

			if ( ! toggle || ! content ) {
				return;
			}

			document.body.addEventListener( 'click', ( event ) => {
				if ( toggle.contains( event.target as Node ) ) {
					toggleDropdown( dropdown );
					return;
				}

				if (
					dropdown.getAttribute( 'aria-expanded' ) === 'true' &&
					! dropdown.contains( event.target as Node ) &&
					window.innerWidth > CLICKAWAY_WINDOW_WIDTH_THRESHOLD
				) {
					closeDropDown( dropdown );
				}
			} );
		} );
};

registerClickListeners();
