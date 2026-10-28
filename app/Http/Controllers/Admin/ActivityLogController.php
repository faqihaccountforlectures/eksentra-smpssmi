<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar riwayat log aktivitas / audit trail untuk Administrator.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $actionFilter = $request->query('action');
        $dateFilter = $request->query('date');

        $query = ActivityLog::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($actionFilter) {
            $query->where('action', $actionFilter);
        }

        if ($dateFilter) {
            $query->whereDate('created_at', $dateFilter);
        }

        $logs = $query->paginate(15)->withQueryString();

        // Daftar aksi unik untuk opsi dropdown filter
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.activity_logs.index', compact('logs', 'actions', 'search', 'actionFilter', 'dateFilter'));
    }
}
