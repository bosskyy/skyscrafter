<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with fallback support
     * Works even if storage symlink is not created yet
     */
    public static function url(?string $imagePath, ?string $default = null): string
    {
        if (!$imagePath) {
            return $default ? asset($default) : asset('images/logo_sky.png');
        }

        // Check if using new storage path format (contains 'products/')
        if (strpos($imagePath, 'products/') !== false || strpos($imagePath, 'storage/') === false) {
            // Try storage URL first
            if (Storage::disk('public')->exists($imagePath)) {
                return asset('storage/' . $imagePath);
            }

            // Fallback: try direct access if symlink not ready
            $storagePath = storage_path('app/public/' . $imagePath);
            if (File::exists($storagePath)) {
                // Try to serve directly from storage if symlink failed
                return url('api/images/' . base64_encode($imagePath));
            }
        }

        // If old format (just filename), check both locations
        $pathsToTry = [
            public_path('images/' . $imagePath),
            storage_path('app/public/products/' . $imagePath),
        ];

        foreach ($pathsToTry as $path) {
            if (File::exists($path)) {
                if (strpos($path, 'storage') !== false) {
                    return asset('storage/products/' . $imagePath);
                } else {
                    return asset('images/' . $imagePath);
                }
            }
        }

        // Return default or placeholder
        return $default ? asset($default) : asset('images/logo_sky.png');
    }

    /**
     * Check if image exists in storage
     */
    public static function exists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        return Storage::disk('public')->exists($imagePath) ||
               File::exists(public_path('images/' . $imagePath));
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
        ];
    }
}
