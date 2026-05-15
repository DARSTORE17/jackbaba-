@extends('layouts.admin')

@section('title', 'Security Log Details - Bravus Market')

@section('styles')
    <style>
        .security-detail-page {
            background: #f8f9fa;
        }

        .detail-card {
            border: 1px solid rgba(220, 53, 69, 0.15);
            border-radius: 0.75rem;
            background: #ffffff;
        }

        .detail-card .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .severity-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .severity-low {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .severity-medium {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .severity-high {
            background: linear-gradient(135deg, #fd7e14 0%, #e8680f 100%);
            color: white;
        }

        .severity-critical {
            background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
            color: white;
            font-weight: bold;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }

        .json-data {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 300px;
            overflow-y: auto;
        }

        .alert-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dc3545;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-3 px-lg-4 security-detail-page">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.security.index') }}">Security Logs</a></li>
                    <li class="breadcrumb-item active">Log #{{ $log->id }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="fw-bold text-danger">
                        <i class="bi bi-shield-exclamation me-2"></i>Security Event Details
                    </h2>
                    <p class="text-muted mb-0">Detailed information about security event #{{ $log->id }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Logs
                    </a>
                    <form method="POST" action="{{ route('admin.security.destroy', $log) }}" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this log?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Delete Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Event Overview -->
        <div class="col-12">
            <div class="card detail-card shadow-sm position-relative">
                @if($log->alert_sent)
                    <div class="alert-indicator" title="Alert sent"></div>
                @endif
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Event Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">Event Type:</span>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $log->event_type)) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">Severity:</span>
                                    <span class="badge severity-badge severity-{{ $log->severity }}">
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="detail-label">Timestamp:</span>
                                    <span>{{ $log->created_at->format('M d, Y H:i:s T') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">IP Address:</span>
                                    <code>{{ $log->ip_address ?: 'N/A' }}</code>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">User:</span>
                                    <span>{{ $log->username ?: 'Anonymous' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="detail-label">Alert Sent:</span>
                                    <span>
                                        @if($log->alert_sent)
                                            <i class="bi bi-check-circle text-success"></i> Yes
                                            @if($log->alert_sent_at)
                                                ({{ $log->alert_sent_at->diffForHumans() }})
                                            @endif
                                        @else
                                            <i class="bi bi-x-circle text-muted"></i> No
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Message -->
        <div class="col-12">
            <div class="card detail-card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-quote me-2"></i>Event Message
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-{{ $log->severity === 'critical' ? 'danger' : ($log->severity === 'high' ? 'warning' : 'info') }}">
                        <h6 class="alert-heading">{{ $log->message }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Details -->
        @if($log->url || $log->method)
            <div class="col-12">
                <div class="card detail-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-globe me-2"></i>Request Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="detail-section">
                            @if($log->url)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">URL:</span>
                                    <code>{{ $log->url }}</code>
                                </div>
                            @endif
                            @if($log->method)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="detail-label">Method:</span>
                                    <span class="badge bg-primary">{{ $log->method }}</span>
                                </div>
                            @endif
                            @if($log->user_agent)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="detail-label">User Agent:</span>
                                    <small class="text-muted">{{ Str::limit($log->user_agent, 100) }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Context Data -->
        @if($log->context)
            <div class="col-12">
                <div class="card detail-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Context Data
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="json-data">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Request Data -->
        @if($log->request_data)
            <div class="col-12">
                <div class="card detail-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-code-slash me-2"></i>Request Data
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="json-data">{{ json_encode($log->request_data, JSON_PRETTY_PRINT) }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- User Information -->
        @if($log->user)
            <div class="col-12">
                <div class="card detail-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle me-2"></i>User Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="detail-section">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="detail-label">User ID:</span>
                                <span>{{ $log->user->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="detail-label">Name:</span>
                                <span>{{ $log->user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="detail-label">Email:</span>
                                <span>{{ $log->user->email }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="detail-label">Role:</span>
                                <span>{{ $log->user->role ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection