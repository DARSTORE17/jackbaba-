@extends('layouts.seller')

@section('title', 'Profile & Store Settings - Bravus Market Seller')

@section('content')
@php
    $avatarUrl = !empty($seller->passport)
        ? asset('storage/' . $seller->passport)
        : 'https://ui-avatars.com/api/?name=' . urlencode($seller->name) . '&background=667eea&color=fff&size=120';
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Profile & Store Settings</h1>
                    <p class="text-muted mb-0">Manage your seller account, profile picture, password, VAT and delivery charges.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('seller.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Seller Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-12 col-md-3 text-center">
                                <img src="{{ $avatarUrl }}" alt="{{ $seller->name }}" class="rounded-circle shadow-sm mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                                <label for="passport" class="form-label d-block">Profile Picture</label>
                                <input type="file" class="form-control @error('passport') is-invalid @enderror" id="passport" name="passport" accept="image/*">
                                @error('passport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $seller->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $seller->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $seller->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lock-fill me-2"></i>Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Tax / VAT</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="seller_vat_enabled" value="0">
                            <input class="form-check-input" type="checkbox" id="sellerVatEnabled" name="seller_vat_enabled" value="1"
                                {{ old('seller_vat_enabled', $seller->seller_vat_enabled ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="sellerVatEnabled">
                                Charge VAT on my products
                            </label>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="sellerVatRate" class="form-label">VAT rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('seller_vat_rate') is-invalid @enderror"
                                    id="sellerVatRate" name="seller_vat_rate" value="{{ old('seller_vat_rate', $seller->seller_vat_rate ?? 18) }}">
                                @error('seller_vat_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Delivery</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="sellerDeliveryPayment" class="form-label">Who pays delivery?</label>
                                <select class="form-select @error('seller_delivery_payment') is-invalid @enderror"
                                    id="sellerDeliveryPayment" name="seller_delivery_payment">
                                    <option value="customer" {{ old('seller_delivery_payment', $seller->seller_delivery_payment ?? 'customer') === 'customer' ? 'selected' : '' }}>
                                        Customer pays delivery
                                    </option>
                                    <option value="free" {{ old('seller_delivery_payment', $seller->seller_delivery_payment ?? 'customer') === 'free' ? 'selected' : '' }}>
                                        Free delivery
                                    </option>
                                </select>
                                @error('seller_delivery_payment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="deliveryFeeField">
                                <label for="sellerDeliveryFee" class="form-label">Delivery fee (TZS)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('seller_delivery_fee') is-invalid @enderror"
                                    id="sellerDeliveryFee" name="seller_delivery_fee" value="{{ old('seller_delivery_fee', $seller->seller_delivery_fee ?? 5000) }}">
                                @error('seller_delivery_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deliveryPayment = document.getElementById('sellerDeliveryPayment');
        const deliveryFeeField = document.getElementById('deliveryFeeField');
        const deliveryFee = document.getElementById('sellerDeliveryFee');

        function syncDeliveryFee() {
            const isFree = deliveryPayment.value === 'free';
            deliveryFeeField.style.opacity = isFree ? '0.45' : '1';
            deliveryFee.disabled = isFree;
        }

        deliveryPayment.addEventListener('change', syncDeliveryFee);
        syncDeliveryFee();
    });
</script>
@endpush
