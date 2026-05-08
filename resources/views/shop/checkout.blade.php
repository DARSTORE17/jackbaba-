@extends('layouts.app')

@section('title', 'Checkout - Bravus Market')

<style>
    /* Full page height */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        background: #f8f9fa;
    }

    .shop-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 6px;
        min-height: calc(100vh - 120px);
        background: #ffffff;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }

    /* Checkout form styling */
    .checkout-form .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        border-radius: 2px !important;
        margin-bottom: 6px;
    }

    .checkout-form .card-header {
        background: #f8f9fa !important;
        color: #495057 !important;
        border-radius: 0 !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding: 5px 8px !important;
        font-weight: normal !important;
        font-size: 0.8rem !important;
    }

    .checkout-form .card-header h5 {
        margin: 0 !important;
        font-size: 0.8rem !important;
        font-weight: normal !important;
    }

    .checkout-form .card-body {
        padding: 8px !important;
    }

    .section-title {
        font-weight: normal !important;
        color: #6c757d !important;
        margin-bottom: 6px !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }

    /* Order summary styling */
    .order-summary-card {
        position: sticky !important;
        top: 20px !important;
        border: none !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
        border-radius: 15px !important;
    }

    .order-summary-card .card-header {
        background: #ffffff !important;
        color: #2563EB !important;
        border-bottom: 2px solid #ffeef2 !important;
        padding: 20px !important;
    }

    .order-total {
        border-top: 2px solid #e2e8f0 !important;
        padding-top: 20px !important;
        margin-top: 20px !important;
    }

    .order-total .total-amount {
        color: #2563EB !important;
        font-size: 1.5rem !important;
        font-weight: bold !important;
    }

    /* Form controls styling */
    .form-label {
        font-weight: 600 !important;
        color: #4a5568 !important;
        margin-bottom: 8px !important;
        text-align: left !important;
        display: block !important;
    }

    .form-control, .form-select {
        border-radius: 10px !important;
        border: 2px solid #e2e8f0 !important;
        padding: 12px 16px !important;
        font-size: 0.95rem !important;
        transition: all 0.3s ease !important;
        background-color: #ffffff !important;
        text-align: left !important;
        width: 100% !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 3px rgba(255, 111, 145, 0.1) !important;
        outline: none !important;
    }

    .form-control:invalid {
        border-color: #ef4444 !important;
    }

    /* Form row alignment */
    .form-row {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: space-between !important;
        gap: 15px !important;
    }

    .form-field {
        flex: 1 !important;
        min-width: 200px !important;
    }

    .form-field.full-width {
        flex: 1 1 100% !important;
    }

    /* Button styling */
    .btn-place-order {
        background: linear-gradient(135deg, #2563EB, #1D4ED8) !important;
        border: none !important;
        border-radius: 25px !important;
        padding: 16px 30px !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: white !important;
        width: 100% !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }

    .btn-place-order:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(255, 111, 145, 0.4) !important;
        background: linear-gradient(135deg, #1D4ED8, #2563EB) !important;
    }

    .btn-place-order:active {
        transform: translateY(0) !important;
        box-shadow: 0 4px 15px rgba(255, 111, 145, 0.3) !important;
    }

    /* Product item styling */
    .product-item {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 15px 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 15px !important;
    }

    .product-item:last-child {
        border-bottom: none !important;
    }

    .product-item img {
        width: 60px !important;
        height: 60px !important;
        border-radius: 10px !important;
        object-fit: cover !important;
        border: 2px solid #f1f5f9 !important;
    }

    .product-item .text-primary {
        color: #2563EB !important;
    }

    /* Form text styling */
    .form-text {
        color: #6b7280 !important;
        font-size: 0.875rem !important;
        margin-top: 4px !important;
    }

    /* Shipping info */
    .shipping-info {
        background: linear-gradient(135deg, #e6fffa, #b2f5ea) !important;
        border: 1px solid #81e6d9 !important;
        border-radius: 12px !important;
        padding: 20px !important;
        margin: 20px 0 !important;
        color: #065f46 !important;
    }

    .alert-info {
        background-color: rgba(59, 130, 246, 0.1) !important;
        border-color: rgba(59, 130, 246, 0.2) !important;
        color: #1e40af !important;
        border-radius: 10px !important;
        border: none !important;
    }

    /* Loading overlay */
    .loading-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(255, 255, 255, 0.95) !important;
        z-index: 9999 !important;
        align-items: center !important;
        justify-content: center !important;
        backdrop-filter: blur(5px) !important;
    }

    .spinner-border {
        color: #2563EB !important;
        width: 3rem !important;
        height: 3rem !important;
        border-width: 0.3em !important;
    }

    /* Phone number input group */
    .input-group .form-control {
        border-radius: 0 10px 10px 0 !important;
        border-left: none !important;
    }

    .form-select + .input-group .form-control {
        border-radius: 0 10px 10px 0 !important;
    }

    /* Checkbox styling */
    .form-check-input:checked {
        background-color: #2563EB !important;
        border-color: #2563EB !important;
    }

    .form-check-input:focus {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 111, 145, 0.25) !important;
    }

    /* Card structure spacing */
    .row.g-3 {
        margin-bottom: 20px !important;
    }

    /* Heading styling */
    h1.h2 {
        color: #2563EB !important;
        font-weight: 700 !important;
        margin-bottom: 30px !important;
        font-size: 2rem !important;
    }

    h6 {
        color: #374151 !important;
        font-weight: 600 !important;
        font-size: 1.1rem !important;
    }

    /* Back button */
    .btn-outline-primary {
        border-color: #2563EB !important;
        color: #2563EB !important;
        border-radius: 25px !important;
        padding: 8px 20px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .btn-outline-primary:hover {
        background-color: #2563EB !important;
        border-color: #2563EB !important;
        color: white !important;
        transform: translateY(-2px) !important;
    }

    /* Responsive design for all screen sizes */
    @media (max-width: 1200px) {
        .shop-container {
            padding: 15px !important;
            width: 100% !important;
        }

        .row.g-4 {
            gap: 20px !important;
        }

        .checkout-form .card-body {
            padding: 25px !important;
        }

        .checkout-form .card-header h5 {
            font-size: 1.15rem !important;
        }
    }

    @media (max-width: 992px) {
        .row.g-4 {
            flex-direction: column !important;
            gap: 15px !important;
        }

        .col-lg-8, .col-lg-4 {
            flex: 1 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        .order-summary-card {
            position: static !important;
            order: -1 !important;
        }

        .checkout-form {
            max-width: 100% !important;
        }
    }

    @media (max-width: 768px) {
        .shop-container {
            padding: 10px !important;
            min-height: auto !important;
            width: 100% !important;
        }

        .checkout-form .card {
            margin-bottom: 15px !important;
        }

        .checkout-form .card-body {
            padding: 20px !important;
        }

        .checkout-form .card-header {
            padding: 15px !important;
        }

        .checkout-form .card-header h5 {
            font-size: 1.1rem !important;
        }

        h1.h2 {
            font-size: 1.5rem !important;
            margin-bottom: 20px !important;
        }

        .btn-place-order {
            padding: 14px !important;
            font-size: 1rem !important;
        }

        .product-item {
            padding: 12px 0 !important;
            gap: 12px !important;
            flex-direction: row !important;
            text-align: left !important;
        }

        .product-item img {
            width: 50px !important;
            height: 50px !important;
        }

        .row.g-3 {
            margin-bottom: 15px !important;
        }

        .form-label {
            font-size: 0.9rem !important;
            margin-bottom: 6px !important;
        }

        .form-control, .form-select {
            padding: 10px 12px !important;
            font-size: 0.9rem !important;
        }

        .order-total .total-amount {
            font-size: 1.3rem !important;
        }

        h6 {
            font-size: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .shop-container {
            padding: 8px !important;
        }

        .checkout-form .card-body {
            padding: 15px !important;
        }

        .checkout-form .card-header {
            padding: 12px !important;
        }

        .checkout-form .card-header h5 {
            font-size: 1rem !important;
        }

        h1.h2 {
            font-size: 1.3rem !important;
            margin-bottom: 15px !important;
        }

        .row.g-3 .col-12,
        .row.g-3 .col-md-4,
        .row.g-3 .col-md-6,
        .row.g-3 .col-md-8 {
            padding: 0 0 8px 0 !important;
        }

        .form-control, .form-select {
            padding: 10px !important;
            font-size: 0.85rem !important;
            min-height: 40px !important;
        }

        .form-label {
            font-size: 0.85rem !important;
        }

        .btn-place-order {
            padding: 12px !important;
            font-size: 0.95rem !important;
            border-radius: 20px !important;
        }

        .product-item {
            padding: 10px 0 !important;
            gap: 8px !important;
        }

        .product-item h6 {
            font-size: 0.85rem !important;
        }

        .product-item img {
            width: 40px !important;
            height: 40px !important;
        }

        .order-total {
            font-size: 0.9rem !important;
        }

        .order-total .total-amount {
            font-size: 1.2rem !important;
        }

        .btn-outline-primary {
            padding: 6px 15px !important;
            font-size: 0.9rem !important;
        }

        h6 {
            font-size: 0.95rem !important;
        }

        .form-text {
            font-size: 0.8rem !important;
        }

        textarea.form-control {
            min-height: 80px !important;
        }
    }

    @media (max-width: 480px) {
        .shop-container {
            padding: 5px !important;
        }

        .checkout-form .card {
            border-radius: 10px !important;
        }

        .checkout-form .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 10px !important;
        }

        .checkout-form .card-body {
            padding: 12px !important;
        }

        .d-flex.justify-content-between.align-items-center {
            flex-direction: column !important;
            gap: 10px !important;
            align-items: stretch !important;
        }

        .btn-outline-primary {
            align-self: flex-start !important;
            width: auto !important;
        }

        h1.h2 {
            text-align: center !important;
        }

        .product-item {
            flex-direction: column !important;
            text-align: center !important;
            gap: 6px !important;
            align-items: center !important;
        }

        .product-item .flex-grow-1 {
            text-align: center !important;
        }

        .order-summary-card .card-header h5 {
            font-size: 0.9rem !important;
        }

        /* Stack order summary better on small screens */
        .order-total > div {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 8px !important;
        }

        .text-start, .text-end {
            text-align: center !important;
        }
    }

    /* Fix for very small devices */
    @media (max-width: 360px) {
        .shop-container {
            padding: 3px !important;
        }

        .checkout-form .card-body {
            padding: 8px !important;
        }

        .form-control, .form-select {
            font-size: 14px !important;
            padding: 8px !important;
        }

        .btn-place-order {
            padding: 10px !important;
            font-size: 0.9rem !important;
        }

        .checkout-form .card-header h5 {
            font-size: 0.9rem !important;
        }

        h1.h2 {
            font-size: 1.2rem !important;
        }
    }

    /* Landscape orientation on mobile */
    @media screen and (max-height: 500px) and (orientation: landscape) {
        .shop-container {
            padding: 5px !important;
        }

        .checkout-form .card-body {
            padding: 15px !important;
        }

        .order-summary-card {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
    }

    /* SweetAlert wide popup styling */
    .swal-wide .swal2-popup {
        width: 99% !important;
        max-width: none !important;
        /* Ensure proper overflow handling */
        overflow: visible !important;
        left: 5px !important;
        right: 5px !important;
        top: 10px !important;
        bottom: 10px !important;
        height: calc(100vh - 20px) !important;
        max-height: calc(100vh - 20px) !important;
    }

    /* Desktop two-column layout */
    @media (min-width: 769px) {
        .swal-wide .checkout-popup-layout {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            gap: 30px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .swal-wide .customer-info-column {
            flex: 1 !important;
            max-width: calc(100% - 330px) !important;
            min-width: 300px !important;
        }

        .swal-wide .order-summary-column {
            flex: 0 0 300px !important;
            width: 300px !important;
            max-width: 300px !important;
        }
    }

    /* Ensure the order summary card doesn't exceed column width */
    .swal-wide .order-summary-column .card {
        max-width: 100% !important;
        margin: 0 !important;
    }

    @media (max-width: 768px) {
        .swal-wide .swal2-popup {
            width: 95% !important;
            max-width: none !important;
        }
    }

    @media (max-width: 480px) {
        .swal-wide .swal2-popup {
            width: 98% !important;
            height: 90vh !important;
            overflow-y: auto !important;
        }

        .text-start[style*="max-height: 400px"] {
            max-height: 300px !important;
        }
    }

    /* Custom Order Confirmation Modal */
    .order-confirmation-modal .modal-dialog {
        max-width: 600px !important;
        margin: 2rem auto !important;
    }

    .order-confirmation-modal .modal-content {
        border: none !important;
        border-radius: 10px !important;
        background: white !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }

    .order-confirmation-modal .modal-header {
        background: #2563EB !important;
        color: white !important;
        border-radius: 10px 10px 0 0 !important;
        border: none !important;
        padding: 15px 20px !important;
    }

    .order-confirmation-modal .modal-header .btn-close {
        filter: invert(1) !important;
    }

    .order-confirmation-modal .modal-body {
        padding: 20px !important;
    }

    .order-confirmation-modal .confirmation-title {
        font-size: 1.5rem !important;
        color: #2563EB !important;
        text-align: center !important;
        margin-bottom: 15px !important;
        font-weight: bold !important;
    }

    .order-confirmation-modal .info-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 15px !important;
        margin-bottom: 20px !important;
    }

    @media (min-width: 768px) {
        .order-confirmation-modal .info-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 15px !important;
            margin-bottom: 20px !important;
        }

        .order-confirmation-modal .info-card:nth-child(3) {
            grid-column: span 2 !important;
        }
    }

    .order-confirmation-modal .info-card {
        background: #f8f9fa !important;
        padding: 15px !important;
        border-radius: 8px !important;
        border: 1px solid #dee2e6 !important;
    }

    .order-confirmation-modal .info-card h5 {
        color: #2563EB !important;
        margin-bottom: 10px !important;
        font-size: 1rem !important;
        font-weight: bold !important;
    }

    .order-confirmation-modal .info-card .info-item {
        margin-bottom: 5px !important;
        font-size: 0.9rem !important;
    }

    .order-confirmation-modal .modal-footer {
        border: none !important;
        padding: 15px 20px !important;
        justify-content: center !important;
        gap: 10px !important;
    }

    .order-confirmation-modal .btn-confirm {
        background: #28a745 !important;
        border: none !important;
        border-radius: 5px !important;
        padding: 10px 20px !important;
        font-size: 0.9rem !important;
        font-weight: bold !important;
    }

    .order-confirmation-modal .btn-confirm:hover {
        background: #218838 !important;
    }

    .order-confirmation-modal .btn-edit {
        background: #6c757d !important;
        border: none !important;
        border-radius: 5px !important;
        padding: 10px 20px !important;
        font-size: 0.9rem !important;
        font-weight: bold !important;
        color: white !important;
    }

    .order-confirmation-modal .btn-edit:hover {
        background: #545b62 !important;
    }

    @media (max-width: 768px) {
        .order-confirmation-modal .modal-dialog {
            margin: 1rem !important;
        }

        .order-confirmation-modal .modal-body {
            padding: 15px !important;
        }

        .order-confirmation-modal .confirmation-title {
            font-size: 1.3rem !important;
        }
    }
</style>

@section('content')
    <main class="shop-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2 mb-0 text-primary">Checkout</h1>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to Cart
                    </a>
                </div>

                <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
                    @csrf

                    <div class="row g-4">
                        <!-- Left Column - Customer Information Forms -->
                        <div class="col-lg-8">
                            <!-- Required Information Form -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Required Information</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Personal Information Section -->
                                    <div class="mb-4">
                                        <h6 class="section-title mb-3">Personal Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">First Name *</label>
                                                <input type="text" name="first_name" class="form-control form-control-lg"
                                                       value="{{ $user->first_name ?? old('first_name') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Last Name *</label>
                                                <input type="text" name="last_name" class="form-control form-control-lg"
                                                       value="{{ $user->last_name ?? old('last_name') }}" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email" name="email" class="form-control form-control-lg"
                                                       value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information Section -->
                                    <div class="mb-4">
                                        <h6 class="section-title mb-3">Contact Details</h6>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Country Code *</label>
                                                <select name="phone_country" class="form-select form-select-lg" id="phoneCountry" required>
                                                    @foreach($eastAfricanCountries as $code => $name)
                                                        <option value="{{ $code }}" {{ $code === 'TZ' ? 'selected' : '' }}>
                                                            {{ preg_replace('/ \(.+\)$/', '', $name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label">Phone Number *</label>
                                                <input type="text" name="phone_number" class="form-control form-control-lg"
                                                       placeholder="XXXXXXXXX" maxlength="9"
                                                       pattern="[0-9]{9}" required>
                                                <div class="form-text text-muted">Enter 9 digits only (without country code)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delivery Information Section -->
                                    <div class="mb-4">
                                        <h6 class="section-title mb-3">Delivery Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Country *</label>
                                                <select name="shipping_country" class="form-select form-select-lg" id="shipping_country" required>
                                                    @foreach($eastAfricanCountries as $code => $name)
                                                <option value="{{ $code }}" {{ $code === 'TZ' ? 'selected' : '' }}>
                                                    {{ preg_replace('/ \(.+\)$/', '', $name) }}
                                                </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label">Region *</label>
                                                <select name="shipping_region" class="form-select form-select-lg" id="shippingRegion" required>
                                                    <option value="">Select a region</option>
                                                </select>
                                                <div class="form-text text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>Choose your region first, then specify exact location below
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <div class="col-12">
                                                <label class="form-label">Specific Location *</label>
                                                <input type="text" name="delivery_location" class="form-control form-control-lg"
                                                       placeholder="E.g., Near Mosque, Post Office, Shopping Mall, Hospital Road" required autocomplete="address-line1">
                                                <div class="form-text text-muted">
                                                    <i class="bi bi-lightbulb me-1"></i>Enter a clear location description that delivery drivers can easily find
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location Examples -->
                                        <div class="mt-3 p-3 bg-light rounded-3">
                                            <h6 class="text-primary mb-2"><i class="bi bi-info-circle-fill me-2"></i>Examples of clear locations:</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block mb-1">• Near Mosque - Downtown Area</small>
                                                    <small class="text-muted d-block mb-1">• Behind Post Office - Main Street</small>
                                                    <small class="text-muted d-block">• Next to Shopping Mall - Entrance A</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block mb-1">• Hospital Road - Opposite Police Station</small>
                                                    <small class="text-muted d-block mb-1">• Near Bank - City Center</small>
                                                    <small class="text-muted d-block">• School Compound - Teacher's Block</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Optional Information Form -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Optional Information</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Shipping Address -->
                                    <h6 class="mb-3">Shipping Address</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <input type="text" name="shipping_address" class="form-control"
                                                   placeholder="Street address, building, apartment, etc.">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="shipping_state" class="form-control"
                                                   placeholder="State/Region">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="shipping_postal_code" class="form-control"
                                                   placeholder="Postal Code">
                                        </div>
                                    </div>

                                    <!-- Billing Address -->
                                    <div class="mt-4">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="sameAsShipping" checked>
                                            <label class="form-check-label" for="sameAsShipping">
                                                Billing address same as shipping address
                                            </label>
                                        </div>

                                        <div id="billingSection" style="display: none;">
                                            <h6 class="mb-3">Billing Address</h6>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <input type="text" name="billing_address" class="form-control"
                                                           placeholder="Street address, building, apartment, etc.">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="billing_city" class="form-control"
                                                           placeholder="City">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="billing_state" class="form-control"
                                                           placeholder="State/Region">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="billing_postal_code" class="form-control"
                                                           placeholder="Postal Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Notes -->
                                    <div class="mt-4">
                                        <label class="form-label">Order Notes (Optional)</label>
                                        <textarea name="customer_notes" class="form-control" rows="3"
                                                  placeholder="Any special instructions for your order..."></textarea>
                                        <div class="form-text">These notes will be visible to our delivery team.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Order Summary -->
                        <div class="col-lg-4">
                            <div class="card order-summary-card">
                                <div class="card-header">
                                    <h5 class="mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Products -->
                                    <div class="mb-4">
                                        @foreach($cartItems as $item)
                                            <div class="product-item">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->thumbnail
                                                        ? asset('storage/' . $item->product->thumbnail)
                                                        : ($item->product->media->where('is_primary', true)->first()
                                                            ? asset('storage/' . $item->product->media->where('is_primary', true)->first()->file_path)
                                                            : asset('img/logo.png')) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 text-truncate" style="font-size: 0.9rem;">{{ $item->product->name }}</h6>
                                                        <p class="mb-0 text-muted small">Qty: {{ $item->quantity }}</p>
                                                    </div>
                                                    <div class="text-end">
                                                        <strong class="text-primary">Tsh{{ number_format($item->price * $item->quantity, 2) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Shipping Info -->
                                    @if($shippingCost == 0)
                                        <div class="shipping-info">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-truck text-success me-2"></i>
                                                <small class="text-success fw-bold">FREE DELIVERY!</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info py-2 mb-3">
                                            <small><i class="bi bi-truck me-1"></i>Add Tsh{{ number_format(100000 - $subtotal, 2) }} more for FREE shipping</small>
                                        </div>
                                    @endif

                                    <!-- Order Totals -->
                                    <div class="order-total">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                            <span>Tsh{{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (18% VAT)</span>
                                            <span>Tsh{{ number_format($taxAmount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Shipping</span>
                                            <span>{{ $shippingCost > 0 ? 'Tsh' . number_format($shippingCost, 2) : 'Free' }}</span>
                                        </div>
                                        <hr class="my-3">
                                        <div class="d-flex justify-content-between">
                                            <strong class="fs-5">Total</strong>
                                            <strong class="total-amount fs-5">Tsh{{ number_format($total, 2) }}</strong>
                                        </div>
                                    </div>

                                    <!-- Place Order Button -->
                                    <button type="button" onclick="confirmOrder()" class="btn btn-place-order mt-4">
                                        <i class="bi bi-credit-card me-2"></i>Place Order
                                    </button>

                                    <div class="mt-3 text-center">
                                        <small class="text-muted">Secure checkout powered by our trusted payment system</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Overlay (hidden by default) -->
        <div id="loadingOverlay" class="loading-overlay" style="display: none;">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Processing...</span>
                </div>
                <h5 class="mt-3 text-primary">Processing your order...</h5>
                <p class="text-muted">Please wait while we secure your purchase.</p>
            </div>
        </div>

        <!-- Order Confirmation Modal -->
        <div class="modal fade order-confirmation-modal" id="orderConfirmationModal" tabindex="-1" aria-labelledby="orderConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-4" id="orderConfirmationModalLabel">
                            <span class="confirmation-title">Order Confirmation</span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="info-grid" id="confirmationDetails">
                            <!-- Details will be populated by JavaScript -->
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-edit" data-bs-dismiss="modal">
                            <i class="bi bi-pencil-square me-2"></i>Review & Edit
                        </button>
                        <button type="button" class="btn btn-confirm" id="confirmOrderBtn">
                            <i class="bi bi-check-circle me-2"></i>Confirm & Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Checkout page loaded');

            // Country regions data
            const countryRegions = @json($countryRegions);

            // Function to populate region dropdown
            function populateRegionDropdown(countryCode) {
                const regionSelect = document.getElementById('shippingRegion');
                if (!regionSelect) return;

                // Clear existing options
                regionSelect.innerHTML = '<option value="">Select a region</option>';

                if (countryCode && countryRegions[countryCode]) {
                    // Sort regions alphabetically and add to dropdown
                    const regions = countryRegions[countryCode].sort((a, b) => a.localeCompare(b));
                    regions.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region;
                        option.textContent = region;
                        regionSelect.appendChild(option);
                    });
                }
            }

            // Set initial region for Tanzania (default)
            populateRegionDropdown('TZ');

            // Add event listener to country dropdown
            const countrySelect = document.querySelector('select[name="shipping_country"]');
            if (countrySelect) {
                countrySelect.addEventListener('change', function() {
                    const selectedCountry = this.value;
                    populateRegionDropdown(selectedCountry);
                });
            }

            // Handle billing address toggle for new form
            const sameAsShippingCheckbox = document.getElementById('sameAsShipping');
            if (sameAsShippingCheckbox) {
                sameAsShippingCheckbox.addEventListener('change', function() {
                    const billingSection = document.getElementById('billingSection');
                    if (billingSection) {
                        if (this.checked) {
                            billingSection.style.display = 'none';
                        } else {
                            billingSection.style.display = 'block';
                        }
                    }
                });
                console.log('Billing address toggle initialized');
            }

            // Add event listener for confirm button in modal
            const confirmOrderBtn = document.getElementById('confirmOrderBtn');
            if (confirmOrderBtn) {
                confirmOrderBtn.addEventListener('click', function() {
                    console.log('Confirm button clicked');
                    // Hide the modal first
                    const modal = bootstrap.Modal.getInstance(document.getElementById('orderConfirmationModal'));
                    if (modal) {
                        modal.hide();
                    }
                    // Then place the order
                    placeOrder();
                });
            }

            // Confirm order placement
            window.confirmOrder = function() {
                console.log('confirmOrder called');

                const form = document.getElementById('checkoutForm');
                if (!form) {
                    console.error('Form not found');
                    return;
                }

                // Check if form is valid
                if (!form.checkValidity()) {
                    console.log('Form validation failed');
                    form.reportValidity();
                    return;
                }

                console.log('Form validation passed');

                // Collect form data
                const formData = new FormData(form);

                // Get selected values
                const phoneCountrySelect = document.getElementById('phoneCountry');
                const shippingCountrySelect = document.getElementById('shipping_country');
                const regionSelect = document.getElementById('shippingRegion');

                // Calculate totals and show confirmation
                const subtotal = {{ $subtotal }};
                const taxAmount = {{ $taxAmount }};
                const shippingCost = {{ $shippingCost }};
                const total = {{ $total }};
                const itemCount = {{ $cartItems->sum('quantity') }};

                console.log('Showing confirmation modal');

                // Populate the modal with order details
                const detailsContainer = document.getElementById('confirmationDetails');
                if (detailsContainer) {
                    detailsContainer.innerHTML = `
                        <!-- Your Details Card -->
                        <div class="info-card">
                            <h5><i class="bi bi-person"></i> Your Details</h5>
                            <div class="info-item">
                                <div><strong>Name:</strong> ${formData.get('first_name')} ${formData.get('last_name')}</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Email:</strong> ${formData.get('email')}</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Phone:</strong> +${phoneCountrySelect.options[phoneCountrySelect.selectedIndex].text} ${formData.get('phone_number')}</div>
                            </div>
                        </div>

                        <!-- Delivery Info Card -->
                        <div class="info-card">
                            <h5><i class="bi bi-geo-alt"></i> Delivery Info</h5>
                            <div class="info-item">
                                <div><strong>Location:</strong> ${shippingCountrySelect.options[shippingCountrySelect.selectedIndex].text}, ${regionSelect.options[regionSelect.selectedIndex].text}</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Address:</strong> ${formData.get('delivery_location')}</div>
                            </div>
                            ${formData.get('customer_notes') ? `<div class="info-item"><div><strong>Notes:</strong> <em>${formData.get('customer_notes')}</em></div></div>` : ''}
                        </div>

                        <!-- Order Summary Card -->
                        <div class="info-card">
                            <h5><i class="bi bi-receipt"></i> Order Summary</h5>
                            <div class="info-item">
                                <div><strong>Items:</strong> ${itemCount} products</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Subtotal:</strong> Tsh${subtotal.toLocaleString()}</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Tax (18%):</strong> Tsh${taxAmount.toLocaleString()}</div>
                            </div>
                            <div class="info-item">
                                <div><strong>Shipping:</strong> ${shippingCost > 0 ? 'Tsh' + shippingCost.toLocaleString() : 'FREE!'}</div>
                            </div>
                            <div class="info-item" style="border-top: 2px solid #2563EB; padding-top: 12px; margin-top: 8px;">
                                <div><strong style="color: #2563EB; font-size: 1.1rem;">Total: Tsh${total.toLocaleString()}</strong></div>
                            </div>
                        </div>
                    `;
                }

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('orderConfirmationModal'));
                modal.show();
            };

            // Submit the order
            window.placeOrder = function() {
                console.log('placeOrder called');

                const form = document.getElementById('checkoutForm');
                const loadingOverlay = document.getElementById('loadingOverlay');

                if (!form || !loadingOverlay) {
                    console.error('Form or loading overlay not found');
                    return;
                }

                // Show loading overlay
                loadingOverlay.style.display = 'flex';
                console.log('Loading overlay shown');

                // Prepare form data for AJAX submission
                const formData = new FormData(form);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Make AJAX request instead of form submission
                fetch('{{ route("checkout.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 419) {
                        // CSRF token expired
                        loadingOverlay.style.display = 'none';
                        Swal.fire({
                            title: 'Session Expired',
                            text: 'Please refresh the page and try again',
                            icon: 'warning',
                            confirmButtonColor: '#2563EB',
                            confirmButtonText: 'Refresh Page'
                        }).then(() => {
                            window.location.reload();
                        });
                        return null;
                    }

                    return response.json();
                })
                .then(data => {
                    loadingOverlay.style.display = 'none';

                    if (!data) return; // Skip if redirect was handled above

                    if (data.success) {
                        // Show success notification with spinner and auto-redirect
                        Swal.fire({
                            title: 'Order Placed Successfully!',
                            html: `
                                <div class="text-center">
                                    <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Processing...</span>
                                    </div>
                                    <p class="mb-0">${data.message || 'Your order has been confirmed successfully.'}</p>
                                    <p class="text-muted small mt-2">Redirecting to dashboard to track your order...</p>
                                </div>
                            `,
                            icon: 'success',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            timer: 4000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'swal-wide'
                            }
                        }).then(() => {
                            // Redirect to customer dashboard after notification
                            window.location.href = '/customer/dashboard';
                        });
                    } else {
                        // Show error
                        Swal.fire({
                            title: 'Order Failed',
                            text: data.message || 'Failed to place order. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'Try Again'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingOverlay.style.display = 'none';
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK'
                    });
                });
            };

            console.log('Checkout JavaScript initialized successfully');
        });
    </script>
@endsection
