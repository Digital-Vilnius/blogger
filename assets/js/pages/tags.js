import {Aside} from "../components/Aside";

const filter = new Aside('tags-filter');
const filterButtons = document.getElementsByClassName('tags-filter-toggle');

filterButtons.forEach((button) => {
    button.addEventListener('click', ($event) => {
        filter.show();
        $event.stopPropagation();
    });
});