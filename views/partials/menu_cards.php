<?php if (empty($menuItems)): ?>
    <p class="muted">No menu items found.</p>
<?php else: foreach ($menuItems as $m): ?>
    <article class="card menu-card">
        <?php if (!empty($m['image_path'])): ?>
            <img src="<?= MENU_UPLOAD_WEB ?>/<?= Security::e($m['image_path']) ?>" alt="" class="card-img">
        <?php endif; ?>
        <h3><a href="<?= app_link('/menu-item', ['id' => (int) $m['id']]) ?>"><?= Security::e($m['name']) ?></a></h3>
        <p class="meta"><?= Security::e($m['restaurant_name'] ?? '') ?> · ৳<?= number_format((float) $m['price'], 0) ?></p>
        <p><?= Security::e(mb_strimwidth($m['description'], 0, 90, '…')) ?></p>
    </article>
<?php endforeach; endif; ?>
