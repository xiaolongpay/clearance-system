<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * The student opened the bell, so the clearance results shown there count as seen.
     */
    public function seen(Request $request)
    {
        $student = Auth::guard('student')->user();

        Student::where('id', $student->id)->update(['notifications_seen_at' => now()]);

        return response()->noContent();
    }
}
