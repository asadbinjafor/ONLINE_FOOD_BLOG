(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + p; };
    const routeLink = window.routeLink || function (p, q) {
        let u = routeUrl(p);
        if (q) u += (u.includes('?') ? '&' : '?') + new URLSearchParams(q).toString();
        return u;
    };
    const btn = document.getElementById('search-btn');
    const form = document.getElementById('search-form');
    if (!btn || !form) return;

    const renderRestaurants = (items) => {
        const el = document.getElementById('restaurant-results');
        if (!items.length) {
            el.innerHTML = '<p class="muted">No restaurants found.</p>';
            return;
        }
        el.innerHTML = items.map((r) => `
            <article class="card restaurant-card">
                <h3><a href="${routeLink('/restaurant', { id: r.id })}">${esc(r.name)}</a></h3>
                <p class="meta">${esc(r.location)} · ${esc(r.area)}</p>
                <p>${esc((r.short_background || '').slice(0, 120))}…</p>
                <a class="link-arrow" href="${routeLink('/restaurant', { id: r.id })}">View menu →</a>
            </article>`).join('');
    };

    const renderMenu = (items) => {
        const el = document.getElementById('menu-results');
        if (!items.length) {
            el.innerHTML = '<p class="muted">No menu items found.</p>';
            return;
        }
        el.innerHTML = items.map((m) => `
            <article class="card menu-card">
                <h3><a href="${routeLink('/menu-item', { id: m.id })}">${esc(m.name)}</a></h3>
                <p class="meta">${esc(m.restaurant_name || '')} · ৳${Number(m.price).toFixed(0)}</p>
                <p>${esc((m.description || '').slice(0, 90))}…</p>
            </article>`).join('');
    };

    const esc = (s) => {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    };

    const runSearch = async () => {
        const fd = new FormData(form);
        const params = new URLSearchParams();
        ['q', 'location', 'area', 'min_price', 'max_price'].forEach((k) => {
            const v = fd.get(k);
            if (v) params.set(k, v);
        });
        btn.disabled = true;
        btn.textContent = 'Searching…';
        try {
            const url = routeUrl('/api/search') + (params.toString() ? '&' + params : '');
            const res = await fetch(url);
            const data = await res.json();
            if (data.ok) {
                renderRestaurants(data.restaurants);
                renderMenu(data.menu_items);
            }
        } catch (e) {
            console.error(e);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Search';
        }
    };

    btn.addEventListener('click', runSearch);
})();
