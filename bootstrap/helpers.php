<?php

// File: bootstrap/helpers.php
// Register this in AppServiceProvider or Kernel

if (!function_exists('imageUrl')) {
    /**
     * Get image URL with fallback support
     * Usage: imageUrl($product->image) or imageUrl($product->image, 'images/placeholder.png')
     */
    function imageUrl(?string $imagePath, ?string $default = null): string
    {
        return \App\Helpers\ImageHelper::url($imagePath, $default);
    }
}

if (!function_exists('imagePath')) {
    /**
     * Check if image path exists
     */
    function imagePath(?string $imagePath): bool
    {
        return \App\Helpers\ImageHelper::exists($imagePath);
    }
}

if (!function_exists('symlinkStatus')) {
    /**
     * Get symlink status for debugging
     */
    function symlinkStatus(): array
    {
        return \App\Helpers\ImageHelper::getSymlinkStatus();
    }
}
