<?php
class AdminController
{
    private RestaurantModel $restaurants;
    private MenuItemModel $menu;
    private ReviewModel $reviews;
    private FoodExperienceModel $foodExp;
    private UserModel $users;

    public function __construct()
    {
        $this->restaurants = new RestaurantModel();
        $this->menu = new MenuItemModel();
        $this->reviews = new ReviewModel();
        $this->foodExp = new FoodExperienceModel();
        $this->users = new UserModel();
    }

    public function dashboard(): void
    {
        Auth::requireAdmin();
        view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => [
                'restaurants' => $this->restaurants->count(),
                'menu_items' => $this->menu->count(),
                'reviews' => $this->reviews->count(),
                'food_posts' => $this->foodExp->countPosts(),
                'members' => $this->users->countMembers(),
                'admins' => $this->users->countAdmins(),
                'users' => $this->users->countUsers(),
            ],
        ]);
    }

    public function restaurants(): void
    {
        Auth::requireAdmin();
        view('admin/restaurants/index', [
            'title' => 'Manage Restaurants',
            'restaurants' => $this->restaurants->all(),
            'success' => flash('success'),
        ]);
    }

    public function restaurantCreateForm(): void
    {
        Auth::requireAdmin();
        view('admin/restaurants/form', ['title' => 'Add Restaurant', 'restaurant' => null, 'errors' => []]);
    }

    public function restaurantEditForm(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $restaurant = $this->restaurants->find($id);
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        view('admin/restaurants/form', ['title' => 'Edit Restaurant', 'restaurant' => $restaurant, 'errors' => []]);
    }

    public function restaurantStore(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $data = $this->validateRestaurant($_POST);
        if (!empty($data['errors'])) {
            view('admin/restaurants/form', ['title' => 'Add Restaurant', 'restaurant' => null, 'errors' => $data['errors'], 'old' => $data['old']]);
            return;
        }
        $this->restaurants->create($data['old']);
        flash('success', 'Restaurant created.');
        redirect('/admin/restaurants');
    }

    public function restaurantUpdate(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $restaurant = $this->restaurants->find($id);
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        $data = $this->validateRestaurant($_POST);
        if (!empty($data['errors'])) {
            view('admin/restaurants/form', ['title' => 'Edit Restaurant', 'restaurant' => $restaurant, 'errors' => $data['errors'], 'old' => $data['old']]);
            return;
        }
        $this->restaurants->update($id, $data['old']);
        flash('success', 'Restaurant updated.');
        redirect('/admin/restaurants');
    }

    public function restaurantDelete(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id) {
            $this->restaurants->delete($id);
            flash('success', 'Restaurant deleted.');
        }
        redirect('/admin/restaurants');
    }

    public function menuItems(): void
    {
        Auth::requireAdmin();
        $rid = (int) ($_GET['restaurant_id'] ?? 0);
        $restaurant = $this->restaurants->find($rid);
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        view('admin/menu_items/index', [
            'title' => 'Menu — ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'items' => $this->menu->byRestaurant($rid),
            'success' => flash('success'),
        ]);
    }

    public function menuItemCreateForm(): void
    {
        Auth::requireAdmin();
        $rid = (int) ($_GET['restaurant_id'] ?? 0);
        $restaurant = $this->restaurants->find($rid);
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        view('admin/menu_items/form', ['title' => 'Add Menu Item', 'restaurant' => $restaurant, 'item' => null, 'errors' => []]);
    }

    public function menuItemEditForm(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->menu->find($id);
        if (!$item) {
            redirect('/admin/restaurants');
        }
        $restaurant = $this->restaurants->find((int) $item['restaurant_id']);
        view('admin/menu_items/form', ['title' => 'Edit Menu Item', 'restaurant' => $restaurant, 'item' => $item, 'errors' => []]);
    }

    public function menuItemStore(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $rid = (int) ($_POST['restaurant_id'] ?? 0);
        $restaurant = $this->restaurants->find($rid);
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        $data = $this->validateMenuItem($_POST, $_FILES, true);
        if (!empty($data['errors'])) {
            view('admin/menu_items/form', ['title' => 'Add Menu Item', 'restaurant' => $restaurant, 'item' => null, 'errors' => $data['errors'], 'old' => $data['old']]);
            return;
        }
        $data['old']['restaurant_id'] = $rid;
        $this->menu->create($data['old']);
        flash('success', 'Menu item added.');
        redirect('/admin/menu-items?restaurant_id=' . $rid);
    }

    public function menuItemUpdate(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $item = $this->menu->find($id);
        if (!$item) {
            redirect('/admin/restaurants');
        }
        $restaurant = $this->restaurants->find((int) $item['restaurant_id']);
        $data = $this->validateMenuItem($_POST, $_FILES, false);
        if (!empty($data['errors'])) {
            view('admin/menu_items/form', ['title' => 'Edit Menu Item', 'restaurant' => $restaurant, 'item' => $item, 'errors' => $data['errors'], 'old' => $data['old']]);
            return;
        }
        $this->menu->update($id, $data['old']);
        flash('success', 'Menu item updated.');
        redirect('/admin/menu-items?restaurant_id=' . (int) $item['restaurant_id']);
    }

    public function menuItemDelete(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['id'] ?? 0);
        $item = $this->menu->find($id);
        if ($item) {
            $this->menu->delete($id);
            flash('success', 'Menu item deleted.');
            redirect('/admin/menu-items?restaurant_id=' . (int) $item['restaurant_id']);
        }
        redirect('/admin/restaurants');
    }

    public function members(): void
    {
        Auth::requireAdmin();
        view('admin/members', [
            'title' => 'Manage Users',
            'users' => $this->users->allUsers(),
            'success' => flash('success'),
        ]);
    }

    public function userAddForm(): void
    {
        Auth::requireAdmin();
        view('admin/users/form', [
            'title' => 'Add User',
            'errors' => [],
            'old' => ['role' => 'member'],
        ]);
    }

    public function userStore(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'member';
        if (!in_array($role, ['admin', 'member'], true)) {
            $role = 'member';
        }
        $errors = [];
        $old = compact('name', 'email', 'role');
        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        } elseif ($this->users->emailExists($email)) {
            $errors['email'] = 'Email already registered.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }
        if ($errors) {
            view('admin/users/form', ['title' => 'Add User', 'errors' => $errors, 'old' => $old]);
            return;
        }
        $this->users->create($name, $email, password_hash($password, PASSWORD_DEFAULT), $role);
        $label = $role === 'admin' ? 'Admin' : 'Member';
        flash('success', $label . ' account created successfully.');
        redirect('/admin/members');
    }

    public function reviews(): void
    {
        Auth::requireAdmin();
        view('admin/reviews', ['title' => 'Food Item Reviews', 'reviews' => $this->reviews->allWithMeta()]);
    }

    public function foodExperienceAdmin(): void
    {
        Auth::requireAdmin();
        view('admin/food_experience', [
            'title' => 'Food Experience Moderation',
            'posts' => $this->foodExp->allPosts(),
        ]);
    }

    private function validateRestaurant(array $post): array
    {
        $old = [
            'name' => trim($post['name'] ?? ''),
            'location' => trim($post['location'] ?? ''),
            'area' => trim($post['area'] ?? ''),
            'short_background' => trim($post['short_background'] ?? ''),
            'goals' => trim($post['goals'] ?? ''),
        ];
        $errors = [];
        foreach (['name', 'location', 'area', 'short_background', 'goals'] as $f) {
            if ($old[$f] === '') {
                $errors[$f] = 'This field is required.';
            }
        }
        return ['errors' => $errors, 'old' => $old];
    }

    private function validateMenuItem(array $post, array $files, bool $requireImage): array
    {
        $old = [
            'name' => trim($post['name'] ?? ''),
            'description' => trim($post['description'] ?? ''),
            'price' => (float) ($post['price'] ?? 0),
            'image_path' => null,
        ];
        $errors = [];
        if ($old['name'] === '') {
            $errors['name'] = 'Name is required.';
        }
        if ($old['description'] === '') {
            $errors['description'] = 'Description is required.';
        }
        if ($old['price'] <= 0) {
            $errors['price'] = 'Price must be greater than zero.';
        }
        $uploadErr = Security::validateImageUpload($files['image'] ?? ['error' => UPLOAD_ERR_NO_FILE], $requireImage);
        if ($uploadErr) {
            $errors['image'] = $uploadErr;
        } elseif (!empty($files['image']['name'])) {
            $saved = Security::saveUpload($files['image'], MENU_UPLOAD_DIR, 'menu');
            if ($saved) {
                $old['image_path'] = $saved;
            } else {
                $errors['image'] = 'Could not save image.';
            }
        }
        return ['errors' => $errors, 'old' => $old];
    }
}
