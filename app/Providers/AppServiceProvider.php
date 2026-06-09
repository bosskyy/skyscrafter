<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-create storage symlink if it doesn't exist
        $this->ensureStorageSymlink();

        // Load helpers
        require_once base_path('bootstrap/helpers.php');
    }

    /**
     * Ensure storage symlink exists for image serving
     */
    private function ensureStorageSymlink(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        // If symlink doesn't exist or is broken, create it
        if (!is_link($link)) {
            try {
                // Windows check
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Windows: use mklink command
                    if (!file_exists($link)) {
                        $cmd = sprintf('mklink /D "%s" "%s"', $link, $target);
                        shell_exec($cmd);
                    }
                } else {
                    // Unix-like: use symlink
                    if (file_exists($link)) {
                        File::delete($link);
                    }
                    symlink($target, $link);
                }
            } catch (\Exception $e) {
                \Log::warning('Could not create storage symlink: ' . $e->getMessage());
            }
        }
    }
}
