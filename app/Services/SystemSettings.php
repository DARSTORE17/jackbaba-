<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemSettings
{
    public static function getPath(): string
    {
        return storage_path('app/system-settings.json');
    }

    public static function defaultColors(): array
    {
        return [
            'primary' => '#4fbb89',
            'secondary' => '#96d6ab',
        ];
    }

    public static function defaultContent(): array
    {
        return [
            'site_name' => 'Bravus Market',
            'site_tagline' => 'Premium electronics, phones, laptops, and accessories',
            'site_description' => 'Bravus Market brings trusted, original electronics to Tanzania with fast delivery and great prices.',
            'logo_path' => '',
            'hero_video_path' => '',
            'hero_image_path' => '',
            'about_store_image_path' => '',
            'about_store_image_2_path' => '',
            'about_store_image_title' => 'Our Store Image',
            'about_store_image_subtitle' => 'Original electronics, trusted service, and fast support.',
            'images_cache_buster' => 0, // Incremented when images change
            'hero_kicker' => 'Premium electronics in Tanzania',
            'hero_title' => 'Bravus Market',
            'hero_description' => 'Shop original phones, laptops, and smart accessories with a clean, trusted, and fast buying experience.',
            'home_offer_kicker' => 'What we offer',
            'home_offer_title' => 'Everything feels simple from browsing to checkout.',
            'home_confidence_kicker' => 'Why choose us?',
            'home_confidence_title' => 'Original products, calm service, and delivery you can trust.',
            'home_confidence_description' => 'We keep the shopping experience clear and friendly, so customers can choose confidently and get support when they need it.',
            'hero_button_primary' => 'Shop Now',
            'hero_button_secondary' => 'Explore Categories',
            'hero_highlight_1_title' => 'Original Products',
            'hero_highlight_1_description' => 'Verified electronics',
            'hero_highlight_2_title' => 'Fast Delivery',
            'hero_highlight_2_description' => 'Across Tanzania',
            'hero_highlight_3_title' => 'Warranty Included',
            'hero_highlight_3_description' => 'Peace of mind on every order',
            'home_category_kicker' => 'Shop by category',
            'home_category_title' => 'Browse top electronics categories',
            'trust_card_1_title' => 'Fast Delivery',
            'trust_card_1_description' => 'Nationwide delivery across Tanzania.',
            'trust_card_2_title' => 'Secure Payments',
            'trust_card_2_description' => 'Pay with M-Pesa, bank transfer, or card safely.',
            'trust_card_3_title' => 'Warranty Included',
            'trust_card_3_description' => 'Warranty support for eligible devices.',
            'trust_card_4_title' => 'Easy Returns',
            'trust_card_4_description' => 'Simple returns and transparent refund policy.',
            'home_review_quote' => 'Fast delivery, original devices, and payment was easy. My phone arrived the next day with a warranty receipt.',
            'home_review_author' => 'Amina, Dar es Salaam',
            'home_review_support_label' => 'Need help now?',
            'home_review_support_button' => 'WhatsApp support',
            'home_confidence_item_1' => 'Fast delivery to all regions of Tanzania',
            'home_confidence_item_2' => 'Secure payment experience with M-Pesa & card options',
            'home_confidence_item_3' => 'Warranty included on eligible devices',
            'home_confidence_item_4' => 'Easy returns and transparent refund policy',
            'shop_kicker' => 'Bravus Market shop',
            'shop_title' => 'Shop electronics with confidence.',
            'shop_description' => 'Browse original phones, laptops, accessories, and smart tech with a soft, simple shopping experience.',
            'contact_title' => 'Contact Us',
            'contact_description' => 'Need help choosing a product, tracking an order, or speaking with our team? Reach us through any channel below.',
            'phone' => '+255 754 321 987',
            'whatsapp' => '255754321987',
            'email' => 'support@bravusmarket.co.tz',
            'address' => 'Dar es Salaam, Tanzania',
            'business_hours' => 'Mon - Sat: 9:00 AM - 7:00 PM',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'tiktok_url' => '#',
            'youtube_url' => '#',
        ];
    }

    public static function defaults(): array
    {
        return array_merge(self::defaultColors(), self::defaultContent());
    }

    public static function load(): array
    {
        if (self::databaseReady()) {
            $data = DB::table('system_settings')->pluck('value', 'key')->all();

            if (!empty($data)) {
                return array_merge(self::defaults(), $data);
            }
        }

        $path = self::getPath();

        if (!file_exists($path)) {
            self::save(self::defaults());
            return self::defaults();
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return self::defaults();
        }

        return array_merge(self::defaults(), $data);
    }

    public static function save(array $data): void
    {
        $defaults = self::defaults();
        $allowed = array_intersect_key($data, $defaults);
        $settings = array_merge($defaults, $allowed);

        if (self::databaseReady()) {
            $now = now();
            $rows = collect($settings)->map(fn ($value, $key) => [
                'key' => $key,
                'value' => is_scalar($value) || is_null($value) ? (string) $value : json_encode($value),
                'created_at' => $now,
                'updated_at' => $now,
            ])->values()->all();

            DB::table('system_settings')->upsert($rows, ['key'], ['value', 'updated_at']);
            return;
        }

        $directory = dirname(self::getPath());
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(self::getPath(), json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private static function databaseReady(): bool
    {
        try {
            return Schema::hasTable('system_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
