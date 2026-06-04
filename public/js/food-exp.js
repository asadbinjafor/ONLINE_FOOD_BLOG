(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + p; };
    document.querySelectorAll('.food-comment-form').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const postId = form.dataset.postId;
            const comment = form.querySelector('textarea').value.trim();
            if (!comment) return alert('Comment required.');
            const body = new FormData();
            body.append('post_id', postId);
            body.append('comment', comment);
            const res = await fetch(routeUrl('/api/food-exp/comments/add'), { method: 'POST', body });
            const data = await res.json();
            if (data.ok) location.reload();
            else alert(data.error || 'Failed');
        });
    });
    document.querySelectorAll('.delete-food-comment').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const body = new FormData();
            body.append('id', btn.dataset.id);
            const res = await fetch(routeUrl('/api/food-exp/comments/delete'), { method: 'POST', body });
            const data = await res.json();
            if (data.ok) btn.closest('.comment-item').remove();
        });
    });
})();
