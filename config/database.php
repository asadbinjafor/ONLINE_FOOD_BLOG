<?php
function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $url = getenv('DATABASE_URL');
    if ($url) {
        $parts = parse_url($url);
        if ($parts === false || !isset($parts['host'], $parts['path'])) {
            throw new RuntimeException('Invalid DATABASE_URL');
        }
        $host = $parts['host'];
        $port = $parts['port'] ?? 5432;
        $name = ltrim($parts['path'], '/');
        $user = rawurldecode($parts['user'] ?? '');
        $pass = rawurldecode($parts['pass'] ?? '');
        parse_str($parts['query'] ?? '', $options);
        $sslmode = $options['sslmode'] ?? (getenv('DB_SSLMODE') ?: 'require');
    } else {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '5432';
        $name = getenv('DB_NAME') ?: 'project3';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASSWORD') ?: '';
        $sslmode = getenv('DB_SSLMODE') ?: 'prefer';
    }
    $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode={$sslmode}";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        throw new RuntimeException('Database connection unavailable.', 0, $e);
    }
    return $pdo;
}
