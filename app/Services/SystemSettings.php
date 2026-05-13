<?php

namespace App\Services;

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

        $directory = dirname(self::getPath());
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(self::getPath(), json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
