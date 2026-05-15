<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\AdminController;
use App\Services\MediaStorage;
use App\Services\SystemSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            'hero_button_primary' => ['required', 'string', 'max:60'],
            'hero_button_secondary' => ['required', 'string', 'max:60'],
            'hero_highlight_1_title' => ['required', 'string', 'max:80'],
            'hero_highlight_1_description' => ['required', 'string', 'max:120'],
            'hero_highlight_2_title' => ['required', 'string', 'max:80'],
            'hero_highlight_2_description' => ['required', 'string', 'max:120'],
            'hero_highlight_3_title' => ['required', 'string', 'max:80'],
            'hero_highlight_3_description' => ['required', 'string', 'max:120'],
            'home_category_kicker' => ['required', 'string', 'max:120'],
            'home_category_title' => ['required', 'string', 'max:180'],
            'trust_card_1_title' => ['required', 'string', 'max:80'],
            'trust_card_1_description' => ['required', 'string', 'max:140'],
            'trust_card_2_title' => ['required', 'string', 'max:80'],
            'trust_card_2_description' => ['required', 'string', 'max:140'],
            'trust_card_3_title' => ['required', 'string', 'max:80'],
            'trust_card_3_description' => ['required', 'string', 'max:140'],
            'trust_card_4_title' => ['required', 'string', 'max:80'],
            'trust_card_4_description' => ['required', 'string', 'max:140'],
            'home_review_quote' => ['required', 'string', 'max:300'],
            'home_review_author' => ['required', 'string', 'max:120'],
            'home_review_support_label' => ['required', 'string', 'max:80'],
            'home_review_support_button' => ['required', 'string', 'max:80'],
            'home_confidence_item_1' => ['required', 'string', 'max:140'],
            'home_confidence_item_2' => ['required', 'string', 'max:140'],
            'home_confidence_item_3' => ['required', 'string', 'max:140'],
            'home_confidence_item_4' => ['required', 'string', 'max:140'],
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
                MediaStorage::delete($settings[$settingKey]);
            }

            $data[$settingKey] = MediaStorage::upload($request->file($input), 'system', $input === 'hero_video' ? 'video' : 'image');
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
