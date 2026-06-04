<?php
class ApiController
{
    private RestaurantModel $restaurants;
    private ReviewModel $reviews;
    private RestaurantReviewModel $restaurantReviews;
    private MenuItemModel $menu;
    private FoodExperienceModel $foodExp;

    public function __construct()
    {
        $this->restaurants = new RestaurantModel();
        $this->reviews = new ReviewModel();
        $this->restaurantReviews = new RestaurantReviewModel();
        $this->menu = new MenuItemModel();
        $this->foodExp = new FoodExperienceModel();
    }

    public function search(): void
    {
        $q = trim($_GET['q'] ?? '');
        $location = trim($_GET['location'] ?? '') ?: null;
        $area = trim($_GET['area'] ?? '') ?: null;
        $min = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null;
        $max = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null;
        $data = $this->restaurants->searchCombined($q ?: null, $location, $area, $min, $max);
        Security::json(['ok' => true, 'restaurants' => $data['restaurants'], 'menu_items' => $data['menu_items']]);
    }

    public function reviewAdd(): void
    {
        Auth::requireMember();
        $menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        if (!$this->menu->find($menuItemId)) {
            Security::json(['ok' => false, 'error' => 'Menu item not found.'], 404);
        }
        if ($comment === '' || strlen($comment) > 2000) {
            Security::json(['ok' => false, 'error' => 'Comment required (max 2000 chars).'], 422);
        }
        $id = $this->reviews->create($menuItemId, Auth::user()['id'], $comment);
        Security::json(['ok' => true, 'id' => $id, 'message' => 'Review posted.']);
    }

    public function reviewDelete(): void
    {
        Auth::requireMember();
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $this->deleteOwnReview($id);
    }

    public function reviewDeleteById(): void
    {
        Auth::requireMember();
        $id = (int) ($_GET['id'] ?? 0);
        $this->deleteOwnReview($id);
    }

    private function deleteOwnReview(int $id): void
    {
        if (!$this->reviews->delete($id, Auth::user()['id'])) {
            Security::json(['ok' => false, 'error' => 'Review not found or not yours.'], 403);
        }
        Security::json(['ok' => true]);
    }

    public function restaurantReviewAdd(): void
    {
        Auth::requireMember();
        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 5);
        if (!$this->restaurants->find($restaurantId)) {
            Security::json(['ok' => false, 'error' => 'Restaurant not found.'], 404);
        }
        if ($comment === '') {
            Security::json(['ok' => false, 'error' => 'Comment required.'], 422);
        }
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }
        $id = $this->restaurantReviews->create($restaurantId, Auth::user()['id'], $comment, $rating);
        Security::json(['ok' => true, 'id' => $id]);
    }

    public function restaurantReviewDelete(): void
    {
        Auth::requireMember();
        $id = (int) ($_POST['id'] ?? 0);
        if (!$this->restaurantReviews->delete($id, Auth::user()['id'])) {
            Security::json(['ok' => false, 'error' => 'Not allowed.'], 403);
        }
        Security::json(['ok' => true]);
    }

    public function restaurantReviewDeleteById(): void
    {
        Auth::requireMember();
        $id = (int) ($_GET['id'] ?? 0);
        if (!$this->restaurantReviews->delete($id, Auth::user()['id'])) {
            Security::json(['ok' => false, 'error' => 'Not allowed.'], 403);
        }
        Security::json(['ok' => true]);
    }

    public function foodExpCommentAdd(): void
    {
        Auth::requireRole('admin', 'member');
        $postId = (int) ($_POST['post_id'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        if (!$this->foodExp->findPost($postId)) {
            Security::json(['ok' => false, 'error' => 'Post not found.'], 404);
        }
        if ($comment === '' || strlen($comment) > 2000) {
            Security::json(['ok' => false, 'error' => 'Comment required.'], 422);
        }
        $id = $this->foodExp->addComment($postId, Auth::user()['id'], $comment);
        Security::json(['ok' => true, 'id' => $id, 'user_name' => Auth::user()['name']]);
    }

    public function foodExpCommentDelete(): void
    {
        Auth::requireRole('admin', 'member');
        $id = (int) ($_POST['id'] ?? 0);
        $c = $this->foodExp->findComment($id);
        if (!$c || ((int) $c['user_id'] !== Auth::user()['id'] && Auth::user()['role'] !== 'admin')) {
            Security::json(['ok' => false, 'error' => 'Not allowed.'], 403);
        }
        $this->foodExp->deleteComment($id);
        Security::json(['ok' => true]);
    }

    public function foodExpPostDelete(): void
    {
        Auth::requireRole('admin', 'member');
        $id = (int) ($_POST['id'] ?? 0);
        $p = $this->foodExp->findPost($id);
        if (!$p || ((int) $p['user_id'] !== Auth::user()['id'] && Auth::user()['role'] !== 'admin')) {
            Security::json(['ok' => false, 'error' => 'Not allowed.'], 403);
        }
        $this->foodExp->deletePost($id);
        Security::json(['ok' => true]);
    }
}
