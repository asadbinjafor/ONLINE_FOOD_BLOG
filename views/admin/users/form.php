<div class="card form-card">
    <h1>Add User</h1>
    <p class="muted">Create a new admin or member account.</p>
    <form method="post" action="<?= app_url('/admin/users/add') ?>" class="form-stack" data-validate="register">
        <?= Security::csrfField() ?>
        <div class="field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" required value="<?= Security::e($old['name'] ?? '') ?>">
            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= Security::e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= Security::e($old['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?><span class="field-error"><?= Security::e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="role">Account type</label>
            <select id="role" name="role">
                <option value="member" <?= ($old['role'] ?? '') === 'member' ? 'selected' : '' ?>>Member (user)</option>
                <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <div class="field">
            <label for="password">Password (min 8 characters)</label>
            <input type="password" id="password" name="password" required minlength="8">
            <?php if (!empty($errors['password'])): ?><span class="field-error"><?= Security::e($errors['password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="password_confirm">Confirm password</label>
            <input type="password" id="password_confirm" name="password_confirm" required minlength="8">
            <?php if (!empty($errors['password_confirm'])): ?><span class="field-error"><?= Security::e($errors['password_confirm']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Create account</button>
        <a href="<?= app_url('/admin/members') ?>">Cancel</a>
    </form>
</div>
