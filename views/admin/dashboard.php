<h1>Admin Dashboard</h1>
<div class="stats-grid">
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['restaurants'] ?></span><span>Restaurants</span></div>
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['menu_items'] ?></span><span>Menu items</span></div>
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['reviews'] ?></span><span>Food reviews</span></div>
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['food_posts'] ?></span><span>Experience posts</span></div>
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['admins'] ?></span><span>Admins</span></div>
    <div class="stat-card"><span class="stat-num"><?= (int) $stats['members'] ?></span><span>Members</span></div>
</div>
<p class="admin-links">
    <a class="btn" href="<?= app_url('/admin/restaurants') ?>">Manage restaurants</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/members') ?>">Manage users</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/users/add') ?>">Add admin / user</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/reviews') ?>">Moderate reviews</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/food-experience') ?>">Food experience</a>
</p>
