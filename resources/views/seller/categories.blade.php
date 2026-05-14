@extends('layouts.seller')

@section('content')
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-tags me-3"></i>Product Categories
                </h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Category
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <!-- Categories Grid -->
            <div class="row">
                @forelse($categories->take(4) as $category)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto me-3">
                                    <i class="bi bi-{{ $category->products_count > 0 ? 'box-seam' : 'folder' }} fa-2x text-gray-300"></i>
                                </div>
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        {{ $category->name }}</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->products_count }} Products</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-tags display-1 text-muted"></i>
                        <p class="mt-3 text-muted">No categories found.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                            <i class="bi bi-plus-circle me-2"></i>Create your first category
                        </button>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Categories Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-list-check me-2"></i>All Categories
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Products Count</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr data-category-id="{{ $category->id }}">
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ?: 'No description' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $category->products_count }}</span>
                                    </td>
                                    <td>{{ $category->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-info me-1 category-action" data-action="show" data-id="{{ $category->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning me-1 category-action" data-action="edit" data-id="{{ $category->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger category-action" data-action="delete" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-tags text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2 mb-0">No categories found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal zoom-modal" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createCategoryForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="create_name" name="name" maxlength="191" required>
                        <div class="invalid-feedback">Please provide a category name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="create_image" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="create_image" name="image" accept="image/*">
                        <div class="invalid-feedback">Please upload a valid image file.</div>
                        <img id="create_image_preview" class="img-fluid rounded mt-3 d-none" style="max-height: 200px; object-fit: cover;" alt="Image preview">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Create Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal zoom-modal" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCategoryForm" enctype="multipart/form-data">
            <input type="hidden" id="edit_category_id" name="category_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" maxlength="191" required>
                        <div class="invalid-feedback">Please provide a category name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <div class="invalid-feedback">Please upload a valid image file.</div>
                        <img id="edit_image_preview" class="img-fluid rounded mt-3 d-none" style="max-height: 200px; object-fit: cover;" alt="Image preview">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Update Category
                    </button>
                </div>
            </div>
        </form>
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

<script>
$(document).ready(function() {

    // CSRF Setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Create Category
    $('#createCategoryForm').submit(function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const spinner = submitBtn.find('.spinner-border');
        const formData = new FormData(this);

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();

        $.ajax({
            url: '{{ route("seller.categories.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createCategoryModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#create_${key}`).addClass('is-invalid');
                        $(`#create_${key}`).next('.invalid-feedback').text(errors[key][0]).show();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    $('#create_image').on('change', function() {
        const file = this.files[0];
        const preview = $('#create_image_preview');
        if (file) {
            preview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
        } else {
            preview.attr('src', '').addClass('d-none');
        }
    });

    $('#edit_image').on('change', function() {
        const file = this.files[0];
        const preview = $('#edit_image_preview');
        if (file) {
            preview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
        } else {
            preview.attr('src', '').addClass('d-none');
        }
    });

    function mediaUrl(path) {
        if (!path) {
            return '';
        }

        return path.startsWith('http') ? path : '{{ asset("storage") }}/' + path;
    }

    // Edit Category
    window.editCategory = function(id) {
        $.ajax({
            url: '{{ route("seller.categories.show", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const category = response.category;
                    $('#edit_category_id').val(category.id);
                    $('#edit_name').val(category.name);
                    $('#edit_description').val(category.description || '');

                    if (category.image) {
                        $('#edit_image_preview').attr('src', mediaUrl(category.image)).removeClass('d-none');
                    } else {
                        $('#edit_image_preview').attr('src', '').addClass('d-none');
                    }

                    $('#editCategoryModal').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load category details'
                });
            }
        });
    };

    // Update Category
    $('#editCategoryForm').submit(function(e) {
        e.preventDefault();

        const categoryId = $('#edit_category_id').val();
        const submitBtn = $(this).find('button[type="submit"]');
        const spinner = submitBtn.find('.spinner-border');
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();

        $.ajax({
            url: '{{ route("seller.categories.update", ":id") }}'.replace(':id', categoryId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editCategoryModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#edit_${key}`).addClass('is-invalid');
                        $(`#edit_${key}`).next('.invalid-feedback').text(errors[key][0]).show();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Delete Category
    window.deleteCategory = function(id, name) {
        Swal.fire({
            title: 'Delete Category',
            text: `Are you sure you want to delete "${name}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("seller.categories.destroy", ":id") }}'.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete category'
                        });
                    }
                });
            }
        });
    };

    // Show Category Details
    window.showCategory = function(id) {
        $.ajax({
            url: '{{ route("seller.categories.show", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const category = response.category;
                    let content = `
                        <strong>Name:</strong> ${category.name}<br>
                        <strong>Description:</strong> ${category.description || 'No description'}<br>
                        <strong>Products:</strong> ${category.products_count}<br>
                        <strong>Created:</strong> ${new Date(category.created_at).toLocaleDateString()}<br>
                        <strong>Slug:</strong> ${category.slug}
                    `;

                    Swal.fire({
                        title: 'Category Details',
                        html: content,
                        icon: 'info'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load category details'
                });
            }
        });
    };

    // Category button actions
    $(document).on('click', '.category-action', function() {
        const action = $(this).data('action');
        const id = $(this).data('id');
        const name = $(this).data('name');

        if (action === 'show') {
            showCategory(id);
        } else if (action === 'edit') {
            editCategory(id);
        } else if (action === 'delete') {
            deleteCategory(id, name);
        }
    });

    // Reset forms when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();
        $(this).find('img[id$="_image_preview"]').attr('src', '').addClass('d-none');
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
