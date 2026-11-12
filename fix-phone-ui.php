<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Gives every staff page (every page under resources/views with the top navbar)
 * the readable phone layout:
 *   - adds the phone viewport tag (so the page uses the real phone screen width),
 *   - links public/css/phone-ui.css and public/js/phone-ui.js,
 *   - removes the older phone-nav.css link, which this replaces.
 *
 * How to run (in the project folder):   php fix-phone-ui.php
 *
 * Every page it changes is first copied to storage/phone-ui-backup.
 * Running it twice is safe: pages that are already done are skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/phone-ui-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

foreach (['public/css/phone-ui.css', 'public/js/phone-ui.js'] as $needed) {
    if (! is_file($root . '/' . $needed)) {
        exit("Could not find $needed. Copy the public folder from the zip into the project first.\n");
    }
}

$cssLink = '    <link rel="stylesheet" href="{{ asset(\'css/phone-ui.css\') }}">';
$jsTag = '    <script src="{{ asset(\'js/phone-ui.js\') }}" defer></script>';
$viewport = '    <meta name="viewport" content="width=device-width, initial-scale=1">';

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

    if (! str_contains($original, '<div class="navbar">')) {
        continue;
    }

    $eol = str_contains($original, "\r\n") ? "\r\n" : "\n";
    $updated = $original;

    // 1) drop the older phone-nav.css link
    $updated = preg_replace('/^[ \t]*<link[^>]*phone-nav\.css[^>]*>\R?/m', '', $updated);

    // 2) the phone viewport tag
    if (! str_contains($updated, 'name="viewport"')) {
        $updated = preg_replace('/(<meta charset="[^"]*">)/i', '$1' . $eol . $viewport, $updated, 1, $metaCount);

        if (! $metaCount) {
            $updated = preg_replace('/^[ \t]*<\/head>/m', $viewport . $eol . '</head>', $updated, 1);
        }
    }

    // 3) the shared phone stylesheet and script
    if (! str_contains($updated, 'phone-ui.css')) {
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
    ? "Nothing to change. The pages already have the phone layout.\n"
    : "Done. $changed page(s) updated. Backups are in storage/phone-ui-backup.\n";
