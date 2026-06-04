<?php $pageScript = 'search.js'; ?>
<section class="hero">
    <div class="hero-content">
        <h1>Discover flavors across the city</h1>
        <?php if ($isVisitor): ?>
            <p class="lead">Browse restaurants and dishes. <a href="<?= app_url('/register') ?>">Join free</a> to post reviews and share your food experiences.</p>
            <div class="hero-actions">
                <a class="btn" href="<?= app_url('/register') ?>">Get started</a>
                <a class="btn btn-outline" href="<?= app_url('/login') ?>">Login</a>
            </div>
        <?php else: ?>
            <p class="lead">Search restaurants, filter by area, and explore menus curated for food lovers.</p>
        <?php endif; ?>
    </div>
</section>

<section class="search-panel card">
    <h2>Find restaurants &amp; dishes</h2>
    <form id="search-form" class="search-grid" onsubmit="return false;">
        <div class="field">
            <label for="q">Search</label>
            <input type="search" id="q" name="q" placeholder="Restaurant or dish name">
        </div>
        <div class="field">
            <label for="location">Location</label>
            <select id="location" name="location">
                <option value="">All cities</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= Security::e($loc) ?>"><?= Security::e($loc) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="area">Area</label>
            <select id="area" name="area">
                <option value="">All areas</option>
                <?php foreach ($areas as $a): ?>
                    <option value="<?= Security::e($a) ?>"><?= Security::e($a) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="min_price">Min price (৳)</label>
            <input type="number" id="min_price" name="min_price" min="0" step="10">
        </div>
        <div class="field">
            <label for="max_price">Max price (৳)</label>
            <input type="number" id="max_price" name="max_price" min="0" step="10">
        </div>
        <button type="button" class="btn" id="search-btn">Search</button>
    </form>
</section>

<div id="search-results">
    <section class="section-block">
        <h2>Restaurants</h2>
        <div class="card-grid" id="restaurant-results">
            <?php require ROOT_DIR . '/views/partials/restaurant_cards.php'; ?>
        </div>
    </section>
    <section class="section-block">
        <h2>Menu items</h2>
        <div class="card-grid" id="menu-results">
            <?php $menuItems = $menuItems; require ROOT_DIR . '/views/partials/menu_cards.php'; ?>
        </div>
    </section>
</div>

<?php if ($isVisitor && !empty($featuredRestaurants)): ?>
<section class="section-block">
    <h2>Featured spots</h2>
    <div class="card-grid">
        <?php $restaurants = $featuredRestaurants; require ROOT_DIR . '/views/partials/restaurant_cards.php'; ?>
    </div>
</section>
<?php endif; ?>
