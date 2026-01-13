<!-- Categories Page - Kids Shop Kids Store365 -->
@extends('layouts.app')

@section('title', 'Categories - KidsStore365')

@section('css')
<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #5ed1c7;
        --accent-color: #3b82f6;
        --success-color: #22c55e;
        --light-bg: #f8fafc;
        --text-dark: #1e293b;
        --text-light: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --cyan-500: #06b6d4;
        --cyan-600: #0891b2;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --aqua-500: #5ed1c7;
        --slate-100: #f1f5f9;
        --slate-700: #334155;
        --slate-900: #0f172a;
        --white: #ffffff;
    }

    body {
        background: linear-gradient(135deg, var(--slate-100) 0%, var(--light-bg) 100%);
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        overflow-x: hidden;
    }

    /* Animated Background Elements */
    .page-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    .bg-bubble {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(94, 209, 199, 0.08));
        animation: floatBubble 8s ease-in-out infinite;
    }

    .bg-bubble-1 {
        width: 300px;
        height: 300px;
        top: -150px;
        right: -150px;
        animation-delay: 0s;
    }

    .bg-bubble-2 {
        width: 200px;
        height: 200px;
        top: 20%;
        left: -100px;
        animation-delay: 2s;
    }

    .bg-bubble-3 {
        width: 150px;
        height: 150px;
        bottom: 10%;
        right: 10%;
        animation-delay: 4s;
    }

    .bg-bubble-4 {
        width: 100px;
        height: 100px;
        bottom: -50px;
        left: 20%;
        animation-delay: 6s;
    }

    /* Particle Effects */
    .particles-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.6) 0%, rgba(94, 209, 199, 0.3) 100%);
        border-radius: 50%;
        animation: particleFloat 12s linear infinite;
    }

    .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 60%;
        left: 20%;
        animation-delay: 2s;
    }

    .particle:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 40%;
        left: 70%;
        animation-delay: 4s;
    }

    .particle:nth-child(4) {
        width: 5px;
        height: 5px;
        top: 80%;
        left: 50%;
        animation-delay: 6s;
    }

    .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 30%;
        left: 90%;
        animation-delay: 8s;
    }

    .particle:nth-child(6) {
        width: 3px;
        height: 3px;
        top: 70%;
        left: 5%;
        animation-delay: 10s;
    }

    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        position: relative;
        z-index: 2;
    }

    .page-header {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
    }

    .page-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--blue-600), var(--cyan-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
        animation: slideInDown 1s ease-out;
        position: relative;
        display: inline-block;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, var(--blue-600), var(--aqua-500));
        border-radius: 2px;
        animation: growWidth 1.2s ease-out 0.5s forwards;
        animation-fill-mode: both;
    }

    .page-subtitle {
        font-size: 1.2rem;
        color: var(--slate-700);
        max-width: 700px;
        margin: 0 auto;
        animation: fadeInUp 1s ease-out 0.3s both;
        font-weight: 500;
    }

    .breadcrumbs {
        margin-bottom: 30px;
        text-align: center;
        animation: fadeInUp 1s ease-out 0.6s both;
    }

    .breadcrumb {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 25px;
        padding: 8px 20px;
        display: inline-flex;
        align-items: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: var(--shadow-sm);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: var(--cyan-500);
        font-weight: bold;
    }

    .breadcrumb-item a {
        color: var(--blue-600);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--cyan-500);
    }

    .breadcrumb-item.active {
        color: var(--slate-900);
        font-weight: 600;
    }

    .search-container {
        margin-bottom: 60px;
        text-align: center;
        animation: fadeInUp 1s ease-out 0.8s both;
        position: sticky;
        top: 60px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 20px;
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .search-bar {
        position: relative;
        max-width: 600px;
        margin: 0 auto;
    }

    .search-bar input {
        width: 100%;
        padding: 18px 60px 18px 25px;
        border: 2px solid rgba(59, 130, 246, 0.2);
        border-radius: 50px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }

    .search-bar input:focus {
        outline: none;
        border-color: var(--blue-600);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), var(--shadow-lg);
        transform: scale(1.02);
    }

    .search-bar input::placeholder {
        color: var(--slate-700);
        opacity: 0.7;
    }

    .search-bar button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, var(--blue-600), var(--blue-700));
        color: white;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .search-bar button:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .search-bar button i {
        font-size: 1.1rem;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
    }

    .category-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: block;
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        position: relative;
        animation: slideInUp 0.8s ease-out both;
        cursor: pointer;
    }

    .category-card:nth-child(1) { animation-delay: 0.1s; }
    .category-card:nth-child(2) { animation-delay: 0.2s; }
    .category-card:nth-child(3) { animation-delay: 0.3s; }
    .category-card:nth-child(4) { animation-delay: 0.4s; }

    .category-card:hover {
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        transform: translateY(-10px) scale(1.02);
        border-color: var(--blue-600);
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(59, 130, 246, 0.05), rgba(94, 209, 199, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .category-card:hover::before {
        opacity: 1;
    }

    .category-header {
        background: linear-gradient(135deg, var(--blue-600), var(--cyan-500));
        padding: 40px 25px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .category-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: repeating-conic-gradient(from 0deg, rgba(255,255,255,0.1) 0deg 90deg, transparent 90deg 180deg);
        animation: rotatePattern 20s linear infinite;
    }

    .category-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1) rotateY(180deg);
        background: rgba(255,255,255,0.3);
        box-shadow: 0 12px 35px rgba(0,0,0,0.2);
    }

    .category-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .category-description {
        font-size: 1rem;
        opacity: 0.95;
        margin-bottom: 20px;
        line-height: 1.6;
        position: relative;
        z-index: 2;
    }

    .category-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        opacity: 0.9;
        background: rgba(255,255,255,0.1);
        padding: 8px 15px;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        z-index: 2;
    }

    .category-footer {
        padding: 25px;
        text-align: center;
        background: linear-gradient(135deg, rgba(241, 245, 249, 0.8), rgba(248, 250, 252, 0.9));
        position: relative;
    }

    .explore-btn {
        background: linear-gradient(135deg, var(--cyan-500), var(--aqua-500));
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(94, 209, 199, 0.3);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .explore-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .explore-btn:hover::before {
        left: 100%;
    }

    .explore-btn:hover {
        background: linear-gradient(135deg, var(--cyan-600), var(--aqua-500));
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(94, 209, 199, 0.4);
    }

    .explore-btn i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .explore-btn:hover i {
        transform: translateX(3px);
    }

    .empty-state {
        text-align: center;
        padding: 100px 30px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        box-shadow: var(--shadow-lg);
        margin-top: 60px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        animation: bounceIn 1s ease-out;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
        z-index: -1;
    }

    .empty-state i {
        font-size: 5rem;
        background: linear-gradient(135deg, var(--blue-600), var(--cyan-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        animation: bounce 2s infinite;
    }

    .empty-state h3 {
        color: var(--text-dark);
        margin-bottom: 15px;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .empty-state p {
        color: var(--slate-700);
        margin-bottom: 30px;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .empty-state .btn {
        background: linear-gradient(135deg, var(--blue-600), var(--blue-700));
        color: white;
        padding: 15px 30px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        border: none;
    }

    .empty-state .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        color: white;
    }

    /* Floating Decorative Elements */
    .floating-decoration {
        position: absolute;
        z-index: 0;
        pointer-events: none;
        animation: floatRotate 8s ease-in-out infinite;
    }

    .floating-decoration.decoration-1 {
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .floating-decoration.decoration-1 i {
        font-size: 3rem;
        color: rgba(59, 130, 246, 0.3);
    }

    .floating-decoration.decoration-2 {
        top: 5%;
        right: 10%;
        animation-delay: 2s;
    }

    .floating-decoration.decoration-2 i {
        font-size: 4rem;
        color: rgba(94, 209, 199, 0.25);
    }

    .floating-decoration.decoration-3 {
        bottom: 15%;
        left: 3%;
        animation-delay: 4s;
    }

    .floating-decoration.decoration-3 i {
        font-size: 3.5rem;
        color: rgba(37, 99, 235, 0.2);
    }

    .floating-decoration.decoration-4 {
        bottom: 10%;
        right: 5%;
        animation-delay: 6s;
    }

    .floating-decoration.decoration-4 i {
        font-size: 4.2rem;
        color: rgba(94, 209, 199, 0.3);
    }

    /* Animations */
    @keyframes slideInDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes growWidth {
        from {
            width: 0;
        }
        to {
            width: 100px;
        }
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

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

    @keyframes rotatePattern {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes floatBubble {
        0%, 100% {
            transform: translateY(0px) scale(1);
        }
        50% {
            transform: translateY(-30px) scale(1.1);
        }
    }

    @keyframes particleFloat {
        0% {
            transform: translateY(0px) translateX(0px);
            opacity: 0;
        }
        10% {
            opacity: 0.7;
        }
        90% {
            opacity: 0.7;
        }
        100% {
            transform: translateY(-100vh) translateX(-50px);
            opacity: 0;
        }
    }

    @keyframes floatRotate {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .page-title {
            font-size: 2.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .category-card {
            margin-bottom: 20px;
        }

        .floating-decoration {
            display: none;
        }

        .bg-bubble {
            display: none;
        }

        .breadcrumb {
            padding: 6px 15px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .page-container {
            padding: 15px;
        }

        .page-title {
            font-size: 2rem;
        }

        .category-header {
            padding: 30px 20px;
        }

        .category-name {
            font-size: 1.5rem;
        }

        .category-icon {
            width: 70px;
            height: 70px;
            font-size: 1.8rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Animated Background Elements -->
<div class="page-background">
    <div class="bg-bubble bg-bubble-1"></div>
    <div class="bg-bubble bg-bubble-2"></div>
    <div class="bg-bubble bg-bubble-3"></div>
    <div class="bg-bubble bg-bubble-4"></div>
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
</div>

<!-- Floating Decorative Elements -->
<div class="floating-decoration decoration-1">
    <i class="bi bi-star-fill"></i>
</div>
<div class="floating-decoration decoration-2">
    <i class="bi bi-heart-fill"></i>
</div>
<div class="floating-decoration decoration-3">
    <i class="bi bi-balloon-fill"></i>
</div>
<div class="floating-decoration decoration-4">
    <i class="bi bi-emoji-smile-fill"></i>
</div>

<div class="page-container">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Categories
                </li>
            </ol>
        </nav>
    </div>

    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-grid-3x3-gap me-3"></i>
            🎨 Shop by Category
        </h1>
        <p class="page-subtitle">
            ✨ Discover amazing collections specially curated for your little ones ✨<br>
            <span style="font-size: 1rem; color: var(--cyan-500); font-weight: 600;">
                🌈 Toys • 👕 Clothing • 🎁 Gifts & More
            </span>
        </p>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <form method="GET" action="{{ route('categories') }}">
            <div class="search-bar">
                <input type="text" name="search"
                       placeholder="🔍 Search for categories like 'toys', 'clothes', 'gifts'..."
                       value="{{ request('search') }}">
                <button type="submit" title="Search Categories">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Grid -->
    <div class="categories-grid">
        @forelse($categories as $category)
            @php
                $icons = [
                    'baby-clothes' => 'bi-t-shirt',
                    'kids-toys' => 'bi-rocket-takeoff',
                    'gifts-hampers' => 'bi-gift',
                ];
                $colors = [
                    'baby-clothes' => ['--blue-600', '--cyan-500'],
                    'kids-toys' => ['--cyan-600', '--aqua-500'],
                    'gifts-hampers' => ['--blue-700', '--cyan-600'],
                ];
                $icon = $icons[$category->slug] ?? 'bi-circle';
                $gradient = $colors[$category->slug] ?? ['--blue-600', '--cyan-500'];
            @endphp

            <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                <div class="category-header" style="background: linear-gradient(135deg, {{ $gradient[0] }}, {{ $gradient[1] }});">
                    <div class="category-icon">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h2 class="category-name">{{ $category->name }}</h2>
                    @if($category->description)
                        <p class="category-description">{{ Str::limit($category->description, 100) }}</p>
                    @endif
                    <div class="category-stats">
                        <span>
                            <i class="bi bi-box-seam me-1"></i>
                            {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                        </span>
                    </div>
                </div>
                <div class="category-footer">
                    <span class="explore-btn">
                        Explore {{ $category->name }}
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <i class="bi bi-search-heart"></i>
                <h3>Oops! No categories found</h3>
                <p>
                    We couldn't find any categories matching your search.
                    <br><strong>Try searching for:</strong> toys, clothes, gifts, or hampers
                </p>
                <a href="{{ route('shop') }}" class="btn">
                    <i class="bi bi-shop me-2"></i>Browse All Products
                </a>
                <br><br>
                <a href="{{ route('categories') }}" class="text-decoration-none">
                    <small style="color: var(--cyan-500); font-weight: 500;">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Clear search & show all categories
                    </small>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Fun Footer Call-to-Action -->
    @if($categories->isNotEmpty())
    <div style="text-align: center; margin-top: 80px; padding: 40px 20px; animation: fadeInUp 1s ease-out 1s both;">
        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(94, 209, 199, 0.08));
                    border-radius: 25px; padding: 40px; backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2); max-width: 600px; margin: 0 auto;
                    box-shadow: var(--shadow-lg);">
            <h3 style="color: var(--slate-900); margin-bottom: 15px; font-weight: 700;">
                🎈 Can't find what you're looking for?
            </h3>
            <p style="color: var(--slate-700); margin-bottom: 25px; line-height: 1.6;">
                Browse our complete collection of amazing kids' products!
            </p>
            <a href="{{ route('shop') }}" class="explore-btn"
               style="background: linear-gradient(135deg, var(--blue-600), var(--blue-700)); color: white;">
                🛍️ Explore All Products
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-search functionality
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = searchInput.closest('form');
    let searchTimeout;

    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchValue = e.target.value.trim();
            if (searchValue.length >= 2 || searchValue.length === 0) {
                searchForm.submit();
            }
        }, 800); // Wait 800ms after user stops typing
    });

    // Enter key behavior
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            searchForm.submit();
        }
    });

    // Clear search button
    const searchButton = searchForm.querySelector('button[type="submit"]');
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        searchForm.submit();
    });

    // Enhance search input styling
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('search-focused');
    });

    searchInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('search-focused');
    });
});
</script>
