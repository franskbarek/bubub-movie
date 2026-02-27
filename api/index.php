<?php

define('LARAVEL_START', microtime(true));

$root    = dirname(__DIR__);
$tmpBase = '/tmp/laravel';

// ── 1. Buat writable dirs di /tmp ────────────────────────
foreach ([
    "$tmpBase/storage/logs",
    "$tmpBase/storage/framework/cache/data",
    "$tmpBase/storage/framework/sessions",
    "$tmpBase/storage/framework/views",
    "$tmpBase/storage/app/public",
] as $dir) {
    is_dir($dir) || mkdir($dir, 0755, true);
}

// ── 2. Force env ─────────────────────────────────────────
$_ENV['LOG_CHANNEL']    = 'stderr';
$_ENV['CACHE_STORE']    = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');

// ── 3. Server vars ────────────────────────────────────────
$_SERVER['DOCUMENT_ROOT']   = $root . '/public';
$_SERVER['SCRIPT_FILENAME'] = $root . '/public/index.php';
chdir($root);

// ── 4. Boot ───────────────────────────────────────────────
require $root . '/vendor/autoload.php';

if (file_exists($m = $root . '/storage/framework/maintenance.php')) {
    require $m;
}

use Illuminate\Http\Request;

$app = require_once $root . '/bootstrap/app.php';

// Override storage → /tmp (untuk logs, sessions, views cache, framework cache)
// bootstrap/cache sudah di-commit ke repo, tidak perlu writable
$app->useStoragePath("$tmpBase/storage");

$app->handleRequest(Request::capture());
