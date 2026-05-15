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

            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('admin.database.backup') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i>Download Backup
                    </button>
                </form>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#formatModal">
                    <i class="bi bi-exclamation-triangle me-1"></i>Format Database
                </button>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i>Import Backup
                </button>
            </div>
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
                                    <th>Actions</th>
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
                                        <td>
                                            <a href="{{ route('admin.database.edit', $table['name']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </td>
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

    <!-- Format Database Modal -->
    <div class="modal fade" id="formatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-exclamation-triangle me-2"></i>Format Database
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Warning!</strong> This action will permanently delete ALL data from ALL tables in the database.
                        This cannot be undone. Make sure you have a backup before proceeding.
                    </div>
                    <p>To confirm, type <code>FORMAT_DATABASE</code> in the field below:</p>
                    <form method="POST" action="{{ route('admin.database.format') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="confirm" placeholder="Type FORMAT_DATABASE to confirm" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Format Database
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Backup Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-upload me-2"></i>Import Database Backup
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> Importing a backup will overwrite existing data.
                        Make sure you have a backup of current data before proceeding.
                    </div>
                    <form method="POST" action="{{ route('admin.database.import') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select SQL Backup File</label>
                            <input type="file" class="form-control" name="backup_file" accept=".sql,.txt" required>
                            <div class="form-text">Only .sql and .txt files are allowed.</div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-upload me-1"></i>Import Backup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0 !important;
                padding: 15px !important;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                align-items: stretch;
            }

            .d-flex.gap-2 .btn {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection
