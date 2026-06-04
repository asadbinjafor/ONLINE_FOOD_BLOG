(function () {
    const toggle = document.querySelector('[data-nav-toggle]');
    const links = document.querySelector('[data-nav-links]');
    if (toggle && links) {
        toggle.addEventListener('click', () => links.classList.toggle('is-open'));
    }

    document.querySelectorAll('[data-validate]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const type = form.dataset.validate;
            let ok = true;
            const show = (id, msg) => {
                let el = form.querySelector('[data-client-error="' + id + '"]');
                if (!el) {
                    el = document.createElement('span');
                    el.className = 'field-error';
                    el.dataset.clientError = id;
                    const field = form.querySelector('#' + id) || form.querySelector('[name="' + id + '"]');
                    if (field && field.parentNode) field.parentNode.appendChild(el);
                }
                el.textContent = msg;
                ok = false;
            };
            form.querySelectorAll('[data-client-error]').forEach((n) => n.remove());

            if (type === 'register' || type === 'profile') {
                const p = form.querySelector('[name="password"]') || form.querySelector('[name="new_password"]');
                const c = form.querySelector('[name="password_confirm"]') || form.querySelector('[name="new_password_confirm"]');
                if (p && c && p.value && p.value !== c.value) {
                    show(c.id || 'password_confirm', 'Passwords do not match.');
                }
                if (p && p.value && p.value.length < 8) {
                    show(p.id || 'password', 'At least 8 characters.');
                }
            }
            const email = form.querySelector('[type="email"]');
            if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                show(email.id || 'email', 'Invalid email.');
            }
            if (type === 'menu-item') {
                const price = form.querySelector('[name="price"]');
                if (price && parseFloat(price.value) <= 0) show('price', 'Price must be positive.');
            }
            if (!ok) e.preventDefault();
        });
    });
})();
