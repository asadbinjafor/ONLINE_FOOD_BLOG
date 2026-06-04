<?php
class HomeController
{
    private RestaurantModel $restaurants;
    private MenuItemModel $menu;

    public function __construct()
    {
        $this->restaurants = new RestaurantModel();
        $this->menu = new MenuItemModel();
    }

    public function index(): void
    {
        $user = Auth::user();
        $locations = $this->restaurants->distinctLocations();
        $areas = $this->restaurants->distinctAreas();
        $data = $this->restaurants->searchCombined(null, null, null, null, null);

        view('home/index', [
            'title' => APP_NAME,
            'isVisitor' => !$user,
            'locations' => $locations,
            'areas' => $areas,
            'restaurants' => $data['restaurants'],
            'menuItems' => $data['menu_items'],
            'featuredRestaurants' => array_slice($data['restaurants'], 0, 3),
        ]);
    }
}
