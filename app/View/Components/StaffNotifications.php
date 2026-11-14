<?php

namespace App\View\Components;

use App\Models\ClearanceUpload;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class StaffNotifications extends Component
{
    public int $unseen = 0;

    public $items;

    public $seenAt = null;

    public function __construct()
    {
        $this->items = collect();

        $department = Auth::guard('web')->user()?->department;

        if (! $department) {
            return;
        }

        $this->seenAt = $department->uploads_seen_at;

        $base = ClearanceUpload::query()
            ->join('clearance_items', 'clearance_items.id', '=', 'clearance_uploads.clearance_item_id')
            ->join('students', 'students.id', '=', 'clearance_items.student_id')
            ->where('clearance_items.department_id', $department->id)
            ->where('clearance_items.school_year_id', SchoolYear::where('is_active', true)->value('id'));

        $this->unseen = (clone $base)
            ->when($this->seenAt, fn ($q) => $q->where('clearance_uploads.created_at', '>', $this->seenAt))
            ->count();

        // Never load the file data here, only what the list shows
        $this->items = $base
            ->select([
                'clearance_uploads.id',
                'clearance_uploads.kind',
                'clearance_uploads.created_at',
                'students.first_name',
                'students.last_name',
                'students.student_number',
            ])
            ->orderByDesc('clearance_uploads.created_at')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('components.staff-notifications');
    }
}
