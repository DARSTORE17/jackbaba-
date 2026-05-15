@extends('layouts.admin')

@section('styles')
    <style>
        .settings-page {
            background: #f4f8ff;
        }

        .settings-card {
            border: 1px solid rgba(13, 110, 253, 0.16);
            border-radius: 1rem;
            overflow: hidden;
            background: #ffffff;
        }

        .settings-card .card-header,
        .settings-card .card-body {
            border: none;
        }

        .settings-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid rgba(13, 110, 253, 0.08);
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 0.02em;
        }

        .settings-card .form-label {
            color: #0d6efd;
            font-weight: 600;
        }

        .settings-card .form-control {
            border-color: rgba(13, 110, 253, 0.2);
        }

        .settings-card .form-control:focus {
            border-color: #0d6efd;
            box-shadow: none;
        }

        .settings-preview-card {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
        }

        .settings-preview-card .color-code {
            margin-top: 0.35rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .settings-actions {
            margin-top: 0.75rem;
        }

        .settings-actions .btn-primary {
            background: #0d6efd;
            border-color: #0d6efd;
        }

        .settings-action-note {
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 992px) {
            .settings-card {
                border-radius: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .settings-card {
                padding: 0;
            }

            .settings-preview-card {
                min-height: 140px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-3 px-lg-4 settings-page">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Storefront Settings</h2>
            <p class="text-muted mb-0">Manage storefront colors, brand text, hero content, shop content, and contact details from one place.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Color Palette</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            @foreach(['primary' => 'Primary Brand Color', 'secondary' => 'Secondary Brand Color'] as $key => $label)
                                <div class="col-12 col-sm-6">
                                    <label for="{{ $key }}" class="form-label">{{ $label }} Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $colors[$key] ?? '#0d6efd') }}">
                                        <input type="text" class="form-control color-text" data-target="{{ $key }}" value="{{ old($key, $colors[$key] ?? '#0d6efd') }}">
                                    </div>
                                    @error($key)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Live Preview</div>
                    <div class="card-body">
                        <div class="row gx-3 gy-3">
                            @foreach(['primary' => 'Primary', 'secondary' => 'Secondary'] as $key => $label)
                                <div class="col-12 col-sm-6">
                                    <div id="preview-{{ $key }}" class="settings-preview-card" data-color="{{ old($key, $colors[$key] ?? '#0d6efd') }}">
                                        <div class="fw-bold">{{ $label }}</div>
                                        <div class="small color-code">{{ old($key, $colors[$key] ?? '#0d6efd') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Brand Content</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="site_name">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="site_tagline">Tagline</label>
                                <input type="text" class="form-control" id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="site_description">Footer Description</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Storefront Media</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="logo">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @if(!empty($settings['logo_path']))
                                    <img src="{{ media_url($settings['logo_path']) }}" alt="Current logo" class="img-fluid rounded mt-2" style="max-height: 90px;">
                                @endif
                                @error('logo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_image">Hero Image</label>
                                <input type="file" class="form-control" id="hero_image" name="hero_image" accept="image/*">
                                @if(!empty($settings['hero_image_path']))
                                    <img src="{{ media_url($settings['hero_image_path']) }}" alt="Current hero image" class="img-fluid rounded mt-2" style="max-height: 90px;">
                                @endif
                                @error('hero_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="about_store_image">About Store Card Image 1</label>
                                <input type="file" class="form-control" id="about_store_image" name="about_store_image" accept="image/*">
                                @if(!empty($settings['about_store_image_path']))
                                    <img src="{{ media_url($settings['about_store_image_path']) }}" alt="Current about store image" class="img-fluid rounded mt-2" style="max-height: 110px;">
                                @endif
                                @error('about_store_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="about_store_image_2">About Store Card Image 2</label>
                                <input type="file" class="form-control" id="about_store_image_2" name="about_store_image_2" accept="image/*">
                                @if(!empty($settings['about_store_image_2_path']))
                                    <img src="{{ media_url($settings['about_store_image_2_path']) }}" alt="Current second about store image" class="img-fluid rounded mt-2" style="max-height: 110px;">
                                @endif
                                @error('about_store_image_2')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_video">Hero Background Video</label>
                                <input type="file" class="form-control" id="hero_video" name="hero_video" accept="video/mp4,video/webm,video/ogg">
                                @if(!empty($settings['hero_video_path']))
                                    <video src="{{ media_url($settings['hero_video_path']) }}" class="w-100 rounded mt-2" style="max-height: 90px;" muted controls></video>
                                @endif
                                @error('hero_video')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="about_store_image_title">About Store Card Title</label>
                                <input type="text" class="form-control" id="about_store_image_title" name="about_store_image_title" value="{{ old('about_store_image_title', $settings['about_store_image_title'] ?? '') }}">
                                @error('about_store_image_title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="about_store_image_subtitle">About Store Card Subtitle</label>
                                <input type="text" class="form-control" id="about_store_image_subtitle" name="about_store_image_subtitle" value="{{ old('about_store_image_subtitle', $settings['about_store_image_subtitle'] ?? '') }}">
                                @error('about_store_image_subtitle')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Home Hero Content</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_kicker">Hero Kicker</label>
                                <input type="text" class="form-control" id="hero_kicker" name="hero_kicker" value="{{ old('hero_kicker', $settings['hero_kicker'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_title">Hero Title</label>
                                <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="hero_description">Hero Description</label>
                                <textarea class="form-control" id="hero_description" name="hero_description" rows="3">{{ old('hero_description', $settings['hero_description'] ?? '') }}</textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_button_primary">Primary Button Label</label>
                                <input type="text" class="form-control" id="hero_button_primary" name="hero_button_primary" value="{{ old('hero_button_primary', $settings['hero_button_primary'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_button_secondary">Secondary Button Label</label>
                                <input type="text" class="form-control" id="hero_button_secondary" name="hero_button_secondary" value="{{ old('hero_button_secondary', $settings['hero_button_secondary'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <h6 class="mt-3">Hero Highlights</h6>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_1_title">Highlight 1 Title</label>
                                <input type="text" class="form-control" id="hero_highlight_1_title" name="hero_highlight_1_title" value="{{ old('hero_highlight_1_title', $settings['hero_highlight_1_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_1_description">Highlight 1 Description</label>
                                <input type="text" class="form-control" id="hero_highlight_1_description" name="hero_highlight_1_description" value="{{ old('hero_highlight_1_description', $settings['hero_highlight_1_description'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_2_title">Highlight 2 Title</label>
                                <input type="text" class="form-control" id="hero_highlight_2_title" name="hero_highlight_2_title" value="{{ old('hero_highlight_2_title', $settings['hero_highlight_2_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_2_description">Highlight 2 Description</label>
                                <input type="text" class="form-control" id="hero_highlight_2_description" name="hero_highlight_2_description" value="{{ old('hero_highlight_2_description', $settings['hero_highlight_2_description'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_3_title">Highlight 3 Title</label>
                                <input type="text" class="form-control" id="hero_highlight_3_title" name="hero_highlight_3_title" value="{{ old('hero_highlight_3_title', $settings['hero_highlight_3_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="hero_highlight_3_description">Highlight 3 Description</label>
                                <input type="text" class="form-control" id="hero_highlight_3_description" name="hero_highlight_3_description" value="{{ old('hero_highlight_3_description', $settings['hero_highlight_3_description'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Home Section Content</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_offer_kicker">Offer Kicker</label>
                                <input type="text" class="form-control" id="home_offer_kicker" name="home_offer_kicker" value="{{ old('home_offer_kicker', $settings['home_offer_kicker'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_offer_title">Offer Title</label>
                                <input type="text" class="form-control" id="home_offer_title" name="home_offer_title" value="{{ old('home_offer_title', $settings['home_offer_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_category_kicker">Category Kicker</label>
                                <input type="text" class="form-control" id="home_category_kicker" name="home_category_kicker" value="{{ old('home_category_kicker', $settings['home_category_kicker'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_category_title">Category Title</label>
                                <input type="text" class="form-control" id="home_category_title" name="home_category_title" value="{{ old('home_category_title', $settings['home_category_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_kicker">Confidence Kicker</label>
                                <input type="text" class="form-control" id="home_confidence_kicker" name="home_confidence_kicker" value="{{ old('home_confidence_kicker', $settings['home_confidence_kicker'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_title">Confidence Title</label>
                                <input type="text" class="form-control" id="home_confidence_title" name="home_confidence_title" value="{{ old('home_confidence_title', $settings['home_confidence_title'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="home_confidence_description">Confidence Description</label>
                                <textarea class="form-control" id="home_confidence_description" name="home_confidence_description" rows="3">{{ old('home_confidence_description', $settings['home_confidence_description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Home Trust & Social Proof</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_1_title">Trust Card 1 Title</label>
                                <input type="text" class="form-control" id="trust_card_1_title" name="trust_card_1_title" value="{{ old('trust_card_1_title', $settings['trust_card_1_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_1_description">Trust Card 1 Description</label>
                                <input type="text" class="form-control" id="trust_card_1_description" name="trust_card_1_description" value="{{ old('trust_card_1_description', $settings['trust_card_1_description'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_2_title">Trust Card 2 Title</label>
                                <input type="text" class="form-control" id="trust_card_2_title" name="trust_card_2_title" value="{{ old('trust_card_2_title', $settings['trust_card_2_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_2_description">Trust Card 2 Description</label>
                                <input type="text" class="form-control" id="trust_card_2_description" name="trust_card_2_description" value="{{ old('trust_card_2_description', $settings['trust_card_2_description'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_3_title">Trust Card 3 Title</label>
                                <input type="text" class="form-control" id="trust_card_3_title" name="trust_card_3_title" value="{{ old('trust_card_3_title', $settings['trust_card_3_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_3_description">Trust Card 3 Description</label>
                                <input type="text" class="form-control" id="trust_card_3_description" name="trust_card_3_description" value="{{ old('trust_card_3_description', $settings['trust_card_3_description'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_4_title">Trust Card 4 Title</label>
                                <input type="text" class="form-control" id="trust_card_4_title" name="trust_card_4_title" value="{{ old('trust_card_4_title', $settings['trust_card_4_title'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="trust_card_4_description">Trust Card 4 Description</label>
                                <input type="text" class="form-control" id="trust_card_4_description" name="trust_card_4_description" value="{{ old('trust_card_4_description', $settings['trust_card_4_description'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="home_review_quote">Review Quote</label>
                                <textarea class="form-control" id="home_review_quote" name="home_review_quote" rows="2">{{ old('home_review_quote', $settings['home_review_quote'] ?? '') }}</textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_review_author">Review Author</label>
                                <input type="text" class="form-control" id="home_review_author" name="home_review_author" value="{{ old('home_review_author', $settings['home_review_author'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_review_support_label">Support Label</label>
                                <input type="text" class="form-control" id="home_review_support_label" name="home_review_support_label" value="{{ old('home_review_support_label', $settings['home_review_support_label'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_review_support_button">Support Button Text</label>
                                <input type="text" class="form-control" id="home_review_support_button" name="home_review_support_button" value="{{ old('home_review_support_button', $settings['home_review_support_button'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_item_1">Confidence Item 1</label>
                                <input type="text" class="form-control" id="home_confidence_item_1" name="home_confidence_item_1" value="{{ old('home_confidence_item_1', $settings['home_confidence_item_1'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_item_2">Confidence Item 2</label>
                                <input type="text" class="form-control" id="home_confidence_item_2" name="home_confidence_item_2" value="{{ old('home_confidence_item_2', $settings['home_confidence_item_2'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_item_3">Confidence Item 3</label>
                                <input type="text" class="form-control" id="home_confidence_item_3" name="home_confidence_item_3" value="{{ old('home_confidence_item_3', $settings['home_confidence_item_3'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="home_confidence_item_4">Confidence Item 4</label>
                                <input type="text" class="form-control" id="home_confidence_item_4" name="home_confidence_item_4" value="{{ old('home_confidence_item_4', $settings['home_confidence_item_4'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Shop Page Content</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="shop_kicker">Shop Kicker</label>
                                <input type="text" class="form-control" id="shop_kicker" name="shop_kicker" value="{{ old('shop_kicker', $settings['shop_kicker'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="shop_title">Shop Title</label>
                                <input type="text" class="form-control" id="shop_title" name="shop_title" value="{{ old('shop_title', $settings['shop_title'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="shop_description">Shop Description</label>
                                <textarea class="form-control" id="shop_description" name="shop_description" rows="3">{{ old('shop_description', $settings['shop_description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm settings-card">
                    <div class="card-header">Contact Page and Social Links</div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="contact_title">Contact Page Title</label>
                                <input type="text" class="form-control" id="contact_title" name="contact_title" value="{{ old('contact_title', $settings['contact_title'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="contact_description">Contact Page Description</label>
                                <textarea class="form-control" id="contact_description" name="contact_description" rows="3">{{ old('contact_description', $settings['contact_description'] ?? '') }}</textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="whatsapp">WhatsApp Number</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="business_hours">Business Hours</label>
                                <input type="text" class="form-control" id="business_hours" name="business_hours" value="{{ old('business_hours', $settings['business_hours'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $settings['address'] ?? '') }}">
                            </div>
                            @foreach(['facebook_url' => 'Facebook URL', 'instagram_url' => 'Instagram URL', 'tiktok_url' => 'TikTok URL', 'youtube_url' => 'YouTube URL'] as $key => $label)
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="{{ $key }}">{{ $label }}</label>
                                    <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="settings-actions">
                            <button type="submit" class="btn btn-primary">Save Storefront Settings</button>
                            <p class="settings-action-note mt-2">All settings are saved instantly when you click the button.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function getContrastColor(hex) {
        const cleanHex = hex.replace('#', '');
        const r = parseInt(cleanHex.substring(0, 2), 16);
        const g = parseInt(cleanHex.substring(2, 4), 16);
        const b = parseInt(cleanHex.substring(4, 6), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

        return luminance > 0.55 ? '#111827' : '#ffffff';
    }

    function updatePreview(input) {
        const preview = document.getElementById('preview-' + input.id);
        const textInput = document.querySelector('.color-text[data-target="' + input.id + '"]');

        if (!preview) return;

        preview.style.backgroundColor = input.value;
        preview.style.color = getContrastColor(input.value);
        preview.querySelector('.color-code').textContent = input.value.toUpperCase();

        if (textInput) {
            textInput.value = input.value.toUpperCase();
        }
    }

    document.querySelectorAll('input[type="color"]').forEach(input => {
        updatePreview(input);
        input.addEventListener('input', () => updatePreview(input));
    });

    document.querySelectorAll('.color-text').forEach(input => {
        input.addEventListener('change', () => {
            const target = document.getElementById(input.dataset.target);
            if (!target || !/^#[0-9A-Fa-f]{6}$/.test(input.value)) return;
            target.value = input.value;
            updatePreview(target);
        });
    });
</script>
@endsection
