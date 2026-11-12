<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Pages used to load each signature as a separate image file (storage/signatures/...).
 * On the online hosting that file request can fail, so the signature does not show.
 * This changes every page to put the signature inside the page itself, which always works.
 *
 * How to run (in the project folder):   php fix-signature-images.php
 *
 * Every page it changes is first copied to storage/signature-backup. Running it twice is safe.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/signature-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

// asset('storage/' . $something->signature_path)   becomes   $something->signatureDataUri()
$pattern = '/asset\(\s*\'storage\/\'\s*\.\s*(\$[A-Za-z_>\-]+)->signature_path\s*\)/';
$changed = 0;

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir, FilesystemIterator::SKIP_DOTS));

foreach ($files as $file) {
    if (! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $original = file_get_contents($path);

    if (! preg_match($pattern, $original)) {
        continue;
    }

    $updated = preg_replace($pattern, '$1->signatureDataUri()', $original, -1, $count);

    $relative = ltrim(str_replace([$viewsDir, '\\'], ['', '/'], $path), '/');
    $backupPath = $backupDir . '/' . $relative;

    if (! is_dir(dirname($backupPath))) {
        mkdir(dirname($backupPath), 0777, true);
    }

    copy($path, $backupPath);
    file_put_contents($path, $updated);

    $changed++;
    echo "Updated  $relative   ($count place(s))\n";
}

echo "\n";
echo $changed === 0
    ? "Nothing to change. The pages already show the signatures inside the page.\n"
    : "Done. $changed page(s) updated. Backups are in storage/signature-backup.\n";
