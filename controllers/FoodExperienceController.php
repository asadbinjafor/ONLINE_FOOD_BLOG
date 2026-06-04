<?php
class FoodExperienceController
{
    private FoodExperienceModel $model;
    private RestaurantModel $restaurants;
    private MenuItemModel $menu;

    public function __construct()
    {
        $this->model = new FoodExperienceModel();
        $this->restaurants = new RestaurantModel();
        $this->menu = new MenuItemModel();
    }

    public function index(): void
    {
        $posts = $this->model->allPosts();
        $commentsByPost = [];
        foreach ($posts as $p) {
            $commentsByPost[$p['id']] = $this->model->commentsForPost((int) $p['id']);
        }
        view('food_experience/index', [
            'title' => 'Food Experience',
            'posts' => $posts,
            'commentsByPost' => $commentsByPost,
        ]);
    }

    public function createForm(): void
    {
        Auth::requireRole('admin', 'member');
        view('food_experience/form', [
            'title' => 'Share Food Experience',
            'post' => null,
            'restaurants' => $this->restaurants->all(),
            'errors' => [],
        ]);
    }

    public function editForm(): void
    {
        Auth::requireRole('admin', 'member');
        $id = (int) ($_GET['id'] ?? 0);
        $post = $this->model->findPost($id);
        if (!$post || (int) $post['user_id'] !== Auth::user()['id']) {
            redirect('/food-experience');
        }
        view('food_experience/form', [
            'title' => 'Edit Post',
            'post' => $post,
            'restaurants' => $this->restaurants->all(),
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('admin', 'member');
        Security::requireCsrfPost();
        $data = $this->validatePost($_POST);
        if ($data['errors']) {
            view('food_experience/form', [
                'title' => 'Share Food Experience',
                'post' => null,
                'restaurants' => $this->restaurants->all(),
                'errors' => $data['errors'],
                'old' => $data['old'],
            ]);
            return;
        }
        $data['old']['user_id'] = Auth::user()['id'];
        $this->model->createPost($data['old']);
        flash('success', 'Experience shared!');
        redirect('/food-experience');
    }

    public function update(): void
    {
        Auth::requireRole('admin', 'member');
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $post = $this->model->findPost($id);
        if (!$post || (int) $post['user_id'] !== Auth::user()['id']) {
            redirect('/food-experience');
        }
        $data = $this->validatePost($_POST);
        if ($data['errors']) {
            view('food_experience/form', [
                'title' => 'Edit Post',
                'post' => $post,
                'restaurants' => $this->restaurants->all(),
                'errors' => $data['errors'],
            ]);
            return;
        }
        $this->model->updatePost($id, $data['old']);
        flash('success', 'Post updated.');
        redirect('/food-experience');
    }

    public function deletePost(): void
    {
        Auth::requireRole('admin', 'member');
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $post = $this->model->findPost($id);
        if ($post && (int) $post['user_id'] === Auth::user()['id']) {
            $this->model->deletePost($id);
            flash('success', 'Post deleted.');
        }
        redirect('/food-experience');
    }

    private function validatePost(array $post): array
    {
        $old = [
            'title' => trim($post['title'] ?? ''),
            'content' => trim($post['content'] ?? ''),
            'post_type' => in_array($post['post_type'] ?? '', POST_TYPES, true) ? $post['post_type'] : 'food',
            'restaurant_id' => (int) ($post['restaurant_id'] ?? 0) ?: null,
            'menu_item_id' => (int) ($post['menu_item_id'] ?? 0) ?: null,
        ];
        $errors = [];
        if ($old['title'] === '') {
            $errors['title'] = 'Title required.';
        }
        if ($old['content'] === '') {
            $errors['content'] = 'Content required.';
        }
        return ['errors' => $errors, 'old' => $old];
    }
}
