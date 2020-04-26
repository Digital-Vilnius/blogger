export class Aside {

    constructor(id) {
        this.filterElement = document.getElementById(id);
        this.overlayElement = document.getElementById('overlay');
        this.bodyElement = document.getElementsByTagName('body')[0];
        this.bodyElement.addEventListener('click', ($event) => {
            this.filterElement.classList.contains('visible') && !this.filterElement.contains($event.target) ? this.hide() : '';
        });
        this.filterElement.getElementsByClassName('close')[0].addEventListener('click', () => this.hide());
    }

    show() {
        if (!this.filterElement.classList.contains('visible')) {
            this.filterElement.classList.add('visible');
            this.overlayElement.classList.add('visible');
            this.bodyElement.classList.add('overlay-visible');
        }
    }

    hide() {
        if (this.filterElement.classList.contains('visible')) {
            this.filterElement.classList.remove('visible');
            this.overlayElement.classList.remove('visible');
            this.bodyElement.classList.remove('overlay-visible');
        }
    }
}