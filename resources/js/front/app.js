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
                `input.--tag--input[value="${tag.parentNode.textContent}"]`
                );
            tagInput.remove();
            tag.parentNode.remove();
        });
    }

    // insert tag into list
    const insertTag = res => {
        const tagList = document.querySelector('.--idea--tags');
        const tagInputsLits = document.querySelector('.--tags--inputs');
        const tagInput = document.querySelector('.--add--new');
        const listBox = document.querySelector('.--tags--list');
        tagInput.value = '';
        listBox.style.display = null;
        const div = document.createElement('div');
        div.classList.add('tag');
        const title = document.createTextNode(res);
        div.appendChild(title);
        const i = document.createElement('i');
        div.appendChild(i);
        tagList.appendChild(div);
        const input = document.createElement('input');
        input.name = 'tags[]';
        input.classList.add('--tag--input');
        input.type = 'hidden';
        input.value = res;
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
                    b.style.display = 'block';
                    b.innerHTML = res.data.tags;

                    //add tags from search to list
                    document.querySelectorAll('.--list--tag')
                    .forEach(t => {
                        t.addEventListener('click', _ => {
                            axios.put(b.dataset.url, { tag: t.dataset.id })
                                .then(res => {
                                    insertTag(res.data.tag);
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
                        insertTag(res.data.tag);
                    } else {
                        // FOR FUN do not keep this else statement
                        document.querySelector('.--idea--tags').innerHTML = '<div style="color: red;">AHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH</div>';
                    }
                })
        });

    // edit tags page listeners
    if (document.querySelector('.--edit--tags')) {
        const tags = document.querySelectorAll('.--tag');
        tags.forEach(t => {
            const i = t.querySelector('i');
            initRemoveTag(i);
        });
    }
}

//login modal display
if(document.querySelector('.--modal') && document.querySelector('.--submit--btn') && document.querySelector('button.btn-close[data-bs-dismiss]')){
    const modal = document.querySelector('.--modal');
    const submitBtn = document.querySelector('.--submit--btn');
    const close = document.querySelector('button.btn-close[data-bs-dismiss]');
    console.log(submitBtn);
    submitBtn.addEventListener('click', _=> {
        modal.style.display = 'block';
    });
    close.addEventListener('click', _=> {
        modal.style.display = null;
    });
}