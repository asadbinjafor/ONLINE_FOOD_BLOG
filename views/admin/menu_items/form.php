<?php $isEdit = !empty($item); ?>
<div class="card form-card">
    <h1><?= $isEdit ? 'Edit' : 'Add' ?> Menu Item</h1>
    <p class="muted">Restaurant: <?= Security::e($restaurant['name']) ?></p>
    <form method="post" enctype="multipart/form-data" action="<?= app_url($isEdit ? '/admin/menu-items/edit' : '/admin/menu-items/create') ?>" class="form-stack" data-validate="menu-item">
        <?= Security::csrfField() ?>
        <input type="hidden" name="restaurant_id" value="<?= (int) $restaurant['id'] ?>">
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><?php endif; ?>
        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required value="<?= Security::e($item['name'] ?? '') ?>">
            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= Security::e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" required rows="4"><?= Security::e($item['description'] ?? '') ?></textarea>
            <?php if (!empty($errors['description'])): ?><span class="field-error"><?= Security::e($errors['description']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="price">Price (৳)</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" required value="<?= Security::e((string) ($item['price'] ?? '')) ?>">
            <?php if (!empty($errors['price'])): ?><span class="field-error"><?= Security::e($errors['price']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="image">Image (JPEG/PNG, max 2MB)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png" <?= $isEdit ? '' : 'required' ?>>
            <?php if (!empty($item['image_path'])): ?>
                <img src="<?= MENU_UPLOAD_WEB ?>/<?= Security::e($item['image_path']) ?>" alt="" class="thumb">
            <?php endif; ?>
            <?php if (!empty($errors['image'])): ?><span class="field-error"><?= Security::e($errors['image']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Save</button>
    </form>
</div>
