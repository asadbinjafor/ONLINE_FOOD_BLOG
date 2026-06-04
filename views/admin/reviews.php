<?php $pageScript = 'admin.js'; ?>
<h1>Food item reviews</h1>
<table class="data-table">
    <thead><tr><th>Item</th><th>Restaurant</th><th>User</th><th>Comment</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($reviews as $r): ?>
        <tr>
            <td><?= Security::e($r['item_name']) ?></td>
            <td><?= Security::e($r['restaurant_name']) ?></td>
            <td><?= Security::e($r['user_name']) ?></td>
            <td><?= Security::e(mb_strimwidth($r['comment'], 0, 80, '…')) ?></td>
            <td><button type="button" class="btn-link danger admin-delete-review" data-id="<?= (int) $r['id'] ?>">Remove</button></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
