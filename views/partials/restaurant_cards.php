<?php if (empty($restaurants)): ?>
    <p class="muted">No restaurants found.</p>
<?php else: foreach ($restaurants as $r): ?>
    <article class="card restaurant-card">
        <h3><a href="<?= app_link('/restaurant', ['id' => (int) $r['id']]) ?>"><?= Security::e($r['name']) ?></a></h3>
        <p class="meta"><?= Security::e($r['location']) ?> · <?= Security::e($r['area']) ?></p>
        <p><?= Security::e(mb_strimwidth($r['short_background'], 0, 120, '…')) ?></p>
        <a class="link-arrow" href="<?= app_link('/restaurant', ['id' => (int) $r['id']]) ?>">View menu →</a>
    </article>
<?php endforeach; endif; ?>
