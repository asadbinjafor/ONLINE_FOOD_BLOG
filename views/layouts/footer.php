</main>
<footer class="site-footer">
    <div class="container footer-inner">
        <p>&copy; <?= date('Y') ?> <?= Security::e(APP_NAME) ?> — Discover restaurants, dishes &amp; food stories.</p>
    </div>
</footer>
<script>
window.APP_BASE = <?= json_encode(rtrim(BASE_URL, '/')) ?>;
window.routeUrl = function (path) {
    const index = window.APP_BASE + '/index.php';
    if (!path || path === '/') return index;
    return index + '?route=' + encodeURIComponent(path);
};
window.routeLink = function (path, params) {
    let url = window.routeUrl(path);
    if (params && Object.keys(params).length) {
        const qs = new URLSearchParams(params).toString();
        url += (url.includes('?') ? '&' : '?') + qs;
    }
    return url;
};
</script>
<script src="<?= BASE_URL ?>/public/js/app.js"></script>
<?php if (!empty($pageScript)): ?>
<script src="<?= BASE_URL ?>/public/js/<?= Security::e($pageScript) ?>"></script>
<?php endif; ?>
</body>
</html>
