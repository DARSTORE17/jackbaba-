@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">System Settings</h2>
            <p class="text-muted">This section is reserved for admin configuration and future system settings.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h5>Administrator Controls</h5>
                <p class="text-muted">From here, the admin can manage sellers, categories, and other system-wide settings.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-start border-4 border-primary h-100">
                        <div class="card-body">
                            <h6>Brand Colors</h6>
                            <p class="text-muted">Use the main blue brand color across the admin panel and user-facing pages.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-start border-4 border-info h-100">
                        <div class="card-body">
                            <h6>Coming Soon</h6>
                            <p class="text-muted">More system-wide options can be added here, such as shipping, tax, or homepage content.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
