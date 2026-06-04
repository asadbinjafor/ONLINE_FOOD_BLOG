<?php $pageScript = 'food-exp.js'; ?>
<div class="admin-header">
    <h1>Food Experience</h1>
    <?php if (Auth::user()): ?>
        <a class="btn" href="<?= app_url('/food-experience/create') ?>">Share experience</a>
    <?php endif; ?>
</div>
<?php foreach ($posts as $p): ?>
    <article class="card blog-card" id="post-<?= (int) $p['id'] ?>">
        <h2><?= Security::e($p['title']) ?></h2>
        <p class="meta"><?= Security::e($p['author_name']) ?> · <?= Security::e($p['post_type']) ?> · <?= Security::e($p['created_at']) ?></p>
        <?php if ($p['restaurant_name']): ?><p class="meta">Restaurant: <?= Security::e($p['restaurant_name']) ?></p><?php endif; ?>
        <?php if ($p['menu_item_name']): ?><p class="meta">Dish: <?= Security::e($p['menu_item_name']) ?></p><?php endif; ?>
        <div class="blog-content"><?= nl2br(Security::e($p['content'])) ?></div>
        <?php if (Auth::user() && (int) $p['user_id'] === Auth::user()['id']): ?>
            <p>
                <a href="<?= app_link('/food-experience/edit', ['id' => (int) $p['id']]) ?>">Edit</a>
                <form method="post" action="<?= app_url('/food-experience/delete') ?>" class="inline-form" onsubmit="return confirm('Delete post?');">
                    <?= Security::csrfField() ?>
                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                    <button type="submit" class="btn-link danger">Delete</button>
                </form>
            </p>
        <?php elseif (isAdmin()): ?>
            <button type="button" class="btn-link danger admin-delete-food-post" data-id="<?= (int) $p['id'] ?>">Remove (admin)</button>
        <?php endif; ?>
        <section class="comments-block" data-post-id="<?= (int) $p['id'] ?>">
            <h3>Comments</h3>
            <?php foreach ($commentsByPost[$p['id']] ?? [] as $c): ?>
                <div class="comment-item" data-id="<?= (int) $c['id'] ?>">
                    <strong><?= Security::e($c['user_name']) ?></strong>
                    <p><?= Security::e($c['comment']) ?></p>
                    <?php if (Auth::user() && ((int) $c['user_id'] === Auth::user()['id'] || isAdmin())): ?>
                        <button type="button" class="btn-link delete-food-comment" data-id="<?= (int) $c['id'] ?>">Delete</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if (Auth::user()): ?>
                <form class="food-comment-form form-stack" data-post-id="<?= (int) $p['id'] ?>">
                    <textarea name="comment" required maxlength="2000" placeholder="Add a comment…"></textarea>
                    <button type="submit" class="btn btn-sm">Comment</button>
                </form>
            <?php endif; ?>
        </section>
    </article>
<?php endforeach; ?>
