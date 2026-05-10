@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="fw-bold">Edit Product</h2>
            <p class="text-muted">Modify product details, price, stock, and description.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Old Price (TZS)</label>
                                    <input type="number" name="old_price" class="form-control" value="{{ $product->old_price }}" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New Price (TZS)</label>
                                    <input type="number" name="new_price" class="form-control" value="{{ $product->new_price }}" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thumbnail Image</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @if($product->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="max-width: 200px; border-radius: 5px;">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ $product->description?->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Specifications</label>
                            <textarea name="specifications" class="form-control" rows="3">{{ $product->description?->specifications }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <textarea name="details" class="form-control" rows="3">{{ $product->description?->details }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Seller</small>
                        <p>{{ $product->seller ? $product->seller->name : 'System' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Product ID</small>
                        <p>{{ $product->id }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <p>{{ $product->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Updated</small>
                        <p>{{ $product->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Rating</small>
                        <p>{{ $product->rate }}/5</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Advertised</small>
                        <p>{{ $product->is_advertised ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
