@extends('layouts.customer')

@section('title', 'My Addresses - KidsStore')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-geo-alt me-2"></i>My Addresses
                        </h5>
                        <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-cart-check me-1"></i>Use at Checkout
                        </a>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                            <div class="row g-3">
                                @foreach($addresses as $address)
                                    <div class="col-lg-6">
                                        <div class="card h-100 border shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <span class="badge bg-{{ $address->type === 'billing' ? 'info' : 'success' }} text-uppercase">
                                                            {{ $address->type }}
                                                        </span>
                                                        @if($address->is_default)
                                                            <span class="badge bg-primary ms-1">Default</span>
                                                        @endif
                                                    </div>
                                                    <i class="bi bi-house-door text-primary fs-4"></i>
                                                </div>

                                                <h6 class="fw-bold mb-2">{{ $address->full_name }}</h6>
                                                <p class="mb-2 text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $address->phone }}
                                                </p>
                                                @if($address->email)
                                                    <p class="mb-2 text-muted">
                                                        <i class="bi bi-envelope me-1"></i>{{ $address->email }}
                                                    </p>
                                                @endif
                                                <address class="mb-0">
                                                    {{ $address->street_address }}<br>
                                                    {{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) {{ $address->postal_code }}@endif<br>
                                                    {{ $address->country }}
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-geo-alt fs-1 text-muted mb-3"></i>
                                <h6 class="text-muted mb-2">No saved addresses</h6>
                                <p class="text-muted mb-3">Addresses you use during checkout will appear here.</p>
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

    <style>
        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0 !important;
                padding: 15px !important;
            }
        }
    </style>
@endsection
