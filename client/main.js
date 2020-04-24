async function getPosts() {
    let res = await fetch('http://restfullapinativephp/posts');
    let posts = await res.json();

    document.querySelector('.post-list').innerHTML = '';

    posts.forEach((post) => {
        document.querySelector('.post-list').innerHTML += `
            <div class="card col-3 mt-4 mx-3">    
                <div class="card-body">
                    <h5 class="card-title">${post.title}</h5>
                    <p class="card-text">${post.body}</p>
                </div>
                <a href="#" onclick="deletePost(${post.id})">Удалить</a>
                <a href="#" onclick="selectPost('${post.id}', '${post.title}', '${post.body}')">Редактировать</a>
            </div>
        `;
    });
}

async function addPost() {
    // убрать перезагрузку страницы не получилось методом preventDefault но страница сама перестала перегружаться.
    // event.preventDefault();
    const title = document.getElementById('title').value,
        body = document.getElementById('body').value;
    if (title == '' || body == '') {
        return;
    }

    let formData = new FormData();
    formData.append('title', title);
    formData.append('body', body);

    const post = await fetch('http://restfullapinativephp/post', {
        method: 'POST',
        body: formData
    });
    const data = await post.json();

    document.getElementById('postForm').reset();

    if (data.status == true) {
        getPosts();
    }
}

async function deletePost(id) {
    const del = await fetch(`http://restfullapinativephp/post/${id}`, {
        method: "DELETE"
    });
    const data = await del.json();

    if (data.status == true) {
        getPosts();
    }
}

async function selectPost(id, title, body) {
    document.getElementById('updatedId').value = id;
    document.getElementById('updateTitle').value = title;
    document.getElementById('updateBody').value = body;
}

async function updatePost() {
    const id = document.getElementById('updatedId').value,
        title = document.getElementById('updateTitle').value,
        body = document.getElementById('updateBody').value;
    if (title == '' || body == '') {
        return;
    }

    const data = {
        title: title,
        body: body
    };

    const update = await fetch(`http://restfullapinativephp/post/${id}`, {
        method: 'PATCH',
        body: JSON.stringify(data)
    });

    const resData = await update.json();

    document.getElementById('updateForm').reset();

    if (resData.status == true) {
        getPosts();
    }

}
getPosts();