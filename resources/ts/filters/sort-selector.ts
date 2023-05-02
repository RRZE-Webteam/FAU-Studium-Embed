const SELECTOR = '.c-sort-selector select';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(SELECTOR).forEach((selectElement) => {
        selectElement.addEventListener('change', () => {
            window.location.href = (selectElement as HTMLSelectElement).value;
        });
    });
});
