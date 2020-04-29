const tagsInput = document.getElementById('post_tags');
const tagify = new Tagify(tagsInput);
tagify.on('input', onInput);
tagsInput.addEventListener('change', onChange);

function onInput(event) {
    const value = event.detail.value;
    tagify.settings.whitelist.length = 0;
    tagify.loading(true).dropdown.hide.call(tagify);

    fetch(`/api/blog/${blogId}/tags?keyword=${value}`)
        .then(response => response.json())
        .then((response) => {
            const {result, count} = response;
            const names = result.map(tag => tag.name);
            tagify.settings.whitelist.splice(0, count, ...names);
            tagify.loading(false).dropdown.show.call(tagify, value);
        })
}

function onChange(event) {
    const namesArray = JSON.parse(event.target.value);
    tagsInput.value = namesArray.map(name => name.value);
}