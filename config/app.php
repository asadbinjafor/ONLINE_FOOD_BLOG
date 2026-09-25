<?php
define('ROOT_DIR', dirname(__DIR__));
define('BASE_URL', rtrim((string) (getenv('BASE_URL') !== false ? getenv('BASE_URL') : (getenv('RENDER') ? '' : '/project3')), '/'));
define('APP_NAME', 'Online Food Blog');
define('REMEMBER_DAYS', 30);
define('REMEMBER_SECRET', getenv('REMEMBER_SECRET') ?: '');
define('UPLOAD_MAX_BYTES', 2 * 1024 * 1024);

define('PROFILE_UPLOAD_DIR', ROOT_DIR . '/public/uploads/profiles/');
define('MENU_UPLOAD_DIR', ROOT_DIR . '/public/uploads/menu/');
define('SUPABASE_URL', rtrim(getenv('SUPABASE_URL') ?: '', '/'));
define('SUPABASE_SERVICE_ROLE_KEY', getenv('SUPABASE_SERVICE_ROLE_KEY') ?: '');
define('SUPABASE_STORAGE_BUCKET', getenv('SUPABASE_STORAGE_BUCKET') ?: 'food-blog');
define('STORAGE_ENABLED', SUPABASE_URL !== '' && SUPABASE_SERVICE_ROLE_KEY !== '');
define('PROFILE_UPLOAD_WEB', STORAGE_ENABLED ? SUPABASE_URL . '/storage/v1/object/public/' . rawurlencode(SUPABASE_STORAGE_BUCKET) . '/profiles' : BASE_URL . '/public/uploads/profiles');
define('MENU_UPLOAD_WEB', STORAGE_ENABLED ? SUPABASE_URL . '/storage/v1/object/public/' . rawurlencode(SUPABASE_STORAGE_BUCKET) . '/menu' : BASE_URL . '/public/uploads/menu');
define('POST_TYPES', ['restaurant', 'food', 'both']);

function ensureUploadDirs(): void
{
    if (STORAGE_ENABLED || getenv('RENDER')) {
        return;
    }
    foreach ([PROFILE_UPLOAD_DIR, MENU_UPLOAD_DIR] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
