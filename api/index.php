<?php

define('LARAVEL_START', microtime(true));

$root = dirname(__DIR__);

// Buat /tmp dirs untuk storage yang butuh write
foreach ([
    '/tmp/storage/logs',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/app/public',
] as $dir) {
    is_dir($dir) || mkdir($dir, 0755, true);
}

$_SERVER['DOCUMENT_ROOT']   = $root . '/public';
$_SERVER['SCRIPT_FILENAME'] = $root . '/public/index.php';

chdir($root);

require $root . '/vendor/autoload.php';

if (file_exists($m = $root . '/storage/framework/maintenance.php')) {
    require $m;
}

use Illuminate\Http\Request;

$app = require_once $root . '/bootstrap/app.php';
$app->useStoragePath('/tmp/storage');
$app->handleRequest(Request::capture());
