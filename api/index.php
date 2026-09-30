<?php

// Ensure required writable directories exist in Vercel's /tmp serverless filesystem
$tmpDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/storage',
    '/tmp/storage/framework',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Automatically use pre-seeded SQLite database in /tmp on Vercel if no external DB_HOST is configured
$dbHost = getenv('DB_HOST');
if ((getenv('VERCEL') || getenv('VERCEL_ENV')) && empty($dbHost)) {
    $sqliteTmpPath = '/tmp/database.sqlite';
    $bundledSqlite = __DIR__ . '/../database/database.sqlite';

    if (!file_exists($sqliteTmpPath)) {
        if (file_exists($bundledSqlite)) {
            @copy($bundledSqlite, $sqliteTmpPath);
            @chmod($sqliteTmpPath, 0666);
        } else {
            @touch($sqliteTmpPath);
            @chmod($sqliteTmpPath, 0666);
        }
    }

    putenv('DB_CONNECTION=sqlite');
    putenv("DB_DATABASE={$sqliteTmpPath}");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $sqliteTmpPath;
    $_SERVER['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_DATABASE'] = $sqliteTmpPath;
}

require __DIR__ . '/../public/index.php';
