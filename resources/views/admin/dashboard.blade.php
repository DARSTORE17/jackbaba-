@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="fw-bold">Admin Dashboard</h2>
                    <p class="text-muted">Manage the system, add sellers, and control categories from one place.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-4 border-primary shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Sellers</h6>
                    <h3 class="fw-bold">{{ $sellerCount }}</h3>
                    <p class="mb-0 text-muted">Active seller accounts</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-4 border-info shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Customers</h6>
                    <h3 class="fw-bold">{{ $customerCount }}</h3>
                    <p class="mb-0 text-muted">Registered customers</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-4 border-success shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Products</h6>
                    <h3 class="fw-bold">{{ $productCount }}</h3>
                    <p class="mb-0 text-muted">Total products in catalog</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-start border-4 border-warning shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Categories</h6>
                    <h3 class="fw-bold">{{ $categoryCount }}</h3>
                    <p class="mb-0 text-muted">System categories</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
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

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <span class="fw-bold">Quick Actions</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.sellers') }}" class="list-group-item list-group-item-action">Add or edit sellers</a>
                        <a href="{{ route('admin.categories') }}" class="list-group-item list-group-item-action">Manage categories</a>
                        <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">View/edit products</a>
                        <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action">System settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
