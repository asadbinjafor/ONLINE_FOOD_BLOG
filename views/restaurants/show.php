<?php $pageScript = 'reviews.js'; ?>
<article class="detail-header card">
    <h1><?= Security::e($restaurant['name']) ?></h1>
    <p class="meta"><?= Security::e($restaurant['location']) ?> · <?= Security::e($restaurant['area']) ?></p>
    <p><?= Security::e($restaurant['short_background']) ?></p>
    <p><strong>Goals:</strong> <?= Security::e($restaurant['goals']) ?></p>
</article>

<section class="section-block">
    <h2>Menu</h2>
    <div class="card-grid">
        <?php foreach ($menuItems as $m): ?>
            <article class="card menu-card">
                <?php if (!empty($m['image_path'])): ?>
                    <img src="<?= MENU_UPLOAD_WEB ?>/<?= Security::e($m['image_path']) ?>" alt="" class="card-img">
                <?php endif; ?>
                <h3><a href="<?= app_link('/menu-item', ['id' => (int) $m['id']]) ?>"><?= Security::e($m['name']) ?></a></h3>
                <p class="price">৳<?= number_format((float) $m['price'], 0) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-block card" id="restaurant-reviews">
    <h2>Restaurant reviews</h2>
    <div id="restaurant-review-list">
        <?php foreach ($reviews as $rv): ?>
            <div class="review-item" data-id="<?= (int) $rv['id'] ?>">
                <strong><?= Security::e($rv['user_name']) ?></strong>
                <span class="rating">★ <?= (int) $rv['rating'] ?></span>
                <p><?= Security::e($rv['comment']) ?></p>
                <time class="muted"><?= Security::e($rv['created_at']) ?></time>
                <?php if (isMember() && (int) $rv['user_id'] === Auth::user()['id']): ?>
                    <button type="button" class="btn-link delete-restaurant-review" data-id="<?= (int) $rv['id'] ?>">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isMember()): ?>
        <form id="restaurant-review-form" class="form-stack" data-restaurant-id="<?= (int) $restaurant['id'] ?>">
            <div class="field">
                <label>Your name</label>
                <input type="text" value="<?= Security::e(Auth::user()['name']) ?>" readonly>
            </div>
            <div class="field">
                <label for="rating">Rating (1–5)</label>
                <select id="rating" name="rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
                </select>
            </div>
            <div class="field">
                <label for="restaurant_comment">Comment</label>
                <textarea id="restaurant_comment" name="comment" required maxlength="2000"></textarea>
            </div>
            <button type="submit" class="btn">Post review</button>
        </form>
    <?php endif; ?>
</section>
