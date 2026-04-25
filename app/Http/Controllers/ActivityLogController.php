<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'role' => ['nullable', 'in:admin,manager,staff'],
            'action' => ['nullable', 'in:login,logout'],
        ]);

        if (!Schema::hasTable('activity_logs')) {
            return view('admin.logs', [
                'logs' => collect(),
                'filters' => [
                    'role' => $validated['role'] ?? '',
                    'action' => $validated['action'] ?? '',
                ],
                'tableReady' => false,
            ]);
        }

        $logs = ActivityLog::query()
            ->when($validated['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($validated['action'] ?? null, function ($query, $action) {
                $query->where('action', $action);
            })
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.logs', [
            'logs' => $logs,
            'filters' => [
                'role' => $validated['role'] ?? '',
                'action' => $validated['action'] ?? '',
            ],
            'tableReady' => true,
        ]);
    }
}
