const dropdownLists = document.getElementsByClassName('dropdown');

dropdownLists.forEach((dropdown) => {
    const dropdownToggles = dropdown.getElementsByClassName('dropdown-toggle');

    dropdownToggles.forEach((dropdownToggle) => {
        dropdownToggle.addEventListener('click', () => {
            dropdown.classList.contains('visible')
                ? dropdown.classList.remove('visible')
                : dropdown.classList.add('visible');
        });
    });
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.dropdown')) {
        dropdownLists.forEach((dropdown) => {
            dropdown.classList.remove('visible');
        });
    }
});