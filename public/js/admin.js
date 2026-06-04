(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + p; };
    const post = async (path, id) => {
        const body = new FormData();
        body.append('id', id);
        const res = await fetch(routeUrl(path), { method: 'POST', body });
        return res.json();
    };
    document.querySelectorAll('.admin-delete-member').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!confirm('Remove member and all their content?')) return;
            const data = await post('/api/admin/member/delete', btn.dataset.id);
            if (data.ok) btn.closest('tr').remove();
            else alert(data.error || 'Failed');
        });
    });
    document.querySelectorAll('.admin-delete-review').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!confirm('Remove review?')) return;
            const data = await post('/api/admin/review/delete', btn.dataset.id);
            if (data.ok) btn.closest('tr').remove();
        });
    });
    document.querySelectorAll('.admin-delete-food-post').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!confirm('Remove post?')) return;
            const data = await post('/api/admin/food-post/delete', btn.dataset.id);
            if (data.ok) {
                const card = btn.closest('.blog-card') || btn.closest('article');
                if (card) card.remove();
            }
        });
    });
})();
