<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * The downloadable PDF clearance form used to read each signature from a file on the disk.
 * With the signatures kept in the database, the form now reads them from there instead.
 *
 * How to run (in the project folder):   php fix-vercel-pdf.php
 *
 * The changed page is first copied to storage/vercel-backup. Running it twice is safe.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/vercel-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

$pattern = '/storage_path\(\s*\'app\/public\/\'\s*\.\s*(\$[A-Za-z_>\-]+)->signature_path\s*\)/';
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

    $updated = preg_replace($pattern, '$1->signatureDataUri()', $original);

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
    ? "Nothing to change. The PDF form already reads the signatures from the database.\n"
    : "Done. $changed page(s) updated. Backup is in storage/vercel-backup.\n";
