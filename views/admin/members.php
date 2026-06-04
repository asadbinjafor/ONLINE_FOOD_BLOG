<?php $pageScript = 'admin.js'; ?>
<div class="admin-header">
    <h1>Manage Users</h1>
    <a class="btn" href="<?= app_url('/admin/users/add') ?>">+ Add admin / user</a>
</div>
<?php if (!empty($success)): ?><div class="alert alert-success"><?= Security::e($success) ?></div><?php endif; ?>
<table class="data-table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= Security::e($u['name']) ?></td>
            <td><?= Security::e($u['email']) ?></td>
            <td><span class="role-badge role-<?= Security::e($u['role']) ?>"><?= Security::e(ucfirst($u['role'])) ?></span></td>
            <td><?= Security::e($u['created_at']) ?></td>
            <td>
                <?php if ($u['role'] === 'member'): ?>
                    <button type="button" class="btn-link danger admin-delete-member" data-id="<?= (int) $u['id'] ?>">Remove</button>
                <?php elseif ((int) $u['id'] === (Auth::user()['id'] ?? 0)): ?>
                    <span class="muted">You</span>
                <?php else: ?>
                    <span class="muted">—</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
