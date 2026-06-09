<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    /**
     * Serve images from storage as fallback
     * Fallback route for when symlink is not available
     */
    public function serve(string $path)
    {
        $decodedPath = base64_decode($path, true);

        if (!$decodedPath) {
            abort(400, 'Invalid image path');
        }

        // Security: prevent directory traversal
        if (strpos($decodedPath, '..') !== false || strpos($decodedPath, '/') === 0) {
            abort(403, 'Invalid path');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($decodedPath)) {
            abort(404, 'Image not found');
        }

        $filePath = storage_path('app/public/' . $decodedPath);

        if (!File::exists($filePath)) {
            abort(404, 'Image not found');
        }

        $file = File::get($filePath);
        $type = File::mimeType($filePath);

        return response($file, 200)->header('Content-Type', $type);
    }
}
