@extends('layouts.customer')

@section('title', 'Customer Dashboard - KidsStore')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row">
           
                <!-- Main Content Grid -->
                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-8 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0 fw-bold text-primary">
                                    <i class="bi bi-clock-history me-2"></i>Recent Orders
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(isset($recentOrders) && $recentOrders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Date</th>
                                                    <th>Items</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentOrders->take(5) as $order)
                                                    <tr>
                                                        <td class="fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                        <td>{{ $order->ordered_at->format('M d, Y') }}</td>
                                                        <td>{{ $order->orderItems->count() }} items</td>
                                                        <td class="fw-bold text-primary">Tsh {{ number_format($order->total_amount, 2) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                                {{ ucfirst($order->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('customer.order.details', $order) }}" class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('customer.orders') }}" class="btn btn-primary">
                                            View All Orders <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                                        <h6 class="text-muted mb-2">No orders yet</h6>
                                        <p class="text-muted mb-3">Start shopping to see your orders here!</p>
                                        <a href="{{ route('shop') }}" class="btn btn-primary">
                                            Start Shopping <i class="bi bi-shop ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Info -->
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="bi bi-rocket-takeoff me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('shop') }}" class="btn btn-primary">
                                        <i class="bi bi-shop me-2"></i>Browse Products
                                    </a>
                                    <a href="{{ route('cart.index') }}" class="btn btn-outline-success">
                                        <i class="bi bi-cart-plus me-2"></i>Shopping Cart ({{ $cartItemsCount ?? 0 }})
                                    </a>
                                    <a href="{{ route('customer.wishlist') }}" class="btn btn-outline-danger">
                                        <i class="bi bi-heart me-2"></i>My Wishlist
                                    </a>
                                    <a href="{{ route('customer.addresses') }}" class="btn btn-outline-info">
                                        <i class="bi bi-geo-alt me-2"></i>Manage Addresses
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="bi bi-person-circle me-2"></i>Account Info
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @if(Auth::user() && Auth::user()->passport)
                                        <img src="{{ asset('storage/' . Auth::user()->passport) }}"
                                             class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&size=50"
                                             class="rounded-circle me-3">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="fw-bold text-primary">{{ $totalOrders ?? 0 }}</div>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-success">{{ $completedOrders ?? 0 }}</div>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-warning">{{ $pendingOrders ?? 0 }}</div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('customer.profile') }}" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-pencil me-1"></i>Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured/Browse Section -->
                @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary">
                                    <i class="bi bi-star-fill me-2"></i>Recommended for You
                                </h5>
                                <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-primary">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($featuredProducts->take(4) as $product)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('img/logo.png') }}"
                                                     class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-2" style="font-size: 0.9rem;">
                                                        {{ Str::limit($product->name, 40) }}
                                                    </h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold text-primary">Tsh {{ number_format($product->price, 2) }}</span>
                                                        <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-sm btn-primary">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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
        @media (max-width: 992px) {
            .container-fluid {
                margin-left: 0 !important;
            }
        }
    </style>
@endsection
