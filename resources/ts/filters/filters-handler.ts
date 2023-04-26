const FILTER_SELECTOR = '.c-filter-control';

const addQueryParam = (key: string, value: string) => {
    const params = new URLSearchParams(window.location.search);
    const currentValue = params.get(key);

    if (currentValue) {
        const newValue = currentValue.split(',');
        newValue.push(value);
        params.set(key, newValue.join(','));
        window.location.search = params.toString();
        return;
    }

    params.set(key, value);
    window.location.search = params.toString();
};

const removeQueryParam = (key: string, value: string) => {
    const params = new URLSearchParams(window.location.search.toString());
    const currentValue = params.get(key);

    if (!currentValue) {
        return;
    }

    const newValue = currentValue.split(',').filter((id) => id !== value);

    if (!newValue.length) {
        params.delete(key);
        window.location.search = params.toString();
        return;
    }

    params.set(key, newValue.join(','));
    window.location.search = params.toString();
};

const filterChangeHandler = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const filterId = target.name;

    if (target.checked) {
        addQueryParam(filterId, target.value);
        return;
    }

    removeQueryParam(filterId, target.value);
};

document.querySelectorAll(FILTER_SELECTOR).forEach((filterControl) => {
    const checkboxes = filterControl.querySelectorAll<HTMLInputElement>('input[type=checkbox]');

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', filterChangeHandler);
    });
});
