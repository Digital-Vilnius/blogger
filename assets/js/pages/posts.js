import {Aside} from "../components/Aside";

const filter = new Aside('posts-filter');
const filterButtons = document.getElementsByClassName('posts-filter-toggle');

filterButtons.forEach((button) => {
    button.addEventListener('click', ($event) => {
        filter.show();
        $event.stopPropagation();
    });
});