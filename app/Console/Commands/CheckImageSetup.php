<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckImageSetup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'images:check {--fix : Auto-fix issues}';

    /**
     * The console command description.
     */
    protected $description = 'Check and diagnose image setup issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('====================================');
        $this->info('Image Setup Diagnostic Tool');
        $this->info('====================================');
        $this->newLine();

        $link = public_path('storage');
        $target = storage_path('app/public');
        $productsDir = storage_path('app/public/products');

        // Check 1: Storage directory exists
        $this->info('[1] Checking storage directory...');
        if (is_dir($target)) {
            $this->line('    ✓ Storage directory exists: ' . $target);
        } else {
            $this->error('    ✗ Storage directory not found!');
            if ($this->option('fix')) {
                File::makeDirectory($target, 0755, true);
                $this->info('    ✓ Created storage directory');
            }
        }
        $this->newLine();

        // Check 2: Symlink exists
        $this->info('[2] Checking symbolic link...');
        if (is_link($link)) {
            $this->line('    ✓ Symlink exists: ' . $link . ' -> ' . readlink($link));
        } else {
            $this->error('    ✗ Symlink does not exist!');
            if ($this->option('fix')) {
                try {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        shell_exec(sprintf('mklink /D "%s" "%s"', $link, $target));
                    } else {
                        symlink($target, $link);
                    }
                    $this->info('    ✓ Symlink created successfully');
                } catch (\Exception $e) {
                    $this->error('    ✗ Failed to create symlink: ' . $e->getMessage());
                }
            }
        }
        $this->newLine();

        // Check 3: Products directory
        $this->info('[3] Checking products directory...');
        if (is_dir($productsDir)) {
            $fileCount = count(File::files($productsDir));
            $this->line('    ✓ Products directory exists with ' . $fileCount . ' files');
        } else {
            $this->error('    ✗ Products directory not found!');
            if ($this->option('fix')) {
                File::makeDirectory($productsDir, 0755, true);
                $this->info('    ✓ Created products directory');
            }
        }
        $this->newLine();

        // Check 4: Permissions
        $this->info('[4] Checking permissions...');
        $permissions = substr(sprintf('%o', fileperms($target)), -4);
        $this->line('    Storage permissions: ' . $permissions);
        $this->line('    Storage readable: ' . (is_readable($target) ? '✓ Yes' : '✗ No'));
        $this->line('    Storage writable: ' . (is_writable($target) ? '✓ Yes' : '✗ No'));
        $this->newLine();

        // Check 5: Old images
        $this->info('[5] Checking for legacy images...');
        $oldImagesDir = public_path('images');
        if (is_dir($oldImagesDir)) {
            $oldFiles = File::files($oldImagesDir);
            $count = count($oldFiles);
            if ($count > 0) {
                $this->line('    Found ' . $count . ' legacy images in public/images/');
                if ($this->option('fix')) {
                    foreach ($oldFiles as $file) {
                        $filename = $file->getFilename();
                        File::copy($file->getPathname(), $productsDir . '/' . $filename);
                    }
                    $this->info('    ✓ Copied ' . $count . ' legacy images to storage');
                }
            } else {
                $this->line('    ✓ No legacy images found');
            }
        } else {
            $this->line('    ✓ No legacy images directory');
        }
        $this->newLine();

        // Check 6: Database images
        $this->info('[6] Checking database for product images...');
        $products = \App\Models\Product::whereNotNull('image')->get();
        if ($products->count() > 0) {
            $this->line('    Found ' . $products->count() . ' products with images');
            foreach ($products as $product) {
                $exists = File::exists(storage_path('app/public/' . $product->image)) ||
                         File::exists(public_path('images/' . $product->image));
                $status = $exists ? '✓' : '✗';
                $this->line('    ' . $status . ' ' . $product->name . ': ' . $product->image);
            }
        } else {
            $this->line('    No products with images');
        }
        $this->newLine();

        // Summary
        $this->info('====================================');
        $this->info('Summary');
        $this->info('====================================');
        if (is_link($link) && is_dir($productsDir)) {
            $this->info('✓ All systems are go! Images should be working.');
        } else {
            $this->error('⚠ Some issues found. Run: php artisan images:check --fix');
        }
    }
}
