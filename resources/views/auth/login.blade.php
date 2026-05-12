@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="auth-section" style="min-height: 100vh; position: relative; overflow: hidden;">
    <!-- Background Gradient and Decorations -->
    <div class="auth-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-600) 100%); z-index: -2;"></div>

    <!-- Floating Particles -->
    <div class="floating-particles" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
        <div class="particle" style="position: absolute; top: 20%; left: 10%; width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; animation: floatParticle 12s ease-in-out infinite;"></div>
        <div class="particle" style="position: absolute; top: 40%; right: 15%; width: 6px; height: 6px; background: rgba(255,255,255,0.4); border-radius: 50%; animation: floatParticle 15s ease-in-out infinite reverse;"></div>
        <div class="particle" style="position: absolute; bottom: 30%; left: 20%; width: 5px; height: 5px; background: rgba(255,255,255,0.5); border-radius: 50%; animation: floatParticle 10s ease-in-out infinite;"></div>
    </div>

    <!-- Floating Icons -->
    <div class="floating-icons" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
        <i class="bi bi-star-fill" style="position: absolute; top: 15%; right: 10%; font-size: 2.5rem; color: rgba(255,255,255,0.3); animation: floatIcon 8s ease-in-out infinite;"></i>
        <i class="bi bi-heart-fill" style="position: absolute; top: 35%; right: 80%; font-size: 3rem; color: rgba(255,255,255,0.2); animation: floatIcon 10s ease-in-out infinite reverse;"></i>
        <i class="bi bi-emoji-smile-fill" style="position: absolute; bottom: 25%; left: 85%; font-size: 3.5rem; color: rgba(255,255,255,0.2); animation: floatIcon 9s ease-in-out infinite;"></i>
    </div>

    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <!-- Left Side - Welcome Section -->
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                <div class="welcome-content text-center" style="color: var(--white); padding: 0 4rem;">
                    <div class="welcome-icon mb-4" style="animation: bounceIn 1s ease-out;">
                        <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 20px 40px rgba(0,0,0,0.2); backdrop-filter: blur(10px);">
                            <i class="bi bi-house-door-fill" style="font-size: 3.5rem; color: var(--white);"></i>
                        </div>
                    </div>
                    <h1 class="welcome-title" style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; text-shadow: 0 4px 20px rgba(0,0,0,0.3); animation: slideInLeft 1s ease-out 0.3s both;">Welcome Back!</h1>
                    <p class="welcome-subtitle" style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9; animation: slideInLeft 1s ease-out 0.5s both;">
                        Ready to continue your electronics shopping journey? We're thrilled to have you back at Bravus Market.
                    </p>
                    <div class="welcome-features text-left" style="max-width: 400px; margin: 0 auto; animation: slideInLeft 1s ease-out 0.7s both;">
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--blue-600); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Access your saved items</span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--blue-600); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Track your orders</span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--blue-600); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Manage your account</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Form - Improved for mobile -->
            <div class="col-lg-6 col-xl-5 col-12 d-flex align-items-center justify-content-center p-3">
                <div class="login-form-wrapper" style="width: 100%; max-width: 500px; margin: 0 auto;">
                    <div class="login-card" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 25px; padding: 3rem; box-shadow: 0 25px 60px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.3); animation: slideInRight 1s ease-out; min-height: auto;">
                        <div class="form-header text-center mb-4">
                            <h2 class="form-title" style="color: var(--slate-800); font-weight: 700; font-size: 2rem; margin-bottom: 0.5rem;">Sign In</h2>
                            <p style="color: var(--slate-600); font-size: 1rem;">Enter your credentials to access your account</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger" style="border-radius: 10px; border: none; background: linear-gradient(135deg, #2563EB, #1D4ED8); color: white;">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-floating mb-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="email" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback" style="display: block; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating position-relative mb-4">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 3rem 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="password" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Password</label>
                                <button type="button" class="password-toggle-btn btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2 border-0" onclick="togglePasswordVisibility('password', this)"
                                        style="background: transparent; color: var(--slate-600); border: none; padding: 0; line-height: 1; z-index: 10; width: 40px; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback" style="display: block; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                           style="transform: scale(1.2); margin-right: 0.5rem;">
                                    <label class="form-check-label" for="remember" style="color: var(--slate-700); font-weight: 500;">
                                        Remember me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="color: var(--blue-600); font-weight: 500; text-decoration: none;"
                                       onmouseover="this.style.color='var(--blue-700)'" onmouseout="this.style.color='var(--blue-600)'">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary w-100"
                                    style="background: linear-gradient(135deg, var(--blue-600), var(--blue-700)); border: none; border-radius: 12px; padding: 1rem; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                                </span>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p style="color: var(--slate-600); margin-bottom: 0;">
                                Don't have an account? <a href="{{ route('register') }}" style="color: var(--blue-600); font-weight: 600; text-decoration: none;"
                                                          onmouseover="this.style.color='var(--blue-700)'" onmouseout="this.style.color='var(--blue-600)'">Sign up here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes floatParticle {
    0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.6; }
    25% { transform: translateY(-20px) translateX(10px); opacity: 0.4; }
    50% { transform: translateY(-10px) translateX(-10px); opacity: 0.2; }
    75% { transform: translateY(-30px) translateX(5px); opacity: 0.4; }
}

@keyframes floatIcon {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(180deg); }
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes slideInLeft {
    from { transform: translateX(-50px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideInRight {
    from { transform: translateX(50px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Hover effects for form controls */
.login-card input:focus {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

.login-card .btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
}

/* Floating Label Animations */
.login-card .form-floating {
    position: relative;
}

.login-card .form-floating > label {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    display: flex;
    align-items: center;
    transform-origin: 0 0;
    transition: all 0.2s ease;
    pointer-events: none;
}

.login-card .form-floating > .form-control {
    height: 3.5rem;
}

.login-card .form-floating > .form-control:focus ~ label,
.login-card .form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.8) translateY(-1rem) translateX(0.15rem);
}

.login-card .form-floating > .form-control:focus,
.login-card .form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem !important;
    padding-bottom: 0.625rem !important;
}

.login-card .form-floating > .form-control:focus,
.login-card .form-floating > .form-control:not(:placeholder-shown) {
    border-color: var(--blue-600);
}

/* Eye button styling */
.password-toggle-btn {
    background: transparent;
    border: none;
    outline: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.password-toggle-btn i {
    color: var(--slate-700);
    display: inline-block;
    font-size: 1.18rem;
    line-height: 1;
}

.password-toggle-btn:hover {
    opacity: 0.8;
}

/* Mobile responsiveness - IMPROVED */
@media (max-width: 991.98px) {
    .welcome-content {
        display: none;
    }
    
    .auth-section {
        padding: 1rem 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
    
    .container-fluid {
        padding: 0 15px;
    }
    
    .row {
        margin: 0;
        width: 100%;
    }
    
    .login-form-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .login-card {
        width: 100%;
        padding: 2rem 1.5rem !important;
        margin: 0 auto;
        border-radius: 20px !important;
    }
    
    .form-title {
        font-size: 1.8rem !important;
    }
}

@media (max-width: 768px) {
    .auth-section {
        padding: 0.5rem 0;
    }
    
    .login-card {
        padding: 1.5rem 1rem !important;
        border-radius: 15px !important;
        margin: 0.5rem auto;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
    }
    
    .form-title {
        font-size: 1.6rem !important;
        margin-bottom: 0.3rem !important;
    }
    
    .form-header p {
        font-size: 0.9rem !important;
    }
    
    .form-floating > .form-control {
        height: 3.2rem !important;
        font-size: 0.95rem !important;
        padding: 0.875rem 0.75rem !important;
    }
    
    .form-floating > label {
        font-size: 0.9rem !important;
    }
    
    .btn-primary {
        padding: 0.875rem !important;
        font-size: 1rem !important;
    }
    
    .form-check-label, 
    .forgot-password-link {
        font-size: 0.9rem !important;
    }
    
    /* Adjust floating particles for mobile */
    .floating-particles .particle {
        display: none;
    }
    
    .floating-icons i {
        font-size: 2rem !important;
    }
}

@media (max-width: 576px) {
    .auth-section {
        padding: 0.5rem 0;
        min-height: 100dvh;
    }
    
    .container-fluid {
        padding: 0 10px;
    }
    
    .login-card {
        padding: 1.25rem 0.875rem !important;
        border-radius: 12px !important;
        margin: 0.25rem auto;
        width: 100%;
    }
    
    .form-title {
        font-size: 1.4rem !important;
    }
    
    .form-header p {
        font-size: 0.85rem !important;
    }
    
    .form-floating > .form-control {
        height: 3rem !important;
        font-size: 0.9rem !important;
        padding: 0.75rem 0.625rem !important;
    }
    
    .form-floating > label {
        font-size: 0.85rem !important;
        padding-left: 0.625rem !important;
    }
    
    .password-toggle-btn {
        right: 5px !important;
        width: 35px !important;
    }
    
    .btn-primary {
        padding: 0.75rem !important;
        font-size: 0.95rem !important;
    }
    
    .form-check-label, 
    .forgot-password-link {
        font-size: 0.85rem !important;
    }
    
    .text-center p {
        font-size: 0.9rem !important;
    }
    
    /* Hide floating icons on very small screens */
    .floating-icons i {
        display: none;
    }
}

@media (max-width: 360px) {
    .login-card {
        padding: 1rem 0.75rem !important;
    }
    
    .form-title {
        font-size: 1.3rem !important;
    }
    
    .form-floating > .form-control {
        height: 2.8rem !important;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start !important;
    }
    
    .form-check {
        width: 100%;
    }
    
    .forgot-password-link {
        align-self: flex-end;
    }
}
</style>

<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash-fill';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-fill';
    }
}
</script>
@endsection
