const SELECTORS = ['#wpadminbar', '#headerwrapper'];
const CSS_VAR = '--fau-top-fixed-height';
const SEARCH_FILTERS_SELECTOR = '.c-search-filters';
const SHADOW_CLASSNAME = '-shadow';

const updateCssVar = () => {
    const fixedElementsHeight = SELECTORS.reduce((accumulator, selector) => {
        const element = document.querySelector(selector);

        if (!element) {
            return accumulator;
        }

        return accumulator + element.clientHeight;
    }, 0);

    document.body.style.setProperty(CSS_VAR, `${fixedElementsHeight}px`);
};

const toggleShadow = (entries: IntersectionObserverEntry[]) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add(SHADOW_CLASSNAME);
            return;
        }

        entry.target.classList.remove(SHADOW_CLASSNAME);
    });
};

const watchForFilterChanges = () => {
    document.querySelectorAll(SEARCH_FILTERS_SELECTOR).forEach((filtersStickyContainer) => {
        const offset = document.body.style.getPropertyValue(CSS_VAR);
        const offsetValue = parseInt(offset.replace('px', ''), 10);

        if (!offsetValue) {
            return;
        }

        const rootMargin = `0px 0px -${window.outerHeight - offsetValue}px`;

        const observer = new IntersectionObserver(toggleShadow, {
            rootMargin,
        });
        observer.observe(filtersStickyContainer);
    });
};

window.addEventListener('resize', updateCssVar);
window.addEventListener('DOMContentLoaded', updateCssVar);
window.addEventListener('DOMContentLoaded', watchForFilterChanges);
