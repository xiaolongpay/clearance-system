<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Show the audit log, newest first, with search and filters.
     */
    public function index(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $query = AuditLog::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('actor_name', 'like', "%{$search}%")
                    ->orWhere('actor_department', 'like', "%{$search}%")
                    ->orWhere('target_label', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        return view('techlab.audit-logs.index', [
            'logs' => $query->paginate(25)->withQueryString(),
            'actions' => AuditLog::ACTIONS,
        ]);
    }
}
