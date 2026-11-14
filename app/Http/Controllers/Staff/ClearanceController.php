<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\ClearanceUpdated;
use App\Models\AuditLog;
use App\Models\ClearanceItem;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClearanceController extends Controller
{
    /**
     * List students for this department's clearance queue, with search/filter.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $department = $user->department;

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        $query = ClearanceItem::with(['student', 'attachments'])
            ->where('clearance_items.department_id', $department->id)
            ->where('clearance_items.school_year_id', $activeSchoolYear?->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('course')) {
            $query->whereHas('student', fn ($q) => $q->where('course', $request->input('course')));
        }

        if ($request->filled('year_level')) {
            $query->whereHas('student', fn ($q) => $q->where('year_level', $request->input('year_level')));
        }

        if ($request->filled('section')) {
            $query->whereHas('student', fn ($q) => $q->where('section', $request->input('section')));
        }

        if ($request->input('uploads') === 'any') {
            $query->whereHas('uploads');
        }

        if ($request->input('uploads') === 'new') {
            $query->whereHas('uploads', fn ($q) => $q->whereNull('viewed_at'));
        }

        $clearanceItems = $query
            ->join('students', 'clearance_items.student_id', '=', 'students.id')
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->select('clearance_items.*')
            ->paginate(20)
            ->withQueryString();

        return view('staff.clearance.index', [
            'department' => $department,
            'activeSchoolYear' => $activeSchoolYear,
            'clearanceItems' => $clearanceItems,
            'courses' => Student::distinct()->pluck('course')->sort()->values(),
            'yearLevels' => Student::distinct()->pluck('year_level')->sort()->values(),
            'sections' => Student::distinct()->pluck('section')->sort()->values(),
        ]);
    }

    /**
     * Mark a student cleared for this department.
     */
    public function markCleared(ClearanceItem $clearanceItem)
    {
        $user = Auth::guard('web')->user();

        abort_if($clearanceItem->department_id !== $user->department_id, 403);

        $clearanceItem->update([
            'status' => 'cleared',
            'remarks' => null,
            'signed_by' => $user->id,
            'signed_at' => now(),
        ]);

        $emailed = $this->sendClearanceEmail($clearanceItem);

        AuditLog::record(
            'clearance.cleared',
            $this->describe($clearanceItem),
            $emailed ? 'Email notification sent' : 'Email notification could not be sent'
        );

        return back()->with('success', $emailed
            ? 'Student marked as cleared and notified by email.'
            : 'Student marked as cleared, but the email notification could not be sent.');
    }

    /**
     * Revert a cleared or rejected student back to pending.
     */
    public function revert(ClearanceItem $clearanceItem)
    {
        $user = Auth::guard('web')->user();

        abort_if($clearanceItem->department_id !== $user->department_id, 403);

        $previousStatus = $clearanceItem->status;

        $clearanceItem->update([
            'status' => 'pending',
            'remarks' => null,
            'signed_by' => null,
            'signed_at' => null,
        ]);

        AuditLog::record(
            'clearance.reverted',
            $this->describe($clearanceItem),
            "Changed from {$previousStatus} back to pending"
        );

        return back()->with('success', 'Status reverted to pending.');
    }

    /**
     * Mark a student rejected for this department, with a remark.
     */
    public function reject(Request $request, ClearanceItem $clearanceItem)
    {
        $user = Auth::guard('web')->user();

        abort_if($clearanceItem->department_id !== $user->department_id, 403);

        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:255'],
        ]);

        $clearanceItem->update([
            'status' => 'rejected',
            'remarks' => $validated['remarks'],
            'signed_by' => $user->id,
            'signed_at' => now(),
        ]);

        $emailed = $this->sendClearanceEmail($clearanceItem);

        AuditLog::record(
            'clearance.rejected',
            $this->describe($clearanceItem),
            'Reason: ' . $validated['remarks'] . '. '
                . ($emailed ? 'Email notification sent.' : 'Email notification could not be sent.')
        );

        return back()->with('success', $emailed
            ? 'Student marked as rejected and notified by email.'
            : 'Student marked as rejected, but the email notification could not be sent.');
    }

    /**
     * A readable label for the audit log, for example
     * "Villarba, Faith (370758) - Cashier".
     */
    private function describe(ClearanceItem $clearanceItem): string
    {
        $clearanceItem->loadMissing(['student', 'department']);

        $student = $clearanceItem->student;

        return "{$student->last_name}, {$student->first_name} ({$student->student_number}) - {$clearanceItem->department->name}";
    }

    /**
     * Send the clearance update email to the student, without blocking
     * the request if mail fails for any reason.
     */
    private function sendClearanceEmail(ClearanceItem $clearanceItem): bool
    {
        $clearanceItem->load(['student', 'department', 'schoolYear']);

        if (! $clearanceItem->student?->email) {
            return false;
        }

        try {
            Mail::to($clearanceItem->student->email)->send(new ClearanceUpdated($clearanceItem));

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
