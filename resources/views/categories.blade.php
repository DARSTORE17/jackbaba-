@extends('layouts.app')

@section('title', 'Categories - ' . ($systemSettings['site_name'] ?? 'Bravus Market'))

@section('css')
<style>
    .categories-page {
        min-height: 100vh;
        padding: clamp(2.5rem, 6vw, 5rem) 0;
        background: var(--background-color);
    }

    .categories-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .categories-head h1 {
        margin: 0.8rem 0 0;
        color: var(--primary-color);
        font-size: clamp(2rem, 5vw, 4rem);
        line-height: 1;
        font-weight: 950;
    }

    .categories-head p {
        max-width: 680px;
        margin: 0.8rem 0 0;
        color: var(--primary-color);
        font-weight: 700;
        line-height: 1.7;
    }

    .soft-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        width: fit-content;
        padding: 0.62rem 0.9rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 999px;
        color: var(--primary-color);
        background: #ffffff;
        box-shadow: var(--shadow-softest);
        font-size: 0.88rem;
        font-weight: 900;
    }

    .categories-search {
        position: relative;
        min-width: min(420px, 100%);
    }

    .categories-search input {
        width: 100%;
        min-height: 54px;
        padding: 0 3.4rem 0 1rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 999px;
        color: var(--primary-color);
        background: #ffffff;
        box-shadow: var(--shadow-softest);
        font-weight: 800;
        outline: 0;
    }

    .categories-search button {
        position: absolute;
        top: 50%;
        right: 0.4rem;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 50%;
        color: #ffffff;
        background: var(--primary-color);
        transform: translateY(-50%);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .category-card {
        display: flex;
        min-height: 390px;
        overflow: hidden;
        flex-direction: column;
        border-radius: 24px;
        color: var(--primary-color);
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: var(--shadow-softest);
        text-decoration: none;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .category-card:hover {
        color: var(--primary-color);
        transform: translateY(-6px);
        box-shadow: var(--shadow-soft);
    }

    .category-media {
        height: 190px;
        margin: 0.85rem 0.85rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 18px;
        background: var(--secondary-color);
    }

    .category-media img {
        width: 100%;
        height: 100%;
        padding: 0.75rem;
        object-fit: contain;
        transition: transform 0.28s ease;
    }

    .category-card:hover .category-media img {
        transform: scale(1.04);
    }

    .category-placeholder {
        width: 74px;
        height: 74px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        color: #ffffff;
        background: var(--primary-color);
        font-size: 2rem;
    }

    .category-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 1.1rem;
    }

    .category-body h2 {
        margin: 0;
        color: var(--primary-color);
        font-size: 1.22rem;
        line-height: 1.25;
        font-weight: 950;
    }

    .category-body p {
        margin: 0.7rem 0 0;
        color: var(--primary-color);
        line-height: 1.65;
        font-weight: 700;
    }

    .category-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 1rem;
        font-weight: 950;
    }

    .category-count {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        padding: 0 0.7rem;
        border-radius: 999px;
        background: var(--secondary-color);
    }

    .category-arrow {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #ffffff;
        background: var(--primary-color);
    }

    .empty-state {
        padding: 4rem 1rem;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: var(--shadow-softest);
        text-align: center;
    }

    .empty-state i {
        color: var(--primary-color);
        font-size: 3rem;
    }

    .empty-state h2,
    .empty-state p {
        color: var(--primary-color);
    }

    @media (max-width: 1199px) {
        .categories-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .categories-head {
            align-items: stretch;
            flex-direction: column;
        }

        .categories-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }

        .category-card {
            min-height: 0;
        }
    }
</style>
@endsection

@section('content')
<main class="categories-page">
    <div class="container">
        <div class="categories-head">
            <div>
                <span class="soft-kicker">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    {{ $systemSettings['site_name'] ?? '' }}
                </span>
                <h1>Shop by Category</h1>
                <p>{{ $systemSettings['site_description'] ?? '' }}</p>
            </div>

            <form method="GET" action="{{ route('categories') }}" class="categories-search">
                <input type="text" name="search" placeholder="Search categories..." value="{{ request('search') }}">
                <button type="submit" aria-label="Search categories">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <div class="categories-grid">
            @forelse($categories as $category)
                @php
                    $hasImage = !empty($category->image)
                        && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image);
                    $imageUrl = $hasImage ? asset('storage/' . $category->image) : null;
                @endphp

                <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                    <div class="category-media">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $category->name }}" loading="lazy">
                        @else
                            <span class="category-placeholder">
                                <i class="bi bi-grid"></i>
                            </span>
                        @endif
                    </div>
                    <div class="category-body">
                        <h2>{{ $category->name }}</h2>
                        @if($category->description)
                            <p>{{ Str::limit($category->description, 95) }}</p>
                        @endif
                        <div class="category-bottom">
                            <span class="category-count">{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</span>
                            <span class="category-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h2>No categories found</h2>
                    <p>Try another search term or clear your search.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
