const switchesInputs = document.getElementsByClassName('switch');

switchesInputs.forEach((switchInput) => {
    switchInput.addEventListener('click', () => {
        const input = switchInput.getElementsByTagName('input')[0];
        input.checked = !input.checked;
        input.dispatchEvent(new Event('input'));
    });
});