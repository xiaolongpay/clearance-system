<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Adds the readable phone menu (public/css/phone-nav.css) to every staff page,
 * that is, every page under resources/views that has the top navbar.
 *
 * How to run (in the project folder):   php fix-phone-nav.php
 *
 * Every page it changes is first copied to storage/phone-nav-backup.
 * Running it twice is safe: pages that already have the link are skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/phone-nav-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

if (! is_file($root . '/public/css/phone-nav.css')) {
    exit("Could not find public/css/phone-nav.css. Copy the public folder from the zip into the project first.\n");
}

$link = '    <link rel="stylesheet" href="{{ asset(\'css/phone-nav.css\') }}">';
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

    // only pages with the staff navbar, and only once
    if (! str_contains($original, '<div class="navbar">') || str_contains($original, 'phone-nav.css')) {
        continue;
    }

    $eol = str_contains($original, "\r\n") ? "\r\n" : "\n";
    $updated = preg_replace('/^[ \t]*<\/head>/m', $link . $eol . '</head>', $original, 1, $count);

    if ($count !== 1) {
        echo "Skipped  " . str_replace([$viewsDir, '\\'], ['', '/'], $path) . "  (no </head> found)\n";
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
    ? "Nothing to change. The pages already have the phone menu.\n"
    : "Done. $changed page(s) updated. Backups are in storage/phone-nav-backup.\n";
