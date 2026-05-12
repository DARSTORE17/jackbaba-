@extends('layouts.seller')

@section('title', 'Orders Management - KidsStore Seller')

@section('styles')

<style>
    /* Custom styles for modals */
    .modal-center {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .modal-content {
        animation: zoomIn 0.3s ease-out;
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .order-status-badge {
        font-size: 0.75rem;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-processing {
        background-color: #cce5ff;
        color: #0066cc;
    }

    .status-confirmed,
    .status-ready_for_pickup {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-preparing {
        background-color: #e2e3ff;
        color: #25237a;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .customer-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: scale(1.05);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2563EB, #2563EB);
        border-color: #2563EB;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* Enhanced Pagination Styles */
    .pagination {
        margin: 2px 0 0 0;
    }

    .page-link {
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.25rem 0.5rem;
        margin: 0 1px;
        border-radius: 4px !important;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .page-link:hover {
        color: #0a58ca;
        background-color: #f8f9fa;
        border-color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #2563EB, #2563EB);
        border-color: #2563EB;
        color: white;
        box-shadow: 0 2px 6px rgba(255, 111, 145, 0.3);
    }

    .page-item.active .page-link:hover {
        background: linear-gradient(135deg, #ff5a7d, #ff7693);
        border-color: #ff5a7d;
        color: white;
        transform: translateY(0);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        border-radius: 6px !important;
    }

    /* Search and Filter Styles */
    .search-filter .form-control,
    .search-filter .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .search-filter .form-control:focus,
    .search-filter .form-select:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 0.2rem rgba(255, 111, 145, 0.25);
    }

    .search-filter .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }

    .search-filter .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e2e8f0;
        border-right: none;
        color: #6c757d;
    }

    .search-filter .form-control {
        border-left: none;
    }

    .search-filter .form-control:focus {
        border-left: none;
    }
</style>
@endsection

@section('content')

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-receipt me-3"></i>Orders Management
                </h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" id="refreshBtn">
                        <i class="bi bi-arrow-repeat me-1"></i>Refresh
                    </button>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="autoRefresh">
                        <label class="form-check-label" for="autoRefresh">Auto Refresh</label>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="card mb-4 search-filter">
                <div class="card-body">
                    <form id="searchForm" method="GET" action="{{ route('seller.orders') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="ready_for_pickup" {{ request('status') == 'ready_for_pickup' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-filter me-2"></i>Filter
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-repeat me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Orders List</h5>
                    <div class="text-muted small">
                        Total: {{ $orders->total() }} orders
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $order->user->name }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $order->orderItems->count() }} item{{ $order->orderItems->count() > 1 ? 's' : '' }}
                                    </td>
                                    <td>
                                        <strong>Tsh{{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="order-status-badge status-{{ $order->status }}">
                                            {{ $order->status === 'ready_for_pickup' ? 'Out for Delivery' : $order->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            {{ $order->created_at->format('d M Y') }}
                                        </div>
                                        <div class="small">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary action-btn view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewOrderModal"
                                                    data-order-id="{{ $order->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <select class="form-select form-select-sm status-select"
                                                    data-order-id="{{ $order->id }}"
                                                    style="width: 100px;">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="ready_for_pickup" {{ $order->status == 'ready_for_pickup' ? 'selected' : '' }}>Out for Delivery</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>No orders found.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper" style="margin-top: 2px;">
                        <div class="d-flex justify-content-center">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal zoom-modal" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewOrderModalLabel">
                    <i class="bi bi-eye me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    function formatOrderStatus(status) {
        if (status === 'ready_for_pickup') {
            return 'Out for Delivery';
        }

        return status.replace(/_/g, ' ').replace(/\b\w/g, function(letter) {
            return letter.toUpperCase();
        });
    }

    // Show loading overlay
    function showLoading() {
        if (!$('#loadingOverlay').length) {
            $('body').append(`
                <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center; z-index: 1050;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
        }
        $('#loadingOverlay').css('display', 'flex');
    }

    // Hide loading overlay
    function hideLoading() {
        $('#loadingOverlay').hide();
    }

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Auto refresh functionality
    let autoRefreshInterval;
    $('#autoRefresh').change(function() {
        if ($(this).is(':checked')) {
            autoRefreshInterval = setInterval(function() {
                location.reload();
            }, 30000); // Refresh every 30 seconds
        } else {
            clearInterval(autoRefreshInterval);
        }
    });

    // Refresh button
    $('#refreshBtn').click(function() {
        location.reload();
    });

    // Status change handler
    $(document).on('change', '.status-select', function() {
        var orderId = $(this).data('order-id');
        var newStatus = $(this).val();

        showLoading();

        $.ajax({
            url: '{{ route("seller.orders.updateStatus", ":id") }}'.replace(':id', orderId),
            type: 'PATCH',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    // Update the status badge
                    var statusElement = $(`.status-select[data-order-id="${orderId}"]`).closest('tr').find('.order-status-badge');
                    statusElement.removeClass().addClass(`order-status-badge status-${newStatus}`).text(formatOrderStatus(newStatus));

                    // Update the select
                    $(`.status-select[data-order-id="${orderId}"]`).val(newStatus);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },
            error: function(xhr) {
                hideLoading();
                var errorMsg = xhr.responseJSON?.message || 'Failed to update order status';

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonText: 'OK'
                });

                // Reset the select to original value
                location.reload();
            }
        });
    });

    // Function to load order details
    function loadOrderDetails(orderId) {
        showLoading();

        $.ajax({
            url: '{{ route("seller.orders.show", ":id") }}'.replace(':id', orderId),
            type: 'GET',
            success: function(data) {
                hideLoading();

                var order = data.order;
                var itemsHtml = '';

                order.order_items.forEach(function(item, index) {
                    itemsHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>Tsh${Number(item.price).toLocaleString()}</td>
                            <td>Tsh${Number(item.total).toLocaleString()}</td>
                        </tr>
                    `;
                });

                var shippingInfo = order.order_address ? `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Shipping Address</h6>
                            <address>
                                ${order.order_address.first_name} ${order.order_address.last_name}<br>
                                ${order.order_address.address}<br>
                                ${order.order_address.city}, ${order.order_address.state} ${order.order_address.zip_code}<br>
                                ${order.order_address.country}<br>
                                Phone: ${order.order_address.phone}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Order Summary</h6>
                            <p><strong>Seller Items:</strong> Tsh${Number(order.seller_subtotal).toLocaleString()}</p>
                            <p><strong>Order Shipping:</strong> Tsh${Number(order.shipping_cost).toLocaleString()}</p>
                            <p><strong>Order Tax:</strong> Tsh${Number(order.tax_amount).toLocaleString()}</p>
                            <p><strong>Order Total:</strong> Tsh${Number(order.total_amount).toLocaleString()}</p>
                        </div>
                    </div>
                ` : '<p>No shipping information available</p>';

                var content = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Order Information</h6>
                                    <p><strong>Order ID:</strong> #${order.id}</p>
                                    <p><strong>Customer:</strong> ${order.user.name}</p>
                                    <p><strong>Email:</strong> ${order.user.email}</p>
                                    <p><strong>Phone:</strong> ${order.user.phone || 'N/A'}</p>
                                    <p><strong>Status:</strong> <span class="order-status-badge status-${order.status}">${formatOrderStatus(order.status)}</span></p>
                                    <p><strong>Payment Status:</strong> <span class="badge bg-${order.payment_status == 'paid' ? 'success' : 'warning'}">${order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}</span></p>
                                    <p><strong>Order Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                    <p><strong>Notes:</strong> ${order.notes || 'No notes'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Order Items</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${itemsHtml}
                                                <tr class="table-active">
                                                    <td colspan="4"><strong>Order Total</strong></td>
                                                    <td><strong>Tsh${Number(order.seller_subtotal).toLocaleString()}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    ${shippingInfo}
                `;

                $('#orderDetailsContent').html(content);
            },
            error: function(xhr) {
                hideLoading();
                $('#orderDetailsContent').html('<div class="alert alert-danger">Failed to load order details</div>');
            }
        });
    }

    // View Order - Load data
    $('.view-btn').click(function() {
        var orderId = $(this).data('order-id');
        loadOrderDetails(orderId);
    });
});
</script>

<style>
/* Modal zoom from center animation - Clean version */
.zoom-modal .modal-dialog {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) scale(0.5) !important;
    opacity: 0;
    transition: all 0.3s ease !important;
    margin: 0 !important;
    max-width: 1000px !important;
    max-height: 85vh !important;
    width: 95% !important;
}

.zoom-modal.show .modal-dialog {
    transform: translate(-50%, -50%) scale(1) !important;
    opacity: 1 !important;
}

.zoom-modal.modal.fade .modal-dialog {
    transition: all 0.3s ease !important;
}

.zoom-modal .modal-content {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.zoom-modal .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
}

@media (max-width: 768px) {
    .zoom-modal .modal-dialog {
        max-width: 95% !important;
        margin: 0 10px !important;
    }
}

.zoom-modal .modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.zoom-modal .modal-title {
    font-weight: 600;
    color: #343a40;
}

.zoom-modal .modal-body {
    padding: 1.5rem;
    max-height: calc(85vh - 140px);
    overflow-y: auto;
}

.zoom-modal .modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.zoom-modal .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.zoom-modal .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.zoom-modal .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.zoom-modal .btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
}

.zoom-modal .form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
}

.zoom-modal .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: none;
}

.zoom-modal .btn-close {
    background-size: 1em;
    opacity: 0.5;
}

.zoom-modal .btn-close:hover {
    opacity: 0.75;
}

.zoom-modal .modal-content {
    animation: none;
}

/* Custom button colors for different modal types */
.zoom-modal .modal-header.bg-primary .modal-title {
    color: #fff;
}

.zoom-modal .modal-header.bg-warning .modal-title {
    color: #000;
}

.zoom-modal .modal-header.bg-info .modal-title {
    color: #fff;
}

.zoom-modal .modal-header.bg-primary .btn-close {
    filter: invert(1);
}

.zoom-modal .modal-header.bg-info .btn-close {
    filter: invert(1);
}
</style>

<script>
$(document).ready(function() {
    // CSRF Setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Reset forms when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();
    });

    // Add zoom animation class to modals when they are about to show
    $('.modal').on('show.bs.modal', function() {
        $(this).addClass('zoom-modal');
    });

    // Remove zoom animation class from modals when they are hidden
    $('[data-bs-toggle="modal"]').css({
        'transition': 'none',
        'transform': 'none'
    });
});
</script>
@endpush
