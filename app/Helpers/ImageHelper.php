<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with multiple fallback locations
     * Priority: public/images → storage → default
     */
    public static function url(?string $imagePath, ?string $default = null): string
    {
        if (!$imagePath) {
            return $default ? asset($default) : asset('images/logo_sky.png');
        }

        // PRIORITY 1: Check public/images (for static images like logo, layanan, etc)
        $publicPath = public_path('images/' . $imagePath);
        if (File::exists($publicPath)) {
            return asset('images/' . $imagePath);
        }

        // PRIORITY 2: Check if using storage path format (products/xxx or storage/products/xxx)
        if (strpos($imagePath, 'products/') !== false) {
            // If it's a full storage path, try to serve it
            if (Storage::disk('public')->exists($imagePath)) {
                return asset('storage/' . $imagePath);
            }
        }

        // PRIORITY 3: Try storage direct (if just filename)
        if (Storage::disk('public')->exists('products/' . $imagePath)) {
            return asset('storage/products/' . $imagePath);
        }

        // PRIORITY 4: Try storage path direct
        $storagePath = storage_path('app/public/' . $imagePath);
        if (File::exists($storagePath)) {
            return asset('storage/' . $imagePath);
        }

        // PRIORITY 5: Fallback to API route (if symlink failed)
        if ($imagePath) {
            return url('api/images/' . base64_encode($imagePath));
        }

        // Final default
        return $default ? asset($default) : asset('images/logo_sky.png');
    }

    /**
     * Get simple image URL from public/images
     * Use this for static images (logo, etc)
     */
    public static function publicImage(?string $filename, ?string $default = null): string
    {
        if (!$filename) {
            return $default ? asset($default) : asset('images/logo_sky.png');
        }

        $path = public_path('images/' . $filename);
        if (File::exists($path)) {
            return asset('images/' . $filename);
        }

        return $default ? asset($default) : asset('images/logo_sky.png');
    }

    /**
     * Check if image exists in any location
     */
    public static function exists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        return File::exists(public_path('images/' . $imagePath)) ||
               Storage::disk('public')->exists($imagePath) ||
               File::exists(storage_path('app/public/' . $imagePath));
    }

    /**
     * Get symlink status for debugging
     */
    public static function getSymlinkStatus(): array
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        return [
            'symlink_exists' => is_link($link),
            'target_exists' => is_dir($target),
            'link_path' => $link,
            'target_path' => $target,
            'is_readable' => is_readable($target),
            'php_os' => PHP_OS,
            'public_images_dir' => public_path('images'),
            'public_images_readable' => is_readable(public_path('images')),
        ];
    }
}

