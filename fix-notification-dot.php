<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Adds the notification bell to the student dashboard:
 *   1. puts <x-student-notifications /> in the top bar, next to the light/dark button, and
 *   2. adds the route that the bell uses to mark the results as seen.
 *
 * How to run (in the project folder):   php fix-notification-dot.php
 *
 * The two files are first copied to storage/notification-backup. Running it twice is safe.
 */

$root = __DIR__;
$dashboard = $root . '/resources/views/student/dashboard.blade.php';
$routes = $root . '/routes/web.php';
$backupDir = $root . '/storage/notification-backup';

foreach ([$dashboard, $routes] as $file) {
    if (! is_file($file)) {
        exit("Could not find $file. Run this from the project folder.\n");
    }
}

if (! is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

$done = 0;

// 1) the bell in the top bar of the student dashboard
$html = file_get_contents($dashboard);

if (str_contains($html, '<x-student-notifications')) {
    echo "Dashboard   already has the bell.\n";
} elseif (preg_match('/^([ \t]*)(<button\b[^>]*id="themeToggle"[^>]*>)/m', $html, $m, PREG_OFFSET_CAPTURE)) {
    copy($dashboard, $backupDir . '/dashboard.blade.php');
    $indent = $m[1][0];
    $eol = str_contains($html, "\r\n") ? "\r\n" : "\n";
    $html = substr_replace($html, $indent . '<x-student-notifications />' . $eol, $m[0][1], 0);
    file_put_contents($dashboard, $html);
    echo "Dashboard   bell added next to the light/dark button.\n";
    $done++;
} else {
    echo "Dashboard   COULD NOT find the light/dark button. Add this line by hand inside the top bar:  <x-student-notifications />\n";
}

// 2) the route used by the bell
$web = file_get_contents($routes);
$route = "Route::post('/student/notifications/seen', [\\App\\Http\\Controllers\\Student\\NotificationController::class, 'seen'])->name('student.notifications.seen');";

if (str_contains($web, 'student.notifications.seen')) {
    echo "Routes      already has the route.\n";
} elseif (preg_match('/^([ \t]*)Route::get\(\'\/student\/clearance-pdf\'[^\n]*\n/m', $web, $m, PREG_OFFSET_CAPTURE)) {
    copy($routes, $backupDir . '/web.php');
    $indent = $m[1][0];
    $eol = str_contains($web, "\r\n") ? "\r\n" : "\n";
    $web = substr_replace($web, $indent . $route . $eol, $m[0][1] + strlen($m[0][0]), 0);
    file_put_contents($routes, $web);
    echo "Routes      route added to the student group.\n";
    $done++;
} else {
    echo "Routes      COULD NOT find the student group. Paste this line inside the Route::middleware('auth:student') group in routes/web.php:\n  $route\n";
}

echo "\nDone. $done file(s) changed. Backups are in storage/notification-backup.\n";
