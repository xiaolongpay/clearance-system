<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Makes the Account Settings page work properly on a phone:
 *   - adds the phone viewport tag,
 *   - links public/css/phone-account.css.
 * The page keeps its design; it just uses the real phone screen width.
 *
 * How to run (in the project folder):   php fix-account-phone.php
 *
 * The page is first copied to storage/account-phone-backup.
 * Running it twice is safe: a page that is already done is skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/account-phone-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

if (! is_file($root . '/public/css/phone-account.css')) {
    exit("Could not find public/css/phone-account.css. Copy the public folder from the zip into the project first.\n");
}

$cssLink = '    <link rel="stylesheet" href="{{ asset(\'css/phone-account.css\') }}">';
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

    // the Account Settings page is the one that posts to account.update
    if (! str_contains($original, "route('account.update')") || str_contains($original, 'phone-account.css')) {
        continue;
    }

    $eol = str_contains($original, "\r\n") ? "\r\n" : "\n";
    $updated = $original;

    if (! str_contains($updated, 'name="viewport"')) {
        $updated = preg_replace('/(<meta charset="[^"]*">)/i', '$1' . $eol . $viewport, $updated, 1, $metaCount);

        if (! $metaCount) {
            $updated = preg_replace('/^[ \t]*<\/head>/m', $viewport . $eol . '</head>', $updated, 1);
        }
    }

    $updated = preg_replace('/^[ \t]*<\/head>/m', $cssLink . $eol . '</head>', $updated, 1, $count);

    if ($count !== 1) {
        echo "Skipped  " . ltrim(str_replace([$viewsDir, '\\'], ['', '/'], $path), '/') . "  (no </head> found)\n";
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
    ? "Nothing to change. The Account Settings page is already set up for phones.\n"
    : "Done. Account Settings now works on phones. Backup is in storage/account-phone-backup.\n";
