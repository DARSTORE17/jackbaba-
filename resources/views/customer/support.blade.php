@extends('layouts.customer')

@section('title', 'Customer Support - Bravus Market')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-headset me-2"></i>Customer Support
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="support-card">
                            <i class="bi bi-envelope-fill"></i>
                            <h6>Email</h6>
                            <p>{{ $systemSettings['email'] ?? 'support@bravusmarket.com' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-card">
                            <i class="bi bi-telephone-fill"></i>
                            <h6>Phone</h6>
                            <p>{{ $systemSettings['phone'] ?? '+255 754 321 987' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-card">
                            <i class="bi bi-geo-alt-fill"></i>
                            <h6>Address</h6>
                            <p>{{ $systemSettings['address'] ?? 'Dar es Salaam, Tanzania' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="bi bi-chat-dots me-1"></i>Contact Us
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary ms-2">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .support-card {
            height: 100%;
            padding: 1.5rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        }

        .support-card i {
            color: var(--primary-color);
            font-size: 1.8rem;
        }

        .support-card h6 {
            margin: 0.85rem 0 0.35rem;
            font-weight: 800;
        }

        .support-card p {
            margin: 0;
            color: #475569;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0 !important;
                padding: 15px !important;
            }
        }
    </style>
@endsection
