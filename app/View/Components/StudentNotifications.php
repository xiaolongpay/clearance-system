<?php

namespace App\View\Components;

use App\Models\ClearanceItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

/**
 * The bell on the student dashboard. A red badge shows how many clearance results
 * (cleared or rejected) arrived since the student last opened the bell.
 */
class StudentNotifications extends Component
{
    public $items;

    public int $unseen = 0;

    public ?Carbon $seenAt = null;

    public function __construct()
    {
        $this->items = collect();

        $student = Auth::guard('student')->user();

        if (! $student) {
            return;
        }

        $this->seenAt = $student->notifications_seen_at ? Carbon::parse($student->notifications_seen_at) : null;

        $results = ClearanceItem::where('student_id', $student->id)
            ->where('school_year_id', $student->school_year_id)
            ->whereIn('status', ['cleared', 'rejected']);

        $this->unseen = (clone $results)
            ->when($this->seenAt, fn ($query) => $query->where('updated_at', '>', $this->seenAt))
            ->count();

        $this->items = (clone $results)
            ->with('department')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
    }

    public function render(): View
    {
        return view('components.student-notifications');
    }
}
