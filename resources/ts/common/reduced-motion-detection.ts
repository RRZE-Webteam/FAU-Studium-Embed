export default (): boolean => {
	return window.matchMedia( '(prefers-reduced-motion)' ).matches;
};
