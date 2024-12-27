import submitForm from '../form/form-handler';
import isReducedMotion from '../common/reduced-motion-detection';

const DROPDOWN_SELECTOR = '.fau-dropdown';
const DROPDOWN_TOGGLE_SELECTOR = '.fau-dropdown__toggle';
const DROPDOWN_CONTENT_SELECTOR = '.fau-dropdown__content';
const CLICKAWAY_WINDOW_WIDTH_THRESHOLD = 768; // close on click away only works on screen sizes above this value

class Dropdown {
	private dropdown: HTMLElement;
	private toggle: HTMLElement;
	private content: HTMLElement;
	private checkboxes: HTMLInputElement[];
	private initialState: boolean[] = [];

	constructor( dropdown: HTMLElement ) {
		this.dropdown = dropdown;
		this.toggle = dropdown.querySelector( DROPDOWN_TOGGLE_SELECTOR )!;
		this.content = dropdown.querySelector( DROPDOWN_CONTENT_SELECTOR )!;
		this.checkboxes = Array.from(
			this.content.querySelectorAll( 'input[type="checkbox"]' )
		);

		this.init();
	}

	private recordInitialState() {
		this.initialState = this.checkboxes.map(
			( checkbox ) => checkbox.checked
		);
	}

	private hasStateChanged(): boolean {
		const currentState = this.checkboxes.map(
			( checkbox ) => checkbox.checked
		);

		return this.initialState.some(
			( value, index ) => value !== currentState[ index ]
		);
	}

	private close() {
		this.dropdown.setAttribute( 'aria-expanded', 'false' );

		if ( this.hasStateChanged() && isReducedMotion() ) {
			submitForm();
		}
	}

	private toggleDropdown() {
		const isAriaExpanded =
			this.dropdown.getAttribute( 'aria-expanded' ) === 'true';

		if ( ! isAriaExpanded ) {
			this.recordInitialState();
		}

		this.dropdown.setAttribute(
			'aria-expanded',
			isAriaExpanded ? 'false' : 'true'
		);

		if ( isAriaExpanded && this.hasStateChanged() && isReducedMotion() ) {
			submitForm();
		}
	}

	private handleBodyClick( event: MouseEvent ) {
		const target = event.target as Node;

		if ( this.toggle.contains( target ) ) {
			this.toggleDropdown();
			return;
		}

		if (
			this.dropdown.getAttribute( 'aria-expanded' ) === 'true' &&
			! this.dropdown.contains( target ) &&
			window.innerWidth > CLICKAWAY_WINDOW_WIDTH_THRESHOLD
		) {
			this.close();
		}
	}

	private init() {
		document.body.addEventListener(
			'click',
			this.handleBodyClick.bind( this )
		);
	}
}

const registerDropdowns = () => {
	document
		.querySelectorAll< HTMLElement >( DROPDOWN_SELECTOR )
		.forEach( ( dropdown ) => new Dropdown( dropdown ) );
};

registerDropdowns();
