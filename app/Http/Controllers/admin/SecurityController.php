<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use App\Services\SecurityLogger;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    protected SecurityLogger $securityLogger;

    public function __construct(SecurityLogger $securityLogger)
    {
        $this->securityLogger = $securityLogger;
    }

    public function index(Request $request)
    {
        $query = SecurityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by severity
        if ($request->filled('severity')) {
            $query->bySeverity($request->severity);
        }

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->byEventType($request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(50);

        // Get statistics
        $stats = [
            'total_logs' => SecurityLog::count(),
            'high_risk_logs' => SecurityLog::highRisk()->count(),
            'recent_logs' => SecurityLog::recent(24)->count(),
            'unsent_alerts' => SecurityLog::unsentAlerts()->count(),
        ];

        // Get unique event types and severities for filters
        $eventTypes = SecurityLog::distinct('event_type')->pluck('event_type');
        $severities = ['low', 'medium', 'high', 'critical'];

        // Check for suspicious patterns
        $suspiciousPatterns = $this->securityLogger->detectSuspiciousPatterns();

        return view('admin.security', compact(
            'logs',
            'stats',
            'eventTypes',
            'severities',
            'suspiciousPatterns'
        ));
    }

    public function show(SecurityLog $log)
    {
        return view('admin.security-show', compact('log'));
    }

    public function destroy(SecurityLog $log)
    {
        $log->delete();

        return redirect()->route('admin.security.index')
            ->with('success', 'Security log deleted successfully.');
    }

    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'confirm' => 'required|in:CLEAR_LOGS',
        ]);

        $deleted = SecurityLog::where('created_at', '<', now()->subDays($request->days))->delete();

        return redirect()->route('admin.security.index')
            ->with('success', "Cleared {$deleted} security logs older than {$request->days} days.");
    }

    public function export(Request $request)
    {
        $query = SecurityLog::orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('severity')) {
            $query->bySeverity($request->severity);
        }

        if ($request->filled('event_type')) {
            $query->byEventType($request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $logs = $query->get();

        $filename = 'security-logs-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            // CSV headers
            fputcsv($handle, [
                'ID',
                'Event Type',
                'Severity',
                'Message',
                'IP Address',
                'User',
                'URL',
                'Created At'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->event_type,
                    $log->severity,
                    $log->message,
                    $log->ip_address,
                    $log->username,
                    $log->url,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
