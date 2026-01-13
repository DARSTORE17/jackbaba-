@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="auth-section" style="min-height: 100vh; position: relative; overflow: hidden;">
    <!-- Background Gradient and Decorations -->
    <div class="auth-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, var(--aqua-500) 0%, var(--cyan-600) 50%, var(--blue-600) 100%); z-index: -2;"></div>

    <!-- Floating Particles -->
    <div class="floating-particles" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
        <div class="particle" style="position: absolute; top: 25%; left: 15%; width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; animation: floatParticle 12s ease-in-out infinite;"></div>
        <div class="particle" style="position: absolute; top: 45%; right: 10%; width: 6px; height: 6px; background: rgba(255,255,255,0.4); border-radius: 50%; animation: floatParticle 15s ease-in-out infinite reverse;"></div>
        <div class="particle" style="position: absolute; bottom: 35%; left: 25%; width: 5px; height: 5px; background: rgba(255,255,255,0.5); border-radius: 50%; animation: floatParticle 10s ease-in-out infinite;"></div>
    </div>

    <!-- Floating Icons -->
    <div class="floating-icons" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
        <i class="bi bi-star-fill" style="position: absolute; top: 20%; right: 15%; font-size: 2.5rem; color: rgba(255,255,255,0.3); animation: floatIcon 8s ease-in-out infinite;"></i>
        <i class="bi bi-heart-fill" style="position: absolute; top: 30%; right: 85%; font-size: 3rem; color: rgba(255,255,255,0.2); animation: floatIcon 10s ease-in-out infinite reverse;"></i>
        <i class="bi bi-emoji-smile-fill" style="position: absolute; bottom: 20%; left: 80%; font-size: 3.5rem; color: rgba(255,255,255,0.2); animation: floatIcon 9s ease-in-out infinite;"></i>
    </div>

    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <!-- Register Form - Now full width on mobile -->
            <div class="col-lg-8 col-xl-6 col-12 d-flex align-items-center justify-content-center p-3">
                <div class="register-form-wrapper" style="width: 100%; max-width: 500px; margin: 0 auto;">
                    <div class="register-card" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 25px; padding: 3rem; box-shadow: 0 25px 60px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.3); animation: slideInUp 1s ease-out; min-height: auto;">
                        <div class="form-header text-center mb-4">
                            <h2 class="form-title" style="color: var(--slate-800); font-weight: 700; font-size: 2rem; margin-bottom: 0.5rem;">Join KidzStore365</h2>
                            <p style="color: var(--slate-600); font-size: 1rem;">Create your account and discover endless possibilities</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger" style="border-radius: 10px; border: none; background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white;">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name" required autocomplete="name" autofocus
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="name" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Full Name</label>
                                @error('name')
                                    <div class="invalid-feedback" style="display: block; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email"
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="email" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback" style="display: block; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating position-relative mb-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password"
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 3rem 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="password" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Password</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2 border-0" onclick="togglePasswordVisibility('password', this)"
                                        style="background: transparent; color: var(--slate-600); border: none; padding: 0; line-height: 1; z-index: 10; width: 40px; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback" style="display: block; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating position-relative mb-4">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password"
                                       style="border-radius: 12px; border: 2px solid transparent; padding: 1rem 3rem 1rem 0.75rem; font-size: 1rem; background: var(--slate-50); transition: all 0.3s ease; box-shadow: none;"
                                       onfocus="this.style.borderColor='var(--blue-600)'; this.parentElement.querySelector('label').style.color='var(--blue-600)';"
                                       onblur="this.style.borderColor='transparent'; this.parentElement.querySelector('label').style.color='var(--slate-700)';">
                                <label for="password-confirm" style="color: var(--slate-700); font-weight: 500; padding-left: 0.75rem; pointer-events: none; transition: all 0.2s ease;">Confirm Password</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2 border-0" onclick="togglePasswordVisibility('password-confirm', this)"
                                        style="background: transparent; color: var(--slate-600); border: none; padding: 0; line-height: 1; z-index: 10; width: 40px; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>

                            <button type="submit" class="btn btn-primary w-100"
                                    style="background: linear-gradient(135deg, var(--blue-600), var(--aqua-500)); border: none; border-radius: 12px; padding: 1rem; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    <i class="bi bi-person-plus"></i> Create Account
                                </span>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p style="color: var(--slate-600); margin-bottom: 0;">
                                Already have an account? <a href="{{ route('login') }}" style="color: var(--blue-600); font-weight: 600; text-decoration: none;"
                                                             onmouseover="this.style.color='var(--aqua-500)'" onmouseout="this.style.color='var(--blue-600)'">Sign in here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Welcome Section -->
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center order-lg-2">
                <div class="welcome-content text-center" style="color: var(--white); padding: 0 4rem;">
                    <div class="welcome-icon mb-4" style="animation: bounceIn 1s ease-out;">
                        <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 20px 40px rgba(0,0,0,0.2); backdrop-filter: blur(10px);">
                            <i class="bi bi-stars" style="font-size: 3.5rem; color: var(--white);"></i>
                        </div>
                    </div>
                    <h1 class="welcome-title" style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; text-shadow: 0 4px 20px rgba(0,0,0,0.3); animation: slideInRight 1s ease-out 0.3s both;">Join Our Family!</h1>
                    <p class="welcome-subtitle" style="font-size: 1.3rem; margin-bottom: 2rem; opacity: 0.9; animation: slideInRight 1s ease-out 0.5s both;">
                        Start your magical adventure with KidzStore365. Discover cute products crafted with love and care! 🎉
                    </p>
                    <div class="welcome-features text-left" style="max-width: 400px; margin: 0 auto; animation: slideInRight 1s ease-out 0.7s both;">
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--cyan-500); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Exclusive member deals</span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--cyan-500); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Personalized recommendations</span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--cyan-500); font-size: 1.5rem; margin-right: 1rem;"></i>
                            <span style="font-weight: 500;">Priority customer support</span>
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

@keyframes slideInUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Hover effects for form controls */
.register-card input:focus {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

.register-card .btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
}

/* Floating Label Animations */
.register-card .form-floating {
    position: relative;
}

.register-card .form-floating > label {
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

.register-card .form-floating > .form-control {
    height: 3.5rem;
}

.register-card .form-floating > .form-control:focus ~ label,
.register-card .form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.8) translateY(-1rem) translateX(0.15rem);
}

.register-card .form-floating > .form-control:focus,
.register-card .form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem !important;
    padding-bottom: 0.625rem !important;
}

.register-card .form-floating > .form-control:focus,
.register-card .form-floating > .form-control:not(:placeholder-shown) {
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
    
    .register-form-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .register-card {
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
    
    .register-card {
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
    
    .register-card {
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
    
    /* Hide floating icons on very small screens */
    .floating-icons i {
        display: none;
    }
}

@media (max-width: 360px) {
    .register-card {
        padding: 1rem 0.75rem !important;
    }
    
    .form-title {
        font-size: 1.3rem !important;
    }
    
    .form-floating > .form-control {
        height: 2.8rem !important;
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