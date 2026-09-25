<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
try {
    require_once __DIR__ . '/config/database.php';
    db()->query('SELECT 1')->fetchColumn();
    echo json_encode(['status' => 'ok']);
} catch (Throwable $error) {
    error_log('Health check failed: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['status' => 'unavailable']);
}
