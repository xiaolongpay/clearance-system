<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * What it does, in every page under resources/views:
 *   1. Turns the STI logo in the top bar into a link to the user's own dashboard
 *      (students go to the student dashboard, staff to the staff dashboard).
 *   2. Removes the separate "Dashboard" link from the staff menu, because the logo does that job.
 *
 * How to run (in the project folder):   php fix-nav.php
 *
 * Every page it changes is first copied to storage/nav-backup, so nothing is lost.
 * Running it twice is safe: pages that are already changed are skipped.
 */

$root = __DIR__;
$viewsDir = $root . '/resources/views';
$backupDir = $root . '/storage/nav-backup';

if (! is_dir($viewsDir)) {
    exit("Could not find resources/views. Run this from the project folder.\n");
}

$dashboardUrl = <<<'BLADE'
{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}
BLADE;

// <div class="navbar-brand"> or <div class="topbar-left"> that holds only the STI logo
$logoPattern = '/(<div class="(?:navbar-brand|topbar-left)">\s*)(<img src="\{\{ asset\(\'images\/sti_val_logo\.png\'\) \}\}"[^>]*>)(\s*<\/div>)/';

// the "Dashboard" link in the staff menu (with or without the active class)
$dashboardLinkPattern = '/\r?\n[ \t]*<a href="\{\{ route\(\'staff\.dashboard\'\) \}\}" class="nav-link[^"]*">Dashboard<\/a>/';

$changedFiles = 0;

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir, FilesystemIterator::SKIP_DOTS)
);

foreach ($files as $file) {
    if (! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $original = file_get_contents($path);

    $updated = preg_replace_callback(
        $logoPattern,
        fn ($m) => $m[1] . '<a href="' . $dashboardUrl . '" title="Go to dashboard" style="display:block;">' . $m[2] . '</a>' . $m[3],
        $original,
        -1,
        $logosLinked
    );

    $updated = preg_replace($dashboardLinkPattern, '', $updated, -1, $linksRemoved);

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

    $changedFiles++;
    echo sprintf("Changed  %-48s logo linked: %s   Dashboard link removed: %s\n", $relative, $logosLinked ? 'yes' : 'no', $linksRemoved ? 'yes' : 'no');
}

echo "\n";
echo $changedFiles === 0
    ? "Nothing to change. The pages are already updated.\n"
    : "Done. $changedFiles page(s) updated. Backups are in storage/nav-backup.\n";
