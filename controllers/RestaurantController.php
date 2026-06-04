<?php
class RestaurantController
{
    private RestaurantModel $restaurants;
    private MenuItemModel $menu;
    private RestaurantReviewModel $reviews;

    public function __construct()
    {
        $this->restaurants = new RestaurantModel();
        $this->menu = new MenuItemModel();
        $this->reviews = new RestaurantReviewModel();
    }

    public function index(): void
    {
        $list = $this->restaurants->all();
        view('restaurants/index', [
            'title' => 'Restaurants',
            'restaurants' => $list,
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $restaurant = $this->restaurants->find($id);
        if (!$restaurant) {
            http_response_code(404);
            view('errors/404', ['title' => 'Not Found']);
            return;
        }
        view('restaurants/show', [
            'title' => $restaurant['name'],
            'restaurant' => $restaurant,
            'menuItems' => $this->menu->byRestaurant($id),
            'reviews' => $this->reviews->byRestaurant($id),
        ]);
    }
}
