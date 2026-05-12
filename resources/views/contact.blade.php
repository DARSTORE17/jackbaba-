@extends('layouts.app')

@section('title', ($systemSettings['contact_title'] ?? 'Contact Us') . ' - ' . ($systemSettings['site_name'] ?? 'Bravus Market'))

@section('content')
@php
    $phone = $systemSettings['phone'] ?? '';
    $whatsapp = preg_replace('/\D+/', '', $systemSettings['whatsapp'] ?? '');
    $email = $systemSettings['email'] ?? '';
    $address = $systemSettings['address'] ?? '';
    $hours = $systemSettings['business_hours'] ?? '';
@endphp

<main class="contact-page">
    <section class="contact-hero">
        <div class="container">
            <span class="contact-kicker">{{ $systemSettings['site_name'] ?? 'Bravus Market' }}</span>
            <h1>{{ $systemSettings['contact_title'] ?? 'Contact Us' }}</h1>
            <p>{{ $systemSettings['contact_description'] ?? '' }}</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <a class="contact-card" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
                    <i class="bi bi-telephone-fill"></i>
                    <span>Phone</span>
                    <strong>{{ $phone }}</strong>
                </a>

                <a class="contact-card" href="mailto:{{ $email }}">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Email</span>
                    <strong>{{ $email }}</strong>
                </a>

                <a class="contact-card" href="https://wa.me/{{ $whatsapp }}?text=Hello%2C%20I%20need%20help" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp"></i>
                    <span>WhatsApp</span>
                    <strong>{{ $systemSettings['whatsapp'] ?? '' }}</strong>
                </a>

                <div class="contact-card">
                    <i class="bi bi-clock-fill"></i>
                    <span>Business Hours</span>
                    <strong>{{ $hours }}</strong>
                </div>
            </div>

            <div class="contact-panel">
                <div>
                    <span class="contact-kicker">Visit Us</span>
                    <h2>{{ $address }}</h2>
                    <p>Our team is ready to help with product questions, orders, delivery, and after-sale support.</p>
                </div>
                <div class="contact-actions">
                    <a href="https://wa.me/{{ $whatsapp }}?text=Hello%2C%20I%20need%20help" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-whatsapp"></i>
                        Chat on WhatsApp
                    </a>
                    <a href="mailto:{{ $email }}" class="btn btn-outline-primary">
                        <i class="bi bi-envelope"></i>
                        Send Email
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('css')
<style>
    .contact-page {
        background: var(--background-color);
    }

    .contact-hero {
        padding: clamp(5rem, 10vw, 8rem) 0 clamp(3rem, 7vw, 5rem);
        color: #ffffff;
        background: var(--primary-color);
    }

    .contact-kicker {
        display: inline-flex;
        margin-bottom: 1rem;
        font-weight: 900;
        opacity: 0.9;
    }

    .contact-hero h1 {
        max-width: 760px;
        margin: 0;
        font-size: clamp(2.4rem, 6vw, 5rem);
        line-height: 1;
    }

    .contact-hero p {
        max-width: 720px;
        margin: 1.2rem 0 0;
        font-size: 1.12rem;
        line-height: 1.8;
        opacity: 0.88;
    }

    .contact-section {
        padding: clamp(3rem, 7vw, 5rem) 0;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.4rem;
    }

    .contact-card,
    .contact-panel {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .contact-card {
        display: flex;
        min-height: 170px;
        flex-direction: column;
        gap: 0.55rem;
        padding: 1.25rem;
        color: var(--primary-color);
        text-decoration: none;
    }

    .contact-card i {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: #ffffff;
        background: var(--primary-color);
        font-size: 1.25rem;
    }

    .contact-card span {
        margin-top: auto;
        color: rgba(15, 23, 42, 0.58);
        font-weight: 800;
    }

    .contact-card strong {
        color: var(--primary-color);
        font-size: 1rem;
        overflow-wrap: anywhere;
    }

    .contact-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        padding: clamp(1.4rem, 4vw, 2.4rem);
    }

    .contact-panel .contact-kicker {
        color: var(--primary-color);
    }

    .contact-panel h2 {
        margin: 0;
        color: var(--primary-color);
        font-size: clamp(1.8rem, 4vw, 3rem);
    }

    .contact-panel p {
        max-width: 640px;
        margin: 1rem 0 0;
        color: rgba(15, 23, 42, 0.72);
        line-height: 1.7;
    }

    .contact-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .contact-actions .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    @media (max-width: 991px) {
        .contact-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .contact-panel {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 575px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-card {
            min-height: 145px;
        }
    }
</style>
@endsection
