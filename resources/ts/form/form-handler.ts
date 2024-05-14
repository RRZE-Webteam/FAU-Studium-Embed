const FORM_SELECTOR = '.c-degree-programs-search form';

const form = document.querySelector( FORM_SELECTOR ) as HTMLFormElement;

export default () => {
	form?.submit();
};
