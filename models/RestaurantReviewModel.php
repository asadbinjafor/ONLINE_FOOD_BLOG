<?php
class RestaurantReviewModel
{
    public function byRestaurant(int $restaurantId): array
    {
        $st = db()->prepare('SELECT rr.*, u.name AS user_name FROM restaurant_reviews rr JOIN users u ON u.id=rr.user_id WHERE rr.restaurant_id=? ORDER BY rr.created_at DESC');
        $st->execute([$restaurantId]);
        return $st->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM restaurant_reviews WHERE id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(int $restaurantId, int $userId, string $comment, int $rating): int
    {
        $st = db()->prepare('INSERT INTO restaurant_reviews (restaurant_id, user_id, comment, rating) VALUES (?,?,?,?)');
        $st->execute([$restaurantId, $userId, $comment, $rating]);
        return (int) db()->lastInsertId();
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        if ($userId) {
            $st = db()->prepare('DELETE FROM restaurant_reviews WHERE id=? AND user_id=?');
            $st->execute([$id, $userId]);
        } else {
            $st = db()->prepare('DELETE FROM restaurant_reviews WHERE id=?');
            $st->execute([$id]);
        }
        return $st->rowCount() > 0;
    }
}
