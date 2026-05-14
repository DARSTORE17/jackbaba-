<?php

use App\Services\MediaStorage;

if (!function_exists('media_url')) {
    function media_url(?string $path, ?string $fallback = null, string $cacheBuster = ''): ?string
    {
        return MediaStorage::url($path, $fallback, $cacheBuster);
    }
}

if (!function_exists('media_exists')) {
    function media_exists(?string $path): bool
    {
        return MediaStorage::exists($path);
    }
}
