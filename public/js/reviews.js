(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + p; };
    const reviewSection = document.getElementById('item-reviews');
    if (reviewSection) {
        const menuItemId = reviewSection.dataset.menuItemId;
        const form = document.getElementById('review-form');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const comment = form.querySelector('#comment').value.trim();
                if (!comment) return alert('Comment required.');
                const body = new FormData();
                body.append('menu_item_id', menuItemId);
                body.append('comment', comment);
                const res = await fetch(routeUrl('/api/reviews/add'), { method: 'POST', body });
                const data = await res.json();
                if (data.ok) location.reload();
                else alert(data.error || 'Failed');
            });
        }
        document.querySelectorAll('.delete-review').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!confirm('Delete your review?')) return;
                const res = await fetch(routeUrl('/api/reviews/delete'), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + btn.dataset.id,
                });
                const data = await res.json();
                if (data.ok) btn.closest('.review-item').remove();
            });
        });
        document.querySelectorAll('.admin-delete-review').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!confirm('Remove this review?')) return;
                const body = new FormData();
                body.append('id', btn.dataset.id);
                const res = await fetch(routeUrl('/api/admin/review/delete'), { method: 'POST', body });
                const data = await res.json();
                if (data.ok) btn.closest('.review-item').remove();
            });
        });
    }

    const restForm = document.getElementById('restaurant-review-form');
    if (restForm) {
        restForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const body = new FormData();
            body.append('restaurant_id', restForm.dataset.restaurantId);
            body.append('comment', restForm.querySelector('#restaurant_comment').value.trim());
            body.append('rating', restForm.querySelector('#rating').value);
            const res = await fetch(routeUrl('/api/restaurant-reviews/add'), { method: 'POST', body });
            const data = await res.json();
            if (data.ok) location.reload();
            else alert(data.error || 'Failed');
        });
        document.querySelectorAll('.delete-restaurant-review').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const res = await fetch(routeUrl('/api/restaurant-reviews/delete'), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + btn.dataset.id,
                });
                const data = await res.json();
                if (data.ok) btn.closest('.review-item').remove();
            });
        });
    }
})();
