<?php
class RestaurantModel
{
    public function all(?string $location = null, ?string $area = null, ?string $q = null): array
    {
        $sql = 'SELECT * FROM restaurants WHERE 1=1';
        $params = [];
        if ($location) {
            $sql .= ' AND location = ?';
            $params[] = $location;
        }
        if ($area) {
            $sql .= ' AND area = ?';
            $params[] = $area;
        }
        if ($q) {
            $sql .= ' AND (name ILIKE ? OR short_background ILIKE ?)';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
        }
        $sql .= ' ORDER BY name ASC';
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM restaurants WHERE id=? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $st = db()->prepare('INSERT INTO restaurants (name, location, area, short_background, goals) VALUES (?,?,?,?,?) RETURNING id');
        $st->execute([$data['name'], $data['location'], $data['area'], $data['short_background'], $data['goals']]);
        return (int) $st->fetchColumn();
    }

    public function update(int $id, array $data): void
    {
        $st = db()->prepare('UPDATE restaurants SET name=?, location=?, area=?, short_background=?, goals=? WHERE id=?');
        $st->execute([$data['name'], $data['location'], $data['area'], $data['short_background'], $data['goals'], $id]);
    }

    public function delete(int $id): void
    {
        $st = db()->prepare('DELETE FROM restaurants WHERE id=?');
        $st->execute([$id]);
    }

    public function count(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM restaurants')->fetchColumn();
    }

    public function distinctLocations(): array
    {
        return db()->query('SELECT DISTINCT location FROM restaurants ORDER BY location')->fetchAll(PDO::FETCH_COLUMN);
    }

    public function distinctAreas(?string $location = null): array
    {
        if ($location) {
            $st = db()->prepare('SELECT DISTINCT area FROM restaurants WHERE location=? ORDER BY area');
            $st->execute([$location]);
            return $st->fetchAll(PDO::FETCH_COLUMN);
        }
        return db()->query('SELECT DISTINCT area FROM restaurants ORDER BY area')->fetchAll(PDO::FETCH_COLUMN);
    }

    public function searchCombined(?string $q, ?string $location, ?string $area, ?float $minPrice, ?float $maxPrice): array
    {
        $restaurants = $this->all($location, $area, $q);
        $itemSql = 'SELECT m.*, r.name AS restaurant_name, r.location, r.area FROM menu_items m JOIN restaurants r ON r.id=m.restaurant_id WHERE 1=1';
        $params = [];
        if ($location) {
            $itemSql .= ' AND r.location = ?';
            $params[] = $location;
        }
        if ($area) {
            $itemSql .= ' AND r.area = ?';
            $params[] = $area;
        }
        if ($q) {
            $itemSql .= ' AND (m.name ILIKE ? OR m.description ILIKE ? OR r.name ILIKE ?)';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        if ($minPrice !== null) {
            $itemSql .= ' AND m.price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $itemSql .= ' AND m.price <= ?';
            $params[] = $maxPrice;
        }
        $itemSql .= ' ORDER BY m.name ASC';
        $st = db()->prepare($itemSql);
        $st->execute($params);
        $items = $st->fetchAll();
        return ['restaurants' => $restaurants, 'menu_items' => $items];
    }
}
