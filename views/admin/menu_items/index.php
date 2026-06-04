<div class="admin-header">
    <h1>Menu — <?= Security::e($restaurant['name']) ?></h1>
    <a class="btn" href="<?= app_link('/admin/menu-items/create', ['restaurant_id' => (int) $restaurant['id']]) ?>">Add item</a>
</div>
<a href="<?= app_url('/admin/restaurants') ?>">← Restaurants</a>
<table class="data-table">
    <thead><tr><th>Name</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($items as $m): ?>
        <tr>
            <td><?= Security::e($m['name']) ?></td>
            <td>৳<?= number_format((float) $m['price'], 0) ?></td>
            <td>
                <a href="<?= app_link('/admin/menu-items/edit', ['id' => (int) $m['id']]) ?>">Edit</a>
                <form method="post" action="<?= app_url('/admin/menu-items/delete') ?>" class="inline-form" onsubmit="return confirm('Delete this item?');">
                    <?= Security::csrfField() ?>
                    <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                    <button type="submit" class="btn-link danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
