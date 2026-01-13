@extends('layouts.seller')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Store</h1>
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Product
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Store Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-shop me-2"></i>Store Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Store Logo" class="img-fluid mb-3" style="max-height: 120px;">
                        <h5 class="mb-1">Kids Shop</h5>
                        <p class="text-muted mb-3">Your trusted online kids store</p>
                        <div class="row text-center">
                            <div class="col-sm-6 mb-3">
                                <div class="h5 mb-0">50</div>
                                <small class="text-muted">Products</small>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="h5 mb-0">120</div>
                                <small class="text-muted">Orders</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-8 col-lg-7">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Manage Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <a href="{{ route('seller.products') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Go to Products
                                        </a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-basket-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        View Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <a href="{{ route('seller.orders') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Go to Orders
                                        </a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-receipt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Customer Insights</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <a href="{{ route('seller.customers') }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Go to Customers
                                        </a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        View Analytics</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <a href="{{ route('seller.analytics') }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Go to Analytics
                                        </a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-bar-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products Summary -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i>Recent Products
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">View and manage your recently added products.</p>
                    <a href="{{ route('seller.products') }}" class="btn btn-outline-primary">
                        <i class="bi bi-basket me-2"></i>See All Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
