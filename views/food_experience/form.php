<div class="card form-card">
    <h1><?= empty($post) ? 'Share' : 'Edit' ?> Food Experience</h1>
    <form method="post" action="<?= app_url(empty($post) ? '/food-experience/create' : '/food-experience/edit') ?>" class="form-stack" data-validate="food-exp">
        <?= Security::csrfField() ?>
        <?php if (!empty($post)): ?><input type="hidden" name="id" value="<?= (int) $post['id'] ?>"><?php endif; ?>
        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required value="<?= Security::e($post['title'] ?? $old['title'] ?? '') ?>">
            <?php if (!empty($errors['title'])): ?><span class="field-error"><?= Security::e($errors['title']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="post_type">Type</label>
            <select id="post_type" name="post_type">
                <?php foreach (POST_TYPES as $t): ?>
                    <option value="<?= $t ?>" <?= ($post['post_type'] ?? 'food') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="restaurant_id">Link restaurant (optional)</label>
            <select id="restaurant_id" name="restaurant_id">
                <option value="">—</option>
                <?php foreach ($restaurants as $r): ?>
                    <option value="<?= (int) $r['id'] ?>" <?= (int)($post['restaurant_id'] ?? 0) === (int)$r['id'] ? 'selected' : '' ?>><?= Security::e($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="content">Your story</label>
            <textarea id="content" name="content" required rows="8"><?= Security::e($post['content'] ?? '') ?></textarea>
            <?php if (!empty($errors['content'])): ?><span class="field-error"><?= Security::e($errors['content']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Publish</button>
    </form>
</div>
