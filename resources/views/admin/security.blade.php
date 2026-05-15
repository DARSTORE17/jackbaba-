@extends('layouts.admin')

@section('title', 'Security Logs - Bravus Market')

@section('styles')
    <style>
        .security-page {
            background: #f8f9fa;
        }

        .security-card {
            border: 1px solid rgba(220, 53, 69, 0.15);
            border-radius: 0.75rem;
            background: #ffffff;
        }

        .security-card .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .stats-card {
            border-radius: 0.75rem;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .severity-low { color: #6c757d; }
        .severity-medium { color: #ffc107; }
        .severity-high { color: #fd7e14; }
        .severity-critical { color: #dc3545; font-weight: bold; }

        .event-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .log-details {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            font-family: monospace;
            font-size: 0.875rem;
        }

        .alert-pattern {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 0.5rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-3 px-lg-4 security-page">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Security Logs</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-danger">
                <i class="bi bi-shield-exclamation me-2"></i>Security Monitoring
            </h2>
            <p class="text-muted mb-0">Monitor security events, threats, and suspicious activities.</p>
        </div>
    </div>

    <!-- Security Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-primary mb-2"></i>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($stats['total_logs']) }}</div>
                    <div class="text-muted small">Total Logs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning mb-2"></i>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($stats['high_risk_logs']) }}</div>
                    <div class="text-muted small">High Risk Events</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1 text-info mb-2"></i>
                    <div class="fs-4 fw-bold text-info">{{ number_format($stats['recent_logs']) }}</div>
                    <div class="text-muted small">Last 24 Hours</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-bell fs-1 text-danger mb-2"></i>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['unsent_alerts']) }}</div>
                    <div class="text-muted small">Pending Alerts</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspicious Patterns Alert -->
    @if(count($suspiciousPatterns) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-pattern">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Suspicious Activity Detected
                    </h6>
                    @foreach($suspiciousPatterns as $pattern)
                        <p class="mb-1">
                            <strong>{{ $pattern['description'] }}</strong>
                            @if(isset($pattern['count']))
                                ({{ $pattern['count'] }} incidents)
                            @endif
                        </p>
                        @if(isset($pattern['data']))
                            <ul class="mb-2">
                                @foreach($pattern['data'] as $item)
                                    <li>{{ $item->ip_address }}: {{ $item->attempts }} attempts</li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="card security-card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filters & Actions
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.security.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Severity</label>
                    <select name="severity" class="form-select">
                        <option value="">All Severities</option>
                        @foreach($severities as $severity)
                            <option value="{{ $severity }}" {{ request('severity') === $severity ? 'selected' : '' }}>
                                {{ ucfirst($severity) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Event Type</label>
                    <select name="event_type" class="form-select">
                        <option value="">All Events</option>
                        @foreach($eventTypes as $type)
                            <option value="{{ $type }}" {{ request('event_type') === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">IP Address</label>
                    <input type="text" name="ip_address" class="form-control" placeholder="192.168.1.1" value="{{ request('ip_address') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>

            <hr>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.security.export', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-download me-1"></i>Export CSV
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearModal">
                    <i class="bi bi-trash me-1"></i>Clear Old Logs
                </button>
            </div>
        </div>
    </div>

    <!-- Security Logs Table -->
    <div class="card security-card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Security Events
            </h5>
        </div>
        <div class="card-body">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Event</th>
                                <th>Severity</th>
                                <th>Message</th>
                                <th>IP Address</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $log->created_at->format('M d, H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary event-badge">
                                            {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge severity-{{ $log->severity }}">
                                            {{ ucfirst($log->severity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            {{ Str::limit($log->message, 80) }}
                                            @if(strlen($log->message) > 80)
                                                <a href="{{ route('admin.security.show', $log) }}" class="text-decoration-none ms-1">
                                                    <small>View full</small>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $log->ip_address ?: 'N/A' }}</code>
                                    </td>
                                    <td>
                                        {{ $log->username ?: 'Anonymous' }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.security.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.security.destroy', $log) }}" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this log?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-shield-check fs-1 text-success"></i>
                    <h6 class="text-muted mt-3">No security events found</h6>
                    <p class="text-muted">All clear! No security events match your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2"></i>Clear Old Security Logs
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Warning!</strong> This will permanently delete old security logs.
                    Consider exporting logs before clearing them.
                </div>
                <form method="POST" action="{{ route('admin.security.clear') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Delete logs older than (days)</label>
                        <input type="number" name="days" class="form-control" min="1" max="365" value="30" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmation</label>
                        <input type="text" name="confirm" class="form-control" placeholder="Type CLEAR_LOGS to confirm" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Clear Logs
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection