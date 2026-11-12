<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * List all students with search/filter, for management.
     */
    public function index(Request $request)
    {
        $query = Student::query();

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

        $students = $query->orderBy('last_name')->paginate(25)->withQueryString();

        return view('techlab.students.index', [
            'students' => $students,
        ]);
    }

    /**
     * Reset a single student's password back to their student number (default).
     */
    public function resetPassword(Student $student)
    {
        $student->update([
            'password' => Hash::make($student->student_number),
            'must_change_password' => true,
        ]);

        AuditLog::record(
            'student.password_reset',
            "{$student->last_name}, {$student->first_name} ({$student->student_number})",
            'Password set back to the default'
        );

        return back()->with('success', "Password for {$student->first_name} {$student->last_name} has been reset to default.");
    }

    /**
     * Delete selected students via checkboxes.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $students = Student::whereIn('id', $validated['student_ids'])->get();

        $names = $students
            ->map(fn ($student) => "{$student->last_name}, {$student->first_name} ({$student->student_number})")
            ->take(30)
            ->implode('; ');

        if ($students->count() > 30) {
            $names .= '; and ' . ($students->count() - 30) . ' more';
        }

        $count = Student::whereIn('id', $validated['student_ids'])->delete();

        AuditLog::record('student.deleted', "$count student(s)", $names);

        return back()->with('success', "$count student(s) deleted.");
    }
}
