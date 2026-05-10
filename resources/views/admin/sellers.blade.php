@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold">Sellers</h2>
                <p class="text-muted">Add new sellers and manage existing seller accounts from the admin panel.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSellerModal">
                <i class="bi bi-plus-circle me-2"></i> New Seller
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                        <tr>
                            <td>{{ $seller->name }}</td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1 edit-seller-btn" 
                                    data-id="{{ $seller->id }}" 
                                    data-name="{{ $seller->name }}" 
                                    data-email="{{ $seller->email }}"
                                    data-bs-toggle="modal" data-bs-target="#editSellerModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this seller?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No sellers available yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $sellers->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="createSellerModal" tabindex="-1" aria-labelledby="createSellerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.sellers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSellerModalLabel">Create Seller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Seller</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editSellerModal" tabindex="-1" aria-labelledby="editSellerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editSellerForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSellerModalLabel">Edit Seller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editSellerName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editSellerEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Leave blank to keep current password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Seller</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.querySelectorAll('.edit-seller-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const form = document.getElementById('editSellerForm');
            form.action = '/admin/sellers/' + id;
            document.getElementById('editSellerName').value = name;
            document.getElementById('editSellerEmail').value = email;
        });
    });
</script>
@endsection
