@extends('layouts.seller')

@section('title', 'Store Settings - Bravus Market Seller')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Store Settings</h1>
                    <p class="text-muted mb-0">Control VAT and delivery charges used at customer checkout.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('seller.settings.update') }}">
                @csrf
                @method('PUT')

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
                        <i class="bi bi-check2-circle me-2"></i>Save Settings
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
