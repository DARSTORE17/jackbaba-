@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Storefront Settings</h2>
            <p class="text-muted mb-0">Manage storefront colors, brand text, hero content, shop content, and contact details from one place.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row gy-4">
            <div class="col-12 col-xl-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Color Palette</h5>

                        <div class="row gy-3">
                            @foreach(['primary' => 'Primary Brand Color', 'secondary' => 'Secondary Brand Color'] as $key => $label)
                                <div class="col-12 col-md-6">
                                    <label for="{{ $key }}" class="form-label">{{ $label }} Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $colors[$key] ?? '#000000') }}">
                                        <input type="text" class="form-control color-text" data-target="{{ $key }}" value="{{ old($key, $colors[$key] ?? '#000000') }}">
                                    </div>
                                    @error($key)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Live Preview</h5>
                        <div class="row gx-3 gy-3">
                            @foreach(['primary' => 'Primary', 'secondary' => 'Secondary'] as $key => $label)
                                <div class="col-12 col-sm-6">
                                    <div id="preview-{{ $key }}" class="rounded-3 p-3 text-center color-preview" data-color="{{ old($key, $colors[$key] ?? '#000000') }}">
                                        <div>
                                            <div class="fw-bold">{{ $label }}</div>
                                            <div class="small color-code">{{ old($key, $colors[$key] ?? '#000000') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Brand Content</h5>

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

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Storefront Media</h5>

                        <div class="row gy-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="logo">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @if(!empty($settings['logo_path']))
                                    <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Current logo" class="img-fluid rounded mt-2" style="max-height: 90px;">
                                @endif
                                @error('logo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="hero_image">Hero Image</label>
                                <input type="file" class="form-control" id="hero_image" name="hero_image" accept="image/*">
                                @if(!empty($settings['hero_image_path']))
                                    <img src="{{ asset('storage/' . $settings['hero_image_path']) }}" alt="Current hero image" class="img-fluid rounded mt-2" style="max-height: 90px;">
                                @endif
                                @error('hero_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="hero_video">Hero Background Video</label>
                                <input type="file" class="form-control" id="hero_video" name="hero_video" accept="video/mp4,video/webm,video/ogg">
                                @if(!empty($settings['hero_video_path']))
                                    <video src="{{ asset('storage/' . $settings['hero_video_path']) }}" class="w-100 rounded mt-2" style="max-height: 90px;" muted controls></video>
                                @endif
                                @error('hero_video')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Home Hero Content</h5>

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
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Home Section Content</h5>

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

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Shop Page Content</h5>

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

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Contact and Social Links</h5>

                        <div class="row gy-3">
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

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Storefront Settings</button>
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
