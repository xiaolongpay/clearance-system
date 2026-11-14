<?php

/*
 * Adds the attachment feature to the existing pages and routes.
 * Run it once from the project folder:  php fix-uploads.php
 * It keeps a backup of every file it changes in storage/uploads-backup,
 * and it is safe to run twice.
 */

$root = __DIR__;
$backup = $root . '/storage/uploads-backup';
$changed = 0;

if (! is_dir($backup)) {
    mkdir($backup, 0777, true);
}

function patch(string $label, string $path, callable $edit): void
{
    global $root, $backup, $changed;

    $full = $root . '/' . $path;

    if (! file_exists($full)) {
        echo str_pad($label, 28) . " FILE NOT FOUND: $path\n";

        return;
    }

    $before = file_get_contents($full);
    $after = $edit($before);

    if ($after === null) {
        echo str_pad($label, 28) . " could not find the place to edit. Nothing changed.\n";

        return;
    }

    if ($after === $before) {
        echo str_pad($label, 28) . " already done\n";

        return;
    }

    copy($full, $backup . '/' . str_replace(['/', '\\'], '_', $path) . '.bak');
    file_put_contents($full, $after);
    $changed++;
    echo str_pad($label, 28) . " updated\n";
}

/** Insert $add right before the first $needle, unless $marker is already in the file. */
function before(string $text, string $needle, string $add, string $marker): ?string
{
    if (str_contains($text, $marker)) {
        return $text;
    }

    $pos = strpos($text, $needle);

    return $pos === false ? null : substr($text, 0, $pos) . $add . substr($text, $pos);
}

/** Insert $add right after the first $needle, unless $marker is already in the file. */
function after(string $text, string $needle, string $add, string $marker): ?string
{
    if (str_contains($text, $marker)) {
        return $text;
    }

    $pos = strpos($text, $needle);

    return $pos === false ? null : substr($text, 0, $pos + strlen($needle)) . $add . substr($text, $pos + strlen($needle));
}

// ---------------------------------------------------------------- routes
patch('Routes', 'routes/web.php', function ($t) {
    $t = after(
        $t,
        "->name('student.notifications.seen');",
        "\n    Route::post('/student/uploads/{clearanceItem}', [\\App\\Http\\Controllers\\Student\\UploadController::class, 'store'])->name('student.uploads.store');"
            . "\n    Route::delete('/student/uploads/{upload}', [\\App\\Http\\Controllers\\Student\\UploadController::class, 'destroy'])->name('student.uploads.destroy');",
        'student.uploads.store'
    );

    if ($t === null) {
        return null;
    }

    $t = after(
        $t,
        "->name('clearance.revert');",
        "\n    Route::post('notifications/seen', [\\App\\Http\\Controllers\\Staff\\NotificationController::class, 'seen'])->name('notifications.seen');",
        "name('notifications.seen')"
    );

    if ($t === null) {
        return null;
    }

    if (! str_contains($t, "name('uploads.show')")) {
        $t = before(
            $t,
            '// Account settings',
            "// Open an attachment (the student who sent it, the office it was sent to, and Techlab)\n"
                . "Route::middleware('auth:web,student')->get('/uploads/{upload}', [\\App\\Http\\Controllers\\UploadFileController::class, 'show'])->name('uploads.show');\n\n",
            "name('uploads.show')"
        );
    }

    return $t;
});

// ---------------------------------------------------------------- student dashboard
patch('Student dashboard', 'resources/views/student/dashboard.blade.php', function ($t) {
    // a small "Attach files" link under each office name
    $anchor = "{!! str_replace('/', '/<wbr>', e(\$item->department->name)) !!}";
    $pos = strpos($t, $anchor);

    if (! str_contains($t, 'student.partials.attach-button')) {
        if ($pos === false) {
            return null;
        }

        $close = strpos($t, '</button>', $pos);

        if ($close === false) {
            return null;
        }

        $close += strlen('</button>');
        $t = substr($t, 0, $close) . "\n                                    @include('student.partials.attach-button', ['item' => \$item])" . substr($t, $close);
    }

    // the office name button needs to know which clearance item it belongs to
    if (! str_contains($t, 'data-item=')) {
        $marker = 'class="office-link"';

        if (! str_contains($t, $marker)) {
            return null;
        }

        $t = preg_replace('/class="office-link"/', 'class="office-link" data-item="{{ $item->id }}"', $t, 1);
    }

    if (! str_contains($t, 'student.partials.uploads')) {
        $end = strrpos($t, '</body>');

        if ($end === false) {
            return null;
        }

        $t = substr($t, 0, $end) . "    @include('student.partials.uploads')\n" . substr($t, $end);
    }

    return $t;
});

// ---------------------------------------------------------------- staff approvals page
patch('Staff approvals page', 'resources/views/staff/clearance/index.blade.php', function ($t) {
    $t = before($t, '<button type="button" class="theme-toggle"', "<x-staff-notifications />\n            ", '<x-staff-notifications');

    if ($t === null) {
        return null;
    }

    $t = after(
        $t,
        '<div class="student-id">{{ $item->student->student_number }}</div>',
        "\n                                @include('staff.partials.attachments-link', ['item' => \$item])",
        'staff.partials.attachments-link'
    );

    if ($t === null) {
        return null;
    }

    $t = before(
        $t,
        '<button type="submit" class="btn-filter">',
        "@include('staff.partials.upload-filter')\n\n            ",
        'staff.partials.upload-filter'
    );

    if ($t === null) {
        return null;
    }

    if (! str_contains($t, 'staff.partials.attachments-modal')) {
        $end = strrpos($t, '</body>');

        if ($end === false) {
            return null;
        }

        $t = substr($t, 0, $end) . "    @include('staff.partials.attachments-modal')\n" . substr($t, $end);
    }

    return $t;
});

// ---------------------------------------------------------------- staff dashboard
patch('Staff dashboard', 'resources/views/staff/dashboard-placeholder.blade.php', function ($t) {
    return before($t, '<button type="button" class="theme-toggle"', "<x-staff-notifications />\n            ", '<x-staff-notifications');
});

echo "\nDone. $changed file(s) changed. Backups are in storage/uploads-backup.\n";
