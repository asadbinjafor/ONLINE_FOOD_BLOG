<?php
define('ROOT_DIR', dirname(__DIR__));
define('BASE_URL', '/project3');
define('APP_NAME', 'Online Food Blog');

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'project3');
define('DB_USER', 'root');
define('DB_PASS', '');

define('REMEMBER_DAYS', 30);
define('REMEMBER_SECRET', 'project3_remember_secret_change_in_production');
define('UPLOAD_MAX_BYTES', 2 * 1024 * 1024);

define('PROFILE_UPLOAD_DIR', ROOT_DIR . '/public/uploads/profiles/');
define('MENU_UPLOAD_DIR', ROOT_DIR . '/public/uploads/menu/');
define('PROFILE_UPLOAD_WEB', BASE_URL . '/public/uploads/profiles/');
define('MENU_UPLOAD_WEB', BASE_URL . '/public/uploads/menu/');

define('POST_TYPES', ['restaurant', 'food', 'both']);

function ensureUploadDirs(): void
{
    foreach ([PROFILE_UPLOAD_DIR, MENU_UPLOAD_DIR] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
