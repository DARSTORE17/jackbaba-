@extends('layouts.customer')

@section('title', 'Order Details - KidsStore')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-12">
                <!-- Back Button -->
                <div class="mb-3">
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary back-to-orders-btn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <span class="btn-text"><i class="bi bi-arrow-left me-2"></i>Back to Orders</span>
                    </a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-receipt me-2"></i>Order Details
                            </h5>
                            <span class="badge bg-{{ $order->status_color }} fs-6">
                                {{ $order->status_text }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Order Information</h6>
                                <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Order Date:</strong> {{ $order->ordered_at->format('M d, Y H:i') }}</p>
                                <p class="mb-1"><strong>Status:</strong>
                                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span>
                                </p>
                                @if($order->estimated_delivery_date)
                                    <p class="mb-1"><strong>Estimated Delivery:</strong> {{ $order->estimated_delivery_date->format('M d, Y') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Shipping Address</h6>
                                @if($order->shippingAddress)
                                    <address class="mb-0">
                                        {{ $order->shippingAddress->full_name }}<br>
                                        {{ $order->shippingAddress->street_address }}<br>
                                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                                        {{ $order->shippingAddress->country }}
                                    </address>
                                @else
                                    <p class="text-muted">No shipping address found</p>
                                @endif
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h6 class="text-muted mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        @if($order->status === 'pending')
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : asset('img/logo.png') }}"
                                                         alt="{{ $item->product->name }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->product->description ? Str::limit($item->product->description->description, 50) : 'No description' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold">Tsh {{ number_format($item->price, 2) }}</td>
                                            <td>
                                                @if($order->status === 'pending')
                                                    <form class="d-inline update-quantity-form" data-item-id="{{ $item->id }}">
                                                        <div class="input-group input-group-sm" style="width: 120px;">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="decrease">
                                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                                <span class="btn-text">-</span>
                                                            </button>
                                                            <input type="number" class="form-control text-center quantity-input"
                                                                   value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" readonly>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="increase">
                                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                                <span class="btn-text">+</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                @else
                                                    {{ $item->quantity }}
                                                @endif
                                            </td>
                                            <td class="fw-bold text-primary">Tsh {{ number_format($item->total, 2) }}</td>
                                            @if($order->status === 'pending')
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"
                                                            data-item-id="{{ $item->id }}" data-product-name="{{ $item->product->name }}">
                                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                        <span class="btn-text">Remove</span>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="row mt-4">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Order Summary</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>Tsh {{ number_format($order->subtotal, 2) }}</span>
                                        </div>
                                        @if($order->tax_amount > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Tax:</span>
                                                <span>Tsh {{ number_format($order->tax_amount, 2) }}</span>
                                            </div>
                                        @endif
                                        @if($order->shipping_cost > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Delivery Cost:</span>
                                                <span>Tsh {{ number_format($order->shipping_cost, 2) }}</span>
                                            </div>
                                        @endif
                                        @if($order->discount_amount > 0)
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span>Discount:</span>
                                                <span>-Tsh {{ number_format($order->discount_amount, 2) }}</span>
                                            </div>
                                        @endif
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span class="text-primary">Tsh {{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                @if($order->canBeCancelled())
                                    <button type="button" class="btn btn-outline-danger" id="cancelOrderBtn">
                                        <i class="bi bi-x-circle me-2"></i>Cancel Order
                                    </button>
                                @endif
                            </div>
                            <div>
                                @if($order->status === 'completed')
                                    <button class="btn btn-primary">
                                        <i class="bi bi-star me-2"></i>Write Review
                                    </button>
                                @endif
                            </div>
                        </div>
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
                    <p>Are you sure you want to cancel this order?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                        <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" style="display: inline;">
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

    <!-- Remove Item Modal -->
    <div class="modal fade" id="removeItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove <strong id="removeProductName"></strong> from this order?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Item</button>
                    <button type="button" class="btn btn-danger" id="confirmRemoveBtn">Remove Item</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Cancel order
        document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        });

        // Update quantity
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.update-quantity-form');
                const input = form.querySelector('.quantity-input');
                const itemId = form.getAttribute('data-item-id');
                const action = this.getAttribute('data-action');
                let quantity = parseInt(input.value);

                if (action === 'increase') {
                    if (quantity < parseInt(input.max)) {
                        quantity++;
                    } else {
                        showErrorMessage('Cannot add more items. Maximum stock available: ' + input.max);
                        return;
                    }
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }

                input.value = quantity;

                // Show loading spinner
                showButtonLoading(this);

                // Make AJAX call to update quantity
                updateQuantity(itemId, quantity, form, this);
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const productName = this.getAttribute('data-product-name');

                document.getElementById('removeProductName').textContent = productName;
                document.getElementById('confirmRemoveBtn').setAttribute('data-item-id', itemId);

                const modal = new bootstrap.Modal(document.getElementById('removeItemModal'));
                modal.show();
            });
        });

        document.getElementById('confirmRemoveBtn')?.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const url = `/customer/orders/{{ $order->id }}/items/${itemId}`;

            console.log('Removing item, request to:', url); // Debug log

            // Show loading spinner
            showButtonLoading(this);

            // Make AJAX call to remove item
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading spinner
                hideButtonLoading(this);

                if (data.success) {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('removeItemModal'));
                    modal.hide();

                    // Remove the item row from the table
                    const itemRow = document.querySelector(`button[data-item-id="${itemId}"]`).closest('tr');
                    if (itemRow) {
                        itemRow.remove();
                    }

                    // Update order totals if provided
                    if (data.order_total) {
                        const orderTotalElement = document.querySelector('.fw-bold .text-primary');
                        if (orderTotalElement) {
                            orderTotalElement.textContent = `Tsh ${data.order_total}`;
                        }
                    }

                    // Check if no items left
                    const remainingRows = document.querySelectorAll('tbody tr').length;
                    if (remainingRows === 0) {
                        // Reload page if no items left (order might be cancelled)
                        location.reload();
                    } else {
                        showSuccessMessage('Item removed from order successfully!');
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to remove item'));
                }
            })
            .catch(error => {
                // Hide loading spinner
                hideButtonLoading(this);
                console.error('Error:', error);
                alert('An error occurred while removing the item.');
            });
        });

        // Handle cancel order form submission with loading
        document.querySelector('#cancelOrderModal form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');

            // Show loading on the modal button
            showButtonLoading(submitBtn);

            // Submit the form
            form.submit();
        });

        function updateQuantity(itemId, quantity, form, button) {
            const orderId = {{ $order->id }};
            const url = '/customer/orders/' + orderId + '/items/' + itemId;

            console.log('Making request to:', url); // Debug log

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading spinner
                hideButtonLoading(button);

                if (data.success) {
                    // Update the item total in the UI
                    const row = form.closest('tr');
                    const totalCell = row.querySelector('td:nth-child(4)');
                    totalCell.innerHTML = `Tsh ${data.item_total}`;

                    // Update order total
                    const orderTotalElement = document.querySelector('.fw-bold .text-primary');
                    if (orderTotalElement) {
                        orderTotalElement.textContent = `Tsh ${data.order_total}`;
                    }

                    // Show success message
                    showSuccessMessage('Quantity updated successfully!');

                } else {
                    alert('Error: ' + (data.error || 'Failed to update quantity'));
                }
            })
            .catch(error => {
                // Hide loading spinner
                hideButtonLoading(button);
                console.error('Error:', error);
                alert('An error occurred while updating the quantity.');
            });
        }

        function showButtonLoading(button) {
            const spinner = button.querySelector('.spinner-border');
            const text = button.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (text) text.classList.add('d-none');
            button.disabled = true;
        }

        function hideButtonLoading(button) {
            const spinner = button.querySelector('.spinner-border');
            const text = button.querySelector('.btn-text');
            if (spinner) spinner.classList.add('d-none');
            if (text) text.classList.remove('d-none');
            button.disabled = false;
        }

        // Show loading on back to orders button when clicked
        document.querySelector('.back-to-orders-btn')?.addEventListener('click', function() {
            showButtonLoading(this);
        });

        function showSuccessMessage(message) {
            // Create and show a temporary success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        function showErrorMessage(message) {
            // Create and show a temporary error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds for error messages
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
@endsection
