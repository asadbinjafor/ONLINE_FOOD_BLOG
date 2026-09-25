<?php
class MenuItemModel
{
    public function byRestaurant(int $restaurantId): array
    {
        $st = db()->prepare('SELECT * FROM menu_items WHERE restaurant_id=? ORDER BY name');
        $st->execute([$restaurantId]);
        return $st->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = db()->prepare('SELECT m.*, r.name AS restaurant_name, r.location, r.area FROM menu_items m JOIN restaurants r ON r.id=m.restaurant_id WHERE m.id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $st = db()->prepare('INSERT INTO menu_items (restaurant_id, name, description, price, image_path) VALUES (?,?,?,?,?) RETURNING id');
        $st->execute([$data['restaurant_id'], $data['name'], $data['description'], $data['price'], $data['image_path'] ?? null]);
        return (int) $st->fetchColumn();
    }

    public function update(int $id, array $data): void
    {
        if (!empty($data['image_path'])) {
            $st = db()->prepare('UPDATE menu_items SET name=?, description=?, price=?, image_path=? WHERE id=?');
            $st->execute([$data['name'], $data['description'], $data['price'], $data['image_path'], $id]);
        } else {
            $st = db()->prepare('UPDATE menu_items SET name=?, description=?, price=? WHERE id=?');
            $st->execute([$data['name'], $data['description'], $data['price'], $id]);
        }
    }

    public function delete(int $id): void
    {
        $st = db()->prepare('DELETE FROM menu_items WHERE id=?');
        $st->execute([$id]);
    }

    public function count(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM menu_items')->fetchColumn();
    }
}
