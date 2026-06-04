<?php $pageScript = 'reviews.js'; ?>
<article class="detail-header card">
    <?php if (!empty($item['image_path'])): ?>
        <img src="<?= MENU_UPLOAD_WEB ?>/<?= Security::e($item['image_path']) ?>" alt="" class="detail-img">
    <?php endif; ?>
    <h1><?= Security::e($item['name']) ?></h1>
    <p class="meta"><a href="<?= app_link('/restaurant', ['id' => (int) $item['restaurant_id']]) ?>"><?= Security::e($item['restaurant_name']) ?></a> · <?= Security::e($item['location']) ?></p>
    <p class="price-lg">৳<?= number_format((float) $item['price'], 0) ?></p>
    <p><?= Security::e($item['description']) ?></p>
</article>

<section class="section-block card" id="item-reviews" data-menu-item-id="<?= (int) $item['id'] ?>">
    <h2>Reviews</h2>
    <div id="review-list">
        <?php foreach ($reviews as $rv): ?>
            <div class="review-item" data-id="<?= (int) $rv['id'] ?>" data-user-id="<?= (int) $rv['user_id'] ?>">
                <strong><?= Security::e($rv['user_name']) ?></strong>
                <p><?= Security::e($rv['comment']) ?></p>
                <time class="muted"><?= Security::e($rv['created_at']) ?></time>
                <?php if (isMember() && (int) $rv['user_id'] === Auth::user()['id']): ?>
                    <button type="button" class="btn-link delete-review" data-id="<?= (int) $rv['id'] ?>">Delete</button>
                <?php elseif (isAdmin()): ?>
                    <button type="button" class="btn-link admin-delete-review" data-id="<?= (int) $rv['id'] ?>">Remove</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isMember()): ?>
        <form id="review-form" class="form-stack">
            <div class="field">
                <label>Your name</label>
                <input type="text" value="<?= Security::e(Auth::user()['name']) ?>" readonly>
            </div>
            <div class="field">
                <label for="comment">Your review</label>
                <textarea id="comment" name="comment" required maxlength="2000"></textarea>
            </div>
            <button type="submit" class="btn">Post review</button>
        </form>
    <?php elseif (!Auth::user()): ?>
        <p class="muted"><a href="<?= app_url('/login') ?>">Login</a> as a member to post a review.</p>
    <?php endif; ?>
</section>
