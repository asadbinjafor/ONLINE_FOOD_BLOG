<?php
class ReviewModel
{
    public function byMenuItem(int $menuItemId): array
    {
        $st = db()->prepare('SELECT r.*, u.name AS user_name FROM reviews r JOIN users u ON u.id=r.user_id WHERE r.menu_item_id=? ORDER BY r.created_at DESC');
        $st->execute([$menuItemId]);
        return $st->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM reviews WHERE id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(int $menuItemId, int $userId, string $comment): int
    {
        $st = db()->prepare('INSERT INTO reviews (menu_item_id, user_id, comment) VALUES (?,?,?)');
        $st->execute([$menuItemId, $userId, $comment]);
        return (int) db()->lastInsertId();
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        if ($userId) {
            $st = db()->prepare('DELETE FROM reviews WHERE id=? AND user_id=?');
            $st->execute([$id, $userId]);
        } else {
            $st = db()->prepare('DELETE FROM reviews WHERE id=?');
            $st->execute([$id]);
        }
        return $st->rowCount() > 0;
    }

    public function count(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
    }

    public function allWithMeta(): array
    {
        return db()->query('SELECT r.*, u.name AS user_name, m.name AS item_name, rest.name AS restaurant_name
            FROM reviews r
            JOIN users u ON u.id=r.user_id
            JOIN menu_items m ON m.id=r.menu_item_id
            JOIN restaurants rest ON rest.id=m.restaurant_id
            ORDER BY r.created_at DESC')->fetchAll();
    }
}
