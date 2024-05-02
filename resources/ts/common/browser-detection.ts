const isSafariBrowser = () =>
	navigator.userAgent.indexOf( 'Safari' ) > -1 &&
	navigator.userAgent.indexOf( 'Chrome' ) <= -1;

if ( isSafariBrowser() ) {
	document.documentElement.classList.add( 'is-safari' );
}
