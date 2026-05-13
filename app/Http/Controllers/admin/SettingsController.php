<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\AdminController;
use App\Services\SystemSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function edit()
    {
        $settings = SystemSettings::load();
        $colors = array_intersect_key($settings, SystemSettings::defaultColors());

        return view('admin.settings', compact('settings', 'colors'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primary' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'secondary' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'site_name' => ['required', 'string', 'max:80'],
            'site_tagline' => ['required', 'string', 'max:160'],
            'site_description' => ['required', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'about_store_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'about_store_image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'hero_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:51200'],
            'about_store_image_title' => ['required', 'string', 'max:120'],
            'about_store_image_subtitle' => ['required', 'string', 'max:180'],
            'hero_kicker' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:120'],
            'hero_description' => ['required', 'string', 'max:300'],
            'home_offer_kicker' => ['required', 'string', 'max:120'],
            'home_offer_title' => ['required', 'string', 'max:180'],
            'home_confidence_kicker' => ['required', 'string', 'max:120'],
            'home_confidence_title' => ['required', 'string', 'max:180'],
            'home_confidence_description' => ['required', 'string', 'max:400'],
            'shop_kicker' => ['required', 'string', 'max:120'],
            'shop_title' => ['required', 'string', 'max:160'],
            'shop_description' => ['required', 'string', 'max:300'],
            'contact_title' => ['required', 'string', 'max:120'],
            'contact_description' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:40'],
            'whatsapp' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:120'],
            'address' => ['required', 'string', 'max:180'],
            'business_hours' => ['required', 'string', 'max:120'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $settings = SystemSettings::load();
        $data = $validator->validated();

        // Track if any images were updated
        $imagesUpdated = false;

        foreach (['logo' => 'logo_path', 'hero_image' => 'hero_image_path', 'about_store_image' => 'about_store_image_path', 'about_store_image_2' => 'about_store_image_2_path', 'hero_video' => 'hero_video_path'] as $input => $settingKey) {
            unset($data[$input]);

            if (!$request->hasFile($input)) {
                continue;
            }

            if (!empty($settings[$settingKey])) {
                Storage::disk('public')->delete($settings[$settingKey]);
            }

            $data[$settingKey] = $request->file($input)->store('system', 'public');
            $imagesUpdated = true;
        }

        // Add cache-buster timestamp if images were updated
        if ($imagesUpdated) {
            $data['images_cache_buster'] = time();
        }

        SystemSettings::save(array_merge($settings, $data));

        // Clear all caches to force refresh
        Cache::flush();

        return redirect()->route('admin.settings')->with('success', 'System settings updated successfully.');
    }
}
