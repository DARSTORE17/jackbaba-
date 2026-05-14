<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaStorage
{
    public static function enabled(): bool
    {
        return config('filesystems.default') === 'cloudinary'
            && filled(config('services.cloudinary.cloud_name'))
            && filled(config('services.cloudinary.api_key'))
            && filled(config('services.cloudinary.api_secret'));
    }

    public static function upload(UploadedFile $file, string $folder, string $resourceType = 'auto'): string
    {
        if (!self::enabled()) {
            return $file->store($folder, 'public');
        }

        $timestamp = time();
        $params = [
            'folder' => trim($folder, '/'),
            'timestamp' => $timestamp,
        ];

        $response = self::request($resourceType . '/upload', array_merge($params, [
            'file' => new \CURLFile(
                $file->getRealPath(),
                $file->getMimeType() ?: 'application/octet-stream',
                $file->getClientOriginalName()
            ),
            'api_key' => config('services.cloudinary.api_key'),
            'signature' => self::signature($params),
        ]), true);

        return $response['secure_url'] ?? throw new RuntimeException('Cloudinary upload did not return a secure URL.');
    }

    public static function delete(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        if (!self::isCloudinaryUrl($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        if (!self::enabled()) {
            return;
        }

        $asset = self::cloudinaryAsset($path);

        if (!$asset) {
            return;
        }

        try {
            $params = [
                'public_id' => $asset['public_id'],
                'timestamp' => time(),
            ];

            self::request($asset['resource_type'] . '/destroy', array_merge($params, [
                'api_key' => config('services.cloudinary.api_key'),
                'signature' => self::signature($params),
            ]));
        } catch (\Throwable $e) {
            Log::warning('Cloudinary delete failed', [
                'path' => $path,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public static function url(?string $path, ?string $fallback = null, string $cacheBuster = ''): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path . $cacheBuster;
        }

        return asset('storage/' . ltrim($path, '/')) . $cacheBuster;
    }

    public static function exists(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return true;
        }

        return Storage::disk('public')->exists($path);
    }

    private static function request(string $action, array $fields, bool $multipart = false): array
    {
        if (!extension_loaded('curl')) {
            throw new RuntimeException('PHP cURL extension is required for Cloudinary uploads.');
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/{$action}";
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $multipart ? $fields : http_build_query($fields),
            CURLOPT_TIMEOUT => 60,
        ]);

        $body = curl_exec($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($body === false) {
            throw new RuntimeException('Cloudinary request failed: ' . $error);
        }

        $data = json_decode($body, true);

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException($data['error']['message'] ?? 'Cloudinary request failed.');
        }

        return is_array($data) ? $data : [];
    }

    private static function signature(array $params): string
    {
        ksort($params);

        $payload = collect($params)
            ->reject(fn ($value) => blank($value))
            ->map(fn ($value, $key) => $key . '=' . $value)
            ->implode('&');

        return sha1($payload . config('services.cloudinary.api_secret'));
    }

    private static function isCloudinaryUrl(string $path): bool
    {
        return str_contains($path, 'res.cloudinary.com');
    }

    private static function cloudinaryAsset(string $url): ?array
    {
        $parts = parse_url($url);
        $path = $parts['path'] ?? '';

        if (!preg_match('#/(image|video|raw)/upload/(.+)$#', $path, $matches)) {
            return null;
        }

        $segments = explode('/', $matches[2]);
        $versionIndex = null;

        foreach ($segments as $index => $segment) {
            if (preg_match('/^v\d+$/', $segment)) {
                $versionIndex = $index;
                break;
            }
        }

        $publicIdSegments = $versionIndex === null
            ? $segments
            : array_slice($segments, $versionIndex + 1);

        if (empty($publicIdSegments)) {
            return null;
        }

        $publicId = preg_replace('/\.[^.\/]+$/', '', implode('/', $publicIdSegments));

        return [
            'resource_type' => $matches[1],
            'public_id' => $publicId,
        ];
    }
}
