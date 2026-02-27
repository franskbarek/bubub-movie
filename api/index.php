<?php

/**
 * Vercel Serverless Entry Point for Laravel 11
 * Solution: redirect all writable paths to /tmp
 */

define('LARAVEL_START', microtime(true));

$root    = dirname(__DIR__);
$tmpBase = '/tmp/laravel';

// ── Step 1: Create /tmp directories ──────────────────────
$writableDirs = [
    "$tmpBase/storage/logs",
    "$tmpBase/storage/framework/cache/data",
    "$tmpBase/storage/framework/sessions",
    "$tmpBase/storage/framework/views",
    "$tmpBase/storage/app",
    "$tmpBase/bootstrap/cache",
];

foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// ── Step 2: Force env overrides BEFORE Laravel boots ─────
$_ENV['LOG_CHANNEL']    = 'stderr';
$_ENV['CACHE_STORE']    = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');

// ── Step 3: Server vars ───────────────────────────────────
$_SERVER['DOCUMENT_ROOT']   = $root . '/public';
$_SERVER['SCRIPT_FILENAME'] = $root . '/public/index.php';

chdir($root);

// ── Step 4: Autoload ──────────────────────────────────────
require $root . '/vendor/autoload.php';

// ── Step 5: Maintenance mode check ───────────────────────
if (file_exists($m = $root . '/storage/framework/maintenance.php')) {
    require $m;
}

// ── Step 6: Create app & override storage path ───────────
$app = require_once $root . '/bootstrap/app.php';

// Override storage path → /tmp before any service provider runs
$app->useStoragePath("$tmpBase/storage");

// Also override bootstrap path for cache (config cache, route cache, etc.)
// This prevents "bootstrap/cache must be writable" error
if (!is_dir("$tmpBase/bootstrap/cache")) {
    mkdir("$tmpBase/bootstrap/cache", 0755, true);
}

// Bind the bootstrap path override
$app->instance('path.bootstrap', "$tmpBase/bootstrap");

// ── Step 7: Handle request ────────────────────────────────
use Illuminate\Http\Request;
$app->handleRequest(Request::capture());
