<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Puts every staff page back to the exact web design on a phone, and makes it readable:
 *   - removes the phone viewport tag added earlier (so the page keeps the normal web design),
 *   - removes the earlier phone-ui.css / phone-ui.js / phone-nav.css links,
 *   - links public/css/phone-scaled.css and public/js/phone-scaled.js.
 *
 * How to run (in the project folder):   php fix-phone-scaled.php
 *
 * Every page it changes is first copied to storage/phone-scaled-backup.
 * Running it twice is safe: pages that are already done are skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/phone-scaled-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

foreach (['public/css/phone-scaled.css', 'public/js/phone-scaled.js'] as $needed) {
    if (! is_file($root . '/' . $needed)) {
        exit("Could not find $needed. Copy the public folder from the zip into the project first.\n");
    }
}

$cssLink = '    <link rel="stylesheet" href="{{ asset(\'css/phone-scaled.css\') }}">';
$jsTag = '    <script src="{{ asset(\'js/phone-scaled.js\') }}" defer></script>';

$changed = 0;

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir, FilesystemIterator::SKIP_DOTS)
);

foreach ($files as $file) {
    if (! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $original = file_get_contents($path);

    // only the staff pages (the ones with the top navbar)
    if (! str_contains($original, '<div class="navbar">')) {
        continue;
    }

    $eol = str_contains($original, "\r\n") ? "\r\n" : "\n";
    $updated = $original;

    // remove the earlier phone files and the phone viewport tag
    $updated = preg_replace('/^[ \t]*<link[^>]*(phone-ui|phone-nav)\.css[^>]*>\R?/m', '', $updated);
    $updated = preg_replace('/^[ \t]*<script[^>]*phone-ui\.js[^>]*><\/script>\R?/m', '', $updated);
    $updated = preg_replace('/^[ \t]*<meta name="viewport"[^>]*>\R?/m', '', $updated);

    // add the exact-design phone files, once
    if (! str_contains($updated, 'phone-scaled.css')) {
        $updated = preg_replace('/^[ \t]*<\/head>/m', $cssLink . $eol . $jsTag . $eol . '</head>', $updated, 1, $count);

        if ($count !== 1) {
            echo "Skipped  " . ltrim(str_replace([$viewsDir, '\\'], ['', '/'], $path), '/') . "  (no </head> found)\n";
            continue;
        }
    }

    if ($updated === $original) {
        continue;
    }

    $relative = ltrim(str_replace([$viewsDir, '\\'], ['', '/'], $path), '/');
    $backupPath = $backupDir . '/' . $relative;

    if (! is_dir(dirname($backupPath))) {
        mkdir(dirname($backupPath), 0777, true);
    }

    copy($path, $backupPath);
    file_put_contents($path, $updated);

    $changed++;
    echo "Updated  $relative\n";
}

echo "\n";
echo $changed === 0
    ? "Nothing to change. The pages already use the exact web design.\n"
    : "Done. $changed page(s) updated. Backups are in storage/phone-scaled-backup.\n";
