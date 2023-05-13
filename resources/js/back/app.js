window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

// tags

if (document.querySelector('.--tags')) {

    const initRemoveTag = tag => {
        const tags = tag.closest('.--tags');
        tag.addEventListener('click', _ => {
            axios.put(tags.dataset.url, { tag: tag.dataset.id })
                .then(res => {
                    if (res.data.status == 'ok') {
                        tag.remove();
                    }
                });
        });
    }

    const insertTag = (tagList, res) => {
        const add = tagList.closest('.--add');
        const bin = tagList.closest('.--tags');
        const div = document.createElement('div');
        div.classList.add('tag');
        div.dataset.id = res.data.id;
        const title = document.createTextNode(res.data.tag);
        div.appendChild(title);
        const i = document.createElement('i');
        div.appendChild(i);
        bin.insertBefore(div, add);
        initRemoveTag(div);
    }

    const initTagList = tagList => {
        tagList.querySelectorAll('.--list--tag')
            .forEach(t => {
                t.addEventListener('click', _ => {
                    axios.put(tagList.dataset.url, { tag: t.dataset.id })
                        .then(res => {
                            if (res.data.status == 'ok') {
                                insertTag(tagList, res);
                            }
                        });
                });
            });
    }

    document.querySelectorAll('.--new')
        .forEach(b => {
            b.addEventListener('click', _ => {
                const i = b.closest('.--add').querySelector('.--add--new');
                axios.post(b.dataset.url, { tag: i.value })
                    .then(res => {
                        insertTag(b.closest('.--add').querySelector('.--tags--list'), res);
                    })
            });
        });

    document.querySelectorAll('.--add--new')
        .forEach(i => {
            i.addEventListener('input', e => {
                axios.get(e.target.dataset.url + '?t=' + e.target.value)
                    .then(res => {
                        i.closest('.--add').querySelector('.--tags--list').innerHTML = res.data.tags;
                        initTagList(i.closest('.--add').querySelector('.--tags--list'));
                    });
            });
            i.addEventListener('focus', e => {
                i.closest('.--add').querySelector('.--tags--list').style.display = 'block';
            });
            i.addEventListener('blur', e => {
                setTimeout(() => {
                    e.target.value = '';
                    i.closest('.--add').querySelector('.--tags--list').innerHTML = '';
                    i.closest('.--add').querySelector('.--tags--list').style.display = 'none';
                }, 200);
            });
        });

    document.querySelectorAll('.--tags')
        .forEach(tags => {
            tags.querySelectorAll('.--tag')
                .forEach(tag => {
                    initRemoveTag(tag)
                });
        });
}