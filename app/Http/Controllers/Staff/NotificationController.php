<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * The office opened its bell, so everything up to now counts as seen.
     */
    public function seen()
    {
        $department = Auth::guard('web')->user()?->department;

        if ($department) {
            $department->forceFill(['uploads_seen_at' => now()])->save();
        }

        return response()->noContent();
    }
}
