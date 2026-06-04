<?php $pageScript = 'admin.js'; ?>
<h1>Food Experience moderation</h1>
<?php foreach ($posts as $p): ?>
    <article class="card blog-card">
        <h3><?= Security::e($p['title']) ?></h3>
        <p class="meta">by <?= Security::e($p['author_name']) ?> · <?= Security::e($p['post_type']) ?> · <?= Security::e($p['created_at']) ?></p>
        <p><?= Security::e(mb_strimwidth($p['content'], 0, 200, '…')) ?></p>
        <button type="button" class="btn-link danger admin-delete-food-post" data-id="<?= (int) $p['id'] ?>">Remove post</button>
    </article>
<?php endforeach; ?>
