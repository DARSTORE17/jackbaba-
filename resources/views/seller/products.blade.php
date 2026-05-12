@extends('layouts.seller')

@section('title', 'Products Management - KidsStore Seller')

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

    .thumbnail-preview {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #6c757d;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .thumbnail-preview:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .thumbnail-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #dee2e6;
        color: #6c757d;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .thumbnail-placeholder:hover {
        transform: scale(1.1);
        border-color: #6c757d;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .advertised-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #2563EB, #2563EB);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .discount-badge {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .stock-indicator {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .stock-low {
        background-color: #fef08a;
        color: #a16207;
    }

    .stock-medium {
        background-color: #fef3c7;
        color: #a16207;
    }

    .stock-high {
        background-color: #dcfce7;
        color: #166534;
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

    /* Pagination Info */
    .pagination-info {
        display: inline-block;
        margin-right: 15px;
        font-size: 14px;
        color: #6c757d;
        vertical-align: middle;
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }


</style>
@endsection

@section('content')

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-box-seam me-3"></i>Products Management
                </h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                </button>
            </div>

            <!-- Search and Filter -->
            <div class="card mb-4 search-filter">
                <div class="card-body">
                    <form id="searchForm" method="GET" action="{{ route('seller.products') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-filter me-2"></i>Filter
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('seller.products') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-repeat me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Products List</h5>
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>S/No.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Discount</th>
                                    <th>Rating</th>
                                    <th>Advertise</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="product-card">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="thumbnail-preview thumbnail-clickable" data-bs-toggle="modal" data-bs-target="#viewProductModal" data-product-id="{{ $product->id }}">
                                        @else
                                            <div class="thumbnail-placeholder thumbnail-clickable" data-bs-toggle="modal" data-bs-target="#viewProductModal" data-product-id="{{ $product->id }}">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $product->name }}</strong>
                                            <small class="text-muted">{{ $product->slug }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>Tsh{{ number_format($product->new_price, 2) }}</strong>
                                            @if($product->old_price)
                                                <small class="text-decoration-line-through text-muted">
                                                    Tsh{{ number_format($product->old_price, 2) }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stock-indicator
                                            @if($product->stock <= 10) stock-low
                                            @elseif($product->stock <= 50) stock-medium
                                            @else stock-high @endif">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->discount > 0)
                                            <span class="discount-badge">
                                                {{ $product->discount }}% OFF
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No Discount</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-select rating-select" data-product-id="{{ $product->id }}" style="width: 70px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ $i == $product->rate ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input advertised-toggle"
                                                   type="checkbox"
                                                   data-product-id="{{ $product->id }}"
                                                   {{ $product->is_advertised ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                {{ $product->is_advertised ? 'Advertised' : 'Normal' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('seller.products.media', $product->id) }}"
                                               class="btn btn-sm btn-outline-success action-btn"
                                               title="Manage Media">
                                                <i class="bi bi-images"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary action-btn edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger action-btn delete-btn"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info action-btn view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewProductModal"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>No products found.
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
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal zoom-modal" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Left Column -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="addName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="addName" name="name" required>
                                </div>
                                <div class="col-6">
                                    <label for="addCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="addCategory" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="addStock" class="form-label">Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="addStock" name="stock" required>
                                </div>
                                <div class="col-12">
                                    <label for="addNewPrice" class="form-label">New Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Tsh</span>
                                        <input type="number" step="0.01" class="form-control" id="addNewPrice" name="new_price" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="addOldPrice" class="form-label">Old Price (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Tsh</span>
                                        <input type="number" step="0.01" class="form-control" id="addOldPrice" name="old_price">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="vat_enabled" value="0">
                                            <input class="form-check-input" type="checkbox" id="addVatEnabled" name="vat_enabled" value="1" {{ auth()->user()->seller_vat_enabled ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="addVatEnabled">Charge VAT for this product</label>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label for="addVatRate" class="form-label">VAT %</label>
                                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="addVatRate" name="vat_rate" value="{{ auth()->user()->seller_vat_rate ?? 18 }}">
                                            </div>
                                            <div class="col-6">
                                                <label for="addDeliveryPayment" class="form-label">Delivery</label>
                                                <select class="form-select delivery-payment" id="addDeliveryPayment" name="delivery_payment" data-fee-target="#addDeliveryFee">
                                                    <option value="customer" {{ (auth()->user()->seller_delivery_payment ?? 'customer') === 'customer' ? 'selected' : '' }}>Customer pays</option>
                                                    <option value="free" {{ (auth()->user()->seller_delivery_payment ?? 'customer') === 'free' ? 'selected' : '' }}>Free delivery</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="addDeliveryFee" class="form-label">Delivery fee (TZS)</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="addDeliveryFee" name="delivery_fee" value="{{ auth()->user()->seller_delivery_fee ?? 5000 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="addThumbnail" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="addThumbnail" name="thumbnail" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Right Column -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="addDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="addDescription" name="description" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="addSpecifications" class="form-label">Specifications</label>
                                    <textarea class="form-control" id="addSpecifications" name="specifications" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="addDetails" class="form-label">Details</label>
                                    <textarea class="form-control" id="addDetails" name="details" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveProductBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                        <i class="bi bi-check2 me-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal zoom-modal" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" enctype="multipart/form-data">
                <input type="hidden" id="editProductId" name="product_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Left Column -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="editName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="col-6">
                                    <label for="editCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editCategory" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="editStock" class="form-label">Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="editStock" name="stock" required>
                                </div>
                                <div class="col-12">
                                    <label for="editNewPrice" class="form-label">New Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Tsh</span>
                                        <input type="number" step="0.01" class="form-control" id="editNewPrice" name="new_price" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="editOldPrice" class="form-label">Old Price (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Tsh</span>
                                        <input type="number" step="0.01" class="form-control" id="editOldPrice" name="old_price">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="vat_enabled" value="0">
                                            <input class="form-check-input" type="checkbox" id="editVatEnabled" name="vat_enabled" value="1">
                                            <label class="form-check-label" for="editVatEnabled">Charge VAT for this product</label>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label for="editVatRate" class="form-label">VAT %</label>
                                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="editVatRate" name="vat_rate">
                                            </div>
                                            <div class="col-6">
                                                <label for="editDeliveryPayment" class="form-label">Delivery</label>
                                                <select class="form-select delivery-payment" id="editDeliveryPayment" name="delivery_payment" data-fee-target="#editDeliveryFee">
                                                    <option value="customer">Customer pays</option>
                                                    <option value="free">Free delivery</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="editDeliveryFee" class="form-label">Delivery fee (TZS)</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="editDeliveryFee" name="delivery_fee">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="editThumbnail" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="editThumbnail" name="thumbnail" accept="image/*">
                                    <div id="currentThumbnailPreview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Right Column -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="editDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="editSpecifications" class="form-label">Specifications</label>
                                    <textarea class="form-control" id="editSpecifications" name="specifications" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="editDetails" class="form-label">Details</label>
                                    <textarea class="form-control" id="editDetails" name="details" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning text-dark" id="updateProductBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                        <i class="bi bi-check2 me-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal zoom-modal" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewProductModalLabel">
                    <i class="bi bi-eye me-2"></i>Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <img id="viewThumbnail" src="" alt="Product Image" class="rounded mb-3" style="width: 100px !important; height: 100px !important; object-fit: cover !important; border: 2px solid #dee2e6 !important; border-radius: 50% !important;">
                                <h5 id="viewName" class="card-title"></h5>
                                <p class="card-text">
                                    <span class="badge bg-primary" id="viewCategory"></span>
                                    <span class="badge bg-success" id="viewStock"></span>
                                </p>
                                <div class="d-flex justify-content-center mb-3" id="viewRating"></div>
                                <h4 id="viewPrice" class="text-primary"></h4>
                                <small class="text-decoration-line-through text-muted" id="viewOldPrice" style="display: none;"></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Description</h6>
                                <p id="viewDescription" class="card-text"></p>

                                <h6 class="card-subtitle mb-2 text-muted mt-3">Specifications</h6>
                                <p id="viewSpecifications" class="card-text"></p>

                                <h6 class="card-subtitle mb-2 text-muted mt-3">Details</h6>
                                <p id="viewDetails" class="card-text"></p>

                                <div class="mt-3">
                                    <span class="badge bg-info" id="viewStatus"></span>
                                    <span class="badge bg-warning" id="viewDiscount"></span>
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
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Show loading overlay
    function showLoading() {
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

    function syncDeliveryFee(select) {
        const feeInput = $($(select).data('fee-target'));
        const isFree = $(select).val() === 'free';
        feeInput.prop('disabled', isFree);
        feeInput.closest('.col-12').css('opacity', isFree ? 0.45 : 1);
    }

    $('.delivery-payment').each(function() {
        syncDeliveryFee(this);
    });

    $(document).on('change', '.delivery-payment', function() {
        syncDeliveryFee(this);
    });

    // Add Product Form Submission
    $('#addProductForm').submit(function(e) {
        e.preventDefault();
        showLoading();

        const submitBtn = $('#saveProductBtn');
        const spinner = submitBtn.find('.spinner-border');

        // Show loading spinner on button
        submitBtn.prop('disabled', true);
        spinner.show();

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("seller.products.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    // Reset form
                    $('#addProductForm')[0].reset();

                    // Close modal
                    $('#addProductModal').modal('hide');

                    // Refresh page after short delay
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {
                hideLoading();
                var errors = xhr.responseJSON?.errors;
                var message = xhr.responseJSON?.message || 'An error occurred';

                if (errors) {
                    // Display validation errors
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
                // Reset button state
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Edit Product - Load data
    $('.edit-btn').click(function() {
        var productId = $(this).data('product-id');
        showLoading();

        $.ajax({
            url: '{{ route("seller.products.show", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'GET',
            success: function(response) {
                hideLoading();
                if (response.success) {
                    var product = response.product;

                    // Fill edit form
                    $('#editProductId').val(product.id);
                    $('#editName').val(product.name);
                    $('#editCategory').val(product.category_id);
                    $('#editNewPrice').val(product.new_price);
                    $('#editOldPrice').val(product.old_price || '');
                    $('#editStock').val(product.stock);
                    $('#editIsAdvertised').prop('checked', product.is_advertised);
                    $('#editVatEnabled').prop('checked', Boolean(product.vat_enabled));
                    $('#editVatRate').val(product.vat_rate || 18);
                    $('#editDeliveryPayment').val(product.delivery_payment || 'customer');
                    $('#editDeliveryFee').val(product.delivery_fee || 0);
                    syncDeliveryFee(document.getElementById('editDeliveryPayment'));

                    // Set description data
                    if (product.description) {
                        $('#editDescription').val(product.description.description || '');
                        $('#editSpecifications').val(product.description.specifications || '');
                        $('#editDetails').val(product.description.details || '');
                    }

                    // Show current thumbnail preview
                    var thumbnailPreview = '';
                    if (product.thumbnail) {
                        thumbnailPreview = `
                            <div class="mt-2">
                                <p>Current Thumbnail:</p>
                                <img src="{{ asset('storage') }}/${product.thumbnail}" alt="Current Thumbnail" class="thumbnail-preview">
                            </div>
                        `;
                    } else {
                        thumbnailPreview = '<p class="text-muted mt-2">No thumbnail available</p>';
                    }
                    $('#currentThumbnailPreview').html(thumbnailPreview);
                }
            },
            error: function(xhr) {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to load product data',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Edit Product Form Submission
    $('#editProductForm').submit(function(e) {
        e.preventDefault();
        showLoading();

        const submitBtn = $('#updateProductBtn');
        const spinner = submitBtn.find('.spinner-border');

        // Show loading spinner on button
        submitBtn.prop('disabled', true);
        spinner.show();

        var productId = $('#editProductId').val();
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("seller.products.update", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    // Close modal
                    $('#editProductModal').modal('hide');

                    // Refresh page after short delay
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {
                hideLoading();
                var errors = xhr.responseJSON?.errors;
                var message = xhr.responseJSON?.message || 'An error occurred';

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
                // Reset button state
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Delete Product
    $('.delete-btn').click(function() {
        var productId = $(this).data('product-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                $.ajax({
                    url: '{{ route("seller.products.destroy", ["id" => ":id"]) }}'.replace(':id', productId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Refresh page after short delay
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete product',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Function to load product data
    function loadProductData(productId) {
        $.ajax({
            url: '{{ route("seller.products.show", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var product = response.product;

                    // Set view data
                    $('#viewName').text(product.name);
                    $('#viewCategory').text(product.category?.name || 'N/A');

                    // Set thumbnail
                    if (product.thumbnail) {
                        $('#viewThumbnail').attr('src', '{{ asset("storage") }}/' + product.thumbnail);
                    } else {
                        $('#viewThumbnail').attr('src', 'https://via.placeholder.com/200?text=No+Image');
                    }

                    // Set price
                    var priceHtml = 'Tsh' + parseFloat(product.new_price).toFixed(2);
                    if (product.old_price) {
                        priceHtml += ' <small class="text-decoration-line-through text-muted">Tsh' + parseFloat(product.old_price).toFixed(2) + '</small>';
                        $('#viewOldPrice').text('Tsh' + parseFloat(product.old_price).toFixed(2)).show();
                    } else {
                        $('#viewOldPrice').hide();
                    }
                    $('#viewPrice').html(priceHtml);

                    // Set stock
                    $('#viewStock').text('Stock: ' + product.stock);

                    // Set rating
                    var ratingHtml = '';
                    for (var i = 1; i <= 5; i++) {
                        if (i <= product.rate) {
                            ratingHtml += '<i class="fas fa-star text-warning"></i>';
                        } else {
                            ratingHtml += '<i class="far fa-star text-muted"></i>';
                        }
                    }
                    $('#viewRating').html(ratingHtml);

                    // Set description data
                    if (product.description) {
                        $('#viewDescription').text(product.description.description || 'No description available');
                        $('#viewSpecifications').text(product.description.specifications || 'No specifications available');
                        $('#viewDetails').text(product.description.details || 'No details available');
                    } else {
                        $('#viewDescription').text('No description available');
                        $('#viewSpecifications').text('No specifications available');
                        $('#viewDetails').text('No details available');
                    }

                    // Set status
                    $('.view-checkout-badge').remove();
                    $('#viewStatus').text(product.is_advertised ? 'Advertised' : 'Normal');
                    $('#viewStatus').after(`<span class="badge bg-secondary ms-1 view-checkout-badge">${product.delivery_payment === 'free' ? 'Free delivery' : 'Delivery Tsh' + Number(product.delivery_fee || 0).toLocaleString()}</span>`);
                    $('#viewStatus').after(`<span class="badge bg-secondary ms-1 view-checkout-badge">${product.vat_enabled ? product.vat_rate + '% VAT' : 'No VAT'}</span>`);

                    // Set discount
                    if (product.discount > 0) {
                        $('#viewDiscount').text(product.discount + '% OFF');
                    } else {
                        $('#viewDiscount').text('No Discount').removeClass('bg-warning').addClass('bg-secondary');
                    }
                }
            },
            error: function(xhr) {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to load product data',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // View Product - Load data for view button or thumbnail click
    $('.view-btn, .thumbnail-clickable').click(function() {
        var productId = $(this).data('product-id');
        loadProductData(productId);
    });

    // Toggle Advertised Status
    $(document).on('change', '.advertised-toggle', function() {
        var productId = $(this).data('product-id');
        var isChecked = $(this).is(':checked');

        $.ajax({
            url: '{{ route("seller.products.toggleAdvertised", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update status',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Update Rating
    $(document).on('change', '.rating-select', function() {
        var productId = $(this).data('product-id');
        var newRating = $(this).val();

        $.ajax({
            url: '{{ route("seller.products.update", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'POST',
            data: {
                rate: newRating,
                _method: 'PUT'
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Product rating updated successfully!',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update rating',
                    confirmButtonText: 'OK'
                });
            }
        });
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
    max-width: 850px !important;
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
