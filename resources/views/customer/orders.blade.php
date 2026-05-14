@extends('layouts.customer')

@section('title', 'My Orders - KidsStore')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-receipt me-2"></i>My Orders
                        </h5>
                        <div class="d-flex gap-2">
                            <!-- Status Filter -->
                            <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                                <option value="">All Orders</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td class="fw-bold">#{{ $order->order_number }}</td>
                                                <td>{{ $order->ordered_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    @foreach($order->orderItems as $item)
                                                        <div class="d-flex align-items-center mb-1">
                                                            <img src="{{ media_url($item->product->thumbnail, asset('img/logo.png')) }}"
                                                                 alt="{{ $item->product->name }}" class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                            <small>
                                                                {{ Str::limit($item->product->name, 30) }} ({{ $item->quantity }})
                                                                <span class="text-muted d-block">
                                                                    <i class="bi bi-shop me-1"></i>{{ $item->product->seller->name ?? 'Bravus Market' }}
                                                                </span>
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="fw-bold text-primary">Tsh {{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status_color }}">
                                                        {{ $order->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('customer.order.details', $order) }}" class="btn btn-sm btn-outline-primary view-order-btn">
                                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                            <span class="btn-text">View</span>
                                                        </a>
                                                        @if($order->canBeCancelled())
                                                            <button type="button" class="btn btn-sm btn-outline-danger cancel-order-btn"
                                                                    data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}">
                                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                                <span class="btn-text">Cancel</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-receipt-x fs-1 text-muted mb-3"></i>
                                <h6 class="text-muted mb-2">No orders found</h6>
                                <p class="text-muted mb-3">You haven't placed any orders yet.</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary">
                                    Start Shopping <i class="bi bi-shop ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel order <strong id="cancelOrderNumber"></strong>?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                    <form id="cancelOrderForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            <span class="btn-text">Cancel Order</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        });

        // Cancel order functionality
        document.querySelectorAll('.cancel-order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderNumber = this.getAttribute('data-order-number');

                document.getElementById('cancelOrderNumber').textContent = orderNumber;
                document.getElementById('cancelOrderForm').action = `/customer/orders/${orderId}/cancel`;

                const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
                modal.show();
            });
        });

        // Handle cancel order form submission with loading
        document.getElementById('cancelOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');

            // Show loading on the modal button
            showButtonLoading(submitBtn);

            // Submit the form
            form.submit();
        });

        // Show loading on view order buttons when clicked
        document.querySelectorAll('.view-order-btn').forEach(button => {
            button.addEventListener('click', function() {
                showButtonLoading(this);
            });
        });

        function showButtonLoading(button) {
            const spinner = button.querySelector('.spinner-border');
            const text = button.querySelector('.btn-text') || button;
            if (spinner) spinner.classList.remove('d-none');
            if (text && text !== button) text.classList.add('d-none');
            button.disabled = true;
        }

        function hideButtonLoading(button) {
            const spinner = button.querySelector('.spinner-border');
            const text = button.querySelector('.btn-text') || button;
            if (spinner) spinner.classList.add('d-none');
            if (text && text !== button) text.classList.remove('d-none');
            button.disabled = false;
        }
    </script>
@endsection
