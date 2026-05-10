@extends('layouts.seller')

@section('title', 'Seller Dashboard - KidsStore')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Seller Dashboard</h1>
            <p class="text-muted">Welcome to your seller dashboard. View your store summary and recent activity here.</p>
        </div>
    </div>

    <!-- Key Stats Cards -->
    <div class="row gy-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #2563eb;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Products</h5>
                    <p class="display-5 mb-0 fw-bold">{{ $productCount ?? 0 }}</p>
                    <small class="text-muted">Total products</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #7c3aed;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Categories</h5>
                    <p class="display-5 mb-0 fw-bold">{{ $categoryCount ?? 0 }}</p>
                    <small class="text-muted">Active categories</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Orders</h5>
                    <p class="display-5 mb-0 fw-bold">{{ $orderCount ?? 0 }}</p>
                    <small class="text-muted">Total orders</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Revenue</h5>
                    <p class="mb-0 fw-bold" style="font-size: 1.5rem; word-break: break-word; overflow-wrap: break-word;">TSh {{ number_format($totalRevenue ?? 0, 0) }}</p>
                    <small class="text-muted">Total sales</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit/Loss Section -->
    <div class="row gy-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #3b82f6;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Estimated Cost</h5>
                    <p class="mb-0 fw-bold" style="font-size: 1.5rem; word-break: break-word;">TSh {{ number_format($totalProductsCost ?? 0, 0) }}</p>
                    <small class="text-muted">Product costs (30% estimate)</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-left: 4px solid {{ $profit >= 0 ? '#10b981' : '#ef4444' }};">
                <div class="card-body">
                    <h5 class="card-title text-muted">Profit/Loss</h5>
                    <p class="mb-0 fw-bold" style="font-size: 1.5rem; word-break: break-word; color: {{ $profit >= 0 ? '#10b981' : '#ef4444' }};">
                        {{ $profit >= 0 ? '+' : '' }}TSh {{ number_format($profit ?? 0, 0) }}
                    </p>
                    <small class="text-muted">Total earnings/loss</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #6366f1;">
                <div class="card-body">
                    <h5 class="card-title text-muted">Profit Margin</h5>
                    <p class="mb-0 fw-bold" style="font-size: 1.5rem; word-break: break-word;">{{ $profitMargin }}%</p>
                    <small class="text-muted">Return on sales</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Reports Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Monthly Reports (Last 12 Months)</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadReport()">
                        <i class="bi bi-download"></i> Download Report
                    </button>
                </div>
                <div class="card-body">
                    @if($monthlyStats && count($monthlyStats) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Month</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                        <th>Est. Cost (30%)</th>
                                        <th>Profit/Loss</th>
                                        <th>Margin %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyStats as $stat)
                                        @php
                                            $cost = $stat->revenue * 0.3;
                                            $profit = $stat->revenue - $cost;
                                            $margin = ($profit / $stat->revenue) * 100;
                                            $date = \Carbon\Carbon::createFromFormat('Y-m', $stat->month);
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $date->format('M Y') }}</strong></td>
                                            <td>{{ $stat->orders_count }}</td>
                                            <td>TSh {{ number_format($stat->revenue, 0) }}</td>
                                            <td>TSh {{ number_format($cost, 0) }}</td>
                                            <td style="color: {{ $profit >= 0 ? '#10b981' : '#ef4444' }}; font-weight: bold;">
                                                {{ $profit >= 0 ? '+' : '' }}TSh {{ number_format($profit, 0) }}
                                            </td>
                                            <td>{{ round($margin, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No monthly data available yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders && count($recentOrders) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td>{{ $order->orderItems->count() }}</td>
                                            <td>TSh {{ number_format($order->total_amount ?? 0, 0) }}</td>
                                            <td>
                                                <span class="badge {{ $order->status === 'completed' ? 'bg-success' : ($order->status === 'pending' ? 'bg-warning' : 'bg-info') }}">
                                                    {{ ucfirst($order->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Products -->
        <div class="col-lg-6 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">Recent Products</h5>
                </div>
                <div class="card-body">
                    @if($recentProducts && count($recentProducts) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProducts as $product)
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><strong>{{ $product->name }}</strong></h6>
                                            <small class="text-muted">Price: TSh {{ number_format($product->price ?? 0, 0) }}</small><br>
                                            <small class="text-muted">Stock: {{ $product->stock ?? 0 }} units</small>
                                        </div>
                                        <a href="{{ route('seller.products.show', $product->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No products yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-lg-6 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    @if($topProducts && count($topProducts) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><strong>{{ $product->name }}</strong></h6>
                                            <small class="text-muted">Sold: {{ $product->total_sold ?? 0 }} units</small><br>
                                            <small class="text-success fw-bold">Revenue: TSh {{ number_format($product->revenue ?? 0, 0) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No sales data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadReport() {
    // Get table data
    const table = document.querySelector('.table');
    let csv = [];
    
    // Get headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Get rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach(td => {
            row.push('"' + td.textContent.trim().replace(/"/g, '""') + '"');
        });
        csv.push(row.join(','));
    });
    
    // Create and download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'monthly-report-' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection