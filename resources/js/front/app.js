window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//gallery
if (document.querySelector('.--add--gallery')) {
    let g = 0;
    document.querySelector('.--add--gallery')
        .addEventListener('click', _ => {
            const input = document.querySelector('[data-gallery="0"]').cloneNode(true);
            g++;
            input.dataset.gallery = g;
            input.querySelector('input').setAttribute('name', 'gallery[]');
            input.querySelector('span')
                .addEventListener('click', e => {
                    e.target.closest('.mb-3').remove();
                });
            document.querySelector('.gallery-inputs').append(input);
        });
}

//tags
if (document.querySelector('.--tags')) {

    // delete tag from list
    const initRemoveTag = tag => {
        tag.addEventListener('click', _ => {
            const tagInput = document.querySelector(
                `input.--tag--input[value="${tag.parentNode.textContent}"]`);
            tagInput.remove();
            tag.parentNode.remove();
            
        });
    }

    // insert tag into list
    const insertTag = res => {
        const div = document.createElement('div');
        const tagList = document.querySelector('.--idea--tags');
        const tagInputsLits = document.querySelector('.--tags--inputs');
        div.classList.add('tag');
        div.dataset.id = res.data.id;
        const title = document.createTextNode(res.data.tag);
        div.appendChild(title);
        const i = document.createElement('i');
        div.appendChild(i);
        tagList.appendChild(div);
        const input = document.createElement('input');
        input.name = 'tags[]';
        input.classList.add('--tag--input');
        input.type = 'hidden';
        input.value = res.data.tag;
        tagInputsLits.appendChild(input);
        initRemoveTag(i);
    }

    // add tags input search
    document.querySelector('.--add--new')
        .addEventListener('input', e => {
            axios.get(e.target.dataset.url + '?t=' + e.target.value)
                .then(res => {
                    const b = document.querySelector('.--tags--list');
                    // show list of found tags
                    b.innerHTML = res.data.tags;

                    //add tags from search to list
                    document.querySelectorAll('.--list--tag')
                    .forEach(t => {
                        t.addEventListener('click', _ => {
                            axios.put(b.dataset.url, { tag: t.dataset.id })
                                .then(res => {
                                    insertTag(res);
                                })
                        });
                    });
                });
        });

    // add tag button press
    document.querySelector('.--create--add--tag')
        .addEventListener('click', _ => {
            const i = document.querySelector('.--add--new');
            const b = document.querySelector('.--create--add--tag');
            axios.post(b.dataset.url, { tag: i.value })
                .then(res => {
                    if (res.data.status == 'ok') {
                        insertTag(res);
                    } else {
                        // FOR FUN do not keep this else statement
                        document.querySelector('.--idea--tags').innerHTML = '<div style="color: red;">AHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH</div>';
                    }
                })
        });
}