@extends('layouts.seller')

@section('title', 'Seller Dashboard - Bravus Market')

@section('styles')
<style>
    .dashboard-card {
        border: 0 !important;
        overflow: hidden;
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: color-mix(in srgb, var(--primary-color) 12%, #ffffff);
        color: var(--primary-color);
        font-size: 1.35rem;
    }

    .metric-value {
        font-size: clamp(1.35rem, 2.6vw, 2rem);
        font-weight: 800;
        line-height: 1.1;
        color: var(--text-color);
        word-break: break-word;
    }

    .mini-table td,
    .mini-table th {
        white-space: nowrap;
    }

    .chart-box {
        min-height: 320px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Seller Dashboard</h1>
            <p class="text-muted mb-0">Live store overview, sales graphs, and recent activity.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.products') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </a>
            <a href="{{ route('seller.analytics') }}" class="btn btn-outline-primary">
                <i class="bi bi-graph-up-arrow me-2"></i>Analytics
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex justify-content-between gap-3">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Revenue</div>
                        <div class="metric-value">Tsh{{ number_format($totalRevenue ?? 0, 0) }}</div>
                        <small class="text-muted">Total sales</small>
                    </div>
                    <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex justify-content-between gap-3">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Orders</div>
                        <div class="metric-value">{{ number_format($orderCount ?? 0) }}</div>
                        <small class="text-muted">All customer orders</small>
                    </div>
                    <span class="metric-icon"><i class="bi bi-receipt"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex justify-content-between gap-3">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Products</div>
                        <div class="metric-value">{{ number_format($productCount ?? 0) }}</div>
                        <small class="text-muted">{{ number_format($lowStockCount ?? 0) }} low stock</small>
                    </div>
                    <span class="metric-icon"><i class="bi bi-box-seam"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex justify-content-between gap-3">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Profit Margin</div>
                        <div class="metric-value">{{ number_format($profitMargin ?? 0, 1) }}%</div>
                        <small class="{{ ($profit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ ($profit ?? 0) >= 0 ? '+' : '' }}Tsh{{ number_format($profit ?? 0, 0) }}
                        </small>
                    </div>
                    <span class="metric-icon"><i class="bi bi-percent"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Revenue & Orders</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadReport()">
                        <i class="bi bi-download me-1"></i>CSV
                    </button>
                </div>
                <div class="card-body chart-box">
                    <canvas id="dashboardRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body chart-box">
                    <canvas id="dashboardStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top Selling Products</h5>
                    <a href="{{ route('seller.analytics') }}" class="btn btn-sm btn-outline-primary">More</a>
                </div>
                <div class="card-body">
                    @if(($topProducts ?? collect())->count())
                        <div class="table-responsive">
                            <table class="table table-hover mini-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ number_format($product->total_sold ?? 0) }}</td>
                                            <td>Tsh{{ number_format($product->revenue ?? 0, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No sales data yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-primary">All Orders</a>
                </div>
                <div class="card-body">
                    @if(($recentOrders ?? collect())->count())
                        <div class="table-responsive">
                            <table class="table table-hover mini-table mb-0">
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
                                            <td>{{ $order->created_at->format('d M') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No orders yet.</div>
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
    const revenueCtx = document.getElementById('dashboardRevenueChart');
    const statusCtx = document.getElementById('dashboardStatusChart');
    const monthlyLabels = @json(($monthlyChart ?? collect())->pluck('label'));
    const monthlyRevenue = @json(($monthlyChart ?? collect())->pluck('revenue'));
    const monthlyOrders = @json(($monthlyChart ?? collect())->pluck('orders'));
    const statusLabels = @json(($statusBreakdown ?? collect())->pluck('status')->map(fn($status) => ucwords(str_replace('_', ' ', $status))));
    const statusValues = @json(($statusBreakdown ?? collect())->pluck('total'));

    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: monthlyRevenue,
                        backgroundColor: 'rgba(37, 99, 235, 0.45)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders',
                        data: monthlyOrders,
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
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => 'Tsh' + Number(value).toLocaleString() }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.75)',
                        'rgba(34, 197, 94, 0.75)',
                        'rgba(245, 158, 11, 0.75)',
                        'rgba(239, 68, 68, 0.75)',
                        'rgba(99, 102, 241, 0.75)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});

function downloadReport() {
    const rows = [
        ['Month', 'Revenue', 'Orders'],
        ...@json(($monthlyChart ?? collect())->map(fn($row) => [$row['label'], $row['revenue'], $row['orders']])->values())
    ];
    const csv = rows.map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'seller-dashboard-' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);
}
</script>
@endpush
