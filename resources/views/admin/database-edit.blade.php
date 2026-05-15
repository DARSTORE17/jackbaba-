@extends('layouts.admin')

@section('title', "Edit Table: $table - Bravus Market")

@section('styles')
    <style>
        .database-edit-page {
            background: #f8f9fa;
        }

        .table-edit-card {
            border: 1px solid rgba(13, 110, 253, 0.15);
            border-radius: 0.75rem;
            background: #ffffff;
        }

        .table-edit-card .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: white;
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .data-table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .data-table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        .action-buttons .btn {
            margin: 0.125rem;
        }

        .edit-form {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .form-section {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .form-section h6 {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch !important;
            }

            .action-buttons .btn {
                margin: 0.125rem 0;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-3 px-lg-4 database-edit-page">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.database') }}">Database</a></li>
                    <li class="breadcrumb-item active">Edit Table: {{ $table }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="bi bi-table me-2"></i>Edit Table: <code>{{ $table }}</code>
            </h2>
            <p class="text-muted mb-0">View and modify data in the {{ $table }} table. Use with caution.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card table-edit-card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Table Structure
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover data-table">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Null</th>
                                    <th>Key</th>
                                    <th>Default</th>
                                    <th>Extra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($columns as $column)
                                    <tr>
                                        <td class="fw-bold">{{ $column['field'] }}</td>
                                        <td><code>{{ $column['type'] }}</code></td>
                                        <td>
                                            @if($column['null'])
                                                <span class="badge bg-warning">YES</span>
                                            @else
                                                <span class="badge bg-success">NO</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($column['key'] === 'PRI')
                                                <span class="badge bg-primary">PRIMARY</span>
                                            @elseif($column['key'] === 'UNI')
                                                <span class="badge bg-info">UNIQUE</span>
                                            @elseif($column['key'] === 'MUL')
                                                <span class="badge bg-secondary">INDEX</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $column['default'] ?: '-' }}</td>
                                        <td>{{ $column['extra'] ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card table-edit-card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Sample Data (First 10 Rows)
                    </h5>
                </div>
                <div class="card-body">
                    @if($sampleData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover data-table">
                                <thead>
                                    <tr>
                                        @foreach($sampleData->first()->toArray() as $key => $value)
                                            <th>{{ $key }}</th>
                                        @endforeach
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sampleData as $row)
                                        <tr>
                                            @foreach($row->toArray() as $key => $value)
                                                <td>
                                                    @if(is_null($value))
                                                        <em class="text-muted">NULL</em>
                                                    @elseif(strlen($value) > 50)
                                                        <span title="{{ $value }}">{{ Str::limit($value, 50) }}</span>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td>
                                                <div class="action-buttons d-flex">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editRow({{ $row->toJson() }})">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteRow('{{ $columns->where('key', 'PRI')->first()['field'] ?? $columns->first()['field'] }}', '{{ $row->{$columns->where('key', 'PRI')->first()['field'] ?? $columns->first()['field']} }}')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-database-x fs-1 text-muted"></i>
                            <h6 class="text-muted mt-3">No data in this table</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="edit-form">
                <div class="form-section">
                    <h6><i class="bi bi-plus-circle me-2"></i>Insert New Record</h6>
                    <form method="POST" action="{{ route('admin.database.update', $table) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="insert">
                        <div class="row g-3">
                            @foreach($columns as $column)
                                @if($column['extra'] !== 'auto_increment')
                                    <div class="col-md-6">
                                        <label class="form-label" for="insert_{{ $column['field'] }}">
                                            {{ $column['field'] }}
                                            @if($column['key'] === 'PRI')
                                                <span class="text-danger">*</span>
                                            @endif
                                            <small class="text-muted">({{ $column['type'] }})</small>
                                        </label>
                                        <input type="text" class="form-control" id="insert_{{ $column['field'] }}" name="data[{{ $column['field'] }}]" {{ $column['null'] ? '' : 'required' }}>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i>Insert Record
                            </button>
                        </div>
                    </form>
                </div>

                <div class="form-section" id="edit-section" style="display: none;">
                    <h6><i class="bi bi-pencil me-2"></i>Edit Record</h6>
                    <form method="POST" action="{{ route('admin.database.update', $table) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="where_column" id="edit_where_column">
                        <input type="hidden" name="where_value" id="edit_where_value">
                        <div class="row g-3" id="edit-fields">
                            <!-- Fields will be populated by JavaScript -->
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Record
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="delete-form" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger">Delete Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editRow(rowData) {
        const columns = @json($columns);
        const editFields = document.getElementById('edit-fields');
        const primaryKey = columns.find(col => col.key === 'PRI') || columns[0];

        document.getElementById('edit_where_column').value = primaryKey.field;
        document.getElementById('edit_where_value').value = rowData[primaryKey.field];

        editFields.innerHTML = '';

        columns.forEach(column => {
            if (column.extra !== 'auto_increment') {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-6';

                const label = document.createElement('label');
                label.className = 'form-label';
                label.htmlFor = `edit_${column.field}`;
                label.innerHTML = `${column.field} ${column.key === 'PRI' ? '<span class="text-danger">*</span>' : ''} <small class="text-muted">(${column.type})</small>`;

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.id = `edit_${column.field}`;
                input.name = `data[${column.field}]`;
                input.value = rowData[column.field] || '';
                if (!column.null) input.required = true;

                colDiv.appendChild(label);
                colDiv.appendChild(input);
                editFields.appendChild(colDiv);
            }
        });

        document.getElementById('edit-section').style.display = 'block';
        document.getElementById('edit-section').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelEdit() {
        document.getElementById('edit-section').style.display = 'none';
    }

    function deleteRow(whereColumn, whereValue) {
        document.getElementById('delete-form').querySelector('input[name="where_column"]').value = whereColumn;
        document.getElementById('delete-form').querySelector('input[name="where_value"]').value = whereValue;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endsection