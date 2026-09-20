<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;

class ClearanceStatusController extends Controller
{
    /**
     * List all students with their overall clearance status for the active school year.
     */
    public function index(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $totalDepartments = Department::count();

        $query = Student::with(['clearanceItems' => function ($q) use ($activeSchoolYear) {
            $q->where('school_year_id', $activeSchoolYear?->id);
        }])->where('school_year_id', $activeSchoolYear?->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->where('course', $request->input('course'));
        }

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->input('year_level'));
        }

        if ($request->filled('section')) {
            $query->where('section', $request->input('section'));
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->paginate(20)->withQueryString();

        // Attach a computed "fully_cleared" flag to each student
        $students->getCollection()->transform(function ($student) use ($totalDepartments) {
            $clearedCount = $student->clearanceItems->where('status', 'cleared')->count();
            $student->fully_cleared = $totalDepartments > 0 && $clearedCount === $totalDepartments;
            $student->cleared_count = $clearedCount;
            return $student;
        });

        return view('registrar.clearance-status', [
            'students' => $students,
            'activeSchoolYear' => $activeSchoolYear,
            'totalDepartments' => $totalDepartments,
        ]);
    }
}