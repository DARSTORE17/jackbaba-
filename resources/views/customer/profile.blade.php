@extends('layouts.customer')

@section('title', 'My Profile - Bravus Market')

@section('content')
    @php
        $avatarUrl = !empty($user->passport)
            ? asset('storage/' . $user->passport)
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=2563eb&color=fff&size=120';
    @endphp

    <div class="container-fluid px-4 py-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="profile-photo mb-3">
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <span class="badge bg-primary text-uppercase">{{ $user->role ?? 'customer' }}</span>

                        <hr class="my-4">

                        <div class="profile-meta">
                            <div>
                                <i class="bi bi-telephone text-primary"></i>
                                <span>{{ $user->phone ?: 'No phone added' }}</span>
                            </div>
                            <div>
                                <i class="bi bi-calendar-check text-primary"></i>
                                <span>Joined {{ $user->created_at?->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="passport" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control @error('passport') is-invalid @enderror" id="passport" name="passport" accept="image/*">
                                    @error('passport')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(37, 99, 235, 0.16);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .profile-meta {
            display: grid;
            gap: 0.75rem;
            text-align: left;
        }

        .profile-meta div {
            display: flex;
            align-items: center;
            gap: 0.65rem;
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
