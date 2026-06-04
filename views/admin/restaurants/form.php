<?php $isEdit = !empty($restaurant); $old = $old ?? $restaurant ?? []; ?>
<div class="card form-card">
    <h1><?= $isEdit ? 'Edit' : 'Add' ?> Restaurant</h1>
    <form method="post" action="<?= app_url($isEdit ? '/admin/restaurants/edit' : '/admin/restaurants/create') ?>" class="form-stack" data-validate="restaurant">
        <?= Security::csrfField() ?>
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int) $restaurant['id'] ?>"><?php endif; ?>
        <?php foreach (['name','location','area'] as $f): ?>
        <div class="field">
            <label for="<?= $f ?>"><?= ucfirst(str_replace('_',' ',$f)) ?></label>
            <input type="text" id="<?= $f ?>" name="<?= $f ?>" required value="<?= Security::e($old[$f] ?? '') ?>">
            <?php if (!empty($errors[$f])): ?><span class="field-error"><?= Security::e($errors[$f]) ?></span><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <div class="field">
            <label for="short_background">Short background</label>
            <textarea id="short_background" name="short_background" required rows="3"><?= Security::e($old['short_background'] ?? '') ?></textarea>
            <?php if (!empty($errors['short_background'])): ?><span class="field-error"><?= Security::e($errors['short_background']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="goals">Goals</label>
            <textarea id="goals" name="goals" required rows="2"><?= Security::e($old['goals'] ?? '') ?></textarea>
            <?php if (!empty($errors['goals'])): ?><span class="field-error"><?= Security::e($errors['goals']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Save</button>
        <a href="<?= app_url('/admin/restaurants') ?>">Cancel</a>
    </form>
</div>
