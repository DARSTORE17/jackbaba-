@extends('layouts.seller')

@section('title', 'Product Media Management - ' . $product->name . ' - KidsStore Seller')

@section('styles')

<style>
    .media-upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .media-upload-zone:hover {
        border-color: #0d6efd;
        background: #e3f2fd;
    }

    .media-upload-zone.dragover {
        border-color: #0d6efd;
        background: #e3f2fd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
    }

    .media-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .media-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .media-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
    }

    .media-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .media-item:hover .media-overlay {
        opacity: 1;
    }

    .btn-overlay {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #2563EB, #2563EB);
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .media-info {
        margin-top: 10px;
        text-align: center;
    }

    .media-type-badge {
        background: #6c757d;
        color: white;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.75rem;
        margin-bottom: 5px;
        display: inline-block;
    }

    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
</style>
@endsection

@section('content')

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">
                        <i class="bi bi-images me-3"></i>Product Media Management
                    </h1>
                    <p class="text-muted mb-0">Manage images and videos for: <strong>{{ $product->name }}</strong></p>
                </div>
                <a href="{{ route('seller.products') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>

            <!-- Upload Zone -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Media
                    </h5>
                </div>
                <div class="card-body">
                    <div class="media-upload-zone" id="mediaUploadZone">
                        <i class="bi bi-cloud-upload-fill mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5>Drag & Drop Files Here</h5>
                        <p class="text-muted">Or click to browse your files</p>
                        <small class="text-muted">
                            Supported formats: JPG, PNG, GIF (Images) • MP4, AVI (Videos)<br>
                            Max file size: 10MB per file
                        </small>
                        <input type="file" id="mediaInput" class="d-none" multiple accept="image/*,video/*">
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-primary" id="browseBtn">
                            <i class="bi bi-folder2-open me-2"></i>Browse Files
                        </button>
                        <button class="btn btn-outline-info" id="uploadSelectedBtn" style="display: none;">
                            <i class="bi bi-upload me-2"></i>Upload Selected ({% raw %}span id="selectedCount">0</span>{% endraw %})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Media Gallery -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Media Gallery ({{ $product->media->count() }})
                    </h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active" id="gridView">Grid</button>
                        <button class="btn btn-sm btn-outline-primary" id="listView">List</button>
                    </div>
                </div>
                <div class="card-body" id="mediaGallery">
                    @if($product->media->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-images text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5>No Media Found</h5>
                            <p class="text-muted">Start by uploading images and videos for this product</p>
                        </div>
                    @else
                        <div class="row g-3 media-grid">
                            @foreach($product->media as $media)
                                <div class="col-md-3 col-sm-6">
                                    <div class="media-item" data-media-id="{{ $media->id }}">
                                        @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->file_path) }}"
                                                 alt="Product Media" class="media-preview"
                                                 onclick="openMediaModal('{{ asset('storage/' . $media->file_path) }}', '{{ $media->type }}')">
                                        @else
                                            <div class="bg-dark d-flex align-items-center justify-content-center media-preview"
                                                 onclick="openMediaModal('{{ asset('storage/' . $media->file_path) }}', '{{ $media->type }}')">
                                                <i class="bi bi-play-circle-fill" style="font-size: 3rem; color: white;"></i>
                                            </div>
                                        @endif

                                        @if($media->is_primary)
                                            <div class="primary-badge">Primary</div>
                                        @endif

                                        <div class="media-overlay">
                                            @if(!$media->is_primary)
                                                <button class="btn btn-light btn-overlay me-1" onclick="setAsPrimary({{ $media->id }})">
                                                    <i class="bi bi-star"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-danger btn-overlay" onclick="deleteMedia({{ $media->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="media-info">
                                        <div class="media-type-badge">{{ ucfirst($media->type) }}</div>
                                        <small class="text-muted">{{ $media->created_at->format('M j, Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Media Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div id="mediaModalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    const productId = {{ $product->id }};
    let selectedFiles = [];
    let uploadQueue = [];

    // CSRF Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show alert function
    function showAlert(type, message, title = '') {
        Swal.fire({
            icon: type,
            title: title || (type === 'success' ? 'Success!' : 'Error!'),
            text: message,
            showConfirmButton: false,
            timer: 3000
        });
    }

    // File input handling
    $('#browseBtn').click(function() {
        $('#mediaInput').click();
    });

    $('#mediaInput').change(function(e) {
        const files = Array.from(e.target.files);
        handleFiles(files);
        updateSelectedCount();
    });

    // Drag and drop functionality
    const uploadZone = $('#mediaUploadZone')[0];

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadZone.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadZone.classList.remove('dragover');
    }

    uploadZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = Array.from(dt.files);
        handleFiles(files);
        updateSelectedCount();
    }

    function handleFiles(files) {
        selectedFiles = files.filter(file => {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi'];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (!allowedTypes.includes(file.type)) {
                showAlert('error', `File type not supported: ${file.name}`);
                return false;
            }

            if (file.size > maxSize) {
                showAlert('error', `File too large: ${file.name} (max 10MB)`);
                return false;
            }

            return true;
        });
    }

    function updateSelectedCount() {
        if (selectedFiles.length > 0) {
            $('#uploadSelectedBtn').show();
            $('#selectedCount').text(selectedFiles.length);
        } else {
            $('#uploadSelectedBtn').hide();
        }
    }

    // Upload files
    $('#uploadSelectedBtn').click(function() {
        uploadFiles();
    });

    function uploadFiles() {
        if (selectedFiles.length === 0) {
            showAlert('warning', 'No files selected');
            return;
        }

        // Show progress
        Swal.fire({
            title: 'Uploading...',
            html: 'Please wait while we upload your files.',
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const uploadPromises = selectedFiles.map(file => uploadSingleFile(file));

        Promise.all(uploadPromises)
            .then(() => {
                Swal.close();
                showAlert('success', 'All files uploaded successfully!');
                selectedFiles = [];
                updateSelectedCount();
                location.reload(); // Reload to show new media
            })
            .catch(error => {
                Swal.close();
                showAlert('error', `Upload failed: ${error}`);
            });
    }

    function uploadSingleFile(file) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', file.type.startsWith('image/') ? 'image' : 'video');

            $.ajax({
                url: `/seller/products/${productId}/media/upload`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        resolve(response);
                    } else {
                        reject(response.message || 'Upload failed');
                    }
                },
                error: function(xhr) {
                    reject(xhr.responseJSON?.message || 'Upload error');
                }
            });
        });
    }

    // Set as primary
    window.setAsPrimary = function(mediaId) {
        Swal.fire({
            title: 'Set as Primary?',
            text: 'This will be the main image/video for the product.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Set as Primary'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/seller/products/${productId}/media/${mediaId}/primary`,
                    type: 'PATCH',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', 'Media set as primary!');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        showAlert('error', xhr.responseJSON?.message || 'Failed to set as primary');
                    }
                });
            }
        });
    };

    // Delete media
    window.deleteMedia = function(mediaId) {
        Swal.fire({
            title: 'Delete Media?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/seller/products/${productId}/media/${mediaId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', 'Media deleted successfully!');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        showAlert('error', xhr.responseJSON?.message || 'Delete failed');
                    }
                });
            }
        });
    };

    // View media modal
    window.openMediaModal = function(src, type) {
        let content = '';

        if (type === 'image') {
            content = `<img src="${src}" class="img-fluid" style="max-height: 80vh;">`;
        } else {
            content = `<video controls class="w-100" style="max-height: 80vh;">
                <source src="${src}" type="video/mp4">
                Your browser does not support the video tag.
            </video>`;
        }

        $('#mediaModalContent').html(content);
        $('#mediaModal').modal('show');
    };

    // View toggle
    $('#gridView').click(function() {
        $('#gridView').addClass('active');
        $('#listView').removeClass('active');
        $('.media-grid').removeClass('media-list').addClass('row');
    });

    $('#listView').click(function() {
        $('#listView').addClass('active');
        $('#gridView').removeClass('active');
        $('.media-grid').removeClass('row').addClass('media-list');
    });
});
</script>
@endpush
