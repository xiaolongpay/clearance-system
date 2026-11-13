<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Fixes the "Reset Password" button on the Manage Students page.
 * Each student's reset form was placed inside the big "Delete Selected" form. A form inside a form is not
 * allowed in HTML, so the browser threw the inner one away and the button did nothing.
 * This moves the reset forms to just after the big form, where they work.
 *
 * How to run (in the project folder):   php fix-reset-forms.php
 *
 * The page is first copied to storage/reset-forms-backup. Running it twice is safe.
 */

$root = __DIR__;
$file = $root . '/resources/views/techlab/students/index.blade.php';
$backupDir = $root . '/storage/reset-forms-backup';

if (! is_file($file)) {
    exit("Could not find resources/views/techlab/students/index.blade.php. Run this from the project folder.\n");
}

$original = file_get_contents($file);

if (str_contains($original, 'The reset forms live outside the Delete Selected form')) {
    exit("Nothing to change. The reset forms are already fixed.\n");
}

// 1) the reset form that sits inside each table row
$inner = '/\s*<form id="reset-form-\{\{ \$student->id \}\}" method="POST" action="\{\{ route\(\'techlab\.students\.reset-password\', \$student\) \}\}" style="display:none;">\s*@csrf\s*<\/form>/';

if (! preg_match($inner, $original)) {
    exit("Nothing to change. The reset forms are already fixed (or the page looks different from what this helper expects).\n");
}

$updated = preg_replace($inner, '', $original, -1, $removed);

// 2) the same forms, placed right after the big Delete Selected form
$marker = "route('techlab.students.bulk-delete')";
$start = strpos($updated, $marker);
$closeAt = $start === false ? false : strpos($updated, '</form>', $start);

if ($closeAt === false) {
    exit("Could not find the end of the Delete Selected form. Nothing was changed.\n");
}

$insertAt = $closeAt + strlen('</form>');
$eol = str_contains($updated, "\r\n") ? "\r\n" : "\n";

$block = $eol . $eol
    . '        {{-- The reset forms live outside the Delete Selected form, because a form cannot sit inside a form --}}' . $eol
    . '        @foreach ($students as $student)' . $eol
    . '            <form id="reset-form-{{ $student->id }}" method="POST" action="{{ route(\'techlab.students.reset-password\', $student) }}" style="display:none;">' . $eol
    . '                @csrf' . $eol
    . '            </form>' . $eol
    . '        @endforeach';

$updated = substr($updated, 0, $insertAt) . $block . substr($updated, $insertAt);

if (! is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

copy($file, $backupDir . '/index.blade.php');
file_put_contents($file, $updated);

echo "Updated  techlab/students/index.blade.php   (moved the reset forms out of the Delete Selected form)\n";
echo "Done. A backup is in storage/reset-forms-backup.\n";
