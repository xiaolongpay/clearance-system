<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    // Entries only ever have a creation time, never an update time.
    const UPDATED_AT = null;

    /**
     * Every kind of entry the system records, with the label shown on the
     * Audit Log page.
     */
    public const ACTIONS = [
        'clearance.cleared' => 'Marked cleared',
        'clearance.rejected' => 'Rejected',
        'clearance.reverted' => 'Reverted to pending',
        'students.imported' => 'Imported students',
        'student.password_reset' => 'Reset student password',
        'student.deleted' => 'Deleted students',
        'department.created' => 'Created office',
        'department.updated' => 'Updated office',
        'department.password_reset' => 'Reset office password',
        'department.deleted' => 'Deleted office',
        'school_year.created' => 'Created school year',
        'school_year.activated' => 'Activated school year',
        'school_year.deleted' => 'Deleted school year',
        'auth.login' => 'Signed in',
        'auth.logout' => 'Signed out',
        'auth.login_failed' => 'Failed sign-in',
    ];

    protected $fillable = [
        'user_id',
        'actor_name',
        'actor_department',
        'action',
        'target_label',
        'details',
        'ip_address',
    ];

    protected static function booted(): void
    {
        // Audit entries are write-once: the app cannot edit or delete them.
        static::updating(fn () => false);
        static::deleting(fn () => false);
    }

    /**
     * Record one audit entry for the currently logged-in staff member.
     * Pass $actorName only when nobody is logged in (for example a failed
     * sign-in). A logging failure never blocks the real action.
     */
    public static function record(string $action, ?string $targetLabel = null, ?string $details = null, ?string $actorName = null): void
    {
        try {
            $user = Auth::guard('web')->user();

            static::create([
                'user_id' => $user?->id,
                'actor_name' => $actorName ?? $user?->name ?? 'System',
                'actor_department' => $user?->department?->name,
                'action' => $action,
                'target_label' => $targetLabel,
                'details' => $details,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function actionLabel(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }
}
