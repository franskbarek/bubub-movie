<?php

/**
 * Vercel Serverless Entry Point for Laravel 11
 * Fully /tmp-based storage — no symlinks needed
 */

define('LARAVEL_START', microtime(true));

$root    = dirname(__DIR__);
$tmpBase = '/tmp/laravel';

// ── 1. Create /tmp dirs ───────────────────────────────────
foreach ([
    "$tmpBase/storage/logs",
    "$tmpBase/storage/framework/cache/data",
    "$tmpBase/storage/framework/sessions",
    "$tmpBase/storage/framework/views",
    "$tmpBase/storage/app/public",
    "$tmpBase/bootstrap/cache",
] as $dir) {
    is_dir($dir) || mkdir($dir, 0755, true);
}

// ── 2. Override env BEFORE dotenv loads ──────────────────
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

// ── 4. Autoload ───────────────────────────────────────────
require $root . '/vendor/autoload.php';

// ── 5. Maintenance mode ───────────────────────────────────
if (file_exists($m = $root . '/storage/framework/maintenance.php')) {
    require $m;
}

// ── 6. Boot Laravel with /tmp storage path ───────────────
$app = require_once $root . '/bootstrap/app.php';

// Tell Laravel to use /tmp for storage AND bootstrap/cache
$app->useStoragePath("$tmpBase/storage");
$app->instance('path.bootstrap', "$tmpBase/bootstrap");

use Illuminate\Http\Request;
$app->handleRequest(Request::capture());
