<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\ClearanceItem;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentImportController extends Controller
{
    /**
     * Show the CSV upload form.
     */
    public function create()
    {
        return view('techlab.students.import');
    }

    /**
     * Handle the CSV upload and import students.
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

        $departments = Department::all();

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($handle); // skip header row

        $imported = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 7 || empty($row[0])) {
                continue;
            }

            [$studentId, $name, $middleInitialRaw, $email, $course, $year, $section] = $row;

            $nameParts = explode(',', $name, 2);
            $lastName = trim($nameParts[0] ?? '');
            $firstName = trim($nameParts[1] ?? '');

            // Use only the first character of whatever's in the Middle Initial
            // column. If it's blank, leave the student's middle initial blank
            // too — not every student has one.
            $middleInitialRaw = trim($middleInitialRaw ?? '');
            $middleInitial = $middleInitialRaw !== ''
                ? strtoupper(substr($middleInitialRaw, 0, 1))
                : null;

            $student = Student::updateOrCreate(
                ['student_number' => trim($studentId)],
                [
                    'first_name' => $firstName,
                    'middle_initial' => $middleInitial,
                    'last_name' => $lastName,
                    'email' => trim($email),
                    'course' => trim($course),
                    'year_level' => trim($year),
                    'section' => trim($section),
                    'password' => Hash::make(trim($studentId)),
                    'school_year_id' => $activeSchoolYear->id,
                    'must_change_password' => true,
                    'is_active' => true,
                ]
            );

            // Generate a clearance item for every department, if it doesn't exist yet
            foreach ($departments as $department) {
                ClearanceItem::firstOrCreate([
                    'student_id' => $student->id,
                    'department_id' => $department->id,
                    'school_year_id' => $activeSchoolYear->id,
                ], [
                    'status' => 'pending',
                ]);
            }

            $imported++;
        }

        fclose($handle);

        return redirect()->route('techlab.students.import.create')->with('success', "$imported students imported successfully.");
    }
}