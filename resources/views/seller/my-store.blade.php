@extends('layouts.seller')

@section('title', 'My Store - Bravus Market Seller')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">My Store</h1>
            <p class="text-muted mb-0">Live summary for {{ $seller->name }}.</p>
        </div>
        <a href="{{ route('seller.products') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Product
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-shop me-2"></i>Store Information
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Store Logo" class="img-fluid mb-3" style="max-height: 120px;">
                    <h5 class="mb-1">{{ $seller->name }}</h5>
                    <p class="text-muted mb-3">{{ $seller->email }}</p>
                    <div class="row text-center">
                        <div class="col-4 mb-3">
                            <div class="h5 mb-0">{{ number_format($productCount) }}</div>
                            <small class="text-muted">Products</small>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="h5 mb-0">{{ number_format($orderCount) }}</div>
                            <small class="text-muted">Orders</small>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="h5 mb-0">Tsh{{ number_format($revenue, 0) }}</div>
                            <small class="text-muted">Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <a href="{{ route('seller.products') }}" class="card shadow h-100 text-decoration-none text-reset">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-primary fw-bold text-uppercase small mb-1">Manage Products</div>
                                <div class="text-muted">Add, edit, media, VAT, and delivery.</div>
                            </div>
                            <i class="bi bi-basket-fill fs-2 text-primary"></i>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 mb-4">
                    <a href="{{ route('seller.orders') }}" class="card shadow h-100 text-decoration-none text-reset">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-success fw-bold text-uppercase small mb-1">Orders</div>
                                <div class="text-muted">Process delivery and completed orders.</div>
                            </div>
                            <i class="bi bi-receipt fs-2 text-success"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>Recent Products
            </h6>
            <a href="{{ route('seller.products') }}" class="btn btn-sm btn-outline-primary">See All</a>
        </div>
        <div class="card-body">
            @if($recentProducts->isEmpty())
                <div class="alert alert-info mb-0">No products added yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>VAT</th>
                                <th>Delivery</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>Tsh{{ number_format($product->new_price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ $product->vat_enabled ? number_format($product->vat_rate, 2) . '%' : 'No VAT' }}</td>
                                    <td>{{ $product->delivery_payment === 'free' ? 'Free' : 'Tsh' . number_format($product->delivery_fee, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
