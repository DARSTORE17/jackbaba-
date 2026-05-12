@extends('layouts.seller')

@section('title', 'Analytics - Bravus Market Seller')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Sales Analytics</h1>
            <p class="text-muted mb-0">Live performance from your products and orders.</p>
        </div>
        <a href="{{ route('seller.orders') }}" class="btn btn-outline-primary">
            <i class="bi bi-receipt me-2"></i>View Orders
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Revenue</div>
                    <div class="h3 mb-0">Tsh{{ number_format($totalRevenue, 0) }}</div>
                    <small class="text-muted">Completed: Tsh{{ number_format($completedRevenue, 0) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Orders</div>
                    <div class="h3 mb-0">{{ number_format($totalOrders) }}</div>
                    <small class="text-muted">{{ number_format($itemsSold) }} items sold</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Customers</div>
                    <div class="h3 mb-0">{{ number_format($totalCustomers) }}</div>
                    <small class="text-muted">Unique buyers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Products</div>
                    <div class="h3 mb-0">{{ number_format($productCount) }}</div>
                    <small class="text-muted">{{ number_format($lowStockCount) }} low stock, avg rating {{ number_format($averageRating, 1) }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Monthly Sales</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlySalesChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    @forelse($statusBreakdown as $status)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>{{ ucwords(str_replace('_', ' ', $status->status)) }}</span>
                            <span class="badge bg-primary">{{ number_format($status->total) }}</span>
                        </div>
                    @empty
                        <div class="alert alert-info mb-0">No orders yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top Products</h5>
                </div>
                <div class="card-body">
                    @if($topProducts->isEmpty())
                        <div class="alert alert-info mb-0">No product sales yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ number_format($product->quantity) }}</td>
                                            <td>Tsh{{ number_format($product->revenue, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <div class="alert alert-info mb-0">No recent orders.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'Customer' }}</td>
                                            <td>{{ $order->status_text }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlySalesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($monthlyStats->pluck('label')),
            datasets: [
                {
                    label: 'Revenue',
                    data: @json($monthlyStats->pluck('revenue')),
                    backgroundColor: 'rgba(37, 99, 235, 0.45)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Orders',
                    data: @json($monthlyStats->pluck('orders')),
                    type: 'line',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.12)',
                    tension: 0.35,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Tsh' + Number(value).toLocaleString()
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
});
</script>
@endpush
