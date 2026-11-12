<?php

/*
 * Entry point of the Laravel application on Vercel.
 * Vercel sends every request here, and this file starts Laravel the same way public/index.php does.
 */

// Only the /tmp folder can be written to on Vercel, so Laravel's storage lives there.
$storage = '/tmp/storage';

foreach (['', '/app', '/app/public', '/framework/cache/data', '/framework/sessions', '/framework/views', '/logs', '/fonts'] as $folder) {
    if (! is_dir($storage . $folder)) {
        mkdir($storage . $folder, 0777, true);
    }
}

// Security certificate of the TiDB Cloud database (the file is kept in the certs folder).
$certificate = dirname(__DIR__) . '/certs/ca.pem';

if (is_file($certificate) && ! getenv('MYSQL_ATTR_SSL_CA')) {
    putenv('MYSQL_ATTR_SSL_CA=' . $certificate);
    $_ENV['MYSQL_ATTR_SSL_CA'] = $certificate;
    $_SERVER['MYSQL_ATTR_SSL_CA'] = $certificate;
}

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($storage);

$app->handleRequest(Illuminate\Http\Request::capture());
