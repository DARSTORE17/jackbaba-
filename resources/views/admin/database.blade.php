@extends('layouts.admin')

@section('title', 'Database Management - Bravus Market')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="bi bi-database-gear me-2"></i>Database Management
                </h4>
                <p class="text-muted mb-0">Review database tables and download a safe SQL backup.</p>
            </div>

            <form method="POST" action="{{ route('admin.database.backup') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-download me-1"></i>Download Backup
                </button>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase">Connection</div>
                        <div class="fs-5 fw-bold">{{ $connection }}</div>
                        <span class="badge bg-info">{{ strtoupper($driver) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase">Database</div>
                        <div class="fs-5 fw-bold">{{ $database ?: 'n/a' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase">Tables</div>
                        <div class="fs-3 fw-bold text-primary">{{ count($tables) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-bold text-uppercase">Rows / Size</div>
                        <div class="fs-5 fw-bold">{{ number_format($totalRows) }} rows</div>
                        <small class="text-muted">{{ $totalSize > 0 ? number_format($totalSize / 1024 / 1024, 2) . ' MB' : 'Size n/a' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm">
            <i class="bi bi-shield-lock me-2"></i>
            This page intentionally avoids arbitrary SQL execution. Use backups before making schema or data changes.
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-table me-2"></i>Tables
                </h5>
            </div>
            <div class="card-body">
                @if(count($tables) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Table</th>
                                    <th>Engine</th>
                                    <th class="text-end">Rows</th>
                                    <th class="text-end">Size</th>
                                    <th>Collation</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr>
                                        <td class="fw-bold">{{ $table['name'] }}</td>
                                        <td><span class="badge bg-secondary">{{ $table['engine'] }}</span></td>
                                        <td class="text-end">{{ number_format($table['rows']) }}</td>
                                        <td class="text-end">
                                            {{ $table['size_bytes'] > 0 ? number_format($table['size_bytes'] / 1024, 1) . ' KB' : 'n/a' }}
                                        </td>
                                        <td>{{ $table['collation'] }}</td>
                                        <td>{{ $table['updated_at'] ?: 'n/a' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-database-x fs-1 text-muted"></i>
                        <h6 class="text-muted mt-3">No table statistics available</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0 !important;
                padding: 15px !important;
            }
        }
    </style>
@endsection
