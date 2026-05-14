<!-- resources/views/about.blade.php -->
@extends('layouts.app')

@section('content')
@php
    $cacheBuster = !empty($systemSettings['images_cache_buster']) ? '?v=' . $systemSettings['images_cache_buster'] : '';
    $aboutStoreImages = collect([
        $systemSettings['about_store_image_path'] ?? '',
        $systemSettings['about_store_image_2_path'] ?? '',
    ])->filter()->map(fn ($path) => media_url($path, null, $cacheBuster))->values();
@endphp

<!-- ================= About Hero Section ================= -->
<section class="about-hero-section position-relative"
    style="background: linear-gradient(135deg, var(--blue-700), var(--blue-600)); color: var(--white); padding: 120px 0; overflow: hidden;">
    <div class="container text-center position-relative" style="z-index: 2;">
        <h1 class="hero-title" style="font-size: 3.5rem; font-weight: bold;">About Bravus Market</h1>
        <p class="hero-subtitle"
            style="font-size: 1.75rem; margin: 20px 0; max-width: 800px; margin-left:auto; margin-right:auto;">
            Bringing affordable, original electronics to Tanzania with a focus on phones, laptops, and smart accessories.
        </p>
    </div>

    <!-- Floating Icons / Decorative Shapes -->
    <div class="about-decorations">
        <i class="bi bi-heart-fill"
            style="position: absolute; top: 20%; left: 10%; font-size: 2.5rem; color: rgba(255,255,255,0.6);"></i>
        <i class="bi bi-emoji-smile-fill"
            style="position: absolute; top: 40%; left: 85%; font-size: 3rem; color: rgba(255,255,255,0.4);"></i>
        <i class="bi bi-star-fill"
            style="position: absolute; top: 70%; left: 15%; font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
        <i class="bi bi-baby"
            style="position: absolute; top: 30%; left: 50%; font-size: 2.8rem; color: rgba(255,255,255,0.6);"></i>
    </div>
</section>

<!-- ================= Our Story Section ================= -->
<section class="our-story-section" style="padding: 80px 0; background-color: var(--slate-100);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="story-content">
                    <h2 class="section-title" style="color: var(--slate-900); margin-bottom: 30px; font-size: 2.5rem;">Our Story</h2>
                    <p style="font-size: 1.1rem; line-height: 1.6; color: var(--slate-800); margin-bottom: 20px;">
                        Founded in 2025, Bravus Market was created to deliver premium electronics across Tanzania. We believe everyone should access original phones, laptops, and accessories at affordable prices.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.6; color: var(--slate-800); margin-bottom: 20px;">
                        Our team hand-selects every device for quality, trust, and performance—whether you need a new smartphone, laptop, or everyday tech accessory.
                    </p>
                    <div class="story-stats" style="display: flex; gap: 30px; margin-top: 40px;">
                        <div class="stat-item text-center">
                            <div style="font-size: 2rem; font-weight: bold; color: var(--blue-600);">365</div>
                            <div style="font-size: 0.9rem; color: var(--slate-600);">Days a Year</div>
                        </div>
                        <div class="stat-item text-center">
                            <div style="font-size: 2rem; font-weight: bold; color: var(--blue-700);">1000+</div>
                            <div style="font-size: 0.9rem; color: var(--slate-600);">Happy Customers</div>
                        </div>
                        <div class="stat-item text-center">
                            <div style="font-size: 2rem; font-weight: bold; color: var(--blue-600);">50+</div>
                            <div style="font-size: 0.9rem; color: var(--slate-600);">Product Categories</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="story-image-wrapper" style="position: relative;">
                    <div class="story-image-card" data-about-slider>
                        @if($aboutStoreImages->isNotEmpty())
                            @foreach($aboutStoreImages as $imageUrl)
                                <img src="{{ $imageUrl }}"
                                    alt="{{ $systemSettings['about_store_image_title'] ?? 'Our store' }}"
                                    class="story-slide {{ $loop->first ? 'is-active' : '' }}">
                            @endforeach
                            <div class="story-image-caption">
                                <strong>{{ $systemSettings['about_store_image_title'] ?? 'Our Store Image' }}</strong>
                                <span>{{ $systemSettings['about_store_image_subtitle'] ?? '' }}</span>
                            </div>
                        @else
                            <div class="story-image-empty">
                                <i class="bi bi-images"></i>
                                <p>{{ $systemSettings['about_store_image_title'] ?? 'Our Store Image' }}</p>
                                <span>{{ $systemSettings['about_store_image_subtitle'] ?? 'Coming Soon' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($aboutStoreImages->count() > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.querySelector('[data-about-slider]');
            if (!slider) return;

            const slides = Array.from(slider.querySelectorAll('.story-slide'));
            if (slides.length < 2) return;

            let activeIndex = 0;
            setInterval(function () {
                slides[activeIndex].classList.remove('is-active');
                activeIndex = (activeIndex + 1) % slides.length;
                slides[activeIndex].classList.add('is-active');
            }, 2000);
        });
    </script>
@endif

<!-- ================= Our Mission & Values Section ================= -->
<section class="mission-values-section" style="padding: 80px 0; background-color: var(--white);">
    <div class="container text-center">
        <h2 class="section-title" style="margin-bottom: 60px; color: var(--slate-900); font-size: 2.5rem;">Our Mission & Values</h2>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="value-card"
                    style="padding: 25px; background-color: var(--slate-50); border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease; height: 100%;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-shield-check" style="font-size: 2.5rem; color: var(--blue-700);"></i>
                    <h4 class="value-title" style="margin-top: 20px; margin-bottom: 15px; color: var(--slate-900);">Safety First</h4>
                    <p style="color: var(--slate-700); line-height: 1.5;">Every product is rigorously tested for safety standards, ensuring peace of mind for parents.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="value-card"
                    style="padding: 25px; background-color: var(--slate-50); border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease; height: 100%;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-palette" style="font-size: 2.5rem; color: var(--blue-600);"></i>
                    <h4 class="value-title" style="margin-top: 20px; margin-bottom: 15px; color: var(--slate-900);">Creativity & Fun</h4>
                    <p style="color: var(--slate-700); line-height: 1.5;">We believe play is the foundation of learning and creativity development in children.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="value-card"
                    style="padding: 25px; background-color: var(--slate-50); border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease; height: 100%;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-heart-fill" style="font-size: 2.5rem; color: var(--blue-700);"></i>
                    <h4 class="value-title" style="margin-top: 20px; margin-bottom: 15px; color: var(--slate-900);">Loving Care</h4>
                    <p style="color: var(--slate-700); line-height: 1.5;">From curation to customer service, everything we do comes from a place of love for children.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="value-card"
                    style="padding: 25px; background-color: var(--slate-50); border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease; height: 100%;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-globe" style="font-size: 2.5rem; color: var(--blue-700);"></i>
                    <h4 class="value-title" style="margin-top: 20px; margin-bottom: 15px; color: var(--slate-900);">Global Standards</h4>
                    <p style="color: var(--slate-700); line-height: 1.5;">We source the best quality products from around the world while maintaining ethical practices.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= What We Do Section ================= -->
<section class="what-we-do-section" style="padding: 80px 0; background: linear-gradient(135deg, var(--blue-600), var(--blue-600)); color: var(--white);">
    <div class="container text-center">
        <h2 class="section-title" style="margin-bottom: 60px; font-size: 2.5rem;">What We Do</h2>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="service-card"
                    style="padding: 30px 20px; background-color: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-bag-heart-fill" style="font-size: 3rem;"></i>
                    <h4 class="service-title" style="margin-top: 20px; margin-bottom: 15px;">Curated Shopping</h4>
                    <p style="line-height: 1.5; opacity: 0.9;">Hand-picked baby essentials and toys that meet our strict quality standards.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="service-card"
                    style="padding: 30px 20px; background-color: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-truck" style="font-size: 3rem;"></i>
                    <h4 class="service-title" style="margin-top: 20px; margin-bottom: 15px;">Fast Delivery</h4>
                    <p style="line-height: 1.5; opacity: 0.9;">Swift and secure shipping to get your orders to you as quickly as possible.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="service-card"
                    style="padding: 30px 20px; background-color: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-headset" style="font-size: 3rem;"></i>
                    <h4 class="service-title" style="margin-top: 20px; margin-bottom: 15px;">24/7 Support</h4>
                    <p style="line-height: 1.5; opacity: 0.9;">Our friendly team is always here to help with any questions or concerns.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="service-card"
                    style="padding: 30px 20px; background-color: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-arrow-repeat" style="font-size: 3rem;"></i>
                    <h4 class="service-title" style="margin-top: 20px; margin-bottom: 15px;">Easy Returns</h4>
                    <p style="line-height: 1.5; opacity: 0.9;">Hassle-free returns and exchanges if you're not completely satisfied.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= Join Our Community Section ================= -->
<section class="community-section" style="padding: 80px 0; background-color: var(--slate-50);">
    <div class="container text-center">
        <h2 class="section-title" style="margin-bottom: 30px; color: var(--slate-900); font-size: 2.5rem;">Join Our Happy Community</h2>
        <p style="font-size: 1.1rem; color: var(--slate-700); margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Become part of a community that celebrates childhood joy. Get exclusive deals, parenting tips, and connect with other parents who share your values.
        </p>

        <div class="community-cta">
            <a href="/shop" class="btn btn-primary me-3"
                style="background: linear-gradient(135deg, var(--blue-600), var(--blue-700)); border: none; border-radius: 12px; padding: 15px 40px; font-weight: bold; font-size: 1.1rem;">
                Start Shopping <i class="bi bi-arrow-right"></i>
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-primary"
                style="border-color: var(--blue-600); color: var(--blue-600); border-radius: 12px; padding: 15px 40px; font-weight: bold; font-size: 1.1rem;">
                Contact Us <i class="bi bi-chat-dots"></i>
            </a>
        </div>

        <div class="community-features" style="margin-top: 60px;">
            <div class="row g-4">
                <div class="col-md-4">
                    <div style="padding: 20px; background-color: var(--white); border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                        <i class="bi bi-newspaper" style="font-size: 2rem; color: var(--blue-600);"></i>
                        <h5 style="margin-top: 15px; color: var(--slate-900);">Weekly Newsletter</h5>
                        <p style="color: var(--slate-700);">Get the latest product updates and parenting tips.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding: 20px; background-color: var(--white); border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                        <i class="bi bi-star-fill" style="font-size: 2rem; color: var(--blue-700);"></i>
                        <h5 style="margin-top: 15px; color: var(--slate-900);">Loyalty Program</h5>
                        <p style="color: var(--slate-700);">Earn points and unlock special rewards with every purchase.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding: 20px; background-color: var(--white); border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                        <i class="bi bi-people-fill" style="font-size: 2rem; color: var(--blue-700);"></i>
                        <h5 style="margin-top: 15px; color: var(--slate-900);">Parent Community</h5>
                        <p style="color: var(--slate-700);">Connect with other parents and share experiences.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

<style>
/* About page specific animations */
@keyframes floatY {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.about-decorations i {
    animation: floatY 3s ease-in-out infinite;
}

.about-decorations i:nth-child(1) { animation-delay: 0s; }
.about-decorations i:nth-child(2) { animation-delay: 0.5s; }
.about-decorations i:nth-child(3) { animation-delay: 1s; }
.about-decorations i:nth-child(4) { animation-delay: 1.5s; }

/* Section styling consistency */
.section-title {
    font-weight: bold;
}

.story-image-card {
    position: relative;
    min-height: 280px;
    max-width: 430px;
    margin-left: auto;
    overflow: hidden;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.1);
}

.story-image-card .story-slide {
    position: absolute;
    inset: 0;
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.45s ease;
}

.story-image-card .story-slide.is-active {
    opacity: 1;
}

.story-image-caption {
    position: absolute;
    left: 18px;
    right: 18px;
    bottom: 18px;
    padding: 16px 18px;
    border-radius: 14px;
    color: #ffffff;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(12px);
}

.story-image-caption strong,
.story-image-caption span {
    display: block;
}

.story-image-caption strong {
    font-size: 1.15rem;
}

.story-image-caption span {
    margin-top: 4px;
    opacity: 0.86;
}

.story-image-empty {
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 32px;
    text-align: center;
    color: var(--blue-600);
    background: #ffffff;
}

.story-image-empty i {
    font-size: 3.5rem;
}

.story-image-empty p {
    margin: 22px 0 8px;
    font-size: 1.45rem;
    font-weight: 900;
}

.story-image-empty span {
    font-size: 1rem;
    color: var(--blue-700);
}

@media (max-width: 991px) {
    .story-image-card {
        margin-right: auto;
    }
}
</style>
