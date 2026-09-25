<?php
class FoodExperienceModel
{
    public function allPosts(): array
    {
        return db()->query('SELECT p.*, u.name AS author_name,
            r.name AS restaurant_name, m.name AS menu_item_name
            FROM food_experience_posts p
            JOIN users u ON u.id=p.user_id
            LEFT JOIN restaurants r ON r.id=p.restaurant_id
            LEFT JOIN menu_items m ON m.id=p.menu_item_id
            ORDER BY p.created_at DESC')->fetchAll();
    }

    public function findPost(int $id): ?array
    {
        $st = db()->prepare('SELECT p.*, u.name AS author_name FROM food_experience_posts p JOIN users u ON u.id=p.user_id WHERE p.id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function createPost(array $data): int
    {
        $st = db()->prepare('INSERT INTO food_experience_posts (user_id, title, content, post_type, restaurant_id, menu_item_id) VALUES (?,?,?,?,?,?) RETURNING id');
        $st->execute([
            $data['user_id'],
            $data['title'],
            $data['content'],
            $data['post_type'],
            $data['restaurant_id'] ?: null,
            $data['menu_item_id'] ?: null,
        ]);
        return (int) $st->fetchColumn();
    }

    public function updatePost(int $id, array $data): void
    {
        $st = db()->prepare('UPDATE food_experience_posts SET title=?, content=?, post_type=?, restaurant_id=?, menu_item_id=? WHERE id=?');
        $st->execute([
            $data['title'],
            $data['content'],
            $data['post_type'],
            $data['restaurant_id'] ?: null,
            $data['menu_item_id'] ?: null,
            $id,
        ]);
    }

    public function deletePost(int $id): void
    {
        $st = db()->prepare('DELETE FROM food_experience_posts WHERE id=?');
        $st->execute([$id]);
    }

    public function commentsForPost(int $postId): array
    {
        $st = db()->prepare('SELECT c.*, u.name AS user_name FROM food_experience_comments c JOIN users u ON u.id=c.user_id WHERE c.post_id=? ORDER BY c.created_at ASC');
        $st->execute([$postId]);
        return $st->fetchAll();
    }

    public function addComment(int $postId, int $userId, string $comment): int
    {
        $st = db()->prepare('INSERT INTO food_experience_comments (post_id, user_id, comment) VALUES (?,?,?) RETURNING id');
        $st->execute([$postId, $userId, $comment]);
        return (int) $st->fetchColumn();
    }

    public function findComment(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM food_experience_comments WHERE id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function deleteComment(int $id): void
    {
        $st = db()->prepare('DELETE FROM food_experience_comments WHERE id=?');
        $st->execute([$id]);
    }

    public function countPosts(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM food_experience_posts')->fetchColumn();
    }
}
