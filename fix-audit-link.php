<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Adds "Audit Log" to the Techlab Admin menu on every page that is missing it,
 * right after the "School Years" link.
 *
 * How to run (in the project folder):   php fix-audit-link.php
 *
 * Every page it changes is first copied to storage/audit-link-backup.
 * Running it twice is safe: pages that already have the link are skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/audit-link-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

// the "School Years" link in the Techlab Admin menu (with or without class="active")
$pattern = '/([ \t]*)(<a href="\{\{ route\(\'techlab\.school-years\.index\'\) \}\}"[^>]*>School Years<\/a>)/';

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

    // skip pages that have no Techlab menu, or already have the Audit Log link
    if (str_contains($original, 'techlab.audit-logs.index') || ! preg_match($pattern, $original)) {
        continue;
    }

    $eol = str_contains($original, "\r\n") ? "\r\n" : "\n";

    $updated = preg_replace_callback(
        $pattern,
        fn ($m) => $m[1] . $m[2] . $eol . $m[1] . '<a href="{{ route(\'techlab.audit-logs.index\') }}">Audit Log</a>',
        $original,
        1
    );

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
    ? "Nothing to change. Every Techlab menu already has the Audit Log link.\n"
    : "Done. $changed page(s) updated. Backups are in storage/audit-link-backup.\n";
