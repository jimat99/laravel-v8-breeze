<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with(['admin', 'target']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('target', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date from
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Date to
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->latest()->paginate(5)->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::with(['admin', 'target']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('target', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date from
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Date to
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // CSV header
            fputcsv($handle, [
                'Admin',
                'Action',
                'User Name',
                'User Email',
                'Date'
            ]);

            $query->orderBy('created_at', 'desc')
                ->chunk(500, function ($logs) use ($handle) {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->admin->name,
                            $log->action,
                            $log->target->name,
                            $log->target->email,
                            $log->created_at->toDateTimeString()
                        ]);
                    }
                });

            fclose($handle);
        });

        $dateFormat = now()->format('Y-m-d');
        $timeFormat = now()->format('H-i');
        $filename = "Audit-Logs_{$dateFormat}_at_{$timeFormat}.csv";

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition', 
            "attachment; filename={$filename}"
        );

        return $response;
    }
}
