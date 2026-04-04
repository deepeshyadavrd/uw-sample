<?php

// HTTP



define('HTTP_SERVER', 'http://localhost/opencartpro/');



// HTTPS

define('HTTPS_SERVER', 'http://localhost/opencartpro/');



// DIR

define('DIR_APPLICATION', 'c:/xampp/htdocs/opencartpro/catalog/');

define('DIR_SYSTEM', 'c:/xampp/htdocs/opencartpro/system/');

define('DIR_IMAGE', 'c:/xampp/htdocs/opencartpro/image/');

define('DIR_STORAGE', 'c:/xampp/htdocs/opencartpro/storage/logs/');

define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');

define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');

define('DIR_CONFIG', DIR_SYSTEM . 'config/');

define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');

define('DIR_DOWNLOAD', DIR_SYSTEM . 'storage/download/');

define('DIR_LOGS', DIR_SYSTEM . 'storage/logs/');

define('DIR_MODIFICATION', DIR_SYSTEM . 'storage/modification/');

define('DIR_UPLOAD', DIR_SYSTEM . 'storage/upload/');

define('DIR_SESSION', DIR_SYSTEM . 'storage/session/');



// DB

define('DB_DRIVER', 'mysqli');

define('DB_HOSTNAME', 'localhost');

define('DB_USERNAME', 'root');

define('DB_PASSWORD', '');

define('DB_DATABASE', 'beta_uw');

define('DB_PORT', '3306');

define('DB_PREFIX', 'oc_');

// cache

define('CACHE_HOSTNAME', '127.0.0.1');
define('CACHE_PORT', '11211');
define('CACHE_PREFIX', 'oc_');

// Load .env file manually
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
        $_ENV[trim($name)] = trim($value);
    }
}