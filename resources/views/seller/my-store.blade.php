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
                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold text-primary">{{ number_format($totalInitialStock) }}</div>
                            <small class="text-muted">Initial Stock</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success">{{ number_format($totalSoldQuantity) }}</div>
                            <small class="text-muted">Sold</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning">{{ number_format($totalCurrentStock) }}</div>
                            <small class="text-muted">Current</small>
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

            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-graph-up-arrow me-2"></i>Sales Trend - Last 14 Days
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $maxTrendQuantity = max(1, $salesTrend->max('quantity'));
                    @endphp
                    <div class="sales-trend">
                        @foreach($salesTrend as $day)
                            <div class="trend-day" title="{{ $day['label'] }}: {{ $day['quantity'] }} sold">
                                <div class="trend-bar-wrap">
                                    <div class="trend-bar" style="height: {{ max(6, ($day['quantity'] / $maxTrendQuantity) * 100) }}%;"></div>
                                </div>
                                <small>{{ $day['label'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-boxes me-2"></i>Stock Tracking
            </h6>
            <span class="badge bg-primary">{{ number_format($totalSoldQuantity) }} sold total</span>
        </div>
        <div class="card-body">
            @if($stockReport->isEmpty())
                <div class="alert alert-info mb-0">No products added yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Initial</th>
                                <th class="text-end">Sold</th>
                                <th class="text-end">Current</th>
                                <th class="text-end">Revenue</th>
                                <th>Stock Health</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockReport as $product)
                                @php
                                    $initialStock = max(1, (int) $product->initial_stock);
                                    $soldPercent = min(100, round(((int) $product->sold_quantity / $initialStock) * 100));
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $product->name }}</td>
                                    <td class="text-end">{{ number_format($product->initial_stock) }}</td>
                                    <td class="text-end text-success fw-bold">{{ number_format($product->sold_quantity) }}</td>
                                    <td class="text-end {{ $product->stock <= 5 ? 'text-danger' : 'text-primary' }} fw-bold">{{ number_format($product->stock) }}</td>
                                    <td class="text-end">Tsh{{ number_format($product->revenue, 0) }}</td>
                                    <td style="min-width: 180px;">
                                        <div class="progress stock-progress">
                                            <div class="progress-bar" style="width: {{ $soldPercent }}%">{{ $soldPercent }}%</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-people me-2"></i>Recent Sales and Buyers
            </h6>
        </div>
        <div class="card-body">
            @if($recentSales->isEmpty())
                <div class="alert alert-info mb-0">No sales recorded yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>Buyer</th>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($sale->ordered_at)->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($sale->ordered_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $sale->buyer_name }}</div>
                                        <small class="text-muted d-block">{{ $sale->buyer_email }}</small>
                                        <small class="text-muted">{{ $sale->buyer_phone }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $sale->product_name }}</div>
                                        <small class="text-muted">#{{ $sale->order_number }}</small>
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format($sale->quantity) }}</td>
                                    <td class="text-end">Tsh{{ number_format($sale->total_price, 0) }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $sale->status)) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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

<style>
    .sales-trend {
        display: grid;
        grid-template-columns: repeat(14, minmax(42px, 1fr));
        gap: 0.7rem;
        align-items: end;
        min-height: 180px;
        overflow-x: auto;
    }

    .trend-day {
        display: grid;
        gap: 0.45rem;
        min-width: 42px;
        text-align: center;
    }

    .trend-bar-wrap {
        display: flex;
        align-items: end;
        justify-content: center;
        height: 120px;
        border-radius: 12px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .trend-bar {
        width: 100%;
        border-radius: 12px 12px 0 0;
        background: #2563eb;
    }

    .trend-day small {
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .stock-progress {
        height: 26px;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .stock-progress .progress-bar {
        background: #16a34a;
        font-weight: 800;
    }
</style>
@endsection
