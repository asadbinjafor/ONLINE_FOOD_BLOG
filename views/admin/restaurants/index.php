<div class="admin-header">
    <h1>Restaurants</h1>
    <a class="btn" href="<?= app_url('/admin/restaurants/create') ?>">Add restaurant</a>
</div>
<?php if (!empty($success)): ?><div class="alert alert-success"><?= Security::e($success) ?></div><?php endif; ?>
<table class="data-table">
    <thead><tr><th>Name</th><th>Location</th><th>Area</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($restaurants as $r): ?>
        <tr>
            <td><?= Security::e($r['name']) ?></td>
            <td><?= Security::e($r['location']) ?></td>
            <td><?= Security::e($r['area']) ?></td>
            <td class="actions">
                <a href="<?= app_link('/admin/menu-items', ['restaurant_id' => (int) $r['id']]) ?>">Menu</a>
                <a href="<?= app_link('/admin/restaurants/edit', ['id' => (int) $r['id']]) ?>">Edit</a>
                <form method="post" action="<?= app_url('/admin/restaurants/delete') ?>" class="inline-form" onsubmit="return confirm('Delete restaurant and all menu items?');">
                    <?= Security::csrfField() ?>
                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                    <button type="submit" class="btn-link danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
