@extends('layouts.app')

@section('title', 'Order Confirmed - Bravus Market')

@section('css')
<style>
    /* Full page height */
    html, body {
        height: 100%;
    }

    .success-container {
        margin: 0 auto;
        padding: 15px;
        min-height: calc(100vh - 150px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Custom scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #2563EB;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #1D4ED8;
    }

    /* Success card styling */
    .success-card {
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-radius: 20px;
        overflow: hidden;
        max-width: 800px;
        width: 100%;
    }

    /* Success header */
    .success-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }

    .success-header h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .success-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Order details */
    .order-details {
        padding: 2rem;
        background: white;
    }

    .order-info {
        margin-bottom: 2rem;
    }

    .order-number {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: white;
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .order-number .label {
        font-size: 0.9rem;
        opacity: 0.8;
        display: block;
        margin-bottom: 0.5rem;
    }

    .order-number .number {
        font-size: 1.8rem;
        font-weight: bold;
        letter-spacing: 2px;
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-confirmed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    /* Product list */
    .product-list {
        margin: 2rem 0;
    }

    .product-item {
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #fafbfc;
        transition: all 0.2s ease;
    }

    .product-item:hover {
        border-color: #2563EB;
        box-shadow: 0 2px 8px rgba(255, 111, 145, 0.1);
    }

    .product-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Order summary */
    .order-summary {
        border: 2px solid #f1f5f9;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 2rem 0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .summary-row.total {
        border-top: 2px solid #cbd5e0;
        padding-top: 1rem;
        margin-top: 1rem;
        font-size: 1.2rem;
        font-weight: bold;
        color: #2563EB;
    }

    /* Address information */
    .address-section {
        margin: 2rem 0;
    }

    .address-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .address-card h5 {
        color: #2563EB;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    /* Action buttons */
    .action-buttons {
        margin-top: 3rem;
        text-align: center;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        border: none !important;
        border-radius: 25px !important;
        padding: 12px 30px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        margin: 0 10px;
        min-height: 48px;
        transition: all 0.3s ease;
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.24);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(37, 99, 235, 0.32);
        color: #ffffff !important;
        text-decoration: none !important;
        background: linear-gradient(135deg, #1D4ED8, #2563EB) !important;
    }

    .btn-primary-custom i {
        color: inherit !important;
        line-height: 1;
    }

    .btn-outline-custom {
        border: 2px solid #2563EB !important;
        color: #2563EB !important;
        background: #ffffff !important;
        border-radius: 25px !important;
        padding: 12px 30px !important;
        font-weight: 800 !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        margin: 0 10px;
        min-height: 48px;
        transition: all 0.3s ease;
    }

    .btn-outline-custom:hover {
        background: #2563EB;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 111, 145, 0.4);
    }

    /* Timeline for next steps */
    .next-steps {
        margin: 2rem 0;
    }

    .step {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .step-content h6 {
        margin: 0 0 0.25rem 0;
        color: #2d3748;
        font-weight: 600;
    }

    .step-content p {
        margin: 0;
        color: #718096;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-header {
            padding: 2rem 1rem;
        }

        .success-header h1 {
            font-size: 2rem;
        }

        .order-details {
            padding: 1.5rem;
        }

        .action-buttons .btn-primary-custom,
        .action-buttons .btn-outline-custom {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
    }

    /* Animation */
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .success-card {
        animation: bounceIn 0.8s ease-out;
    }
</style>
@endsection

@section('content')
    <main class="success-container">
        <div class="success-card">
            <!-- Success Header -->
            <div class="success-header">
                <i class="bi bi-check-circle-fill success-icon"></i>
                <h1>Order Confirmed!</h1>
                <p>Your order has been placed successfully</p>
            </div>

            <!-- Order Details -->
            <div class="order-details">
                <!-- Order Number -->
                <div class="order-info">
                    <div class="order-number">
                        <span class="label">Order Number</span>
                        <span class="number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Order Date:</strong> {{ $order->ordered_at->format('M d, Y \a\t H:i') }}
                        </div>
                        <div>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Products Ordered -->
                <div class="product-list">
                    <h5 class="mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Items Ordered</h5>
                    @foreach($order->orderItems as $item)
                        <div class="product-item">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->product->thumbnail
                                    ? asset('storage/' . $item->product->thumbnail)
                                    : ($item->product->media->where('is_primary', true)->first()
                                        ? asset('storage/' . $item->product->media->where('is_primary', true)->first()->file_path)
                                        : asset('img/logo.png')) }}"
                                     alt="{{ $item->product_name }}"
                                     class="me-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    <p class="mb-0 text-muted small">
                                        Quantity: {{ $item->quantity }} |
                                        Unit Price: Tsh{{ number_format($item->unit_price, 2) }} |
                                        <strong>Total: Tsh{{ number_format($item->total_price, 2) }}</strong>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-shop me-1"></i>
                                        Seller: <strong>{{ $item->product->seller->name ?? 'Bravus Market' }}</strong>
                                        @if($item->product?->seller?->phone)
                                            <span class="ms-2"><i class="bi bi-telephone me-1"></i>{{ $item->product->seller->phone }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Addresses -->
                <div class="address-section row">
                    @php
                        $shippingAddress = $order->orderAddresses->where('type', 'shipping')->first();
                        $billingAddress = $order->orderAddresses->where('type', 'billing')->first();
                    @endphp

                    @if($shippingAddress)
                    <div class="col-md-6">
                        <div class="address-card">
                            <h5><i class="bi bi-geo-alt-fill me-2"></i>Shipping Address</h5>
                            <p class="mb-1 fw-bold">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</p>
                            <p class="mb-1">{{ $shippingAddress->phone }}</p>
                            <p class="mb-1">{{ $shippingAddress->email }}</p>
                            <p class="mb-0">{{ $shippingAddress->street_address }}<br>
                               {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}<br>
                               {{ $shippingAddress->country }}</p>
                        </div>
                    </div>
                    @endif

                    @if($billingAddress && $billingAddress->id !== $shippingAddress->id)
                    <div class="col-md-6">
                        <div class="address-card">
                            <h5><i class="bi bi-credit-card-fill me-2"></i>Billing Address</h5>
                            <p class="mb-1 fw-bold">{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</p>
                            <p class="mb-1">{{ $billingAddress->phone }}</p>
                            <p class="mb-1">{{ $billingAddress->email }}</p>
                            <p class="mb-0">{{ $billingAddress->street_address }}<br>
                               {{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}<br>
                               {{ $billingAddress->country }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h5 class="mb-3 text-primary"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                    <div class="summary-row">
                        <span>Subtotal ({{ $order->orderItems->sum('quantity') }} items)</span>
                        <span>Tsh{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (18% VAT)</span>
                        <span>Tsh{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>{{ $order->shipping_cost > 0 ? 'Tsh' . number_format($order->shipping_cost, 2) : 'Free' }}</span>
                    </div>

                    @if($order->customer_notes)
                    <div class="summary-row">
                        <span>Order Notes</span>
                        <span class="text-muted" style="font-size: 0.85rem; max-width: 200px;">{{ Str::limit($order->customer_notes, 50) }}</span>
                    </div>
                    @endif

                    <div class="summary-row total">
                        <span>Total Amount</span>
                        <span>Tsh{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h5 class="mb-3 text-primary"><i class="bi bi-list-check me-2"></i>What happens next?</h5>
                    <div class="step">
                        <div class="step-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="step-content">
                            <h6>Order Confirmed</h6>
                            <p>Your order has been placed and confirmed. We're preparing your items for shipment.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="step-content">
                            <h6>Processing & Shipping</h6>
                            <p>Our team will process your order and arrange for delivery. You'll receive tracking information via email.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="bi bi-box2"></i>
                        </div>
                        <div class="step-content">
                            <h6>Delivery</h6>
                            <p>Your order will be delivered to the shipping address within 3-5 business days.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('shop') }}" class="btn-primary-custom">
                        <i class="bi bi-shop me-2"></i>Continue Shopping
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn-outline-custom">
                        <i class="bi bi-eye me-2"></i>View Order Details
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Auto-show success notification -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show success notification
            Swal.fire({
                title: 'Order Confirmed!',
                text: 'Your order has been placed successfully',
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Great!',
                timer: 4000,
                showConfirmButton: true
            });
        });
    </script>
@endsection
