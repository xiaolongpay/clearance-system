<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ClearancePdfController extends Controller
{
    /**
     * Generate and download the student's clearance PDF.
     */
    public function download()
    {
        $student = Auth::guard('student')->user();

        $clearanceItems = $student->clearanceItems()
            ->with('department')
            ->where('school_year_id', $student->school_year_id)
            ->get()
            ->sortBy(fn ($item) => $item->department->name);

        $schoolYear = $student->schoolYear;

        $pdf = Pdf::loadView('student.clearance-pdf', [
            'student' => $student,
            'clearanceItems' => $clearanceItems,
            'schoolYear' => $schoolYear,
        ]);

        $filename = 'Clearance-' . $student->student_number . '.pdf';

        return $pdf->download($filename);
    }
}