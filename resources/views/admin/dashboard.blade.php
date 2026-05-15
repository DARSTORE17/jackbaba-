@extends('layouts.admin')

@section('styles')
    <style>
        .dashboard-page {
            background: #eff6ff;
        }

        .admin-dashboard-header {
            gap: 1rem;
        }

        .dashboard-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
        }

        .dashboard-card {
            border: 1px solid rgba(13, 110, 253, 0.15);
            border-radius: 1rem;
            overflow: hidden;
        }

        .dashboard-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 170px;
        }

        .dashboard-card-title {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.78rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
        }

        .dashboard-card-value {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-card-meta {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
        }

        .dashboard-card-meta p {
            margin-bottom: 0;
            color: #6c757d;
        }

        .dashboard-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            font-size: 1.3rem;
        }

        .dashboard-card .bg-primary-soft {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .dashboard-card .bg-info-soft {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .dashboard-card .bg-success-soft {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .dashboard-card .bg-warning-soft {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .admin-quick-actions .list-group-item {
            padding: 1rem 1rem;
            border-radius: 0.85rem;
            background: #ffffff;
            border: 1px solid rgba(13, 110, 253, 0.14);
            margin-bottom: 0.75rem;
        }

        .admin-quick-actions .list-group-item:last-child {
            margin-bottom: 0;
        }

        .admin-quick-actions .list-group-item:hover {
            background: rgba(13, 110, 253, 0.05);
        }

        @media (max-width: 576px) {
            .dashboard-card .card-body {
                min-height: auto;
            }

            .dashboard-card-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-3 px-lg-4 dashboard-page">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between admin-dashboard-header mb-3">
                <div>
                    <h2 class="fw-bold">Admin Dashboard</h2>
                    <p class="text-muted mb-0">Manage the system, add sellers, and control categories from one place.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('admin.sellers') }}" class="btn btn-sm btn-primary">View sellers</a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-metrics">
        <div class="card shadow-sm dashboard-card">
            <div class="card-body">
                <div>
                    <div class="dashboard-card-title">Sellers</div>
                    <div class="dashboard-card-value">{{ $sellerCount }}</div>
                </div>
                <div class="dashboard-card-meta">
                    <p class="mb-0">Active seller accounts</p>
                    <span class="dashboard-icon bg-primary-soft">
                        <i class="bi bi-shop"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm dashboard-card">
            <div class="card-body">
                <div>
                    <div class="dashboard-card-title">Customers</div>
                    <div class="dashboard-card-value">{{ $customerCount }}</div>
                </div>
                <div class="dashboard-card-meta">
                    <p class="mb-0">Registered customers</p>
                    <span class="dashboard-icon bg-info-soft">
                        <i class="bi bi-people"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm dashboard-card">
            <div class="card-body">
                <div>
                    <div class="dashboard-card-title">Products</div>
                    <div class="dashboard-card-value">{{ $productCount }}</div>
                </div>
                <div class="dashboard-card-meta">
                    <p class="mb-0">Total products in catalog</p>
                    <span class="dashboard-icon bg-success-soft">
                        <i class="bi bi-box-seam"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm dashboard-card">
            <div class="card-body">
                <div>
                    <div class="dashboard-card-title">Categories</div>
                    <div class="dashboard-card-value">{{ $categoryCount }}</div>
                </div>
                <div class="dashboard-card-meta">
                    <p class="mb-0">System categories</p>
                    <span class="dashboard-icon bg-warning-soft">
                        <i class="bi bi-tags"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between flex-column flex-sm-row gap-2">
                    <span class="fw-bold">Latest Sellers</span>
                    <a href="{{ route('admin.sellers') }}" class="btn btn-sm btn-primary">View all sellers</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSellers as $seller)
                                <tr>
                                    <td>{{ $seller->name }}</td>
                                    <td>{{ $seller->email }}</td>
                                    <td>{{ $seller->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">No sellers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100 admin-quick-actions">
                <div class="card-header">
                    <span class="fw-bold">Quick Actions</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.sellers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Add or edit sellers</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.categories') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Manage categories</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>View/edit products</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>System settings</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.database') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Database management</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
