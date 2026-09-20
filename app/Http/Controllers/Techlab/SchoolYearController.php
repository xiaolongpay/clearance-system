<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\ClearanceItem;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * List all school years and show which one is active.
     */
    public function index()
    {
        $schoolYears = SchoolYear::orderByDesc('year_label')->get();

        return view('techlab.school-years.index', [
            'schoolYears' => $schoolYears,
        ]);
    }

    /**
     * Create a new school year/semester entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_label' => ['required', 'string', 'max:255', 'unique:school_years,year_label'],
            'semester' => ['required', 'in:1st Semester,2nd Semester'],
        ]);

        SchoolYear::create([
            'year_label' => $validated['year_label'] . ' - ' . $validated['semester'],
            'is_active' => false,
        ]);

        return back()->with('success', 'School year created.');
    }

    /**
     * Activate a school year, deactivating all others, and start a fresh
     * clearance cycle for every existing student across all departments.
     */
    public function activate(SchoolYear $schoolYear)
    {
        SchoolYear::query()->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);

        // Move every existing student into this new school year
        Student::query()->update(['school_year_id' => $schoolYear->id]);

        $departments = Department::all();
        $students = Student::all();

        foreach ($students as $student) {
            foreach ($departments as $department) {
                ClearanceItem::firstOrCreate([
                    'student_id' => $student->id,
                    'department_id' => $department->id,
                    'school_year_id' => $schoolYear->id,
                ], [
                    'status' => 'pending',
                ]);
            }
        }

        return back()->with('success', "{$schoolYear->year_label} is now active. All students have a fresh clearance cycle.");
    }

    /**
     * Delete a school year and all its related clearance records.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->is_active) {
            return back()->withErrors(['school_year' => 'You cannot delete the currently active school year. Activate a different one first.']);
        }

        $schoolYear->delete();

        return back()->with('success', "{$schoolYear->year_label} has been deleted.");
    }
}