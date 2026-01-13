@extends('layouts.seller')

@section('title', 'Customer Management - KidsStore Seller')

@section('content')
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-people me-3"></i>Customer Management
                </h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Customer
                </button>
            </div>

            <!-- Search -->
            <div class="card mb-4 search-filter">
                <div class="card-body">
                    <form id="searchForm" method="GET" action="{{ route('seller.customers') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('seller.customers') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-repeat me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customers List</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="refreshBtn">
                            <i class="bi bi-arrow-repeat me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>S/No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Join Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $customer->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary action-btn view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewCustomerModal"
                                                    data-customer-id="{{ $customer->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning action-btn edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCustomerModal"
                                                    data-customer-id="{{ $customer->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger action-btn delete-btn"
                                                    data-customer-id="{{ $customer->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>No customers found.
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
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal zoom-modal" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addCustomerModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Add New Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="addName" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addName" name="name" required>
                        </div>
                        <div class="col-12">
                            <label for="addEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="addEmail" name="email" required>
                        </div>
                        <div class="col-12">
                            <label for="addPassword" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="addPassword" name="password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="addPasswordConfirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="addPasswordConfirm" name="password_confirmation" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveCustomerBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                        <i class="bi bi-check2 me-2"></i>Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal zoom-modal" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editCustomerModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm">
                <input type="hidden" id="editCustomerId" name="customer_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="editName" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="col-12">
                            <label for="editEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="col-12">
                            <label for="editPassword" class="form-label">New Password (leave empty to keep current)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPassword" name="password">
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="editPasswordConfirm" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPasswordConfirm" name="password_confirmation">
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning" id="updateCustomerBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                        <i class="bi bi-check2 me-2"></i>Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div class="modal zoom-modal" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewCustomerModalLabel">
                    <i class="bi bi-person me-2"></i>Customer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-large mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px;">
                        <span id="viewAvatar"></span>
                    </div>
                    <h4 id="viewName"></h4>
                    <p class="text-muted mb-0">Customer</p>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Email:</strong>
                                    </div>
                                    <div class="col-8">
                                        <span id="viewEmail"></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Join Date:</strong>
                                    </div>
                                    <div class="col-8">
                                        <span id="viewJoinDate"></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Role:</strong>
                                    </div>
                                    <div class="col-8">
                                        <span id="viewRole" class="badge bg-success">Customer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    max-width: 500px !important;
    width: 100% !important;
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
</style>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Add Customer Form Submission
    $('#addCustomerForm').submit(function(e) {
        e.preventDefault();

        const submitBtn = $('#saveCustomerBtn');
        const spinner = submitBtn.find('.spinner-border');

        submitBtn.prop('disabled', true);
        spinner.show();

        const formData = new FormData(this);

        $.ajax({
            url: '{{ route("seller.customers.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    $('#addCustomerForm')[0].reset();
                    $('#addCustomerModal').modal('hide');

                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                const message = xhr.responseJSON?.message || 'An error occurred';

                if (errors) {
                    let errorMessage = '<ul>';
                    $.each(errors, function(key, value) {
                        errorMessage += '<li>' + value[0] + '</li>';
                    });
                    errorMessage += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Edit Customer - Load data
    $('.edit-btn').click(function() {
        const customerId = $(this).data('customer-id');

        $.ajax({
            url: '{{ route("seller.customers.show", ["id" => ":id"]) }}'.replace(':id', customerId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const customer = response.customer;

                    $('#editCustomerId').val(customer.id);
                    $('#editName').val(customer.name);
                    $('#editEmail').val(customer.email);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to load customer data',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Edit Customer Form Submission
    $('#editCustomerForm').submit(function(e) {
        e.preventDefault();

        const submitBtn = $('#updateCustomerBtn');
        const spinner = submitBtn.find('.spinner-border');

        submitBtn.prop('disabled', true);
        spinner.show();

        const customerId = $('#editCustomerId').val();
        const formData = new FormData(this);

        $.ajax({
            url: '{{ route("seller.customers.update", ["id" => ":id"]) }}'.replace(':id', customerId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    $('#editCustomerModal').modal('hide');

                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                const message = xhr.responseJSON?.message || 'An error occurred';

                if (errors) {
                    let errorMessage = '<ul>';
                    $.each(errors, function(key, value) {
                        errorMessage += '<li>' + value[0] + '</li>';
                    });
                    errorMessage += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Delete Customer
    $('.delete-btn').click(function() {
        const customerId = $(this).data('customer-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! All customer data will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete customer!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("seller.customers.destroy", ["id" => ":id"]) }}'.replace(':id', customerId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete customer',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // View Customer - Load data
    $('.view-btn').click(function() {
        const customerId = $(this).data('customer-id');

        $.ajax({
            url: '{{ route("seller.customers.show", ["id" => ":id"]) }}'.replace(':id', customerId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const customer = response.customer;

                    $('#viewName').text(customer.name);
                    $('#viewEmail').text(customer.email);
                    $('#viewJoinDate').text(new Date(customer.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }));
                    $('#viewAvatar').text(customer.name.charAt(0).toUpperCase());
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to load customer data',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Toggle password visibility
    $('.toggle-password').click(function() {
        const input = $(this).siblings('input');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    // Refresh button
    $('#refreshBtn').click(function() {
        location.reload();
    });

    // Reset forms when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });

    // Add zoom animation class to modals when they are about to show
    $('.modal').on('show.bs.modal', function() {
        $(this).addClass('zoom-modal');
    });

    // Remove animation effects from buttons to prevent "cheza-cheza"
    $('[data-bs-toggle="modal"]').css({
        'transition': 'none',
        'transform': 'none'
    });
});
</script>
@endpush
