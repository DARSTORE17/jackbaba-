<!-- resources/views/home.blade.php -->
@extends('layouts.app') <!-- assume header/footer already included -->

@section('content')
    <!-- ================= Hero Section ================= -->
    <section class="hero-section position-relative"
        style="background: linear-gradient(135deg, var(--aqua-500) 0%, var(--cyan-600) 50%, var(--blue-600) 100%); color: var(--white); padding: 120px 0; overflow: hidden;">
        <!-- Hero Particles Background -->
        <div class="hero-particles-container" id="heroParticles">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
            <div class="particle particle-4"></div>
            <div class="particle particle-5"></div>
        </div>

        <div class="container text-center position-relative" style="z-index: 3;">
            <div class="hero-content" style="animation: slideUp 1s ease-out;">
                <h1 class="hero-title"
                    style="font-size: 4rem; font-weight: 800; margin-bottom: 20px; text-shadow: 0 4px 20px rgba(0,0,0,0.3); animation: slideUp 1s ease-out 0.2s both;">
                    <span class="title-accent" style="color: var(--cyan-500);">Welcome to</span> KidzStore365
                </h1>
                <p class="hero-subtitle"
                    style="font-size: 1.25rem; margin: 30px 0; max-width: 750px; margin-left:auto; margin-right:auto; line-height: 1.6; opacity: 0.95; animation: slideUp 1s ease-out 0.4s both;">
                    ✨ Everything Cute, Fun & Safe for Your Little Ones ✨<br>
                    <span style="font-size: 1.1rem;">Explore toys, clothes, accessories, and more that spark imagination and
                        love!</span>
                </p>

                <!-- CTA Buttons -->
                <div class="hero-cta" style="margin-top: 40px; animation: slideUp 1s ease-out 0.6s both;">
                    <a href="/shop" class="btn btn-primary btn-lg hero-btn-primary"
                        style="border: none; border-radius: 25px; padding: 18px 45px; font-weight: 700; font-size: 1.1rem; box-shadow: 0 8px 25px rgba(255, 56, 147, 0.3); transform: translateY(0); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        🛍️ Shop Now
                        <span class="btn-arrow">→</span>
                    </a>
                    <a href="/categories" class="btn btn-outline-light btn-lg hero-btn-outline"
                        style="border-radius: 25px; padding: 18px 45px; font-weight: 700; font-size: 1.1rem; border-width: 2px; backdrop-filter: blur(10px);">
                        🌈 Explore Categories
                        <span class="btn-arrow">→</span>
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="trust-indicators"
                    style="margin-top: 50px; opacity: 0; animation: fadeIn 1s ease-out 0.8s forwards;">
                    <div class="row g-4 justify-content-center">
                        <div class="col-auto">
                            <div class="trust-item"
                                style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px);">
                                <i class="bi bi-shield-check-fill" style="color: var(--cyan-500); font-size: 1.2rem;"></i>
                                <span style="font-weight: 600;">100% Safe</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="trust-item"
                                style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px);">
                                <i class="bi bi-truck" style="color: var(--blue-600); font-size: 1.2rem;"></i>
                                <span style="font-weight: 600;">Fast Delivery</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Decorative Elements -->
        <div class="hero-decorations">
            <!-- Animated Icons -->
            <div class="floating-icon icon-1" style="position: absolute; top: 20%; left: 5%;">
                <i class="bi bi-star-fill" style="font-size: 2.5rem; color: rgba(255,255,255,0.7);"></i>
            </div>
            <div class="floating-icon icon-2" style="position: absolute; top: 15%; right: 10%;">
                <i class="bi bi-heart-fill" style="font-size: 3rem; color: rgba(255,255,255,0.6);"></i>
            </div>
            <div class="floating-icon icon-3" style="position: absolute; bottom: 25%; left: 8%;">
                <i class="bi bi-emoji-smile-fill" style="font-size: 3.5rem; color: rgba(255,255,255,0.5);"></i>
            </div>
            <div class="floating-icon icon-4" style="position: absolute; bottom: 20%; right: 15%;">
                <i class="bi bi-gift-fill" style="font-size: 2.8rem; color: rgba(255,255,255,0.6);"></i>
            </div>
            <div class="floating-icon icon-5" style="position: absolute; top: 40%; right: 5%;">
                <i class="bi bi-balloon-fill" style="font-size: 3.2rem; color: rgba(255,255,255,0.4);"></i>
            </div>

            <!-- Geometric Shapes -->
            <div class="geometric-shape shape-1"
                style="position: absolute; top: 30%; left: 20%; width: 80px; height: 80px; background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05)); border-radius: 50%;">
            </div>
            <div class="geometric-shape shape-2"
                style="position: absolute; bottom: 35%; right: 25%; width: 60px; height: 60px; background: linear-gradient(45deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03)); border-radius: 20px;">
            </div>
            <div class="geometric-shape shape-3"
                style="position: absolute; top: 60%; left: 30%; width: 40px; height: 40px; background: linear-gradient(45deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)); clip-path: polygon(50% 0%, 0% 100%, 100% 100%);">
            </div>
        </div>

        <!-- Optional Hero Image / Kids Toys -->
        <div class="hero-image"
            style="position: absolute; bottom: 10%; right: 8%; z-index: 2; animation: floatUp 6s ease-in-out infinite;">
            <div class="toy-circle-frame"
                style="width: 180px; height: 180px; border-radius: 50%; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,250,252,0.8)); display: flex; align-items: center; justify-content: center; box-shadow: 0 15px 35px rgba(0,0,0,0.15); border: 3px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px); overflow: hidden;">
                <img src="{{ asset('img/hero-toys.png') }}" alt="Kids Toys"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; animation: bounceIn 2s ease-out 1s both;">
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator"
            style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 3; animation: bounce 2s infinite;">
            <div
                style="width: 30px; height: 50px; border: 2px solid rgba(255,255,255,0.5); border-radius: 15px; position: relative;">
                <div
                    style="width: 4px; height: 8px; background: rgba(255,255,255,0.7); border-radius: 2px; position: absolute; top: 8px; left: 11px; animation: scrollDown 2s infinite;">
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Floating Animation -->
    <style>
        @keyframes floatY {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes floatUp {
            0% {
                transform: translateY(20px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(20px);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes scrollDown {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            50% {
                transform: translateY(15px);
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes floatRotate {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }

            100% {
                transform: translateY(0px) rotate(360deg);
            }
        }

        /* Enhanced Hero Animations */
        .hero-section .floating-icon {
            animation: floatRotate 8s ease-in-out infinite;
        }

        .hero-section .floating-icon.icon-1 {
            animation-delay: 0s;
        }

        .hero-section .floating-icon.icon-2 {
            animation-delay: 1s;
        }

        .hero-section .floating-icon.icon-3 {
            animation-delay: 2s;
        }

        .hero-section .floating-icon.icon-4 {
            animation-delay: 3s;
        }

        .hero-section .floating-icon.icon-5 {
            animation-delay: 4s;
        }

        .hero-section .geometric-shape {
            animation: floatY 6s ease-in-out infinite;
        }

        .hero-section .geometric-shape.shape-1 {
            animation-delay: 0.5s;
        }

        .hero-section .geometric-shape.shape-2 {
            animation-delay: 1.5s;
        }

        .hero-section .geometric-shape.shape-3 {
            animation-delay: 2.5s;
        }

        /* Hero Particles */
        .hero-particles-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.2) 100%);
            border-radius: 50%;
            animation: particleFloat 10s linear infinite;
        }

        .particle-1 {
            width: 4px;
            height: 4px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .particle-2 {
            width: 6px;
            height: 6px;
            top: 60%;
            left: 20%;
            animation-delay: 2s;
        }

        .particle-3 {
            width: 3px;
            height: 3px;
            top: 40%;
            left: 70%;
            animation-delay: 4s;
        }

        .particle-4 {
            width: 5px;
            height: 5px;
            top: 80%;
            left: 50%;
            animation-delay: 6s;
        }

        .particle-5 {
            width: 4px;
            height: 4px;
            top: 30%;
            left: 90%;
            animation-delay: 8s;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(0px) translateX(0px);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) translateX(-50px);
                opacity: 0;
            }
        }

        /* Hero Button Enhancements */
        .hero-btn-primary {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            position: relative;
            overflow: hidden;
        }

        .hero-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .hero-btn-primary:hover::before {
            left: 100%;
        }

        .hero-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 56, 147, 0.4);
        }

        .hero-btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-arrow {
            display: inline-block;
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .btn:hover .btn-arrow {
            transform: translateX(5px);
        }
    </style>



<!-- ================= What We Do Section ================= -->
<section class="what-we-do-section" style="padding: 80px 0; background: linear-gradient(135deg, var(--cyan-500), var(--blue-600)); color: var(--white);">
    <div class="container text-center">
        <h2 class="section-title" style="margin-bottom: 60px; font-size: 2.5rem;">What We Do?</h2>

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



    <!-- ================= Featured Categories Section ================= -->
    <section class="featured-categories"
        style="padding: 100px 0; background: linear-gradient(135deg, var(--slate-100) 0%, var(--slate-50) 100%); position: relative; overflow: hidden;">
        <!-- Section Background Pattern -->
        <div class="categories-bg-pattern">
            <div class="bg-circle bg-circle-1"
                style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: linear-gradient(45deg, rgba(47, 163, 154, 0.1), rgba(94, 209, 199, 0.05)); border-radius: 50%; animation: floatBg 8s ease-in-out infinite;">
            </div>
            <div class="bg-circle bg-circle-2"
                style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: linear-gradient(45deg, rgba(255, 137, 160, 0.08), rgba(255, 109, 145, 0.04)); border-radius: 50%; animation: floatBg 6s ease-in-out infinite reverse;">
            </div>
            <div class="bg-circle bg-circle-3"
                style="position: absolute; top: 50%; left: 10%; width: 80px; height: 80px; background: linear-gradient(45deg, rgba(94, 209, 199, 0.06), rgba(255, 182, 193, 0.03)); border-radius: 50%; animation: floatBg 7s ease-in-out infinite;">
            </div>
        </div>

        <div class="container text-center position-relative" style="z-index: 2;">
            <h2 class="section-title fs-5 fs-lg-1"
                style="margin-bottom: 60px; color: var(--slate-900); font-weight: 800; animation: fadeInUp 1s ease-out;">
                <span style="color: var(--blue-600);">🛍️ Shop by Categories</span>
                <div
                    style="width: 80px; height: 4px; background: linear-gradient(90deg, var(--blue-600), var(--aqua-500)); border-radius: 2px; margin: 15px auto 0;">
                </div>
            </h2>
            <div class="row g-4">
                <div class="col-lg-4 col-sm-6 col-12">
                    <a href="{{ route('category.show', 'baby-clothes') }}" class="text-decoration-none">
                        <div class="category-card"
                            style="background-color: var(--white); border-radius: 20px; padding: 40px 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(255,255,255,0.8); position: relative; overflow: hidden; animation: slideInUp 1s ease-out 0.1s both;">
                            <div class="category-icon"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--blue-600), var(--blue-800)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 8px 25px rgba(255, 56, 147, 0.3);">
                                <i class="bi bi-t-shirt" style="font-size: 2.5rem; color: var(--white);"></i>
                            </div>
                            <h3 class="category-title"
                                style="margin-bottom: 15px; color: var(--slate-900); font-weight: 700; font-size: 1.3rem;">Baby
                                Clothes</h3>
                            <p class="category-desc" style="color: var(--slate-700); font-size: 1rem; line-height: 1.6;">
                                Adorable outfits for newborns and toddlers with premium comfort and style.</p>
                            <div class="category-hover-bg"
                                style="position: absolute; bottom: 0; left: 0; width: 100%; height: 0; background: linear-gradient(135deg, var(--cyan-500), var(--blue-600)); transition: height 0.3s ease; opacity: 0.1;">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <a href="{{ route('category.show', 'kids-toys') }}" class="text-decoration-none">
                        <div class="category-card"
                            style="background-color: var(--white); border-radius: 20px; padding: 40px 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(255,255,255,0.8); position: relative; overflow: hidden; animation: slideInUp 1s ease-out 0.3s both;">
                            <div class="category-icon"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--cyan-600), var(--aqua-500)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 8px 25px rgba(47, 163, 154, 0.3);">
                                <i class="bi bi-capsule" style="font-size: 2.5rem; color: var(--white);"></i>
                            </div>
                            <h3 class="category-title"
                                style="margin-bottom: 15px; color: var(--slate-900); font-weight: 700; font-size: 1.3rem;">Kids
                                Toys</h3>
                            <p class="category-desc" style="color: var(--slate-700); font-size: 1rem; line-height: 1.6;">Fun
                                and safe toys designed to inspire imagination and learning adventures.</p>
                            <div class="category-hover-bg"
                                style="position: absolute; bottom: 0; left: 0; width: 100%; height: 0; background: linear-gradient(135deg, var(--blue-600), var(--cyan-500)); transition: height 0.3s ease; opacity: 0.1;">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <a href="{{ route('category.show', 'gifts-hampers') }}" class="text-decoration-none">
                        <div class="category-card"
                            style="background-color: var(--white); border-radius: 20px; padding: 40px 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(255,255,255,0.8); position: relative; overflow: hidden; animation: slideInUp 1s ease-out 0.5s both;">
                            <div class="category-icon"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--blue-700), var(--cyan-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 8px 25px rgba(255, 109, 145, 0.3);">
                                <i class="bi bi-gift" style="font-size: 2.5rem; color: var(--white);"></i>
                            </div>
                            <h3 class="category-title"
                                style="margin-bottom: 15px; color: var(--slate-900); font-weight: 700; font-size: 1.3rem;">
                                Gifts & Hampers</h3>
                            <p class="category-desc" style="color: var(--slate-700); font-size: 1rem; line-height: 1.6;">
                                Perfect presents for creating magical moments and lasting memories.</p>
                            <div class="category-hover-bg"
                                style="position: absolute; bottom: 0; left: 0; width: 100%; height: 0; background: linear-gradient(135deg, var(--cyan-500), var(--blue-700)); transition: height 0.3s ease; opacity: 0.1;">
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="categories-cta" style="margin-top: 50px; animation: fadeInUp 1s ease-out 0.7s both;">
                <a href="/categories" class="btn btn-outline-primary btn-lg"
                    style="border-color: var(--blue-600); color: var(--blue-600); border-radius: 25px; padding: 15px 40px; font-weight: 700; border-width: 2px; transition: all 0.3s ease;">
                    <i class="bi bi-eye"></i> View All Categories
                </a>
            </div>
        </div>
    </section>

    <!-- ================= Why Choose Us / Features Section ================= -->
    <section class="features-section"
        style="padding: 100px 0; background: linear-gradient(135deg, var(--white) 0%, var(--slate-100) 100%); position: relative; overflow: hidden;">
        <div class="container text-center position-relative" style="z-index: 2;">
            <h2 class="section-title fs-5 fs-lg-1"
                style="margin-bottom: 70px; color: var(--slate-900); font-weight: 800; animation: fadeInUp 1s ease-out;">
                <span style="color: var(--aqua-500);">✨ Why Choose KidzStore365?</span>
                <div
                    style="width: 80px; height: 4px; background: linear-gradient(90deg, var(--aqua-500), var(--blue-600)); border-radius: 2px; margin: 15px auto 0;">
                </div>
            </h2>

            <div class="row g-4">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="feature-card"
                        style="padding: 40px 30px; background-color: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); animation: slideInLeft 1s ease-out 0.1s both; position: relative; border: 1px solid rgba(255,255,255,0.8);">
                        <div class="feature-icon"
                            style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--aqua-500), var(--cyan-600)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 10px 30px rgba(47, 163, 154, 0.3); transform: rotate(-5deg); transition: transform 0.3s ease;">
                            <i class="bi bi-shield-check" style="font-size: 2.8rem; color: var(--white);"></i>
                        </div>
                        <h4 class="feature-title"
                            style="margin-bottom: 20px; color: var(--slate-900); font-weight: 700; font-size: 1.4rem;">Safe
                            & Trusted</h4>
                        <p style="color: var(--slate-700); line-height: 1.7; font-size: 1rem;">Every product meets the
                            highest safety standards, ensuring complete peace of mind for parents and caregivers.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="feature-card"
                        style="padding: 40px 30px; background-color: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); animation: slideInUp 1s ease-out 0.3s both; position: relative; border: 1px solid rgba(255,255,255,0.8);">
                        <div class="feature-icon"
                            style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--blue-600), var(--blue-800)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 10px 30px rgba(255, 56, 147, 0.3); transform: rotate(5deg); transition: transform 0.3s ease;">
                            <i class="bi bi-rocket" style="font-size: 2.8rem; color: var(--white);"></i>
                        </div>
                        <h4 class="feature-title"
                            style="margin-bottom: 20px; color: var(--slate-900); font-weight: 700; font-size: 1.4rem;">
                            Super Fast Delivery</h4>
                        <p style="color: var(--slate-700); line-height: 1.7; font-size: 1rem;">Swift and secure shipping
                            with real-time tracking, getting your orders to your doorstep in record time.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="feature-card"
                        style="padding: 40px 30px; background-color: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); animation: slideInRight 1s ease-out 0.5s both; position: relative; border: 1px solid rgba(255,255,255,0.8);">
                        <div class="feature-icon"
                            style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--cyan-600), var(--blue-700)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 10px 30px rgba(255, 109, 145, 0.3); transform: rotate(-5deg); transition: transform 0.3s ease;">
                            <i class="bi bi-heart-fill" style="font-size: 2.8rem; color: var(--white);"></i>
                        </div>
                        <h4 class="feature-title"
                            style="margin-bottom: 20px; color: var(--slate-900); font-weight: 700; font-size: 1.4rem;">
                            Loving Customer Care</h4>
                        <p style="color: var(--slate-700); line-height: 1.7; font-size: 1rem;">We adore our little
                            customers and provide 24/7 support with genuine care and dedication.</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial Preview -->
            <div class="testimonial-preview" style="margin-top: 80px; animation: fadeInUp 1s ease-out 0.7s both;">
                <div
                    style="display: inline-block; padding: 25px 40px; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248, 250, 252, 0.9)); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div
                            style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--aqua-500), var(--cyan-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(47, 163, 154, 0.3);">
                            <i class="bi bi-person-fill" style="color: var(--white); font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--slate-900);">Happy Parent</div>
                            <div style="font-size: 0.9rem; color: var(--cyan-600);">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p style="color: var(--slate-700); font-style: italic; font-size: 1rem; margin: 0;">
                        "KidzStore365 has made shopping for our little ones so magical! Everything is safe, beautiful, and
                        delivered with love! 💖"
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Section Animations -->
    <style>
        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes floatBg {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Enhanced Card Hover Effects */
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .category-card:hover .category-hover-bg {
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .feature-card:hover .feature-icon {
            transform: rotate(0deg) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        /* Section Background Patterns */
        .categories-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
    </style>

    <style>
        /* Mobile-first responsive styles */
        .hero-cta {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 20px;
            }

            .hero-title {
                font-size: 2rem !important;
            }

            .hero-subtitle {
                font-size: 1rem !important;
                margin: 20px 0;
            }

            .hero-btn-primary,
            .hero-btn-outline {
                padding: 8px 15px !important;
                font-size: 0.8rem !important;
            }

            .hero-cta {
                margin-top: 30px;
                gap: 8px;
                flex-wrap: nowrap;
            }

            .hero-image {
                display: none;
            }

            .trust-indicators {
                margin-top: 30px;
            }

            .trust-item span {
                font-size: 0.8rem;
            }

            .featured-categories,
            .features-section {
                padding: 60px 0;
            }

            .category-card,
            .feature-card {
                padding: 20px 15px;
            }

            .category-icon,
            .feature-icon {
                width: 60px;
                height: 60px;
            }

            .category-icon i,
            .feature-icon i {
                font-size: 2rem;
            }

            .category-title,
            .feature-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endsection
