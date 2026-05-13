@extends('layouts.app')

@section('title', $category->name . ' - Bravus Market')

@section('css')
<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #5ed1c7;
        --accent-color: #3b82f6;
        --success-color: #22c55e;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-bg: #f8fafc;
        --dark-bg: #1e293b;
        --text-dark: #1e293b;
        --text-light: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
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
        min-height: 100vh;
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
        background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.08));
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
        bottom: 30%;
        right: 10%;
        animation-delay: 4s;
    }

    .bg-bubble-4 {
        width: 100px;
        height: 100px;
        bottom: -50px;
        left: 30%;
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
        background: radial-gradient(circle, rgba(59, 130, 246, 0.6) 0%, rgba(37, 99, 235, 0.3) 100%);
        border-radius: 50%;
        animation: particleFloat 12s linear infinite;
    }

    .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 50%;
        left: 15%;
        animation-delay: 2s;
    }

    .particle:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 30%;
        left: 75%;
        animation-delay: 4s;
    }

    .particle:nth-child(4) {
        width: 5px;
        height: 5px;
        top: 70%;
        left: 45%;
        animation-delay: 6s;
    }

    .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 85%;
        animation-delay: 8s;
    }

    .particle:nth-child(6) {
        width: 3px;
        height: 3px;
        top: 65%;
        left: 8%;
        animation-delay: 10s;
    }

    /* Enhanced Category Hero Section */
    .category-hero {
        position: relative;
        background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 25%, #064e3b 50%, #1e293b 75%, #1e3a8a 100%);
        background-size: 300% 300%;
        animation: gradientShift 8s ease-in-out infinite;
        color: white;
        padding: 40px 0;
        margin-bottom: 30px;
        border-radius: 0 0 20px 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .category-hero::before {
        content: '';
        position: absolute;
        top: -30%;
        left: -30%;
        width: 160%;
        height: 160%;
        background: radial-gradient(circle at center, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 40%, transparent 70%);
        animation: pulseGlow 6s ease-in-out infinite;
        z-index: 1;
    }

    .category-hero::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 80%, rgba(15, 81, 50, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(13, 92, 72, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(15, 81, 66, 0.06) 0%, transparent 50%);
        z-index: 0;
        animation: bgPatternMove 20s linear infinite;
    }

    .category-hero-content {
        position: relative;
        z-index: 3;
        max-width: 800px;
        margin: 0 auto;
    }

    .category-title {
        margin-bottom: 8px;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.95) 50%, rgba(255,255,255,0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 1px 5px rgba(0,0,0,0.1);
        position: relative;
        display: inline-block;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }

    .category-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 20%, rgba(255,255,255,1) 50%, rgba(255,255,255,0.8) 80%, rgba(255,255,255,0) 100%);
        border-radius: 2px;
    }

    .category-description {
        max-width: 600px;
        margin: 10px 0 0;
        opacity: 0.95;
    }

    .stat-item {
        text-align: center;
        background: rgba(255,255,255,0.15);
        padding: 15px 20px;
        border-radius: 12px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        min-width: 120px;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        background: rgba(255,255,255,0.2);
    }

    .stat-number {
        display: block;
        margin-bottom: 2px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.05);
        background: linear-gradient(135deg, #ffffff, rgba(255,255,255,0.9));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
    }

    .stat-label {
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: rgba(255,255,255,0.9);
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 8px 20px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        justify-content: center;
        margin-bottom: 30px;
        animation: fadeInUp 1s ease-out 0.8s both;
    }

    .breadcrumb-custom .breadcrumb-item a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-custom .breadcrumb-item a:hover {
        color: #ffffff;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #ffffff;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
        color: var(--blue-700);
        font-weight: bold;
    }

    /* Shop Container */
    .shop-container {
        max-width: 1400px;
        margin: -50px auto 0;
        padding: 40px;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 30px;
        box-shadow: var(--shadow-xl);
        position: relative;
        z-index: 3;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    /* Sticky Search and Controls */
    .sticky-search-controls {
        position: sticky;
        top: 201px;
        z-index: 10;
        margin-bottom: 40px;
    }

    /* Search Bar */
    .search-container {
        animation: fadeInUp 1s ease-out both;
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

    /* Categories Section */
    .subcategories-section {
        margin-bottom: 30px;
        background: var(--light-bg);
        padding: 25px;
        border-radius: 20px;
        position: sticky;
        top: 60px;
        z-index: 10;
    }

    .subcategories-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
        text-align: center;
    }

    .subcategories-grid {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 10px 0;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) transparent;
    }

    .subcategories-grid::-webkit-scrollbar {
        height: 4px;
    }

    .subcategories-grid::-webkit-scrollbar-track {
        background: transparent;
    }

    .subcategories-grid::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 2px;
    }

    .subcategory-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        white-space: nowrap;
        background: white;
        color: var(--text-dark);
        border-color: #e5e7eb;
    }

    .subcategory-pill:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .subcategory-pill:hover,
    .subcategory-pill.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Product Grid */
    .products-grid {
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        position: relative;
        break-inside: avoid;
        margin-bottom: 20px;
    }

    .product-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-5px);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        background: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 250px;
    }

    .product-image img {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .product-badge {
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #ffffff;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
    }

    .badge-discount {
        background: #2563eb;
    }

    .badge-new {
        background: #16a34a;
    }

    .badge-advertised {
        background: #2563eb;
    }

    .product-info {
        padding: 15px;
    }

    .product-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-title:hover {
        color: var(--primary-color);
    }

    .product-prices {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .product-old-price {
        font-size: 14px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 10px;
    }

    .stars {
        display: flex;
        gap: 2px;
    }

    .star {
        font-size: 12px;
        color: #fbbf24;
    }

    .rating-count {
        font-size: 12px;
        color: #6b7280;
    }

    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .stock-status {
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 500;
    }

    .stock-in {
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
    }

    .stock-low {
        background: rgba(251, 191, 36, 0.1);
        color: #b45309;
    }

    .stock-out {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Pagination */
    .pagination-custom {
        display: flex;
        justify-content: center;
        margin: 40px 0;
    }

    .pagination-custom .page-link {
        border: none;
        background: transparent;
        color: var(--text-dark);
        padding: 10px 15px;
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination-custom .page-link:hover,
    .pagination-custom .page-link.active {
        background: var(--primary-color);
        color: white;
    }

    /* Loading States */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: var(--light-bg);
        border-radius: 20px;
        margin-bottom: 40px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .products-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-card {
            display: flex;
            padding: 10px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .product-image {
            flex: 0 0 120px;
            height: auto;
            min-height: auto;
        }

        .product-info {
            flex: 1;
            padding-left: 15px;
            padding-top: 0;
        }

        .category-title {
            font-size: 2rem;
        }

        .shop-container {
            margin: -30px auto 0;
            padding: 20px;
            border-radius: 20px;
        }

        .category-hero {
            padding: 40px 0 60px;
        }

        .stat-number {
            font-size: 1.5rem;
        }
    }

    @media (min-width: 769px) {
        .products-grid {
            column-count: 5;
            column-gap: 15px;
        }
    }

    @media (min-width: 1200px) {
        .products-grid {
            column-count: 6;
        }
    }

    /* Advanced animations */
    .product-card:nth-child(odd) {
        animation-delay: 0.1s;
    }

    .product-card:nth-child(even) {
        animation-delay: 0.2s;
    }

    .product-card {
        animation: fadeInUp 0.6s ease-out both;
    }

    /* Missing Animations */
    @keyframes rotatePattern {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes growWidth {
        from { width: 0; }
        to { width: 120px; }
    }

    @keyframes floatBubble {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(0px) rotate(180deg); }
        75% { transform: translateY(20px) rotate(270deg); }
    }

    @keyframes particleFloat {
        0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.6; }
        25% { transform: translateY(-30px) translateX(15px); opacity: 0.8; }
        50% { transform: translateY(-15px) translateX(-10px); opacity: 0.4; }
        75% { transform: translateY(10px) translateX(20px); opacity: 1; }
    }

    /* Floating Decorative Elements */
    .floating-decoration {
        position: fixed;
        z-index: 2;
        pointer-events: none;
        animation: floatAround 10s ease-in-out infinite;
        font-size: 2rem;
        color: rgba(59, 130, 246, 0.3);
    }

    .decoration-1 {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
        animation-duration: 12s;
    }

    .decoration-1 i {
        animation: rotatePattern 15s linear infinite;
    }

    .decoration-2 {
        top: 20%;
        right: 15%;
        animation-delay: 2s;
        animation-duration: 14s;
        color: rgba(37, 99, 235, 0.3);
    }

    .decoration-2 i {
        animation: pulse 2s ease-in-out infinite;
    }

    .decoration-3 {
        bottom: 25%;
        left: 12%;
        animation-delay: 4s;
        animation-duration: 16s;
        color: rgba(251, 191, 36, 0.3);
        font-size: 1.5rem;
    }

    .decoration-3 i {
        animation: bounce 3s ease-in-out infinite;
    }

    .decoration-4 {
        bottom: 35%;
        right: 10%;
        animation-delay: 6s;
        animation-duration: 18s;
        color: rgba(239, 68, 68, 0.3);
    }

    .decoration-4 i {
        animation: scale 4s ease-in-out infinite;
    }

    @keyframes floatAround {
        0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
        25% { transform: translateY(-20px) translateX(15px) rotate(90deg); }
        50% { transform: translateY(10px) translateX(-20px) rotate(180deg); }
        75% { transform: translateY(-15px) translateX(10px) rotate(270deg); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    @keyframes scale {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(180deg); }
    }

    /* Enhanced search focused state */
    .search-bar.search-focused input {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), var(--shadow-lg);
    }

    /* Category badge in product meta */
    .product-meta .category {
        font-size: 11px;
        color: #6b7280;
    }

    /* Pagination custom class */
    .pagination-custom .page-link {
        border: 2px solid rgba(59, 130, 246, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .pagination-custom .page-link:hover,
    .pagination-custom .page-link.active {
        background: var(--primary-color);
        color: white;
        box-shadow: var(--shadow-md);
    }

    /* Controls bar styling */
    .controls-bar select:focus {
        border-color: var(--blue-600);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: scale(1.02);
    }

    /* Subcategory badge styling */
    .subcategory-pill .badge {
        border-radius: 8px;
        font-weight: 600;
    }

    /* Empty state enhancements */
    .empty-state p {
        color: #6b7280;
        margin-bottom: 20px;
    }

    .empty-state .btn {
        border-radius: 25px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .empty-state .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Hero section additional animations */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes pulseGlow {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
    }

    @keyframes bgPatternMove {
        0% { transform: translateX(0%) translateY(0%) rotate(0deg); }
        50% { transform: translateX(-5px) translateY(-5px) rotate(180deg); }
        100% { transform: translateX(0%) translateY(0%) rotate(360deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Back to shop button */
    .back-to-shop {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: rgba(255,255,255,0.9);
        color: var(--primary-color);
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: var(--shadow-lg);
    }

    .back-to-shop:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
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

<!-- Back to Shop -->
<a href="{{ route('shop') }}" class="back-to-shop" title="Back to Shop">
    <i class="bi bi-arrow-left"></i>
</a>

<!-- Category Hero Section -->
<div class="category-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="category-title display-6 fw-semibold text-start">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="category-description lead text-start">{{ $category->description }}</p>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <div class="stat-item d-inline-block">
                    <span class="stat-number fw-medium">{{ $products->total() }}</span>
                    <div class="stat-label small">Total Products</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-container ">
    <!-- Browse Other Categories -->
    <div class="subcategories-section">
        <h3 class="subcategories-title">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>Browse Other Categories
        </h3>
        <div class="subcategories-grid">
            @foreach($categories->where('id', '!=', $category->id) as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="subcategory-pill">
                    {{ $cat->name }}
                    <span class="badge ms-1" style="background: var(--success-color); color: white; font-size: 10px;">{{ $cat->products_count ?? $cat->products->count() }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Sticky Search and Controls -->
    <div class="sticky-search-controls">
        <!-- Search Bar -->
        <div class="search-container">
            <form method="GET" action="{{ route('category.show', $category->slug) }}">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search in {{ $category->name }}..." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Controls Bar -->
        <div class="controls-bar mb-4" style="animation: fadeInUp 1s ease-out both; animation-delay: 0.2s;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <!-- Sort Dropdown -->
            <div class="sort-dropdown">
                <select name="sort_by" onchange="changeSort(this.value)"
                        class="form-select"
                        style="min-width: 200px; border-radius: 15px; border: 2px solid rgba(59, 130, 246, 0.2);
                               background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);
                               box-shadow: var(--shadow-sm); transition: all 0.3s ease; font-weight: 500;">
                    <option value="" style="color: var(--slate-700);">Sort By</option>
                    <option value="name-asc" {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name-desc" {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="new_price-asc" {{ request('sort_by') == 'new_price' && request('sort_order') == 'asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="new_price-desc" {{ request('sort_by') == 'new_price' && request('sort_order') == 'desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    <option value="created_at-desc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="rate-desc" {{ request('sort_by') == 'rate' && request('sort_order') == 'desc' ? 'selected' : '' }}>Highest Rated</option>
                </select>
            </div>

            <!-- Filters -->
            <div class="d-flex gap-2 flex-wrap">
                @php
                    $currentUrl = url()->current();
                    $queryParams = request()->query();
                @endphp

                <a href="{{ $currentUrl }}?{{ http_build_query(array_merge($queryParams, ['in_stock' => '1'])) }}"
                   class="btn btn-outline-primary btn-sm {{ request('in_stock') == '1' ? 'btn-primary' : '' }}"
                   style="border-radius: 20px; border: 2px solid rgba(59, 130, 246, 0.3); font-weight: 600;
                          transition: all 0.3s ease; backdrop-filter: blur(10px); {{ request('in_stock') == '1' ? 'color: white; background: linear-gradient(135deg, var(--blue-600), var(--blue-700));' : 'color: var(--blue-600); background: rgba(59, 130, 246, 0.05);' }}">
                    <i class="bi bi-check-circle-fill me-1"></i>✓ In Stock Only
                </a>
                <a href="{{ $currentUrl }}?{{ http_build_query(array_merge($queryParams, ['on_sale' => '1'])) }}"
                   class="btn btn-outline-primary btn-sm {{ request('on_sale') == '1' ? 'btn-primary' : '' }}"
                   style="border-radius: 20px; border: 2px solid rgba(59, 130, 246, 0.3); font-weight: 600;
                          transition: all 0.3s ease; backdrop-filter: blur(10px); {{ request('on_sale') == '1' ? 'color: white; background: linear-gradient(135deg, var(--blue-600), var(--blue-700));' : 'color: var(--blue-600); background: rgba(59, 130, 246, 0.05);' }}">
                    <i class="bi bi-percent me-1"></i>On Sale
                </a>
                <a href="{{ $currentUrl }}?{{ http_build_query(array_merge($queryParams, ['rating' => '4'])) }}"
                   class="btn btn-outline-primary btn-sm {{ request('rating') == '4' ? 'btn-primary' : '' }}"
                   style="border-radius: 20px; border: 2px solid rgba(59, 130, 246, 0.3); font-weight: 600;
                          transition: all 0.3s ease; backdrop-filter: blur(10px); {{ request('rating') == '4' ? 'color: white; background: linear-gradient(135deg, var(--blue-600), var(--blue-700));' : 'color: var(--blue-600); background: rgba(59, 130, 246, 0.05);' }}">
                    <i class="bi bi-star-fill me-1"></i>4+ Stars
                </a>

                @if(count(array_filter($queryParams, fn($v, $k) => in_array($k, ['in_stock', 'on_sale', 'rating']) && $v, ARRAY_FILTER_USE_BOTH)))
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="btn btn-outline-secondary btn-sm"
                       style="border-radius: 20px; border: 2px solid rgba(107, 114, 128, 0.3); font-weight: 600;
                              transition: all 0.3s ease; backdrop-filter: blur(10px); color: var(--slate-600);
                              background: rgba(107, 114, 128, 0.05);">
                        <i class="bi bi-x-circle-fill me-1"></i>Clear Filters
                    </a>
                @endif
            </div>
        </div>
    </div>
    </div> <!-- end sticky-search-controls -->

    <!-- Products Grid -->
    <div class="products-grid">
    @forelse($products as $product)
        <article class="product-card">
            <div class="product-image">
                <a href="{{ route('shop.show', $product->slug) }}">
                    <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('img/logo.png') }}" alt="{{ $product->name }}" loading="lazy">
                </a>
                <div class="product-badges">
                    @if($product->discount > 0)
                        <span class="product-badge badge-discount">{{ $product->discount }}% OFF</span>
                    @endif
                    @if($product->is_advertised)
                        <span class="product-badge badge-advertised">Featured</span>
                    @endif
                    @if($product->created_at->diffInDays(now()) <= 7)
                        <span class="product-badge badge-new">New</span>
                    @endif
                </div>
            </div>

            <div class="product-info">
                <h3 class="product-title">
                    <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none">
                        {{ $product->name }}
                    </a>
                </h3>

                <div class="product-prices">
                    <span class="product-price">Tsh{{ number_format($product->new_price, 2) }}</span>
                    @if($product->old_price && $product->old_price > $product->new_price)
                        <span class="product-old-price">Tsh{{ number_format($product->old_price, 2) }}</span>
                    @endif
                </div>

                @if($product->rate > 0)
                <div class="product-rating">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $product->rate ? 'bi-star-fill' : 'bi-star' }} star"></i>
                        @endfor
                    </div>
                    <span class="rating-count">({{ $product->rate }}.0)</span>
                </div>
                @endif

                <div class="product-meta">
                    <span class="stock-status {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                        @if($product->stock > 10)
                            In Stock
                        @elseif($product->stock > 0)
                            Only {{ $product->stock }} left
                        @else
                            Out of Stock
                        @endif
                    </span>
                    <span class="category">
                        <i class="bi bi-tag-fill"></i> {{ $category->name }}
                    </span>
                </div>
            </div>
        </article>
    @empty
        <div class="no-products-found d-flex align-items-center justify-content-center" style="min-height: 60vh;">
            <div class="text-center">
                <i class="bi bi-search text-muted animated-icon" style="font-size: 5rem; animation: pulse 2s infinite; display: block;"></i><br>
                <p class="text-primary">
                    No products found in {{ $category->name }}. Try adjusting your search or browse other categories!
                </p><br>
                <a href="{{ route('shop') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left me-2"></i>Back to Shop
                </a>
            </div>
        </div>
        </div>
    @endforelse

    <!-- Pagination -->
    {{ $products->appends(request()->query())->links() }}
</div>

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

    // Auto-scroll effect for subcategories
    const subcategoryGrid = document.querySelector('.subcategories-grid');
    if (subcategoryGrid && subcategoryGrid.scrollWidth > subcategoryGrid.clientWidth) {
        // Auto-scroll functionality could be added here
    }

    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
    }

    // Add loading animation to product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Change sorting
function changeSort(value) {
    if (!value) return;
    const [sortBy, sortOrder] = value.split('-');
    const url = new URL(window.location);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    // Remove conflicting params
    url.searchParams.delete('search');
    url.searchParams.delete('in_stock');
    url.searchParams.delete('on_sale');
    url.searchParams.delete('rating');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

// Smooth scroll to top when back to shop
document.querySelector('.back-to-shop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setTimeout(() => {
        window.location.href = this.href;
    }, 300);
});
</script>
@endsection
