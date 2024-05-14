import {
	ACCORDION_BUTTON_SELECTOR,
	ACCORDION_ITEM_SELECTOR,
	CONTENT_HEIGHT_VAR,
} from './constants';

const open = ( button: HTMLButtonElement ) => {
	if ( button.ariaExpanded === 'true' ) {
		return;
	}
	const content = document.getElementById(
		button.getAttribute( 'aria-controls' ) ?? ''
	);
	if ( ! ( content instanceof HTMLElement ) ) {
		return;
	}

	if ( ! content.style.getPropertyValue( CONTENT_HEIGHT_VAR ) ) {
		content.style.setProperty(
			CONTENT_HEIGHT_VAR,
			`${ content.scrollHeight }px`
		);
	}

	button.setAttribute( 'aria-expanded', 'true' );
	content.removeAttribute( 'hidden' );
};

const close = ( button: HTMLButtonElement ) => {
	if ( button.ariaExpanded === 'false' ) {
		return;
	}
	const content = document.getElementById(
		button.getAttribute( 'aria-controls' ) ?? ''
	);
	if ( ! ( content instanceof HTMLElement ) ) {
		return;
	}
	button.setAttribute( 'aria-expanded', 'false' );
	content.setAttribute( 'hidden', 'hidden' );
};

const onClickButton = ( button: HTMLButtonElement ) => {
	const accordionItem = button.closest( ACCORDION_ITEM_SELECTOR );
	if ( ! ( accordionItem instanceof HTMLElement ) ) {
		return;
	}
	const accordion = accordionItem.parentElement;
	if ( ! ( accordion instanceof HTMLElement ) ) {
		return;
	}

	if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
		close( button );
		return;
	}

	open( button );
	accordion
		.querySelectorAll< HTMLButtonElement >(
			`${ ACCORDION_BUTTON_SELECTOR }:not(#${ button.id })`
		)
		.forEach( close );
};

const initAccordion = () => {
	document
		.querySelectorAll< HTMLButtonElement >( ACCORDION_BUTTON_SELECTOR )
		.forEach( ( button: HTMLButtonElement ) => {
			button.addEventListener( 'click', ( e ) => {
				e.preventDefault();
				onClickButton( button );
			} );
		} );
};

initAccordion();
