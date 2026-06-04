<?php
class UserModel
{
    public function findByEmail(string $email): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE email = ?';
        $params = [$email];
        if ($excludeId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $st = db()->prepare($sql . ' LIMIT 1');
        $st->execute($params);
        return (bool) $st->fetch();
    }

    public function create(string $name, string $email, string $passwordHash, string $role): int
    {
        $st = db()->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)');
        $st->execute([$name, $email, $passwordHash, $role]);
        return (int) db()->lastInsertId();
    }

    public function updateProfile(int $id, string $name, string $email, ?string $picture): void
    {
        if ($picture) {
            $st = db()->prepare('UPDATE users SET name=?, email=?, profile_picture=? WHERE id=?');
            $st->execute([$name, $email, $picture, $id]);
        } else {
            $st = db()->prepare('UPDATE users SET name=?, email=? WHERE id=?');
            $st->execute([$name, $email, $id]);
        }
    }

    public function updatePassword(int $id, string $hash): void
    {
        $st = db()->prepare('UPDATE users SET password_hash=? WHERE id=?');
        $st->execute([$hash, $id]);
    }

    public function setRememberToken(int $id, string $plainToken): void
    {
        $hash = hash_hmac('sha256', $plainToken, REMEMBER_SECRET);
        $st = db()->prepare('UPDATE users SET remember_token=? WHERE id=?');
        $st->execute([$hash, $id]);
    }

    public function clearRememberToken(int $id): void
    {
        $st = db()->prepare('UPDATE users SET remember_token=NULL WHERE id=?');
        $st->execute([$id]);
    }

    public function findByRememberToken(int $id, string $plainToken): ?array
    {
        $hash = hash_hmac('sha256', $plainToken, REMEMBER_SECRET);
        $st = db()->prepare('SELECT id, name, email, role FROM users WHERE id=? AND remember_token=? LIMIT 1');
        $st->execute([$id, $hash]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function allUsers(): array
    {
        return db()->query('SELECT id, name, email, role, created_at FROM users ORDER BY role ASC, created_at DESC')->fetchAll();
    }

    public function allMembers(): array
    {
        return db()->query("SELECT id, name, email, role, created_at FROM users WHERE role='member' ORDER BY created_at DESC")->fetchAll();
    }

    public function countUsers(): int
    {
        return (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function countAdmins(): int
    {
        return (int) db()->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    }

    public function deleteMember(int $id): bool
    {
        $st = db()->prepare("DELETE FROM users WHERE id=? AND role='member'");
        $st->execute([$id]);
        return $st->rowCount() > 0;
    }

    public function countMembers(): int
    {
        return (int) db()->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
    }
}
