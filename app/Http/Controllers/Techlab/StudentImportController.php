<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ClearanceItem;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentImportController extends Controller
{
    /**
     * Column names the importer recognises in the CSV header row.
     * The order of the columns does not matter, and only Student ID and
     * Name are required. Everything else is optional.
     */
    private const COLUMN_NAMES = [
        'student_id' => ['student id', 'student no', 'student number', 'student', 'id', 'id no', 'id number'],
        'name' => ['name', 'student name', 'full name'],
        'middle_initial' => ['middle initial', 'mi', 'm i', 'middle'],
        'email' => ['email', 'email address', 'school email', 'e mail'],
        'course' => ['course', 'program', 'strand', 'program strand'],
        'year' => ['year', 'year level', 'yr', 'level'],
        'section' => ['section', 'sec'],
    ];

    /**
     * Show the CSV upload form.
     */
    public function create()
    {
        return view('techlab.students.import');
    }

    /**
     * Handle the CSV upload and import students.
     *
     * New students get a default password (their student number) and must
     * change it at first login. Students who already exist keep their
     * current password, so a re-import never resets it.
     */
    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        if (! $activeSchoolYear) {
            return back()->withErrors(['csv_file' => 'No active school year is set. Please activate one first.']);
        }

        $parsed = $this->parseCsv($request->file('csv_file')->getRealPath());

        if ($parsed['error']) {
            return back()->withErrors(['csv_file' => $parsed['error']]);
        }

        $departments = Department::all();

        $added = 0;
        $updated = 0;
        $generatedEmails = 0;

        foreach ($parsed['rows'] as $row) {
            $studentNumber = $row['student_number'];

            // Details that come from the CSV (the password is NOT in this list)
            $details = [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'school_year_id' => $activeSchoolYear->id,
                'is_active' => true,
            ];

            if ($row['has_middle_initial']) {
                $details['middle_initial'] = $row['middle_initial'];
            }

            if ($row['email'] !== '') {
                $details['email'] = $row['email'];
            }

            foreach (['course' => 'course', 'year_level' => 'year', 'section' => 'section'] as $field => $key) {
                if ($row['has_' . $key]) {
                    $details[$field] = $row[$key];
                }
            }

            $student = Student::where('student_number', $studentNumber)->first();

            if ($student) {
                // Existing student: update the details only, keep the password
                $student->update($details);
                $updated++;
            } else {
                // New student: fill anything the file did not provide, then
                // set the default password (the student number)
                if (! isset($details['email'])) {
                    $details['email'] = $this->makeEmail($row['last_name'], $studentNumber);
                    $generatedEmails++;
                }

                $student = Student::create($details + [
                    'student_number' => $studentNumber,
                    'course' => '',
                    'year_level' => '',
                    'section' => '',
                    'password' => Hash::make($studentNumber),
                    'must_change_password' => true,
                ]);
                $added++;
            }

            // Generate a clearance item for every department, if it doesn't exist yet
            foreach ($departments as $department) {
                ClearanceItem::createOrFirst([
                    'student_id' => $student->id,
                    'department_id' => $department->id,
                    'school_year_id' => $activeSchoolYear->id,
                ], [
                    'status' => 'pending',
                ]);
            }
        }

        if ($added + $updated === 0) {
            return back()->withErrors([
                'csv_file' => 'No students were imported. The file has no rows with a student number.',
            ]);
        }

        $message = "$added new students added, $updated existing students updated (their passwords were kept).";

        if ($parsed['skipped'] > 0) {
            $message .= " {$parsed['skipped']} row(s) were skipped because they had no student number.";
        }

        if ($generatedEmails > 0) {
            $message .= " The file has no email for $generatedEmails new student(s), so their school email was made from their last name and student number.";
        }

        AuditLog::record(
            'students.imported',
            $request->file('csv_file')->getClientOriginalName(),
            "$added added, $updated updated"
                . ($parsed['skipped'] > 0 ? ", {$parsed['skipped']} skipped" : '')
        );

        return redirect()->route('techlab.students.import.create')->with('success', $message);
    }

    /**
     * Read the CSV: detect the separator, remove a hidden BOM, match the
     * header names to the fields the system needs, and return clean rows.
     */
    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        $headerLine = fgets($handle);
        $headerLine = preg_replace('/^\xEF\xBB\xBF/', '', $headerLine ?: '');

        // A heading like "Name (Last, First)" contains a comma that would split
        // it into two columns, so drop any text in brackets from the header
        $headerLine = preg_replace('/\([^)]*\)/', '', $headerLine);

        $delimiter = substr_count($headerLine, ';') > substr_count($headerLine, ',') ? ';' : ',';
        $headerCells = str_getcsv($headerLine, $delimiter);

        $columns = $this->mapColumns($headerCells);

        if ($columns === null) {
            fclose($handle);
            $found = implode(', ', array_map('trim', $headerCells));

            return [
                'error' => "Could not recognise the columns in this file (found: $found). The first row must name the columns, and the file needs at least Student_ID and Name, for example: Student_ID, Name, Middle Initial, Email, Course, Year, Section.",
                'rows' => [],
                'skipped' => 0,
            ];
        }

        $rows = [];
        $skipped = 0;

        while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($line === [null]) {
                continue; // blank line
            }

            $get = fn (string $field) => isset($columns[$field]) ? trim((string) ($line[$columns[$field]] ?? '')) : '';

            $studentNumber = $get('student_id');

            if ($studentNumber === '') {
                $skipped++;
                continue;
            }

            [$lastName, $firstName] = $this->splitName($get('name'));

            $middle = $get('middle_initial');

            $rows[] = [
                'student_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'has_middle_initial' => isset($columns['middle_initial']),
                'middle_initial' => $middle !== '' ? strtoupper(substr($middle, 0, 1)) : null,
                'email' => $get('email'),
                'has_course' => isset($columns['course']),
                'course' => $get('course'),
                'has_year' => isset($columns['year']),
                'year' => $get('year'),
                'has_section' => isset($columns['section']),
                'section' => $get('section'),
            ];
        }

        fclose($handle);

        return ['error' => null, 'rows' => $rows, 'skipped' => $skipped];
    }

    /**
     * Match header names to fields. Returns [field => column index], or null
     * if the file does not even have Student ID and Name.
     */
    private function mapColumns(array $headerCells): ?array
    {
        $normalized = array_map(fn ($cell) => $this->normalize($cell), $headerCells);
        $columns = [];

        foreach (self::COLUMN_NAMES as $field => $names) {
            foreach ($normalized as $index => $cell) {
                if (in_array($cell, $names, true) && ! in_array($index, $columns, true)) {
                    $columns[$field] = $index;
                    break;
                }
            }
        }

        if (isset($columns['student_id'], $columns['name'])) {
            return $columns;
        }

        // Unrecognised header names: fall back to the standard column order
        $count = count($headerCells);

        if ($count >= 7) {
            return ['student_id' => 0, 'name' => 1, 'middle_initial' => 2, 'email' => 3, 'course' => 4, 'year' => 5, 'section' => 6];
        }

        if ($count === 6) {
            return ['student_id' => 0, 'name' => 1, 'email' => 2, 'course' => 3, 'year' => 4, 'section' => 5];
        }

        return null;
    }

    private function normalize(?string $text): string
    {
        $text = strtolower((string) $text);
        $text = preg_replace('/[^a-z0-9]+/', ' ', $text);

        return trim($text);
    }

    /**
     * "Last, First" is the standard. Without a comma, the last word is the
     * last name and the rest is the first name.
     */
    private function splitName(string $name): array
    {
        if (str_contains($name, ',')) {
            [$last, $first] = array_map('trim', explode(',', $name, 2));

            return [$last, $first];
        }

        $parts = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);
        $last = $parts ? array_pop($parts) : '';

        return [$last, implode(' ', $parts)];
    }

    private function makeEmail(string $lastName, string $studentNumber): string
    {
        $clean = strtolower(preg_replace('/[^a-z]/i', '', $lastName)) ?: 'student';

        return $clean . '.' . $studentNumber . '@valencia.sti.edu.ph';
    }
}
