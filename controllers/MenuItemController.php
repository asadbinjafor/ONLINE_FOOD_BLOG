<?php
class MenuItemController
{
    private MenuItemModel $menu;
    private ReviewModel $reviews;

    public function __construct()
    {
        $this->menu = new MenuItemModel();
        $this->reviews = new ReviewModel();
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->menu->find($id);
        if (!$item) {
            http_response_code(404);
            view('errors/404', ['title' => 'Not Found']);
            return;
        }
        view('menu_items/show', [
            'title' => $item['name'],
            'item' => $item,
            'reviews' => $this->reviews->byMenuItem($id),
        ]);
    }
}
