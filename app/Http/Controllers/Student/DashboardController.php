<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the logged-in student's clearance dashboard.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        $clearanceItems = $student->clearanceItems()
            ->with('department')
            ->where('school_year_id', $student->school_year_id)
            ->get();

        $totalDepartments = $clearanceItems->count();
        $clearedCount = $clearanceItems->where('status', 'cleared')->count();
        $isFullyCleared = $totalDepartments > 0 && $clearedCount === $totalDepartments;

        return view('student.dashboard', [
            'student' => $student,
            'clearanceItems' => $clearanceItems,
            'clearedCount' => $clearedCount,
            'totalDepartments' => $totalDepartments,
            'isFullyCleared' => $isFullyCleared,
        ]);
    }
}