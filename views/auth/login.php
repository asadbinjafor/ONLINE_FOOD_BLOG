<div class="auth-wrap card">
    <h1>Login</h1>
    <?php if (!empty($errors['general'])): ?><p class="field-error"><?= Security::e($errors['general']) ?></p><?php endif; ?>
    <form method="post" action="<?= app_url('/login') ?>" class="form-stack" data-validate="login">
        <?= Security::csrfField() ?>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= Security::e($old['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?><span class="field-error"><?= Security::e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="8">
            <?php if (!empty($errors['password'])): ?><span class="field-error"><?= Security::e($errors['password']) ?></span><?php endif; ?>
        </div>
        <label class="checkbox"><input type="checkbox" name="remember_me" value="1"> Remember me (30 days)</label>
        <button type="submit" class="btn">Login</button>
    </form>
    <p class="form-footer">No account? <a href="<?= app_url('/register') ?>">Register</a></p>
</div>
